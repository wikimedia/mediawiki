<?php

/**
 * Augment search results.
 *
 */
interface ResultSetAugmentor {
	/**
	 * Produce data to augment search result set.
	 * @param SearchResultSet $resultSet
	 * @return array Data for all results
	 */
	public function augmentAll( SearchResultSet $resultSet );
}
