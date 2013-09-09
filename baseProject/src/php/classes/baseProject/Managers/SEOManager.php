<?php
/**
 * This static class that will parse the seo-meta.xml file located in the config folder and also display the
 * necessary HTML.
 */
namespace baseProject\Managers;

use baseProject\Helpers\HtmlHelper;

/**
 * Handles any SEO related task for a page, use SEOManager::GetElement() and SEOManager::Show( $seoElement ) in
 * controller and view respectively. Can also call setSeoToken( $token, $value )
 * @see \baseProject\Controllers\BasePageController::setSeoToken()
 */
class SEOManager {
	/**
	 * Parse through the seo-meta.xml configuration file and get the relevant node for this page.
	 * @return null|\SimpleXMLElement the SEO element for this page.
	 */
	public static function GetElement() {
		$currentRoute = SEOManager::GetPathName();

		$xml = simplexml_load_file( APP_CONF_PATH . "/seo-meta.xml" );
		$currentNode = null;

		// go through every page, as soon as a match is made, break out.
		for( $i = 0; $i < count( $xml->page ); $i++ ) {
			$currentNode = $xml->page[$i];
			$regex = str_replace( "*", "(.*?)", $currentNode["url"] );
			$regex = str_replace( "/", '\/', $regex );

			$success = preg_match( "/^$regex$/", $currentRoute, $matches );
			if( $success ) { break; }
		}

		return $currentNode;
	}


	/**
	 * Get the current URL.
	 * @return string the current URL
	 */
	private static function getPathName() {
		if( array_key_exists( "REDIRECT_URL", $_SERVER ) ) {
			return $_SERVER["REDIRECT_URL"];
		}

		return "/";
	}

	/**
	 * Set the title tag, all SEO meta tags, and open graph tags with the passed data.
	 * @param $seo
	 */
	public static function Show( $seo ) {
?>
	<title><?php echo HtmlHelper::formatForHtml( $seo["title"] ); ?></title>

	<meta property="og:title" content="<?php echo HtmlHelper::formatForHtml( $seo->og["title"] ); ?>" />
	<meta property="og:description" content="<?php echo HtmlHelper::formatForHtml( $seo->og["description"] ); ?>" />
	<meta property="og:image" content="<?php echo HtmlHelper::formatForHtml( APP_BASE_URL . $seo->og["image"] ); ?>" />
	<meta property="og:type" />

	<meta name="description" content="<?php echo HtmlHelper::formatForHtml( $seo->meta["description"] ); ?>" />
	<meta name="keywords" content="<?php echo HtmlHelper::formatForHtml( $seo->meta["keywords"] ); ?>" />
<?php
	}

	/**
	 * Show the meta data and open graph data on the page. Useful for debugging and verifying the content is correct.
	 * Must have APP_SHOW_SEO_DUMP enabled in config.
	 * @param mixed $seo the seo data
	 */
	public static function ShowSEODebug( $seo ) {
		if( APP_SHOW_SEO_DUMP ) : ?>
			<style>
				.seo-dump { background-color: #111; color: #f5f5f5; padding: 10px; }
				.seo-dump a { color: #f5f5f5; }
				.seo-dump a.close { position: absolute; top: 10px ;right: 10px; padding: 5px 8px; background-color: #f5f5f5; color: #111; text-decoration: none; }
				.seo-dump h2 { color: #f5f5f5; font-size: 1em; margin: 0 0 10px; }
				.seo-dump dl { overflow: hidden; }
				.seo-dump dt { float: left; width: 15em; clear: left; text-align: right; font-weight: bold; padding-bottom: 10px; padding-right: 10px; }
				.seo-dump dd { margin-left: 15em; padding-bottom: 10px; }
			</style>

			<div class="seo-dump">
				<h2>SEO and Open Graph Dump</h2>

				<dl>
					<dt>Page Title</dt>
					<dd><?php echo $seo["title"]; ?></dd>

					<dt>Meta Description</dt>
					<dd><?php echo $seo->meta["description"]; ?></dd>

					<dt>Meta Keywords</dt>
					<dd><?php echo $seo->meta["keywords"]; ?></dd>

					<dt>Open Graph - Title</dt>
					<dd><?php echo $seo->og["title"]; ?></dd>

					<dt>Open Graph - Description</dt>
					<dd><?php echo $seo->og["description"]; ?></dd>

					<dt>Open Graph - Image</dt>
					<dd><a href="<?php echo $seo->og["image"];?>">
							<?php echo APP_BASE_URL . $seo->og["image"]; ?><br />
							<img src="<?php echo APP_BASE_URL . $seo->og["image"];?>" />
						</a>
					</dd>
				</dl>

				<a class="close" href="javascript:" onclick="this.parentNode.parentNode.removeChild( this.parentNode );">X</a>
			</div>
		<?php endif; ?>
	<?php
	}
}
