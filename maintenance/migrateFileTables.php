<?php
/**
 * Maintenance script to refresh image metadata fields.
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

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

use MediaWiki\Maintenance\Maintenance;
use Wikimedia\Rdbms\IMaintainableDatabase;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * Maintenance script to refresh image metadata fields.
 *
 * @ingroup Maintenance
 */
class MigrateFileTables extends Maintenance {

	/**
	 * @var IMaintainableDatabase
	 */
	protected $dbw;

	public function __construct() {
		parent::__construct();

		$this->addDescription( 'Script to migrate from image/oldimage tables to file/filerevision' );
		$this->setBatchSize( 200 );

		$this->addOption( 'start', 'Name of file to start with', false, true );
		$this->addOption( 'end', 'Name of file to end with', false, true );
		$this->addOption(
			'sleep',
			'Time to sleep between each batch (in seconds). Default: 0',
			false,
			true
		);
	}

	public function execute() {
		$verbose = $this->hasOption( 'verbose' );
		$start = $this->getOption( 'start', false );
		$sleep = (int)$this->getOption( 'sleep', 0 );

		$dbw = $this->getPrimaryDB();
		$queryBuilderTemplate = $dbw->newSelectQueryBuilder()
			->select(
				[
					'img_name',
					'img_size',
					'img_width',
					'img_height',
					'img_metadata',
					'img_bits',
					'img_media_type',
					'img_major_mime',
					'img_minor_mime',
					'img_timestamp',
					'img_sha1',
					'img_actor',
					'img_metadata',
					'img_description_id',
					'img_description_text' => 'comment_img_description.comment_text',
					'img_description_data' => 'comment_img_description.comment_data',
					'img_description_cid' => 'comment_img_description.comment_id'
				]
			)
			->from( 'image' )
			->join(
				'comment',
				'comment_img_description',
				'comment_img_description.comment_id = img_description_id'
			);
		$totalRowsInserted = 0;
		$filesHandled = 0;
		$batchSize = intval( $this->getBatchSize() );
		if ( $batchSize <= 0 ) {
			$this->fatalError( "Batch size is too low...", 12 );
		}
		$end = $this->getOption( 'end', false );
		if ( $end !== false ) {
			$queryBuilderTemplate->andWhere( $dbw->expr( 'img_name', '<=', $end ) );
		}
		$queryBuilderTemplate
			->orderBy( 'img_name', SelectQueryBuilder::SORT_ASC )
			->limit( $batchSize );

		$batchCondition = [];
		// For the WHERE img_name > 'foo' condition that comes after doing a batch
		if ( $start !== false ) {
			$batchCondition[] = $dbw->expr( 'img_name', '>=', $start );
		}
		do {
			$queryBuilder = clone $queryBuilderTemplate;
			$res = $queryBuilder->andWhere( $batchCondition )
				->caller( __METHOD__ )->fetchResultSet();
			if ( $res->numRows() > 0 ) {
				$row1 = $res->current();
				$this->output( "Processing next {$res->numRows()} row(s) starting with {$row1->img_name}.\n" );
				$res->rewind();
			}

			foreach ( $res as $row ) {
				$rowsInserted = $this->handleFile( $row );
				$filesHandled += 1;
				$totalRowsInserted += $rowsInserted;

				$this->output( "Migrated File:{$row->img_name}. Inserted $rowsInserted rows.\n" );
			}
			if ( $res->numRows() > 0 ) {
				// @phan-suppress-next-line PhanPossiblyUndeclaredVariable rows contains at least one item
				$batchCondition = [ $dbw->expr( 'img_name', '>', $row->img_name ) ];
			}
			$this->waitForReplication();
			if ( $sleep ) {
				sleep( $sleep );
			}
		} while ( $res->numRows() === $batchSize );

		$this->output( "\nFinished migration for $filesHandled files. "
			. "$totalRowsInserted rows have been inserted into filerevision table.\n" );
	}

