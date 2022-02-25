<?php
/**
 * This file deals with database interface functions
 * and query specifics/optimisations.
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
 */
namespace Wikimedia\Rdbms;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use RuntimeException;
use Throwable;

/**
 * @ingroup Database
 * @internal
 */
class TransactionManager {
	/** @var int Transaction is in a error state requiring a full or savepoint rollback */
	public const STATUS_TRX_ERROR = 1;
	/** @var int Transaction is active and in a normal state */
	public const STATUS_TRX_OK = 2;
	/** @var int No transaction is active */
	public const STATUS_TRX_NONE = 3;

	/** @var float Guess of how many seconds it takes to replicate a small insert */
	private const TINY_WRITE_SEC = 0.010;
	/** @var float Consider a write slow if it took more than this many seconds */
	private const SLOW_WRITE_SEC = 0.500;
	/** @var int Assume an insert of this many rows or less should be fast to replicate */
	private const SMALL_WRITE_ROWS = 100;

	/** @var string Prefix to the atomic section counter used to make savepoint IDs */
	private const SAVEPOINT_PREFIX = 'wikimedia_rdbms_atomic';

	/** @var string Application-side ID of the active transaction or an empty string otherwise */
	private $trxId = '';
	/** @var float|null UNIX timestamp at the time of BEGIN for the last transaction */
	private $trxTimestamp = null;
	/** @var int Transaction status */
	private $trxStatus = self::STATUS_TRX_NONE;
	/** @var Throwable|null The last error that caused the status to become STATUS_TRX_ERROR */
	private $trxStatusCause;
	/** @var array|null Error details of the last statement-only rollback */
	private $trxStatusIgnoredCause;

	/** @var string[] Write query callers of the current transaction */
	private $trxWriteCallers = [];
	/** @var float Seconds spent in write queries for the current transaction */
	private $trxWriteDuration = 0.0;
	/** @var int Number of write queries for the current transaction */
	private $trxWriteQueryCount = 0;
	/** @var int Number of rows affected by write queries for the current transaction */
	private $trxWriteAffectedRows = 0;
	/** @var float Like trxWriteQueryCount but excludes lock-bound, easy to replicate, queries */
	private $trxWriteAdjDuration = 0.0;
	/** @var int Number of write queries counted in trxWriteAdjDuration */
	private $trxWriteAdjQueryCount = 0;

	/** @var array List of (name, unique ID, savepoint ID) for each active atomic section level */
	private $trxAtomicLevels = [];
	/** @var bool Whether the current transaction was started implicitly due to DBO_TRX */
	private $trxAutomatic = false;
	/** @var bool Whether the current transaction was started implicitly by startAtomic() */
	private $trxAutomaticAtomic = false;

	/** @var string|null Name of the function that start the last transaction */
	private $trxFname = null;
	/** @var bool Whether possible write queries were done in the last transaction started */
	private $trxDoneWrites = false;
	/** @var int Counter for atomic savepoint identifiers (reset with each transaction) */
	private $trxAtomicCounter = 0;

	/** @var LoggerInterface */
	private $logger;

	public function __construct( LoggerInterface $logger = null ) {
		$this->logger = $logger ?? new NullLogger();
	}

	public function trxLevel() {
		return ( $this->trxId != '' ) ? 1 : 0;
	}

	/**
	 * TODO: This should be removed once all usages have been migrated here
	 * @return string
	 */
	public function getTrxId(): string {
		return $this->trxId;
	}

