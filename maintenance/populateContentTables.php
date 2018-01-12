<?php

use MediaWiki\MediaWikiServices;
use MediaWiki\Storage\BlobStore;
use MediaWiki\Storage\NameTableStore;
use MediaWiki\Storage\RevisionFactory;
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

	const SLOT_MAIN = 'main';
	const SLOT_NOT_INHERITED = 0;

	/**
	 * @var IDatabase
	 */
	private $dbw;

	/**
	 * @var NameTableStore
	 */
	private $contentModelStore;

	/**
	 * @var RevisionFactory
	 */
	private $revisionFactory;

	public function __construct() {
		parent::__construct();

		$this->addDescription( 'Populate content and slot tables' );
		$this->setBatchSize( 500 );

		$this->addOption('start-id', 'Revision id to start populating from', false, true);
	}

	private function initServices() {
		$this->contentModelStore = MediaWikiServices::getInstance()->getContentModelStore();
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
		$this->beginTransaction( $this->dbw, __METHOD__ );

		foreach ( $rows as $row ) {
			$revisionId = (int)$row->rev_id;

			try {
				$contentId = $this->insertContentRow( $row );
				$this->insertSlotRow( $row, $contentId );
			} catch (\Exception $e) {
				$this->rollbackTransaction( $this->dbw, __METHOD__ );
			}
		}

		$this->commitTransaction( $this->dbw, __METHOD__ );

		return isset( $revisionId ) ? $revisionId : null;
	}

	/**
	 * @param \stdClass $row
	 * @return int
	 */
	private function insertContentRow( $row ) {
		$contentModel = $this->getContentModel( $row );

		$this->dbw->insert(
			'content',
			[
				'content_size' => (int)$row->rev_len,
				'content_sha1' => (int)$row->rev_sha1,
				'content_model' => $this->contentModelStore->acquireId( $contentModel ),
				'content_address' => $this->getAddress( $row )
			],
			__METHOD__
		);

		return $this->dbw->insertId();
	}

	/**
	 * @param \stdClass $row
	 * @param int $contentId
	 * @return int
	 */
	private function insertSlotRow( $row, $contentId ) {
		$this->dbw->insert(
			'slots',
			[
				'slot_revision_id' => $row->rev_id,
				'slot_role_id' => self::SLOT_MAIN,
				'slot_content_id' => $contentId,
				'slot_inherited' => self::SLOT_NOT_INHERITED
			],
			__METHOD__
		);

		return $this->dbw->insertId();
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
			[ 'rev_id', 'rev_text_id', 'rev_len', 'rev_sha1', 'rev_content_model' ],
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
	 * @param \stdClass $row
	 * @return string
	 */
	private function getContentModel( $row ) {
		$contentModel = $row->rev_content_model;

		if ( !$contentModel ) {
			$revision = Revision::newFromRow( $row );
			$contentModel = ContentHandler::getDefaultModelFor( $revision->getTitle() );
		}

		return $contentModel;
	}

	/**
	 * @param \stdClass $row
	 * @return string
	 */
	private function getAddress( $row ) {
		return 'tt' . $row->text_id;
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