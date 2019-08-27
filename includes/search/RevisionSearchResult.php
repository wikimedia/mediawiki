<?php

/**
 * SearchResult class based on the Revision information.
 * This class is suited for search engines that do not store a specialized version of the searched
 * content.
 */
class RevisionSearchResult extends SearchResult {
	use RevisionSearchResultTrait;

	/**
	 * @param Title|null $title
	 */
	public function __construct( $title ) {
		$this->mTitle = $title;
		$this->initFromTitle( $title );
	}
}
