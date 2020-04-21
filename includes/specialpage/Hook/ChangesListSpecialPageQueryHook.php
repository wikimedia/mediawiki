<?php

namespace MediaWiki\SpecialPage\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ChangesListSpecialPageQueryHook {
	/**
	 * Called when building SQL query on pages
	 * inheriting from ChangesListSpecialPage (in core: RecentChanges,
	 * RecentChangesLinked and Watchlist).
	 * Do not use this to implement individual filters if they are compatible with the
	 * ChangesListFilter and ChangesListFilterGroup structure.
	 * Instead, use sub-classes of those classes, in conjunction with the
	 * ChangesListSpecialPageStructuredFilters hook.
	 * This hook can be used to implement filters that do not implement that structure,
	 * or custom behavior that is not an individual filter.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $name name of the special page, e.g. 'Watchlist'
	 * @param ?mixed &$tables array of tables to be queried
	 * @param ?mixed &$fields array of columns to select
	 * @param ?mixed &$conds array of WHERE conditionals for query
	 * @param ?mixed &$query_options array of options for the database request
	 * @param ?mixed &$join_conds join conditions for the tables
	 * @param ?mixed $opts FormOptions for this request
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onChangesListSpecialPageQuery( $name, &$tables, &$fields,
		&$conds, &$query_options, &$join_conds, $opts
	);
}
