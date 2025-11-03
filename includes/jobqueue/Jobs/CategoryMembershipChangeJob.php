<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\JobQueue\Jobs;

use MediaWiki\JobQueue\Job;
use MediaWiki\JobQueue\JobSpecification;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\WikiPage;
use MediaWiki\RecentChanges\CategoryMembershipChange;
use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\RecentChanges\RecentChangeFactory;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStoreRecord;
use MediaWiki\Title\Title;
use Wikimedia\Rdbms\IDBAccessObject;
use Wikimedia\Rdbms\LBFactory;
use Wikimedia\Rdbms\RawSQLExpression;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * Job to add recent change entries mentioning category membership changes
 *
 * This allows users to easily scan categories for recent page membership changes
 *
 * Parameters include:
 *   - pageId : page ID
 *   - revTimestamp : timestamp of the triggering revision
 *
 * Category changes will be mentioned for revisions at/after the timestamp for this page
 *
 * @since 1.27
 * @ingroup JobQueue
 */
class CategoryMembershipChangeJob extends Job {
	/** @var int|null */
	private $ticket;

	private RecentChangeFactory $recentChangeFactory;

	private const ENQUEUE_FUDGE_SEC = 60;

	/**
	 * @param PageIdentity $page the page for which to update category membership.
	 * @param string $revisionTimestamp The timestamp of the new revision that triggered the job.
	 * @param bool $forImport Whether the new revision that triggered the import was imported
	 * @return JobSpecification
	 */
	public static function newSpec( PageIdentity $page, $revisionTimestamp, bool $forImport ) {
		return new JobSpecification(
			'categoryMembershipChange',
			[
				'pageId' => $page->getId(),
				'revTimestamp' => $revisionTimestamp,
				'forImport' => $forImport,
			],
			[
				'removeDuplicates' => true,
				'removeDuplicatesIgnoreParams' => [ 'revTimestamp' ]
			],
			$page
		);
	}

	/**
	 * Constructor for use by the Job Queue infrastructure.
	 * @note Don't call this when queueing a new instance, use newSpec() instead.
	 * @param PageIdentity $page the categorized page.
	 * @param array $params Such latest revision instance of the categorized page.
	 * @param RecentChangeFactory $recentChangeFactory
	 */
	public function __construct(
		PageIdentity $page,
		array $params,
		RecentChangeFactory $recentChangeFactory
	) {
		parent::__construct( 'categoryMembershipChange', $page, $params );
		$this->recentChangeFactory = $recentChangeFactory;
		// Only need one job per page. Note that ENQUEUE_FUDGE_SEC handles races where an
		// older revision job gets inserted while the newer revision job is de-duplicated.
		$this->removeDuplicates = true;
	}

