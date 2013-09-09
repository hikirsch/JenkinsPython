<?php
/**
 * A sample routes table that demonstrates extending
 */
namespace sampleProject;

use sampleProject\Controllers\SampleController;
use baseProject\AbstractRoutesTable;
use baseProject\Exceptions\Error500Exception;
use baseProject\Exceptions\Error410Exception;

/**
 * This class supports a couple of additional pages to test some of functionality of baseProject.
 * @see \baseProject\AbstractRoutesTable.
 */
class RoutesTable extends AbstractRoutesTable {
	/**
	 * An implementation of the static routes for sampleProject.
	 * @return array static routes array
	 */
	public function getStaticRoutes() {
		return array(
			"/" => "Index",
			"/home" => "Home",
			"/error" => "SampleError",
			"/gone" => "SampleGone",
			"/unsupported-browser" => "UnsupportedBrowser"
		);
	}

	/**
	 * An implementation of the dynamic routes for sampleProject.
	 * @return array dynamic routes array
	 */
	public function getDynamicRoutes() {
		return array(
			"/dynamic/*" => "SampleDynamic",
		);
	}

	/**
	 * An implementation of the always support routes for sampleProject.
	 * @return array an array of routes to always support
	 */
	public function getAlwaysSupportRoute() {
		return array(
			"/unsupported-browser"
		);
	}

	/**
	 * Index Page, /
	 */
	public function Index(){
		$this->redirect("/home");
	}

	/**
	 * Home page, /home
	 */
	public function Home(){
		$controller = new SampleController();
		$controller->home();
	}

	/**
	 * Sample Dynamic Page, /dynamic/*
	 * @param mixed $x the value of * in the url
	 */
	public function SampleDynamic( $x ){
		$controller = new SampleController();
		$controller->dynamic( $x );
	}

	/**
	 * A sample error page. Throws a 500 with an inner exception. /error
	 * @throws \baseProject\Exceptions\Error500Exception
	 * @throws \Exception
	 */
	public function SampleError() {
		try {
			throw new \Exception( "Some fake exception." );
		} catch( \Exception $e ) {
			throw new Error500Exception("Something bad happened.", $e);
		}
	}

	/**
	 * Sample 410 Gone Error page, /gone
	 * @throws \baseProject\Exceptions\Error410Exception
	 */
	public function SampleGone() {
		throw new Error410Exception( "This page is gone yo!" );
	}
}
