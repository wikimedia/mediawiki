<?php

namespace MediaWiki\Language\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "MessagesPreLoad" to register handlers implementing this interface.
 *
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

/** @deprecated class alias since 1.46 */
class_alias( MessagesPreLoadHook::class, 'MediaWiki\\Cache\\Hook\\MessagesPreLoadHook' );