	/** @inheritDoc */
	public function run() {
		$services = MediaWikiServices::getInstance();
		$lbFactory = $services->getDBLoadBalancerFactory();
		$lb = $lbFactory->getMainLB();
		$dbw = $lb->getConnection( DB_PRIMARY );

		$this->ticket = $lbFactory->getEmptyTransactionTicket( __METHOD__ );

		$page = $services->getWikiPageFactory()->newFromID( $this->params['pageId'], IDBAccessObject::READ_LATEST );
		if ( !$page ) {
			$this->setLastError( "Could not find page #{$this->params['pageId']}" );
			return false; // deleted?
		}

		// Cut down on the time spent in waitForPrimaryPos() in the critical section
		$dbr = $lb->getConnection( DB_REPLICA );
		if ( !$lb->waitForPrimaryPos( $dbr ) ) {
			$this->setLastError( "Timed out while pre-waiting for replica DB to catch up" );
			return false;
		}

		// Use a named lock so that jobs for this page see each others' changes
		$lockKey = "{$dbw->getDomainID()}:CategoryMembershipChange:{$page->getId()}"; // per-wiki
		$scopedLock = $dbw->getScopedLockAndFlush( $lockKey, __METHOD__, 1 );
		if ( !$scopedLock ) {
			$this->setLastError( "Could not acquire lock '$lockKey'" );
			return false;
		}

		// Wait till replica DB is caught up so that jobs for this page see each others' changes
		if ( !$lb->waitForPrimaryPos( $dbr ) ) {
			$this->setLastError( "Timed out while waiting for replica DB to catch up" );
			return false;
		}
		// Clear any stale REPEATABLE-READ snapshot
		$dbr->flushSnapshot( __METHOD__ );

		$cutoffUnix = wfTimestamp( TS_UNIX, $this->params['revTimestamp'] );
		// Using ENQUEUE_FUDGE_SEC handles jobs inserted out of revision order due to the delay
		// between COMMIT and actual enqueueing of the CategoryMembershipChangeJob job.
		$cutoffUnix -= self::ENQUEUE_FUDGE_SEC;

		// Get the newest page revision that has a SRC_CATEGORIZE row.
		// Assume that category changes before it were already handled.
		$subQuery = $dbr->newSelectQueryBuilder()
			->select( '1' )
			->from( 'recentchanges' )
			->where( 'rc_this_oldid = rev_id' )
			->andWhere( [ 'rc_source' => RecentChange::SRC_CATEGORIZE ] );
		$row = $dbr->newSelectQueryBuilder()
			->select( [ 'rev_timestamp', 'rev_id' ] )
			->from( 'revision' )
			->where( [ 'rev_page' => $page->getId() ] )
			->andWhere( $dbr->expr( 'rev_timestamp', '>=', $dbr->timestamp( $cutoffUnix ) ) )
			->andWhere( new RawSQLExpression( 'EXISTS (' . $subQuery->getSQL() . ')' ) )
			->orderBy( [ 'rev_timestamp', 'rev_id' ], SelectQueryBuilder::SORT_DESC )
			->caller( __METHOD__ )->fetchRow();

		// Only consider revisions newer than any such revision
		if ( $row ) {
			$cutoffUnix = wfTimestamp( TS_UNIX, $row->rev_timestamp );
			$lastRevId = (int)$row->rev_id;
		} else {
			$lastRevId = 0;
		}

		// Find revisions to this page made around and after this revision which lack category
		// notifications in recent changes. This lets jobs pick up were the last one left off.
		$revisionStore = $services->getRevisionStore();
		$res = $revisionStore->newSelectQueryBuilder( $dbr )
			->joinComment()
			->where( [
				'rev_page' => $page->getId(),
				$dbr->buildComparison( '>', [
					'rev_timestamp' => $dbr->timestamp( $cutoffUnix ),
					'rev_id' => $lastRevId,
				] )
			] )
			->orderBy( [ 'rev_timestamp', 'rev_id' ], SelectQueryBuilder::SORT_ASC )
			->caller( __METHOD__ )->fetchResultSet();

		// Apply all category updates in revision timestamp order
		foreach ( $res as $row ) {
			$this->notifyUpdatesForRevision( $lbFactory, $page, $revisionStore->newRevisionFromRow( $row ) );
		}

		return true;
	}