	/**
	 * TODO: This should be removed once all usages have been migrated here
	 * @param string $mode One of IDatabase::TRANSACTION_* values
	 * @param string $fname method name
	 */
	public function newTrxId( $mode, $fname ) {
		static $nextTrxId;
		$nextTrxId = ( $nextTrxId !== null ? $nextTrxId++ : mt_rand() ) % 0xffff;
		$this->trxId = sprintf( '%06x', mt_rand( 0, 0xffffff ) ) . sprintf( '%04x', $nextTrxId );
		$this->trxStatus = self::STATUS_TRX_OK;
		$this->trxStatusIgnoredCause = null;
		$this->trxWriteDuration = 0.0;
		$this->trxWriteQueryCount = 0;
		$this->trxWriteAffectedRows = 0;
		$this->trxWriteAdjDuration = 0.0;
		$this->trxWriteAdjQueryCount = 0;
		$this->trxWriteCallers = [];
		$this->trxAtomicLevels = [];
		// T147697: make explicitTrxActive() return true until begin() finishes. This way,
		// no caller triggered by getApproximateLagStatus() will think its OK to muck around
		// with the transaction just because startAtomic() has not yet finished updating the
		// tracking fields (e.g. trxAtomicLevels).
		$this->trxAutomatic = ( $mode === IDatabase::TRANSACTION_INTERNAL );
		$this->trxAutomaticAtomic = false;
		$this->trxFname = $fname;
		$this->trxDoneWrites = false;
		$this->trxAtomicCounter = 0;
		$this->trxTimestamp = microtime( true );
	}

	/**
	 * Reset the application-side transaction ID and return the old one
	 * This will become private soon.
	 * @return string The old transaction ID or an empty string if there wasn't one
	 */
	public function consumeTrxId() {
		$old = $this->trxId;
		$this->trxId = '';
		$this->trxAtomicCounter = 0;

		return $old;
	}

	public function trxTimestamp(): ?float {
		return $this->trxLevel() ? $this->trxTimestamp : null;
	}

	/**
	 * @return int One of the STATUS_TRX_* class constants
	 */
	public function trxStatus(): int {
		return $this->trxStatus;
	}

	public function setTrxStatusToOk() {
		$this->trxStatus = self::STATUS_TRX_OK;
		$this->trxStatusIgnoredCause = null;
	}

	public function setTrxStatusToNone() {
		$this->trxStatus = self::STATUS_TRX_NONE;
	}

	public function assertTransactionStatus( IDatabase $db, $deprecationLogger, $fname ) {
		if ( $this->trxStatus < self::STATUS_TRX_OK ) {
			throw new DBTransactionStateError(
				$db,
				"Cannot execute query from $fname while transaction status is ERROR",
				[],
				$this->trxStatusCause
			);
		} elseif ( $this->trxStatus === self::STATUS_TRX_OK && $this->trxStatusIgnoredCause ) {
			list( $iLastError, $iLastErrno, $iFname ) = $this->trxStatusIgnoredCause;
			call_user_func( $deprecationLogger,
				"Caller from $fname ignored an error originally raised from $iFname: " .
				"[$iLastErrno] $iLastError"
			);
			$this->trxStatusIgnoredCause = null;
		}
	}

	public function setTransactionErrorFromStatus( $db, $fname ) {
		if ( $this->trxStatus > self::STATUS_TRX_ERROR ) {
			// Put the transaction into an error state if it's not already in one
			$trxError = new DBUnexpectedError(
				$db,
				"Uncancelable atomic section canceled (got $fname)"
			);
			$this->setTransactionError( $trxError );
		}
	}

	/**
	 * Mark the transaction as requiring rollback (STATUS_TRX_ERROR) due to an error
	 *
	 * @param Throwable $trxError
	 */
	public function setTransactionError( Throwable $trxError ) {
		if ( $this->trxStatus > self::STATUS_TRX_ERROR ) {
			$this->trxStatus = self::STATUS_TRX_ERROR;
			$this->trxStatusCause = $trxError;
		}
	}

	/**
	 * @param array|null $trxStatusIgnoredCause
	 */
	public function setTrxStatusIgnoredCause( ?array $trxStatusIgnoredCause ): void {
		$this->trxStatusIgnoredCause = $trxStatusIgnoredCause;
	}

