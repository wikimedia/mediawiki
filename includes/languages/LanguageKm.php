<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @author Niklas Laxström
 */

use MediaWiki\Language\Language;

/**
 * Khmer (ភាសាខ្មែរ)
 *
 * @ingroup Languages
 */
class LanguageKm extends Language {

	/**
	 * @param string|int|float $number
	 *
	 * @return string
	 */
	public function formatNum( $number ) {
		/**
		 * NO-op for Khmer. Cannot use
		 * $separatorTransformTable = [ ',' => '' ]
		 * That would break when parsing and doing strstr '' => 'foo';
		 */
		return $this->formatNumNoSeparators( $number );
	}

}
