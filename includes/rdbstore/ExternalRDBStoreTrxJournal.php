<?php
/**
 * This file deals with sharded RDBMs stores.
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
 * @ingroup RDBStore
 * @author Aaron Schulz
 */

/**
 * Class that helps with cross-shard commits and anomaly correction in external RDB stores.
 * This logs the transaction ID and affected servers before the PREPARE/COMMIT phases.
 * Distributed transactions that use this class should essentially work as follows:
 *   - a) The coordinator prepare transaction journal is updated
 *   - b) Changes are proposed on the relevant DBs, starting XA transactions
 *   - c) The coordinator commit transaction journal is updated
 *   - d) Changes are prepared and committed on the relevant DBs, ending XA transactions
 * These log files are deleted when everything is committed successfully. Old log files are
 * assumed to correspond to failed transactions and are used to repair the affected DB rows.
 *
 * @ingroup RDBStore
 * @since 1.20
 */
class ExternalRDBStoreTrxJournal {
	/** @var ExternalRDBStore */
	protected $rdbStore;
	/** @var BagOStuff */
	protected $srvCache;

	/** @var Array */
	protected $directory; // string; path
	protected $trxId; // string; timestamped UUID
	protected $phase = self::PHASE_READY; // integer

	const PHASE_READY      = 1; // not in a transaction
	const PHASE_PREPARING  = 2; // ready for PREPAREs
	const PHASE_COMMITTING = 3; // agreement on all changes, ready for COMMITs

	const STALE_SEC = 900; // how old a .tlog file must be to be "stale"
	const CHECK_SEC = 60; // period for checking for stale .tlog files

	/**
	 * @param $rdbStore ExternalRDBStore
	 */
	public function __construct( ExternalRDBStore $rdbStore ) {
		$this->rdbStore  = $rdbStore;
		$this->directory = wfTempDir() . '/mw-rdbstore/' . rawurlencode( $rdbStore->getName() );
		try {
			$cache = ObjectCache::newAccelerator( array() ); // local server cache
		} catch ( MWException $e ) {
			$cache = new EmptyBagOStuff();
		}
		$this->srvCache = $cache;
	}

	/**
	 * Function to be called right before transaction PREPARE
	 *
	 * @param $trxId string Unique global transaction ID
	 * @param $servers array List of hostname/ips for each server
	 * @return void
	 */
	public function onPrePrepare( $trxId, array $servers ) {
		if ( $this->phase !== self::PHASE_READY ) {
			throw new DBUnexpectedError( "Transaction alreay in progress." );
		}
		$this->trxId = $trxId;
		// Log the PREPARE attempt to a file in /tmp space
		$data = serialize( array( 'trxId' => $this->trxId, 'servers' => $servers ) );
		wfProfileIn( __METHOD__ . '-log' );
		wfMkdirParents( $this->directory ); // create dir if needed
		$bytes = file_put_contents( "{$this->directory}/{$this->trxId}.p.tlog", $data );
		wfProfileOut( __METHOD__ . '-log' );
		if ( $bytes !== strlen( $data ) ) {
			throw new DBUnexpectedError( "Could not write to '{$this->trxId}.p.tlog' file." );
		}
		$this->phase = self::PHASE_PREPARING;
	}

	/**
	 * Function to be called right before transaction COMMIT
	 *
	 * @return void
	 */
	public function onPreCommit() {
		if ( $this->phase !== self::PHASE_PREPARING ) {
			throw new DBUnexpectedError( "Transaction not prepared." );
		}
		// Log the COMMIT decision to a file in /tmp space
		wfProfileIn( __METHOD__ . '-log' );
		$ok = rename(
			"{$this->directory}/{$this->trxId}.p.tlog",
			"{$this->directory}/{$this->trxId}.c.tlog"
		);
		wfProfileOut( __METHOD__ . '-log' );
		if ( !$ok ) {
			throw new DBUnexpectedError( "Could not write to '{$this->trxId}.c.tlog' file." );
		}
		$this->phase = self::PHASE_COMMITTING;
	}

	/**
	 * Function to be called right after transaction COMMIT
	 *
	 * @return void
	 */
	public function onPostCommit() {
		if ( $this->phase !== self::PHASE_COMMITTING ) {
			throw new DBUnexpectedError( "Transaction is not ready to commit." );
		}
		wfProfileIn( __METHOD__ . '-log' );
		unlink( "{$this->directory}/{$this->trxId}.c.tlog" ); // not needed anymore
		wfProfileOut( __METHOD__ . '-log' );
		$this->trxId = null;
		$this->phase = self::PHASE_READY;
	}

	/**
	 * @return integer Number of .tlog files consumed
	 */
	public function periodicLogCheck() {
		wfProfileIn( __METHOD__ );
		$key  = "rdbstore-collect-{$this->rdbStore->getName()}";
		$time = $this->srvCache->get( $key );
		if ( !is_int( $time ) ) { // cache miss
			wfSuppressWarnings();
			$time = filemtime( "{$this->directory}.collect" ); // false if file doesn't exist
			wfRestoreWarnings();
			$this->srvCache->set( $key, (int)$time, 86400 );
		}
		if ( $time < ( time() - self::CHECK_SEC ) ) {
			$this->srvCache->set( $key, time(), 86400 );
			wfDebug( __METHOD__ . ": Checking for stale tlog files.\n" );
			$count = $this->fixDBFromLogs( 10 );
		} else {
			wfDebug( __METHOD__ . ": Skipping check for stale tlog files.\n" );
			$count = 0;
		}
		wfProfileOut( __METHOD__ );
		return $count;
	}

