<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 */

use MediaWiki\Maintenance\LoggedUpdateMaintenance;

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
		$this->setBatchSize( 1000 );
		$this->addOption(
			'sleep',
			'Sleep time (in seconds) between every batch, defaults to zero',
			false,
			true
		);
		$this->addOption( 'populate-only', 'Do not update change_tag_def table' );
		$this->addOption( 'set-user-tags-only', 'Only update ctd_user_defined from valid_tag table' );
	}

	/** @inheritDoc */
	protected function doDBUpdates() {
		$this->setBatchSize( $this->getOption( 'batch-size', $this->getBatchSize() ) );

		$dbw = $this->getDB( DB_PRIMARY );
		if ( $dbw->fieldExists(
				'change_tag',
				'ct_tag',
				__METHOD__
			)
		) {
			if ( $this->hasOption( 'set-user-tags-only' ) ) {
				$this->setUserDefinedTags();
				return true;
			}
			if ( !$this->hasOption( 'populate-only' ) ) {
				$this->updateCountTag();
			}
			$this->backpopulateChangeTagId();
			$this->setUserDefinedTags();
		} else {
			$this->updateCountTagId();
		}

		// TODO: Implement
		// $this->cleanZeroCountRows();

		return true;
	}

	private function setUserDefinedTags() {
		$dbw = $this->getDB( DB_PRIMARY );

		$userTags = null;
		if ( $dbw->tableExists( 'valid_tag', __METHOD__ ) ) {
			$userTags = $dbw->newSelectQueryBuilder()
				->select( 'vt_tag' )
				->from( 'valid_tag' )
				->caller( __METHOD__ )->fetchFieldValues();
		}

		if ( !$userTags ) {
			$this->output( "No user defined tags to set, moving on...\n" );
			return;
		}

		if ( $this->hasOption( 'dry-run' ) ) {
			$this->output(
				'These tags will have ctd_user_defined=1 : ' . implode( ', ', $userTags ) . "\n"
			);
			return;
		}

		$dbw->newUpdateQueryBuilder()
			->update( 'change_tag_def' )
			->set( [ 'ctd_user_defined' => 1 ] )
			->where( [ 'ctd_name' => $userTags ] )
			->caller( __METHOD__ )
			->execute();
		$this->waitForReplication();
		$this->output( "Finished setting user defined tags in change_tag_def table\n" );
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

	private function updateCountTag() {
		$dbr = $this->getReplicaDB();

		// This query can be pretty expensive, don't run it on master
		$res = $dbr->newSelectQueryBuilder()
			->select( [ 'ct_tag', 'hitcount' => 'count(*)' ] )
			->from( 'change_tag' )
			->groupBy( 'ct_tag' )
			->caller( __METHOD__ )->fetchResultSet();

		$dbw = $this->getPrimaryDB();

		foreach ( $res as $row ) {
			// Hygiene check
			if ( !$row->ct_tag ) {
				continue;
			}

			if ( $this->hasOption( 'dry-run' ) ) {
				$this->output( 'This row will be updated: ' . $row->ct_tag . $row->hitcount . "\n" );
				continue;
			}
			$dbw->newInsertQueryBuilder()
				->insertInto( 'change_tag_def' )
				->row( [
					'ctd_name' => $row->ct_tag,
					'ctd_user_defined' => 0,
					'ctd_count' => $row->hitcount
				] )
				->onDuplicateKeyUpdate()
				->uniqueIndexFields( [ 'ctd_name' ] )
				->set( [ 'ctd_count' => $row->hitcount ] )
				->caller( __METHOD__ )->execute();
		}
		$this->waitForReplication();
	}

	private function backpopulateChangeTagId() {
		$dbr = $this->getReplicaDB();
		$changeTagDefs = $dbr->newSelectQueryBuilder()
			->select( [ 'ctd_name', 'ctd_id' ] )
			->from( 'change_tag_def' )
			->orderBy( 'ctd_id' )
			->caller( __METHOD__ )->fetchResultSet();

		foreach ( $changeTagDefs as $row ) {
			$this->backpopulateChangeTagPerTag( $row->ctd_name, $row->ctd_id );
		}
	}

	private function backpopulateChangeTagPerTag( string $tagName, int $tagId ) {
		$dbr = $this->getReplicaDB();
		$dbw = $this->getPrimaryDB();
		$sleep = (int)$this->getOption( 'sleep', 0 );
		$lastId = 0;
		$this->output( "Starting to add ct_tag_id = {$tagId} for ct_tag = {$tagName}\n" );
		while ( true ) {
			// Given that indexes might not be there, it's better to use replica
			$ids = $dbr->newSelectQueryBuilder()
				->select( 'ct_id' )
				->from( 'change_tag' )
				->where( [ 'ct_tag' => $tagName, 'ct_tag_id' => null, $dbr->expr( 'ct_id', '>', $lastId ) ] )
				->orderBy( 'ct_id' )
				->limit( $this->getBatchSize() )
				->caller( __METHOD__ )->fetchFieldValues();

			if ( !$ids ) {
				break;
			}
			$lastId = end( $ids );

			if ( $this->hasOption( 'dry-run' ) ) {
				$this->output(
					"These ids will be changed to have \"{$tagId}\" as tag id: " . implode( ', ', $ids ) . "\n"
				);
				continue;
			} else {
				$this->output( "Updating ct_tag_id = {$tagId} up to row ct_id = {$lastId}\n" );
			}

			$dbw->newUpdateQueryBuilder()
				->update( 'change_tag' )
				->set( [ 'ct_tag_id' => $tagId ] )
				->where( [ 'ct_id' => $ids ] )
				->caller( __METHOD__ )
				->execute();

			$this->waitForReplication();
			if ( $sleep > 0 ) {
				sleep( $sleep );
			}
		}

		$this->output( "Finished adding ct_tag_id = {$tagId} for ct_tag = {$tagName}\n" );
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
