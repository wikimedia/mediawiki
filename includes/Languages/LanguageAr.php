<?php

/**
 * @license GPL-2.0-or-later
 * @file
 * @author Niklas Laxström
 */

use MediaWiki\Language\Language;
use MediaWiki\Languages\Data\NormalizeAr;

/**
 * Arabic (العربية) specific code.
 *
 * @ingroup Languages
 */
class LanguageAr extends Language {

	/**
	 * Replace Arabic presentation forms with their standard equivalents (T11413).
	 *
	 * Optimization: This is language-specific to reduce negative performance impact.
	 *
	 * @param string $s
	 * @return string
	 */
	public function normalize( $s ) {
		$s = parent::normalize( $s );
		return $this->transformUsingPairFile( NormalizeAr::class, $s );
	}
}
