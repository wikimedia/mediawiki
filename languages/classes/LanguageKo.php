<?php
/** Korean (한국어)
  *
  * @package MediaWiki
  * @subpackage Language
  */

class LanguageKo extends Language {
	function firstChar( $s ) {
		preg_match( '/^([\x00-\x7f]|[\xc0-\xdf][\x80-\xbf]|' .
		'[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xf7][\x80-\xbf]{3})/', $s, $matches);

		if ( isset( $matches[1] ) ) {
			if ( strlen( $matches[1] ) != 3 ) {
				return $matches[1];
			}
			$code = (ord($matches[1]{0}) & 0x0f) << 12;
			$code |= (ord($matches[1]{1}) & 0x3f) << 6;
			$code |= (ord($matches[1]{2}) & 0x3f);
			if ( $code < 0xac00 || 0xd7a4 <= $code) {
				return $matches[1];
			} elseif ( $code < 0xb098 ) {
				return "\xe3\x84\xb1";
			} elseif ( $code < 0xb2e4 ) {
				return "\xe3\x84\xb4";
			} elseif ( $code < 0xb77c ) {
				return "\xe3\x84\xb7";
			} elseif ( $code < 0xb9c8 ) {
				return "\xe3\x84\xb9";
			} elseif ( $code < 0xbc14 ) {
				return "\xe3\x85\x81";
			} elseif ( $code < 0xc0ac ) {
				return "\xe3\x85\x82";
			} elseif ( $code < 0xc544 ) {
				return "\xe3\x85\x85";
			} elseif ( $code < 0xc790 ) {
				return "\xe3\x85\x87";
			} elseif ( $code < 0xcc28 ) {
				return "\xe3\x85\x88";
			} elseif ( $code < 0xce74 ) {
				return "\xe3\x85\x8a";
			} elseif ( $code < 0xd0c0 ) {
				return "\xe3\x85\x8b";
			} elseif ( $code < 0xd30c ) {
				return "\xe3\x85\x8c";
			} elseif ( $code < 0xd558 ) {
				return "\xe3\x85\x8d";
			} else {
				return "\xe3\x85\x8e";
			}
		} else {
			return "";
		}
	}
}

?>
