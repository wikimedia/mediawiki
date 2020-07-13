<?php

namespace MediaWiki\Hook;

use EnhancedChangesList;
use RecentChange;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface EnhancedChangesListModifyBlockLineDataHook {
	/**
	 * Use this hook to alter data used to build a non-grouped recent change line in
	 * EnhancedChangesList.
	 *
	 * @since 1.35
	 *
	 * @param EnhancedChangesList $changesList
	 * @param array &$data Array of components that will be joined in order to create the line
	 * @param RecentChange $rc RecentChange object for this line
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onEnhancedChangesListModifyBlockLineData( $changesList, &$data,
		$rc
	);
}
