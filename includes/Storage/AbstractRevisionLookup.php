<?php

namespace MediaWiki\Storage;

use IDBAccessObject;
use MediaWiki\Linker\LinkTarget;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Title;
use WANObjectCache;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\DBConnRef;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\LoadBalancer;

abstract class AbstractRevisionLookup implements IDBAccessObject, RevisionLookup, LoggerAwareInterface {

	use DatabaseWikiIdChecker;

	/**
	 * @var LoadBalancer
	 */
	protected $loadBalancer;

	/**
	 * @var WANObjectCache
	 */
	protected $cache;
	/**
	 * @var RevisionFactory
	 */
	protected $revisionFactory;

	/**
	 * @var RevisionTitleLookup
	 */
	protected $revisionTitleLookup;

	/**
	 * @var LoggerInterface
	 */
	protected $logger;

	/**
	 * @var boolean
	 */
	protected $contentHandlerUseDB = true;

	public function __construct(
		LoadBalancer $loadBalancer,
		WANObjectCache $cache,
		RevisionFactory $revisionFactory,
		RevisionTitleLookup $revisionTitleLookup
	) {
		$this->loadBalancer = $loadBalancer;
		$this->cache = $cache;
		$this->revisionFactory = $revisionFactory;
		$this->revisionTitleLookup = $revisionTitleLookup;
		$this->logger = new NullLogger();
	}

	/**
	 * @param int $mode DB_MASTER or DB_REPLICA
	 *
	 * @return IDatabase
	 */
	abstract protected function getDBConnection( $mode );

	/**
	 * @param int $mode DB_MASTER or DB_REPLICA
	 *
	 * @return DBConnRef
	 */
	abstract protected function getDBConnectionRef( $mode );

	/**
	 * @return bool
	 */
	public function getContentHandlerUseDB() {
		return $this->contentHandlerUseDB;
	}

	/**
	 * @param bool $contentHandlerUseDB
	 */
	public function setContentHandlerUseDB( $contentHandlerUseDB ) {
		$this->contentHandlerUseDB = $contentHandlerUseDB;
	}

	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	/**
	 * Given a set of conditions, return a row with the
	 * fields necessary to build RevisionRecord objects.
	 *
	 * MCR migration note: this corresponds to Revision::fetchFromConds
	 *
	 * @param IDatabase $db
	 * @param array $conditions
	 * @param int $flags (optional)
	 *
	 * @return object|false data row as a raw object
	 */
	abstract protected function fetchRevisionRowFromConds( IDatabase $db, $conditions, $flags = 0 );

	/**
	 * Given a set of conditions, fetch a revision
	 *
	 * This method should be used if we are pretty sure the revision exists.
	 * Unless $flags has READ_LATEST set, this method will first try to find the revision
	 * on a replica before hitting the master database.
	 *
	 * MCR migration note: this corresponds to Revision::newFromConds
	 *
	 * @param array $conditions
	 * @param int $flags (optional)
	 * @param Title $title
	 *
	 * @return RevisionRecord|null
	 */
	protected function newRevisionFromConds( $conditions, $flags = 0, Title $title = null ) {
		$lb = $this->loadBalancer;
		$db = $this->getDBConnection( ( $flags & self::READ_LATEST ) ? DB_MASTER : DB_REPLICA );
		$rev = $this->loadRevisionFromConds( $db, $conditions, $flags, $title );
		$lb->reuseConnection( $db );

		// Make sure new pending/committed revision are visibile later on
		// within web requests to certain avoid bugs like T93866 and T94407.
		if ( !$rev
			&& !( $flags & self::READ_LATEST )
			&& $lb->getServerCount() > 1
			&& $lb->hasOrMadeRecentMasterChanges()
		) {
			$flags = self::READ_LATEST;
			$db = $this->getDBConnection( DB_MASTER );
			$rev = $this->loadRevisionFromConds( $db, $conditions, $flags, $title );
			$lb->reuseConnection( $db );
		}

		return $rev;
	}

	/**
	 * Given a set of conditions, fetch a revision from
	 * the given database connection.
	 *
	 * MCR migration note: this corresponds to Revision::loadFromConds
	 *
	 * @param IDatabase $db
	 * @param array $conditions
	 * @param int $flags (optional)
	 * @param Title $title
	 *
	 * @return RevisionRecord|null
	 */
	protected function loadRevisionFromConds(
		IDatabase $db,
		$conditions,
		$flags = 0,
		Title $title = null
	) {
		$row = $this->fetchRevisionRowFromConds( $db, $conditions, $flags );
		if ( $row ) {
			$rev = $this->revisionFactory->newRevisionFromRow( $row, $flags, $title );

			return $rev;
		}

		return null;
	}

