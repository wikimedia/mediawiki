<?php

/**
 * @ingroup Language
 */
class LanguageZh_hans extends Language {
	function hasWordBreaks() {
		return false;
	}
	
	function stripForSearch( $string ) {
		// Eventually this should be a word segmentation;
		// for now just treat each character as a word.
		//
		// Note we put a space on both sides to cover cases
		// where a number or Latin char follows a Han char.
		//
		// @todo Fixme: only do this for Han characters...
		$t = preg_replace(
				"/([\\xc0-\\xff][\\x80-\\xbf]*)/",
				" $1 ", $string);
		$t = preg_replace( '/ +/', ' ', $t );
		$t = trim( $t );
		return parent::stripForSearch( $t );
	}
}
