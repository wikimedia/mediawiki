<?php

namespace Wikimedia\Rdbms;

use Wikimedia\Rdbms\Platform\ISQLPlatform;

/**
 * A query builder for UNION queries takes SelectQueryBuilder objects
 *
 * Any particular query builder object should only be used for a single database query,
 * and not be reused afterwards.
 *
 * @since 1.41
 * @ingroup Database
 */
class UnionQueryBuilder {
	/** sort the results in ascending order */
	public const SORT_ASC = 'ASC';

	/** sort the results in descending order */
	public const SORT_DESC = 'DESC';

	/**
	 * @var SelectQueryBuilder[]
	 */
	private $sqbs = [];

	private IDatabase $db;

	/** @var bool */
	private $all = IReadableDatabase::UNION_DISTINCT;

	/** @var array */
	private $options = [];

	/**
	 * @var string The caller (function name) to be passed to IDatabase::query()
	 */
	private $caller = __CLASS__;

	/**
	 * To create a UnionQueryBuilder instance, use `$db->newUnionQueryBuilder()` instead.
	 */
	public function __construct( IDatabase $db ) {
		$this->db = $db;
	}

	/**
	 * Add a select query builder object to the list of union
	 *
	 * @return $this
	 */
	public function add( SelectQueryBuilder $selectQueryBuilder ) {
		$this->sqbs[] = $selectQueryBuilder;
		return $this;
	}

	/**
	 * Enable UNION_ALL option, the default is UNION_DISTINCT
	 *
	 * @return $this
	 */
	public function all() {
		$this->all = $this->db::UNION_ALL;
		return $this;
	}

	/**
	 * Set the query limit. Return at most this many rows. The rows are sorted
	 * and then the first rows are taken until the limit is reached. Limit
	 * is applied to a result set after offset.
	 *
	 * If the query builder already has a limit, the old limit will be discarded.
	 * This would be also ignored if the DB does not support limit in union queries.
	 *
	 * @param int $limit
	 * @return $this
	 */
	public function limit( $limit ) {
		if ( !$this->db->unionSupportsOrderAndLimit() ) {
			return $this;
		}
		$this->options['LIMIT'] = $limit;
		return $this;
	}

	/**
	 * Set the offset. Skip this many rows at the start of the result set. Offset
	 * with limit() can theoretically be used for paging through a result set,
	 * but this is discouraged for performance reasons.
	 *
	 * If the query builder already has an offset, the old offset will be discarded.
	 * This would be also ignored if the DB does not support offset in union queries.
	 *
	 * @param int $offset
	 * @return $this
	 */
	public function offset( $offset ) {
		if ( !$this->db->unionSupportsOrderAndLimit() ) {
			return $this;
		}
		$this->options['OFFSET'] = $offset;
		return $this;
	}

	/**
	 * Set the ORDER BY clause. If it has already been set, append the
	 * additional fields to it.
	 *
	 * This would be ignored if the DB does not support order by in union queries.
	 *
	 * @param string[]|string $fields The field or list of fields to order by.
	 * @param-taint $fields exec_sql
	 * @param string|null $direction self::SORT_ASC or self::SORT_DESC.
	 * If this is null then $fields is assumed to optionally contain ASC or DESC
	 * after each field name.
	 * @param-taint $direction exec_sql
	 * @return $this
	 */
	public function orderBy( $fields, $direction = null ) {
		if ( !$this->db->unionSupportsOrderAndLimit() ) {
			return $this;
		}
		if ( $direction === null ) {
			$this->mergeOption( 'ORDER BY', $fields );
		} elseif ( is_array( $fields ) ) {
			$fieldsWithDirection = [];
			foreach ( $fields as $field ) {
				$fieldsWithDirection[] = "$field $direction";
			}
			$this->mergeOption( 'ORDER BY', $fieldsWithDirection );
		} else {
			$this->mergeOption( 'ORDER BY', "$fields $direction" );
		}
		return $this;
	}

	/**
	 * Add a value to an option which may be not set or a string or array.
	 *
	 * @param string $name
	 * @param string|string[] $newArrayOrValue
	 */
	private function mergeOption( $name, $newArrayOrValue ) {
		$value = isset( $this->options[$name] )
			? (array)$this->options[$name] : [];
		if ( is_array( $newArrayOrValue ) ) {
			$value = array_merge( $value, $newArrayOrValue );
		} else {
			$value[] = $newArrayOrValue;
		}
		$this->options[$name] = $value;
	}

	/**
	 * Set the method name to be included in an SQL comment.
	 *
	 * @param string $fname
	 * @param-taint $fname exec_sql
	 * @return $this
	 */
	public function caller( $fname ) {
		$this->caller = $fname;
		return $this;
	}

	/**
	 * Run the constructed UNION query and return all results.
	 *
	 * @return IResultWrapper
	 */
	public function fetchResultSet() {
		$query = new Query( $this->getSQL(), ISQLPlatform::QUERY_CHANGE_NONE, 'SELECT' );
		return $this->db->query( $query, $this->caller );
	}

	/**
	 * Run the constructed UNION query, and return a single field extracted
	 * from the first result row. If there were no result rows, false is
	 * returned. This may only be called when only one field has been added to
	 * the constituent queries.
	 *
	 * @since 1.42
	 * @return mixed
	 * @return-taint tainted
	 */
	public function fetchField() {
		$this->limit( 1 );
		foreach ( $this->fetchResultSet() as $row ) {
			$row = (array)$row;
			if ( count( $row ) !== 1 ) {
				throw new \UnexpectedValueException(
					__METHOD__ . ' expects the query to have only one field' );
			}
			return $row[ array_key_first( $row ) ];
		}
		return false;
	}

	/**
	 * Run the constructed UNION query, and extract a single field from each
	 * result row, returning an array containing all the values. This may only
	 * be called when only one field has been added to the constituent queries.
	 *
	 * @since 1.42
	 * @return array
	 * @return-taint tainted
	 */
	public function fetchFieldValues() {
		$values = [];
		foreach ( $this->fetchResultSet() as $row ) {
			$row = (array)$row;
			if ( count( $row ) !== 1 ) {
				throw new \UnexpectedValueException(
					__METHOD__ . ' expects the query to have only one field' );
			}
			$values[] = $row[ array_key_first( $row ) ];
		}
		return $values;
	}

	/**
	 * Run the constructed UNION query, and return the first result row. If
	 * there were no results, return false.
	 *
	 * @since 1.42
	 * @return \stdClass|false
	 * @return-taint tainted
	 */
	public function fetchRow() {
		$this->limit( 1 );
		foreach ( $this->fetchResultSet() as $row ) {
			return $row;
		}
		return false;
	}

	/**
	 * Get the SQL query string which would be used by fetchResultSet().
	 *
	 * @since 1.42
	 * @return string
	 */
	public function getSQL() {
		$sqls = [];
		foreach ( $this->sqbs as $sqb ) {
			$sqls[] = $sqb->getSQL();
		}
		return $this->db->unionQueries( $sqls, $this->all, $this->options );
	}

}
