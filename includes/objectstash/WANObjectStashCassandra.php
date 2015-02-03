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
 * @ingroup Stash
 * @author Aaron Schulz
 */

/**
 * @defgroup Stash Stash
 */

/**
 * Multi-datacenter aware stashing interface using Cassandra
 *
 * All read/writes use LOCAL_ONE consistency, to avoid cross-DC traffic
 *
 * @ingroup Cache
 * @since 1.25
 */
abstract class WANObjectStashCassandra extends WANObectStash {
	/** Possible values for getLastError() */
	const ERR_NONE = 0; // no error
	const ERR_NO_RESPONSE = 1; // no response
	const ERR_UNREACHABLE = 2; // can't connect
	const ERR_UNEXPECTED = 3; // response gave some error

	/** @var evseevnn\Cassandra\Database */
	protected $db;
	/** @var ConnectionException */
	protected $lastConnError;
	/** @var integer UNIX timestamp */
	protected $lastConnErrorTime;
	/** @var integer ERR_* constant */
	protected $lastError = self::ERR_NONE;

	/**
	 * @param array $params
	 */
	function __construct( array $params ) {
		if ( !class_exists( 'evseevnn\Cassandra\Database' ) ) {
			throw new Exception( "Missing cassandra binding; " .
				"see https://github.com/evseevnn/php-cassandra-binary" );
		}

		$this->db = new evseevnn\Cassandra\Database(
			$params['cassandraConfig']['nodes'],
			$params['cassandraConfig']['keyspace']
		);
	}

	/**
	 * Fetch the value of a key from the stash
	 *
	 * @param string $key Cache key
	 * @return mixed Returns false on failure
	 */
	public function get( $key ) {
		$res = array();
		try {
			$this->connect();
			$res = $this->db->query(
				'SELECT value FROM "objectstash" WHERE "key" = :key LIMIT 1',
				array( 'key' => $key ),
				ConsistencyEnum::CONSISTENCY_LOCAL_ONE
			);
		} catch ( evseevnn\Cassandra\ConnectionException $e ) {
			$this->lastError = self::ERR_UNREACHABLE;
		} catch ( evseevnn\Cassandra\QueryException $e ) {
			$this->lastError = self::ERR_NO_RESPONSE;
		} catch ( evseevnn\Cassandra\CassandraException $e ) {
			$this->lastError = self::ERR_UNEXPECTED;
		}

		return isset( $res[0] ) ? $res[0]['value'] : false;
	}

	/**
	 * Set the value of a key from stash
	 *
	 * @param string $key Cache key
	 * @param mixed $value
	 * @param integer $ttl Seconds to live [0=forever]
	 * @return bool Success
	 */
	public function set( $key, $value, $ttl = 0 ) {
		try {
			$this->connect();
			$res = $this->db->query(
				'INSERT INTO "objectstash" (key,value) VALUES (:key, :value)',
				array( 'key' => $key, 'value' => $value ),
				ConsistencyEnum::CONSISTENCY_LOCAL_ONE
			);
			return true;
		} catch ( evseevnn\Cassandra\ConnectionException $e ) {
			$this->lastError = self::ERR_UNREACHABLE;
		} catch ( evseevnn\Cassandra\QueryException $e ) {
			$this->lastError = self::ERR_NO_RESPONSE;
		} catch ( evseevnn\Cassandra\CassandraException $e ) {
			$this->lastError = self::ERR_UNEXPECTED;
		}

		return false;
	}

	/**
	 * Delete the value of a key from stash
	 *
	 * @param string $key Cache key
	 * @return bool True if the item was deleted or not found, false on failure
	 */
	public function delete( $key ) {
		try {
			$this->connect();
			$this->db->query(
				'DELETE FROM "objectstash" WHERE "key" = :key',
				array( 'key' => $key ),
				ConsistencyEnum::CONSISTENCY_LOCAL_ONE
			);
			return true;
		} catch ( evseevnn\Cassandra\ConnectionException $e ) {
			$this->lastError = self::ERR_UNREACHABLE;
		} catch ( evseevnn\Cassandra\QueryException $e ) {
			$this->lastError = self::ERR_NO_RESPONSE;
		} catch ( evseevnn\Cassandra\CassandraException $e ) {
			$this->lastError = self::ERR_UNEXPECTED;
		}

		return false;
	}

	/**
	 * Try to connect to Cassandra, remebering recent failures
	 *
	 * @throws evseevnn\Cassandra\ConnectionException
	 */
	protected function connect() {
		if ( $this->lastConnError && ( time() - $this->lastConnErrorTime ) < 5 ) {
			throw $this->lastConnError;
		}
		try {
			$this->db->connect();
			$this->lastConnError = null;
			$this->lastConnErrorTime = null;
		} catch ( evseevnn\Cassandra\ConnectionException $e ) {
			$this->lastConnError = $e;
			$this->lastConnErrorTime = time();
			throw $e;
		}
	}

	/**
	 * Get the "last error" registered; clearLastError() should be called manually
	 * @return int ERR_* constant for the "last error" registry
	 */
	public function getLastError();

	/**
	 * Clear the "last error" registry
	 */
	public function clearLastError();
}
