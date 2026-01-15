<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @author Niklas Laxström, 2008
 */

namespace MediaWiki\Languages;

use MediaWiki\Language\Language;

/**
 * Burmese (Myanmasa)
 *
 * @ingroup Languages
 */
class LanguageMy extends Language {

	/**
	 * @param string|int|float $number
	 * @return string
	 */
	public function formatNum( $number ) {
		/* NO-op. Cannot use
		 * $separatorTransformTable = [ ',' => '' ]
		 * That would break when parsing and doing strstr '' => 'foo';
		 */
		return $this->formatNumNoSeparators( $number );
	}
}

/** @deprecated class alias since 1.46 */
class_alias( LanguageMy::class, 'LanguageMy' );
