<?php

namespace MediaWiki\Storage;

use MediaWiki\MediaWikiServices;
use WANObjectCache;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\DBConnRef;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\LoadBalancer;

class DBSingleContentRevisionLookup extends SingleContentRevisionLookup {

	/**
	 * @var IDatabase|Database
	 */
	private $db;

	public function __construct(
		LoadBalancer $loadBalancer,
		WANObjectCache $cache,
		RevisionFactory $revisionFactory,
		RevisionTitleLookup $revisionTitleLookup,
		IDatabase $database,
		$wikiId = false
	) {
		$this->db = $database;
		parent::__construct(
			$loadBalancer,
			$cache,
			$revisionFactory,
			$revisionTitleLookup,
			$wikiId
		);
	}

	/**
	 * @param Database|IDatabase $db
	 * @return DBSingleContentRevisionLookup
	 */
	public static function factory( IDatabase $db ) {
		$services = MediaWikiServices::getInstance();
		/** @var RevisionTitleLookup $revisionTitleLookup */
		$revisionTitleLookup = $services->getService( '_RevisionTitleLookup' );
		return new DBSingleContentRevisionLookup(
			$services->getDBLoadBalancer(),
			$services->getMainWANObjectCache(),
			$services->getRevisionFactory(),
			$revisionTitleLookup,
			$db
		);
	}

	/**
	 * @param int $mode DB_MASTER or DB_REPLICA
	 *
	 * @return IDatabase|Database
	 */
	protected function getDBConnection( $mode ) {
		return $this->db;
	}

	/**
	 * @param int $mode DB_MASTER or DB_REPLICA
	 *
	 * @return DBConnRef
	 */
	protected function getDBConnectionRef( $mode ) {
		// TODO is this evil? should be check the db is for this LB? is that needed?
		return new DBConnRef( $this->loadBalancer, $this->db );
	}

}
