<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Exception;

use MediaWiki\Message\Message;

/**
 * Interface for MediaWiki-localized exceptions
 *
 * @stable to implement
 *
 * @since 1.29
 * @ingroup Exception
 */
interface ILocalizedException {
	/**
	 * Return a Message object for this exception
	 * @return Message
	 */
	public function getMessageObject();
}

/** @deprecated class alias since 1.44 */
class_alias( ILocalizedException::class, 'ILocalizedException' );
