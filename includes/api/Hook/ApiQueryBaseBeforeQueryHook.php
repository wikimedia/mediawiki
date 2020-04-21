<?php

namespace MediaWiki\Api\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ApiQueryBaseBeforeQueryHook {
	/**
	 * Called for (some) API query modules before a
	 * database query is made. WARNING: It would be very easy to misuse this hook and
	 * break the module! Any joins added *must* join on a unique key of the target
	 * table unless you really know what you're doing. An API query module wanting to
	 * use this hook should see the ApiQueryBase::select() and
	 * ApiQueryBase::processRow() documentation.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $module ApiQueryBase module in question
	 * @param ?mixed &$tables array of tables to be queried
	 * @param ?mixed &$fields array of columns to select
	 * @param ?mixed &$conds array of WHERE conditionals for query
	 * @param ?mixed &$query_options array of options for the database request
	 * @param ?mixed &$join_conds join conditions for the tables
	 * @param ?mixed &$hookData array that will be passed to the 'ApiQueryBaseAfterQuery' and
	 *   'ApiQueryBaseProcessRow' hooks, intended for inter-hook communication.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onApiQueryBaseBeforeQuery( $module, &$tables, &$fields,
		&$conds, &$query_options, &$join_conds, &$hookData
	);
}
