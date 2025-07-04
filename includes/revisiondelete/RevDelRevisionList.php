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
use MediaWiki\DomainEvent\DomainEventDispatcher;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\Event\PageHistoryVisibilityChangedEvent;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\ProperPageIdentity;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Status\Status;
use MediaWiki\Title\Title;
use Wikimedia\Rdbms\FakeResultWrapper;
use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\LBFactory;

/**
 * List for revision table items
 *
 * This will check both the 'revision' table for live revisions and the
 * 'archive' table for traditionally-deleted revisions that have an
 * ar_rev_id saved.
 *
 * See RevDelRevisionItem and RevDelArchivedRevisionItem for items.
 */
class RevDelRevisionList extends RevDelList {

	/** @var LBFactory */
	private $lbFactory;

	/** @var HookRunner */
	private $hookRunner;

	/** @var HTMLCacheUpdater */
	private $htmlCacheUpdater;

	/** @var RevisionStore */
	private $revisionStore;

	/** @var int */
	public $currentRevId;
	private DomainEventDispatcher $eventDispatcher;

	public function __construct(
		IContextSource $context,
		PageIdentity $page,
		array $ids,
		LBFactory $lbFactory,
		HookContainer $hookContainer,
		HTMLCacheUpdater $htmlCacheUpdater,
		RevisionStore $revisionStore,
		DomainEventDispatcher $eventDispatcher
	) {
		parent::__construct( $context, $page, $ids, $lbFactory );
		$this->lbFactory = $lbFactory;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->htmlCacheUpdater = $htmlCacheUpdater;
		$this->revisionStore = $revisionStore;
		$this->eventDispatcher = $eventDispatcher;
	}

	/** @inheritDoc */
	public function getType() {
		return 'revision';
	}

	/** @inheritDoc */
	public static function getRelationType() {
		return 'rev_id';
	}

	/** @inheritDoc */
	public static function getRestriction() {
		return 'deleterevision';
	}

	/** @inheritDoc */
	public static function getRevdelConstant() {
		return RevisionRecord::DELETED_TEXT;
	}

	/** @inheritDoc */
	public static function suggestTarget( $target, array $ids ) {
		$revisionRecord = MediaWikiServices::getInstance()
			->getRevisionLookup()
			->getRevisionById( $ids[0] );

		if ( $revisionRecord ) {
			return Title::newFromPageIdentity( $revisionRecord->getPage() );
		}
		return $target;
	}

	/**
	 * @param \Wikimedia\Rdbms\IReadableDatabase $db
	 * @return IResultWrapper
	 */
	public function doQuery( $db ) {
		$ids = array_map( 'intval', $this->ids );
		$queryBuilder = $this->revisionStore->newSelectQueryBuilder( $db )
			->joinComment()
			->joinUser()
			->joinPage()
			->where( [ 'rev_page' => $this->page->getId(), 'rev_id' => $ids ] )
			->orderBy( 'rev_id', \Wikimedia\Rdbms\SelectQueryBuilder::SORT_DESC )
			// workaround for MySQL bug (T104313)
			->useIndex( [ 'revision' => 'PRIMARY' ] );

		MediaWikiServices::getInstance()->getChangeTagsStore()->modifyDisplayQueryBuilder( $queryBuilder, 'revision' );

		$live = $queryBuilder->caller( __METHOD__ )->fetchResultSet();
		if ( $live->numRows() >= count( $ids ) ) {
			// All requested revisions are live, keeps things simple!
			return $live;
		}

		$queryBuilder = $this->revisionStore->newArchiveSelectQueryBuilder( $db )
			->joinComment()
			->where( [ 'ar_rev_id' => $ids ] )
			->orderBy( 'ar_rev_id', \Wikimedia\Rdbms\SelectQueryBuilder::SORT_DESC );

		MediaWikiServices::getInstance()->getChangeTagsStore()->modifyDisplayQueryBuilder( $queryBuilder, 'archive' );

		// Check if any requested revisions are available fully deleted.
		$archived = $queryBuilder->caller( __METHOD__ )->fetchResultSet();

		if ( $archived->numRows() == 0 ) {
			return $live;
		} elseif ( $live->numRows() == 0 ) {
			return $archived;
		} else {
			// Combine the two! Whee
			$rows = [];
			foreach ( $live as $row ) {
				$rows[$row->rev_id] = $row;
			}
			foreach ( $archived as $row ) {
				$rows[$row->ar_rev_id] = $row;
			}
			krsort( $rows );
			return new FakeResultWrapper( array_values( $rows ) );
		}
	}

