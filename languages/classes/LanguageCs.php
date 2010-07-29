<?php

/** Czech (čeština [subst.], český [adj.], česky [adv.])
 *
 * @ingroup Language
 */
class LanguageCs extends Language {

	# Plural transformations
	# Invoked by putting
	#   {{plural:count|form1|form2-4|form0,5+}} for two forms plurals
	#   {{plural:count|form1|form0,2+}} for single form plurals
	# in a message
	function convertPlural( $count, $forms ) {
		if ( !count( $forms ) ) { return ''; }
		$forms = $this->preConvertPlural( $forms, 3 );

		switch ( $count ) {
			case 1:
				return $forms[0];
			case 2:
			case 3:
			case 4:
				return $forms[1];
			default:
				return $forms[2];
		}
	}
}
