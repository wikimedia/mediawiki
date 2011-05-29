<?php

/** Polish (polski)
 *
 * @ingroup Language
 */
class LanguagePl extends Language {

	/**
	 * @param $count string
	 * @param $forms array
	 * @return string
	 */
	function convertPlural( $count, $forms ) {
		if ( !count( $forms ) ) { return ''; }
		$forms = $this->preConvertPlural( $forms, 3 );
		$count = abs( $count );
		if ( $count == 1 )
			return $forms[0];     // singular
		switch ( $count % 10 ) {
			case 2:
			case 3:
			case 4:
				if ( $count / 10 % 10 != 1 )
					return $forms[1]; // plural
			default:
				return $forms[2];   // plural genitive
		}
	}

	/**
	 * @param $_ string
	 * @return string
	 */
	function commafy( $_ ) {
		if ( !preg_match( '/^\-?\d{1,4}(\.\d+)?$/', $_ ) ) {
			return strrev( (string)preg_replace( '/(\d{3})(?=\d)(?!\d*\.)/', '$1,', strrev( $_ ) ) );
		} else {
			return $_;
		}
	}
}
