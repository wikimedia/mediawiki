<?php

use MediaWiki\MediaWikiServices;
use MediaWiki\Title\Title;

/**
 * Custom job to perform updates on tables in busier environments
 *
 * Job parameters include:
 *   - table     : DB table to update
 *   - column    : The *_user_text column to update
 *   - oldname   : The old user name
 *   - newname   : The new user name
 *   - count     : The expected number of rows to update in this batch
 *   - logId     : The ID of the logging table row expected to exist if the rename was committed
 *
 * Additionally, one of the following groups of parameters must be set:
 * a) The timestamp based rename parameters:
 *   - timestampColumn : The *_timestamp column
 *   - minTimestamp    : The minimum bound of the timestamp column range for this batch
 *   - maxTimestamp    : The maximum bound of the timestamp column range for this batch
 *   - uniqueKey       : A column that is unique (preferrably the PRIMARY KEY) [optional]
 * b) The unique key based rename parameters:
 *   - uniqueKey : A column that is unique (preferrably the PRIMARY KEY)
 *   - keyId     : A list of values for this column to determine rows to update for this batch
 *
 * To avoid some race conditions, the following parameters should be set:
 *   - userID    : The ID of the user to update
 *   - uidColumn : The *_user_id column
 */
class RenameUserJob extends Job {
	/** @var array Core tables+columns that are being migrated to the `actor` table */
	private static $actorMigratedColumns = [
		'revision.rev_user_text',
		'archive.ar_user_text',
		'ipblocks.ipb_by_text',
		'image.img_user_text',
		'oldimage.oi_user_text',
		'filearchive.fa_user_text',
		'recentchanges.rc_user_text',
		'logging.log_user_text',
	];

	public function __construct( Title $title, $params = [] ) {
		parent::__construct( 'renameUser', $title, $params );
	}

