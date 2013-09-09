<?php
/**
 * The AbstractRoutesTable class defines a base class for defining a Routes Table for an application. Implement
 * the three required methods, "getStaticRoutes", "getDynamicRoutes", and "getAlwaysSupportRoute".
 */

namespace baseProject;

use baseProject\Controllers\ErrorController;
use baseProject\Controllers\BasePageController;

/**
 * This class defines the basics for a route table.
 * @abstract
 */
abstract class AbstractRoutesTable {
	/**
	 * An instance of the routes table currently used by the application
	 * @var AbstractRoutesTable
	 */
	private static $activeRoutesTable;

	/**
	 * An internal cache of URLs mapped to their delegates for getting URLs via this routes table
	 * @var array
	 */
	private static $urlCache = null;

	/**
	 * Set the active routes table. This is used internally from the Dispatcher.
	 * @param AbstractRoutesTable $routesTable an instance of this class
	 */
	public static function SetActiveRoutesTable( $routesTable ) {
		AbstractRoutesTable::$activeRoutesTable = $routesTable;
	}

	/**
	 * Call GetUrl and then echo it.
	 * @internal param string $url the delegate method name from the routes table
	 * @internal param mixed $args any url arguments
	 */
	public static function Url() {
		echo call_user_func_array('\baseProject\AbstractRoutesTable::GetUrl', func_get_args() );
	}

	/**
	 * Get the url from the url => delegate mapping in the routes table.
	 * @param $delegate string the name of a delegate from the routes table to get the url of
	 * @return string|null the url of the delegate passed
	 */
	public static function GetUrl( $delegate ) {
		// only init this once and when we actually want a url.
		if( AbstractRoutesTable::$urlCache == null ) {
			AbstractRoutesTable::initUrlCache();
		}

		// if the URL is a static map
		if( array_key_exists( $delegate, AbstractRoutesTable::$urlCache["staticRoutes"] )  ) {
			return AbstractRoutesTable::$urlCache["staticRoutes"][ $delegate ];

		}

		// if the URL is a dynamic map
		if( array_key_exists( $delegate, AbstractRoutesTable::$urlCache["dynamicRoutes"] )  ) {
			$url = AbstractRoutesTable::$urlCache["dynamicRoutes"][ $delegate ];

			// replace all the '*' to '%s' for the URL, and reuse the arguments array except
			// set the first argument to the url we want to sprintf on.
			$array = func_get_args();
			$array[ 0 ] = str_replace( "*", "%s", $url );

			// we use call_user_func_array so that we can pass the array itself into sprintf
			return call_user_func_array( 'sprintf', $array );
		}

		return null;
	}

	/**
	 * Creates a local cache of the delegate to URL lookup.
	 */
	private static function initUrlCache() {
		AbstractRoutesTable::$urlCache = array();

		AbstractRoutesTable::$urlCache["staticRoutes"] = array_flip( AbstractRoutesTable::$activeRoutesTable->getStaticRoutes() );
		AbstractRoutesTable::$urlCache["dynamicRoutes"] = array_flip( AbstractRoutesTable::$activeRoutesTable->getDynamicRoutes() );
	}

	/**
	 * Method handler to redirect to the unsupported browser page.
	 */
	public function RedirectToUnsupportedBrowser() {
		$this->redirect("/unsupported-browser");
	}


	/**
	 * Show the unsupported browser page
	 */
	public function UnsupportedBrowser() {
		$controller = new BasePageController("Pages/UnsupportedBrowser");
		$controller->render();
	}

	/**
	 * Access Denied (403) error page
	 * @param \Exception $e an exception to be logged
	 */
	public function AccessDenied403($e) {
		$controller = new ErrorController($e);
		$controller->render(403);
	}

	/**
	 * File Not Found (404) error page
	 * @param \Exception $e an exception to be logged
	 */
	public function FileNotFound404($e) {
		$controller = new ErrorController($e);
		$controller->render(404);
	}

	/**
	 * Gone (410) error page
	 * @param \Exception $e an exception to be logged
	 */
	public function Gone410($e) {
		$controller = new ErrorController($e);
		$controller->render(410);
	}

	/**
	 * Internal Server Error (500) error page
	 * @param \Exception $e an exception to be logged
	 */
	public function InternalServerError500($e) {
		$controller = new ErrorController($e);
		$controller->render(500);
	}

	/**
	 * Redirect to a new page
	 * @param string $url the url to redirect to
	 */
	public function redirect( $url ) {
		header("Location: " . $url );
		exit;
	}

	/**
	 * Return an array of static urls
	 * @return array url as the key, delegate function name as the value
	 */
	public abstract function getStaticRoutes();

	/**
	 * Return an array of dynamic urls. Use a * to represent a parameter, e.g. /my/page/*
	 * @return array url as the key, delegate function name as the value
	 */
	public abstract function getDynamicRoutes();

	/**
	 * An array of urls that the system should ignore browser support rules on.
	 * @return array urls that should always be supported
	 */
	public abstract function getAlwaysSupportRoute();
}
