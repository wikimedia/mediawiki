<?php

namespace MediaWiki\Search;

use MediaWiki\FileRepo\File\File;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Title\Title;

/**
 * A manually constructed search result, for use with FauxSearchResultSet.
 */
class FauxSearchResult extends RevisionSearchResult {

	public function __construct(
		Title $title,
		?RevisionRecord $revRecord = null,
		?File $image = null,
		?string $text = ''
	) {
		parent::__construct( $title );
		$this->mRevisionRecord = $revRecord;
		$this->mImage = $image;
		$this->mText = $text;
	}

	/**
	 * @inheritDoc
	 */
	protected function initFromTitle( $title ) {
		// everything's initialized in the constructor
	}
}

/** @deprecated class alias since 1.46 */
class_alias( FauxSearchResult::class, 'FauxSearchResult' );
