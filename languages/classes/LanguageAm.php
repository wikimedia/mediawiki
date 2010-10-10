<?php
/**
 * Amharic (አማርኛ)
 *
 * @ingroup Language
 */
class LanguageAm extends Language {
	/**
	 * Use singular form for zero
	 */
	function convertPlural( $count, $forms ) {
		if ( !count( $forms ) ) { return ''; }
		$forms = $this->preConvertPlural( $forms, 2 );

		return ( $count <= 1 ) ? $forms[0] : $forms[1];
	}
}
