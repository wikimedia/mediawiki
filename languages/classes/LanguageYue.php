<?php
/**
 * @ingroup Language
 */
class LanguageYue extends Language {
	function hasWordBreaks() {
		return false;
	}

	/**
	 * Eventually this should be a word segmentation;
	 * for now just treat each character as a word.
	 * @todo Fixme: only do this for Han characters...
	 */
	function segmentByWord( $string ) {
		$reg = "/([\\xc0-\\xff][\\x80-\\xbf]*)/";
		$s = self::insertSpace( $string, $reg );
		return $s;
	}

	function normalizeForSearch( $string ) {
		wfProfileIn( __METHOD__ );

		// Double-width roman characters
		$s = self::convertDoubleWidth( $string );
		$s = trim( $s );
		$s = parent::normalizeForSearch( $s );

		wfProfileOut( __METHOD__ );
		return $s;
	}
}
