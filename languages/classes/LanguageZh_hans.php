<?php

/**
 * @ingroup Language
 */
class LanguageZh_hans extends Language {
	function hasWordBreaks() {
		return false;
	}
	
	function stripForSearch( $string, $doStrip = true ) {
		wfProfileIn( __METHOD__ );

		// Double-width roman characters
		$s = self::convertDoubleWidth( $string );

		if ( $doStrip == true ) {
			// Eventually this should be a word segmentation;
			// for now just treat each character as a word.
			// @todo Fixme: only do this for Han characters...
			$reg = "/([\\xc0-\\xff][\\x80-\\xbf]*)/";
			$s = self::wordSegmentation( $s, $reg );
		}

		$s = trim( $s );

		// Do general case folding and UTF-8 armoring
		$s = parent::stripForSearch( $s, $doStrip );
		wfProfileOut( __METHOD__ );
		return $s;
	}
}