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
	 */
	public function newTrxId() {
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
	}

	/**
	 * Reset the application-side transaction ID and return the old one
	 * This will become private soon.
	 * @return string The old transaction ID or an empty string if there wasn't one
	 */
	public function consumeTrxId() {
		$old = $this->trxId;
		$this->trxId = '';

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
	 * @param float|null $trxTimestamp
	 * @unstable This will be removed once usages are migrated here
	 */
	public function setTrxTimestamp( ?float $trxTimestamp ) {
		$this->trxTimestamp = $trxTimestamp;
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

	public function pendingWriteQueryDuration( $type = IDatabase::ESTIMATE_TOTAL, $rtt = null ) {
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
}
