<?php

namespace MediaWiki\Hook;

use UsersPager;

/**
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
