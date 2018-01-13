<?php

namespace MediaWiki\Storage;

use Content;
use ContentHandler;
use DBAccessObjectUtils;
use IDBAccessObject;
use MWException;
use MWUnknownContentModelException;
use RecentChange;
use Title;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\LoadBalancer;

abstract class AbstractRevisionStore implements RevisionStore, IDBAccessObject {

	use DatabaseWikiIdChecker;

	/**
	 * @var LoadBalancer
	 */
	protected $loadBalancer;

	/**
	 * @var bool|string
	 */
	protected $wikiId;

	/**
	 * @var boolean
	 */
	protected $contentHandlerUseDB = true;

	public function __construct(
		LoadBalancer $loadBalancer,
		$wikiId = false
	) {
		$this->loadBalancer = $loadBalancer;
		$this->wikiId = $wikiId;
	}

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

	/**
	 * @param int $mode DB_MASTER or DB_REPLICA
	 *
	 * @return IDatabase
	 */
	public function getDBConnection( $mode ) {
		return $this->loadBalancer->getConnection( $mode, [], $this->wikiId );
	}

	/**
	 * @param IDatabase $connection
	 */
	public function releaseDBConnection( IDatabase $connection ) {
		$this->loadBalancer->reuseConnection( $connection );
	}

	/**
	 * @param mixed $value
	 * @param string $name
	 *
	 * @throw IncompleteRevisionException if $value is null
	 * @return mixed $value, if $value is not null
	 */
	public function failOnNull( $value, $name ) {
		if ( $value === null ) {
			throw new IncompleteRevisionException(
				"$name must not be " . var_export( $value, true ) . "!"
			);
		}

		return $value;
	}

	/**
	 * @param mixed $value
	 * @param string $name
	 *
	 * @throw IncompleteRevisionException if $value is empty
	 * @return mixed $value, if $value is not null
	 */
	public function failOnEmpty( $value, $name ) {
		if ( $value === null || $value === 0 || $value === '' ) {
			throw new IncompleteRevisionException(
				"$name must not be " . var_export( $value, true ) . "!"
			);
		}

		return $value;
	}

	/**
	 * MCR migration note: this corresponds to Revision::checkContentModel
	 *
	 * @param Content $content
	 * @param Title $title
	 *
	 * @throws MWException
	 * @throws MWUnknownContentModelException
	 */
	public function checkContentModel( Content $content, Title $title ) {
		// Note: may return null for revisions that have not yet been inserted

		$model = $content->getModel();
		$format = $content->getDefaultFormat();
		$handler = $content->getContentHandler();

		$name = "$title";

		if ( !$handler->isSupportedFormat( $format ) ) {
			throw new MWException( "Can't use format $format with content model $model on $name" );
		}

		if ( !$this->contentHandlerUseDB ) {
			// if $wgContentHandlerUseDB is not set,
			// all revisions must use the default content model and format.

			$defaultModel = ContentHandler::getDefaultModelFor( $title );
			$defaultHandler = ContentHandler::getForModelID( $defaultModel );
			$defaultFormat = $defaultHandler->getDefaultFormat();

			if ( $model != $defaultModel ) {
				throw new MWException( "Can't save non-default content model with "
					. "\$wgContentHandlerUseDB disabled: model is $model, "
					. "default for $name is $defaultModel"
				);
			}

			if ( $format != $defaultFormat ) {
				throw new MWException( "Can't use non-default content format with "
					. "\$wgContentHandlerUseDB disabled: format is $format, "
					. "default for $name is $defaultFormat"
				);
			}
		}

		if ( !$content->isValid() ) {
			throw new MWException(
				"New content for $name is not valid! Content model is $model"
			);
		}
	}

	/**
	 * MCR migration note: this replaces Revision::isUnpatrolled
	 *
	 * @todo This is overly specific, so move or kill this method.
	 *
	 * @param RevisionRecord $rev
	 *
	 * @return int Rcid of the unpatrolled row, zero if there isn't one
	 */
	public function getRcIdIfUnpatrolled( RevisionRecord $rev ) {
		$rc = $this->getRecentChange( $rev );
		if ( $rc && $rc->getAttribute( 'rc_patrolled' ) == 0 ) {
			return $rc->getAttribute( 'rc_id' );
		} else {
			return 0;
		}
	}

	/**
	 * Get the RC object belonging to the current revision, if there's one
	 *
	 * MCR migration note: this replaces Revision::getRecentChange
	 *
	 * @todo move this somewhere else?
	 *
	 * @param RevisionRecord $rev
	 * @param int $flags (optional) $flags include:
	 *      IDBAccessObject::READ_LATEST: Select the data from the master
	 *
	 * @return null|RecentChange
	 */
	public function getRecentChange( RevisionRecord $rev, $flags = 0 ) {
		$dbr = $this->getDBConnection( DB_REPLICA );

		list( $dbType, ) = DBAccessObjectUtils::getDBOptions( $flags );

		$userIdentity = $rev->getUser( RevisionRecord::RAW );

		if ( !$userIdentity ) {
			// If the revision has no user identity, chances are it never went
			// into the database, and doesn't have an RC entry.
			return null;
		}

		// TODO: Select by rc_this_oldid alone - but as of Nov 2017, there is no index on that!
		$rc = RecentChange::newFromConds(
			[
				'rc_user_text' => $userIdentity->getName(),
				'rc_timestamp' => $dbr->timestamp( $rev->getTimestamp() ),
				'rc_this_oldid' => $rev->getId()
			],
			__METHOD__,
			$dbType
		);

		$this->releaseDBConnection( $dbr );

		// XXX: cache this locally? Glue it to the RevisionRecord?
		return $rc;
	}

