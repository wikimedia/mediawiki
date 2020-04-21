<?php

namespace MediaWiki\Content\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface GetDifferenceEngineHook {
	/**
	 * Called when getting a new difference engine interface
	 * object.  Can be used to decorate or replace the default difference engine.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $context IContextSource context to be used for diff
	 * @param ?mixed $old Revision ID to show and diff with
	 * @param ?mixed $new Either a revision ID or one of the strings 'cur', 'prev' or 'next'
	 * @param ?mixed $refreshCache If set, refreshes the diff cache
	 * @param ?mixed $unhide If set, allow viewing deleted revs
	 * @param ?mixed &$differenceEngine The difference engine object to be used for the diff
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onGetDifferenceEngine( $context, $old, $new, $refreshCache,
		$unhide, &$differenceEngine
	);
}
