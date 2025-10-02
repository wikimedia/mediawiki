<?php

namespace MediaWiki\Cache\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "MessageCacheFetchOverrides" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface MessageCacheFetchOverridesHook {
	/**
	 * This hook is called to fetch overrides. Use this hook to override message keys
	 * for customisations. Returned messages key must be formatted with:
	 * 1) the first letter in lower case according to the content language
	 * 2) spaces replaced with underscores
	 *
	 * @since 1.41
	 *
	 * @param (string|callable)[] &$keys Message keys mapped to their override. Values may also be a
	 *   callable that returns a message key. Callables are passed the message key,
	 *   the MessageCache instance, a Language/StubUserLang object and a boolean indicating if the
	 *   value should be fetched from the database. Note that strings will always be interpreted as
	 *   a message key; this is not valid callable syntax! This prevents ambiguity between message keys and functions.
	 *   Use Closure::fromCallable or another valid callable syntax.
	 */
	public function onMessageCacheFetchOverrides( array &$keys ): void;
}
