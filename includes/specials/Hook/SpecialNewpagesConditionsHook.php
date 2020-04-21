<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SpecialNewpagesConditionsHook {
	/**
	 * Called when building sql query for
	 * Special:NewPages.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $special NewPagesPager object (subclass of ReverseChronologicalPager)
	 * @param ?mixed $opts FormOptions object containing special page options
	 * @param ?mixed &$conds array of WHERE conditionals for query
	 * @param ?mixed &$tables array of tables to be queried
	 * @param ?mixed &$fields array of columns to select
	 * @param ?mixed &$join_conds join conditions for the tables
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialNewpagesConditions( $special, $opts, &$conds,
		&$tables, &$fields, &$join_conds
	);
}
