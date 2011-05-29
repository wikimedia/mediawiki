<?php
/** Azerbaijani (Azərbaycan)
  *
  * @ingroup Language
  */
class LanguageAz extends Language {

	/**
	 * @param $string string
	 * @return mixed|string
	 */
	function ucfirst ( $string ) {
		if ( $string[0] == 'i' ) {
			return 'İ' . substr( $string, 1 );
		} else {
			return parent::ucfirst( $string );
		}
	}
}
