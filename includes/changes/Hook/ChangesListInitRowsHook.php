<?php

namespace MediaWiki\Hook;

use ChangesList;
use Wikimedia\Rdbms\IResultWrapper;

/**
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
	 * @param IResultWrapper|array $rows Data that will be rendered
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onChangesListInitRows( $changesList, $rows );
}
