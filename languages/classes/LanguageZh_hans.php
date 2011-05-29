<?php

/**
 * Simplified Chinese
 *
 * @ingroup Language
 */
class LanguageZh_hans extends Language {

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
	 *
	 * @return string
	 */
	function segmentByWord( $string ) {
		$reg = "/([\\xc0-\\xff][\\x80-\\xbf]*)/";
		$s = self::insertSpace( $string, $reg );
		return $s;
	}

	/**
	 * @param $s
	 * @return string
	 */
	function normalizeForSearch( $s ) {
		wfProfileIn( __METHOD__ );

		// Double-width roman characters
		$s = parent::normalizeForSearch( $s );
		$s = trim( $s );
		$s = $this->segmentByWord( $s );

		wfProfileOut( __METHOD__ );
		return $s;
	}
}
