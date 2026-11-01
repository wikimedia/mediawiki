<?php
/**
 * @license GPL-2.0-or-later
 */

use MediaWiki\Maintenance\LoggedUpdateMaintenance;
use MediaWiki\Maintenance\LoggedUpdateOutcome;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Populate and improve accuracy of change_tag_def statistics.
 *
 * @ingroup Maintenance
 */
class PopulateChangeTagDef extends LoggedUpdateMaintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Populate and improve accuracy of change_tag_def statistics' );
		$this->addOption( 'dry-run', 'Print debug info instead of actually deleting' );
	}

	/** @inheritDoc */
	protected function doDBUpdates(): LoggedUpdateOutcome {
		$this->updateCountTagId();

		// TODO: Implement
		// $this->cleanZeroCountRows();

		return $this->hasOption( 'dry-run' ) ? LoggedUpdateOutcome::SIMULATED : LoggedUpdateOutcome::COMPLETE;
	}

	private function updateCountTagId() {
		$dbr = $this->getReplicaDB();

		// This query can be pretty expensive, don't run it on master
		$res = $dbr->newSelectQueryBuilder()
			->select( [ 'ct_tag_id', 'hitcount' => 'count(*)' ] )
			->from( 'change_tag' )
			->groupBy( 'ct_tag_id' )
			->caller( __METHOD__ )->fetchResultSet();

		$dbw = $this->getPrimaryDB();

		foreach ( $res as $row ) {
			if ( !$row->ct_tag_id ) {
				continue;
			}

			if ( $this->hasOption( 'dry-run' ) ) {
				$this->output( 'This row will be updated: id ' . $row->ct_tag_id . ', ' . $row->hitcount . " hits\n" );
				continue;
			}

			$dbw->newUpdateQueryBuilder()
				->update( 'change_tag_def' )
				->set( [ 'ctd_count' => $row->hitcount ] )
				->where( [ 'ctd_id' => $row->ct_tag_id ] )
				->caller( __METHOD__ )
				->execute();
		}
		$this->waitForReplication();
	}

	/** @inheritDoc */
	protected function getUpdateKey() {
		return __CLASS__;
	}
}

// @codeCoverageIgnoreStart
$maintClass = PopulateChangeTagDef::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
