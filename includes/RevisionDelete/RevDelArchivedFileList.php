<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup RevisionDelete
 */

use MediaWiki\Cache\HTMLCacheUpdater;
use MediaWiki\Context\IContextSource;
use MediaWiki\FileRepo\File\FileSelectQueryBuilder;
use MediaWiki\FileRepo\RepoGroup;
use MediaWiki\Page\PageIdentity;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\LBFactory;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * List for filearchive table items
 */
class RevDelArchivedFileList extends RevDelFileList {

	/**
	 * @param IContextSource $context
	 * @param PageIdentity $page
	 * @param array $ids
	 * @param LBFactory $lbFactory
	 * @param HTMLCacheUpdater $htmlCacheUpdater
	 * @param RepoGroup $repoGroup
	 */
	public function __construct(
		IContextSource $context,
		PageIdentity $page,
		array $ids,
		LBFactory $lbFactory,
		HTMLCacheUpdater $htmlCacheUpdater,
		RepoGroup $repoGroup
	) {
		parent::__construct(
			$context,
			$page,
			$ids,
			$lbFactory,
			$htmlCacheUpdater,
			$repoGroup
		);
		// Technically, we could just inherit the constructor from RevDelFileList,
		// but since ArchivedFile::getQueryInfo() uses MediaWikiServices it might
		// be useful to replace at some point with either a callback or a separate
		// service to allow for unit testing
	}

	/** @inheritDoc */
	public function getType() {
		return 'filearchive';
	}

	/** @inheritDoc */
	public static function getRelationType() {
		return 'fa_id';
	}

	/**
	 * @param IReadableDatabase $db
	 * @return IResultWrapper
	 */
	public function doQuery( $db ) {
		$ids = array_map( 'intval', $this->ids );

		$queryBuilder = FileSelectQueryBuilder::newForArchivedFile( $db );
		$queryBuilder->where( [ 'fa_name' => $this->page->getDBkey(), 'fa_id' => $ids ] )
			->orderBy( 'fa_id', SelectQueryBuilder::SORT_DESC );

		return $queryBuilder->caller( __METHOD__ )->fetchResultSet();
	}

	/** @inheritDoc */
	public function newItem( $row ) {
		return new RevDelArchivedFileItem( $this, $row );
	}
}
