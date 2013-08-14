<?php

require_once __DIR__ . '/Maintenance.php';

class MigrateStore extends Maintenance {
	/** @var DataStore */
	private $from;
	/** @var DataStore */
	private $to;
	/** @var int */
	private $count = 0;

	public function __construct() {
		$this->mDescription = 'Mirates data from one DataStore to another';
		$this->setBatchSize( 500 );
		$this->addArg( 'from', 'Store to migrate from' );
		$this->addArg( 'to', 'Store to migrate to' );
		$this->addOption( 'prefix', 'Optional prefix for keys to migrate', false, true );
		$this->addOption( 'delete', 'Delete migrated data' );
	}

	private function save( $data ) {
		$this->to->setMulti( $data );
		$this->count += count( $data );
		if ( $this->hasOption( 'delete' ) ) {
			$this->from->delete( array_keys( $data ) );
		}
		$this->output( "{$this->count}\n" );
	}

	public function execute() {
		$this->from = DataStore::getStore( $this->getArg( 0 ) );
		$this->to = DataStore::getStore( $this->getArg( 1 ) );
		$prefix = $this->getOption( 'prefix', '' );
		$data = array();
		foreach ( $this->from->getByPrefix( $prefix, true ) as $key => $value ) {
			$data[$key] = $value;
			if ( count( $data ) >= $this->mBatchSize ) {
				$this->save( $data );
				wfWaitForSlaves();
				$data = array();
			}
		}
		if ( $data ) {
			$this->save( $data );
		}
	}
}

$maintClass = 'MigrateStore';
require_once RUN_MAINTENANCE_IF_MAIN;
