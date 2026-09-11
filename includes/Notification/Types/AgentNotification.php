<?php
namespace MediaWiki\Notification\Types;

use MediaWiki\Notification\AgentAware;
use MediaWiki\Notification\Notification;
use MediaWiki\User\UserIdentity;
use Wikimedia\JsonCodec\JsonCodecable;

/**
 * A Notification to describe something that was done by a user, without a related page.
 *
 * Use WikiNotification if the notification also has a related page.
 *
 * @newable
 * @since 1.47
 */
class AgentNotification extends Notification implements AgentAware {

	/**
	 * @param string $type Notification type
	 * @param UserIdentity $agent The user responsible for triggering this notification.
	 * @param (scalar|array|null|JsonCodecable)[] $custom Custom notification data, see
	 * setProperty() for more details about the allowed keys and values
	 */
	public function __construct(
		string $type,
		private readonly UserIdentity $agent,
		array $custom = []
	) {
		parent::__construct( $type, $custom );
	}

	/**
	 * @inheritDoc
	 */
	public function getAgent(): UserIdentity {
		return $this->agent;
	}

}
