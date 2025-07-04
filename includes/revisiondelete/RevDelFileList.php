<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup RevisionDelete
 */

use MediaWiki\Cache\HTMLCacheUpdater;
use MediaWiki\Context\IContextSource;
use MediaWiki\FileRepo\File\File;
use MediaWiki\FileRepo\File\FileSelectQueryBuilder;
use MediaWiki\FileRepo\FileRepo;
use MediaWiki\FileRepo\RepoGroup;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Status\Status;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\LBFactory;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * List for oldimage table items
 */
class RevDelFileList extends RevDelList {

	protected const SUPPRESS_BIT = File::DELETED_RESTRICTED;

	/** @var HTMLCacheUpdater */
	private $htmlCacheUpdater;

	/** @var RepoGroup */
	private $repoGroup;

	/** @var array */
	public $storeBatch;

	/** @var array */
	public $deleteBatch;

	/** @var array */
	public $cleanupBatch;

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
		parent::__construct( $context, $page, $ids, $lbFactory );
		$this->htmlCacheUpdater = $htmlCacheUpdater;
		$this->repoGroup = $repoGroup;
	}

	/** @inheritDoc */
	public function getType() {
		return 'oldimage';
	}

	/** @inheritDoc */
	public static function getRelationType() {
		return 'oi_archive_name';
	}

	/** @inheritDoc */
	public static function getRestriction() {
		return 'deleterevision';
	}

	/** @inheritDoc */
	public static function getRevdelConstant() {
		return File::DELETED_FILE;
	}

	/**
	 * @param IReadableDatabase $db
	 * @return IResultWrapper
	 */
	public function doQuery( $db ) {
		$archiveNames = [];
		foreach ( $this->ids as $timestamp ) {
			$archiveNames[] = $timestamp . '!' . $this->page->getDBkey();
		}

		$queryBuilder = FileSelectQueryBuilder::newForOldFile( $db );
		$queryBuilder
			->where( [ 'oi_name' => $this->page->getDBkey(), 'oi_archive_name' => $archiveNames ] )
			->orderBy( 'oi_timestamp', SelectQueryBuilder::SORT_DESC );
		return $queryBuilder->caller( __METHOD__ )->fetchResultSet();
	}

	/** @inheritDoc */
	public function newItem( $row ) {
		return new RevDelFileItem( $this, $row );
	}

	public function clearFileOps() {
		$this->deleteBatch = [];
		$this->storeBatch = [];
		$this->cleanupBatch = [];
	}

	/** @inheritDoc */
	public function doPreCommitUpdates() {
		$status = Status::newGood();
		$repo = $this->repoGroup->getLocalRepo();
		if ( $this->storeBatch ) {
			$status->merge( $repo->storeBatch( $this->storeBatch, FileRepo::OVERWRITE_SAME ) );
		}
		if ( !$status->isOK() ) {
			return $status;
		}
		if ( $this->deleteBatch ) {
			$status->merge( $repo->deleteBatch( $this->deleteBatch ) );
		}
		if ( !$status->isOK() ) {
			// Running cleanupDeletedBatch() after a failed storeBatch() with the DB already
			// modified (but destined for rollback) causes data loss
			return $status;
		}
		if ( $this->cleanupBatch ) {
			$status->merge( $repo->cleanupDeletedBatch( $this->cleanupBatch ) );
		}

		return $status;
	}

	/** @inheritDoc */
	public function doPostCommitUpdates( array $visibilityChangeMap ) {
		$file = $this->repoGroup->getLocalRepo()->newFile( $this->page );
		$file->purgeCache();
		$file->purgeDescription();

		// Purge full images from cache
		$purgeUrls = [];
		foreach ( $this->ids as $timestamp ) {
			$archiveName = $timestamp . '!' . $this->page->getDBkey();
			$file->purgeOldThumbnails( $archiveName );
			$purgeUrls[] = $file->getArchiveUrl( $archiveName );
		}

		$this->htmlCacheUpdater->purgeUrls(
			$purgeUrls,
			HTMLCacheUpdater::PURGE_INTENT_TXROUND_REFLECTED
		);

		return Status::newGood();
	}

}
