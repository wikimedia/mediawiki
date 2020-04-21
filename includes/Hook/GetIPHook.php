<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface GetIPHook {
	/**
	 * modify the ip of the current user (called only once).
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$ip string holding the ip as determined so far
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onGetIP( &$ip );
}
