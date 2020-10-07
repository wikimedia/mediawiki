<?php

namespace MediaWiki\Hook;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface RandomPageQueryHook {
	/**
	 * Use this hook to modify the query used by Special:Random to select random pages.
	 *
	 * @since 1.35
	 *
	 * @param array &$tables Database tables to be used in the query
	 * @param array &$conds Conditions to be applied in the query
	 * @param array &$joinConds Join conditions to be applied in the query
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onRandomPageQuery( &$tables, &$conds, &$joinConds );
}
