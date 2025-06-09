<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */
namespace Wikimedia\Rdbms\Platform;

use Wikimedia\Rdbms\DBError;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\LikeMatch;
use Wikimedia\Rdbms\RawSQLValue;
use Wikimedia\Rdbms\Subquery;

/**
 * Interface for query language.
 * Note: This is for simple SQL operations, use query builder classes for building full queries.
 *
 * Methods of this interface should be only used by rdbms library.
 * @since 1.39
 */
interface ISQLPlatform {

	/** Combine list with comma delimiters */
	public const LIST_COMMA = 0;
	/** Combine list with AND clauses */
	public const LIST_AND = 1;
	/** Convert map into a SET clause */
	public const LIST_SET = 2;
	/** Treat as field name and do not apply value escaping */
	public const LIST_NAMES = 3;
	/** Combine list with OR clauses */
	public const LIST_OR = 4;

	/** Unconditional update/delete of whole table */
	public const ALL_ROWS = '*';

	/** Idiom for "no special flags" */
	public const QUERY_NORMAL = 0;
	/** Ignore query errors and return false when they happen */
	public const QUERY_SILENCE_ERRORS = 1; // b/c for 1.32 query() argument; (int)true = 1
	/** Track a TEMPORARY table CREATE as if it was for a permanent table (for testing) */
	public const QUERY_PSEUDO_PERMANENT = 2;
	/** Enforce that a query does not make effective writes */
	public const QUERY_REPLICA_ROLE = 4;
	/** Ignore the current presence of any DBO_TRX flag */
	public const QUERY_IGNORE_DBO_TRX = 8;
	/** Do not try to retry the query if the connection was lost */
	public const QUERY_NO_RETRY = 16;
	/** Query is a read-only Data Query Language query */
	public const QUERY_CHANGE_NONE = 32;
	/** Query is a Transaction Control Language command (BEGIN, USE, SET, ...) */
	public const QUERY_CHANGE_TRX = 64;
	/** Query is a Data Manipulation Language command (INSERT, DELETE, LOCK, ...) */
	public const QUERY_CHANGE_ROWS = 128;
	/** Query is a Data Definition Language command */
	public const QUERY_CHANGE_SCHEMA = 256;
	/** Query is a command for advisory locks */
	public const QUERY_CHANGE_LOCKS = 512;

	/**
	 * Special value for ->caller() / $fname parameter used when providing a caller is not
	 * expected, because we're formatting a subquery that won't be executed directly.
	 * @since 1.43
	 */
	public const CALLER_SUBQUERY = 'subquery';

	/**
	 * Special value for ->caller() / $fname parameter used when a caller is not provided.
	 * @since 1.43
	 */
	public const CALLER_UNKNOWN = 'unknown';

	/**
	 * @param string|int $field
	 * @return string
	 */
	public function bitNot( $field );

	/**
	 * @param string|int $fieldLeft
	 * @param string|int $fieldRight
	 * @return string
	 */
	public function bitAnd( $fieldLeft, $fieldRight );

	/**
	 * @param string|int $fieldLeft
	 * @param string|int $fieldRight
	 * @return string
	 */
	public function bitOr( $fieldLeft, $fieldRight );

	/**
	 * Escape a SQL identifier (e.g. table, column, database) for use in a SQL query
	 *
	 * Depending on the database this will either be `backticks` or "double quotes"
	 *
	 * @param string $s
	 * @param-taint $s escapes_sql NOTE: this is subpar, as addIdentifierQuotes isn't always the right type of escaping.
	 * @return string
	 * @return-taint none
	 * @since 1.33
	 */
	public function addIdentifierQuotes( $s );

	/**
	 * Build a GREATEST function statement comparing columns/values
	 *
	 * Integer and float values in $values will not be quoted
	 *
	 * If $fields is an array, then each value with a string key is treated as an expression
	 * (which must be manually quoted); such string keys do not appear in the SQL and are only
	 * descriptive aliases.
	 *
	 * @param string|string[] $fields Name(s) of column(s) with values to compare
	 * @param string|int|float|string[]|int[]|float[] $values Values to compare
	 * @return mixed
	 * @since 1.35 in IDatabase, moved to ISQLPlatform in 1.39
	 */
	public function buildGreatest( $fields, $values );

