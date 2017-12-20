<?php

namespace Wikimedia\Rdbms;

use InvalidArgumentException;

/**
 * Database connection wrapper that prevents write operations.
 *
 * This should be used when returning a connection to the master database when a database
 * connection was requested for the DB_REPLICA index, which is typically the case in a
 * singe database setup. Failing on attempts to write to such a connection makes it
 * more likely to discover accidental writes to a replica in a single database development
 * or testing environment, and especially unit tests.
 *
 * @note The wrapper provides protection against writes on a "best effort" basis. It is not
 * guaranteed to prevent all writes under all conditions.
 *
 * @ingroup Database
 * @since 1.31
 */
class DBNoWriteWrapper extends DBConnectionProxy {

	/**
	 * @param string $verb
	 * @param string $caller
	 * @throws DBError
	 */
	private function throwNoWrite( $verb, $caller ) {
		throw new DBError(
			$this,
			"Write operation ($verb) is not allowed on this database connection! Caused by $caller."
		);
	}

	/**
	 * @todo move to a trait, so we don't have to duplicate the code from the Database here.
	 *
	 * @param string $sql
	 * @return bool
	 */
	private function isWriteQuery( $sql ) {
		return !preg_match(
			'/^(?:SELECT|BEGIN|ROLLBACK|COMMIT|SET|SHOW|EXPLAIN|\(SELECT)\b/i', $sql );
	}

	/**
	 * @todo move to a trait, so we don't have to duplicate the code from the Database here.
	 *
	 * @param string $sql
	 * @return string|null
	 */
	private function getQueryVerb( $sql ) {
		return preg_match( '/^\s*([a-z]+)/i', $sql, $m ) ? strtoupper( $m[1] ) : null;
	}

	/**
	 * @param string $sql
	 * @param string $fname
	 * @param bool $tempIgnore
	 * @throws DBError
	 * @return bool|mixed|IResultWrapper
	 */
	public function query( $sql, $fname = __METHOD__, $tempIgnore = false ) {
		if ( $this->isWriteQuery( $sql ) ) {
			$this->throwNoWrite( $this->getQueryVerb( $sql ), $fname );
		}

		return parent::query( $sql, $fname, $tempIgnore );
	}

	/**
	 * @param string $table
	 * @param array|string $conds
	 * @param string $fname
	 * @throws DBError
	 */
	public function delete( $table, $conds, $fname = __METHOD__ ) {
		$this->throwNoWrite( __FUNCTION__, $fname );
	}

	/**
	 * @param string $table
	 * @param array $a
	 * @param string $fname
	 * @param array $options
	 * @throws DBError
	 */
	public function insert( $table, $a, $fname = __METHOD__, $options = [ ] ) {
		$this->throwNoWrite( __FUNCTION__, $fname );
	}

	/**
	 * @param string $table
	 * @param array $values
	 * @param array $conds
	 * @param string $fname
	 * @param array $options
	 * @throws DBError
	 */
	public function update( $table, $values, $conds, $fname = __METHOD__, $options = [ ] ) {
		$this->throwNoWrite( __FUNCTION__, $fname );
	}

	/**
	 * @param string $table
	 * @param array $uniqueIndexes
	 * @param array $rows
	 * @param string $fname
	 * @throws DBError
	 */
	public function replace( $table, $uniqueIndexes, $rows, $fname = __METHOD__ ) {
		$this->throwNoWrite( __FUNCTION__, $fname );
	}

	/**
	 * @param string $table
	 * @param array $rows
	 * @param array $uniqueIndexes
	 * @param array $set
	 * @param string $fname
	 * @throws DBError
	 */
	public function upsert(
		$table,
		array $rows,
		array $uniqueIndexes,
		array $set,
		$fname = __METHOD__
	) {
		$this->throwNoWrite( __FUNCTION__, $fname );
	}

	/**
	 * @param string $delTable
	 * @param string $joinTable
	 * @param string $delVar
	 * @param string $joinVar
	 * @param array $conds
	 * @param string $fname
	 * @throws DBError
	 */
	public function deleteJoin(
		$delTable,
		$joinTable,
		$delVar,
		$joinVar,
		$conds,
		$fname = __METHOD__
	) {
		$this->throwNoWrite( __FUNCTION__, $fname );
	}

	/**
	 * @param string $destTable
	 * @param array|string $srcTable
	 * @param array $varMap
	 * @param array $conds
	 * @param string $fname
	 * @param array $insertOptions
	 * @param array $selectOptions
	 * @param array $selectJoinConds
	 * @throws DBError
	 */
	public function insertSelect(
		$destTable,
		$srcTable,
		$varMap,
		$conds,
		$fname = __METHOD__,
		$insertOptions = [ ],
		$selectOptions = [ ],
		$selectJoinConds = [ ]
	) {
		$this->throwNoWrite( __FUNCTION__, $fname );
	}

}