	/**
	 * Do a batched query for the sizes of a set of revisions.
	 *
	 * MCR migration note: this replaces Revision::getParentLengths
	 *
	 * @param int[] $revIds
	 * @return int[] associative array mapping revision IDs from $revIds to the nominal size
	 *         of the corresponding revision.
	 */
	public function getRevisionSizes( array $revIds ) {
		return $this->listRevisionSizes( $this->getDBConnection( DB_REPLICA ), $revIds );
	}

	/**
	 * Get rev_timestamp from rev_id, without loading the rest of the row
	 *
	 * MCR migration note: this replaces Revision::getTimestampFromId
	 *
	 * @param Title $title
	 * @param int $id
	 * @param int $flags
	 * @return string|bool False if not found
	 */
	public function getTimestampFromId( $title, $id, $flags = 0 ) {
		$db = $this->getDBConnection(
			( $flags & IDBAccessObject::READ_LATEST ) ? DB_MASTER : DB_REPLICA
		);

		$conds = [ 'rev_id' => $id ];
		$conds['rev_page'] = $title->getArticleID();
		$timestamp = $db->selectField( 'revision', 'rev_timestamp', $conds, __METHOD__ );

		$this->releaseDBConnection( $db );
		return ( $timestamp !== false ) ? wfTimestamp( TS_MW, $timestamp ) : false;
	}

	/**
	 * Get count of revisions per page...not very efficient
	 *
	 * MCR migration note: this replaces Revision::countByPageId
	 *
	 * @param IDatabase $db
	 * @param int $id Page id
	 * @return int
	 */
	public function countRevisionsByPageId( IDatabase $db, $id ) {
		$this->checkDatabaseWikiId( $db, $this->wikiId );

		$row = $db->selectRow( 'revision',
			[ 'revCount' => 'COUNT(*)' ],
			[ 'rev_page' => $id ],
			__METHOD__
		);
		if ( $row ) {
			return intval( $row->revCount );
		}
		return 0;
	}

	/**
	 * Get count of revisions per page...not very efficient
	 *
	 * MCR migration note: this replaces Revision::countByTitle
	 *
	 * @param IDatabase $db
	 * @param Title $title
	 * @return int
	 */
	public function countRevisionsByTitle( IDatabase $db, $title ) {
		$id = $title->getArticleID();
		if ( $id ) {
			return $this->countRevisionsByPageId( $db, $id );
		}
		return 0;
	}

	/**
	 * Check if no edits were made by other users since
	 * the time a user started editing the page. Limit to
	 * 50 revisions for the sake of performance.
	 *
	 * MCR migration note: this replaces Revision::userWasLastToEdit
	 *
	 * @deprecated since 1.31; Can possibly be removed, since the self-conflict suppression
	 *       logic in EditPage that uses this seems conceptually dubious. Revision::userWasLastToEdit
	 *       has been deprecated since 1.24.
	 *
	 * @param IDatabase $db The Database to perform the check on.
	 * @param int $pageId The ID of the page in question
	 * @param int $userId The ID of the user in question
	 * @param string $since Look at edits since this time
	 *
	 * @return bool True if the given user was the only one to edit since the given timestamp
	 */
	public function userWasLastToEdit( IDatabase $db, $pageId, $userId, $since ) {
		$this->checkDatabaseWikiId( $db, $this->wikiId );

		if ( !$userId ) {
			return false;
		}

		$res = $db->select(
			'revision',
			'rev_user',
			[
				'rev_page' => $pageId,
				'rev_timestamp > ' . $db->addQuotes( $db->timestamp( $since ) )
			],
			__METHOD__,
			[ 'ORDER BY' => 'rev_timestamp ASC', 'LIMIT' => 50 ]
		);
		foreach ( $res as $row ) {
			if ( $row->rev_user != $userId ) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Get previous revision Id for this page_id
	 * This is used to populate rev_parent_id on save
	 *
	 * MCR migration note: this corresponds to Revision::getPreviousRevisionId
	 *
	 * @param IDatabase $db
	 * @param RevisionRecord $rev
	 *
	 * @return int
	 */
	public function getPreviousRevisionId( IDatabase $db, RevisionRecord $rev ) {
		$this->checkDatabaseWikiId( $db, $this->wikiId );

		if ( $rev->getPageId() === null ) {
			return 0;
		}
		# Use page_latest if ID is not given
		if ( !$rev->getId() ) {
			$prevId = $db->selectField(
				'page', 'page_latest',
				[ 'page_id' => $rev->getPageId() ],
				__METHOD__
			);
		} else {
			$prevId = $db->selectField(
				'revision', 'rev_id',
				[ 'rev_page' => $rev->getPageId(), 'rev_id < ' . $rev->getId() ],
				__METHOD__,
				[ 'ORDER BY' => 'rev_id DESC' ]
			);
		}
		return intval( $prevId );
	}

}