	/**
	 * Build a LEAST function statement comparing columns/values
	 *
	 * Integer and float values in $values will not be quoted
	 *
	 * If $fields is an array, then each value with a string key is treated as an expression
	 * (which must be manually quoted); such string keys do not appear in the SQL and are only
	 * descriptive aliases.
	 *
	 * @param string|string[] $fields Name(s) of column(s) with values to compare
	 * @param string|int|float|string[]|int[]|float[] $values Values to compare
	 * @return mixed
	 * @since 1.35 in IDatabase, moved to ISQLPlatform in 1.39
	 */
	public function buildLeast( $fields, $values );

	/**
	 * Build a condition comparing multiple values, for use with indexes that cover
	 * multiple fields, common when e.g. paging through results or doing batch operations.
	 *
	 * For example, you might be displaying a list of people ordered alphabetically by their last
	 * and first name, split across multiple pages. The first page of the results ended at Jane Doe.
	 * When building the query for the next page, you would use:
	 *
	 *     $queryBuilder->where( $db->buildComparison( '>', [ 'last' => 'Doe', 'first' => 'Jane' ] ) );
	 *
	 * This will return people whose last name follows Doe, or whose last name is Doe and first name
	 * follows Jane.
	 *
	 * Note that the order of keys in the associative array $conds is significant,
	 * and must match the order of fields used by the index.
	 *
	 * When comparing a single value, prefer using the expression builder:
	 *
	 *     $db->expr( 'key', '<=', $val )
	 *
	 *     // equivalent to:
	 *     $db->buildComparison( '<=', [ 'key' => $val ] )
	 *     'key <= ' . $db->addQuotes( $val )
	 *
	 * @param string $op Comparison operator, one of '>', '>=', '<', '<='
	 * @param non-empty-array<string,mixed> $conds Map of field names to their values to use in the comparison
	 * @return string SQL code
	 */
	public function buildComparison( string $op, array $conds ): string;

	/**
	 * Makes an encoded list of strings from an array
	 *
	 * These can be used to make conjunctions or disjunctions on SQL condition strings
	 * derived from an array ({@see IDatabase::select} $conds documentation).
	 *
	 * Example usage:
	 * @code
	 *     $sql = $db->makeList( [
	 *         'rev_page' => $id,
	 *         $db->makeList( [ 'rev_minor' => 1, 'rev_len < 500' ], $db::LIST_OR )
	 *     ], $db::LIST_AND );
	 * @endcode
	 * This would set $sql to "rev_page = '$id' AND (rev_minor = 1 OR rev_len < 500)"
	 *
	 * @param array $a Containing the data
	 * @param-taint $a escapes_sql - Note, this is also special-cased in MediaWikiSecurityCheckPlugin
	 * @param int $mode IDatabase class constant:
	 *    - IDatabase::LIST_COMMA: Comma separated, no field names
	 *    - IDatabase::LIST_AND:   ANDed WHERE clause (without the WHERE).
	 *    - IDatabase::LIST_OR:    ORed WHERE clause (without the WHERE)
	 *    - IDatabase::LIST_SET:   Comma separated with field names, like a SET clause
	 *    - IDatabase::LIST_NAMES: Comma separated field names
	 * @param-taint $mode none
	 * @throws DBError If an error occurs, {@see IDatabase::query}
	 * @return string
	 * @return-taint none
	 */
	public function makeList( array $a, $mode = self::LIST_COMMA );

