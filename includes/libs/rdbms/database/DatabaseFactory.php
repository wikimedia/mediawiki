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
namespace Wikimedia\Rdbms;

use InvalidArgumentException;
use Psr\Log\NullLogger;
use Throwable;
use Wikimedia\ObjectCache\HashBagOStuff;
use Wikimedia\RequestTimeout\CriticalSectionProvider;
use Wikimedia\Telemetry\NoopTracer;
use Wikimedia\Telemetry\TracerInterface;

/**
 * Constructs Database objects
 *
 * @since 1.39
 * @ingroup Database
 */
class DatabaseFactory {
	/** @var string Agent name for query profiling */
	private $agent;
	/** @var callable Deprecation logger */
	private $deprecationLogger;
	/**
	 * @var callable|null An optional callback that returns a ScopedCallback instance,
	 * meant to profile the actual query execution in {@see Database::doQuery}
	 */
	private $profiler;
	/** @var TracerInterface */
	private $tracer;
	/** @var CriticalSectionProvider|null */
	private $csProvider;
	/** @var bool Whether this PHP instance is for a CLI script */
	private $cliMode;
	/** @var bool Log SQL queries in debug toolbar if set to true */
	private $debugSql;

	public function __construct( array $params = [] ) {
		$this->agent = $params['agent'] ?? '';
		$this->deprecationLogger = $params['deprecationLogger'] ?? static function ( $msg ) {
			trigger_error( $msg, E_USER_DEPRECATED );
		};
		$this->csProvider = $params['criticalSectionProvider'] ?? null;
		$this->profiler = $params['profiler'] ?? null;
		$this->tracer = $params['tracer'] ?? new NoopTracer();
		$this->cliMode = $params['cliMode'] ?? ( PHP_SAPI === 'cli' || PHP_SAPI === 'phpdbg' );
		$this->debugSql = $params['debugSql'] ?? false;
	}

	/**
	 * Construct a Database subclass instance given a database type and parameters
	 *
	 * This also connects to the database immediately upon object construction
	 *
	 * @param string $type A possible DB type (sqlite, mysql, postgres,...)
	 * @param array $params Parameter map with keys:
	 *   - host : The hostname of the DB server
	 *   - user : The name of the database user the client operates under
	 *   - password : The password for the database user
	 *   - dbname : The name of the database to use where queries do not specify one.
	 *      The database must exist or an error might be thrown. Setting this to the empty string
	 *      will avoid any such errors and make the handle have no implicit database scope. This is
	 *      useful for queries like SHOW STATUS, CREATE DATABASE, or DROP DATABASE. Note that a
	 *      "database" in Postgres is rougly equivalent to an entire MySQL server. This the domain
	 *      in which user names and such are defined, e.g. users are database-specific in Postgres.
	 *   - schema : The database schema to use (if supported). A "schema" in Postgres is roughly
	 *      equivalent to a "database" in MySQL. Note that MySQL and SQLite do not use schemas.
	 *   - tablePrefix : Optional table prefix that is implicitly added on to all table names
	 *      recognized in queries. This can be used in place of schemas for handle site farms.
	 *   - flags : Optional bit field of DBO_* constants that define connection, protocol,
	 *      buffering, and transaction behavior. It is STRONGLY adviced to leave the DBO_DEFAULT
	 *      flag in place UNLESS this this database simply acts as a key/value store.
	 *   - ssl : Whether to use TLS connections.
	 *   - strictWarnings: Whether to check for warnings and throw an exception if an unacceptable
	 *       warning is found.
	 *   - driver: Optional name of a specific DB client driver. For MySQL, there is only the
	 *      'mysqli' driver; the old one 'mysql' has been removed.
	 *   - variables: Optional map of session variables to set after connecting. This can be
	 *      used to adjust lock timeouts or encoding modes and the like.
	 *   - topologyRole: Optional IDatabase::ROLE_* constant for the server.
	 *   - lbInfo: Optional map of field/values for the managing load balancer instance.
	 *      The "master" and "replica" fields are used to flag the replication role of this
	 *      database server and whether methods like getLag() should actually issue queries.
	 *   - connectTimeout: Optional timeout, in seconds, for connection attempts.
	 *   - receiveTimeout: Optional timeout, in seconds, for receiving query results.
	 *   - logger: Optional PSR-3 logger interface instance.
	 *   - tracer: Optional TracerInterface instance.
	 *   - profiler : Optional callback that takes a section name argument and returns
	 *      a ScopedCallback instance that ends the profile section in its destructor.
	 *      These will be called in query(), using a simplified version of the SQL that
	 *      also includes the agent as a SQL comment.
	 *   - trxProfiler: Optional TransactionProfiler instance.
	 *   - errorLogger: Optional callback that takes an Exception and logs it.
	 *   - deprecationLogger: Optional callback that takes a string and logs it.
	 *   - cliMode: Whether to consider the execution context that of a CLI script.
	 *   - agent: Optional name used to identify the end-user in query profiling/logging.
	 *   - serverName: Optional human-readable server name
	 *   - srvCache: Optional BagOStuff instance to an APC-style cache.
	 *   - nonNativeInsertSelectBatchSize: Optional batch size for non-native INSERT SELECT.
	 * @param int $connect One of the class constants (NEW_CONNECTED, NEW_UNCONNECTED) [optional]
	 * @return Database|null If the database driver or extension cannot be found
	 * @throws InvalidArgumentException If the database driver or extension cannot be found
	 */
	public function create( $type, $params = [], $connect = Database::NEW_CONNECTED ) {
		$class = $this->getClass( $type, $params['driver'] ?? null );

		if ( class_exists( $class ) && is_subclass_of( $class, IDatabase::class ) ) {
			$params += [
				// Default configuration
				'host' => null,
				'user' => null,
				'password' => null,
				'dbname' => null,
				'schema' => null,
				'tablePrefix' => '',
				'variables' => [],
				'lbInfo' => [],
				'serverName' => null,
				'topologyRole' => null,
				// Objects and callbacks
				'srvCache' => $params['srvCache'] ?? new HashBagOStuff(),
				'trxProfiler' => $params['trxProfiler'] ?? new TransactionProfiler(),
				'logger' => $params['logger'] ?? new NullLogger(),
				'errorLogger' => $params['errorLogger'] ?? static function ( Throwable $e ) {
					trigger_error( get_class( $e ) . ': ' . $e->getMessage(), E_USER_WARNING );
				},
			];

			$params['flags'] ??= 0;
			if ( $this->debugSql ) {
				$params['flags'] |= DBO_DEBUG;
			}

			$overrides = [
				'flags' => $this->initConnectionFlags( $params['flags'] ),
				'cliMode' => $this->cliMode,
				'agent' => $this->agent,
				'profiler' => $this->profiler,
				'deprecationLogger' => $this->deprecationLogger,
				'criticalSectionProvider' => $this->csProvider,
				'tracer' => $this->tracer,
			];

			/** @var Database $conn */
			$conn = new $class( array_merge( $params, $overrides ) );
			if ( $connect === Database::NEW_CONNECTED ) {
				$conn->initConnection();
			}
		} else {
			$conn = null;
		}

		return $conn;
	}

