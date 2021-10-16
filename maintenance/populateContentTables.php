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
 *
 * @file
 * @ingroup Maintenance
 */

use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Storage\BlobAccessException;
use MediaWiki\Storage\BlobStore;
use MediaWiki\Storage\NameTableStore;
use MediaWiki\Storage\SqlBlobStore;
use Wikimedia\Assert\Assert;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IResultWrapper;

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

	/** @var NameTableStore */
	private $slotRoleStore;

	/** @var BlobStore */
	private $blobStore;

	/** @var int */
	private $mainRoleId;

	/** @var array|null Map "{$modelId}:{$address}" to content_id */
	private $contentRowMap = null;

	private $count = 0, $totalCount = 0;

	public function __construct() {
		parent::__construct();

		$this->addDescription( 'Populate content and slot tables' );
		$this->addOption( 'table', 'revision or archive table, or `all` to populate both', false,
			true );
		$this->addOption( 'reuse-content',
			'Reuse content table rows when the address and model are the same. '
			. 'This will increase the script\'s time and memory usage, perhaps significantly.',
			false, false );
		$this->addOption( 'start-revision', 'The rev_id to start at', false, true );
		$this->addOption( 'start-archive', 'The ar_rev_id to start at', false, true );
		$this->setBatchSize( 500 );
	}

	private function initServices() {
		$this->dbw = $this->getDB( DB_MASTER );
		$services = MediaWikiServices::getInstance();
		$this->contentModelStore = $services->getContentModelStore();
		$this->slotRoleStore = $services->getSlotRoleStore();
		$this->blobStore = $services->getBlobStore();

		// Don't trust the cache for the NameTableStores, in case something went
		// wrong during a previous run (see T224949#5325895).
		$this->contentModelStore->reloadMap();
		$this->slotRoleStore->reloadMap();
		$this->mainRoleId = $this->slotRoleStore->acquireId( SlotRecord::MAIN );
	}

	public function execute() {
		$t0 = microtime( true );

		$this->initServices();

		if ( $this->getOption( 'reuse-content', false ) ) {
			$this->loadContentMap();
		}

		foreach ( $this->getTables() as $table ) {
			$this->populateTable( $table );
		}

		$elapsed = microtime( true ) - $t0;
		$this->writeln( "Done. Processed $this->totalCount rows in $elapsed seconds" );
		return true;
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
		$t0 = microtime( true );
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
		$elapsed = microtime( true ) - $t0;
		$this->writeln( "Loaded " . count( $this->contentRowMap ) . " rows in $elapsed seconds" );
	}

	/**
	 * @param string $table
	 */
	private function populateTable( $table ) {
		$t0 = microtime( true );
		$this->count = 0;
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
			$startOption = 'start-revision';
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
			$startOption = 'start-archive';
		}

		if ( !$this->dbw->fieldExists( $table, $fields['text_id'], __METHOD__ ) ) {
			$this->writeln( "No need to populate, $table.{$fields['text_id']} field does not exist" );
			return;
		}

		$minmax = $this->dbw->selectRow(
			$table,
			[ 'min' => "MIN( $idField )", 'max' => "MAX( $idField )" ],
			'',
			__METHOD__
		);
		if ( $this->hasOption( $startOption ) ) {
			$minmax->min = (int)$this->getOption( $startOption );
		}
		if ( !$minmax || !is_numeric( $minmax->min ) || !is_numeric( $minmax->max ) ) {
			// No rows?
			$minmax = (object)[ 'min' => 1, 'max' => 0 ];
		}

		$batchSize = $this->getBatchSize();

		for ( $startId = $minmax->min; $startId <= $minmax->max; $startId += $batchSize ) {
			$endId = min( $startId + $batchSize - 1, $minmax->max );
			$rows = $this->dbw->select(
				$tables,
				$fields,
				[
					"$idField >= $startId",
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

			$elapsed = microtime( true ) - $t0;
			$this->writeln(
				"... $table processed up to revision id $endId of {$minmax->max}"
				. " ($this->count rows in $elapsed seconds)"
			);
		}

		$elapsed = microtime( true ) - $t0;
		$this->writeln( "Done populating $table table. Processed $this->count rows in $elapsed seconds" );
	}

	/**
	 * @param IResultWrapper $rows
	 * @param int $startId
	 * @param string $table
	 * @return int|null
	 */
	private function populateContentTablesForRowBatch( IResultWrapper $rows, $startId, $table ) {
		$this->beginTransaction( $this->dbw, __METHOD__ );

		if ( $this->contentRowMap === null ) {
			$map = [];
		} else {
			$map = &$this->contentRowMap;
		}
		$contentKeys = [];

		try {
			// Step 1: Figure out content rows needing insertion.
			$contentRows = [];
			foreach ( $rows as $row ) {
				$revisionId = $row->rev_id;

				Assert::invariant( $revisionId !== null, 'rev_id must not be null' );

				$model = $this->getContentModel( $row );
				$modelId = $this->contentModelStore->acquireId( $model );
				$address = SqlBlobStore::makeAddressFromTextId( $row->text_id );

				$key = "{$modelId}:{$address}";
				$contentKeys[$revisionId] = $key;

				if ( !isset( $map[$key] ) ) {
					$this->fillMissingFields( $row, $model, $address );

					$map[$key] = false;
					$contentRows[] = [
						'content_size' => (int)$row->len,
						'content_sha1' => $row->sha1,
						'content_model' => $modelId,
						'content_address' => $address,
					];
				}
			}

			// Step 2: Insert them, then read them back in for use in the next step.
			if ( $contentRows ) {
				$id = $this->dbw->selectField( 'content', 'MAX(content_id)', '', __METHOD__ );
				$this->dbw->insert( 'content', $contentRows, __METHOD__ );
				$res = $this->dbw->select(
					'content',
					[ 'content_id', 'content_model', 'content_address' ],
					'content_id > ' . (int)$id,
					__METHOD__
				);
				foreach ( $res as $row ) {
					$address = $row->content_address;
					if ( substr( $address, 0, 4 ) === 'bad:' ) {
						$address = substr( $address, 4 );
					}
					$key = $row->content_model . ':' . $address;
					$map[$key] = $row->content_id;
				}
			}

			// Step 3: Insert the slot rows.
			$slotRows = [];
			foreach ( $rows as $row ) {
				$revisionId = $row->rev_id;
				$contentId = $map[$contentKeys[$revisionId]] ?? false;
				if ( $contentId === false ) {
					throw new \RuntimeException( "Content row for $revisionId not found after content insert" );
				}
				$slotRows[] = [
					'slot_revision_id' => $revisionId,
					'slot_role_id' => $this->mainRoleId,
					'slot_content_id' => $contentId,
					// There's no way to really know the previous revision, so assume no inheriting.
					// rev_parent_id can get changed on undeletions, and deletions can screw up
					// rev_timestamp ordering.
					'slot_origin' => $revisionId,
				];
			}
			$this->dbw->insert( 'slots', $slotRows, __METHOD__ );
			$this->count += count( $slotRows );
			$this->totalCount += count( $slotRows );
		} catch ( \Exception $e ) {
			$this->rollbackTransaction( $this->dbw, __METHOD__ );
			$this->fatalError( "Failed to populate content table $table row batch starting at $startId "
				. "due to exception: " . $e->__toString() );
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

	/**
	 * Compute any missing fields in $row.
	 * The way the missing values are computed must correspond to the way this is done in SlotRecord.
	 *
	 * @param object $row to be modified
	 * @param string $model
	 * @param string &$address
	 */
	private function fillMissingFields( $row, $model, &$address ) {
		if ( !isset( $row->content_model ) ) {
			// just for completeness
			$row->content_model = $model;
		}

		if ( isset( $row->len ) && isset( $row->sha1 ) && $row->sha1 !== '' ) {
			// No need to load the content, quite now.
			return;
		}

		try {
			$blob = $this->blobStore->getBlob( $address );
		} catch ( BlobAccessException $e ) {
			$address = 'bad:' . $address;
			$blob = '';
		}

		if ( !isset( $row->len ) ) {
			// NOTE: The nominal size of the content may not be the length of the raw blob.
			$row->len = ContentHandler::makeContent( $blob, null, $model )->getSize();
		}

		if ( !isset( $row->sha1 ) || $row->sha1 === '' ) {
			$row->sha1 = SlotRecord::base36Sha1( $blob );
		}
	}
}

$maintClass = PopulateContentTables::class;
require_once RUN_MAINTENANCE_IF_MAIN;