	/**
	 * Build a "OR" condition with pairs from a two-dimensional array.
	 *
	 * The associative array should have integer keys relating to the $baseKey field.
	 * The nested array should have string keys for the $subKey field. The inner
	 * values are ignored, and are typically boolean true.
	 *
	 * Example usage:
	 * ```
	 *     $data = [
	 *         2 => [
	 *             'Foo' => true,
	 *             'Bar' => true,
	 *         ],
	 *         3 => [
	 *             'Quux' => true,
	 *         ],
	 *     ];
	 *     // (page_namespace = 2 AND page_title IN ('Foo','Bar'))
	 *     // OR (page_namespace = 3 AND page_title = 'Quux')
	 * ```
	 * @todo This is effectively specific to MediaWiki's LinkBatch.
	 * Consider deprecating or generalising slightly.
	 *
	 * @param array $data Nested array, must be non-empty
	 * @phan-param non-empty-array $data
	 * @param string $baseKey Field name to match the base-level keys to (eg 'pl_namespace')
	 * @param string $subKey Field name to match the sub-level keys to (eg 'pl_title')
	 * @return string SQL fragment
	 */
	public function makeWhereFrom2d( $data, $baseKey, $subKey );

	/**
	 * Given an array of condition arrays representing an OR list of AND lists,
	 * for example:
	 *
	 *   (A=1 AND B=2) OR (A=1 AND B=3)
	 *
	 * produce an SQL expression in which the conditions are factored:
	 *
	 *  (A=1 AND (B=2 OR B=3))
	 *
	 * We also use IN() to simplify further:
	 *
	 *  (A=1 AND (B IN (2,3))
	 *
	 * More compactly, in boolean algebra notation, a sum of products, e.g.
	 * AB + AC is factored to produce A(B+C). Factoring proceeds recursively
	 * to reduce expressions with any number of variables, for example
	 *   AEP + AEQ + AFP + AFQ = A(E(P+Q) + F(P+Q))
	 *
	 * The algorithm is simple and will not necessarily find the shortest
	 * possible expression. For the best results, fields should be given in a
	 * consistent order, and the fields with values likely to be shared should
	 * be leftmost in the associative arrays.
	 *
	 * @param array $condsArray An array of associative arrays. The associative
	 *   array keys represent field names, and the values represent the field
	 *   values to compare against.
	 * @return string SQL expression fragment
	 */
	public function factorConds( $condsArray );

	/**
	 * Build a concatenation list to feed into a SQL query
	 * @param string[] $stringList Raw SQL expression list; caller is responsible for escaping
	 * @return string
	 */
	public function buildConcat( $stringList );

	/**
	 * Construct a LIMIT query with optional offset
	 *
	 * The SQL should be adjusted so that only the first $limit rows
	 * are returned. If $offset is provided as well, then the first $offset
	 * rows should be discarded, and the next $limit rows should be returned.
	 * If the result of the query is not ordered, then the rows to be returned
	 * are theoretically arbitrary.
	 *
	 * $sql is expected to be a SELECT, if that makes a difference.
	 *
	 * @param string $sql SQL query we will append the limit too
	 * @param int $limit The SQL limit
	 * @param int|false $offset The SQL offset (default false)
	 * @return string
	 * @since 1.34 in IDatabase, moved to ISQLPlatform in 1.39
	 */
	public function limitResult( $sql, $limit, $offset = false );

	/**
	 * LIKE statement wrapper
	 *
	 * This takes a variable-length argument list with parts of pattern to match
	 * containing either string literals that will be escaped or tokens returned by
	 * {@link anyChar()} or {@link anyString()}.
	 * Alternatively, the function could be provided with an array of the aforementioned parameters.
	 *
	 * Example: $dbr->buildLike( 'My_page_title/', $dbr->anyString() ) returns
	 * a LIKE clause that searches for subpages of 'My page title'.
	 * Alternatively:
	 *   $pattern = [ 'My_page_title/', $dbr->anyString() ];
	 *   $query .= $dbr->buildLike( $pattern );
	 *
	 * @since 1.16 in IDatabase, moved to ISQLPlatform in 1.39
	 * @param string|LikeMatch|non-empty-array<string|LikeMatch> $param
	 * @param-taint $param escapes_sql
	 * @param string|LikeMatch ...$params
	 * @param-taint ...$params escapes_sql
	 * @return string Fully built LIKE statement
	 * @return-taint none
	 */
	public function buildLike( $param, ...$params );

