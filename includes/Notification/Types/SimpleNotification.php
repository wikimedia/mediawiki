<?php
namespace MediaWiki\Notification\Types;

use MediaWiki\Notification\Notification;
use Wikimedia\Message\MessageSpecifier;

/**
 * A simple notification that has only a message presented to a user
 *
 * @since 1.44
 * @unstable
 */
class SimpleNotification extends Notification {

	public const TYPE = 'mediawiki.simple';

	private MessageSpecifier $message;

	public function __construct( MessageSpecifier $message ) {
		$this->message = $message;
		parent::__construct( self::TYPE );
	}

	public function getMessage(): MessageSpecifier {
		return $this->message;
	}

}
