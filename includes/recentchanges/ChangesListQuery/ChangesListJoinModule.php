<?php

namespace MediaWiki\RecentChanges\ChangesListQuery;

use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * A module encapsulating join conditions for a ChangesListQuery join
 *
 * @since 1.45
 */
interface ChangesListJoinModule {
	/**
	 * Declare that the join is required to provide fields in the SELECT clause.
	 * Add or retrieve the default instance of the join.
	 *
	 * @param JoinDependencyProvider $provider
	 * @return ChangesListJoinBuilder
	 */
	public function forFields( JoinDependencyProvider $provider ): ChangesListJoinBuilder;

	/**
	 * Declare that the join is required to provide fields for the WHERE clause.
	 * Add or retrieve the default instance of the join.
	 *
	 * @param JoinDependencyProvider $provider
	 * @return ChangesListJoinBuilder
	 */
	public function forConds( JoinDependencyProvider $provider ): ChangesListJoinBuilder;

	/**
	 * Add or retrieve an instance of the join with the given table alias.
	 *
	 * @param JoinDependencyProvider $provider
	 * @param string $alias
	 * @return ChangesListJoinBuilder
	 */
	public function alias( JoinDependencyProvider $provider, string $alias ): ChangesListJoinBuilder;

	/**
	 * Add all registered instances of the join to a SELECT query.
	 *
	 * @param SelectQueryBuilder $sqb
	 */
	public function prepare( SelectQueryBuilder $sqb ): void;
}
