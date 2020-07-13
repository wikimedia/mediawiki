<?php

namespace MediaWiki\SpecialPage\Hook;

use FormOptions;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ChangesListSpecialPageQueryHook {
	/**
	 * This hook is called when building an SQL query on pages inheriting from
	 * ChangesListSpecialPage (in core: RecentChanges, RecentChangesLinked and
	 * Watchlist). Do not use this to implement individual filters if they are
	 * compatible with the ChangesListFilter and ChangesListFilterGroup structure.
	 * Instead, use sub-classes of those classes in conjunction with the
	 * ChangesListSpecialPageStructuredFilters hook. This hook can be used to
	 * implement filters that do not implement that structure or custom behavior
	 * that is not an individual filter.
	 *
	 * @since 1.35
	 *
	 * @param string $name Name of the special page, e.g. 'Watchlist'
	 * @param array &$tables Array of tables to be queried
	 * @param array &$fields Array of columns to select
	 * @param array &$conds Array of WHERE conditionals for query
	 * @param array &$query_options Array of options for the database request
	 * @param array &$join_conds Join conditions for the tables
	 * @param FormOptions $opts FormOptions for this request
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onChangesListSpecialPageQuery( $name, &$tables, &$fields,
		&$conds, &$query_options, &$join_conds, $opts
	);
}
