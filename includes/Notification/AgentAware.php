<?php

namespace MediaWiki\Notification;

use MediaWiki\User\UserIdentity;

/**
 * Marker interface for Notifications aware of the agent who triggered the notification
 *
 * @since 1.44
 * @unstable
 */
interface AgentAware {

	/**
	 * @return UserIdentity The user responsible for triggering this notification.
	 */
	public function getAgent(): UserIdentity;

}