	/**
	 * @param string $dbType A possible DB type (sqlite, mysql, postgres,...)
	 * @param string|null $driver Optional name of a specific DB client driver
	 * @return array Map of (Database::ATTR_* constant => value) for all such constants
	 * @throws DBUnexpectedError
	 */
	public function attributesFromType( $dbType, $driver = null ) {
		static $defaults = [
			Database::ATTR_DB_IS_FILE => false,
			Database::ATTR_DB_LEVEL_LOCKING => false,
			Database::ATTR_SCHEMAS_AS_TABLE_GROUPS => false
		];

		$class = $this->getClass( $dbType, $driver );
		if ( class_exists( $class ) ) {
			return $class::getAttributes() + $defaults;
		} else {
			throw new DBUnexpectedError( null, "$dbType is not a supported database type." );
		}
	}

	/**
	 * @param string $dbType A possible DB type (sqlite, mysql, postgres,...)
	 * @param string|null $driver Optional name of a specific DB client driver
	 * @return class-string<Database> Database subclass name to use
	 * @throws InvalidArgumentException
	 */
	protected function getClass( $dbType, $driver = null ) {
		// For database types with built-in support, the below maps type to IDatabase
		// implementations. For types with multiple driver implementations (PHP extensions),
		// an array can be used, keyed by extension name. In case of an array, the
		// optional 'driver' parameter can be used to force a specific driver. Otherwise,
		// we auto-detect the first available driver. For types without built-in support,
		// a class named "Database<Type>" is used, eg. DatabaseFoo for type 'foo'.
		static $builtinTypes = [
			'mysql' => [ 'mysqli' => DatabaseMySQL::class ],
			'sqlite' => DatabaseSqlite::class,
			'postgres' => DatabasePostgres::class,
		];

		$dbType = strtolower( $dbType );

		if ( !isset( $builtinTypes[$dbType] ) ) {
			// Not a built in type, assume standard naming scheme
			return 'Database' . ucfirst( $dbType );
		}

		$class = false;
		$possibleDrivers = $builtinTypes[$dbType];
		if ( is_string( $possibleDrivers ) ) {
			$class = $possibleDrivers;
		} elseif ( (string)$driver !== '' ) {
			if ( !isset( $possibleDrivers[$driver] ) ) {
				throw new InvalidArgumentException( __METHOD__ .
					" type '$dbType' does not support driver '{$driver}'" );
			}

			$class = $possibleDrivers[$driver];
		} else {
			foreach ( $possibleDrivers as $posDriver => $possibleClass ) {
				if ( extension_loaded( $posDriver ) ) {
					$class = $possibleClass;
					break;
				}
			}
		}

		if ( $class === false ) {
			throw new InvalidArgumentException( __METHOD__ .
				" no viable database extension found for type '$dbType'" );
		}

		return $class;
	}

	/**
	 * @see IDatabase::DBO_DEFAULT
	 * @param int $flags Bit field of IDatabase::DBO_* constants from configuration
	 * @return int Bit field of IDatabase::DBO_* constants to use with Database::factory()
	 */
	private function initConnectionFlags( int $flags ) {
		if ( self::fieldHasBit( $flags, IDatabase::DBO_DEFAULT ) ) {
			// Server is configured to participate in transaction rounds in non-CLI mode
			if ( $this->cliMode ) {
				$flags &= ~IDatabase::DBO_TRX;
			} else {
				$flags |= IDatabase::DBO_TRX;
			}
		}
		return $flags;
	}

	/**
	 * @param int $flags A bitfield of flags
	 * @param int $bit Bit flag constant
	 * @return bool Whether the bit field has the specified bit flag set
	 */
	private function fieldHasBit( int $flags, int $bit ) {
		return ( ( $flags & $bit ) === $bit );
	}
}
