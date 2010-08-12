<?php
/**
 * Simple wrapper for json_econde and json_decode that falls back on Services_JSON class
 */
if ( !defined( 'MEDIAWIKI' ) ) {
	die( 1 );
}

class FormatJson {
	
	/**
	 * Returns the JSON representation of a value.
	 * 
	 * @param $value Mixed: the value being encoded. Can be any type except a resource.
	 * @param $isHtml Boolean
	 * 
	 * @return string
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
	 * Decodes a JSON string.
	 * 
	 * @param $value String: the json string being decoded.
	 * @param $assoc Boolean: when true, returned objects will be converted into associative arrays.
	 * 
	 * @return Mixed: the value encoded in json in appropriate PHP type.
	 * Values true, false and null (case-insensitive) are returned as true, false
	 * and &null; respectively. &null; is returned if the json cannot be
	 * decoded or if the encoded data is deeper than the recursion limit.
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