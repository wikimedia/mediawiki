<?php

namespace MediaWiki\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "LonelyPagesQuery" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface LonelyPagesQueryHook {
	/**
	 * Use this hook to modify the query used by Special:LonelyPages.
	 *
	 * @since 1.35
	 *
	 * @param array &$tables tables to join in the query
	 * @param array &$conds conditions for the query
	 * @param array &$joinConds join conditions for the query
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onLonelyPagesQuery( &$tables, &$conds, &$joinConds );
}
