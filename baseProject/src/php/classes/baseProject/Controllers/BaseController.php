<?php
/**
 * Base controller for baseProject.
 */

namespace baseProject\Controllers;

/**
 * This BaseController class provides various utility functions that are common for a controler. Utility functions
 * exist for getting values from $_REQUEST, $_SESSION, and redirecting. This class is meant to be extended.
 */
class BaseController {
	/**
	 * Redirect the page to the passed URL. Will stop execution.
	 * @param string $url the url to redirect to
	 */
	public function redirect( $url ) {
		header("Location: $url ");
		exit;
	}

	/**
	 * Get the value of $key from $search. If there isn't anything, use $default.
	 * @param string $key the key to lookup in $search
	 * @param string $search an array
	 * @param mixed|null $default any default value if its not in the search array
	 * @return null|string the value of the $search[ $key ] or the default
	 */
	private function getValueFrom( $key, $search, $default = null ) {
		if( array_key_exists( $key, $search ) ) {
			return trim( $search[ $key ] );
		}

		return $default;
	}

	/**
	 * Get a given key from the $_REQUEST array
	 * @param string $name the name on the form, the key to lookup in $_REQUEST
	 * @param string $default a default value
	 * @return null|string the value of $_REQUEST[$key] or the default
	 */
	protected function getValueFromRequest( $name, $default = null ) {
		return $this->getValueFrom( $name, $_REQUEST, $default );
	}

	/**
	 * Get the value of $key from $_SESSION, if no value exists use $default
	 * @param string $name the name on the form, the key to lookup in $_SESSION
	 * @param string $default the default value if $key doesn't exist
	 * @return null|string
	 */
	protected function getValueFromSession( $name, $default = null ) {
		return $this->getValueFrom( $name, $_SESSION, $default );
	}

	/**
	 * Get the date time passed in the request with the given key. a time() will be returned. The form should
	 * have the following full names - "$name-year", "$name-month", "$name-day".
	 * @param $name string the name on the form, the key to look for in $_REQUEST
	 * @return int|null the timestamp from the request or null if its invalid
	 */
	protected function getDateFromRequest( $name ) {
		$year = $this->getValueFromRequest( $name . "-year" );
		$month =$this->getValueFromRequest( $name . "-month" );
		$day = $this->getValueFromRequest( $name . "-day" );

		if( $year == -1 && $month == -1 && $day == -1 ) { return null; }
		$dateTime = mktime( 0, 0, 0, $month, $day, $year );

		return $dateTime;
	}

	/**
	 * Get the value of key from the request as a boolean.
	 * @param string $name the name on the form, the key to look for in $_REQUEST
	 * @return bool|null whether or not the key says "true" in any valid way
	 */
	protected function getBooleanFromRequest( $name ) {
		$value = strtolower( $this->getValueFromRequest( $name ) );

		return $value === "true";
	}

	/**
	 * Check to see that the date/time being passed in the request is valid.
	 * @param string $key the name on the form, the key to look for in $_REQUEST
	 * @return bool whether or not the date is valid from the request
	 */
	protected function validateDateFromRequest( $key ) {
		return $this->getDateFromRequest( $key ) != null;
	}
}