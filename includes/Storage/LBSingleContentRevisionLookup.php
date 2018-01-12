<?php

namespace MediaWiki\Storage;

use Wikimedia\Rdbms\DBConnRef;
use Wikimedia\Rdbms\IDatabase;

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
