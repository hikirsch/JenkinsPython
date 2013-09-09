<?php
/**
 * Various HTML helpers to format text for HTML for baseProject.
 */
namespace baseProject\Helpers;

/**
 * HtmlHelper class support various methods for text encoding and text display based on the context of
 * the text, e.g. urls, html encoding, etc.
 */
class HtmlHelper {

	/**
	 * Encode the string for HTML, replaces new lines with br's too.
	 * @param string $str the string to transform
	 * @return string an html escaped string
	 */
	public static function formatForHtml( $str ) {
		if( gettype( $str ) == "resource" ) {
			$str = stream_get_contents( $str );
		}

		$str = htmlentities( $str, ENT_QUOTES, "UTF-8" );
		$str = str_replace( "\n", "<br />", $str );
		return $str;
	}
}
