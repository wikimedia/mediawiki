<?php

use MediaWiki\ExternalLinks\LinkFilter;

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script that migrates externallinks data
 *
 * @ingroup Maintenance
 * @since 1.40
 */
class MigrateExternallinks extends LoggedUpdateMaintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription(
			'Migrate externallinks data'
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
		return __CLASS__;
	}

	protected function doDBUpdates() {
		$dbw = $this->getDB( DB_PRIMARY );
		$table = 'externallinks';
		if ( !$dbw->fieldExists( $table, 'el_to', __METHOD__ ) ) {
			$this->output( "Old fields don't exist. There is no need to run this script\n" );
			return true;
		}
		if ( !$dbw->fieldExists( $table, 'el_to_path', __METHOD__ ) ) {
			$this->output( "Run update.php to create the el_to_path column.\n" );
			return false;
		}

		$this->output( "Populating el_to_domain_index and el_to_path columns\n" );
		$updated = 0;

		$highestId = $dbw->newSelectQueryBuilder()
			->select( 'el_id' )
			->from( $table )
			->limit( 1 )
			->caller( __METHOD__ )
			->orderBy( 'el_id', 'DESC' )
			->fetchResultSet()->fetchRow();
		if ( !$highestId ) {
			$this->output( "Page table is empty.\n" );
			return true;
		}
		$highestId = $highestId[0];
		$id = 0;
		while ( $id <= $highestId ) {
			$updated += $this->handleBatch( $id );
			$id += $this->getBatchSize();
		}

		$this->output( "Completed normalization of $table, $updated rows updated.\n" );

		return true;
	}

	private function handleBatch( $lowId ) {
		$batchSize = $this->getBatchSize();
		// BETWEEN is inclusive, let's subtract one.
		$highId = $lowId + $batchSize - 1;
		$dbw = $this->getDB( DB_PRIMARY );
		$updated = 0;
		$res = $dbw->newSelectQueryBuilder()
			->select( [ 'el_id', 'el_to' ] )
			->from( 'externallinks' )
			->where( [
				'el_to_domain_index' => '',
				"el_id BETWEEN $lowId AND $highId"
			] )
			->limit( $batchSize )
			->caller( __METHOD__ )
			->fetchResultSet();
		if ( !$res->numRows() ) {
			return $updated;
		}
		foreach ( $res as $row ) {
			$url = $row->el_to;
			$paths = LinkFilter::makeIndexes( $url );
			if ( !$paths ) {
				continue;
			}
			// just take the first one, we are not sending proto-relative to LinkFilter
			$update = [
				'el_to_domain_index' => substr( $paths[0][0], 0, 255 ),
				'el_to_path' => $paths[0][1]
			];
			$dbw->update( 'externallinks', $update, [ 'el_id' => $row->el_id ], __METHOD__ );
			$updated += $dbw->affectedRows();
		}
		$this->output( "Updated $updated rows\n" );
		// Sleep between batches for replication to catch up
		$this->waitForReplication();
		$sleep = (int)$this->getOption( 'sleep', 0 );
		if ( $sleep > 0 ) {
			sleep( $sleep );
		}
		return $updated;
	}

}

$maintClass = MigrateExternallinks::class;
require_once RUN_MAINTENANCE_IF_MAIN;
