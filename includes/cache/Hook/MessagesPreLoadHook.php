<?php

namespace MediaWiki\Cache\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface MessagesPreLoadHook {
	/**
	 * When loading a message from the database.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $title title of the message (string)
	 * @param ?mixed &$message value (string), change it to the message you want to define
	 * @param ?mixed $code code (string) denoting the language to try.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onMessagesPreLoad( $title, &$message, $code );
}
