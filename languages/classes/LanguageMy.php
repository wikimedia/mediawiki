<?php

/** Burmese (Myanmasa)
 *
 * @ingroup Language
 *
 * @author Niklas Laxström, 2008
 */
class LanguageMy extends Language {

	/**
	 * @param $_ string
	 * @return string
	 */
	function commafy( $_ ) {
		/* NO-op. Cannot use
		 * $separatorTransformTable = array( ',' => '' )
		 * That would break when parsing and doing strstr '' => 'foo';
		 */
		return $_;
	}
}