	/**
	 * Returns a token for {@link buildLike()} that denotes a '_' to be used in a LIKE query
	 *
	 * @return LikeMatch
	 */
	public function anyChar();

	/**
	 * Returns a token for {@link buildLike()} that denotes a '%' to be used in a LIKE query
	 *
	 * @return LikeMatch
	 */
	public function anyString();

	/**
	 * Determine if the RDBMS supports ORDER BY and LIMIT for separate subqueries within UNION
	 *
	 * @return bool
	 */
	public function unionSupportsOrderAndLimit();

	/**
	 * Construct a UNION query
	 *
	 * This is used for providing overload point for other DB abstractions
	 * not compatible with the MySQL syntax.
	 *
	 * @internal callers outside of rdbms library should use UnionQueryBuilder instead.
	 *
	 * @param array $sqls SQL statements to combine
	 * @param bool $all Either {@link IDatabase::UNION_ALL} or {@link IDatabase::UNION_DISTINCT}
	 * @param array $options Query options
	 *
	 * @return string SQL fragment
	 */
	public function unionQueries( $sqls, $all, $options = [] );

	/**
	 * Convert a timestamp in one of the formats accepted by {@link ConvertibleTimestamp}
	 * to the format used for inserting into timestamp fields in this DBMS
	 *
	 * The result is unquoted, and needs to be passed through addQuotes()
	 * before it can be included in raw SQL.
	 *
	 * @param string|int $ts
	 *
	 * @return string
	 */
	public function timestamp( $ts = 0 );

	/**
	 * Convert a timestamp in one of the formats accepted by ConvertibleTimestamp
	 * to the format used for inserting into timestamp fields in this DBMS
	 *
	 * If NULL is input, it is passed through, allowing NULL values to be inserted
	 * into timestamp fields.
	 *
	 * The result is unquoted, and needs to be passed through addQuotes()
	 * before it can be included in raw SQL.
	 *
	 * @param string|int|null $ts
	 *
	 * @return string|null
	 */
	public function timestampOrNull( $ts = null );

	/**
	 * Find out when 'infinity' is. Most DBMSes support this. This is a special
	 * keyword for timestamps in PostgreSQL, and works with CHAR(14) as well
	 * because "i" sorts after all numbers.
	 *
	 * @return string
	 */
	public function getInfinity();

	/**
	 * Encode an expiry time into the DBMS dependent format
	 *
	 * @param string $expiry Timestamp for expiry, or the 'infinity' string
	 * @return string
	 */
	public function encodeExpiry( $expiry );

	/**
	 * Decode an expiry time into a DBMS independent format
	 *
	 * @param string $expiry DB timestamp field value for expiry
	 * @param int $format TS_* constant, defaults to TS_MW
	 * @return string
	 */
	public function decodeExpiry( $expiry, $format = TS_MW );

	/**
	 * Returns an SQL expression for a simple conditional
	 *
	 * This doesn't need to be overridden unless CASE isn't supported in the RDBMS.
	 *
	 * @param string|IExpression|array<string,?scalar|non-empty-array<int,?scalar>>|array<int,string|IExpression> $cond
	 *  SQL condition expression (yields a boolean)
	 * @param string $caseTrueExpression SQL expression to return when the condition is true
	 * @param string $caseFalseExpression SQL expression to return when the condition is false
	 * @return string SQL fragment
	 */
	public function conditional( $cond, $caseTrueExpression, $caseFalseExpression );

	/**
	 * Returns a SQL expression for simple string replacement (e.g. REPLACE() in mysql)
	 *
	 * @param string $orig Column to modify
	 * @param string $old Column to seek
	 * @param string $new Column to replace with
	 * @return string
	 */
	public function strreplace( $orig, $old, $new );

	/**
	 * Build a SUBSTRING function
	 *
	 * Behavior for non-ASCII values is undefined.
	 *
	 * @param string $input Field name
	 * @param int $startPosition Positive integer
	 * @param int|null $length Non-negative integer length or null for no limit
	 * @throws \InvalidArgumentException
	 * @return string SQL text
	 * @since 1.31 in IDatabase, moved to ISQLPlatform in 1.39
	 */
	public function buildSubString( $input, $startPosition, $length = null );

