<?php
/**
 * Your basic bootstrap can specify some basic configuration files as needed (included from /config)
 * and sets up the routes table that should be used. Pass a full namespace as a string.
 */

require_once("baseProject/BootStrapper.php");

/**
 * A sample bootstrap implementation. Set any config files, the routes table, propel configuration, and some other
 * built-in tools such as ensurePrimaryDomain() and enableHtmlCompression().
 */
class SampleBootStrapper extends BootStrapper {
	/**
	 * Configuration files to use.
	 * @var array
	 */
	protected $configurationFiles = array(
		"app-config.php",
		"env-config.php"
	);

	/**
	 * The namespace of the routes table to use
	 * @var string
	 */
	protected $routesTable = '\sampleProject\RoutesTable';

	/**
	 * Sample constructor for bootstrap. Includes some custom things we may want.
	 */
	public function __construct() {
		parent::__construct();

		// uncomment this to ensure the primary domain is whats set in the config, useful for forcefully
		// or unforcefully adding www, in dev its usually set to $_SERVER["SERVER_NAME"] so its always true
		// and the developer can use any hostname for the site.
		$this->ensurePrimaryDomain();

		// EXPERIMENTAL - this feature may work depending on your DOM
		$this->enableHtmlCompression();

		// ok fire the request and we're ready to go.
		$this->handleRequest();
	}

	/**
	 * Include any dependencies this project may require that's not part of the default set.
	 */
	protected function includeDependencies() {
		parent::includeDependencies();

		// we want to init Propel, its ok if it doesn't exist, it wont error if this file doesn't exist.
		// this is the file that will be created upon propel-gen convert-conf
		$this->initPropel( "sampleProject/Model-conf.php" );
	}
}
