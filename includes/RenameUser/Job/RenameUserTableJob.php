<?php

namespace MediaWiki\RenameUser\Job;

use InvalidArgumentException;
use MediaWiki\Config\Config;
use MediaWiki\JobQueue\Job;
use MediaWiki\MainConfigNames;
use MediaWiki\Title\Title;
use Wikimedia\Rdbms\ILBFactory;

/**
 * Custom job to perform updates on tables in busier environments
 *
 * Job parameters include:
 *   - table     : DB table to update
 *   - column    : The *_user_text column to update
 *   - oldname   : The old user name
 *   - newname   : The new user name
 *   - count     : The expected number of rows to update in this batch
 *
 * Additionally, one of the following groups of parameters must be set:
 * a) The timestamp based rename parameters:
 *   - timestampColumn : The *_timestamp column
 *   - minTimestamp    : The minimum bound of the timestamp column range for this batch
 *   - maxTimestamp    : The maximum bound of the timestamp column range for this batch
 *   - uniqueKey       : A column that is unique (preferably the PRIMARY KEY) [optional]
 * b) The unique key based rename parameters:
 *   - uniqueKey : A column that is unique (preferably the PRIMARY KEY)
 *   - keyId     : A list of values for this column to determine rows to update for this batch
 *
 * To avoid some race conditions, the following parameters should be set:
 *   - userID    : The ID of the user to update
 *   - uidColumn : The *_user_id column
 */
class RenameUserTableJob extends Job {
	/** @var int */
	private $updateRowsPerQuery;

	/** @var ILBFactory */
	private $lbFactory;

	public function __construct(
		Title $title,
		array $params,
		Config $config,
		ILBFactory $lbFactory
	) {
		parent::__construct( 'renameUserTable', $title, $params );

		$this->updateRowsPerQuery = $config->get( MainConfigNames::UpdateRowsPerQuery );
		$this->lbFactory = $lbFactory;
	}

	/** @inheritDoc */
	public function run() {
		$dbw = $this->lbFactory->getPrimaryDatabase();
		$ticket = $this->lbFactory->getEmptyTransactionTicket( __METHOD__ );
		$table = $this->params['table'];
		$column = $this->params['column'];

		$oldname = $this->params['oldname'];
		$newname = $this->params['newname'];
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

		# Conditions like "*_user_text = 'x'
		$conds = [ $column => $oldname ];
		# If user ID given, add that to condition to avoid rename collisions
		if ( $userID !== null ) {
			$conds[$uidColumn] = $userID;
		}
		# Bound by timestamp if given
		if ( $timestampColumn !== null ) {
			$conds[] = $dbw->expr( $timestampColumn, '>=', $minTimestamp );
			$conds[] = $dbw->expr( $timestampColumn, '<=', $maxTimestamp );
		# Bound by unique key if given (B/C)
		} elseif ( $uniqueKey !== null && $keyId !== null ) {
			$conds[$uniqueKey] = $keyId;
		} else {
			throw new InvalidArgumentException( 'Expected ID batch or time range' );
		}

		# Actually update the rows for this job...
		if ( $uniqueKey !== null ) {
			// Select the rows to update by PRIMARY KEY
			$ids = $dbw->newSelectQueryBuilder()
				->select( $uniqueKey )
				->from( $table )
				->where( $conds )
				->caller( __METHOD__ )->fetchFieldValues();
			# Update these rows by PRIMARY KEY to avoid replica lag
			foreach ( array_chunk( $ids, $this->updateRowsPerQuery ) as $batch ) {
				$this->lbFactory->commitAndWaitForReplication( __METHOD__, $ticket );

				$dbw->newUpdateQueryBuilder()
					->update( $table )
					->set( [ $column => $newname ] )
					->where( [ $column => $oldname, $uniqueKey => $batch ] )
					->caller( __METHOD__ )->execute();
			}
		} else {
			# Update the chunk of rows directly
			$dbw->newUpdateQueryBuilder()
				->update( $table )
				->set( [ $column => $newname ] )
				->where( $conds )
				->caller( __METHOD__ )->execute();
		}

		return true;
	}
}
/** @deprecated class alias since 1.43 */
class_alias( RenameUserTableJob::class, 'RenameUserJob' );
