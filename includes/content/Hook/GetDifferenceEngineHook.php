<?php

namespace MediaWiki\Content\Hook;

use DifferenceEngine;
use IContextSource;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface GetDifferenceEngineHook {
	/**
	 * This hook is called when getting a new difference engine interface object.
	 * Use this hook to decorate or replace the default difference engine.
	 *
	 * @since 1.35
	 *
	 * @param IContextSource $context IContextSource context to be used for diff
	 * @param int $old Revision ID to show and diff with
	 * @param int|string $new Either a revision ID or one of the strings 'cur', 'prev' or 'next'
	 * @param bool $refreshCache If set, refreshes the diff cache
	 * @param bool $unhide If set, allow viewing deleted revs
	 * @param DifferenceEngine &$differenceEngine Difference engine object to be used for the diff
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onGetDifferenceEngine( $context, $old, $new, $refreshCache,
		$unhide, &$differenceEngine
	);
}