	/** @inheritDoc */
	public function newItem( $row ) {
		if ( isset( $row->rev_id ) ) {
			return new RevDelRevisionItem( $this, $row );
		} elseif ( isset( $row->ar_rev_id ) ) {
			return new RevDelArchivedRevisionItem( $this, $row, $this->lbFactory );
		} else {
			// This shouldn't happen. :)
			throw new InvalidArgumentException( 'Invalid row type in RevDelRevisionList' );
		}
	}

	/** @inheritDoc */
	public function getCurrent() {
		if ( $this->currentRevId === null ) {
			$dbw = $this->lbFactory->getPrimaryDatabase();
			$this->currentRevId = $dbw->newSelectQueryBuilder()
				->select( 'page_latest' )
				->from( 'page' )
				->where( [ 'page_namespace' => $this->page->getNamespace(), 'page_title' => $this->page->getDBkey() ] )
				->caller( __METHOD__ )->fetchField();
		}
		return $this->currentRevId;
	}

	/**
	 * @param array $bitPars See RevisionDeleter::extractBitfield
	 * @param array $visibilityChangeMap [id => ['oldBits' => $oldBits, 'newBits' => $newBits], ... ]
	 * @param array $tags
	 * @param LogEntry $logEntry
	 * @param bool $suppressed
	 */
	protected function emitEvents(
		array $bitPars,
		array $visibilityChangeMap,
		array $tags,
		LogEntry $logEntry,
		bool $suppressed
	) {
		// Figure out which bits got set, and which got unset.
		$bitsSet = RevisionDeleter::extractBitfield( $bitPars, 0 );
		$bitsUnset = RevisionRecord::SUPPRESSED_ALL &
			( ~ RevisionDeleter::extractBitfield( $bitPars, RevisionRecord::SUPPRESSED_ALL ) );

		$page = $this->getPage();
		$performer = $this->getUser();

		// Hack: make sure we have a *proper* PageIdentity
		if ( !$page instanceof ProperPageIdentity ) {
			if ( !$page instanceof Title ) {
				$page = Title::newFromPageIdentity( $page );
			}

			$page = $page->toPageIdentity();
		}

		$flags = [
			PageHistoryVisibilityChangedEvent::FLAG_SUPPRESSED => $suppressed
		];

		$this->eventDispatcher->dispatch(
			new PageHistoryVisibilityChangedEvent(
				$page,
				$performer,
				$this->getCurrent(),
				$bitsSet,
				$bitsUnset,
				$visibilityChangeMap,
				$logEntry->getComment(),
				$tags,
				$flags,
				$logEntry->getTimestamp()
			),
			$this->lbFactory
		);
	}

	/** @inheritDoc */
	public function doPreCommitUpdates() {
		Title::newFromPageIdentity( $this->page )->invalidateCache();
		return Status::newGood();
	}

	/** @inheritDoc */
	public function doPostCommitUpdates( array $visibilityChangeMap ) {
		$this->htmlCacheUpdater->purgeTitleUrls(
			$this->page,
			HTMLCacheUpdater::PURGE_INTENT_TXROUND_REFLECTED
		);
		// Extensions that require referencing previous revisions may need this
		$this->hookRunner->onArticleRevisionVisibilitySet(
			Title::newFromPageIdentity( $this->page ),
			$this->ids,
			$visibilityChangeMap
		);

		return Status::newGood();
	}
}
