<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface GetLogTypesOnUserHook {
	/**
	 * Add log types where the target is a userpage
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$types Array of log types
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onGetLogTypesOnUser( &$types );
}
