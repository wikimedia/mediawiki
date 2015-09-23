<?php
/**
 * Version of FileJournal that logs to a DB table.
 *
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
 * @ingroup FileJournal
 * @author Aaron Schulz
 */

/**
 * Version of FileJournal that logs to a DB table
 * @since 1.20
 */
class DBFileJournal extends FileJournal {
	/** @var DatabaseBase */
	protected $dbw;

	protected $wiki = false; // string; wiki DB name

	/**
	 * Construct a new instance from configuration.
	 *
	 * @param array $config Includes:
	 *     'wiki' : wiki name to use for LoadBalancer
	 */
	protected function __construct( array $config ) {
		parent::__construct( $config );

		$this->wiki = $config['wiki'];
	}

	/**
	 * @see FileJournal::logChangeBatch()
	 * @param array $entries
	 * @param string $batchId
	 * @return Status
	 */
	protected function doLogChangeBatch( array $entries, $batchId ) {
		$status = Status::newGood();

		try {
			$dbw = $this->getMasterDB();
		} catch ( DBError $e ) {
			$status->fatal( 'filejournal-fail-dbconnect', $this->backend );

			return $status;
		}

		$now = wfTimestamp( TS_UNIX );

		$data = array();
		foreach ( $entries as $entry ) {
			$data[] = array(
				'fj_batch_uuid' => $batchId,
				'fj_backend' => $this->backend,
				'fj_op' => $entry['op'],
				'fj_path' => $entry['path'],
				'fj_new_sha1' => $entry['newSha1'],
				'fj_timestamp' => $dbw->timestamp( $now )
			);
		}

		try {
			$dbw->insert( 'filejournal', $data, __METHOD__ );
			if ( mt_rand( 0, 99 ) == 0 ) {
				$this->purgeOldLogs(); // occasionally delete old logs
			}
		} catch ( DBError $e ) {
			$status->fatal( 'filejournal-fail-dbquery', $this->backend );

			return $status;
		}

		return $status;
	}

	/**
	 * @see FileJournal::doGetCurrentPosition()
	 * @return bool|mixed The value from the field, or false on failure.
	 */
	protected function doGetCurrentPosition() {
		$dbw = $this->getMasterDB();

		return $dbw->selectField( 'filejournal', 'MAX(fj_id)',
			array( 'fj_backend' => $this->backend ),
			__METHOD__
		);
	}

	/**
	 * @see FileJournal::doGetPositionAtTime()
	 * @param int|string $time Timestamp
	 * @return bool|mixed The value from the field, or false on failure.
	 */
	protected function doGetPositionAtTime( $time ) {
		$dbw = $this->getMasterDB();

		$encTimestamp = $dbw->addQuotes( $dbw->timestamp( $time ) );

		return $dbw->selectField( 'filejournal', 'fj_id',
			array( 'fj_backend' => $this->backend, "fj_timestamp <= $encTimestamp" ),
			__METHOD__,
			array( 'ORDER BY' => 'fj_timestamp DESC' )
		);
	}

	/**
	 * @see FileJournal::doGetChangeEntries()
	 * @param int $start
	 * @param int $limit
	 * @return array
	 */
	protected function doGetChangeEntries( $start, $limit ) {
		$dbw = $this->getMasterDB();

		$res = $dbw->select( 'filejournal', '*',
			array(
				'fj_backend' => $this->backend,
				'fj_id >= ' . $dbw->addQuotes( (int)$start ) ), // $start may be 0
			__METHOD__,
			array_merge( array( 'ORDER BY' => 'fj_id ASC' ),
				$limit ? array( 'LIMIT' => $limit ) : array() )
		);

		$entries = array();
		foreach ( $res as $row ) {
			$item = array();
			foreach ( (array)$row as $key => $value ) {
				$item[substr( $key, 3 )] = $value; // "fj_op" => "op"
			}
			$entries[] = $item;
		}

		return $entries;
	}

	/**
	 * @see FileJournal::purgeOldLogs()
	 * @return Status
	 * @throws DBError
	 */
	protected function doPurgeOldLogs() {
		$status = Status::newGood();
		if ( $this->ttlDays <= 0 ) {
			return $status; // nothing to do
		}

		$dbw = $this->getMasterDB();
		$dbCutoff = $dbw->timestamp( time() - 86400 * $this->ttlDays );

		$dbw->delete( 'filejournal',
			array( 'fj_timestamp < ' . $dbw->addQuotes( $dbCutoff ) ),
			__METHOD__
		);

		return $status;
	}

	/**
	 * Get a master connection to the logging DB
	 *
	 * @return DatabaseBase
	 * @throws DBError
	 */
	protected function getMasterDB() {
		if ( !$this->dbw ) {
			// Get a separate connection in autocommit mode
			$lb = wfGetLBFactory()->newMainLB();
			$this->dbw = $lb->getConnection( DB_MASTER, array(), $this->wiki );
			$this->dbw->clearFlag( DBO_TRX );
		}

		return $this->dbw;
	}
}
