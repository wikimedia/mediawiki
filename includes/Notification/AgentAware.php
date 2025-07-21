<?php

namespace MediaWiki\Notification;

use MediaWiki\User\UserIdentity;

/**
 * Marker interface for Notifications aware of the agent who triggered the notification
 *
 * @stable to implement
 * @since 1.45
 */
interface AgentAware {

	/**
	 * @return UserIdentity The user responsible for triggering this notification.
	 */
	public function getAgent(): UserIdentity;

}
