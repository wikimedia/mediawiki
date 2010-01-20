<?php
/** Arabic (العربية)
 *
 * @ingroup Language
 *
 * @author Niklas Laxström
 */
class LanguageAr extends Language {
	function convertPlural( $count, $forms ) {
		if ( !count($forms) ) { return ''; }
		$forms = $this->preConvertPlural( $forms, 6 );

		if ( $count == 0 ) {
			$index = 0;
		} elseif ( $count == 1 ) {
			$index = 1;
		} elseif( $count == 2 ) {
			$index = 2;
		} elseif( $count % 100 >= 3 && $count % 100 <= 10 ) {
			$index = 3;
		} elseif( $count % 100 >= 11 && $count % 100 <= 99 ) {
			$index = 4;
		} else {
			$index = 5;
		}
		return $forms[$index];
	}

	/**
	 * Temporary hack for bug 9413: replace Arabic presentation forms with their 
	 * standard equivalents. 
	 *
	 * FIXME: This is language-specific for now only to avoid the negative 
	 * performance impact of enabling it for all languages.
	 */
	function normalize( $s ) {
		global $wgFixArabicUnicode;
		$s = parent::normalize( $s );
		if ( $wgFixArabicUnicode ) {
			$s = $this->transformUsingPairFile( 'normalize-ar.ser', $s );
		}
		return $s;
	}
}
