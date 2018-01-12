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

	/**
	 * @var int|null
	 */
	private $toId = null;

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
		$this->setToIdFromOption();

		$tables = $this->getTables();

		foreach ( $tables as $table ) {
			$this->populateTable( $table );
		}

		$this->writeln( 'Done' );
	}

	private function setToIdFromOption() {
		if ( $this->hasOption( 'to-id' ) ) {
			$this->toId = (int)$this->getOption( 'to-id' );
		}
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

		$this->dbw = $this->getDB( DB_MASTER );

		do {
			if ( $table === 'revision' ) {
				$rows = $this->fetchRevisionData( $startId, $batchSize );
			} else {
				$rows = $this->fetchArchiveRevisionData( $startId, $batchSize );
			}

			$lastId = $this->populateContentTablesForRowBatch( $rows, $startId, $table );

			$this->writeln( "[$table table] Processed up to revision id $lastId" );

			if ( $this->toId !== null && $lastId >= $this->toId ) {
				break;
			}

			$startId = $lastId + 1;
		} while ( $rows->numRows() >= $batchSize && $lastId !== null);

		$this->writeln( "Done populating $table table" );
	}

	/**
	 * @param ResultWrapper $rows
	 * @param int $startId
	 * @params string $table
	 * @return int|null
	 */
	private function populateContentTablesForRowBatch( ResultWrapper $rows, $startId, $table ) {
		$this->beginTransaction( $this->dbw, __METHOD__ );
		$lastId = null;

		foreach ( $rows as $row ) {
			$revisionId = $row->rev_id;

			if ( $this->toId !== null && $revisionId > $this->toId ) {
				break;
			}

			try {
				$contentId = $this->insertContentRow( $row );
				$this->insertSlotRow( $revisionId, $contentId );
				$lastId = $revisionId;
			} catch (\Exception $e) {
				$this->rollbackTransaction( $this->dbw, __METHOD__ );
				$this->fatalError( "Failed to populate content table $table row batch, "
					. "at revision $revisionId, batch starting at $startId" );
			}
		}

		$this->commitTransaction( $this->dbw, __METHOD__ );

		return $lastId;
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

		$rowData = [
			'content_size' => (int)$row->len,
			'content_sha1' => (int)$row->sha1,
			'content_model' => $this->contentModelStore->acquireId( $contentModel ),
			'content_address' => 'tt:' . $row->text_id
		];

		return $rowData;
	}

	/**
	 * @param int $revisionId
	 * @param int $contentId
	 * @return int
	 */
	private function insertSlotRow( $revisionId, $contentId ) {
		// todo switch to slot_origin instead of slot_inherited
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
			[ 'revision', 'slots', 'page' ],
			[
				'rev_id',
				'len' => 'rev_len',
				'sha1' => 'rev_sha1',
				'text_id' => 'rev_text_id',
				'content_model' => 'rev_content_model',
				'namespace' => 'page_namespace',
				'title' => 'page_title'
			],
			[
				"rev_id >= $startId",
				'slot_revision_id IS NULL',
			],
			__METHOD__,
			[
				'LIMIT' => $batchSize,
				'ORDER BY' => 'rev_id'
			],
			[
				'slots' => [ 'LEFT JOIN', 'rev_id=slot_revision_id' ],
				'page' => [ 'LEFT JOIN', 'rev_page=page_id']
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
			[
				'rev_id' => 'ar_rev_id',
				'len' => 'ar_len',
				'sha1' => 'ar_sha1',
				'text_id' => 'ar_text_id',
				'content_model' => 'ar_content_model',
				'namespace' => 'ar_namespace',
				'title' => 'ar_title'
			],
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
		if ( isset( $row->content_model ) ) {
			return $row->content_model;
		}

		$title = Title::makeTitle( $row->namespace, $row->title );

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
