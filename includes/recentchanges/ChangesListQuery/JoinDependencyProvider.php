<?php

namespace MediaWiki\RecentChanges\ChangesListQuery;

/**
 * The narrow interface provided to join modules to allow them to declare any
 * dependencies they have on other tables. This is implemented by
 * ChangesListQuery.
 *
 * @since 1.45
 */
interface JoinDependencyProvider {
	/**
	 * Declare that the join is required to provide fields in the SELECT clause.
	 * Add or retrieve the default instance of the join.
	 *
	 * @param string $table The table name
	 * @return ChangesListJoinBuilder
	 */
	public function joinForFields( string $table ): ChangesListJoinBuilder;

	/**
	 * Declare that the join is required to provide fields for the WHERE clause.
	 * Add or retrieve the default instance of the join.
	 *
	 * @param string $table The table name
	 * @return ChangesListJoinBuilder
	 */
	public function joinForConds( string $table ): ChangesListJoinBuilder;
}
