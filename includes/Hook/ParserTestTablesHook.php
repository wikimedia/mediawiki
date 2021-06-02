<?php

namespace MediaWiki\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ParserTestTables" to register handlers implementing this interface.
 *
 * @ingroup Hooks
 * @deprecated No longer invoked by MW 1.36+
 */
interface ParserTestTablesHook {
	/**
	 * Use this hook to alter the list of tables to duplicate when parser tests are
	 * run. Use when page save hooks require the presence of custom tables to ensure
	 * that tests continue to run properly.
	 *
	 * @since 1.35
	 *
	 * @param string[] &$tables Array of table names
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onParserTestTables( &$tables );
}
