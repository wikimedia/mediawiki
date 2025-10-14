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
