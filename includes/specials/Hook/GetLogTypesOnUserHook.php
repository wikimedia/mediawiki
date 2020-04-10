<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface GetLogTypesOnUserHook {
	/**
	 * Use this hook to add log types where the target is a userpage
	 *
	 * @since 1.35
	 *
	 * @param array &$types Array of log types
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onGetLogTypesOnUser( &$types );
}
