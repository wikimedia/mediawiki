<?php

use MediaWiki\FileRepo\File\File;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Title\Title;

/**
 * A manually constructed search result, for use with FauxSearchResultSet.
 */
class FauxSearchResult extends SearchResult {

	use RevisionSearchResultTrait;

	public function __construct(
		Title $title,
		?RevisionRecord $revRecord = null,
		?File $image = null,
		?string $text = ''
	) {
		parent::__construct();
		$this->mTitle = $title;
		$this->mRevisionRecord = $revRecord;
		$this->mImage = $image;
		$this->mText = $text;
	}

}
