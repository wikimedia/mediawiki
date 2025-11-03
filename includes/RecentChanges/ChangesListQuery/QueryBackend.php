<?php

namespace MediaWiki\RecentChanges\ChangesListQuery;

use Wikimedia\Rdbms\IExpression;

/**
 * The narrow interface passed to filter modules. Filter condition modules call
 * these methods during query preparation to register their fields, conditions
 * and joins.
 *
 * @since 1.45
 */
interface QueryBackend {
	/** The naive density of a RecentChangesLinked query */
	public const DENSITY_LINKS = 'links';
	/** The naive density of a watchlist query */
	public const DENSITY_WATCHLIST = 'watchlist';
	/** The naive density of a user/actor condition */
	public const DENSITY_USER = 'user';
	/** The minimum density to active change tag heuristics */
	public const DENSITY_CHANGE_TAG_THRESHOLD = 'change-tag-threshold';

	/** The recentchanges table will likely be first in the join */
	public const JOIN_ORDER_RECENTCHANGES = 'recentchanges';
	/** Another table will likely be first in the join */
	public const JOIN_ORDER_OTHER = 'other';

	/**
	 * Join on the specified table and declare that it will be used to provide
	 * fields for the SELECT clause. The table name must be registered in the
	 * ChangesListQuery. The join type can be set by calling a method on the
	 * returned object.
	 *
	 * @param string $table
	 * @return ChangesListJoinBuilder
	 */
	public function joinForFields( string $table ): ChangesListJoinBuilder;

	/**
	 * Join on the specified table and declare that it will be used to provide
	 * fields for the WHERE clause. The table name must be registered in the
	 * ChangesListQuery. The join type can be set by calling a method on the
	 * returned object.
	 *
	 * @param string $table
	 * @return ChangesListJoinBuilder
	 */
	public function joinForConds( string $table ): ChangesListJoinBuilder;

	/**
	 * Flag that the joins will inadvertently duplicate recentchanges rows and
	 * that the query will have to deal with that somehow, maybe by adding a
	 * DISTINCT option.
	 *
	 * @return $this
	 */
	public function distinct(): self;

	/**
	 * Adjust the density heuristic by multiplying it by the given factor. This
	 * sets the proportion of recentchanges rows likely to be matched by the
	 * conditions.
	 *
	 * @param float|int|string $density Either a number or one of the
	 *   self::DENSITY_* constants.
	 * @return $this
	 */
	public function adjustDensity( $density ): self;

	/**
	 * Set the join order hint. Whether recentchanges or some other table will
	 * likely be first in the join. If this is JOIN_ORDER_OTHER, partitioning
	 * the query by timestamp will be considered.
	 *
	 * @param string $order
	 */
	public function joinOrderHint( string $order ): self;

	/**
	 * Add a condition to the query
	 *
	 * @param IExpression $expr
	 * @return $this
	 */
	public function where( IExpression $expr ): self;

	/**
	 * Add fields to the query
	 *
	 * @param string|string[] $fields
	 * @return $this
	 */
	public function fields( $fields ): self;

	/**
	 * Add the rc_user and rc_user_text fields to the query, conventional
	 * aliases for actor_user and actor_name.
	 *
	 * @return $this
	 */
	public function rcUserFields(): self;

	/**
	 * Set a flag forcing the query to return no rows when it is executed. Like
	 * adding a 0=1 condition.
	 *
	 * @return $this
	 */
	public function forceEmptySet(): self;

	/**
	 * Check whether forceEmptySet() has been called.
	 *
	 * @return bool
	 */
	public function isEmptySet(): bool;
}
