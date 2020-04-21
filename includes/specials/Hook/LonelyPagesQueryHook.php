<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface LonelyPagesQueryHook {
	/**
	 * Allow extensions to modify the query used by
	 * Special:LonelyPages.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$tables tables to join in the query
	 * @param ?mixed &$conds conditions for the query
	 * @param ?mixed &$joinConds join conditions for the query
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onLonelyPagesQuery( &$tables, &$conds, &$joinConds );
}
