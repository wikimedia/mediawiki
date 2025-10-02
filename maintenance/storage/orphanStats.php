<?php
/**
 * Show some statistics on the blob_orphans table, created with trackBlobs.php.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Maintenance ExternalStorage
 */

use MediaWiki\Maintenance\Maintenance;
use Wikimedia\Rdbms\IDatabase;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/../Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script that shows some statistics on the blob_orphans table,
 * created with trackBlobs.php.
 *
 * @ingroup Maintenance ExternalStorage
 */
class OrphanStats extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription(
			"Show some statistics on the blob_orphans table, created with trackBlobs.php" );
	}

	protected function getExternalDB( int $db, string $cluster ): IDatabase {
		$lbFactory = $this->getServiceContainer()->getDBLoadBalancerFactory();
		$lb = $lbFactory->getExternalLB( $cluster );

		return $lb->getMaintenanceConnectionRef( $db );
	}

	public function execute() {
		if ( !$this->getDB( DB_PRIMARY )->tableExists( 'blob_orphans', __METHOD__ ) ) {
			$this->fatalError( "blob_orphans doesn't seem to exist, need to run trackBlobs.php first" );
		}
		$dbr = $this->getReplicaDB();
		$res = $dbr->newSelectQueryBuilder()
			->select( '*' )
			->from( 'blob_orphans' )
			->caller( __METHOD__ )->fetchResultSet();

		$num = 0;
		$totalSize = 0;
		$hashes = [];
		$maxSize = 0;

		foreach ( $res as $row ) {
			$extDB = $this->getExternalDB( DB_REPLICA, $row->bo_cluster );
			$blobRow = $extDB->newSelectQueryBuilder()
				->select( '*' )
				->from( 'blobs' )
				->where( [ 'blob_id' => $row->bo_blob_id ] )
				->caller( __METHOD__ )->fetchRow();

			$num++;
			$size = strlen( $blobRow->blob_text );
			$totalSize += $size;
			$hashes[sha1( $blobRow->blob_text )] = true;
			$maxSize = max( $size, $maxSize );
		}
		unset( $res );

		$this->output( "Number of orphans: $num\n" );
		if ( $num > 0 ) {
			$this->output( "Average size: " . round( $totalSize / $num, 0 ) . " bytes\n" .
				"Max size: $maxSize\n" .
				"Number of unique texts: " . count( $hashes ) . "\n" );
		}
	}
}

// @codeCoverageIgnoreStart
$maintClass = OrphanStats::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
