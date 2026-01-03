<?php

namespace MediaWiki\Search;

/**
 * Augment search results.
 * @stable to implement
 */
interface ResultSetAugmentor {
	/**
	 * Produce data to augment search result set.
	 * @param ISearchResultSet $resultSet
	 * @return array Data for all results
	 */
	public function augmentAll( ISearchResultSet $resultSet );
}

/** @deprecated class alias since 1.46 */
class_alias( ResultSetAugmentor::class, 'ResultSetAugmentor' );
