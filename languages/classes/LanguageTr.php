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

	function ucfirst ( $string ) {
		if ( !empty( $string ) && $string[0] == 'i' ) {
			return 'İ' . substr( $string, 1 );
		} else {
			return parent::ucfirst( $string );
		}
	}

	function lcfirst ( $string ) {
		if ( !empty( $string ) && $string[0] == 'I' ) {
			return 'ı' . substr( $string, 1 );
		} else {
			return parent::lcfirst( $string );
		}
	}

	/** @see bug 28040 */
	function uc( $string, $first = false ) {
		$string = preg_replace( '/i/', 'İ', $string );
		return parent::uc( $string, $first );
	}

	/** @see bug 28040 */
	function lc( $string, $first = false ) {
		$string = preg_replace( '/I/', 'ı', $string );
		return parent::lc( $string, $first );
	}

}
