<?php

/**
 * Turkish (Türkçe)
 *
 * Turkish has two different i, one with a dot and another without a dot. They
 * are totally different letters in this language, so we have to override the
 * ucfirst and lcfirst methods.
 * See http://en.wikipedia.org/wiki/Dotted_and_dotless_I
 * and @bug 28040
 * @ingroup Language
 */
class LanguageTr extends Language {

	/**
	 * @param $string string
	 * @return string
	 */
	function ucfirst ( $string ) {
		if ( strlen( $string ) && $string[0] == 'i' ) {
			return 'İ' . substr( $string, 1 );
		} else {
			return parent::ucfirst( $string );
		}
	}

	/**
	 * @param $string string
	 * @return mixed|string
	 */
	function lcfirst ( $string ) {
		if ( strlen( $string ) && $string[0] == 'I' ) {
			return 'ı' . substr( $string, 1 );
		} else {
			return parent::lcfirst( $string );
		}
	}

}
