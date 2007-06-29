<?php
/** Ripuarian (Ripoarėsh)
 *
 * @addtogroup Language
 *
 * @author Purodha Blissenbach
 */

class LanguageKsh extends Language {
	/**
	 * Avoid grouping whole numbers between 0 to 9999
	 */
	public function commafy( $_ ) {
		if ( !preg_match( '/^\d{1,4}$/', $_ ) ) {
			return strrev( (string)preg_replace( '/(\d{3})(?=\d)(?!\d*\.)/', '$1,', strrev( $_ ) ) );
		} else {
			return $_;
		}
	}

	/**
	 * Handle cases of (1, other, 0) or (1, other)
	 */
	public function convertPlural( $count, $w1, $w2, $w3, $w4, $w5 ) {
		$count = str_replace (' ', '', $count);
		if ( $count == '1' ) {
			return $w1;
		} elseif ( $count == '0' && $w3 ) {
			return $w3;
		} else {
			return $w2;
		}
	}
}

