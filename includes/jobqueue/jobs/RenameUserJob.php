<?php

use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\Title\Title;
use Psr\Log\LoggerInterface;
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
	/** @var int */
	private $updateRowsPerQuery;

	/** @var ILBFactory */
	private $lbFactory;

	/** @var LoggerInterface */
	private $logger;

	public function __construct(
		Title $title,
		$params,
		Config $config,
		ILBFactory $lbFactory
	) {
		parent::__construct( 'renameUser', $title, $params );

		$this->updateRowsPerQuery = $config->get( MainConfigNames::UpdateRowsPerQuery );
		$this->lbFactory = $lbFactory;
		$this->logger = LoggerFactory::getInstance( 'Renameuser' );
	}

	public function run() {
		$dbw = $this->lbFactory->getMainLB()->getMaintenanceConnectionRef( DB_PRIMARY );
		$table = $this->params['table'];
		$column = $this->params['column'];

		// It's not worth a hook to let extensions add themselves to that list.
		// Just check whether the table and column still exist instead.
		if ( !$dbw->tableExists( $table, __METHOD__ ) ) {
			$this->logger->info(
				"Ignoring job {$this->toString()}, table $table does not exist" );
			return true;
		} elseif ( !$dbw->fieldExists( $table, $column, __METHOD__ ) ) {
			$this->logger->info(
				"Ignoring job {$this->toString()}, column $table.$column does not exist" );
			return true;
		}

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
			$conds[] = "$timestampColumn >= " . $dbw->addQuotes( $minTimestamp );
			$conds[] = "$timestampColumn <= " . $dbw->addQuotes( $maxTimestamp );
		# Bound by unique key if given (B/C)
		} elseif ( $uniqueKey !== null && $keyId !== null ) {
			$conds[$uniqueKey] = $keyId;
		} else {
			throw new InvalidArgumentException( 'Expected ID batch or time range' );
		}

		# Actually update the rows for this job...
		if ( $uniqueKey !== null ) {
			# Select the rows to update by PRIMARY KEY
			$ids = $dbw->selectFieldValues( $table, $uniqueKey, $conds, __METHOD__ );
			# Update these rows by PRIMARY KEY to avoid replica lag
			foreach ( array_chunk( $ids, $this->updateRowsPerQuery ) as $batch ) {
				$dbw->commit( __METHOD__, 'flush' );
				$this->lbFactory->waitForReplication();

				$dbw->update( $table,
					[ $column => $newname ],
					[ $column => $oldname, $uniqueKey => $batch ],
					__METHOD__
				);
			}
		} else {
			# Update the chunk of rows directly
			$dbw->update( $table,
				[ $column => $newname ],
				$conds,
				__METHOD__
			);
		}

		return true;
	}
}
