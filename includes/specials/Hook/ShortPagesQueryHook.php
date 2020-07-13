<?php

namespace MediaWiki\Hook;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ShortPagesQueryHook {
	/**
	 * Use this hook to modify the query used by Special:ShortPages.
	 *
	 * @since 1.35
	 *
	 * @param array &$tables tables to join in the query
	 * @param array &$conds conditions for the query
	 * @param array &$joinConds join conditions for the query
	 * @param array &$options options for the query
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onShortPagesQuery( &$tables, &$conds, &$joinConds, &$options );
}
