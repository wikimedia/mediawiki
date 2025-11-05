<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Language\Language;
use MediaWiki\Languages\Data\NormalizeMl;

/**
 * Malayalam (മലയാളം)
 *
 * @ingroup Languages
 */
class LanguageMl extends Language {
	/**
	 * Convert Unicode 5.0 style Malayalam input to Unicode 5.1, similar to T11413
	 * which is the same issue for Arabic.
	 *
	 * Also fixes miscellaneous problems due to mishandling of ZWJ (e.g. T13162).
	 *
	 * Originally introduced after "[wikitech-l] Unicode equivalence" (Dec 2009)
	 * <https://lists.wikimedia.org/hyperkitty/list/wikitech-l@lists.wikimedia.org/thread/LMMZ3M4757Z5DH42MT75K6GWLAKUBLRD>
	 *
	 * Optimization: This is language-specific to reduce negative performance impact.
	 *
	 * @param string $s
	 * @return string
	 */
	public function normalize( $s ) {
		$s = parent::normalize( $s );
		return $this->transformUsingPairFile( NormalizeMl::class, $s );
	}
}
