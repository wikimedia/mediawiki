<?php

namespace MediaWiki\Hook;

use Wikimedia\Rdbms\IDatabase;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ModifyExportQueryHook {
	/**
	 * Use this hook to modify the query used by the exporter.
	 *
	 * @since 1.35
	 *
	 * @param IDatabase $db Database object to be queried
	 * @param array &$tables Tables in the query
	 * @param array &$conds Conditions in the query
	 * @param array &$opts Options for the query
	 * @param array &$join_conds Join conditions for the query
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onModifyExportQuery( $db, &$tables, &$conds, &$opts,
		&$join_conds
	);
}
