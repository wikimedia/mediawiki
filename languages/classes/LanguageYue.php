<?php
/**
 * @ingroup Language
 */
class LanguageYue extends Language {
	function stripForSearch( $string ) {
		wfProfileIn( __METHOD__ );

		// eventually this should be a word segmentation
		// for now just treat each character as a word
		// @fixme only do this for Han characters...
		$t = preg_replace(
				"/([\\xc0-\\xff][\\x80-\\xbf]*)/",
				" $1", $string);

		// Do general case folding and UTF-8 armoring
		$t = parent::stripForSearch( $t );
		wfProfileOut( __METHOD__ );
		return $t;
	}
}
