<?php

namespace MediaWiki\Hook;

use ChangesList;
use ChangesListFilterGroup;
use Skin;
use User;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface FetchChangesListHook {
	/**
	 * This hook is called when fetching the ChangesList derivative for a particular user.
	 *
	 * @since 1.35
	 *
	 * @param User $user User the list is being fetched for
	 * @param Skin $skin Skin object to be used with the list
	 * @param ChangesList|null &$list Defaults to NULL. Change it to an object instance and
	 *   return false to override the list derivative used.
	 * @param ChangesListFilterGroup[] $groups Added in 1.34
	 * @return bool|void True or no return value to continue, or false to to override the list
	 *   derivative used
	 */
	public function onFetchChangesList( $user, $skin, &$list, $groups );
}
