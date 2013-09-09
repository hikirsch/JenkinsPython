<?php
/**
 * The dispatcher for baseProject
 */
namespace baseProject;

use baseProject\Exceptions\Error404Exception;
use baseProject\Exceptions\Error410Exception;
use baseProject\Exceptions\Error500Exception;
use baseProject\Exceptions\Error403Exception;

/**
 * This class is internal to baseProject. The dispatcher is responsible for taking a routes table and applying it. It will check the URL and call the
 * appropriate function inside the routes table. You probably want to create a new routes table.
 * @see \baseProject\AbstractRoutesTable
 */
class Dispatcher {
	/**
	 * An instance of the routes table to route against
	 * @var \baseProject\AbstractRoutesTable
	 */
	private $routesTable = null;

	/**
	 * Static routes array
	 * @var array
	 */
	private $staticRoutes = null;

	/**
	 * Dynamic routes array
	 * @var array
	 */
	private $dynamicRoutes = null;

	/**
	 * Always support these routes in an array
	 * @var array
	 */
	private $alwaysSupportRoute = null;

	/**
	 * Create an instance of the routes table passed, and get all the routes from the table. Also get the current path.
	 * @param string $routesTable the routes table to route against
	 */
	public function __construct( $routesTable ) {
		$this->routesTable = new $routesTable();

		$routesTable::SetActiveRoutesTable( $this->routesTable );

		$this->staticRoutes = $this->routesTable->getStaticRoutes();
		$this->dynamicRoutes = $this->routesTable->getDynamicRoutes();
		$this->alwaysSupportRoute = $this->routesTable->getAlwaysSupportRoute();

		$this->currentRoute = $this->getPathName();
	}

	/**
	 * Begin the dispatcher. Checks first to see if we should support the browser and then the route.
	 * @return bool whether or not the route was successful.
	 * @throws \baseProject\Exceptions\Error404Exception file not found exception
	 * @throws \baseProject\Exceptions\Error403Exception access denied exception
	 * @throws \baseProject\Exceptions\Error410Exception gone exception
	 * @throws \baseProject\Exceptions\Error500Exception internal server error exception
	 * @throws \Exception any other internal exception may also be thrown
	 */
	public function dispatch() {
		$successfulRoute = false;
		if(
			// we have a browser that supports this page or
			$this->supportedBrowser() ||

			// a browser that isn't supported but we have to exclusively support it
			( in_array( $this->currentRoute, $this->alwaysSupportRoute ) )
		) {
			try {
				$successfulRoute = $this->route();

				if( ! $successfulRoute ) {
					throw new Error404Exception("Route was unsuccessful.");
				}
			} catch( Error404Exception $e ) {
				$this->routesTable->FileNotFound404($e);
			} catch( Error403Exception $e ) {
				$this->routesTable->AccessDenied403($e);
			} catch( Error410Exception $e ) {
				$this->routesTable->Gone410($e);
			} catch( Error500Exception $e ) {
				$this->routesTable->InternalServerError500($e);
			} catch( \Exception $e ) {
				$this->routesTable->InternalServerError500($e);
			}

		} else {
			$this->routesTable->RedirectToUnsupportedBrowser();

			$successfulRoute = true;
		}

		return $successfulRoute;
	}

	/**
	 * Get the current URL.
	 * @return string the current URL
	 */
	public function getPathName() {
		if( array_key_exists( "REDIRECT_URL", $_SERVER ) ) {
			return $_SERVER["REDIRECT_URL"];
		}

		return "/";
	}

	/**
	 * Apply the current URL to a route in the routes table. This will call the specific method in the routes table.
	 * @throws \baseProject\Exceptions\Error500Exception delegate doesn't exist inside a URL.
	 */
	public function route() {
		$successfulRoute = false;

		if( isset( $this->staticRoutes[ $this->currentRoute ] ) ) {
			$delegate = $this->staticRoutes[ $this->currentRoute ];

			if( method_exists( $this->routesTable, $delegate ) ) {
				call_user_func_array( array( $this->routesTable, $delegate ), array() );
			} else {
				throw new Error500Exception( "Method '" . $delegate . "' was routed from url '" . $this->currentRoute . "' but it was not defined in the routes table." );
			}

			$successfulRoute = true;
		} else {
			foreach( $this->dynamicRoutes as $dynamicMap => $delegate ){
				$regex = str_replace( "*", "(.*?)", $dynamicMap );
				$regex = str_replace( "/", '\/', $regex );

				$success = preg_match( "/^$regex$/", $this->currentRoute, $matches );

				if( $success ) {
					// we don't need the match that has the full URL in it, so we take this off
					$matches = array_slice( $matches, 1 );

					call_user_func_array( array( $this->routesTable, $delegate ), $matches );

					$successfulRoute = true;

					break;
				}
			}
		}

		return $successfulRoute;
	}

	/**
	 * Check to see whether or not we should support specific browsers. Currently only pushes IE6 and IE7.
	 * @return bool whether or not this browser is supported on this site.
	 */
	private function supportedBrowser() {
		$hasUserAgent = array_key_exists("HTTP_USER_AGENT", $_SERVER );

		if( $hasUserAgent ) {
			// kill IE6 or IE7
			$ie6 = ( strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6.' ) !== false );
			$ie7 = ( strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 7.' ) !== false );

			// if its not ie6 or ie7
			return ! ( $ie6 || $ie7 );
		}

		return true;
	}
}
