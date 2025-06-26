<?php

namespace MediaWiki\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "SpecialLogResolveLogType" to register handlers
 * implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface SpecialLogResolveLogTypeHook {
	/**
	 * Hook for intercepting and changing the requested log type in Special:Log,
	 * for example, in order to intercept an alias for the log type and change
	 * it to the canonical name.
	 *
	 * @param array $params Request parameters from the URL
	 * @param string &$type Log type, which may be changed by the hook
	 *
	 * @return void
	 * @since 1.45
	 */
	public function onSpecialLogResolveLogType(
		array $params,
		string &$type
	): void;
}
