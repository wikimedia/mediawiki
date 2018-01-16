<?php

namespace MediaWiki\Storage;

use IExpiringStore;
use LogicException;
use Psr\Log\LoggerInterface;
use WANObjectCache;
use Wikimedia\Assert\Assert;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\LoadBalancer;

/**
 * @author Addshore
 * @since 1.31
 */
class NameTableSqlStore {

	/**
	 * @var LoadBalancer
	 */
	private $loadBalancer;

	/**
	 * @var WANObjectCache
	 */
	private $cache;

	/**
	 * @var LoggerInterface
	 */
	private $logger;

	/**
	 * @var string[]
	 */
	private $tableCache = null;

	/**
	 * @var bool|string
	 */
	private $wikiId = false;

	/** @var string */
	private $table;
	/** @var string */
	private $idField;
	/** @var string */
	private $nameField;

	/**
	 * @param LoadBalancer $dbLoadBalancer A load balancer for acquiring database connections
	 * @param WANObjectCache $cache A cache manager for caching data
	 * @param LoggerInterface $logger
	 * @param string $table
	 * @param string $idField
	 * @param string $nameField
	 * @param bool|string $wikiId The ID of the target wiki database. Use false for the local wiki.
	 */
	public function __construct(
		LoadBalancer $dbLoadBalancer,
		WANObjectCache $cache,
		LoggerInterface $logger,
		$table, $idField, $nameField,
		$wikiId = false
	) {
		$this->loadBalancer = $dbLoadBalancer;
		$this->cache = $cache;
		$this->logger = $logger;
		$this->table = $table;
		$this->idField = $idField;
		$this->nameField = $nameField;
		$this->wikiId = $wikiId;
	}

	/**
	 * @param int $index A database index, like DB_MASTER or DB_REPLICA
	 *
	 * @return IDatabase
	 */
	private function getDBConnection( $index ) {
		return $this->loadBalancer->getConnection( $index, [], $this->wikiId );
	}

	private function getCacheKey() {
		return $this->cache->makeKey( 'NameTableSqlStore', $this->table );
	}

	/**
	 * Acquire the id of the given name.
	 * This creates a row in the table if it doesn't already exist.
	 *
	 * @param string $name
	 * @return int
	 */
	public function acquire( $name ) {
		$table = $this->getCachedTable();
		if ( !in_array( $name, $table ) ) {
			$id = $this->store( $name );
			if ( $id === null ) {
				// RACE: $name was already in the db, so select the table
				$table = $this->selectTable();
			} else {
				$table[$id] = $name;
			}
			$this->cacheTable( $table );
		}

		$searchResult = array_search( $name, $table );
		if ( !$searchResult ) {
			// Either we have already checked the value exists, or we have just stored the value.
			throw new LogicException( 'Failed to get the value' );
		}

		return $searchResult;
	}

	/**
	 * Reacquire the id of the given name.
	 * If the name doesn't exist this will return null.
	 * This should be used in cases where we believe the name already exists or want to check for
	 * existance.
	 *
	 * @param string $name
	 * @return int|null Id or null if the name is not stored.
	 */
	public function reacquire( $name ) {
		$table = $this->getCachedTable();
		$searchResult = array_search( $name, $table );
		return ( $searchResult ? $searchResult : null );
	}

	/**
	 * @return array
	 */
	private function getCachedTable() {
		if ( $this->tableCache !== null) {
			return $this->tableCache;
		}
		$table = $this->cache->getWithSetCallback(
			$this->getCacheKey(),
			IExpiringStore::TTL_MONTH,
			function ( $unused, &$ttl, &$setOpts ) {
				return $this->selectTable();
			}
		);

		$this->tableCache = $table;

		return $table;
	}

	/**
	 * Stores the table in the WAN cache and tableCache property
	 *
	 * @param string[] $table
	 */
	private function cacheTable( $table ) {
		$this->cache->set(
			$this->getCacheKey(),
			$table,
			IExpiringStore::TTL_MONTH
		);
		$this->tableCache = $table;
	}

	/**
	 * Gets the table from the db
	 *
	 * @return string[]
	 */
	private function selectTable() {
		$result = $this->getDBConnection( DB_REPLICA )->select(
			$this->table,
			[
				'id' => $this->idField,
				'name' => $this->nameField
			],
			[],
			__METHOD__
		);
		return $this->dbResultToAssocArray( $result );
	}

	/**
	 * Converts a DB result to an assoc array used throughout this class.
	 *
	 * @param IResultWrapper $result
	 * @return string[] id => name
	 */
	private function dbResultToAssocArray( IResultWrapper $result ) {
		$array = [];
		foreach ( $result as $row ) {
			$array[$row->id] = $row->name;
		}
		return $array;
	}

	/**
	 * Stores the given name in the DB, returning the ID when an insert occours.
	 *
	 * @param string $name
	 * @return int|null int if we know the ID, null if we don't
	 */
	private function store( $name ) {
		Assert::parameterType( 'string', $name, '$name' );

		$dbw = $this->getDBConnection( DB_MASTER );

		$dbw->insert(
			$this->table,
			[ $this->nameField => $name ],
			__METHOD__,
			[ 'IGNORE' ]
		);

		if ( $dbw->affectedRows() === 0 ) {
			$this->logger->info(
				'Tried to insert name into table ' . $this->table . ', but value already existed.'
			);
			return null;
		}

		return $dbw->insertId();
	}

}
