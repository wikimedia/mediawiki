<?php

use MediaWiki\MediaWikiServices;
use MediaWiki\Storage\NameTableStore;
use MediaWiki\Storage\SqlBlobStore;
use Wikimedia\Assert\Assert;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ResultWrapper;

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
 *
 * @file
 * @ingroup Maintenance
 */

require_once __DIR__ . '/Maintenance.php';

/**
 * Populate the content and slot tables.
 * @since 1.32
 */
class PopulateContentTables extends Maintenance {

	/** @var IDatabase */
	private $dbw;

	/** @var NameTableStore */
	private $contentModelStore;

	/** @var int */
	private $mainRoleId;

	/** @var array|null Map "{$modelId}:{$address}" to content_id */
	private $contentRowMap = null;

	public function __construct() {
		parent::__construct();

		$this->addDescription( 'Populate content and slot tables' );
		$this->addOption( 'table', 'revision or archive table, or `all` to populate both', false,
			true );
		$this->addOption( 'reuse-content',
			'Reuse content table rows when the address and model are the same. '
			. 'This will increase the script\'s time and memory usage, perhaps significantly.',
			false, false );
		$this->setBatchSize( 500 );
	}

	private function initServices() {
		$this->dbw = $this->getDB( DB_MASTER );
		$this->contentModelStore = MediaWikiServices::getInstance()->getContentModelStore();
		$this->mainRoleId = MediaWikiServices::getInstance()->getSlotRoleStore()->acquireId( 'main' );
	}

	public function execute() {
		global $wgMultiContentRevisionSchemaMigrationStage;

		if ( $wgMultiContentRevisionSchemaMigrationStage < MIGRATION_WRITE_BOTH ) {
			$this->writeln(
				"...cannot update while \$wgMultiContentRevisionSchemaMigrationStage < MIGRATION_WRITE_BOTH"
			);
			return false;
		}

		$this->initServices();

		if ( $this->getOption( 'reuse-content', false ) ) {
			$this->loadContentMap();
		}

		foreach ( $this->getTables() as $table ) {
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
			$this->fatalError( 'Invalid table. Must be either `revision` or `archive` or `all`' );
		}

		if ( $table === 'all' ) {
			$tables = [ 'revision', 'archive' ];
		} else {
			$tables = [ $table ];
		}

		return $tables;
	}

	private function loadContentMap() {
		$this->writeln( "Loading existing content table rows..." );
		$this->contentRowMap = [];
		$dbr = $this->getDB( DB_REPLICA );
		$from = false;
		while ( true ) {
			$res = $dbr->select(
				'content',
				[ 'content_id', 'content_address', 'content_model' ],
				$from ? "content_id > $from" : '',
				__METHOD__,
				[ 'ORDER BY' => 'content_id', 'LIMIT' => $this->getBatchSize() ]
			);
			if ( !$res || !$res->numRows() ) {
				break;
			}
			foreach ( $res as $row ) {
				$from = $row->content_id;
				$this->contentRowMap["{$row->content_model}:{$row->content_address}"] = $row->content_id;
			}
		}
	}

	/**
	 * @param string $table
	 */
	private function populateTable( $table ) {
		$this->writeln( "Populating $table..." );

		if ( $table === 'revision' ) {
			$idField = 'rev_id';
			$tables = [ 'revision', 'slots', 'page' ];
			$fields = [
				'rev_id',
				'len' => 'rev_len',
				'sha1' => 'rev_sha1',
				'text_id' => 'rev_text_id',
				'content_model' => 'rev_content_model',
				'namespace' => 'page_namespace',
				'title' => 'page_title',
			];
			$joins = [
				'slots' => [ 'LEFT JOIN', 'rev_id=slot_revision_id' ],
				'page' => [ 'LEFT JOIN', 'rev_page=page_id' ],
			];
		} else {
			$idField = 'ar_rev_id';
			$tables = [ 'archive', 'slots' ];
			$fields = [
				'rev_id' => 'ar_rev_id',
				'len' => 'ar_len',
				'sha1' => 'ar_sha1',
				'text_id' => 'ar_text_id',
				'content_model' => 'ar_content_model',
				'namespace' => 'ar_namespace',
				'title' => 'ar_title',
			];
			$joins = [
				'slots' => [ 'LEFT JOIN', 'ar_rev_id=slot_revision_id' ],
			];
		}

		$minmax = $this->dbw->selectRow(
			$table,
			[ 'min' => "MIN( $idField )", 'max' => "MAX( $idField )" ],
			'',
			__METHOD__
		);
		$batchSize = $this->getBatchSize();

		for ( $startId = $minmax->min; $startId <= $minmax->max; $startId += $batchSize ) {
			$endId = min( $startId + $batchSize, $minmax->max );
			$rows = $this->dbw->select(
				$tables,
				$fields,
				[
					"$idField > $startId",
					"$idField <= $endId",
					'slot_revision_id IS NULL',
				],
				__METHOD__,
				[ 'ORDER BY' => 'rev_id' ],
				$joins
			);
			if ( $rows->numRows() !== 0 ) {
				$this->populateContentTablesForRowBatch( $rows, $startId, $table );
			}

			$this->writeln( "... $table processed up to revision id $endId of {$minmax->max}" );
		}

		$this->writeln( "Done populating $table table" );
	}

	/**
	 * @param ResultWrapper $rows
	 * @param int $startId
	 * @param string $table
	 * @return int|null
	 */
	private function populateContentTablesForRowBatch( ResultWrapper $rows, $startId, $table ) {
		$this->beginTransaction( $this->dbw, __METHOD__ );

		foreach ( $rows as $row ) {
			$revisionId = $row->rev_id;

			try {
				Assert::invariant( $revisionId !== null, 'rev_id', 'revision id must not be null' );

				$modelId = $this->contentModelStore->acquireId( $this->getContentModel( $row ) );
				$address = SqlBlobStore::makeAddressFromTextId( $row->text_id );

				if ( isset( $this->contentRowMap["{$modelId}:{$address}"] ) ) {
					$contentId = $this->contentRowMap["{$modelId}:{$address}"];
				} else {
					$this->dbw->insert(
						'content',
						[
							'content_size' => (int)$row->len,
							'content_sha1' => $row->sha1,
							'content_model' => $modelId,
							'content_address' => $address,
						],
						__METHOD__
					);
					$contentId = $this->dbw->insertId();
					if ( $this->contentRowMap !== null ) {
						$this->contentRowMap["{$modelId}:{$address}"] = $contentId;
					}
				}

				$this->dbw->insert(
					'slots',
					[
						'slot_revision_id' => $revisionId,
						'slot_role_id' => $this->mainRoleId,
						'slot_content_id' => $contentId,
						// There's no way to really know the previous revision, so assume no inheriting.
						// rev_parent_id can get changed on undeletions, and deletions can screw up
						// rev_timestamp ordering.
						'slot_origin' => $revisionId,
					],
					__METHOD__
				);
			} catch ( \Exception $e ) {
				$this->rollbackTransaction( $this->dbw, __METHOD__ );
				$this->fatalError( "Failed to populate content table $table row batch, "
					. "at revision $revisionId (batch starting at $startId) due to exception: "
					. $e->__toString() );
			}
		}

		$this->commitTransaction( $this->dbw, __METHOD__ );
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
