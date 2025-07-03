<?php
/**
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
use UnexpectedValueException;

/**
 * @ingroup Database
 * @internal This class should not be used outside of Database
 */
class TransactionManager {
	/** Transaction is in a error state requiring a full or savepoint rollback */
	public const STATUS_TRX_ERROR = 1;
	/** Transaction is active and in a normal state */
	public const STATUS_TRX_OK = 2;
	/** No transaction is active */
	public const STATUS_TRX_NONE = 3;

	/** Session is in a error state requiring a reset */
	public const STATUS_SESS_ERROR = 1;
	/** Session is in a normal state */
	public const STATUS_SESS_OK = 2;

	/** @var float Guess of how many seconds it takes to replicate a small insert */
	private const TINY_WRITE_SEC = 0.010;
	/** @var float Consider a write slow if it took more than this many seconds */
	private const SLOW_WRITE_SEC = 0.500;
	/** Assume an insert of this many rows or less should be fast to replicate */
	private const SMALL_WRITE_ROWS = 100;

	/** @var string Prefix to the atomic section counter used to make savepoint IDs */
	private const SAVEPOINT_PREFIX = 'wikimedia_rdbms_atomic';

	/** @var ?TransactionIdentifier Application-side ID of the active (server-side) transaction */
	private $trxId;
	/** @var ?float UNIX timestamp of BEGIN for the last transaction */
	private $trxTimestamp = null;
	/** @var ?float Round trip time estimate for queries during the last transaction */
	private $trxRoundTripDelay = null;
	/** @var int STATUS_TRX_* constant indicating the transaction lifecycle state */
	private $trxStatus = self::STATUS_TRX_NONE;
	/** @var ?Throwable The cause of any unresolved transaction lifecycle state error */
	private $trxStatusCause;
	/** @var ?array Details of any unresolved statement-rollback error within a transaction */
	private $trxStatusIgnoredCause;

	/** @var ?Throwable The cause of any unresolved session state loss error */
	private $sessionError;

	/** @var string[] Write query callers of the last transaction */
	private $trxWriteCallers = [];
	/** @var float Seconds spent in write queries for the last transaction */
	private $trxWriteDuration = 0.0;
	/** @var int Number of write queries for the last transaction */
	private $trxWriteQueryCount = 0;
	/** @var int Number of rows affected by write queries for the last transaction */
	private $trxWriteAffectedRows = 0;
	/** @var float Like trxWriteQueryCount but excludes lock-bound, easy to replicate, queries */
	private $trxWriteAdjDuration = 0.0;
	/** @var int Number of write queries counted in trxWriteAdjDuration */
	private $trxWriteAdjQueryCount = 0;

	/** @var array Pending atomic sections; list of (name, unique ID, savepoint ID) */
	private $trxAtomicLevels = [];
	/** @var bool Whether the last transaction was started implicitly due to DBO_TRX */
	private $trxAutomatic = false;
	/** @var bool Whether the last transaction was started implicitly by startAtomic() */
	private $trxAutomaticAtomic = false;

	/** @var ?string Name of the function that started the last transaction */
	private $trxFname = null;
	/** @var int Counter for atomic savepoint identifiers for the last transaction */
	private $trxAtomicCounter = 0;

	/**
	 * @var array[] Pending postcommit callbacks; list of (callable, method name, atomic section id)
	 * @phan-var array<array{0:callable,1:string,2:AtomicSectionIdentifier|null}>
	 */
	private $trxPostCommitOrIdleCallbacks = [];
	/**
	 * @var array[] Pending precommit callbacks; list of (callable, method name, atomic section id)
	 * @phan-var array<array{0:callable,1:string,2:AtomicSectionIdentifier|null}>
	 */
	private $trxPreCommitOrIdleCallbacks = [];
	/**
	 * @var array[] Pending post-trx callbacks; list of (callable, method name, atomic section id)
	 * @phan-var array<array{0:callable,1:string,2:AtomicSectionIdentifier|null}>
	 */
	private $trxEndCallbacks = [];
	/** @var callable[] Listener callbacks; map of (name => callable) */
	private $trxRecurringCallbacks = [];
	/** @var bool Whether to suppress triggering of transaction end callbacks */
	private $trxEndCallbacksSuppressed = false;

	/** @var LoggerInterface */
	private $logger;
	/** @var TransactionProfiler */
	private $profiler;

