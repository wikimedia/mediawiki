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
	/**
	 * @var SelectQueryBuilder[]
	 */
	private $sqbs = [];

	/** @var IDatabase */
	private $db;

	private $all = IReadableDatabase::UNION_DISTINCT;

	/**
	 * @var string The caller (function name) to be passed to IDatabase::query()
	 */
	private $caller = __CLASS__;

	/**
	 * To create a UnionQueryBuilder instance, use `$db->newUnionQueryBuilder()` instead.
	 *
	 * @param IDatabase $db
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
	 * Set the method name to be included in an SQL comment.
	 *
	 * @param string $fname
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
		$sqls = [];
		$tables = [];
		foreach ( $this->sqbs as $sqb ) {
			$sqls[] = $sqb->getSQL();
			$tables = array_merge( $tables, $sqb->getQueryInfo()['tables'] );
		}
		$sql = $this->db->unionQueries( $sqls, $this->all );
		$query = new Query( $sql, ISQLPlatform::QUERY_CHANGE_NONE, 'SELECT', $tables );
		return $this->db->query( $query, $this->caller );
	}
}