	/**
	 * @param int $rtt
	 * @return float Time to apply writes to replicas based on trxWrite* fields
	 */
	private function calculateLastTrxApplyTime( int $rtt ) {
		$rttAdjTotal = $this->trxWriteAdjQueryCount * $rtt;
		$applyTime = max( $this->trxWriteAdjDuration - $rttAdjTotal, 0 );
		// For omitted queries, make them count as something at least
		$omitted = $this->trxWriteQueryCount - $this->trxWriteAdjQueryCount;
		$applyTime += self::TINY_WRITE_SEC * $omitted;

		return $applyTime;
	}

	public function pendingWriteCallers() {
		return $this->trxLevel() ? $this->trxWriteCallers : [];
	}

	/**
	 * Update the estimated run-time of a query, not counting large row lock times
	 *
	 * LoadBalancer can be set to rollback transactions that will create huge replication
	 * lag. It bases this estimate off of pendingWriteQueryDuration(). Certain simple
	 * queries, like inserting a row can take a long time due to row locking. This method
	 * uses some simple heuristics to discount those cases.
	 *
	 * @param string $queryVerb action in the write query
	 * @param float $runtime Total runtime, including RTT
	 * @param int $affected Affected row count
	 * @param string $fname method name invoking the action
	 */
	public function updateTrxWriteQueryReport( $queryVerb, $runtime, $affected, $fname ) {
		// Whether this is indicative of replica DB runtime (except for RBR or ws_repl)
		$indicativeOfReplicaRuntime = true;
		if ( $runtime > self::SLOW_WRITE_SEC ) {
			// insert(), upsert(), replace() are fast unless bulky in size or blocked on locks
			if ( $queryVerb === 'INSERT' ) {
				$indicativeOfReplicaRuntime = $affected > self::SMALL_WRITE_ROWS;
			} elseif ( $queryVerb === 'REPLACE' ) {
				$indicativeOfReplicaRuntime = $affected > self::SMALL_WRITE_ROWS / 2;
			}
		}

		$this->trxWriteDuration += $runtime;
		$this->trxWriteQueryCount += 1;
		$this->trxWriteAffectedRows += $affected;
		if ( $indicativeOfReplicaRuntime ) {
			$this->trxWriteAdjDuration += $runtime;
			$this->trxWriteAdjQueryCount += 1;
		}

		$this->trxWriteCallers[] = $fname;
	}

	public function pendingWriteQueryDuration( IDatabase $db, $type = IDatabase::ESTIMATE_TOTAL ) {
		if ( !$this->trxLevel() ) {
			return false;
		} elseif ( !$this->trxDoneWrites ) {
			return 0.0;
		}
		$rtt = null;
		if ( $type == IDatabase::ESTIMATE_DB_APPLY ) {
			// passed by reference
			$db->ping( $rtt );
		}
		switch ( $type ) {
			case IDatabase::ESTIMATE_DB_APPLY:
				return $this->calculateLastTrxApplyTime( $rtt );
			default: // everything
				return $this->trxWriteDuration;
		}
	}

	public function getTrxWriteAffectedRows(): int {
		return $this->trxWriteAffectedRows;
	}

	/**
	 * @return string
	 */
	public function flatAtomicSectionList() {
		return array_reduce( $this->trxAtomicLevels, static function ( $accum, $v ) {
			return $accum === null ? $v[0] : "$accum, " . $v[0];
		} );
	}

	public function resetTrxAtomicLevels() {
		$this->trxAtomicLevels = [];
	}

	public function explicitTrxActive() {
		return $this->trxLevel() && ( $this->trxAtomicLevels || !$this->trxAutomatic );
	}

