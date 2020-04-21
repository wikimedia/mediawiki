<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ChangesListInitRowsHook {
	/**
	 * Batch process change list rows prior to rendering.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $changesList ChangesList instance
	 * @param ?mixed $rows The data that will be rendered. May be a \Wikimedia\Rdbms\IResultWrapper
	 *   instance or an array.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onChangesListInitRows( $changesList, $rows );
}
