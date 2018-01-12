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
	 * @var NameTableStore
	 */
	private $slotRoleStore;

	/**
	 * @var BlobStore
	 */
	private $blobStore;

	public function __construct() {
		parent::__construct();

		$this->addDescription( 'Populate content and slot tables' );
		$this->setBatchSize( 500 );

		$this->addOption( 'start-id', 'Revision id to start populating from', false, true );
		$this->addOption( 'to-id', 'Populate up to revision id', false, true );
		$this->addOption( 'table', 'revision or archive table, or `all` to populate both', false,
			true );
	}

	private function initServices() {
		$this->contentModelStore = MediaWikiServices::getInstance()->getContentModelStore();
		$this->slotRoleStore = MediaWikiServices::getInstance()->getSlotRoleStore();
		$this->blobStore = MediaWikiServices::getInstance()->getBlobStore();
	}

	public function execute() {
		$this->initServices();

		$tables = $this->getTables();

		foreach ( $tables as $table ) {
			$this->populateTable( $table );
		}

		$this->writeln( 'Done' );
	}

	/**
	 * @return string[]
	 */
	private function getTables() {
		$table = $this->getOption( 'table', 'all' );
		$validTableOptions = [ 'all', 'revision', 'archive' ];

		if ( !in_array( $table, $validTableOptions ) ) {
			$this->fatalError( 'Invalid table. Must be either `revision` or `archive` or `all`');
		}

		if ( $table === 'all' ) {
			$tables = [ 'revision', 'archive' ];
		} else {
			$tables = [ $table ];
		}

		return $tables;
	}

	/**
	 * @param $table
	 */
	private function populateTable( $table ) {
		$batchSize = $this->getBatchSize();
		$startId = (int)$this->getOption( 'start-id', 0 );
		$toId = null;

		if ( $this->hasOption( 'to-id' ) ) {
			$toId = $this->getOption( 'to-id' );
		}

		$this->dbw = $this->getDB( DB_MASTER );

		do {
			if ( $table === 'revision' ) {
				$rows = $this->fetchRevisionData( $startId, $batchSize );
			} else {
				$rows = $this->fetchArchiveRevisionData( $startId, $batchSize );
			}

			$lastId = $this->populateContentTablesForRowBatch( $rows, $startId );

			$this->writeln( 'Processed up to revision id ' . $lastId );

			if ( $toId !== null && $lastId >= $toId ) {
				break;
			}

			$startId = $lastId + 1;
		} while ( $rows->numRows() >= $batchSize && $lastId !== null);

		$this->writeln( "Done populating table $table" );
	}

	/**
	 * @param ResultWrapper $rows
	 * @param int $startId
	 * @return int|null
	 */
	private function populateContentTablesForRowBatch( ResultWrapper $rows, $startId ) {
		$this->beginTransaction( $this->dbw, __METHOD__ );

		foreach ( $rows as $row ) {
			$revisionId = isset( $row->rev_id ) ? (int)$row->rev_id : $row->ar_rev_id;

			try {
				$contentId = $this->insertContentRow( $row );
				$this->insertSlotRow( $revisionId, $contentId );
			} catch (\Exception $e) {
				$this->rollbackTransaction( $this->dbw, __METHOD__ );
				$this->fatalError( 'Failed to populate content table row batch, '
					. 'at revision ' . $revisionId . ', batch starting at ' . $startId );
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
		$this->dbw->insert(
			'content',
			$this->getContentRowData( $row ),
			__METHOD__
		);

		return $this->dbw->insertId();
	}

	/**
	 * @param \stdClass $row
	 * @return array
	 */
	private function getContentRowData( $row ) {
		$contentModel = $this->getContentModel( $row );

		if ( isset( $row->rev_id ) ) {
			$rowData = [
				'content_size' => (int)$row->rev_len,
				'content_sha1' => (int)$row->rev_sha1,
				'content_model' => $this->contentModelStore->acquireId( $contentModel ),
				'content_address' => 'tt:' . $row->rev_text_id
			];
		} else {
			$rowData = [
				'content_size' => (int)$row->ar_len,
				'content_sha1' => (int)$row->ar_sha1,
				'content_model' => $this->contentModelStore->acquireId( $contentModel ),
				'content_address' => 'tt:' . $row->ar_text_id
			];
		}

		return $rowData;
	}

	/**
	 * @param int $revisionId
	 * @param int $contentId
	 * @return int
	 */
	private function insertSlotRow( $revisionId, $contentId ) {
		$this->dbw->insert(
			'slots',
			[
				'slot_revision_id' => $revisionId,
				'slot_role_id' => $this->slotRoleStore->acquireId( self::SLOT_MAIN ),
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
	private function fetchRevisionData( $startId, $batchSize ) {
		// TODO optimize, maybe instead use WHERE NOT EXISTS
		// TODO alias the select fields here and for archive table and avoid if/else
		//      when referring to field names from revision / archive tables

		$rows = $this->dbw->select(
			[ 'revision', 'slots' ],
			[ 'rev_id', 'rev_len', 'rev_sha1', 'rev_text_id', 'rev_content_model', 'rev_page' ],
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
	 * @param int $startId
	 * @param int $batchSize
	 * @return mixed
	 */
	private function fetchArchiveRevisionData( $startId, $batchSize ) {
		// TODO optimize, maybe instead use WHERE NOT EXISTS

		$rows = $this->dbw->select(
			[ 'archive', 'slots' ],
			[ 'ar_rev_id', 'ar_len', 'ar_sha1', 'ar_text_id', 'ar_content_model', 'ar_page_id' ],
			[
				"ar_rev_id >= $startId",
				'slot_revision_id IS NULL',
			],
			__METHOD__,
			[
				'LIMIT' => $batchSize,
				'ORDER BY' => 'ar_rev_id ASC'
			],
			[
				'slots' => [ 'LEFT JOIN', 'ar_rev_id=slot_revision_id' ]
			]
		);

		return $rows;
	}

	/**
	 * @param \stdClass $row
	 * @return string
	 */
	private function getContentModel( $row ) {
		if ( isset( $row->rev_content_model ) ) {
			return $row->rev_content_model;
		} else if ( isset( $row->ar_content_model ) ) {
			return $row->ar_content_model;
		}

		// TODO does this work for archive table?

		$pageId = isset( $row->rev_page ) ? (int)$row->rev_page : (int)$row->ar_page_id;
		$title = Title::newFromID( $pageId );

		return ContentHandler::getDefaultModelFor( $title );
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
