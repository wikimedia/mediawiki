<?php

namespace MediaWiki\Hook;

/**
 * @stable to implement
 * @ingroup Hooks
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
