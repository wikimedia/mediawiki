<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface LogEventsListShowLogExtractHook {
	/**
	 * Called before the string is added to OutputPage.
	 * Returning false will prevent the string from being added to the OutputPage.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$s html string to show for the log extract
	 * @param ?mixed $types String or Array Log types to show
	 * @param ?mixed $page String or Title The page title to show log entries for
	 * @param ?mixed $user String The user who made the log entries
	 * @param ?mixed $param Associative Array with the following additional options:
	 *   - lim Integer Limit of items to show, default is 50
	 *   - conds Array Extra conditions for the query (e.g. "log_action != 'revision'")
	 *   - showIfEmpty boolean Set to false if you don't want any output in case the
	 *     loglist is empty if set to true (default), "No matching items in log" is
	 *     displayed if loglist is empty
	 *   - msgKey Array If you want a nice box with a message, set this to the key of
	 *     the message. First element is the message key, additional optional elements
	 *     are parameters for the key that are processed with
	 *     wfMessage()->params()->parseAsBlock()
	 *   - offset Set to overwrite offset parameter in $wgRequest set to '' to unset
	 *     offset
	 *   - wrap String Wrap the message in html (usually something like
	 *     "&lt;div ...>$1&lt;/div>").
	 *   - flags Integer display flags (NO_ACTION_LINK,NO_EXTRA_USER_LINKS)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onLogEventsListShowLogExtract( &$s, $types, $page, $user,
		$param
	);
}
