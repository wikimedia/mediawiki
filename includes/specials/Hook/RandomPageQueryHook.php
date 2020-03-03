<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface RandomPageQueryHook {
	/**
	 * Lets you modify the query used by Special:Random to select
	 * random pages.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$tables Database tables to be used in the query
	 * @param ?mixed &$conds Conditions to be applied in the query
	 * @param ?mixed &$joinConds Join conditions to be applied in the query
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onRandomPageQuery( &$tables, &$conds, &$joinConds );
}
