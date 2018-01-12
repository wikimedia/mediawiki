<?php

use MediaWiki\MediaWikiServices;
use MediaWiki\Storage\NameTableStore;
use MediaWiki\Storage\RevisionStore;
use MediaWiki\Storage\SlotRecord;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ResultWrapper;

/**
 * Populate the content and slot tables.
 *
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
 *
 * @file
 * @ingroup Maintenance
 */

require_once __DIR__ . '/Maintenance.php';

/**
 * Usage:
 *  populateContentTables.php --batch-size 200
 */
class PopulateContentTables extends Maintenance {

	/**
	 * @var IDatabase
	 */
	private $dbw;

	/**
	 * @var RevisionStore
	 */
	private $revisionStore;

	/**
	 * @var NameTableStore
	 */
	private $contentModelStore;

	public function __construct() {
		parent::__construct();

		$this->addDescription( 'Populate content and slot tables' );
		$this->setBatchSize( 500 );

		$this->addOption('start-id', 'Revision id to start populating from', false, true);
	}

	private function initServices() {
		$this->contentModelStore = MediaWikiServices::getInstance()->getContentModelStore();
		$this->revisionStore = MediaWikiServices::getInstance()->getRevisionStore();
	}

	public function execute() {
		$this->initServices();

		$batchSize = $this->getBatchSize();
		$startId = (int)$this->getOption( 'start-id', 0 );

		$this->dbw = $this->getDB( DB_MASTER );

		do {
			$rows = $this->fetchRevisionData( $startId, $batchSize );

			$lastId = $this->populateContentTablesForRowBatch( $rows );

			$this->writeln( 'Processed up to revision id ' . $lastId );

			$startId = $lastId + 1;
		} while ( $rows->numRows() >= $batchSize && $lastId !== null);

		$this->writeln( 'Done' );
	}

	/**
	 * @param ResultWrapper $rows
	 * @return int|null
	 */
	private function populateContentTablesForRowBatch( ResultWrapper $rows ) {
		$this->dbw->begin( __METHOD__ );

		foreach ( $rows as $row ) {
			$revisionId = (int)$row->rev_id;
			$revisionRecord = $this->revisionStore->getRevisionById( $revisionId );
			$slot = $revisionRecord->getSlot( 'main' );

			try {
				$contentId = $this->insertContentRow( $slot );
				$this->insertSlotRow( $slot, $contentId );
			} catch (\Exception $e) {
				$this->dbw->rollback( __METHOD__ );
			}
		}

		$this->dbw->commit( __METHOD__ );

		return isset( $revisionId ) ? $revisionId : null;
	}

	/**
	 * @param SlotRecord $slot
	 * @return int
	 */
	private function insertContentRow( SlotRecord $slot ) {
		$rowData = $this->buildContentRowData( $slot );

		$this->dbw->insert(
			'content',
			$rowData,
			__METHOD__
		);

		return $this->dbw->insertId();
	}

	/**
	 * @param SlotRecord $slot
	 * @return array
	 */
	private function buildContentRowData( SlotRecord $slot ) {
		$content = $slot->getContent();

		$contentData = [
			'content_size' => $slot->getSize(),
			'content_sha1' => $slot->getSha1(),
			'content_model' => $this->contentModelStore->acquireId( $content->getModel() ),
			'content_address' => $slot->getAddress()
		];

		return $contentData;
	}

	/**
	 * @param int $revId
	 * @param int $contentId
	 * @return mixed
	 */
	private function insertSlotRow( SlotRecord $slot, $contentId ) {
		$res = $this->dbw->insert(
			'slots',
			$this->getSlotData( $slot, $contentId ),
			__METHOD__
		);

			return $res;
	}

	/**
	 * @param int $revId
	 * @param int $contentId
	 * @return array
	 */
	private function getSlotData( SlotRecord $slot, $contentId ) {
		$slotData = [
			'slot_revision_id' => $slot->getRevision(),
			'slot_role_id' => $slot->getRole(),
			'slot_content_id' => $contentId,
			'slot_inherited' => $slot->isInherited()
		];

		return $slotData;
	}

	/**
	 * @param int $startId
	 * @param int $batchSize
	 * @return mixed
	 */
	private function fetchRevisionData( $startId, $batchSize )
	{
		// TODO optimize, maybe instead use WHERE NOT EXISTS

		$rows = $this->dbw->select(
			[ 'revision', 'slots' ],
			[ 'rev_id', 'rev_text_id', 'rev_len', 'rev_sha1' ],
			[
				"rev_id >= $startId",
				'slot_revision_id IS NULL',
			],
			__METHOD__,
			[
				'LIMIT' => $batchSize,
				'ORDER BY' => 'rev_id ASC'
			],
			[
				'slots' => [ 'LEFT JOIN', 'rev_id=slot_revision_id' ]
			]
		);

		return $rows;
	}

	/**
	 * @param string $msg
	 */
	private function writeln( $msg ) {
		$this->output( "$msg\n" );
	}
}

$maintClass = 'PopulateContentTables';
require_once RUN_MAINTENANCE_IF_MAIN;