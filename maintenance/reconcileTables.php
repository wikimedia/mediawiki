<?php

use MediaWiki\MediaWikiServices;

require_once __DIR__ . '/Maintenance.php';

class ReconcileTables extends Maintenance {

	public function __construct() {
		parent::__construct();

		$this->addDescription(
			'Compare rows between two system and fix any difference from the source'
		);

		$this->addOption( 'table', 'Table name', true, true );
		$this->addOption( 'primaryKey', 'Primary key column', true, true, 'pk' );
		$this->addOption( 'source', 'Source virtual domain', false, true );
		$this->addOption( 'target', 'Target virtual domain', true, true );
		$this->addOption( 'dry', 'Dry mode' );
		$this->setBatchSize( 500 );
	}

	public function execute() {
		$table = $this->getOption( 'table' );
		$primaryKey = $this->getOption( 'primaryKey' );
		$sourceDomain = $this->getOption( 'source', false );
		$targetDomain = $this->getOption( 'target' );
		$batchSize = $this->getBatchSize();
		$dry = $this->getOption( 'dry', false );

		$services = MediaWikiServices::getInstance();
		$lbFactory = $services->getDBLoadBalancerFactory();

		$dbrSource = $lbFactory->getReplicaDatabase( $sourceDomain );
		$dbrTarget = $lbFactory->getReplicaDatabase( $targetDomain );
		$dbwTarget = $lbFactory->getPrimaryDatabase( $targetDomain );

		$this->output( "Reading rows from source virtual domain: $sourceDomain\n" );

		$lastKey = 0;

		$inserted = 0;
		$updated = 0;
		$deleted = 0;

		$maxValue = $dbrSource->newSelectQueryBuilder()
			->select( 'MAX(' . $primaryKey . ')' )
			->from( $table )
			->caller( __METHOD__ )->fetchField();

		while ( $maxValue > $lastKey ) {
			$query = $dbrSource->newSelectQueryBuilder()
				->select( '*' )
				->from( $table )
				->orderBy( $primaryKey )
				->limit( $batchSize );

			if ( $lastKey !== 0 ) {
				$query->where( $dbrSource->expr( $primaryKey, '>', $lastKey ) );
			}
			$resSource = $query->caller( __METHOD__ )->fetchResultSet();
			$resTarget = $query->connection( $dbrTarget )->caller( __METHOD__ )->fetchResultSet();

			if ( !$resSource->numRows() && !$resTarget->numRows() ) {
				break;
			}

			$sourceRows = [];
			$targetRows = [];
			foreach ( $resSource as $row ) {
				$row = (array)$row;
				$sourceRows[$row[$primaryKey]] = $row;
			}
			foreach ( $resTarget as $row ) {
				$row = (array)$row;
				$targetRows[$row[$primaryKey]] = $row;
			}
			$this->beginTransactionRound( __METHOD__ );
			foreach ( array_diff_key( $sourceRows, $targetRows ) as $pk => $row ) {
				$inserted++;
				if ( !$dry ) {
					// @phan-suppress-next-line SecurityCheck-SQLInjection
					$dbwTarget->newInsertQueryBuilder()
						->insertInto( $table )
						->ignore()
						->row( $row )
						->caller( __METHOD__ )->execute();
				}
			}

			foreach ( array_diff_key( $targetRows, $sourceRows ) as $pk => $row ) {
				$deleted++;
				if ( !$dry ) {
					// @phan-suppress-next-line SecurityCheck-SQLInjection
					$dbwTarget->newDeleteQueryBuilder()
						->deleteFrom( $table )
						->where( [ $primaryKey => $pk ] )
						->caller( __METHOD__ )->execute();
				}
			}

			foreach ( $sourceRows as $pk => $row ) {
				$lastKey = $row[$primaryKey];
				if ( !array_key_exists( $pk, $targetRows ) ) {
					continue;
				}
				if ( $row != $targetRows[$pk] ) {
					$updated++;
					if ( !$dry ) {
						// @phan-suppress-next-line SecurityCheck-SQLInjection
						$dbwTarget->newUpdateQueryBuilder()
							->update( $table )
							->set( $row )
							->where( [ $primaryKey => $row[$primaryKey] ] )
							->caller( __METHOD__ )->execute();
					}
				}
			}
			if ( $dry ) {
				$this->output( "Batch done up to $lastKey. " .
					"Would insert $inserted rows, delete $deleted rows, update $updated rows.\n" );
			} else {
				$this->output( "Batch done up to $lastKey. " .
					"Inserted $inserted rows, deleted $deleted rows, updated $updated rows.\n" );
			}
			$this->commitTransactionRound( __METHOD__ );
		}

		$this->output( "Done.\n" );
	}
}

$maintClass = ReconcileTables::class;
require_once RUN_MAINTENANCE_IF_MAIN;
