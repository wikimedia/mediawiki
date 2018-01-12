<?php

namespace MediaWiki\Storage;

use DBAccessObjectUtils;
use IDBAccessObject;
use InvalidArgumentException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Title;
use Wikimedia\Rdbms\DBConnRef;
use Wikimedia\Rdbms\LoadBalancer;

class RevisionTitleLookup implements IDBAccessObject, LoggerAwareInterface {

	/**
	 * @var LoadBalancer
	 */
	protected $loadBalancer;

	/**
	 * @var string|bool
	 */
	private $wikiId;

	/**
	 * @var LoggerInterface
	 */
	private $logger;

	/**
	 * @param LoadBalancer $loadBalancer
	 * @param string|bool $wikiId
	 */
	public function __construct(
		LoadBalancer $loadBalancer,
		$wikiId = false
	) {
		$this->loadBalancer = $loadBalancer;
		$this->wikiId = $wikiId;
		$this->logger = new NullLogger();
	}

	/**
	 * Sets a logger instance on the object
	 *
	 * @param LoggerInterface $logger
	 */
	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	/**
	 * @param int $mode DB_MASTER or DB_REPLICA
	 *
	 * @return DBConnRef
	 */
	private function getDBConnectionRef( $mode ) {
		return $this->loadBalancer->getConnectionRef( $mode, [], $this->wikiId );
	}

	/**
	 * Determines the page Title based on the available information.
	 *
	 * MCR migration note: this corresponds to Revision::getTitle
	 *
	 * @note this method should be private, external use should be avoided!
	 *
	 * @param int|null $pageId
	 * @param int|null $revId
	 * @param int $queryFlags
	 *
	 * @return Title
	 * @throws RevisionAccessException
	 */
	public function getTitle( $pageId, $revId, $queryFlags = self::READ_NORMAL ) {
		if ( !$pageId && !$revId ) {
			throw new InvalidArgumentException( '$pageId and $revId cannot both be 0 or null' );
		}

		// This method handles recalls itself with READ_LATEST if READ_NORMAL doesn't get us a Title
		if ( DBAccessObjectUtils::hasFlags( $queryFlags, self::READ_LATEST_IMMUTABLE ) ) {
			$queryFlags = self::READ_NORMAL;
		}

		$canUseTitleNewFromId = ( $pageId !== null && $pageId > 0 && $this->wikiId === false );
		list( $dbMode, $dbOptions ) = DBAccessObjectUtils::getDBOptions( $queryFlags );
		$titleFlags = ( $dbMode == DB_MASTER ? Title::GAID_FOR_UPDATE : 0 );

		// Loading by ID is best, but Title::newFromID does not support that for foreign IDs.
		if ( $canUseTitleNewFromId ) {
			// TODO: better foreign title handling (introduce TitleFactory)
			$title = Title::newFromID( $pageId, $titleFlags );
			if ( $title ) {
				return $title;
			}
		}

		// rev_id is defined as NOT NULL, but this revision may not yet have been inserted.
		$canUseRevId = ( $revId !== null && $revId > 0 );

		if ( $canUseRevId ) {
			$dbr = $this->getDBConnectionRef( $dbMode );
			// @todo: Title::getSelectFields(), or Title::getQueryInfo(), or something like that
			$row = $dbr->selectRow(
				[ 'revision', 'page' ],
				[
					'page_namespace',
					'page_title',
					'page_id',
					'page_latest',
					'page_is_redirect',
					'page_len',
				],
				[ 'rev_id' => $revId ],
				__METHOD__,
				$dbOptions,
				[ 'page' => [ 'JOIN', 'page_id=rev_page' ] ]
			);
			if ( $row ) {
				// TODO: better foreign title handling (introduce TitleFactory)
				return Title::newFromRow( $row );
			}
		}

		// If we still don't have a title, fallback to master if that wasn't already happening.
		if ( $dbMode !== DB_MASTER ) {
			$title = $this->getTitle( $pageId, $revId, self::READ_LATEST );
			if ( $title ) {
				$this->logger->info(
					__METHOD__ . ' fell back to READ_LATEST and got a Title.',
					[ 'trace' => wfDebugBacktrace() ]
				);
				return $title;
			}
		}

		throw new RevisionAccessException(
			"Could not determine title for page ID $pageId and revision ID $revId"
		);
	}
}