	public function __construct( ?LoggerInterface $logger = null, ?TransactionProfiler $profiler = null ) {
		$this->logger = $logger ?? new NullLogger();
		$this->profiler = $profiler ?? new TransactionProfiler();
	}

	public function trxLevel(): int {
		return $this->trxId ? 1 : 0;
	}

	/**
	 * Get the application-side transaction identifier instance
	 *
	 * @return ?TransactionIdentifier Token for the active transaction; null if there isn't one
	 */
	public function getTrxId() {
		return $this->trxId;
	}

	/**
	 * Reset the application-side transaction identifier instance and return the old one
	 *
	 * @return ?TransactionIdentifier The old transaction token; null if there wasn't one
	 */
	private function consumeTrxId() {
		$old = $this->trxId;
		$this->trxId = null;

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
		$this->trxStatusCause = null;
		$this->trxStatusIgnoredCause = null;
	}

	public function setTrxStatusToNone() {
		$this->trxStatus = self::STATUS_TRX_NONE;
		$this->trxStatusCause = null;
		$this->trxStatusIgnoredCause = null;
	}

	/**
	 * @param IDatabase $db
	 * @param callable $deprecationLogger
	 * @param string $fname
	 */
	public function assertTransactionStatus( IDatabase $db, $deprecationLogger, $fname ) {
		if ( $this->trxStatus === self::STATUS_TRX_ERROR ) {
			throw new DBTransactionStateError(
				$db,
				"Cannot execute query from $fname while transaction status is ERROR",
				[],
				$this->trxStatusCause
			);
		} elseif ( $this->trxStatus === self::STATUS_TRX_OK && $this->trxStatusIgnoredCause ) {
			[ $iLastError, $iLastErrno, $iFname ] = $this->trxStatusIgnoredCause;
			$deprecationLogger(
				"Caller from $fname ignored an error originally raised from $iFname: " .
				"[$iLastErrno] $iLastError"
			);
			$this->trxStatusIgnoredCause = null;
		}
	}

	public function assertSessionStatus( IDatabase $db, string $fname ) {
		if ( $this->sessionError ) {
			throw new DBSessionStateError(
				$db,
				"Cannot execute query from $fname while session status is ERROR",
				[],
				$this->sessionError
			);
		}
	}

	/**
	 * Mark the transaction as requiring rollback (STATUS_TRX_ERROR) due to an error
	 */
	public function setTransactionError( Throwable $trxError ) {
		if ( $this->trxStatus !== self::STATUS_TRX_ERROR ) {
			$this->trxStatus = self::STATUS_TRX_ERROR;
			$this->trxStatusCause = $trxError;
		}
	}

	public function setTrxStatusIgnoredCause( ?array $trxStatusIgnoredCause ): void {
		$this->trxStatusIgnoredCause = $trxStatusIgnoredCause;
	}

	/**
	 * Get the status of the current session (ephemeral server-side state tied to the connection)
	 *
	 * @return int One of the STATUS_SESSION_* class constants
	 */
	public function sessionStatus() {
		// Check if an unresolved error still exists
		return ( $this->sessionError ) ? self::STATUS_SESS_ERROR : self::STATUS_SESS_OK;
	}

	/**
	 * Flag the session as needing a reset due to an error, if not already flagged
	 */
	public function setSessionError( Throwable $sessionError ) {
		$this->sessionError ??= $sessionError;
	}

	/**
	 * Unflag the session as needing a reset due to an error
	 */
	public function clearSessionError() {
		$this->sessionError = null;
	}

	/**
	 * @param float $rtt
	 * @return float Time to apply writes to replicas based on trxWrite* fields
	 */
	private function calculateLastTrxApplyTime( float $rtt ) {
		$rttAdjTotal = $this->trxWriteAdjQueryCount * $rtt;
		$applyTime = max( $this->trxWriteAdjDuration - $rttAdjTotal, 0.0 );
		// For omitted queries, make them count as something at least
		$omitted = $this->trxWriteQueryCount - $this->trxWriteAdjQueryCount;
		$applyTime += self::TINY_WRITE_SEC * $omitted;

		return $applyTime;
	}

	public function pendingWriteCallers(): array {
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
		$this->trxWriteQueryCount++;
		$this->trxWriteAffectedRows += $affected;
		if ( $indicativeOfReplicaRuntime ) {
			$this->trxWriteAdjDuration += $runtime;
			$this->trxWriteAdjQueryCount++;
		}

		$this->trxWriteCallers[] = $fname;
	}

