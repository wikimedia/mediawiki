<?php

use MediaWiki\MediaWikiServices;

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script that populates normalization column in links tables.
 *
 * @ingroup Maintenance
 * @since 1.39
 */
class MigrateLinksTable extends LoggedUpdateMaintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription(
			'Populates normalization column in links tables.'
		);
		$this->addOption(
			'table',
			'Table name. Like templatelinks.',
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

	protected function getUpdateKey() {
		return __CLASS__ . $this->getOption( 'table', '' );
	}

	protected function doDBUpdates() {
		$dbw = $this->getDB( DB_PRIMARY );
		$mapping = \MediaWiki\Linker\LinksMigration::$mapping;
		$table = $this->getOption( 'table', '' );
		if ( !isset( $mapping[$table] ) ) {
			$this->output( "Mapping for this table doesn't exist yet.\n" );
			return false;
		}
		$targetColumn = $mapping[$table]['target_id'];
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
			$updated += $this->handlePageBatch( $pageId, $mapping, $table );
			$pageId += $this->getBatchSize();
		}

		$this->output( "Completed normalization of $table, $updated rows updated.\n" );

		return true;
	}

	private function handlePageBatch( $lowPageId, $mapping, $table ) {
		$batchSize = $this->getBatchSize();
		$targetColumn = $mapping[$table]['target_id'];
		$pageIdColumn = $mapping[$table]['page_id'];
		// BETWEEN is inclusive, let's subtract one.
		$highPageId = $lowPageId + $batchSize - 1;
		$dbw = $this->getDB( DB_PRIMARY );
		$updated = 0;

		while ( true ) {
			$res = $dbw->newSelectQueryBuilder()
				->select( [ $mapping[$table]['ns'], $mapping[$table]['title'] ] )
				->from( $table )
				->where( [
					$targetColumn => null,
					"$pageIdColumn BETWEEN $lowPageId AND $highPageId"
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
			$this->output( "Starting backfill of $ns:$titleString " .
				"title on pages between $lowPageId and $highPageId\n" );
			$id = MediaWikiServices::getInstance()->getLinkTargetLookup()->acquireLinkTargetId( $title, $dbw );
			$conds = [
				$targetColumn => null,
				$mapping[$table]['ns'] => $ns,
				$mapping[$table]['title'] => $titleString,
				"$pageIdColumn BETWEEN $lowPageId AND $highPageId"
			];
			$dbw->update( $table, [ $targetColumn => $id ], $conds, __METHOD__ );
			$updatedInThisBatch = $dbw->affectedRows();
			$updated += $updatedInThisBatch;
			$this->output( "Updated $updatedInThisBatch rows\n" );
			// Sleep between batches for replication to catch up
			MediaWikiServices::getInstance()->getDBLoadBalancerFactory()->waitForReplication();
			$sleep = (int)$this->getOption( 'sleep', 0 );
			if ( $sleep > 0 ) {
				sleep( $sleep );
			}
		}
		return $updated;
	}

}

$maintClass = MigrateLinksTable::class;
require_once RUN_MAINTENANCE_IF_MAIN;