	/**
	 * @param string $field Field or column to cast
	 * @return string
	 * @since 1.28 in IDatabase, moved to ISQLPlatform in 1.39
	 */
	public function buildStringCast( $field );

	/**
	 * @param string $field Field or column to cast
	 * @return string
	 * @since 1.31 in IDatabase, moved to ISQLPlatform in 1.39
	 */
	public function buildIntegerCast( $field );

	/**
	 * Returns true if this database does an implicit order by when the column has an index
	 * For example: SELECT page_title FROM page LIMIT 1
	 *
	 * @return bool
	 */
	public function implicitOrderby();

	/**
	 * Make certain table names use their own database, schema, and table prefix
	 * when passed into SQL queries pre-escaped and without a qualified database name
	 *
	 * For example, "user" can be converted to "myschema.mydbname.user" for convenience.
	 * Appearances like `user`, somedb.user, somedb.someschema.user will used literally.
	 *
	 * Calling this twice will completely clear any old table aliases. Also, note that
	 * callers are responsible for making sure the schemas and databases actually exist.
	 *
	 * @param array[] $aliases Map of (unqualified name of table => (dbname, schema, prefix) map)
	 * @since 1.28 in IDatabase, moved to ISQLPlatform in 1.39
	 */
	public function setTableAliases( array $aliases );

	/**
	 * Return current table aliases.
	 *
	 * @internal only to be used inside rdbms library
	 */
	public function getTableAliases();

	/**
	 * Take the same arguments as IDatabase::select() and return the SQL it would use
	 *
	 * This can be useful for making UNION queries, where the SQL text of each query
	 * is needed. In general, however, callers outside of Database classes should just
	 * use select().
	 *
	 * @see IDatabase::select()
	 *
	 * @param string|array $tables Table reference(s), using the unqualified name of tables
	 *   or of the form "information_schema.<identifier>".
	 * @param-taint $tables exec_sql
	 * @param string|array $vars Field names
	 * @param-taint $vars exec_sql
	 * @phpcs:ignore Generic.Files.LineLength
	 * @param string|IExpression|array<string,?scalar|non-empty-array<int,?scalar>|RawSQLValue>|array<int,string|IExpression> $conds
	 *   Conditions
	 * @param-taint $conds exec_sql_numkey
	 * @param string $fname Caller function name @phan-mandatory-param
	 * @param-taint $fname exec_sql
	 * @param string|array $options Query options
	 * @param-taint $options none This is special-cased in MediaWikiSecurityCheckPlugin
	 * @param string|array $join_conds Join conditions
	 * @param-taint $join_conds none This is special-cased in MediaWikiSecurityCheckPlugin
	 * @return string SQL query string
	 * @return-taint onlysafefor_sql
	 */
	public function selectSQLText(
		$tables,
		$vars,
		$conds = '',
		$fname = __METHOD__,
		$options = [],
		$join_conds = []
	);

	/**
	 * Format a table name ready for use in constructing an SQL query
	 *
	 * This does two important things: it quotes the table names to clean them up,
	 * and it adds a table prefix if only given a table name with no quotes.
	 *
	 * All functions of this object which require a table name call this function
	 * themselves. Pass the canonical name to such functions. This is only needed
	 * when calling {@link query()} directly.
	 *
	 * The provided name should not qualify the database nor the schema, unless the name
	 * is of the form "information_schema.<identifier>". Unlike information_schema tables,
	 * regular tables can receive writes and are subject to configuration regarding table
	 * aliases, virtual domains, and LBFactory sharding. Callers needing to access remote
	 * databases should use appropriate connection factory methods.
	 *
	 * @note This function does not sanitize user input. It is not safe to use
	 *   this function to escape user input.
	 * @param string $name The unqualified name of a table (no quotes, db, schema, nor table
	 *   prefix), or a table name of the form "information_schema.<unquoted identifier>".
	 * @param string $format One of:
	 *   quoted - Automatically pass the table name through addIdentifierQuotes()
	 *            so that it can be used in a query.
	 *   raw - Do not add identifier quotes to the table name.
	 * @return string Qualified table name (includes any applicable prefix or foreign db/schema)
	 */
	public function tableName( string $name, $format = 'quoted' );