	/**
	 * Load a page revision from a given revision ID number.
	 * Returns null if no such revision can be found.
	 *
	 * MCR migration note: this replaces Revision::newFromId
	 *
	 * $flags include:
	 *      IDBAccessObject::READ_LATEST: Select the data from the master
	 *      IDBAccessObject::READ_LOCKING : Select & lock the data from the master
	 *
	 * @param int $id
	 * @param int $flags (optional)
	 * @return RevisionRecord|null
	 */
	public function getRevisionById( $id, $flags = 0 ) {
		return $this->newRevisionFromConds( [ 'rev_id' => intval( $id ) ], $flags );
	}

	/**
	 * Load the revision for the given title with the given timestamp.
	 * WARNING: Timestamps may in some circumstances not be unique,
	 * so this isn't the best key to use.
	 *
	 * MCR migration note: this replaces Revision::loadFromTimestamp
	 *
	 * @param Title $title
	 * @param string $timestamp
	 * @return RevisionRecord|null
	 */
	public function getRevisionByTimestamp( $title, $timestamp ) {
		return $this->newRevisionFromConds(
			[
				'rev_timestamp' => $timestamp,
				'page_namespace' => $title->getNamespace(),
				'page_title' => $title->getDBkey()
			],
			0,
			$title
		);
	}

	/**
	 * Load either the current, or a specified, revision
	 * that's attached to a given link target. If not attached
	 * to that link target, will return null.
	 *
	 * MCR migration note: this replaces Revision::newFromTitle
	 *
	 * $flags include:
	 *      IDBAccessObject::READ_LATEST: Select the data from the master
	 *      IDBAccessObject::READ_LOCKING : Select & lock the data from the master
	 *
	 * @param LinkTarget $linkTarget
	 * @param int $revId (optional)
	 * @param int $flags Bitfield (optional)
	 * @return RevisionRecord|null
	 */
	public function getRevisionByTitle( LinkTarget $linkTarget, $revId = 0, $flags = 0 ) {
		$conds = [
			'page_namespace' => $linkTarget->getNamespace(),
			'page_title' => $linkTarget->getDBkey()
		];

		$title = null;
		if ( $linkTarget instanceof Title ) {
			$title = $linkTarget;
		}

		if ( $revId ) {
			// Use the specified revision ID.
			// Note that we use newRevisionFromConds here because we want to retry
			// and fall back to master if the page is not found on a replica.
			// Since the caller supplied a revision ID, we are pretty sure the revision is
			// supposed to exist, so we should try hard to find it.
			$conds['rev_id'] = $revId;
			return $this->newRevisionFromConds( $conds, $flags, $title );
		} else {
			// Use a join to get the latest revision.
			// Note that we don't use newRevisionFromConds here because we don't want to retry
			// and fall back to master. The assumption is that we only want to force the fallback
			// if we are quite sure the revision exists because the caller supplied a revision ID.
			// If the page isn't found at all on a replica, it probably simply does not exist.
			$db = $this->getDBConnection( ( $flags & self::READ_LATEST ) ? DB_MASTER : DB_REPLICA );

			$conds[] = 'rev_id=page_latest';
			$rev = $this->loadRevisionFromConds( $db, $conds, $flags, $title );

			$this->loadBalancer->reuseConnection( $db );
			return $rev;
		}
	}

