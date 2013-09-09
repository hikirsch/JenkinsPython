<?php
/**
 * The BasePageController provides the abiltiy to render pages. This class also provides for SEO if the SEOManager::Show( $this->seo ); is used in the template.
 */

namespace baseProject\Controllers;

use \Savant3;
use \baseProject\Managers\SEOManager;

/**
 * A wrapper base class for the template engine.
 */
class BasePageController extends BaseController {
	/**
	 * Savant3, the template engine itself
	 * @var \Savant3
	 */
	private $templateEngine = null;

	/**
	 * The master template view name to use for this page. Pages build outside in and any other templates will
	 * render inside this one
	 * @var string
	 */
	private $masterTemplate = null;

	/**
	 * Any attached templates that will be rendered
	 * @var array
	 */
	private $attachedTemplates = array();

	/**
	 * Savant3 template engine configuration - http://phpsavant.com/api/Savant3/Savant3/Savant3.html#$__config
	 * @var array
	 */
	private $config = array(
		'template_path' => APP_VIEWS_PATH
	);

	/**
	 * The SEO node from seo-meta.xml in config
	 * @var \SimpleXMLElement
	 */
	protected $seo = null;

	/**
	 * A base page is bound to a specific kind of master template
	 * @param string $masterTemplate the master template for this page
	 */
	public function __construct( $masterTemplate ) {
		$this->masterTemplate = $masterTemplate;

		$this->seo = SEOManager::GetElement();

		$this->templateEngine = new Savant3( $this->config );
	}

	/**
	 * This will take the given value and attach it to the template engine using the specified key.
	 * If override is true, then the value will get completely overridden as apposed to just appended.
	 * @param string $key the key to expose in Savant3
	 * @param mixed $value the value of the key
	 * @param bool $overwrite whether or not to overwrite any previous value
	 */
	public function attach( $key, $value, $overwrite = false ) {
		if( gettype($value) == "string" && ! $overwrite ) {
			if( ! isset( $this->templateEngine->$key ) ) {
				$this->templateEngine->$key = '';
			}

			$this->templateEngine->$key .= $value;
		} else {
			$this->templateEngine->$key = $value;
		}
	}

	/**
	 * Attach a given template to a specific content key.
	 * @param string $key the name of a sub-template renderer
	 * @param string $template the template to attach
	 */
	public function attachTemplate( $key, $template ) {
		if( ! isset( $this->attachedTemplates[ $key ] ) ) {
			$this->attachedTemplates[ $key ] = array();
		}

		array_push( $this->attachedTemplates[ $key ], $template );
	}

	/**
	 * Runs before rendering a page. Override as necessary to do anything before template rendering.
	 */
	protected function prerender() { }

	/**
	 * Render the page with passed template and the base template.
	 * @param string|null $templateName the view to render with the base template (if any)
	 */
	public function render( $templateName = null ) {
		$this->prerender();

		$this->attach( "seo", $this->seo );

		if( $templateName != null ) {
			$this->attachTemplate( "pageTemplate", $templateName );
		}

		$this->renderTemplates();

		$this->templateEngine->display( $this->masterTemplate . ".tpl.php" );
	}

	/**
	 * Attach all the values from any sub templates.
	 */
	private function renderTemplates() {
		foreach( $this->attachedTemplates as $key => $allTemplates ) {
			foreach( $allTemplates as $template ) {
				$value = $this->templateEngine->fetch( $template . ".tpl.php" );
				$this->attach( $key, $value );
			}
		}
	}

	/**
	 * Set a token variable and and value to be replaced within the SEO data.
	 * @param string $token the token to replace
	 * @param string $value the value to replace
	 */
	protected function setSeoToken( $token, $value ) {
		// the main title
		$this->seo["title"] = str_replace("%$token%", $value, $this->seo["title"] );

		// meta tags
		$this->seo->meta["description"] = str_replace("%$token%", $value, $this->seo->meta["description"] );
		$this->seo->meta["keywords"] = str_replace("%$token%", $value, $this->seo->meta["keywords"] );

		// open graph
		$this->seo->og["title"] = str_replace("%$token%", $value, $this->seo->og["title"] );
		$this->seo->og["description"] = str_replace("%$token%", $value, $this->seo->og["description"] );
	}
}
