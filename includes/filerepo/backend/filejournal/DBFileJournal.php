<?php
/**
 * @file
 * @ingroup FileJournal
 * @author Aaron Schulz
 */

/**
 * Version of FileJournal that logs to a DB table
 * @since 1.20
 */
class DBFileJournal extends FileJournal {
	protected $wiki = false; // string; wiki DB name

	/**
	 * Construct a new instance from configuration.
	 * $config includes:
	 *     'wiki' : wiki name to use for LoadBalancer
	 * 
	 * @param $config Array
	 */
	protected function __construct( array $config ) {
		parent::__construct( $config );

		$this->wiki = $config['wiki'];
	}

	/**
	 * @see FileJournal::logChangeBatch()
	 * @return Status 
	 */
	protected function doLogChangeBatch( array $entries, $batchId ) {
		$status = Status::newGood();

		$dbw = $this->getMasterDB();
		if ( !$dbw ) {
			$status->fatal( 'filejournal-fail-dbconnect', $this->backend );
			return $status;
		}
		$now = wfTimestamp( TS_UNIX );

		$data = array();
		foreach ( $entries as $entry ) {
			$data[] = array(
				'fj_batch_uuid' => $batchId,
				'fj_backend'    => $this->backend,
				'fj_op'         => $entry['op'],
				'fj_path'       => $entry['path'],
				'fj_path_sha1'  => wfBaseConvert( sha1( $entry['path'] ), 16, 36, 31 ),
				'fj_new_sha1'   => $entry['newSha1'],
				'fj_timestamp'  => $dbw->timestamp( $now )
			);
		}

		try {
			$dbw->begin();
			$dbw->insert( 'filejournal', $data, __METHOD__ );
			$dbw->commit();
		} catch ( DBError $e ) {
			$status->fatal( 'filejournal-fail-dbquery', $this->backend );
			return $status;
		}

		return $status;
	}

	/**
	 * @see FileJournal::purgeOldLogs()
	 * @return Status
	 */
	protected function doPurgeOldLogs() {
		$status = Status::newGood();
		if ( $this->ttlDays <= 0 ) {
			return $status; // nothing to do
		}

		$dbw = $this->getMasterDB();
		if ( !$dbw ) {
			$status->fatal( 'filejournal-fail-dbconnect', $this->backend );
			return $status;
		}
		$dbCutoff = $dbw->timestamp( time() - 86400 * $this->ttlDays );

		try {
			$dbw->begin();
			$dbw->delete( 'filejournal',
				array( 'fj_timestamp < ' . $dbw->addQuotes( $dbCutoff ) ),
				__METHOD__
			);
			$dbw->commit();
		} catch ( DBError $e ) {
			$status->fatal( 'filejournal-fail-dbquery', $this->backend );
			return $status;
		}

		return $status;
	}

	/**
	 * Get a master connection to the logging DB
	 * 
	 * @return DatabaseBase|null 
	 */
	protected function getMasterDB() {
		try {
			$lb = wfGetLBFactory()->newMainLB();
			return $lb->getConnection( DB_MASTER, array(), $this->wiki );
		} catch ( DBConnectionError $e ) {
			return null;
		}
	}
}
