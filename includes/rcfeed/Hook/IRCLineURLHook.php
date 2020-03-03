<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface IRCLineURLHook {
	/**
	 * When constructing the URL to use in an IRC notification.
	 * Callee may modify $url and $query, URL will be constructed as $url . $query
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$url URL to index.php
	 * @param ?mixed &$query Query string
	 * @param ?mixed $rc RecentChange object that triggered url generation
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onIRCLineURL( &$url, &$query, $rc );
}