	/**
	 * Find stale .tlog files lying around in /tmp and fix the DB rows.
	 * This deals with inconsistent denormalized rows resulting from prior incomplete
	 * transactions on this server. The .tlog files are removed once the DB is synced.
	 *
	 * @param $maxTrxCount integer Maximum .tlog files to consume
	 * @return integer Number of .tlog files consumed
	 */
	public function fixDBFromLogs( $maxTrxCount = 0 ) {
		wfProfileIn( __METHOD__ );

		$masterSrvsInfo = array(); // master DB hosts/IPs for the RDB store
		foreach ( $this->rdbStore->getClusters() as $cluster ) {
			$lb    = wfGetLBFactory()->getExternalLB( $cluster );
			$sinfo = $lb->getServerInfo( $lb->getWriterIndex() );
			$masterSrvsInfo[$sinfo['host']] = $sinfo;
		}

		$cutoff = time() - self::STALE_SEC;
		// Make sure only one process on this server is doing this...
		wfMkdirParents( $this->directory ); // create dir if needed
		$lockFileHandle = fopen( "{$this->directory}.collect", 'w' ); // open & touch
		if ( !$lockFileHandle ) {
			wfProfileOut( __METHOD__ );
			return false; // bail out
		} elseif ( !flock( $lockFileHandle, LOCK_EX | LOCK_NB ) ) {
			fclose( $lockFileHandle );
			wfProfileOut( __METHOD__ );
			return false; // someone else probably beat us
		}

		$e = null; // exception
		try {
			$tblobs = array(); // (tlog basename => contents of file)
			// Get all of the old .tlog files lying around...
			$dirHandle = opendir( $this->directory );
			if ( $dirHandle ) {
				while ( $file = readdir( $dirHandle ) ) {
					if ( FileBackend::extensionFromPath( $file ) !== 'tlog' ) {
						continue; // sanity
					} elseif ( filemtime( "{$this->directory}/{$file}" ) > $cutoff ) {
						continue; // wait; transaction may still be in progress
					}
					$data = file_get_contents( "{$this->directory}/{$file}" );
					if ( $data === false ) {
						continue; // sanity
					}
					$tblobs[$file] = $data; // delete this afterwards
				}
				closedir( $dirHandle );
			}
			$countDone = 0; // count fixed
			wfDebugLog( 'RDBStore', "Found " . count( $tblobs ) . " limbo transaction(s).\n" );
			// Run through the failed transactions and fix them
			foreach ( $tblobs as $file => $blob ) {
				$trxInfo = unserialize( $blob );
				if ( !is_array( $trxInfo ) ) {
					continue; // shouldn't happen
				}
				$trxInfo['committed'] = ( substr( $file, -7 ) === '.c.tlog' );
				if ( $this->finishTransaction( $trxInfo, $masterSrvsInfo ) ) {
					++$countDone; // another transaction resolved
					unlink( "{$this->directory}/{$file}" ); // done with this tlog file
					if ( $maxTrxCount && $countDone >= $maxTrxCount ) {
						break; // only handle so many tlog files
					}
				}
			}
			wfDebugLog( 'RDBStore', "Repaired $countDone limbo transaction(s).\n" );
		} catch ( Exception $e ) {}

		// Release locks so other processes can poll these files...
		flock( $lockFileHandle, LOCK_UN );
		fclose( $lockFileHandle );

		wfProfileOut( __METHOD__ );
		if ( $e instanceof Exception ) {
			throw $e; // throw back any exception
		}

		return count( $tblobs );
	}

	/**
	 * @param $trxInfo array Map of trx ID, commit status, and servers (hostname/IP) used
	 * @param $masterSrvsInfo array (master hostname => server info)
	 * @return boolean
	 */
	protected function finishTransaction( array $trxInfo, array $masterSrvsInfo ) {
		$id = $trxInfo['trxId']; // transaction ID

		$ok = true;
		foreach ( $trxInfo['servers'] as $srv ) { // for each cohort
			if ( !isset( $masterSrvsInfo[$srv] ) ) {
				$ok = false; // cohort server not found...master switched?
				wfDebugLog( 'RDBStore', "Could not find server '$srv'.\n" );
				continue;
			}
			$server = $masterSrvsInfo[$srv];
			$dbw = DatabaseBase::factory( $server['type'], $server );
			$dbw->clearFlag( DBO_TRX ); // don't wrap in BEGIN
			if ( $this->rdbStore->connExistsXAInternal( $dbw, $id ) ) { // already resolved?
				if ( $trxInfo['committed'] ) { // COMMIT decision made
					$this->rdbStore->connCommitXAInternal( $dbw, '2PC', $id );
					wfDebugLog( 'RDBStore', "Commited transaction '$id'.\n" );
				} else { // no COMMIT decision made; ROLLBACK
					$this->rdbStore->connRollbackXAInternal( $dbw, $id );
					wfDebugLog( 'RDBStore', "Rolled back transaction '$id'.\n" );
				}
			} else {
				wfDebugLog( 'RDBStore', "Could not find transaction '$id'.\n" );
			}
		}

		return $ok;
	}
}
