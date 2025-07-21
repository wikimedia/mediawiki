<?php
namespace MediaWiki\Notification\Types;

use MediaWiki\Notification\AgentAware;
use MediaWiki\Notification\Notification;
use MediaWiki\Notification\TitleAware;
use MediaWiki\Page\PageIdentity;
use MediaWiki\User\UserIdentity;
use Wikimedia\JsonCodec\JsonCodecable;

/**
 * A Notification to describe something was done by a user on specific page.
 * This notification requires both Title and Agent to be present.
 *
 * In case you require a notification that doesn't provide both Agent and Title please
 * provide your own Notification implementation.
 *
 * @newable
 * @since 1.45
 */
class WikiNotification extends Notification implements AgentAware, TitleAware {

	/**
	 * @var PageIdentity Page this Notification refers to
	 */
	private PageIdentity $title;

	/**
	 * @var UserIdentity User who caused this notification
	 */
	private UserIdentity $agent;

	/**
	 * Sets the agent (UserIdentity) who triggered this notification.
	 *
	 * @param string $type Notification type
	 * @param PageIdentity $title The title of the related page
	 * @param UserIdentity $agent The user responsible for triggering this notification.
	 * @param (scalar|array|null|JsonCodecable)[] $custom Custom notification data, see
	 * setProperty() for more details about the allowed keys and values
	 */
	public function __construct(
		string $type,
		PageIdentity $title,
		UserIdentity $agent,
		array $custom = []
	) {
		parent::__construct( $type, $custom );
		$this->title = $title;
		$this->agent = $agent;
	}

	public function setAgent( UserIdentity $agent ): self {
		$this->agent = $agent;
		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function getAgent(): UserIdentity {
		return $this->agent;
	}

	/**
	 * @inheritDoc
	 */
	public function getTitle(): PageIdentity {
		return $this->title;
	}

}