	/**
	 * Fetch a number of table names into a zero-indexed numerical array
	 *
	 * Much like {@link tableName()}, this is only needed when calling
	 * {@link query()} directly. You should prefer calling other
	 * methods, or using {@link SelectQueryBuilder}.
	 *
	 * Theoretical example (which really does not require raw SQL):
	 * ```
	 * [ $user, $watchlist ] = $dbr->tableNamesN( 'user', 'watchlist' );
	 * $sql = "SELECT wl_namespace,wl_title FROM $watchlist,$user
	 *         WHERE wl_user=user_id AND wl_user=$nameWithQuotes";
	 * ```
	 *
	 * @param string ...$tables
	 * @return array
	 * @deprecated Since 1.43; if you must format table names,
	 *  write several calls to {@link tableName}.
	 */
	public function tableNamesN( ...$tables );

	/**
	 * Build a GROUP_CONCAT or equivalent statement for a query.
	 *
	 * This is useful for combining a field for several rows into a single string.
	 * NULL values will not appear in the output, duplicated values will appear,
	 * and the resulting delimiter-separated values have no defined sort order.
	 * Code using the results may need to use the PHP unique() or sort() methods.
	 *
	 * @param string $delim Glue to bind the results together
	 * @param string|array $tables Table reference(s), using the unqualified name of tables
	 *   or of the form "information_schema.<identifier>". {@see select} for details.
	 * @param string $field Field name
	 * @phpcs:ignore Generic.Files.LineLength
	 * @param string|IExpression|array<string,?scalar|non-empty-array<int,?scalar>|RawSQLValue>|array<int,string|IExpression> $conds
	 *   Conditions
	 * @param string|array $join_conds Join conditions
	 * @return string SQL text
	 * @since 1.23
	 */
	public function buildGroupConcatField(
		$delim, $tables, $field, $conds = '', $join_conds = []
	);

	/**
	 * Equivalent to IDatabase::selectSQLText() except wraps the result in Subquery
	 *
	 * @see IDatabase::selectSQLText()
	 *
	 * @param string|array $tables Table reference(s), using the unqualified name of tables
	 *   or of the form "information_schema.<identifier>". {@see select} for details.
	 * @param string|array $vars Field names
	 * @param string|array $conds Conditions
	 * @param string $fname Caller function name @phan-mandatory-param
	 * @param string|array $options Query options
	 * @param string|array $join_conds Join conditions
	 * @return Subquery
	 * @since 1.31
	 */
	public function buildSelectSubquery(
		$tables,
		$vars,
		$conds = '',
		$fname = __METHOD__,
		$options = [],
		$join_conds = []
	);

	/**
	 * Build a reference to a column value from the conflicting proposed upsert() row.
	 *
	 * The reference comes in the form of an alias, function, or parenthesized SQL expression.
	 * It can be used in upsert() SET expressions to handle the merging of column values between
	 * each conflicting pair of existing and proposed rows. Such proposed rows are said to have
	 * been "excluded" from insertion in favor of updating the existing row.
	 *
	 * This is useful for multi-row upserts() since the proposed values cannot just be included
	 * as literals in the SET expressions.
	 *
	 * @see IDatabase::upsert()
	 *
	 * @param string $column Column name
	 * @return string SQL expression returning a scalar
	 * @since 1.39
	 */
	public function buildExcludedValue( $column );

	/**
	 * Set schema variables to be used when streaming commands from SQL files or stdin
	 *
	 * Variables appear as SQL comments and are substituted by their corresponding values
	 *
	 * @param array|null $vars Map of (variable => value) or null to use the defaults
	 */
	public function setSchemaVars( $vars );
}
