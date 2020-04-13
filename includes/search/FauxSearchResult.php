<?php

use MediaWiki\Revision\RevisionRecord;

/**
 * A manually constructed search result, for use with FauxSearchResultSet.
 */
class FauxSearchResult extends SearchResult {

	use RevisionSearchResultTrait;

	public function __construct(
		Title $title,
		RevisionRecord $revRecord = null,
		File $image = null,
		$text = ''
	) {
		$this->mTitle = $title;
		$this->mRevisionRecord = $revRecord;
		$this->mImage = $image;
		$this->mText = $text;
	}

}
