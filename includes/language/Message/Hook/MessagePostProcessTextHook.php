<?php

namespace MediaWiki\Message\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "MessagePostProcessText" to register handlers implementing this interface.
 * If you just want to replace a message, use the MessagesPreLoad hook instead.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface MessagePostProcessTextHook {
	/**
	 * This hook is called after a message is formatted as a text format.
	 * Use this hook if you wish to post process the message.
	 * This hook might not be called for messages constructed early in MW initialization.
	 * @since 1.45
	 *
	 * @param string &$value Formatted value of message.
	 * @param string $format Either Message::FORMAT_PLAIN or Message::FORMAT_TEXT.
	 * @param string $key Message key this is for (RawMessage uses value).
	 */
	public function onMessagePostProcessText( &$value, $format, $key ): void;
}