	public function pendingWriteQueryDuration( string $type = IDatabase::ESTIMATE_TOTAL ): float|false {
		if ( !$this->trxLevel() ) {
			return false;
		} elseif ( !$this->trxWriteCallers ) {
			return 0.0;
		}

		if ( $type === IDatabase::ESTIMATE_DB_APPLY ) {
			return $this->calculateLastTrxApplyTime( $this->trxRoundTripDelay );
		}

		return $this->trxWriteDuration;
	}

	/**
	 * @return string
	 */
	private function flatAtomicSectionList() {
		return array_reduce( $this->trxAtomicLevels, static function ( $accum, $v ) {
			return $accum === null ? $v[0] : "$accum, " . $v[0];
		} );
	}

	public function resetTrxAtomicLevels() {
		$this->trxAtomicLevels = [];
		$this->trxAtomicCounter = 0;
	}

	public function explicitTrxActive(): bool {
		return $this->trxLevel() && ( $this->trxAtomicLevels || !$this->trxAutomatic );
	}

	public function trxCheckBeforeClose( IDatabaseForOwner $db, string $fname ): ?string {
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

		if ( $this->trxEndCallbacksSuppressed && $error === null ) {
			$error = "$fname: callbacks are suppressed; cannot properly commit";
		}

		return $error;
	}