	public function trxCheckBeforeClose( IDatabase $db, $fname ) {
		$error = null;
		if ( $this->trxAtomicLevels ) {
			// Cannot let incomplete atomic sections be committed
			$levels = $this->flatAtomicSectionList();
			$error = "$fname: atomic sections $levels are still open";
		} elseif ( $this->trxAutomatic ) {
			// Only the connection manager can commit non-empty DBO_TRX transactions
			// (empty ones we can silently roll back)
			if ( $db->writesOrCallbacksPending() ) {
				$error = "$fname: " .
					"expected mass rollback of all peer transactions (DBO_TRX set)";
			}
		} else {
			// Manual transactions should have been committed or rolled
			// back, even if empty.
			$error = "$fname: transaction is still open (from {$this->trxFname})";
		}

		return $error;
	}

	public function onAtomicSectionCancel( IDatabase $db, $fname ): void {
		if ( !$this->trxLevel() || !$this->trxAtomicLevels ) {
			throw new DBUnexpectedError( $db, "No atomic section is open (got $fname)" );
		}
	}

	/**
	 * @return AtomicSectionIdentifier|null ID of the topmost atomic section level
	 */
	public function currentAtomicSectionId(): ?AtomicSectionIdentifier {
		if ( $this->trxLevel() && $this->trxAtomicLevels ) {
			$levelInfo = end( $this->trxAtomicLevels );

			return $levelInfo[1];
		}

		return null;
	}

	public function addToAtomicLevels( $fname, AtomicSectionIdentifier $sectionId, $savepointId ) {
		$this->trxAtomicLevels[] = [ $fname, $sectionId, $savepointId ];
		$this->logger->debug( 'startAtomic: entering level ' .
			( count( $this->trxAtomicLevels ) - 1 ) . " ($fname)", [ 'db_log_category' => 'trx' ] );
	}

	public function onBeginTransaction( IDatabase $db, $fname ): void {
		// @phan-suppress-previous-line PhanPluginNeverReturnMethod
		if ( $this->trxAtomicLevels ) {
			$levels = $this->flatAtomicSectionList();
			$msg = "$fname: got explicit BEGIN while atomic section(s) $levels are open";
			throw new DBUnexpectedError( $db, $msg );
		} elseif ( !$this->trxAutomatic ) {
			$msg = "$fname: explicit transaction already active (from {$this->trxFname})";
			throw new DBUnexpectedError( $db, $msg );
		} else {
			$msg = "$fname: implicit transaction already active (from {$this->trxFname})";
			throw new DBUnexpectedError( $db, $msg );
		}
	}

	/**
	 * @param IDatabase $db
	 * @param string $fname
	 * @param string $flush one of IDatabase::FLUSHING_* values
	 * @return bool false if the commit should go aborted, true otherwise.
	 */
	public function onCommit( IDatabase $db, $fname, $flush ): bool {
		if ( $this->trxLevel() && $this->trxAtomicLevels ) {
			// There are still atomic sections open; this cannot be ignored
			$levels = $this->flatAtomicSectionList();
			throw new DBUnexpectedError(
				$db,
				"$fname: got COMMIT while atomic sections $levels are still open"
			);
		}

		if ( $flush === IDatabase::FLUSHING_INTERNAL || $flush === IDatabase::FLUSHING_ALL_PEERS ) {
			if ( !$this->trxLevel() ) {
				return false; // nothing to do
			} elseif ( !$this->trxAutomatic ) {
				throw new DBUnexpectedError(
					$db,
					"$fname: flushing an explicit transaction, getting out of sync"
				);
			}
		} elseif ( !$this->trxLevel() ) {
			$this->logger->error(
				"$fname: no transaction to commit, something got out of sync",
				[
					'exception' => new RuntimeException(),
					'db_log_category' => 'trx'
				]
			);

			return false; // nothing to do
		} elseif ( $this->trxAutomatic ) {
			throw new DBUnexpectedError(
				$db,
				"$fname: expected mass commit of all peer transactions (DBO_TRX set)"
			);
		}
		return true;
	}

