<?php

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
