<?php
/**
 * Copyright © 2009 Roan Kattouw <roan.kattouw@gmail.com>
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

use ImportReporter;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Title\ForeignTitle;

/**
 * Import reporter for the API
 * @ingroup API
 */
class ApiImportReporter extends ImportReporter {
	/** @var array[] */
	private $mResultArr = [];

	/**
	 * @param ?PageIdentity $pageIdentity
	 * @param ForeignTitle $foreignTitle
	 * @param int $revisionCount
	 * @param int $successCount
	 * @param array $pageInfo
	 * @return void
	 */
	public function reportPage( ?PageIdentity $pageIdentity, $foreignTitle, $revisionCount, $successCount, $pageInfo ) {
		// Add a result entry
		$r = [];

		if ( $pageIdentity === null ) {
			# Invalid or non-importable title
			$r['title'] = $pageInfo['title'];
			$r['invalid'] = true;
		} else {
			$titleFactory = MediaWikiServices::getInstance()->getTitleFactory();
			ApiQueryBase::addTitleInfo( $r, $titleFactory->newFromPageIdentity( $pageIdentity ) );
			$r['revisions'] = (int)$successCount;
		}

		$this->mResultArr[] = $r;

		// Piggyback on the parent to do the logging
		parent::reportPage( $pageIdentity, $foreignTitle, $revisionCount, $successCount, $pageInfo );
	}

	public function getData(): array {
		return $this->mResultArr;
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiImportReporter::class, 'ApiImportReporter' );
