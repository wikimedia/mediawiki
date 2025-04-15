<?php

namespace MediaWiki\Maintenance;

use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\LikeValue;
use Wikimedia\Rdbms\SelectQueryBuilder;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

class FixAutoblockLogTitles extends LoggedUpdateMaintenance {

	public function __construct() {
		parent::__construct();
		$this->addDescription(
			'Finds and fixes unblock log rows in the logging table where the log_title starts with the ' .
			'prefix "User:#". These rows are broken because the target is an autoblock and code expects the "#" ' .
			'character to be the first character in the title (T373929).'
		);
		$this->setBatchSize( 200 );
	}

	/** @inheritDoc */
	protected function doDBUpdates() {
		$this->output( "Fixing log entries with log_title starting with 'User:#'\n" );

		$dbr = $this->getReplicaDB();
		$dbw = $this->getPrimaryDB();

		$totalRowsFixed = 0;
		$lastProcessedLogId = 0;
		do {
			// Get a batch of "unblock" log entries from the logging table. The check for the log_title being broken
			// needs to be performed in batches, as it is expensive when run on the whole logging table on large wikis.
			$logIds = $dbr->newSelectQueryBuilder()
				->select( 'log_id' )
				->from( 'logging' )
				->where( [
					'log_type' => 'block',
					'log_action' => 'unblock',
					$dbr->expr( 'log_id', '>', $lastProcessedLogId ),
				] )
				->limit( $this->getBatchSize() )
				->orderBy( 'log_id', SelectQueryBuilder::SORT_ASC )
				->caller( __METHOD__ )
				->fetchFieldValues();

			if ( count( $logIds ) ) {
				$lastId = end( $logIds );
				$firstId = reset( $logIds );
				$this->output( "...Processing unblock rows with IDs $firstId to $lastId\n" );

				// Apply the LIKE query to find the rows with broken log_title values on the batch of log IDs.
				// If any rows are found, then fix the log_title value.
				$matchingRows = $dbr->newSelectQueryBuilder()
					->select( [ 'log_id', 'log_title' ] )
					->from( 'logging' )
					->where( $dbr->expr(
						'log_title',
						IExpression::LIKE,
						new LikeValue( 'User:#', $dbr->anyString() )
					) )
					->andWhere( [ 'log_id' => $logIds ] )
					->limit( $this->getBatchSize() )
					->caller( __METHOD__ )
					->fetchResultSet();

				foreach ( $matchingRows as $row ) {
					$dbw->newUpdateQueryBuilder()
						->update( 'logging' )
						->set( [ 'log_title' => substr( $row->log_title, strlen( 'User:' ) ) ] )
						->where( [ 'log_id' => $row->log_id ] )
						->caller( __METHOD__ )
						->execute();
					$totalRowsFixed += $dbw->affectedRows();
				}

				$this->waitForReplication();
			}

			$lastProcessedLogId = end( $logIds );
		} while ( count( $logIds ) );

		// Say we are done. Only output the total rows fixed if we found rows to fix, otherwise it may be confusing
		// to see that no rows were fixed (and might imply that there are still rows to fix).
		$this->output( "done." );
		if ( $totalRowsFixed ) {
			$this->output( " Fixed $totalRowsFixed rows." );
		}
		$this->output( "\n" );

		return true;
	}

	/** @inheritDoc */
	protected function getUpdateKey() {
		return __CLASS__;
	}
}

// @codeCoverageIgnoreStart
$maintClass = FixAutoblockLogTitles::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
