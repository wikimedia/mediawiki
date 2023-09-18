<?php

namespace MediaWiki\Hook;

use MediaWiki\Pager\UsersPager;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "SpecialListusersQueryInfo" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface SpecialListusersQueryInfoHook {
	/**
	 * This hook is called right before the end of UsersPager::getQueryInfo()
	 *
	 * @since 1.35
	 *
	 * @param UsersPager $pager The UsersPager instance
	 * @param array &$query The query array to be returned
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialListusersQueryInfo( $pager, &$query );
}
