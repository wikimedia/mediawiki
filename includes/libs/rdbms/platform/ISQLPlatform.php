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

/**
 * Interface for query language.
 * Note: This is for simple SQL operations, use QueryBuilder for building full queries.
 *
 * Methods of this interface should be only used by rdbms library.
 * @since 1.39
 */
interface ISQLPlatform {

	/** @var int Combine list with comma delimiters */
	public const LIST_COMMA = 0;
	/** @var int Combine list with AND clauses */
	public const LIST_AND = 1;
	/** @var int Convert map into a SET clause */
	public const LIST_SET = 2;
	/** @var int Treat as field name and do not apply value escaping */
	public const LIST_NAMES = 3;
	/** @var int Combine list with OR clauses */
	public const LIST_OR = 4;

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
	 * @return string
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
	 * @param int $mode IDatabase class constant:
	 *    - IDatabase::LIST_COMMA: Comma separated, no field names
	 *    - IDatabase::LIST_AND:   ANDed WHERE clause (without the WHERE).
	 *    - IDatabase::LIST_OR:    ORed WHERE clause (without the WHERE)
	 *    - IDatabase::LIST_SET:   Comma separated with field names, like a SET clause
	 *    - IDatabase::LIST_NAMES: Comma separated field names
	 * @throws DBError If an error occurs, {@see IDatabase::query}
	 * @return string
	 */
	public function makeList( array $a, $mode = self::LIST_COMMA );

	/**
	 * Build a partial where clause from a 2-d array such as used for LinkBatch.
	 *
	 * The keys on each level may be either integers or strings, however it's
	 * assumed that $baseKey is probably an integer-typed column (i.e. integer
	 * keys are unquoted in the SQL) and $subKey is string-typed (i.e. integer
	 * keys are quoted as strings in the SQL).
	 *
	 * @todo Does this actually belong in the library? It seems overly MW-specific.
	 *
	 * @param array $data Organized as 2-d
	 *    [ baseKeyVal => [ subKeyVal => [ignored], ... ], ... ]
	 * @param string $baseKey Field name to match the base-level keys to (eg 'pl_namespace')
	 * @param string $subKey Field name to match the sub-level keys to (eg 'pl_title')
	 * @return string|bool SQL fragment, or false if no items in array
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
}
