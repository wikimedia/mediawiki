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

use evseevnn\Cassandra\Database;
use evseevnn\Cassandra\Enum\ConsistencyEnum;
use evseevnn\Cassandra\Exception\ConnectionException;
use evseevnn\Cassandra\Exception\QueryException;
use evseevnn\Cassandra\Exception\CassandraException;

/**
 * Cassandra cache storage
 *
 * @note: with several data-centers, it may be useful to wrap this with
 * ReplicatedBagOStuff using a designated DC for READ_LATEST queries
 *
 * @ingroup Cache
 * @since 1.26
 */
abstract class CassandraBagOStuff extends BagOStuff {
	/** @var Database */
	protected $db;
	/** @var ConnectionException */
	protected $lastConnError;
	/** @var integer UNIX timestamp */
	protected $lastConnErrorTime;

	/**
	 * @param array $params Additional params include:
	 *   - cassandraNodes    : $nodes argument to Database::__construct()
	 *   - cassandraKeyspace : $keySpace argument to Database::__construct()
	 *   - cassandraOptions  : $options argument to Database::__construct()
	 * See https://github.com/evseevnn/php-cassandra-binary for more documentation.
	 * @throws Exception
	 */
	function __construct( array $params ) {
		parent::__construct( $params );

		if ( !class_exists( 'evseevnn\Cassandra\Database' ) ) {
			throw new Exception( "Missing cassandra binding; " .
				"see https://github.com/evseevnn/php-cassandra-binary" );
		}

		$this->db = new Database(
			$params['cassandraNodes'],
			$params['cassandraKeyspace'],
			isset( $params['cassandraOptions'] ) ? $params['cassandraOptions'] : array()
		);
	}

	public function get( $key, &$casToken = null, $flags = 0 ) {
		$res = array();
		try {
			$this->connect();
			$res = $this->db->query(
				'SELECT value FROM "objectstash" WHERE "key" = :key LIMIT 1',
				array( 'key' => $key ),
				( $flags & self::READ_LATEST )
					? ConsistencyEnum::CONSISTENCY_LOCAL_QUORUM
					: ConsistencyEnum::CONSISTENCY_LOCAL_ONE
			);
		} catch ( Exception $e ) {
			$this->handleException( $e );
		}

		return isset( $res[0] ) ? $res[0]['value'] : false;
	}

	public function set( $key, $value, $ttl = 0 ) {
		try {
			$this->connect();
			$this->db->query(
				'INSERT INTO "objectstash" (key,value) VALUES (:key, :value)' .
					( $ttl ? ' USING TTL :ttl' : '' ),
				array( 'key' => $key, 'value' => $value, 'ttl' => $ttl ),
				ConsistencyEnum::CONSISTENCY_LOCAL_QUORUM
			);
			return true;
		} catch ( Exception $e ) {
			$this->handleException( $e );
		}

		return false;
	}

	public function delete( $key ) {
		try {
			$this->connect();
			$this->db->query(
				'DELETE FROM "objectstash" WHERE "key" = :key',
				array( 'key' => $key ),
				ConsistencyEnum::CONSISTENCY_LOCAL_QUORUM
			);
			return true;
		} catch ( Exception $e ) {
			$this->handleException( $e );
		}

		return false;
	}

	/**
	 * @param Exception $e
	 * @throws Exception
	 */
	protected function handleException( Exception $e ) {
		if ( $e instanceof ConnectionException ) {
			$this->lastError = self::ERR_UNREACHABLE;
		} elseif ( $e instanceof QueryException ) {
			$this->lastError = self::ERR_NO_RESPONSE;
		} elseif ( $e instanceof CassandraException ) {
			$this->lastError = self::ERR_UNEXPECTED;
		} else {
			throw $e; // not Cassandra?
		}
	}

	/**
	 * Try to connect to Cassandra, remebering recent failures
	 *
	 * @throws ConnectionException
	 */
	protected function connect() {
		if ( $this->lastConnError && ( time() - $this->lastConnErrorTime ) < 5 ) {
			throw $this->lastConnError;
		}
		try {
			// @note: library already de-duplicates connect()
			$this->db->connect();
			$this->lastConnError = null;
			$this->lastConnErrorTime = null;
		} catch ( ConnectionException $e ) {
			$this->lastConnError = $e;
			$this->lastConnErrorTime = time();
			throw $e;
		}
	}
}