	public function onEndAtomic( IDatabase $db, $fname ): array {
		// Check if the current section matches $fname
		$pos = count( $this->trxAtomicLevels ) - 1;
		list( $savedFname, $sectionId, $savepointId ) = $this->trxAtomicLevels[$pos];
		$this->logger->debug( "endAtomic: leaving level $pos ($fname)", [ 'db_log_category' => 'trx' ] );

		if ( $savedFname !== $fname ) {
			throw new DBUnexpectedError(
				$db,
				"Invalid atomic section ended (got $fname but expected $savedFname)"
			);
		}

		return [ $savepointId, $sectionId ];
	}

	public function getPositionFromSectionId( AtomicSectionIdentifier $sectionId = null ): ?int {
		if ( $sectionId !== null ) {
			// Find the (last) section with the given $sectionId
			$pos = -1;
			foreach ( $this->trxAtomicLevels as $i => list( $asFname, $asId, $spId ) ) {
				if ( $asId === $sectionId ) {
					$pos = $i;
				}
			}
		} else {
			$pos = null;
		}

		return $pos;
	}

	public function cancelAtomic( $pos ) {
		$excisedIds = [];
		$excisedFnames = [];
		$newTopSection = $this->currentAtomicSectionId();
		if ( $pos !== null ) {
			// Remove all descendant sections and re-index the array
			$len = count( $this->trxAtomicLevels );
			for ( $i = $pos + 1; $i < $len; ++$i ) {
				$excisedFnames[] = $this->trxAtomicLevels[$i][0];
				$excisedIds[] = $this->trxAtomicLevels[$i][1];
			}
			$this->trxAtomicLevels = array_slice( $this->trxAtomicLevels, 0, $pos + 1 );
			$newTopSection = $this->currentAtomicSectionId();
		}

		// Check if the current section matches $fname
		$pos = count( $this->trxAtomicLevels ) - 1;
		list( $savedFname, $savedSectionId, $savepointId ) = $this->trxAtomicLevels[$pos];

		if ( $excisedFnames ) {
			$this->logger->debug( "cancelAtomic: canceling level $pos ($savedFname) " .
				"and descendants " . implode( ', ', $excisedFnames ),
				[ 'db_log_category' => 'trx' ]
			);
		} else {
			$this->logger->debug( "cancelAtomic: canceling level $pos ($savedFname)",
				[ 'db_log_category' => 'trx' ]
			);
		}

		return [ $savedFname, $excisedIds, $newTopSection, $savedSectionId, $savepointId ];
	}

	public function popAtomicLevel() {
		array_pop( $this->trxAtomicLevels );
	}

	public function isClean() {
		return !$this->trxAtomicLevels && $this->trxAutomaticAtomic;
	}

	public function setAutomaticAtomic( $value ) {
		$this->trxAutomaticAtomic = $value;
	}

	public function turnOnAutomatic() {
		$this->trxAutomatic = true;
	}

	public function nextSavePointId( IDatabase $db, $fname ) {
		$savepointId = self::SAVEPOINT_PREFIX . ++$this->trxAtomicCounter;
		if ( strlen( $savepointId ) > 30 ) {
			// 30 == Oracle's identifier length limit (pre 12c)
			// With a 22 character prefix, that puts the highest number at 99999999.
			throw new DBUnexpectedError(
				$db,
				'There have been an excessively large number of atomic sections in a transaction'
				. " started by $this->trxFname (at $fname)"
			);
		}

		return $savepointId;
	}

	public function getTrxFname() {
		return $this->trxFname;
	}

	public function writesPending() {
		return $this->trxLevel() && $this->trxDoneWrites;
	}

	public function isTrxDoneWrites() {
		return $this->trxDoneWrites;
	}

	public function setTrxDoneWritesToTrue() {
		$this->trxDoneWrites = true;
	}

	public function onDestruct() {
		if ( $this->trxLevel() && $this->trxDoneWrites ) {
			$trxFname = $this->getTrxFname();
			trigger_error( "Uncommitted DB writes (transaction from {$trxFname})" );
		}
	}
}