	/**
	 * @param LBFactory $lbFactory
	 * @param WikiPage $page
	 * @param RevisionRecord $newRev
	 */
	protected function notifyUpdatesForRevision(
		LBFactory $lbFactory, WikiPage $page, RevisionRecord $newRev
	) {
		$title = $page->getTitle();

		// Get the new revision
		if ( $newRev->isDeleted( RevisionRecord::DELETED_TEXT ) ) {
			return;
		}

		$services = MediaWikiServices::getInstance();
		// Get the prior revision (the same for null edits)
		if ( $newRev->getParentId() ) {
			$oldRev = $services->getRevisionLookup()
				->getRevisionById( $newRev->getParentId(), IDBAccessObject::READ_LATEST );
			if ( !$oldRev || $oldRev->isDeleted( RevisionRecord::DELETED_TEXT ) ) {
				return;
			}
		} else {
			$oldRev = null;
		}

		// Parse the new revision and get the categories
		$categoryChanges = $this->getExplicitCategoriesChanges( $page, $newRev, $oldRev );
		[ $categoryInserts, $categoryDeletes ] = $categoryChanges;
		if ( !$categoryInserts && !$categoryDeletes ) {
			return; // nothing to do
		}

		$blc = $services->getBacklinkCacheFactory()->getBacklinkCache( $title );
		$catMembChange = new CategoryMembershipChange(
			$title,
			$blc,
			$newRev,
			$this->recentChangeFactory,
			$this->params['forImport'] ?? false
		);
		$catMembChange->checkTemplateLinks();

		$batchSize = $services->getMainConfig()->get( MainConfigNames::UpdateRowsPerQuery );
		$insertCount = 0;

		foreach ( $categoryInserts as $categoryName ) {
			$categoryTitle = Title::makeTitle( NS_CATEGORY, $categoryName );
			$catMembChange->triggerCategoryAddedNotification( $categoryTitle );
			if ( $insertCount++ && ( $insertCount % $batchSize ) == 0 ) {
				$lbFactory->commitAndWaitForReplication( __METHOD__, $this->ticket );
			}
		}

		foreach ( $categoryDeletes as $categoryName ) {
			$categoryTitle = Title::makeTitle( NS_CATEGORY, $categoryName );
			$catMembChange->triggerCategoryRemovedNotification( $categoryTitle );
			if ( $insertCount++ && ( $insertCount++ % $batchSize ) == 0 ) {
				$lbFactory->commitAndWaitForReplication( __METHOD__, $this->ticket );
			}
		}
	}

	private function getExplicitCategoriesChanges(
		WikiPage $page, RevisionRecord $newRev, ?RevisionRecord $oldRev = null
	): array {
		// Inject the same timestamp for both revision parses to avoid seeing category changes
		// due to time-based parser functions. Inject the same page title for the parses too.
		// Note that REPEATABLE-READ makes template/file pages appear unchanged between parses.
		$parseTimestamp = $newRev->getTimestamp();
		// Parse the old rev and get the categories. Do not use link tables as that
		// assumes these updates are perfectly FIFO and that link tables are always
		// up to date, neither of which are true.
		$oldCategories = $oldRev
			? $this->getCategoriesAtRev( $page, $oldRev, $parseTimestamp )
			: [];
		// Parse the new revision and get the categories
		$newCategories = $this->getCategoriesAtRev( $page, $newRev, $parseTimestamp );

		$categoryInserts = array_values( array_diff( $newCategories, $oldCategories ) );
		$categoryDeletes = array_values( array_diff( $oldCategories, $newCategories ) );

		return [ $categoryInserts, $categoryDeletes ];
	}

	/**
	 * @param WikiPage $page
	 * @param RevisionRecord $rev
	 * @param string $parseTimestamp TS_MW
	 *
	 * @return string[] category names
	 */
	private function getCategoriesAtRev( WikiPage $page, RevisionRecord $rev, $parseTimestamp ) {
		$services = MediaWikiServices::getInstance();
		$options = $page->makeParserOptions( 'canonical' );
		$options->setTimestamp( $parseTimestamp );
		$options->setRenderReason( 'CategoryMembershipChangeJob' );

		$output = $rev instanceof RevisionStoreRecord && $rev->isCurrent()
			? $services->getParserCache()->get( $page, $options )
			: null;

		if ( !$output || $output->getCacheRevisionId() !== $rev->getId() ) {
			$output = $services->getRevisionRenderer()->getRenderedRevision( $rev, $options )
				->getRevisionParserOutput();
		}

		// array keys will cast numeric category names to ints;
		// ::getCategoryNames() is careful to cast them back to strings
		// to avoid breaking things!
		return $output->getCategoryNames();
	}

	/** @inheritDoc */
	public function getDeduplicationInfo() {
		$info = parent::getDeduplicationInfo();
		unset( $info['params']['revTimestamp'] ); // first job wins

		return $info;
	}
}

/** @deprecated class alias since 1.44 */
class_alias( CategoryMembershipChangeJob::class, 'CategoryMembershipChangeJob' );
