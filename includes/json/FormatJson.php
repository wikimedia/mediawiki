<?php

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 1 );
}

/**
 * Simple wrapper for json_econde and json_decode that falls back on Services_JSON class
 */
class FormatJson {

	// Constants for decode() return types
	const AS_OBJECT = true;
	const AS_ARRAY = false;

	/**
	 * Turn an array or object into a JSON string
	 * @param $value Mixed. Array or object to turn into JSON
	 * @param $isHtml bool ???
	 * @return <type>
	 */
	public static function encode( $value, $isHtml = false ) {
		// Some versions of PHP have a broken json_encode, see PHP bug
		// 46944. Test encoding an affected character (U+20000) to
		// avoid this.
		if ( !function_exists( 'json_encode' ) || $isHtml || strtolower( json_encode( "\xf0\xa0\x80\x80" ) ) != '\ud840\udc00' ) {
			$json = new Services_JSON();
			return $json->encode( $value, $isHtml );
		} else {
			return json_encode( $value );
		}
	}

	/**
	 * Decode some JSON into an array or object
	 * @param $value String of Json
	 * @param $assoc bool One of AS_OBJECT or AS_ARRAY to specify return type
	 * @return Array or Object
	 */
	public static function decode( $value, $assoc = false ) {
		if ( !function_exists( 'json_decode' ) ) {
			$json = new Services_JSON();
			$jsonDec = $json->decode( $value );
			if( $assoc ) {
				$jsonDec = wfObjectToArray( $jsonDec );
			}
			return $jsonDec;
		} else {
			return json_decode( $value, $assoc );
		}
	}
}
