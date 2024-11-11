<?php

namespace MediaWiki\RecentChanges;

use MediaWiki\ChangeTags\ChangeTagsStore;
use MediaWiki\DomainEvent\DomainEventSource;
use MediaWiki\DomainEvent\EventSubscriberBase;
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
class ChangeTrackingEventIngress extends EventSubscriberBase {

	private ChangeTagsStore $changeTagsStore;
	private UserEditTracker $userEditTracker;

	/**
	 * @param ChangeTagsStore $changeTagsStore
	 * @param UserEditTracker $userEditTracker
	 */
	public function __construct(
		ChangeTagsStore $changeTagsStore,
		UserEditTracker $userEditTracker
	) {
		parent::__construct();
		$this->changeTagsStore = $changeTagsStore;
		$this->userEditTracker = $userEditTracker;
	}

	public function registerListeners( DomainEventSource $eventSource ): void {
		$this->registerListenerMethod( $eventSource, PageUpdatedEvent::TYPE );
	}

	/**
	 * Listener method for PageUpdatedEvent, to be registered with an
	 * DomainEventSource.
	 *
	 * @noinspection PhpUnused
	 */
	public function handlePageUpdatedEventAfterCommit( PageUpdatedEvent $event ) {
		if ( !$event->hasFlag( EDIT_SUPPRESS_RC ) ) {
			$this->updateRecentChangesAfterPageUpdated(
				$event->getNewRevision(),
				$event->getOldRevision(),
				$event->hasFlag( EDIT_FORCE_BOT ),
				$event->getPatrolStatus(),
				$event->getTags(),
				$event->getEditResult()
			);
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

}
