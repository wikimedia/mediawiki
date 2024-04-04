<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Http;

use MediaWiki\Exception\ILocalizedException;
use MediaWiki\Message\Message;
use RuntimeException;
use Wikimedia\Message\MessageSpecifier;
use Wikimedia\Message\MessageValue;

/**
 * @internal For use by GuzzleHttpRequest
 */
class RedirectRejectedException extends RuntimeException implements ILocalizedException {
	private readonly MessageSpecifier $messageSpecifier;

	/**
	 * @param MessageSpecifier $reason Localized reason why the redirect was rejected
	 */
	public function __construct( MessageSpecifier $reason ) {
		$this->messageSpecifier = new MessageValue( 'http-redirect-rejected', [ $reason ] );
		parent::__construct( 'HTTP redirect target rejected' );
	}

	/** @inheritDoc */
	public function getMessageObject(): Message {
		return Message::newFromSpecifier( $this->messageSpecifier );
	}
}
