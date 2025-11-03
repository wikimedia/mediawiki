<?php
/**
 * Prefix search of page names.
 *
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Title\Title;

/**
 * Performs prefix search, returning strings
 * @deprecated Since 1.27, Use SearchEngine::prefixSearchSubpages or SearchEngine::completionSearch
 * @ingroup Search
 */
class StringPrefixSearch extends PrefixSearch {

	/**
	 * @param Title[] $titles
	 * @return string[]
	 */
	protected function titles( array $titles ) {
		return array_map( static function ( Title $t ) {
			return $t->getPrefixedText();
		}, $titles );
	}

	/**
	 * @param string[] $strings
	 * @return string[]
	 */
	protected function strings( array $strings ) {
		return $strings;
	}
}
