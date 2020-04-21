<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface EnhancedChangesListModifyBlockLineDataHook {
	/**
	 * to alter data used to build
	 * a non-grouped recent change line in EnhancedChangesList.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $changesList EnhancedChangesList object
	 * @param ?mixed &$data An array with all the components that will be joined in order to create
	 *   the line
	 * @param ?mixed $rc The RecentChange object for this line
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onEnhancedChangesListModifyBlockLineData( $changesList, &$data,
		$rc
	);
}
