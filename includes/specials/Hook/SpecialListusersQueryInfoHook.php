<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SpecialListusersQueryInfoHook {
	/**
	 * Called right before the end of.
	 * UsersPager::getQueryInfo()
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $pager The UsersPager instance
	 * @param ?mixed &$query The query array to be returned
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialListusersQueryInfo( $pager, &$query );
}