	public function onCancelAtomicBeforeCriticalSection( IDatabase $db, string $fname ): void {
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

	public function addToAtomicLevels( string $fname, AtomicSectionIdentifier $sectionId, ?string $savepointId ) {
		$this->trxAtomicLevels[] = [ $fname, $sectionId, $savepointId ];
		$this->logger->debug( 'startAtomic: entering level ' .
			( count( $this->trxAtomicLevels ) - 1 ) . " ($fname)", [ 'db_log_category' => 'trx' ] );
	}

	public function onBegin( IDatabase $db, string $fname ): void {
		// Protect against mismatched atomic section, transaction nesting, and snapshot loss
		if ( $this->trxLevel() ) {
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

	public function onEndAtomic( IDatabase $db, string $fname ): array {
		if ( !$this->trxLevel() || !$this->trxAtomicLevels ) {
			throw new DBUnexpectedError( $db, "No atomic section is open (got $fname)" );
		}
		// Check if the current section matches $fname
		$pos = count( $this->trxAtomicLevels ) - 1;
		[ $savedFname, $sectionId, $savepointId ] = $this->trxAtomicLevels[$pos];
		$this->logger->debug( "endAtomic: leaving level $pos ($fname)", [ 'db_log_category' => 'trx' ] );

		if ( $savedFname !== $fname ) {
			throw new DBUnexpectedError(
				$db,
				"Invalid atomic section ended (got $fname but expected $savedFname)"
			);
		}

		return [ $savepointId, $sectionId ];
	}

	public function getPositionFromSectionId( ?AtomicSectionIdentifier $sectionId = null ): ?int {
		if ( $sectionId !== null ) {
			// Find the (last) section with the given $sectionId
			$pos = -1;
			foreach ( $this->trxAtomicLevels as $i => [ , $asId, ] ) {
				if ( $asId === $sectionId ) {
					$pos = $i;
				}
			}
		} else {
			$pos = null;
		}

		return $pos;
	}

	public function cancelAtomic( ?int $pos ): array {
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
		[ $savedFname, $savedSectionId, $savepointId ] = $this->trxAtomicLevels[$pos];

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

	/**
	 * @return bool Whether no levels remain and transaction was started by a popped level
	 */
	public function popAtomicLevel() {
		array_pop( $this->trxAtomicLevels );

		return !$this->trxAtomicLevels && $this->trxAutomaticAtomic;
	}

	public function setAutomaticAtomic( bool $value ) {
		$this->trxAutomaticAtomic = $value;
	}

	public function turnOnAutomatic() {
		$this->trxAutomatic = true;
	}

	public function nextSavePointId( IDatabase $db, string $fname ): string {
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

	public function writesPending(): bool {
		return $this->trxLevel() && $this->trxWriteCallers;
	}

	public function onDestruct() {
		if ( $this->trxLevel() && $this->trxWriteCallers ) {
			trigger_error( "Uncommitted DB writes (transaction from {$this->trxFname})" );
		}
	}

	public function transactionWritingIn( string $serverName, ?string $domainId, float $startTime ) {
		if ( !$this->trxWriteCallers ) {
			$this->profiler->transactionWritingIn(
				$serverName,
				$domainId,
				(string)$this->trxId,
				$startTime
			);
		}
	}

	public function transactionWritingOut( IDatabase $db, string $oldId ) {
		if ( $this->trxWriteCallers ) {
			$this->profiler->transactionWritingOut(
				$db->getServerName(),
				$db->getDomainID(),
				$oldId,
				$this->pendingWriteQueryDuration( IDatabase::ESTIMATE_TOTAL ),
				$this->trxWriteAffectedRows
			);
		}
	}

	/**
	 * @param string|GeneralizedSql $sql
	 * @param float $startTime
	 * @param bool $isPermWrite
	 * @param int|null $rowCount
	 * @param string|null $serverName
	 */
	public function recordQueryCompletion( $sql, $startTime, $isPermWrite, $rowCount, $serverName ) {
		$this->profiler->recordQueryCompletion(
			$sql,
			$startTime,
			$isPermWrite,
			$rowCount,
			(string)$this->trxId,
			$serverName
		);
	}

	public function onTransactionResolution( IDatabase $db, callable $callback, string $fname ) {
		if ( !$this->trxLevel() ) {
			throw new DBUnexpectedError( $db, "No transaction is active" );
		}
		$this->trxEndCallbacks[] = [ $callback, $fname, $this->currentAtomicSectionId() ];
	}

	public function addPostCommitOrIdleCallback( callable $callback, string $fname ) {
		$this->trxPostCommitOrIdleCallbacks[] = [
			$callback,
			$fname,
			$this->currentAtomicSectionId()
		];
	}

	final public function addPreCommitOrIdleCallback( callable $callback, string $fname ) {
		$this->trxPreCommitOrIdleCallbacks[] = [
			$callback,
			$fname,
			$this->currentAtomicSectionId()
		];
	}

	public function setTransactionListener( string $name, ?callable $callback = null ) {
		if ( $callback ) {
			$this->trxRecurringCallbacks[$name] = $callback;
		} else {
			unset( $this->trxRecurringCallbacks[$name] );
		}
	}

	/**
	 * Whether to disable running of post-COMMIT/ROLLBACK callbacks
	 * @param bool $suppress
	 */
	public function setTrxEndCallbackSuppression( bool $suppress ) {
		$this->trxEndCallbacksSuppressed = $suppress;
	}

	/**
	 * Hoist callback ownership for callbacks in a section to a parent section.
	 * All callbacks should have an owner that is present in trxAtomicLevels.
	 * @param AtomicSectionIdentifier $old
	 * @param AtomicSectionIdentifier $new
	 */
	public function reassignCallbacksForSection(
		AtomicSectionIdentifier $old,
		AtomicSectionIdentifier $new
	) {
		foreach ( $this->trxPreCommitOrIdleCallbacks as $key => $info ) {
			if ( $info[2] === $old ) {
				$this->trxPreCommitOrIdleCallbacks[$key][2] = $new;
			}
		}
		foreach ( $this->trxPostCommitOrIdleCallbacks as $key => $info ) {
			if ( $info[2] === $old ) {
				$this->trxPostCommitOrIdleCallbacks[$key][2] = $new;
			}
		}
		foreach ( $this->trxEndCallbacks as $key => $info ) {
			if ( $info[2] === $old ) {
				$this->trxEndCallbacks[$key][2] = $new;
			}
		}
	}

	/**
	 * Update callbacks that were owned by cancelled atomic sections.
	 *
	 * Callbacks for "on commit" should never be run if they're owned by a
	 * section that won't be committed.
	 *
	 * Callbacks for "on resolution" need to reflect that the section was
	 * rolled back, even if the transaction as a whole commits successfully.
	 *
	 * Callbacks for "on section cancel" should already have been consumed,
	 * but errors during the cancellation itself can prevent that while still
	 * destroying the section. Hoist any such callbacks to the new top section,
	 * which we assume will itself have to be cancelled or rolled back to
	 * resolve the error.
	 *
	 * @param AtomicSectionIdentifier[] $excisedSectionsId Cancelled section IDs
	 * @param AtomicSectionIdentifier|null $newSectionId New top section ID
	 * @throws UnexpectedValueException
	 */
	public function modifyCallbacksForCancel(
		array $excisedSectionsId,
		?AtomicSectionIdentifier $newSectionId = null
	) {
		// Cancel the "on commit" callbacks owned by this savepoint
		$this->trxPostCommitOrIdleCallbacks = array_filter(
			$this->trxPostCommitOrIdleCallbacks,
			static function ( $entry ) use ( $excisedSectionsId ) {
				return !in_array( $entry[2], $excisedSectionsId, true );
			}
		);
		$this->trxPreCommitOrIdleCallbacks = array_filter(
			$this->trxPreCommitOrIdleCallbacks,
			static function ( $entry ) use ( $excisedSectionsId ) {
				return !in_array( $entry[2], $excisedSectionsId, true );
			}
		);
		// Make "on resolution" callbacks owned by this savepoint to perceive a rollback
		foreach ( $this->trxEndCallbacks as $key => $entry ) {
			if ( in_array( $entry[2], $excisedSectionsId, true ) ) {
				$callback = $entry[0];
				$this->trxEndCallbacks[$key][0] = static function () use ( $callback ) {
					return $callback( IDatabase::TRIGGER_ROLLBACK );
				};
				// This "on resolution" callback no longer belongs to a section.
				$this->trxEndCallbacks[$key][2] = null;
			}
		}
	}

	public function consumeEndCallbacks(): array {
		$callbackEntries = array_merge(
			$this->trxPostCommitOrIdleCallbacks,
			$this->trxEndCallbacks
		);
		$this->trxPostCommitOrIdleCallbacks = []; // consumed (and recursion guard)
		$this->trxEndCallbacks = []; // consumed (recursion guard)

		return $callbackEntries;
	}

	/**
	 * Consume and run any "on transaction pre-commit" callbacks
	 *
	 * @return int Number of callbacks attempted
	 * @throws Throwable Any exception thrown by a callback
	 */
	public function runOnTransactionPreCommitCallbacks(): int {
		$count = 0;

		// Drain the queues of transaction "precommit" callbacks until it is empty
		do {
			$callbackEntries = $this->trxPreCommitOrIdleCallbacks;
			$this->trxPreCommitOrIdleCallbacks = []; // consumed (and recursion guard)
			$count += count( $callbackEntries );
			foreach ( $callbackEntries as $entry ) {
				try {
					$entry[0]();
				} catch ( Throwable $trxError ) {
					$this->setTransactionError( $trxError );
					throw $trxError;
				}
			}
			// @phan-suppress-next-line PhanImpossibleConditionInLoop
		} while ( $this->trxPreCommitOrIdleCallbacks );

		return $count;
	}

	public function clearPreEndCallbacks() {
		$this->trxPostCommitOrIdleCallbacks = [];
		$this->trxPreCommitOrIdleCallbacks = [];
	}

	public function clearEndCallbacks() {
		$this->trxEndCallbacks = []; // don't copy
	}

	public function writesOrCallbacksPending(): bool {
		return $this->trxLevel() && (
				$this->trxWriteCallers ||
				$this->trxPostCommitOrIdleCallbacks ||
				$this->trxPreCommitOrIdleCallbacks ||
				$this->trxEndCallbacks
			);
	}

	/**
	 * List the methods that have write queries or callbacks for the current transaction
	 *
	 * @return string[]
	 */
	public function pendingWriteAndCallbackCallers(): array {
		$fnames = $this->pendingWriteCallers();
		foreach ( [
			$this->trxPostCommitOrIdleCallbacks,
			$this->trxPreCommitOrIdleCallbacks,
			$this->trxEndCallbacks
		] as $callbacks ) {
			foreach ( $callbacks as $callback ) {
				$fnames[] = $callback[1];
			}
		}

		return $fnames;
	}

	/**
	 * List the methods that have precommit callbacks for the current transaction
	 *
	 * @return string[]
	 */
	public function pendingPreCommitCallbackCallers(): array {
		$fnames = $this->pendingWriteCallers();
		foreach ( $this->trxPreCommitOrIdleCallbacks as $callback ) {
			$fnames[] = $callback[1];
		}

		return $fnames;
	}

	public function isEndCallbacksSuppressed(): bool {
		return $this->trxEndCallbacksSuppressed;
	}

	public function getRecurringCallbacks(): array {
		return $this->trxRecurringCallbacks;
	}

	public function countPostCommitOrIdleCallbacks(): int {
		return count( $this->trxPostCommitOrIdleCallbacks );
	}

	/**
	 * @param string $mode One of IDatabase::TRANSACTION_* values
	 * @param string $fname method name
	 * @param float $rtt Trivial query round-trip-delay
	 */
	public function onBeginInCriticalSection( $mode, $fname, $rtt ) {
		$this->trxId = new TransactionIdentifier();
		$this->setTrxStatusToOk();
		$this->resetTrxAtomicLevels();
		$this->trxWriteCallers = [];
		$this->trxWriteDuration = 0.0;
		$this->trxWriteQueryCount = 0;
		$this->trxWriteAffectedRows = 0;
		$this->trxWriteAdjDuration = 0.0;
		$this->trxWriteAdjQueryCount = 0;
		// T147697: make explicitTrxActive() return true until begin() finishes. This way,
		// no caller triggered by getApproximateLagStatus() will think its OK to muck around
		// with the transaction just because startAtomic() has not yet finished updating the
		// tracking fields (e.g. trxAtomicLevels).
		$this->trxAutomatic = ( $mode === IDatabase::TRANSACTION_INTERNAL );
		$this->trxAutomaticAtomic = false;
		$this->trxFname = $fname;
		$this->trxTimestamp = microtime( true );
		$this->trxRoundTripDelay = $rtt;
	}

	public function onRollbackInCriticalSection( IDatabase $db ) {
		$oldTrxId = $this->consumeTrxId();
		$this->setTrxStatusToNone();
		$this->resetTrxAtomicLevels();
		$this->clearPreEndCallbacks();
		$this->transactionWritingOut( $db, (string)$oldTrxId );
	}

	public function onCommitInCriticalSection( IDatabase $db ): ?float {
		$lastWriteTime = null;

		$oldTrxId = $this->consumeTrxId();
		$this->setTrxStatusToNone();
		if ( $this->trxWriteCallers ) {
			$lastWriteTime = microtime( true );
			$this->transactionWritingOut( $db, (string)$oldTrxId );
		}

		return $lastWriteTime;
	}

	public function onSessionLoss( IDatabase $db ) {
		$oldTrxId = $this->consumeTrxId();
		$this->clearPreEndCallbacks();
		$this->transactionWritingOut( $db, (string)$oldTrxId );
	}

	public function onEndAtomicInCriticalSection( AtomicSectionIdentifier $sectionId ) {
		// Hoist callback ownership for callbacks in the section that just ended;
		// all callbacks should have an owner that is present in trxAtomicLevels.
		$currentSectionId = $this->currentAtomicSectionId();
		if ( $currentSectionId ) {
			$this->reassignCallbacksForSection( $sectionId, $currentSectionId );
		}
	}

	public function onFlushSnapshot( IDatabase $db, string $fname, string $flush, ?string $trxRoundFname ) {
		if ( $this->explicitTrxActive() ) {
			// Committing this transaction would break callers that assume it is still open
			throw new DBUnexpectedError(
				$db,
				"$fname: Cannot flush snapshot; " .
				"explicit transaction '{$this->trxFname}' is still open"
			);
		} elseif ( $this->writesOrCallbacksPending() ) {
			// This only flushes transactions to clear snapshots, not to write data
			$fnames = implode( ', ', $this->pendingWriteAndCallbackCallers() );
			throw new DBUnexpectedError(
				$db,
				"$fname: Cannot flush snapshot; " .
				"writes from transaction {$this->trxFname} are still pending ($fnames)"
			);
		} elseif (
			$this->trxLevel() &&
			$trxRoundFname !== null &&
			$flush !== IDatabase::FLUSHING_INTERNAL &&
			$flush !== IDatabase::FLUSHING_ALL_PEERS
		) {
			$this->logger->warning(
				"$fname: Expected mass snapshot flush of all peer transactions " .
				"in the explicit transactions round '{$trxRoundFname}'",
				[
					'exception' => new RuntimeException(),
					'db_log_category' => 'trx'
				]
			);
		}
	}

	public function onGetScopedLockAndFlush( IDatabase $db, string $fname ) {
		if ( $this->writesOrCallbacksPending() ) {
			// This only flushes transactions to clear snapshots, not to write data
			$fnames = implode( ', ', $this->pendingWriteAndCallbackCallers() );
			throw new DBUnexpectedError(
				$db,
				"$fname: Cannot flush pre-lock snapshot; " .
				"writes from transaction {$this->trxFname} are still pending ($fnames)"
			);
		}
	}
}
