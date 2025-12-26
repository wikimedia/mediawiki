<?php
/**
 * Prefix search of page names.
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Search;

use MediaWiki\MediaWikiServices;
use MediaWiki\Title\Title;

/**
 * Performs prefix search, returning Title objects
 * @deprecated Since 1.27, Use SearchEngine::defaultPrefixSearch or SearchEngine::completionSearch
 * @ingroup Search
 */
class TitlePrefixSearch extends PrefixSearch {

	/**
	 * @param Title[] $titles
	 * @return Title[]
	 */
	protected function titles( array $titles ) {
		return $titles;
	}

	/**
	 * @param string[] $strings
	 * @return Title[]
	 */
	protected function strings( array $strings ) {
		$titles = array_map( Title::newFromText( ... ), $strings );
		$linkBatchFactory = MediaWikiServices::getInstance()->getLinkBatchFactory();
		$linkBatchFactory->newLinkBatch( $titles )
			->setCaller( __METHOD__ )
			->execute();
		return $titles;
	}
}

/** @deprecated class alias since 1.46 */
class_alias( TitlePrefixSearch::class, 'TitlePrefixSearch' );
