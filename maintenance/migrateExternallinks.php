<?php

use MediaWiki\Deferred\LinksUpdate\ExternalLinksTable;
use MediaWiki\ExternalLinks\LinkFilter;
use MediaWiki\Maintenance\LoggedUpdateMaintenance;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

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

	/** @inheritDoc */
	protected function getUpdateKey() {
		return __CLASS__;
	}

	/** @inheritDoc */
	protected function doDBUpdates() {
		/** @var \Wikimedia\Rdbms\Database $dbw */
		$dbw = $this->getServiceContainer()->getConnectionProvider()->getPrimaryDatabase(
			ExternalLinksTable::VIRTUAL_DOMAIN
		);
		'@phan-var \Wikimedia\Rdbms\Database $dbw';
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

	private function handleBatch( int $lowId ): int {
		$batchSize = $this->getBatchSize();
		// range is inclusive, let's subtract one.
		$highId = $lowId + $batchSize - 1;
		$dbw = $this->getServiceContainer()->getConnectionProvider()->getPrimaryDatabase(
			ExternalLinksTable::VIRTUAL_DOMAIN
		);
		$updated = 0;
		$res = $dbw->newSelectQueryBuilder()
			->select( [ 'el_id', 'el_to' ] )
			->from( 'externallinks' )
			->where( [
				'el_to_domain_index' => '',
				$dbw->expr( 'el_id', '>=', $lowId ),
				$dbw->expr( 'el_id', '<=', $highId ),
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
			$dbw->newUpdateQueryBuilder()
				->update( 'externallinks' )
				// just take the first one, we are not sending proto-relative to LinkFilter
				->set( [
					'el_to_domain_index' => substr( $paths[0][0], 0, 255 ),
					'el_to_path' => $paths[0][1]
				] )
				->where( [ 'el_id' => $row->el_id ] )
				->caller( __METHOD__ )->execute();

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

// @codeCoverageIgnoreStart
$maintClass = MigrateExternallinks::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
