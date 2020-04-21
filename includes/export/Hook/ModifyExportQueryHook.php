<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ModifyExportQueryHook {
	/**
	 * Modify the query used by the exporter.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $db The database object to be queried.
	 * @param ?mixed &$tables Tables in the query.
	 * @param ?mixed &$conds Conditions in the query.
	 * @param ?mixed &$opts Options for the query.
	 * @param ?mixed &$join_conds Join conditions for the query.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onModifyExportQuery( $db, &$tables, &$conds, &$opts,
		&$join_conds
	);
}
