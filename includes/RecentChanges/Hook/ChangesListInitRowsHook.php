<?php

namespace MediaWiki\Hook;

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
	 * @param IResultWrapper|\stdClass[] $rows Data that will be rendered
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onChangesListInitRows( $changesList, $rows );
}
