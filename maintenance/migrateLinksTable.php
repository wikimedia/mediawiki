<?php

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

use MediaWiki\Maintenance\LoggedUpdateMaintenance;
use MediaWiki\Title\TitleValue;

/**
 * Maintenance script that populates normalization column in links tables.
 *
 * @ingroup Maintenance
 * @since 1.39
 */
class MigrateLinksTable extends LoggedUpdateMaintenance {
	/** @var int */
	private $totalUpdated = 0;
	/** @var int */
	private $lastProgress = 0;

	public function __construct() {
		parent::__construct();
		$this->addDescription(
			'Populates normalization column in links tables.'
		);
		$this->addOption(
			'table',
			'Table name. Like pagelinks.',
			true,
			true
		);
		$this->addOption(
			'sleep',
			'Sleep time (in seconds) between every batch. Default: 0',
			false,
			true
		);
		$this->setBatchSize( 1000 );
	}

	/** @inheritDoc */
	protected function getUpdateKey() {
		return __CLASS__ . $this->getOption( 'table', '' );
	}

	/** @inheritDoc */
	protected function doDBUpdates() {
		$dbw = $this->getDB( DB_PRIMARY );
		$mapping = \MediaWiki\Linker\LinksMigration::$mapping;
		$table = $this->getOption( 'table', '' );
		if ( !isset( $mapping[$table] ) ) {
			$this->output( "Mapping for this table doesn't exist yet.\n" );
			return false;
		}
		$targetColumn = $mapping[$table]['target_id'];
		if ( !$dbw->fieldExists( $table, $mapping[$table]['title'], __METHOD__ ) ) {
			$this->output( "Old fields don't exist. There is no need to run this script\n" );
			return true;
		}
		if ( !$dbw->fieldExists( $table, $targetColumn, __METHOD__ ) ) {
			$this->output( "Run update.php to create the $targetColumn column.\n" );
			return false;
		}
		if ( !$dbw->tableExists( 'linktarget', __METHOD__ ) ) {
			$this->output( "Run update.php to create the linktarget table.\n" );
			return true;
		}

		$this->output( "Populating the $targetColumn column\n" );
		$updated = 0;

		$highestPageId = $dbw->newSelectQueryBuilder()
			->select( 'page_id' )
			->from( 'page' )
			->limit( 1 )
			->caller( __METHOD__ )
			->orderBy( 'page_id', 'DESC' )
			->fetchResultSet()->fetchRow();
		if ( !$highestPageId ) {
			$this->output( "Page table is empty.\n" );
			return true;
		}
		$highestPageId = $highestPageId[0];
		$pageId = 0;
		while ( $pageId <= $highestPageId ) {
			// Given the indexes and the structure of links tables,
			// we need to split the update into batches of pages.
			// Otherwise the queries will take a really long time in production and cause read-only.
			$this->handlePageBatch( $pageId, $mapping, $table );
			$pageId += $this->getBatchSize();
		}

		$this->output( "Completed normalization of $table, {$this->totalUpdated} rows updated.\n" );

		return true;
	}

	private function handlePageBatch( int $lowPageId, array $mapping, string $table ) {
		$batchSize = $this->getBatchSize();
		$targetColumn = $mapping[$table]['target_id'];
		$pageIdColumn = $mapping[$table]['page_id'];
		// range is inclusive, let's subtract one.
		$highPageId = $lowPageId + $batchSize - 1;
		$dbw = $this->getPrimaryDB();

		while ( true ) {
			$res = $dbw->newSelectQueryBuilder()
				->select( [ $mapping[$table]['ns'], $mapping[$table]['title'] ] )
				->from( $table )
				->where( [
					$targetColumn => [ null, 0 ],
					$dbw->expr( $pageIdColumn, '>=', $lowPageId ),
					$dbw->expr( $pageIdColumn, '<=', $highPageId ),
				] )
				->limit( 1 )
				->caller( __METHOD__ )
				->fetchResultSet();
			if ( !$res->numRows() ) {
				break;
			}
			$row = $res->fetchRow();
			$ns = $row[$mapping[$table]['ns']];
			$titleString = $row[$mapping[$table]['title']];
			$title = new TitleValue( (int)$ns, $titleString );
			$id = $this->getServiceContainer()->getLinkTargetLookup()->acquireLinkTargetId( $title, $dbw );
			$dbw->newUpdateQueryBuilder()
				->update( $table )
				->set( [ $targetColumn => $id ] )
				->where( [
					$targetColumn => [ null, 0 ],
					$mapping[$table]['ns'] => $ns,
					$mapping[$table]['title'] => $titleString,
					$dbw->expr( $pageIdColumn, '>=', $lowPageId ),
					$dbw->expr( $pageIdColumn, '<=', $highPageId ),
				] )
				->caller( __METHOD__ )->execute();
			$this->updateProgress( $dbw->affectedRows(), $lowPageId, $highPageId, $ns, $titleString );
		}
	}

	/**
	 * Update the total progress metric. If enough progress has been made,
	 * report to the user and do a replication wait.
	 *
	 * @param int $updatedInThisBatch
	 * @param int $lowPageId
	 * @param int $highPageId
	 * @param int $ns
	 * @param string $titleString
	 */
	private function updateProgress( $updatedInThisBatch, $lowPageId, $highPageId, $ns, $titleString ) {
		$this->totalUpdated += $updatedInThisBatch;
		if ( $this->totalUpdated >= $this->lastProgress + $this->getBatchSize() ) {
			$this->lastProgress = $this->totalUpdated;
			$this->output( "Updated {$this->totalUpdated} rows, " .
				"at page_id $lowPageId-$highPageId title $ns:$titleString\n" );
			$this->waitForReplication();
			// Sleep between batches for replication to catch up
			$sleep = (int)$this->getOption( 'sleep', 0 );
			if ( $sleep > 0 ) {
				sleep( $sleep );
			}
		}
	}

}

// @codeCoverageIgnoreStart
$maintClass = MigrateLinksTable::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
