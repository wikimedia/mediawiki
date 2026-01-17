<?php

namespace Wikimedia\Rdbms;

/**
 * Shared interface of all write query builders.
 *
 * @since 1.46
 * @ingroup Database
 */
interface IWriteQueryBuilder {
	/**
	 * Change the IDatabase object the query builder is bound to. The specified
	 * IDatabase will subsequently be used to execute the query.
	 *
	 * @param IDatabase $db
	 * @return $this
	 */
	public function connection( IDatabase $db );

	/**
	 * Set the query parameters to the given values, appending to the values
	 * which were already set. This can be used to interface with legacy code.
	 * If a key is omitted, the previous value will be retained.
	 *
	 * The parameters must be formatted as required by the counter-part method in Database object.
	 *
	 * @param array $info Associative array of query info, with keys:
	 *   - table: The table name to be passed to the counter-part method in Database object
	 *   - set: The set conditions
	 *   - conds: The conditions
	 *   - options: The query options
	 *   - caller: The caller signature.
	 *
	 * @return $this
	 */
	public function queryInfo( $info );

	/**
	 * Get an associative array describing the query in terms of its raw parameters to
	 * the counter-part method in Database object. This can be used to interface with legacy code.
	 *
	 * @return array The query info array, with keys:
	 *   - table: The table name
	 *   - set: The set array
	 *   - conds: The conditions
	 *   - options: The query options
	 *   - caller: The caller signature
	 */
	public function getQueryInfo();

	/**
	 * Run the constructed query.
	 */
	public function execute(): void;

	/**
	 * Set the method name to be included in an SQL comment.
	 *
	 * @param string $fname
	 * @param-taint $fname exec_sql
	 * @return $this
	 */
	public function caller( $fname );
}
