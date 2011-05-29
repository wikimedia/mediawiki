<?php
/**
 * Cantonese (粵語)
 *
 * @ingroup Language
 */
class LanguageYue extends Language {

	/**
	 * @return bool
	 */
	function hasWordBreaks() {
		return false;
	}

	/**
	 * Eventually this should be a word segmentation;
	 * for now just treat each character as a word.
	 * @todo FIXME: Only do this for Han characters...
	 *
	 * @param $string string
	 * @return string
	 */
	function segmentByWord( $string ) {
		$reg = "/([\\xc0-\\xff][\\x80-\\xbf]*)/";
		$s = self::insertSpace( $string, $reg );
		return $s;
	}

	/**
	 * @param $string
	 * @return string
	 */
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
