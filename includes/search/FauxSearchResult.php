<?php

use MediaWiki\Revision\RevisionRecord;

/**
 * A manually constructed search result, for use with FauxSearchResultSet.
 */
class FauxSearchResult extends SearchResult {

	use RevisionSearchResultTrait;

	public function __construct(
		Title $title,
		RevisionRecord $revision = null,
		File $image = null,
		$text = ''
	) {
		$this->mTitle = $title;
		$this->mRevision = $revision ? new Revision( $revision ) : null;
		$this->mImage = $image;
		$this->mText = $text;
	}

}
