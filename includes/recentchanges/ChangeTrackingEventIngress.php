<?php

namespace MediaWiki\RecentChanges;

use LogicException;
use MediaWiki\ChangeTags\ChangeTagsStore;
use MediaWiki\Config\Config;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\DomainEvent\DomainEventIngress;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\JobQueue\JobQueueGroup;
use MediaWiki\JobQueue\Jobs\CategoryMembershipChangeJob;
use MediaWiki\JobQueue\Jobs\RevertedTagUpdateJob;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\Event\PageLatestRevisionChangedEvent;
use MediaWiki\Page\Event\PageLatestRevisionChangedListener;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Storage\EditResult;
use MediaWiki\User\TalkPageNotificationManager;
use MediaWiki\User\User;
use MediaWiki\User\UserEditTracker;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserNameUtils;

/**
 * The ingress subscriber for the change tracking component. It updates change
 * tracking state according to domain events coming from other components.
 *
 * @internal
 */
class ChangeTrackingEventIngress
	extends DomainEventIngress
	implements PageLatestRevisionChangedListener
{

	/**
	 * The events handled by this ingress subscriber.
	 * @see registerListeners()
	 */
	public const EVENTS = [
		PageLatestRevisionChangedEvent::TYPE
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
			'TalkPageNotificationManager',
			'MainConfig',
			'JobQueueGroup',
			'ContentHandlerFactory',
			'RecentChangeFactory',
		],
		'events' => [ // see registerListeners()
			PageLatestRevisionChangedEvent::TYPE
		],
	];

	private ChangeTagsStore $changeTagsStore;
	private UserEditTracker $userEditTracker;
	private PermissionManager $permissionManager;
	private WikiPageFactory $wikiPageFactory;
	private HookRunner $hookRunner;
	private UserNameUtils $userNameUtils;
	private TalkPageNotificationManager $talkPageNotificationManager;
	private JobQueueGroup $jobQueueGroup;
	private IContentHandlerFactory $contentHandlerFactory;
	private RecentChangeFactory $recentChangeFactory;
	private bool $useRcPatrol;
	private bool $rcWatchCategoryMembership;

	public function __construct(
		ChangeTagsStore $changeTagsStore,
		UserEditTracker $userEditTracker,
		PermissionManager $permissionManager,
		WikiPageFactory $wikiPageFactory,
		HookContainer $hookContainer,
		UserNameUtils $userNameUtils,
		TalkPageNotificationManager $talkPageNotificationManager,
		Config $mainConfig,
		JobQueueGroup $jobQueueGroup,
		IContentHandlerFactory $contentHandlerFactory,
		RecentChangeFactory $recentChangeFactory
	) {
		// NOTE: keep in sync with self::OBJECT_SPEC
		$this->changeTagsStore = $changeTagsStore;
		$this->userEditTracker = $userEditTracker;
		$this->permissionManager = $permissionManager;
		$this->wikiPageFactory = $wikiPageFactory;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->userNameUtils = $userNameUtils;
		$this->talkPageNotificationManager = $talkPageNotificationManager;
		$this->jobQueueGroup = $jobQueueGroup;
		$this->contentHandlerFactory = $contentHandlerFactory;
		$this->recentChangeFactory = $recentChangeFactory;

		$this->useRcPatrol = $mainConfig->get( MainConfigNames::UseRCPatrol );
		$this->rcWatchCategoryMembership = $mainConfig->get(
			MainConfigNames::RCWatchCategoryMembership
		);
	}

	public static function newForTesting(
		ChangeTagsStore $changeTagsStore,
		UserEditTracker $userEditTracker,
		PermissionManager $permissionManager,
		WikiPageFactory $wikiPageFactory,
		HookContainer $hookContainer,
		UserNameUtils $userNameUtils,
		TalkPageNotificationManager $talkPageNotificationManager,
		Config $mainConfig,
		JobQueueGroup $jobQueueGroup,
		IContentHandlerFactory $contentHandlerFactory,
		RecentChangeFactory $recentChangeFactory
	): self {
		$ingress = new self(
			$changeTagsStore,
			$userEditTracker,
			$permissionManager,
			$wikiPageFactory,
			$hookContainer,
			$userNameUtils,
			$talkPageNotificationManager,
			$mainConfig,
			$jobQueueGroup,
			$contentHandlerFactory,
			$recentChangeFactory
		);
		$ingress->initSubscriber( self::OBJECT_SPEC );
		return $ingress;
	}

	private static function getEditFlags( PageLatestRevisionChangedEvent $event ): int {
		$flags = $event->isCreation() ? EDIT_NEW : EDIT_UPDATE;

		$flags |= (int)$event->isBotUpdate() * EDIT_FORCE_BOT;
		$flags |= (int)$event->isSilent() * EDIT_SILENT;
		$flags |= (int)$event->isImplicit() * EDIT_IMPLICIT;
		$flags |= (int)$event->getLatestRevisionAfter()->isMinor() * EDIT_MINOR;

		return $flags;
	}

	/**
	 * Listener method for PageLatestRevisionChangedEvent, to be registered with an
	 * DomainEventSource.
	 *
	 * @noinspection PhpUnused
	 */
	public function handlePageLatestRevisionChangedEvent( PageLatestRevisionChangedEvent $event ): void {
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

		if ( $event->isEffectiveContentChange() ) {
			$this->generateCategoryMembershipChanges( $event );

			if ( !$event->isImplicit() ) {
				$this->updateUserEditTrackerAfterPageUpdated(
					$event->getPerformer()
				);

				$this->updateNewTalkAfterPageUpdated( $event );
			}
		}

		if ( $event->isRevert() && $event->isEffectiveContentChange() ) {
			$this->updateRevertTagAfterPageUpdated( $event );
		}
	}

	/**
	 * Create RC entries for category changes that resulted from this update
	 * if the relevant config is enabled.
	 * This should only be triggered for actual edits, not reconciliation events (T390636).
	 *
	 * @param PageLatestRevisionChangedEvent $event
	 */
	private function generateCategoryMembershipChanges( PageLatestRevisionChangedEvent $event ): void {
		if ( $this->rcWatchCategoryMembership
			&& !$event->hasCause( PageLatestRevisionChangedEvent::CAUSE_UNDELETE )
			&& $this->anyChangedSlotSupportsCategories( $event )
		) {
			// Note: jobs are pushed after deferred updates, so the job should be able to see
			// the recent change entry (also done via deferred updates) and carry over any
			// bot/deletion/IP flags, ect.
			$this->jobQueueGroup->lazyPush(
				CategoryMembershipChangeJob::newSpec(
					$event->getPage(),
					$event->getLatestRevisionAfter()->getTimestamp(),
					$event->hasCause( PageLatestRevisionChangedEvent::CAUSE_IMPORT )
				)
			);
		}
	}

	/**
	 * Determine whether any slots changed in this update supports categories.
	 *
	 * @param PageLatestRevisionChangedEvent $event
	 *
	 * @return bool
	 */
	private function anyChangedSlotSupportsCategories( PageLatestRevisionChangedEvent $event ): bool {
		$slotsUpdate = $event->getSlotsUpdate();
		foreach ( $slotsUpdate->getModifiedRoles() as $role ) {
			$model = $slotsUpdate->getModifiedSlot( $role )->getModel();

			if ( $this->contentHandlerFactory->getContentHandler( $model )->supportsCategories() ) {
				return true;
			}
		}

		return false;
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
		if ( !$oldRevisionRecord ) {
			$recentChange = $this->recentChangeFactory->createNewPageRecentChange(
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
			$recentChange = $this->recentChangeFactory->createEditRecentChange(
				$newRevisionRecord->getTimestamp(),
				$newRevisionRecord->getPage(),
				$newRevisionRecord->isMinor(),
				$newRevisionRecord->getUser( RevisionRecord::RAW ),
				$newRevisionRecord->getComment( RevisionRecord::RAW )->text,
				$oldRevisionRecord->getId(),
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

		$this->recentChangeFactory->insertRecentChange( $recentChange );
	}

	private function updateUserEditTrackerAfterPageUpdated( UserIdentity $author ) {
		$this->userEditTracker->incrementUserEditCount( $author );
	}

	/**
	 * Listener method for PageLatestRevisionChangedEvent, to be registered with a DomainEventSource.
	 *
	 * @noinspection PhpUnused
	 */
	private function updateNewTalkAfterPageUpdated( PageLatestRevisionChangedEvent $event ) {
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

	private function updateRevertTagAfterPageUpdated( PageLatestRevisionChangedEvent $event ) {
		$patrolStatus = $event->getPatrolStatus();
		$wikiPage = $this->wikiPageFactory->newFromTitle( $event->getPage() );

		// Should the reverted tag update be scheduled right away?
		// The revert is approved if either patrolling is disabled or the
		// edit is patrolled or autopatrolled.
		$approved = !$this->useRcPatrol ||
			$patrolStatus === RecentChange::PRC_PATROLLED ||
			$patrolStatus === RecentChange::PRC_AUTOPATROLLED;

		$editResult = $event->getEditResult();

		if ( !$editResult ) {
			// Reverts should always have an EditResult.
			throw new LogicException( 'Missing EditResult in revert' );
		}

		$revisionRecord = $event->getLatestRevisionAfter();

		// Allow extensions to override the patrolling subsystem.
		$this->hookRunner->onBeforeRevertedTagUpdate(
			$wikiPage,
			$event->getAuthor(),
			$revisionRecord->getComment( RevisionRecord::RAW ),
			self::getEditFlags( $event ),
			$revisionRecord,
			$editResult,
			$approved
		);

		// Schedule a deferred update for marking reverted edits if applicable.
		if ( $approved ) {
			// Enqueue the job
			$this->jobQueueGroup->lazyPush(
				RevertedTagUpdateJob::newSpec(
					$revisionRecord->getId(),
					$editResult
				)
			);
		}
	}

}
