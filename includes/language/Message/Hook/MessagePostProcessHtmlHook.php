<?php

namespace MediaWiki\Message\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "MessagePostProcessHtml" to register handlers implementing this interface.
 * If you just want to replace a message, use the MessagesPreLoad hook instead.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface MessagePostProcessHtmlHook {
	/**
	 * This hook is called after a message is formatted as HTML.
	 * Use this hook if you wish to post process the message.
	 * The final value of $value will be used as HTML, so be careful not to introduce XSS.
	 * This hook will not be called for messages formatted early in MW initialization.
	 * @since 1.45
	 *
	 * @param string &$value Formatted value of message.
	 * @param string $format One of Message::FORMAT_PARSE, Message::FORMAT_BLOCK_PARSE, Message::FORMAT_ESCAPED.
	 * @param string $key Message key this is for (RawMessage uses value).
	 */
	public function onMessagePostProcessHtml( &$value, $format, $key ): void;
}
