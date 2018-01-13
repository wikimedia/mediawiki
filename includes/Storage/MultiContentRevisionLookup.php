<?php

namespace MediaWiki\Storage;

use Wikimedia\Rdbms\DBConnRef;
use Wikimedia\Rdbms\IDatabase;

class MultiContentRevisionLookup extends AbstractRevisionLookup {

	/**
	 * @param int $mode DB_MASTER or DB_REPLICA
	 *
	 * @return IDatabase
	 */
	protected function getDBConnection( $mode ) {
		throw new \RuntimeException( 'Not yet implemented' );
	}

	/**
	 * @param int $mode DB_MASTER or DB_REPLICA
	 *
	 * @return DBConnRef
	 */
	protected function getDBConnectionRef( $mode ) {
		throw new \RuntimeException( 'Not yet implemented' );
	}

	/**
	 * Given a set of conditions, return a row with the
	 * fields necessary to build RevisionRecord objects.
	 *
	 * MCR migration note: this corresponds to Revision::fetchFromConds
	 *
	 * @param IDatabase $db
	 * @param array $conditions
	 * @param int $flags (optional)
	 *
	 * @return object|false data row as a raw object
	 */
	protected function fetchRevisionRowFromConds( IDatabase $db, $conditions, $flags = 0 ) {
		$this->checkDatabaseWikiId( $db, $this->wikiId );

		$queryInfoOptions = [ 'page', 'user' ];
		if ( $this->contentHandlerUseDB ) {
			$queryInfoOptions[] = 'useContentHandler';
		}

		// TODO actually the 'abstract' bit is the query info only.... (if we can do this in 1
		// query...)

		throw new \RuntimeException( 'Not yet implemented' );

		$revQuery = self::getQueryInfo( $queryInfoOptions );
		$options = [];
		if ( ( $flags & self::READ_LOCKING ) == self::READ_LOCKING ) {
			$options[] = 'FOR UPDATE';
		}
		return $db->selectRow(
			$revQuery['tables'],
			$revQuery['fields'],
			$conditions,
			__METHOD__,
			$options,
			$revQuery['joins']
		);
	}
}
