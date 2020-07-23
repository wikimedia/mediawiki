<?php

use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\SlotRoleRegistry;
use Psr\Log\LoggerInterface;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * @since 1.31
 */
class ImportableOldRevisionImporter implements OldRevisionImporter {

	/**
	 * @var LoggerInterface
	 */
	private $logger;

	/**
	 * @var bool
	 */
	private $doUpdates;

	/**
	 * @var ILoadBalancer
	 */
	private $loadBalancer;

	/**
	 * @var RevisionStore
	 */
	private $revisionStore;

	/**
	 * @var SlotRoleRegistry
	 */
	private $slotRoleRegistry;

	/**
	 * @param bool $doUpdates
	 * @param LoggerInterface $logger
	 * @param ILoadBalancer $loadBalancer
	 * @param RevisionStore $revisionStore
	 * @param SlotRoleRegistry|null $slotRoleRegistry
	 */
	public function __construct(
		$doUpdates,
		LoggerInterface $logger,
		ILoadBalancer $loadBalancer,
		RevisionStore $revisionStore,
		SlotRoleRegistry $slotRoleRegistry = null
	) {
		$this->doUpdates = $doUpdates;
		$this->logger = $logger;
		$this->loadBalancer = $loadBalancer;
		$this->revisionStore = $revisionStore;
		// @todo: temporary - remove when FileImporter extension is updated
		if ( !$slotRoleRegistry ) {
			$slotRoleRegistry = \MediaWiki\MediaWikiServices::getInstance()->getSlotRoleRegistry();
		}
		$this->slotRoleRegistry = $slotRoleRegistry;
	}

	public function import( ImportableOldRevision $importableRevision, $doUpdates = true ) {
		$dbw = $this->loadBalancer->getConnectionRef( DB_MASTER );

		# Sneak a single revision into place
		$user = $importableRevision->getUserObj() ?: User::newFromName( $importableRevision->getUser() );
		if ( $user ) {
			$userId = intval( $user->getId() );
			$userText = $user->getName();
		} else {
			$userId = 0;
			$userText = $importableRevision->getUser();
			$user = new User;
		}

		// avoid memory leak...?
		Title::clearCaches();

		$page = WikiPage::factory( $importableRevision->getTitle() );
		$page->loadPageData( 'fromdbmaster' );
		if ( !$page->exists() ) {
			// must create the page...
			$pageId = $page->insertOn( $dbw );
			$created = true;
			$oldcountable = null;
		} else {
			$pageId = $page->getId();
			$created = false;

			// Note: sha1 has been in XML dumps since 2012. If you have an
			// older dump, the duplicate detection here won't work.
			if ( $importableRevision->getSha1Base36() !== false ) {
				$prior = $dbw->selectField( 'revision', '1',
					[ 'rev_page' => $pageId,
					'rev_timestamp' => $dbw->timestamp( $importableRevision->getTimestamp() ),
					'rev_sha1' => $importableRevision->getSha1Base36() ],
					__METHOD__
				);
				if ( $prior ) {
					// @todo FIXME: This could fail slightly for multiple matches :P
					$this->logger->debug( __METHOD__ . ": skipping existing revision for [[" .
						$importableRevision->getTitle()->getPrefixedText() . "]], timestamp " .
						$importableRevision->getTimestamp() . "\n" );
					return false;
				}
			}
		}

		if ( !$pageId ) {
			// This seems to happen if two clients simultaneously try to import the
			// same page
			$this->logger->debug( __METHOD__ . ': got invalid $pageId when importing revision of [[' .
				$importableRevision->getTitle()->getPrefixedText() . ']], timestamp ' .
				$importableRevision->getTimestamp() . "\n" );
			return false;
		}

		// Select previous version to make size diffs correct
		// @todo This assumes that multiple revisions of the same page are imported
		// in order from oldest to newest.
		$qi = $this->revisionStore->getQueryInfo();
		$prevRevRow = $dbw->selectRow( $qi['tables'], $qi['fields'],
			[
				'rev_page' => $pageId,
				'rev_timestamp <= ' . $dbw->addQuotes( $dbw->timestamp( $importableRevision->getTimestamp() ) ),
			],
			__METHOD__,
			[ 'ORDER BY' => [
				'rev_timestamp DESC',
				'rev_id DESC', // timestamp is not unique per page
			]
			],
			$qi['joins']
		);

		# @todo FIXME: Use original rev_id optionally (better for backups)
		# Insert the row
		$revisionRecord = new MutableRevisionRecord( $importableRevision->getTitle() );
		$revisionRecord->setParentId( $prevRevRow ? (int)$prevRevRow->rev_id : 0 );
		$revisionRecord->setComment(
			CommentStoreComment::newUnsavedComment( $importableRevision->getComment() )
		);

		try {
			$revUser = User::newFromAnyId(
				$userId,
				$userText,
				null
			);
		} catch ( InvalidArgumentException $ex ) {
			$revUser = RequestContext::getMain()->getUser();
		}
		$revisionRecord->setUser( $revUser );

		$originalRevision = $prevRevRow
			? $this->revisionStore->newRevisionFromRow(
				$prevRevRow,
				IDBAccessObject::READ_LATEST,
				$importableRevision->getTitle()
			)
			: null;

		foreach ( $importableRevision->getSlotRoles() as $role ) {
			if ( !$this->slotRoleRegistry->isDefinedRole( $role ) ) {
				throw new MWException( "Undefined slot role $role" );
			}

			$newContent = $importableRevision->getContent( $role );
			if ( !$originalRevision || !$originalRevision->hasSlot( $role ) ) {
				$revisionRecord->setContent( $role, $newContent );
			} else {
				$originalSlot = $originalRevision->getSlot( $role );
				if ( !$originalSlot->hasSameContent( $importableRevision->getSlot( $role ) ) ) {
					$revisionRecord->setContent( $role, $newContent );
				} else {
					$revisionRecord->inheritSlot( $originalRevision->getSlot( $role ) );
				}
			}
		}

		$revisionRecord->setTimestamp( $importableRevision->getTimestamp() );
		$revisionRecord->setMinorEdit( $importableRevision->getMinor() );
		$revisionRecord->setPageId( $pageId );

		$latestRevId = $page->getLatest();

		$inserted = $this->revisionStore->insertRevisionOn( $revisionRecord, $dbw );
		if ( $latestRevId ) {
			// If not found (false), cast to 0 so that the page is updated
			// Just to be on the safe side, even though it should always be found
			$latestRevTimestamp = (int)$this->revisionStore->getTimestampFromId(
				$latestRevId,
				RevisionStore::READ_LATEST
			);
		} else {
			$latestRevTimestamp = 0;
		}
		if ( $importableRevision->getTimestamp() > $latestRevTimestamp ) {
			$changed = $page->updateRevisionOn( $dbw, $inserted, $latestRevId );
		} else {
			$changed = false;
		}

		$tags = $importableRevision->getTags();
		if ( $tags !== [] ) {
			ChangeTags::addTags( $tags, null, $inserted->getId() );
		}

		if ( $changed !== false && $this->doUpdates ) {
			$this->logger->debug( __METHOD__ . ": running updates" );
			// countable/oldcountable stuff is handled in WikiImporter::finishImportPage
			// @todo replace deprecated function
			$page->doEditUpdates(
				$inserted,
				$user,
				[ 'created' => $created, 'oldcountable' => 'no-change' ]
			);
		}

		return true;
	}

}
