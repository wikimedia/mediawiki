<?php
/** Burmese (Myanmasa)
 *
 * @ingroup Language
 * @file
 *
 * @author Niklas Laxström, 2008
 */

class LanguageMy extends Language {
	function commafy( $_ ) {
		/* NO-op. Cannot use
		 * $separatorTransformTable = array( ',' => '' )
		 * That would break when parsing and doing strstr '' => 'foo';
		 */
		return $_;
	}
}