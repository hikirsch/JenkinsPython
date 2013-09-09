<?php
/**
 * A base error controller class part of baseProject. The supported error codes are 403, 404, 410 and 500.
 */
namespace baseProject\Controllers;

use baseProject\Exceptions\BaseException;

/**
 * The ErrorController class is a BasePageController class, it takes an optional exception to log, and renders any
 * of the supported error codes.
 */
class ErrorController extends BasePageController {
	/**
	 * An exception that was thrown (if any)
	 * @var \Exception|\baseProject\Exceptions\BaseException
	 */
	private $exception = null;

	/**
	 * The passed exception will be logged.
	 * @param \baseProject\Exceptions\BaseException $e
	 */
	public function __construct( $e ) {
		parent::__construct( "Base/ErrorPage" );

		$this->exception = $e;
	}

	/**
	 * Render the error, passing the proper error code will set the header, also log the exception.
	 * @param int|null $errorCode
	 */
	public function render( $errorCode ) {
		if( $errorCode == 410 ) {
			header("HTTP/1.0 410 Gone");
		}

		if( $errorCode == 403 ) {
			header('HTTP/1.0 403 Forbidden');
		}

		if( $errorCode == 404 ) {
			header('HTTP/1.0 404 Not Found');
		}

		if( $errorCode == 500 ) {
			header("HTTP/1.0 500 Internal Server Error");
		}

		if( $this->exception != null ) {
			error_log( $this->exception );
			$this->attach( "exception", $this->exception );
		}

		if( method_exists( $this->exception, "getInnerException" ) ) {
			$innerException = $this->exception->getInnerException();
			if( $innerException != null ) {
				error_log( $innerException );
				$this->attach( "innerException", $innerException );
			}
		}

		parent::render( "Pages/Errors/" . $errorCode );
	}
}
