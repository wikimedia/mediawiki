<?php

namespace MediaWiki\Cache\Hook;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface MessagesPreLoadHook {
	/**
	 * This hook is called when loading a message from the database.
	 *
	 * @since 1.35
	 *
	 * @param string $title Title of the message
	 * @param string &$message Message you want to define
	 * @param string $code Language code
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onMessagesPreLoad( $title, &$message, $code );
}