	/**
	 * Load either the current, or a specified, revision
	 * that's attached to a given page ID.
	 * Returns null if no such revision can be found.
	 *
	 * MCR migration note: this replaces Revision::newFromPageId
	 *
	 * $flags include:
	 *      IDBAccessObject::READ_LATEST: Select the data from the master (since 1.20)
	 *      IDBAccessObject::READ_LOCKING : Select & lock the data from the master
	 *
	 * @param int $pageId
	 * @param int $revId (optional)
	 * @param int $flags Bitfield (optional)
	 * @return RevisionRecord|null
	 */
	public function getRevisionByPageId( $pageId, $revId = 0, $flags = 0 ) {
		$conds = [ 'page_id' => $pageId ];
		if ( $revId ) {
			// Use the specified revision ID.
			// Note that we use newRevisionFromConds here because we want to retry
			// and fall back to master if the page is not found on a replica.
			// Since the caller supplied a revision ID, we are pretty sure the revision is
			// supposed to exist, so we should try hard to find it.
			$conds['rev_id'] = $revId;
			return $this->newRevisionFromConds( $conds, $flags );
		} else {
			// Use a join to get the latest revision.
			// Note that we don't use newRevisionFromConds here because we don't want to retry
			// and fall back to master. The assumption is that we only want to force the fallback
			// if we are quite sure the revision exists because the caller supplied a revision ID.
			// If the page isn't found at all on a replica, it probably simply does not exist.
			$db = $this->getDBConnection( ( $flags & self::READ_LATEST ) ? DB_MASTER : DB_REPLICA );

			$conds[] = 'rev_id=page_latest';
			$rev = $this->loadRevisionFromConds( $db, $conds, $flags );

			$this->loadBalancer->reuseConnection( $db );
			return $rev;
		}
	}

	/**
	 * Load a revision based on a known page ID and current revision ID from the DB
	 *
	 * This method allows for the use of caching, though accessing anything that normally
	 * requires permission checks (aside from the text) will trigger a small DB lookup.
	 *
	 * MCR migration note: this replaces Revision::newKnownCurrent
	 *
	 * @param Title $title the associated page title
	 * @param int $revId current revision of this page. Defaults to $title->getLatestRevID().
	 *
	 * @return RevisionRecord|bool Returns false if missing
	 */
	public function getKnownCurrentRevision( Title $title, $revId ) {
		$db = $this->getDBConnectionRef( DB_REPLICA );

		$pageId = $title->getArticleID();

		if ( !$pageId ) {
			return false;
		}

		if ( !$revId ) {
			$revId = $title->getLatestRevID();
		}

		if ( !$revId ) {
			wfWarn(
				'No latest revision known for page ' . $title->getPrefixedDBkey()
				. ' even though it exists with page ID ' . $pageId
			);
			return false;
		}

		$row = $this->cache->getWithSetCallback(
		// Page/rev IDs passed in from DB to reflect history merges
			$this->cache->makeGlobalKey( 'revision-row-1.29', $db->getDomainID(), $pageId, $revId ),
			WANObjectCache::TTL_WEEK,
			function ( $curValue, &$ttl, array &$setOpts ) use ( $db, $pageId, $revId ) {
				$setOpts += Database::getCacheSetOptions( $db );

				$conds = [
					'rev_page' => intval( $pageId ),
					'page_id' => intval( $pageId ),
					'rev_id' => intval( $revId ),
				];

				$row = $this->fetchRevisionRowFromConds( $db, $conds );
				return $row ?: false; // don't cache negatives
			}
		);

		// Reflect revision deletion and user renames
		if ( $row ) {
			return $this->revisionFactory->newRevisionFromRow( $row, 0, $title );
		} else {
			return false;
		}
	}

	/**
	 * Get previous revision for this title
	 *
	 * MCR migration note: this replaces Revision::getPrevious
	 *
	 * @param RevisionRecord $rev
	 * @param Title $title if known (optional)
	 *
	 * @return RevisionRecord|null
	 */
	public function getPreviousRevision( RevisionRecord $rev, Title $title = null ) {
		if ( $title === null ) {
			$title = $this->revisionTitleLookup->getTitle( $rev->getPageId(), $rev->getId() );
		}
		$prev = $title->getPreviousRevisionID( $rev->getId() );
		if ( $prev ) {
			return $this->getRevisionByTitle( $title, $prev );
		}
		return null;
	}

	/**
	 * Get next revision for this title
	 *
	 * MCR migration note: this replaces Revision::getNext
	 *
	 * @param RevisionRecord $rev
	 * @param Title $title if known (optional)
	 *
	 * @return RevisionRecord|null
	 */
	public function getNextRevision( RevisionRecord $rev, Title $title = null ) {
		if ( $title === null ) {
			$title = $this->revisionTitleLookup->getTitle( $rev->getPageId(), $rev->getId() );
		}
		$next = $title->getNextRevisionID( $rev->getId() );
		if ( $next ) {
			return $this->getRevisionByTitle( $title, $next );
		}
		return null;
	}

}
