<?php

namespace MediaWiki\Hook;

use MediaWiki\RecentChanges\RecentChange;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "IRCLineURL" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface IRCLineURLHook {
	/**
	 * This hook is called when constructing the URL to use in an RCFeed notification.
	 * Callee may modify $url and $query; URL will be constructed as $url . $query
	 *
	 * @since 1.35
	 *
	 * @param string &$url URL to index.php
	 * @param string &$query Query string
	 * @param RecentChange $rc RecentChange object that triggered URL generation
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onIRCLineURL( &$url, &$query, $rc );
}
