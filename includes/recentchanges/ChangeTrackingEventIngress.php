<?php

namespace MediaWiki\RecentChanges;

use ManualLogEntry;
use MediaWiki\ChangeTags\ChangeTagsStore;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Storage\EditResult;
use MediaWiki\Storage\PageUpdatedEvent;
use MediaWiki\User\UserEditTracker;
use MediaWiki\User\UserIdentity;
use RecentChange;

/**
 * The ingres adapter for the change tracking component. It updates change
 * tracking state according to domain events coming from other components.
 *
 * @internal
 */
class ChangeTrackingEventIngress {

	private ServiceOptions $options;
	private ChangeTagsStore $changeTagsStore;
	private UserEditTracker $userEditTracker;

	/**
	 * @internal
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::PageCreationLog
	];

	/**
	 * @param ServiceOptions $options
	 * @param ChangeTagsStore $changeTagsStore
	 * @param UserEditTracker $userEditTracker
	 */
	public function __construct(
		ServiceOptions $options,
		ChangeTagsStore $changeTagsStore,
		UserEditTracker $userEditTracker
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );

		$this->options = $options;
		$this->changeTagsStore = $changeTagsStore;
		$this->userEditTracker = $userEditTracker;
	}

	/**
	 * Listener method for PageUpdatedEvent, to be registered with an
	 * DomainEventSource.
	 *
	 * @noinspection PhpUnused
	 */
	public function afterPageUpdated( PageUpdatedEvent $event ) {
		if ( !$event->hasFlag( EDIT_SUPPRESS_RC ) ) {
			$this->updateRecentChangesAfterPageUpdated(
				$event->getNewRevision(),
				$event->getOldRevision(),
				$event->hasFlag( EDIT_FORCE_BOT ),
				$event->getPatrolStatus(),
				$event->getTags(),
				$event->getEditResult()
			);

			if ( $event->isNew() && $this->options->get( MainConfigNames::PageCreationLog ) ) {
				$this->updateLogAfterPageUpdated(
					$event->getAuthor(),
					$event->getPage(),
					$event->getNewRevision()->getId(),
					$event->getEventTimestamp(),
					$event->getNewRevision()->getComment( RevisionRecord::RAW )->text
				);
			}
		} else {
			$this->updateChangeTageAfterPageUpdated(
				$event->getTags(),
				$event->getNewRevision()->getId(),
			);
		}

		$this->updateUserEditTrackerAfterPageUpdated(
			$event->getAuthor()
		);
	}

	private function updateChangeTageAfterPageUpdated( array $tags, int $revId ) {
		$this->changeTagsStore->addTags( $tags, null, $revId );
	}

	private function updateRecentChangesAfterPageUpdated(
		RevisionRecord $newRevisionRecord,
		?REvisionRecord $oldRevisionRecord,
		bool $forceBot,
		int $patrolStatus,
		array $tags,
		?EditResult $editResult
	) {
		// Update recentchanges
		if ( !$oldRevisionRecord ) {
			RecentChange::notifyNew(
				$newRevisionRecord->getTimestamp(),
				$newRevisionRecord->getPage(),
				$newRevisionRecord->isMinor(),
				$newRevisionRecord->getUser( RevisionRecord::RAW ),
				$newRevisionRecord->getComment( RevisionRecord::RAW )->text,
				$forceBot, // $event->hasFlag( EDIT_FORCE_BOT ),
				'',
				$newRevisionRecord->getSize(),
				$newRevisionRecord->getId(),
				$patrolStatus,
				$tags
			);
		} else {
			// Add RC row to the DB
			RecentChange::notifyEdit(
				$newRevisionRecord->getTimestamp(),
				$newRevisionRecord->getPage(),
				$newRevisionRecord->isMinor(),
				$newRevisionRecord->getUser( RevisionRecord::RAW ),
				$newRevisionRecord->getComment( RevisionRecord::RAW )->text,
				$oldRevisionRecord->getId(),
				$newRevisionRecord->getTimestamp(),
				$forceBot,
				'',
				$oldRevisionRecord->getSize(),
				$newRevisionRecord->getSize(),
				$newRevisionRecord->getId(),
				$patrolStatus,
				$tags,
				$editResult
			);
		}
	}

	private function updateUserEditTrackerAfterPageUpdated( UserIdentity $author ) {
		$this->userEditTracker->incrementUserEditCount( $author );
	}

	private function updateLogAfterPageUpdated(
		UserIdentity $author,
		PageIdentity $page,
		int $revId,
		string $timestamp,
		string $comment
	) {
		// Log the page creation
		// @TODO: Do we want a 'recreate' action?
		$logEntry = new ManualLogEntry( 'create', 'create' );
		$logEntry->setPerformer( $author );
		$logEntry->setTarget( $page );
		$logEntry->setComment( $comment );
		$logEntry->setTimestamp( $timestamp );
		$logEntry->setAssociatedRevId( $revId );
		$logEntry->insert();
		// Note that we don't publish page creation events to recentchanges
		// (i.e. $logEntry->publish()) since this would create duplicate entries,
		// one for the edit and one for the page creation.
	}

}
