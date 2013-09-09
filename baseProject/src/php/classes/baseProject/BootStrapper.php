<?php
/**
 * A base class for setting up the environment when the application starts.
 */
/**
 * Responsible for taking the request and setting anything related to the environment so that the rest of the
 * application can run. For example, it will set include paths to make including scripts else where easier and
 * then dispatch the request. It should not handing anything more.
 * @abstract
 */
abstract class BootStrapper {
	/**
	 * The namespace of the routes table to be used.
	 * @var string
	 */
	protected $routesTable = null;

	/**
	 * An array of regex's to URLs to redirect to. The key would be the regex (matching allowed) with the
	 * value being the url, use $1, $2 as the values of the groups.
	 * @var array
	 */
	protected $rewriteRules = null;

	/**
	 * An array of strings that represent files located in the /config folder that should be loaded upon app start.
	 * @var array
	 */
	protected $configurationFiles = null;

	/**
	 * On create, set the paths accordingly.
	 */
	public function __construct( ) {
		$this->loadConfiguration();
		$this->setPaths();

		// we handle namespaces internally
		spl_autoload_register( array('BootStrapper', 'ResolveNamespace' ) );

		$this->includeDependencies();
	}

	/**
	 * Load any of the configuration files specified.
	 * @throws Exception
	 */
	private function loadConfiguration() {
		if( $this->configurationFiles != null ) {
			foreach( $this->configurationFiles as $file ) {
				$fullFilePath = "../config/" . $file;

				if( file_exists( $fullFilePath ) ) {
					require_once( $fullFilePath );
				} else {
					throw new Exception( "Unable to load configuration file: '" . $fullFilePath . "'!" );
				}
			}
		}
	}

	/**
	 * Initial paths to set.
	 */
	protected function setPaths() {
		$this->addPath( APP_CLASSES_PATH );
		$this->addPath( APP_CONF_PATH );

		$this->addPath( LIB_PROPEL_PATH );
		$this->addPath( LIB_SAVANT3_PATH );
	}

	/**
	 * PHP requires to define how to load files dynamically. This will resolve all classes located in the "classes"
	 * folder with pathing for the namespace. Each class name must match the name of the file.
	 * @param string $className the full namespace and class name to load
	 * @return bool whether or not the namespace was resolved
	 */
	public static function ResolveNamespace( $className ) {
		$filePath = APP_CLASSES_PATH . "/" . str_replace( "\\", "/", $className ) . ".php";

		if( file_exists( $filePath ) ) {
			require_once( $filePath );
			return true;
		}

		return false;
	}

	/**
	 * Check to see if we are on the domain set in the config, if not redirect to it.
	 */
	protected function ensurePrimaryDomain() {
		// if the domain we are on matches
		if( strtolower( $_SERVER["HTTP_HOST"] ) == strtolower( APP_DEFAULT_HOSTNAME ) ) { return; }

		$protocol = "http";

		if( $_SERVER["HTTPS"] ) {
			$protocol = "https";
		}

		header("Location: " . $protocol . '://' .  APP_DEFAULT_HOSTNAME . $_SERVER["REQUEST_URI"] );
		exit;
	}

	/**
	 * EXPERIMENTAL - Minify the HTML.
	 */
	public function enableHtmlCompression() {
		ob_start( array( "BootStrapper", "CompressHtml") );
	}

	/**
	 * EXPERIMENTAL - Handler for ob_start (used for HTML minification).
	 * @param $buffer
	 * @return mixed
	 */
	public static function CompressHtml($buffer) {
		$search = array(
			'/\>[^\S ]+/s', //strip whitespaces after tags, except space
			'/[^\S ]+\</s', //strip whitespaces before tags, except space
			'/(\s)+/s'  // shorten multiple whitespace sequences
		);

		$replace = array(
			'>',
			'<',
			'\\1'
		);

		$buffer = preg_replace($search, $replace, $buffer);

		return $buffer;
	}

	/**
	 * Add a new include path into PHP.
	 * @param string $newPath the new path to add
	 */
	protected function addPath( $newPath ) {
		ini_set( 'include_path',  $newPath . ":" . ini_get('include_path') );
	}

	/**
	 * Once the environment has been set, we can create a new dispatcher and begin processing the actual request.
	 */
	public function handleRequest() {
		$this->applyRewrite();

		$dispatcher = new baseProject\Dispatcher( $this->routesTable );
		$dispatcher->dispatch();
	}

	/**
	 * Apply any rewrites defined within the bootstrapper.
	 */
	private function applyRewrite() {
		if( ! isset( $this->rewriteRules ) ) return;

		if( array_key_exists( "REQUEST_URI", $_SERVER ) ) {
			foreach( $this->rewriteRules as $ruleRegex => $ruleValue ) {
				if( preg_match( $ruleRegex, $_SERVER["REQUEST_URI"], $match ) ) {
					$newUrl = $ruleValue;

					for( $i = 1; $i < count( $match ); $i += 1 ) {
						$newUrl = str_replace( "$" . $i, $match[ $i ], $newUrl );
					}

					header("Location: " . $newUrl );
					exit;
				}
			}
		}
	}

	/**
	 * Include any dependencies.
	 */
	protected function includeDependencies() {
		require_once( "Savant3.php" );
		require_once( "Propel.php" );
	}


	/**
	 * Propel is a PHP ORM framework. We initialize at the top so the entire project can handle any class it provided.
	 * @param string $modelConfig a configuration path for Propel
	 */
	protected function initPropel( $modelConfig ) {
		$path = stream_resolve_include_path( $modelConfig );

		if( $path ) {
			Propel::init( $modelConfig );
		}
	}
}
