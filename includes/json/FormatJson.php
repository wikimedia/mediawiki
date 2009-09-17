<?php
/*
 * simple wrapper for json_econde and json_decode that falls back on Services_JSON class
 */
if( !(defined( 'MEDIAWIKI' ) ) ) {
	die( 1 );
}

class FormatJson{
	public static function encode($value, $isHtml=false){
		// Some versions of PHP have a broken json_encode, see PHP bug
		// 46944. Test encoding an affected character (U+20000) to
		// avoid this.
		if (!function_exists('json_encode') || $isHtml || strtolower(json_encode("\xf0\xa0\x80\x80")) != '\ud840\udc00') {
			$json = new Services_JSON();
			return $json->encode($value, $isHtml) ;
		} else {
			return json_encode($value);
		}
	}
	public static function decode( $value, $assoc=false ){
		if (!function_exists('json_decode') ) {
			$json = new Services_JSON();
			$jsonDec = $json->decode( $value );
			if( $assoc )
				$jsonDec = wfObjectToArray( $jsonDec );
			return $jsonDec;
		} else {
			return json_decode( $value, $assoc );
		}
	}
}
