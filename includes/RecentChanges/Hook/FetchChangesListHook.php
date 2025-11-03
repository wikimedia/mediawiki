<?php

namespace MediaWiki\Hook;

use MediaWiki\RecentChanges\ChangesList;
use MediaWiki\RecentChanges\ChangesListFilterGroupContainer;
use MediaWiki\Skin\Skin;
use MediaWiki\User\User;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "FetchChangesList" to register handlers implementing this interface.
 *
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
	 * @param ChangesListFilterGroupContainer $groups Added in 1.34, was an array until 1.45
	 * @return bool|void True or no return value to continue, or false to override the list
	 *   derivative used
	 */
	public function onFetchChangesList( $user, $skin, &$list, $groups );
}