	private function handleFile( stdClass $row ): int {
		$repo = $this->getServiceContainer()->getRepoGroup()
			->newCustomLocalRepo();
		$dbw = $this->getPrimaryDB();
		$rowsInserted = 0;

		// LocalFile doesn't like it when the row holds img_description_id
		$imgDescriptionId = $row->img_description_id;
		unset( $row->img_description_id );

		$file = $repo->newFileFromRow( $row );

		// Lock everything we can
		$file->acquireFileLock();
		$dbw->startAtomic( __METHOD__ );
		$dbw->newSelectQueryBuilder()
			->select( '*' )
			->forUpdate()
			->from( 'image' )
			->where( [ 'img_name' => $row->img_name ] )
			->caller( __METHOD__ )->fetchRow();
		$oldimageRows = $dbw->newSelectQueryBuilder()
			->select( '*' )
			->forUpdate()
			->from( 'oldimage' )
			->where( [ 'oi_name' => $row->img_name ] )
			->orderBy( 'oi_timestamp', 'ASC' )
			->caller( __METHOD__ )->fetchResultSet();
		$dbw->newSelectQueryBuilder()
			->select( '*' )
			->forUpdate()
			->from( 'file' )
			->where( [ 'file_name' => $row->img_name ] )
			->caller( __METHOD__ )->fetchRow();

		// Make sure the row exists in file table
		$fileId = $file->acquireFileIdFromName();
		$fileRevisionRows = $dbw->newSelectQueryBuilder()
			->select( '*' )
			->forUpdate()
			->from( 'filerevision' )
			->where( [ 'fr_file' => $fileId ] )
			->caller( __METHOD__ )->fetchResultSet();

		// Make sure the filerevision rows exist
		foreach ( $oldimageRows as $oldimageRow ) {
			$timestamp = $oldimageRow->oi_timestamp;
			$sha1 = $oldimageRow->oi_sha1;

			$alreadyDone = false;
			foreach ( $fileRevisionRows as $fileRevisionRow ) {
				if (
					$timestamp === $fileRevisionRow->fr_timestamp &&
					$sha1 === $fileRevisionRow->fr_sha1
				) {
					// This assume the combination of oi_timestamp and oi_sha1
					// will be always unique which is not the case in production
					// but also all of them were duplicate old uploads and we are
					// willing to simply insert one row only. See T67264
					$alreadyDone = true;
					break;
				}
			}

			if ( $alreadyDone ) {
				continue;
			}

			$dbw->newInsertQueryBuilder()
				->insertInto( 'filerevision' )
				->row(
					[
						'fr_file' => $fileId,
						'fr_size' => $oldimageRow->oi_size,
						'fr_width' => $oldimageRow->oi_width,
						'fr_height' => $oldimageRow->oi_height,
						'fr_metadata' => $oldimageRow->oi_metadata,
						'fr_bits' => $oldimageRow->oi_bits,
						'fr_description_id' => $oldimageRow->oi_description_id,
						'fr_actor' => $oldimageRow->oi_actor,
						'fr_timestamp' => $oldimageRow->oi_timestamp,
						'fr_sha1' => $oldimageRow->oi_sha1,
						'fr_archive_name' => $oldimageRow->oi_archive_name,
						'fr_deleted' => $oldimageRow->oi_deleted,
					]
				)
				->caller( __METHOD__ )->execute();
			$rowsInserted += 1;
		}

		// Make sure the image row (most current version) is there
		$timestamp = $row->img_timestamp;
		$sha1 = $row->img_sha1;

		$alreadyDone = false;
		foreach ( $fileRevisionRows as $fileRevisionRow ) {
			if (
				$timestamp === $fileRevisionRow->fr_timestamp &&
				$sha1 === $fileRevisionRow->fr_sha1
			) {
				$alreadyDone = true;
				break;
			}
		}

		if ( !$alreadyDone ) {
			$dbw->newInsertQueryBuilder()
				->insertInto( 'filerevision' )
				->row(
					[
						'fr_file' => $fileId,
						'fr_size' => $row->img_size,
						'fr_width' => $row->img_width,
						'fr_height' => $row->img_height,
						'fr_metadata' => $row->img_metadata,
						'fr_bits' => $row->img_bits,
						'fr_description_id' => $imgDescriptionId,
						'fr_actor' => $row->img_actor,
						'fr_timestamp' => $row->img_timestamp,
						'fr_sha1' => $row->img_sha1,
						'fr_archive_name' => '',
						'fr_deleted' => 0,
					]
				)
				->caller( __METHOD__ )->execute();
			$rowsInserted += 1;
		}

		// Make sure file has the latest filerevision
		$latestFrId = $dbw->newSelectQueryBuilder()
			->select( 'fr_id' )
			->from( 'filerevision' )
			->where( [ 'fr_file' => $fileId ] )
			->orderBy( 'fr_timestamp', 'DESC' )
			->caller( __METHOD__ )->fetchField();
		$dbw->newUpdateQueryBuilder()
			->update( 'file' )
			->set( [ 'file_latest' => $latestFrId ] )
			->where( [ 'file_id' => $fileId ] )
			->caller( __METHOD__ )->execute();

		$dbw->endAtomic( __METHOD__ );
		$file->releaseFileLock();
		return $rowsInserted;
	}
}

// @codeCoverageIgnoreStart
$maintClass = MigrateFileTables::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
