<?php

namespace MediaWiki\Storage;

use IExpiringStore;
use WANObjectCache;
use Wikimedia\Assert\Assert;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\LoadBalancer;

class NameTableSqlStore implements IExpiringStore{

	const CACHE_GROUP = 'nametables:1';

	/**
	 * @var LoadBalancer
	 */
	private $dbLoadBalancer;

	/**
	 * @var WANObjectCache
	 */
	private $cache;

	/**
	 * @var bool|string
	 */
	private $wikiId = false;

	// TODO inject...
	private $table = 'content_models';
	private $nameFieldName = 'model_name';

	//TODO inject
	private $cacheTTL = self::TTL_HOUR;

	/**
	 * @param LoadBalancer $dbLoadBalancer A load balancer for acquiring database connections
	 * @param WANObjectCache $cache A cache manager for caching data
	 * @param bool|string $wikiId The ID of the target wiki database. Use false for the local wiki.
	 */
	public function __construct(
		LoadBalancer $dbLoadBalancer,
		WANObjectCache $cache,
		$wikiId = false
	) {
		$this->dbLoadBalancer = $dbLoadBalancer;
		$this->cache = $cache;
		$this->wikiId = $wikiId;
	}

	/**
	 * @param int $index A database index, like DB_MASTER or DB_REPLICA
	 *
	 * @return IDatabase
	 */
	private function getDBConnection( $index ) {
		return $this->dbLoadBalancer->getConnection( $index, [], $this->wikiId );
	}

	private function getCacheKey( $id ) {
		return $this->cache->makeKey( 'table-' . $this->table, 'id', $id );
	}

	private function getCacheOpts() {
		return [ 'pcGroup' => self::CACHE_GROUP, 'pcTTL' => IExpiringStore::TTL_PROC_LONG ];
	}

	/**
	 * @param int $id
	 * @return string|null
	 */
	public function get( $id ) {
		Assert::parameterType( 'int', $id, '$id' );

		$name = $this->cache->getWithSetCallback(
			$this->getCacheKey( $id ),
			$this->cacheTTL,
			function ( $unused, &$ttl, &$setOpts ) use ( $id ) {
				return $this->fetchById( $id );
			},
			$this->getCacheOpts()
		);

		if ( $name === false ) {
			return null;
		}

		return $name;
	}

	private function fetchById( $id ) {
		$dbr = $this->getDBConnection( DB_REPLICA );
		$row = $dbr->selectRow(
			$this->table,
			[ $this->nameFieldName ],
			[ 'id' => $id ],
			__METHOD__
		);
		return $row->$this->nameFieldName;
	}

	/**
	 * @param string $name
	 * @return int
	 */
	public function store( $name ) {
		Assert::parameterType( 'string', $name, '$name' );

		$dbw = $this->getDBConnection( DB_MASTER );

		$dbw->insert(
			$this->table,
			[ $this->nameFieldName => $name ],
			__METHOD__,
			[ 'IGNORE' ]
		);

		// If there was a conflict an no insert happened due to the 'IGNORE' options
		if ( $dbw->affectedRows() !== 1 ) {
			// race
			// TODO lookup by name? From cache? Wait for lag?
		}

		//TODO what happens here if IGNORE means a row wasn't inserted?
		$id = $dbw->insertId();

		$this->cache->set(
			$this->getCacheKey( $id ),
			$id,
			$this->cacheTTL,
			$this->getCacheOpts()
		);

		return $id;
	}

}