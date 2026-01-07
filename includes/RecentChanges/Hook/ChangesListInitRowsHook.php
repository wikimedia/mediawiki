<?php

namespace MediaWiki\RecentChanges\Hook;

use MediaWiki\RecentChanges\ChangesList;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ChangesListInitRows" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface ChangesListInitRowsHook {
	/**
	 * Use this hook to batch process change list rows prior to rendering.
	 *
	 * @since 1.35
	 *
	 * @param ChangesList $changesList
	 * @param IResultWrapper|\stdClass[] $rows SQL query result that will be rendered by one of the
	 *  - {@link SpecialRecentChanges},
	 *  - {@link SpecialRecentChangesLinked}, or
	 *  - {@link SpecialWatchlist} special pages.
	 *  You can use e.g. {@link RecentChange::newFromRow} if you want a richer interface to access
	 *  individual rows.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onChangesListInitRows( $changesList, $rows );
}

/** @deprecated class alias since 1.46 */
class_alias( ChangesListInitRowsHook::class, 'MediaWiki\\Hook\\ChangesListInitRowsHook' );
