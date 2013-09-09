<?php
/**
 * A BasePageController implementation. Very simple supporting only two render methods, home and dynamic.
 */

namespace sampleProject\Controllers;

use baseProject\Controllers\BasePageController;

/**
 * SampleController extends BasePageController.
 */
class SampleController extends BasePageController {

	/**
	 * Creates a new controller using the Base/Master as the master view.
	 */
	public function __construct() {
		parent::__construct( 'Base/Master' );
	}

	/**
	 * Home page implementation is simple, just render this sub template.
	 */
	public function home() {
		$this->render( 'Pages/Home' );
	}

	/**
	 * The dynamic page takes whatever the argument was, binds it and renders its sub template. Normally, we'd want
	 * to use $x for something. We also set it into the SEO for demo purposes.
	 * @param string $x the url argument
	 */
	public function dynamic($x) {
		$this->attach( 'x',  $x );
		$this->setSeoToken( "x", $x );
		$this->render( 'Pages/SampleDynamic' );
	}
}