	public function run() {
		global $wgUpdateRowsPerQuery;

		$dbw = wfGetDB( DB_PRIMARY );
		$table = $this->params['table'];
		$column = $this->params['column'];

		// Skip core tables that were migrated to the actor table, even if the
		// field still exists in the database.
		if ( in_array( "$table.$column", self::$actorMigratedColumns, true ) ) {
			wfDebugLog( 'Renameuser',
				"Ignoring job {$this->toString()}, column $table.$column "
					. "actor migration stage lacks WRITE_OLD\n"
			);
			return true;
		}

		// It's not worth a hook to let extensions add themselves to that list.
		// Just check whether the table and column still exist instead.
		if ( !$dbw->tableExists( $table, __METHOD__ ) ) {
			wfDebugLog( 'Renameuser',
				"Ignoring job {$this->toString()}, table $table does not exist\n"
			);
			return true;
		} elseif ( !$dbw->fieldExists( $table, $column, __METHOD__ ) ) {
			wfDebugLog( 'Renameuser',
				"Ignoring job {$this->toString()}, column $table.$column does not exist\n"
			);
			return true;
		}

		$oldname = $this->params['oldname'];
		$newname = $this->params['newname'];
		$count = $this->params['count'];
		if ( isset( $this->params['userID'] ) ) {
			$userID = $this->params['userID'];
			$uidColumn = $this->params['uidColumn'];
		} else {
			$userID = null;
			$uidColumn = null;
		}
		if ( isset( $this->params['timestampColumn'] ) ) {
			$timestampColumn = $this->params['timestampColumn'];
			$minTimestamp = $this->params['minTimestamp'];
			$maxTimestamp = $this->params['maxTimestamp'];
		} else {
			$timestampColumn = null;
			$minTimestamp = null;
			$maxTimestamp = null;
		}
		$uniqueKey = $this->params['uniqueKey'] ?? null;
		$keyId = $this->params['keyId'] ?? null;
		$logId = $this->params['logId'] ?? null;

		if ( $logId ) {
			# Block until the transaction that inserted this job commits.
			# The atomic section is for sanity as FOR UPDATE does not lock in auto-commit mode
			# per http://dev.mysql.com/doc/refman/5.7/en/innodb-locking-reads.html.
			$dbw->startAtomic( __METHOD__ );
			$committed = $dbw->selectField( 'logging',
				'1',
				[ 'log_id' => $logId ],
				__METHOD__,
				[ 'FOR UPDATE' ]
			);
			$dbw->endAtomic( __METHOD__ );
			# If the transaction inserting this job was rolled back, detect that
			if ( $committed === false ) { // rollback happened?
				throw new LogicException( 'Cannot run job if the account rename failed.' );
			}
		}

		# Flush any state snapshot data (and release the lock above)
		$dbw->commit( __METHOD__, 'flush' );

		# Conditions like "*_user_text = 'x'
		$conds = [ $column => $oldname ];
		# If user ID given, add that to condition to avoid rename collisions
		if ( $userID !== null ) {
			$conds[$uidColumn] = $userID;
		}
		# Bound by timestamp if given
		if ( $timestampColumn !== null ) {
			$conds[] = "$timestampColumn >= " . $dbw->addQuotes( $minTimestamp );
			$conds[] = "$timestampColumn <= " . $dbw->addQuotes( $maxTimestamp );
		# Bound by unique key if given (B/C)
		} elseif ( $uniqueKey !== null && $keyId !== null ) {
			$conds[$uniqueKey] = $keyId;
		} else {
			throw new InvalidArgumentException( 'Expected ID batch or time range' );
		}

		$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();

		$affectedCount = 0;
		# Actually update the rows for this job...
		if ( $uniqueKey !== null ) {
			# Select the rows to update by PRIMARY KEY
			$ids = $dbw->selectFieldValues( $table, $uniqueKey, $conds, __METHOD__ );
			# Update these rows by PRIMARY KEY to avoid replica lag
			foreach ( array_chunk( $ids, $wgUpdateRowsPerQuery ) as $batch ) {
				$dbw->commit( __METHOD__, 'flush' );
				$lbFactory->waitForReplication();

				$dbw->update( $table,
					[ $column => $newname ],
					[ $column => $oldname, $uniqueKey => $batch ],
					__METHOD__
				);
				$affectedCount += $dbw->affectedRows();
			}
		} else {
			# Update the chunk of rows directly
			$dbw->update( $table,
				[ $column => $newname ],
				$conds,
				__METHOD__
			);
			$affectedCount += $dbw->affectedRows();
		}

		# Special case: revisions may be deleted while renaming...
		if ( $affectedCount < $count && $table === 'revision' && $timestampColumn !== null ) {
			# If some revisions were not renamed, they may have been deleted.
			# Do a pass on the archive table to get these straglers...
			$ids = $dbw->selectFieldValues(
				'archive',
				'ar_id',
				[
					'ar_user_text' => $oldname,
					'ar_user' => $userID,
					// No user,rev_id index, so use timestamp to bound
					// the rows. This can use the user,timestamp index.
					"ar_timestamp >= '$minTimestamp'",
					"ar_timestamp <= '$maxTimestamp'"
				],
				__METHOD__
			);
			foreach ( array_chunk( $ids, $wgUpdateRowsPerQuery ) as $batch ) {
				$dbw->commit( __METHOD__, 'flush' );
				$lbFactory->waitForReplication();

				$dbw->update(
					'archive',
					[ 'ar_user_text' => $newname ],
					[ 'ar_user_text' => $oldname, 'ar_id' => $batch ],
					__METHOD__
				);
			}
		}
		# Special case: revisions may be restored while renaming...
		if ( $affectedCount < $count && $table === 'archive' && $timestampColumn !== null ) {
			# If some revisions were not renamed, they may have been restored.
			# Do a pass on the revision table to get these straglers...
			$ids = $dbw->selectFieldValues(
				'revision',
				'rev_id',
				[
					'rev_user_text' => $oldname,
					'rev_user' => $userID,
					// No user,rev_id index, so use timestamp to bound
					// the rows. This can use the user,timestamp index.
					"rev_timestamp >= '$minTimestamp'",
					"rev_timestamp <= '$maxTimestamp'"
				],
				__METHOD__
			);
			foreach ( array_chunk( $ids, $wgUpdateRowsPerQuery ) as $batch ) {
				$dbw->commit( __METHOD__, 'flush' );
				$lbFactory->waitForReplication();

				$dbw->update(
					'revision',
					[ 'rev_user_text' => $newname ],
					[ 'rev_user_text' => $oldname, 'rev_id' => $batch ],
					__METHOD__
				);
			}
		}

		return true;
	}
}
