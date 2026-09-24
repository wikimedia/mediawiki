<?php

use MediaWiki\Maintenance\LoggedUpdateMaintenance;
use MediaWiki\Maintenance\LoggedUpdateOutcome;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\SelectQueryBuilder;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * @ingroup Maintenance
 */
class CleanupWatchlistTemporaryAccountRows extends LoggedUpdateMaintenance {

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Drops all watchlist rows for temporary accounts, as they do not have watchlists.' );
		$this->setBatchSize( 500 );
	}

	/** @inheritDoc */
	protected function getUpdateKey(): string {
		return __CLASS__;
	}

	/** @inheritDoc */
	protected function doDBUpdates(): LoggedUpdateOutcome {
		$dbr = $this->getReplicaDB();
		$dbw = $this->getPrimaryDB();
		$tempUserConfig = $this->getServiceContainer()->getTempUserConfig();
		$totalRowsDeleted = 0;

		$this->output( "Deleting watchlist table rows for temporary accounts...\n" );

		if ( !$tempUserConfig->isKnown() ) {
			$this->output( "Temporary accounts are not known, so no rows need deleting.\n" );
			return LoggedUpdateOutcome::COMPLETE;
		}

		$lastWatchlistRowId = 0;
		do {
			$this->beginTransactionRound( __METHOD__ );

			$rowIdsToDelete = $dbr->newSelectQueryBuilder()
				->select( 'wl_id' )
				->from( 'watchlist' )
				->join( 'user', null, 'user_id = wl_user' )
				->where( $tempUserConfig->getMatchCondition( $dbr, 'user_name', IExpression::LIKE ) )
				->andWhere( $dbr->expr( 'wl_id', '>', $lastWatchlistRowId ) )
				->orderBy( 'wl_id', SelectQueryBuilder::SORT_ASC )
				->limit( $this->getBatchSize() )
				->caller( __METHOD__ )
				->fetchFieldValues();
			if ( count( $rowIdsToDelete ) ) {
				$dbw->newDeleteQueryBuilder()
					->deleteFrom( 'watchlist' )
					->where( [ 'wl_id' => $rowIdsToDelete ] )
					->caller( __METHOD__ )
					->execute();
				$totalRowsDeleted += $dbw->affectedRows();
				$lastWatchlistRowId = end( $rowIdsToDelete );

				$this->output(
					"... Deleted $totalRowsDeleted rows\n"
				);
			}

			$this->commitTransactionRound( __METHOD__ );
		} while ( count( $rowIdsToDelete ) === $this->getBatchSize() );

		$this->output( "Done. Total rows deleted: $totalRowsDeleted\n" );
		return LoggedUpdateOutcome::COMPLETE;
	}
}

// @codeCoverageIgnoreStart
$maintClass = CleanupWatchlistTemporaryAccountRows::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
