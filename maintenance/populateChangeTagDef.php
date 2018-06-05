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

require_once __DIR__ . '/Maintenance.php';

/**
 * Populate and improve accuracy of change_tag_def statistics.
 *
 * @ingroup Maintenance
 */
class PopulateChangeTagDef extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Populate and improve accuracy of change_tag_def statistics' );
		$this->addOption( 'dry-run', 'Print debug info instead of actually deleting' );
		$this->setBatchSize( 1000 );
		$this->addOption(
			'sleep',
			'Sleep time (in seconds) between every batch',
			false,
			true
		);
	}

	public function execute() {
		global $wgChangeTagsSchemaMigrationStage;
		$this->setBatchSize( $this->getOption( 'batch-size', $this->getBatchSize() ) );

		$this->countDown( 5 );
		if ( $wgChangeTagsSchemaMigrationStage < MIGRATION_NEW ) {
			$this->updateCountTag();
			$this->backpopulateChangeTag();
		} else {
			$this->updateCountTagId();
		}

		// TODO: Implement
		// $this->cleanZeroCountRows();
	}

	private function updateCountTagId() {
		$lb = MediaWiki\MediaWikiServices::getInstance()->getDBLoadBalancer();
		$dbr = $lb->getConnection( DB_REPLICA );

		// This query can be pretty expensive, don't run it on master
		$res = $dbr->select(
			'change_tag',
			[ 'ct_tag_id', 'hitcount' => 'count(*)' ],
			[],
			__METHOD__,
			[ 'GROUP BY' => 'ct_tag_id' ]
		);

		$dbw = $lb->getConnection( DB_MASTER );

		$dbw->startAtomic();
		foreach ( $res as $row ) {
			if ( !$row->ct_tag_id ) {
				continue;
			}

			if ( $this->hasOption( 'dry-run' ) ) {
				$this->output( 'This row will be updated: ' . implode( ', ', $row ) . "\n" );
				continue;
			}

			$dbw->update(
				'change_tag_def',
				[ 'ctd_count' => $row->hitcount ],
				[ 'ctd_id' => $row->ct_tag_id ]
			);
		}
		$dbw->endAtomic();
		MediaWiki\MediaWikiServices::getInstance()->getDBLoadBalancerFactory()->waitForReplication();
	}

	private function updateCountTag() {
		$lb = MediaWiki\MediaWikiServices::getInstance()->getDBLoadBalancer();
		$dbr = $lb->getConnection( DB_REPLICA );

		// This query can be pretty expensive, don't run it on master
		$res = $dbr->select(
			'change_tag',
			[ 'ct_tag', 'hitcount' => 'count(*)' ],
			[],
			__METHOD__,
			[ 'GROUP BY' => 'ct_tag' ]
		);

		$dbw = $lb->getConnection( DB_MASTER );

		$dbw->startAtomic( __METHOD__ );
		foreach ( $res as $row ) {

			// Hygiene check
			if ( !$row->ct_tag ) {
				continue;
			}

			if ( $this->hasOption( 'dry-run' ) ) {
				$this->output( 'This row will be updated: ' . $row->ct_tag . $row->hitcount . "\n" );
				continue;
			}

			$dbw->upsert(
				'change_tag_def',
				[
					'ctd_name' => $row->ct_tag,
					'ctd_user_defined' => 0,
					'ctd_count' => $row->hitcount
				],
				[ 'ctd_name' ],
				[ 'ctd_count' => $row->hitcount ]
			);
		}
		$dbw->endAtomic( __METHOD__ );
		MediaWiki\MediaWikiServices::getInstance()->getDBLoadBalancerFactory()->waitForReplication();
	}

	private function backpopulateChangeTag() {
		$dbr = MediaWiki\MediaWikiServices::getInstance()->getDBLoadBalancer()->getConnection(
			DB_REPLICA
		);
		$changeTagDefs = $dbr->select(
			'change_tag_def',
			[ 'ctd_name', 'ctd_id' ],
			[]
		);

		foreach ( $changeTagDefs as $row ) {
			$this->backpopulateChangeTagPerTag( $row->ctd_name, $row->ctd_id );
		}
	}

	private function backpopulateChangeTagPerTag( $tagName, $tagId ) {
		$services = MediaWiki\MediaWikiServices::getInstance();
		$dbr = $services->getDBLoadBalancer()->getConnection( DB_REPLICA );
		$dbw = $services->getDBLoadBalancer()->getConnection( DB_MASTER );
		$sleep = (int)$this->getOption( 'sleep', 10 );
		$lastId = 0;
		while ( true ) {
			// Given that indexes might not be there, it's better to use replica
			$ids = $dbr->selectFieldValues(
				'change_tag',
				'ct_id',
				[ 'ct_tag' => $tagName, 'ct_tag_id' => null, 'ct_id > ' . $lastId ],
				__METHOD__,
				[ 'LIMIT' => $this->getBatchSize() ]
			);

			if ( !$ids ) {
				break;
			}
			$lastId = end( $ids );

			if ( $this->hasOption( 'dry-run' ) ) {
				$this->output(
					"This ids will be changed to have \"{$tagId}\" as tag id: " . implode( ', ', $ids ) . "\n"
				);
				continue;
			}

			$dbw->update(
				'change_tag',
				[ 'ct_tag_id' => $tagId ],
				[ 'ct_id' => $ids ]
			);

			$services->getDBLoadBalancerFactory()->waitForReplication();
			if ( $sleep > 0 ) {
				sleep( $sleep );
			}
		}
	}

}

$maintClass = PopulateChangeTagDef::class;
require_once RUN_MAINTENANCE_IF_MAIN;
