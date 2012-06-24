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

	/** @var Array */
	protected $directory; // string; file system path
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
	}

	/**
	 * Function to be called right before transaction PREPARE
	 *
	 * @param $trxId string Unique global transaction ID
	 * @param $clusters array List of DB cluster names for each server
	 * @return void
	 */
	public function onPrePrepare( $trxId, array $clusters ) {
		if ( $this->phase !== self::PHASE_READY ) {
			throw new MWException( "Transaction alreay in progress." );
		}
		$this->trxId = $trxId;
		// Log the PREPARE attempt to a file in /tmp space
		$data = serialize( array( 'clusters' => $clusters ) );
		wfProfileIn( __METHOD__ . '-log' );
		wfMkdirParents( $this->directory ); // create dir if needed
		$bytes = file_put_contents( "{$this->directory}/{$this->trxId}.prepare.tlog", $data );
		wfProfileOut( __METHOD__ . '-log' );
		if ( $bytes !== strlen( $data ) ) {
			throw new MWException( "Could not write to '{$this->trxId}.prepare.tlog' file." );
		}
		wfDebugLog( "RDBStoreTrx", "{$this->rdbStore->getName()}\tPREPARE\t{$this->trxId}\n" );
		$this->phase = self::PHASE_PREPARING;
	}

	/**
	 * Function to be called right before transaction COMMIT
	 *
	 * @return void
	 */
	public function onPreCommit() {
		if ( $this->phase !== self::PHASE_PREPARING ) {
			throw new MWException( "Transaction not prepared." );
		}
		// Log the COMMIT decision to a file in /tmp space
		wfProfileIn( __METHOD__ . '-log' );
		$ok = $this->fsyncCopy(
			"{$this->directory}/{$this->trxId}.prepare.tlog",
			"{$this->directory}/{$this->trxId}.commit.tlog"
		);
		wfProfileOut( __METHOD__ . '-log' );
		if ( !$ok ) {
			throw new MWException( "Could not create '{$this->trxId}.commit.tlog' file." );
		}
		wfDebugLog( "RDBStoreTrx", "{$this->rdbStore->getName()} COMMIT {$this->trxId}\n" );
		$this->phase = self::PHASE_COMMITTING;
	}

	/**
	 * Function to be called right after transaction COMMIT
	 *
	 * @return void
	 */
	public function onPostCommit() {
		if ( $this->phase !== self::PHASE_COMMITTING ) {
			throw new MWException( "Transaction is not ready to commit." );
		}
		wfProfileIn( __METHOD__ . '-log' );
		unlink( "{$this->directory}/{$this->trxId}.prepare.tlog" ); // not needed anymore
		unlink( "{$this->directory}/{$this->trxId}.commit.tlog" ); // not needed anymore
		wfProfileOut( __METHOD__ . '-log' );
		$this->trxId = null;
		$this->phase = self::PHASE_READY;
	}

	/**
	 * @param $src string
	 * @param $dst string
	 * @return bool
	 */
	protected function fsyncCopy( $src, $dst ) {
		if ( !wfIsWindows() ) { // *nix
			$encSrc = wfEscapeShellArg( $src );
			$encDst = wfEscapeShellArg( $dst );
			$retVal = 1;
			wfShellExec( "dd if={$encSrc} of={$encDst} conv=fsync", $retVal );
			return ( $retVal == 0 );
		} else { // windows
			return copy( $src, $dst );
		}
	}

	/**
	 * @return integer Number of .tlog files consumed
	 */
	public function periodicLogCheck() {
		wfProfileIn( __METHOD__ );
		try {
			$cache = ObjectCache::newAccelerator( array() ); // local server cache
		} catch ( MWException $e ) {
			$cache = new EmptyBagOStuff();
		}
		$key  = "rdbstore-collect-{$this->rdbStore->getName()}";
		$time = $cache->get( $key );
		if ( !is_int( $time ) ) { // cache miss
			wfSuppressWarnings();
			$time = filemtime( "{$this->directory}/collect" ); // false if file doesn't exist
			wfRestoreWarnings();
			$cache->set( $key, (int)$time, 86400 );
		}
		if ( $time < ( time() - self::CHECK_SEC ) ) {
			$cache->set( $key, time(), 86400 );
			wfDebug( __METHOD__ . ": Checking for stale tlog files.\n" );
			$count = $this->resolveLimboTransactions( 10 );
		} else {
			wfDebug( __METHOD__ . ": Skipping check for stale tlog files.\n" );
			$count = 0;
		}
		wfProfileOut( __METHOD__ );
		return $count;
	}

	/**
	 * Find stale .tlog files lying around in /tmp and finish the corresponding
	 * DB transactions. The .tlog files are removed once the transactions resolve.
	 *
	 * @param $maxTrxCount integer Maximum .tlog files to consume
	 * @return integer Number of .tlog files consumed
	 */
	public function resolveLimboTransactions( $maxTrxCount = 0 ) {
		wfProfileIn( __METHOD__ );

		// Make sure only one process on this server is doing this...
		wfMkdirParents( $this->directory ); // create dir if needed
		$lockFileHandle = fopen( "{$this->directory}/collect", 'w' ); // open & touch
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
			$logs = array(); // (trx ID => {filename,step})
			// Get all of the old .tlog files lying around...
			$dirHandle = opendir( $this->directory );
			if ( $dirHandle ) {
				$cutoff = time() - self::STALE_SEC;
				$fileRegex = '/^(\d{14}-[0-9a-f]{32})\.(commit|prepare)\.tlog$/';
				while ( $file = readdir( $dirHandle ) ) {
					$m = array(); // matches
					if ( !preg_match( $fileRegex, $file, $m ) ) {
						continue; // sanity
					} elseif ( filemtime( "{$this->directory}/{$file}" ) > $cutoff ) {
						continue; // wait; transaction may still be in progress
					}
					list( /*all*/, $trxId, $step ) = $m;
					if ( $step === 'commit' || !isset( $logs[$trxId] ) ) { // commit has precedence
						$info = unserialize( file_get_contents( "{$this->directory}/{$file}" ) );
						if ( is_array( $info ) ) {
							$logs[$trxId] = $info + array( 'step' => $step );
						}
					}
				}
				closedir( $dirHandle );
			}
			wfDebugLog( 'RDBStore', "Found " . count( $logs ) . " limbo transaction(s).\n" );
			// Run through transactions that failed and resolve them...
			$countDone = 0; // count fixed
			foreach ( $logs as $trxId => $trxInfo ) {
				if ( $this->finishTransaction( $trxId, $trxInfo ) ) {
					++$countDone; // another transaction resolved
					if ( file_exists( "{$this->directory}/{$trxId}.prepare.tlog" ) ) {
						unlink( "{$this->directory}/{$trxId}.prepare.tlog" );
					}
					if ( file_exists( "{$this->directory}/{$trxId}.commit.tlog" ) ) {
						unlink( "{$this->directory}/{$trxId}.commit.tlog" );
					}
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

		return count( $logs );
	}

	/**
	 * @param $trxId string
	 * @param $trxInfo array Map of trx ID, commit status, and clusters used
	 * @return boolean
	 */
	protected function finishTransaction( $trxId, array $trxInfo ) {
		foreach ( $trxInfo['clusters'] as $cluster ) { // for each cohort
			$lb = wfGetLBFactory()->getExternalLB( $cluster ); // LB for cohort
			$dbw = $lb->getConnection( DB_MASTER ); // DB master
			$dbw->clearFlag( DBO_TRX ); // don't wrap in BEGIN
			if ( $this->rdbStore->connExistsXAInternal( $dbw, $trxId ) ) { // already resolved?
				if ( $trxInfo['step'] === 'commit' ) { // COMMIT decision made
					$this->rdbStore->connCommitXAInternal( $dbw, '2PC', $trxId );
					wfDebugLog( 'RDBStore', "Commited transaction '$trxId'.\n" );
				} else { // no COMMIT decision made; ROLLBACK
					$this->rdbStore->connRollbackXAInternal( $dbw, $trxId );
					wfDebugLog( 'RDBStore', "Rolled back transaction '$trxId'.\n" );
				}
			} else {
				wfDebugLog( 'RDBStore', "Could not find transaction '$trxId'.\n" );
			}
		}
		return true;
	}
}
