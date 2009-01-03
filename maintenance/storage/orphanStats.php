<?php

/**
 * Show some statistics on the blob_orphans table, created with trackBlobs.php
 */
require_once( dirname(__FILE__).'/../commandLine.inc' );

$stats = new OrphanStats;
$stats->execute();

class OrphanStats {
	function getDB( $cluster ) {
		$lb = wfGetLBFactory()->getExternalLB( $cluster );
		return $lb->getConnection( DB_SLAVE );
	}

	function execute() {
		$extDBs = array();
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select( 'blob_orphans', '*', false, __METHOD__ );
		
		$num = 0;
		$totalSize = 0;
		$hashes = array();
		$maxSize = 0;

		foreach ( $res as $boRow ) {
			$extDB = $this->getDB( $boRow->bo_cluster );
			$blobRow = $extDB->selectRow( 'blobs', '*', array( 'blob_id' => $boRow->bo_blob_id ), __METHOD__ );
			
			$num++;
			$size = strlen( $blobRow->blob_text );
			$totalSize += $size;
			$hashes[ sha1( $blobRow->blob_text ) ] = true;
			$maxSize = max( $size, $maxSize );
		}
		unset( $res );

		echo "Number of orphans: $num\n";
		if ( $num > 0 ) {
			echo "Average size: " . round( $totalSize / $num, 0 ) . " bytes\n" .
			"Max size: $maxSize\n" . 
			"Number of unique texts: " . count( $hashes ) . "\n";
		}
	}
}
