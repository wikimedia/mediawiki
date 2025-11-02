<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Message\Message;
use Wikimedia\Message\MessageParam;
use Wikimedia\Message\MessageSpecifier;

/**
 * Interface for localizing messages in MediaWiki
 *
 * @stable to implement
 *
 * @since 1.30
 * @ingroup Language
 */
interface MessageLocalizer {

	/**
	 * This is the method for getting translated interface messages.
	 *
	 * @see https://www.mediawiki.org/wiki/Manual:Messages_API
	 * @see Message::__construct
	 *
	 * @param string|string[]|MessageSpecifier $key Message key, or array of keys,
	 *   or a MessageSpecifier.
	 * @phpcs:ignore Generic.Files.LineLength
	 * @param MessageParam|MessageSpecifier|string|int|float|list<MessageParam|MessageSpecifier|string|int|float> ...$params
	 *   See Message::params()
	 * @return Message
	 */
	public function msg( $key, ...$params );

}
