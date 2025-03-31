<?php
namespace MediaWiki\Notification;

/**
 * An object representing notification with list of recipients
 * @since 1.44
 * @unstable
 */
class NotificationEnvelope {

	private Notification $notification;
	private RecipientSet $recipientSet;

	public function __construct( Notification $notification, RecipientSet $recipientSet ) {
		$this->notification = $notification;
		$this->recipientSet = $recipientSet;
	}

	public function getNotification(): Notification {
		return $this->notification;
	}

	public function getRecipientSet(): RecipientSet {
		return $this->recipientSet;
	}

	/**
	 * Syntax sugar, allows easy check if two envelopes point to the same thing
	 */
	public function equals( NotificationEnvelope $envelope ): bool {
		return $envelope === $this;
	}

	/**
	 * Check if the Notification has defined agent.
	 *
	 * Utility method for a very common check where middleware filters Notifications from
	 * specific agent.
	 */
	public function hasAgent(): bool {
		return $this->notification instanceof AgentAware;
	}

}
