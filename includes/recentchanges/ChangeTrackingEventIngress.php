<?php

namespace MediaWiki\RecentChanges;

use MediaWiki\ChangeTags\ChangeTagsStore;
use MediaWiki\DomainEvent\EventSubscriberBase;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Page\Event\PageRevisionUpdatedEvent;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Storage\EditResult;
use MediaWiki\User\TalkPageNotificationManager;
use MediaWiki\User\User;
use MediaWiki\User\UserEditTracker;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserNameUtils;
use RecentChange;

/**
 * The ingress subscriber for the change tracking component. It updates change
 * tracking state according to domain events coming from other components.
 *
 * @internal
 */
class ChangeTrackingEventIngress extends EventSubscriberBase {

	/**
	 * The events handled by this ingress subscriber.
	 * @see registerListeners()
	 */
	public const EVENTS = [
		PageRevisionUpdatedEvent::TYPE
	];

	/**
	 * Object spec used for lazy instantiation.
	 * Using this spec with DomainEventSource::registerSubscriber defers
	 * instantiation until one of the listed events is dispatched.
	 * Declaring it as a constant avoids the overhead of using reflection
	 * for auto-wiring.
	 */
	public const OBJECT_SPEC = [
		'class' => self::class,
		'services' => [ // see __construct
			'ChangeTagsStore',
			'UserEditTracker',
			'PermissionManager',
			'WikiPageFactory',
			'HookContainer',
			'UserNameUtils',
			'TalkPageNotificationManager'
		],
		'events' => [ // see registerListeners()
			PageRevisionUpdatedEvent::TYPE
		],
	];

	private ChangeTagsStore $changeTagsStore;
	private UserEditTracker $userEditTracker;
	private PermissionManager $permissionManager;
	private WikiPageFactory $wikiPageFactory;
	private HookRunner $hookRunner;
	private UserNameUtils $userNameUtils;
	private TalkPageNotificationManager $talkPageNotificationManager;

	public function __construct(
		ChangeTagsStore $changeTagsStore,
		UserEditTracker $userEditTracker,
		PermissionManager $permissionManager,
		WikiPageFactory $wikiPageFactory,
		HookContainer $hookContainer,
		UserNameUtils $userNameUtils,
		TalkPageNotificationManager $talkPageNotificationManager
	) {
		// NOTE: keep in sync with self::OBJECT_SPEC
		$this->changeTagsStore = $changeTagsStore;
		$this->userEditTracker = $userEditTracker;
		$this->permissionManager = $permissionManager;
		$this->wikiPageFactory = $wikiPageFactory;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->userNameUtils = $userNameUtils;
		$this->talkPageNotificationManager = $talkPageNotificationManager;
	}

	public static function newForTesting(
		ChangeTagsStore $changeTagsStore,
		UserEditTracker $userEditTracker,
		PermissionManager $permissionManager,
		WikiPageFactory $wikiPageFactory,
		HookContainer $hookContainer,
		UserNameUtils $userNameUtils,
		TalkPageNotificationManager $talkPageNotificationManager
	) {
		$ingress = new self(
			$changeTagsStore,
			$userEditTracker,
			$permissionManager,
			$wikiPageFactory,
			$hookContainer,
			$userNameUtils,
			$talkPageNotificationManager
		);
		$ingress->initSubscriber( self::OBJECT_SPEC );
		return $ingress;
	}

	/**
	 * Listener method for PageRevisionUpdatedEvent, to be registered with an
	 * DomainEventSource.
	 *
	 * @noinspection PhpUnused
	 */
	public function handlePageRevisionUpdatedEvent( PageRevisionUpdatedEvent $event ) {
		if ( $event->changedLatestRevisionId()
			&& !$event->isSilent()
		) {
			$this->updateRecentChangesAfterPageUpdated(
				$event->getLatestRevisionAfter(),
				$event->getLatestRevisionBefore(),
				$event->isBotUpdate(),
				$event->getPatrolStatus(),
				$event->getTags(),
				$event->getEditResult()
			);
		} elseif ( $event->getTags() ) {
			$this->updateChangeTagsAfterPageUpdated(
				$event->getTags(),
				$event->getLatestRevisionAfter()->getId(),
			);
		}

		if ( $event->isEffectiveContentChange() && !$event->isImplicit() ) {
			$this->updateUserEditTrackerAfterPageUpdated(
				$event->getPerformer()
			);

			$this->updateNewTalkAfterPageUpdated( $event );
		}
	}

	private function updateChangeTagsAfterPageUpdated( array $tags, int $revId ) {
		$this->changeTagsStore->addTags( $tags, null, $revId );
	}

	private function updateRecentChangesAfterPageUpdated(
		RevisionRecord $newRevisionRecord,
		?RevisionRecord $oldRevisionRecord,
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

	/**
	 * Listener method for PageRevisionUpdatedEvent, to be registered with a DomainEventSource.
	 *
	 * @noinspection PhpUnused
	 */
	private function updateNewTalkAfterPageUpdated( PageRevisionUpdatedEvent $event ) {
		// If this is another user's talk page, update newtalk.
		// Don't do this if $options['changed'] = false (null-edits) nor if
		// it's a minor edit and the user making the edit doesn't generate notifications for those.
		$page = $event->getPage();
		$revRecord = $event->getLatestRevisionAfter();
		$recipientName = $page->getDBkey();
		$recipientName = $this->userNameUtils->isIP( $recipientName )
			? $recipientName
			: $this->userNameUtils->getCanonical( $page->getDBkey() );

		if ( $page->getNamespace() === NS_USER_TALK
			&& !( $revRecord->isMinor()
				&& $this->permissionManager->userHasRight(
					$event->getAuthor(), 'nominornewtalk' ) )
			&& $recipientName != $event->getAuthor()->getName()
		) {
			$recipient = User::newFromName( $recipientName, false );
			if ( !$recipient ) {
				wfDebug( __METHOD__ . ": invalid username" );
			} else {
				$wikiPage = $this->wikiPageFactory->newFromTitle( $page );

				// Allow extensions to prevent user notification
				// when a new message is added to their talk page
				if ( $this->hookRunner->onArticleEditUpdateNewTalk( $wikiPage, $recipient ) ) {
					if ( $this->userNameUtils->isIP( $recipientName ) ) {
						// An anonymous user
						$this->talkPageNotificationManager->setUserHasNewMessages( $recipient, $revRecord );
					} elseif ( $recipient->isRegistered() ) {
						$this->talkPageNotificationManager->setUserHasNewMessages( $recipient, $revRecord );
					} else {
						wfDebug( __METHOD__ . ": don't need to notify a nonexistent user" );
					}
				}
			}
		}
	}

}
