<?php

/**
 * Augment search results.
 * @stable to implement
 */
interface ResultAugmentor {
	/**
	 * Produce data to augment search result set.
	 * @param SearchResult $result
	 * @return mixed Data for this result
	 */
	public function augment( SearchResult $result );
}
