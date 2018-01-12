<?php

namespace MediaWiki\Storage;

use Wikimedia\Rdbms\DBConnRef;
use Wikimedia\Rdbms\IDatabase;

/**
 * RevisionLookup that uses the loadBalancer for DB Connections.
 */
class LBSingleContentRevisionLookup extends SingleContentRevisionLookup {

	/**
	 * @param int $mode DB_MASTER or DB_REPLICA
	 *
	 * @return IDatabase
	 */
	protected function getDBConnection( $mode ) {
		return $this->loadBalancer->getConnection( $mode, [], $this->wikiId );
	}

	/**
	 * @param int $mode DB_MASTER or DB_REPLICA
	 *
	 * @return DBConnRef
	 */
	protected function getDBConnectionRef( $mode ) {
		return $this->loadBalancer->getConnectionRef( $mode, [], $this->wikiId );
	}

}
