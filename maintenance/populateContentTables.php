<?php

use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\LBFactory;
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

	// todo get these from the slot_roles table
	const SLOT_NAME_MAIN = 1;

	const SLOT_INHERITED = 1;
	const SLOT_NOT_INHERITED = 0;

	/**
	 * @var IDatabase
	 */
	private $dbw;

	/**
	 * @var LBFactory
	 */
	private $lbFactory;

	/**
	 * @var \MediaWiki\Storage\NameTableStore
	 */
	private $contentModelStore;

	public function __construct() {
		parent::__construct();

		$this->addDescription( 'Populate content and slot tables' );
		$this->setBatchSize( 500 );

		$this->addOption('start-id', 'Revision id to start populating from', false, true);
	}

	private function initServices() {
		$this->lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
		$this->contentModelStore = MediaWikiServices::getInstance()->getContentModelStore();
	}

	public function execute() {
		$this->initServices();

		$batchSize = $this->getBatchSize();
		$batchStartId = (int)$this->getOption( 'start-id', 0 );

		$this->dbw = $this->getDB( DB_MASTER );

		do {
			$rows = $this->fetchRevisionIds( $batchStartId, $batchSize );

			$batchStartId = $this->populateContentTablesForRowBatch( $rows );

			$this->writeln( 'Processed up to revision id ' . $batchStartId );
		} while ( $rows->numRows() >= $batchSize );

		$this->writeln( 'Done' );
	}

	/**
	 * @param ResultWrapper $rows
	 * @return int
	 */
	private function populateContentTablesForRowBatch( ResultWrapper $rows ) {
		$ticket = $this->lbFactory->getEmptyTransactionTicket( __METHOD__ );

		foreach ( $rows as $revisionRow ) {
			$batchStartId = (int)$revisionRow->rev_id;

			$this->populateContentTablesForRevision( $batchStartId );
		}

		$this->lbFactory->commitAndWaitForReplication( __METHOD__, $ticket );

		return $batchStartId;
	}

	/**
	 * @param int $revisionId
	 */
	private function populateContentTablesForRevision( $revisionId ) {
		$revision = Revision::newFromId( $revisionId );

		$contentRow = $this->buildContentRowData( $revision );

		try {
			$contentId = $this->insertContentRow( $contentRow );
			$this->insertSlotRow( $revisionId, $contentId );
		} catch (\Exception $e) {
			// TODO handle
		}
	}

	/**
	 * @param Revision $revision
	 * @return array
	 */
	private function buildContentRowData( Revision $revision ) {
		$contentModel = $revision->getContentModel();
		$contentModelId = $this->contentModelStore->acquireId( $contentModel );

		$contentData = [
			'content_size' => $revision->getSize(),
			'content_sha1' => $revision->getSha1(),
			'content_model' => $contentModelId,
			'content_address' => 'tt:' . $revision->getTextId()
		];

		return $contentData;
	}

	/**
	 * @param int $revId
	 * @param int $contentId
	 * @return mixed
	 */
	private function insertSlotRow( $revId, $contentId ) {
		$res = $this->dbw->insert(
			'slots',
			$this->getSlotData( $revId, $contentId ),
			__METHOD__
		);

			return $res;
	}

	/**
	 * @param int $revId
	 * @param int $contentId
	 * @return array
	 */
	private function getSlotData( $revId, $contentId ) {
		$slotData = [
			'slot_revision_id' => $revId,
			'slot_role_id' => self::SLOT_NAME_MAIN,
			'slot_content_id' => $contentId,
			'slot_inherited' => self::SLOT_NOT_INHERITED
		];

		return $slotData;
	}

	/**
	 * @param array $rowValues
	 * @return int
	 */
	private function insertContentRow( array $rowValues ) {
		$this->dbw->insert(
			'content',
			$rowValues,
			__METHOD__
		);

		return $this->dbw->insertId();
	}

	/**
	 * @param int $lastId
	 * @param int $batchSize
	 * @return mixed
	 */
	private function fetchRevisionIds( $lastId, $batchSize )
	{
		// TODO optimize, maybe instead use WHERE NOT EXISTS

		$rows = $this->dbw->select(
			[ 'revision', 'slots' ],
			[ 'rev_id' ],
			[
				"rev_id > $lastId",
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