<?php

namespace MediaWiki\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "LogEventsListShowLogExtract" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface LogEventsListShowLogExtractHook {
	/**
	 * This hook is called before the string is added to OutputPage.
	 *
	 * @since 1.35
	 *
	 * @param string &$s HTML to show for the log extract
	 * @param string|array $types Log types to show
	 * @param array $pages Page titles to show log entries for
	 * @param string $user User who made the log entries
	 * @param array $param Associative array with the following additional options:
	 *   - `lim` (integer): Limit of items to show, default is 50
	 *   - `conds` (array): Extra conditions for the query (e.g. "log_action != 'revision'")
	 *   - `showIfEmpty` (boolean): Set to false if you don't want any output in case the
	 *     loglist is empty if set to true (default), "No matching items in log" is
	 *     displayed if loglist is empty
	 *   - `msgKey` (array): If you want a nice box with a message, set this to the key of
	 *     the message. First element is the message key, additional optional elements
	 *     are parameters for the key that are processed with
	 *     wfMessage()->params()->parseAsBlock()
	 *   - `offset`: Set to overwrite offset parameter in $wgRequest set to '' to unset
	 *     offset
	 *   - `wrap` (string): Wrap the message in html (usually something like
	 *     "&lt;div ...>$1&lt;/div>")
	 *   - `flags` (integer): Display flags (NO_ACTION_LINK,NO_EXTRA_USER_LINKS)
	 * @return bool|void True or no return value to continue, or false to prevent the
	 *   string from being added to the OutputPage
	 */
	public function onLogEventsListShowLogExtract( &$s, $types, $pages, $user,
		$param
	);
}
