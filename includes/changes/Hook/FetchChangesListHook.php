<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface FetchChangesListHook {
	/**
	 * When fetching the ChangesList derivative for a particular
	 * user.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $user User the list is being fetched for
	 * @param ?mixed $skin Skin object to be used with the list
	 * @param ?mixed &$list List object (defaults to NULL, change it to an object instance and
	 *   return false override the list derivative used)
	 * @param ?mixed $groups Array of ChangesListFilterGroup objects (added in 1.34)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onFetchChangesList( $user, $skin, &$list, $groups );
}
