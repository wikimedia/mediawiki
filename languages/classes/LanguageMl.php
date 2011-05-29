<?php

/**
 * Malayalam (മലയാളം)
 *
 * @ingroup Language
 */
class LanguageMl extends Language {
	/**
	 * Temporary hack for the issue described at
	 * http://permalink.gmane.org/gmane.science.linguistics.wikipedia.technical/46396
	 * Convert Unicode 5.0 style Malayalam input to Unicode 5.1. Similar to
	 * bug 9413. Also fixes miscellaneous problems due to mishandling of ZWJ,
	 * e.g. bug 11162.
	 *
	 * @todo FIXME: This is language-specific for now only to avoid the negative
	 * performance impact of enabling it for all languages.
	 *
	 * @param $s string
	 *
	 * @return string
	 */
	function normalize( $s ) {
		global $wgFixMalayalamUnicode;
		$s = parent::normalize( $s );
		if ( $wgFixMalayalamUnicode ) {
			$s = $this->transformUsingPairFile( 'normalize-ml.ser', $s );
		}
		return $s;
	}
}
