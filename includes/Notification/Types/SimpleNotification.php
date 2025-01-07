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

	public function __construct( MessageSpecifier $message ) {
		parent::__construct( self::TYPE, [
			'msg' => $message
		] );
	}
}
