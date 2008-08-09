<?php

/** Kurdish
 *
 * @ingroup Language
 */
class LanguageKu_ku extends Language {

	/**
	 * Avoid grouping whole numbers between 0 to 9999
	 */
	function commafy( $_ ) {

		if ( !preg_match( '/^\d{1,4}$/', $_ ) ) {
			return strrev( (string)preg_replace( '/(\d{3})(?=\d)(?!\d*\.)/', '$1,', strrev($_) ) );
		} else {
			return $_;
		}
	}
}
