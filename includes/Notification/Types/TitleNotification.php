<?php
namespace MediaWiki\Notification\Types;

use MediaWiki\Notification\Notification;
use MediaWiki\Notification\TitleAware;
use MediaWiki\Page\PageIdentity;
use Wikimedia\JsonCodec\JsonCodecable;

/**
 * A Notification to describe something that happened on a specific page, without an agent.
 *
 * Use WikiNotification if the notification also has an agent.
 *
 * @newable
 * @since 1.47
 */
class TitleNotification extends Notification implements TitleAware {

	/**
	 * @param string $type Notification type
	 * @param PageIdentity $title The title of the related page
	 * @param (scalar|array|null|JsonCodecable)[] $custom Custom notification data, see
	 * setProperty() for more details about the allowed keys and values
	 */
	public function __construct(
		string $type,
		private readonly PageIdentity $title,
		array $custom = []
	) {
		parent::__construct( $type, $custom );
	}

	/**
	 * @inheritDoc
	 */
	public function getTitle(): PageIdentity {
		return $this->title;
	}

}
