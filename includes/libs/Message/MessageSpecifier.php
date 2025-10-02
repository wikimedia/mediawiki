<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace Wikimedia\Message;

/**
 * @stable for implementing
 */
interface MessageSpecifier {
	/**
	 * Returns the message key
	 *
	 * If a list of multiple possible keys was supplied to the constructor, this method may
	 * return any of these keys. After the message has been fetched, this method will return
	 * the key that was actually used to fetch the message.
	 *
	 * @return string
	 */
	public function getKey(): string;

	/**
	 * Returns the message parameters
	 *
	 * @return (MessageParam|MessageSpecifier|string|int|float)[]
	 */
	public function getParams(): array;
}

/**
 * @deprecated since 1.43
 */
class_alias( MessageSpecifier::class, 'MessageSpecifier' );
