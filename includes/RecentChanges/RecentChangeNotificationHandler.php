<?php

namespace MediaWiki\RecentChanges;

use MediaWiki\Notification\Notification;
use MediaWiki\Notification\NotificationHandler;
use MediaWiki\Notification\RecipientSet;
use MediaWiki\Title\TitleFactory;
use MediaWiki\User\User;
use MediaWiki\User\UserFactory;

/**
 * Accept notification events and notify users about them.
 *
 * @internal
 */
class RecentChangeNotificationHandler implements NotificationHandler {

	private UserFactory $userFactory;
	private TitleFactory $titleFactory;

	public function __construct( UserFactory $userFactory, TitleFactory $titleFactory ) {
		$this->userFactory = $userFactory;
		$this->titleFactory = $titleFactory;
	}

	public function checkNotificationRequirements( Notification $notification, User $user ): bool {
		return $user->isEmailConfirmed();
	}

	/**
	 * Notify users about an event occurring.
	 */
	public function notify( Notification $notification, RecipientSet $recipients ): void {
		if ( !$notification instanceof RecentChangeNotification ) {
			return;
		}
		$sourceMap = [
			RecentChangeNotification::ADMIN_NOTIFICATION => RecentChangeMailComposer::ALL_CHANGES,
			RecentChangeNotification::TALK_NOTIFICATION => RecentChangeMailComposer::USER_TALK,
			RecentChangeNotification::WATCHLIST_NOTIFICATION => RecentChangeMailComposer::WATCHLIST,
		];
		$source = $sourceMap[ $notification->getSource() ] ?? RecentChangeMailComposer::ALL_CHANGES;

		$composer = new RecentChangeMailComposer(
			$this->userFactory->newFromUserIdentity( $notification->getAgent() ),
			$this->titleFactory->newFromPageIdentity( $notification->getTitle() ),
			$notification->getRecentChange(),
			$notification->getPageStatus(),
		);
		foreach ( $recipients as $recipient ) {
			$user = $this->userFactory->newFromUserIdentity( $recipient );
			if ( $this->checkNotificationRequirements( $notification, $user ) ) {
				$composer->compose( $recipient, $source );
			}
		}
	}
}
