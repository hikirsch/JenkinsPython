<?php
/**
 * This base exception class supports an inner exception. This is used to throw internal exceptions with in the app
 * so the application can show friendlier error messages.
 */

namespace baseProject\Exceptions;

/**
 * A base exception class with an inner exception.
 */
abstract class BaseException extends \Exception {
	/**
	 * More details on the real exception that was thrown (if avilable)
	 * @var \Exception|null
	 */
	private $innerException;

	/**
	 * Create the exception, save the inner exception separately.
	 * @param string $msg a message stating what went wrong, additional tech details can be in the inner exception
	 * @param \Exception|null $e an inner exception that was thrown
	 */
	public function __construct( $msg, $e = null ) {
		parent::__construct( $msg );

		$this->innerException = $e;
	}

	/**
	 * Get the inner exception.
	 * @return \Exception|null an inner exception
	 */
	public function getInnerException() {
		return $this->innerException;
	}
}
