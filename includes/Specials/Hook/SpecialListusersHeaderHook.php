<?php

namespace MediaWiki\Hook;

use MediaWiki\Pager\UsersPager;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "SpecialListusersHeader" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface SpecialListusersHeaderHook {
	/**
	 * This hook is called after adding the submit button in UsersPager::getPageHeader().
	 *
	 * @since 1.35
	 *
	 * @param UsersPager $pager The UsersPager instance
	 * @param string &$out The header HTML
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialListusersHeader( $pager, &$out );
}
