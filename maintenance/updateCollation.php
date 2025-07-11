<?php
/**
 * Find all rows in the categorylinks table whose collation is out-of-date
 * (cl_collation != $wgCategoryCollation) and repopulate cl_sortkey
 * using the page title and cl_sortkey_prefix.
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
 * @author Aryeh Gregor (Simetrical)
 */

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Storage\NameTableStore;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\Title;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IMaintainableDatabase;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * Maintenance script that will find all rows in the categorylinks table
 * whose collation is out-of-date.
 *
 * @ingroup Maintenance
 */
class UpdateCollation extends Maintenance {
	/** @var int[] */
	public $sizeHistogram = [];

	/** @var int */
	private $numRowsProcessed = 0;

	/** @var bool */
	private $force;

	/** @var bool */
	private $dryRun;

	/** @var bool */
	private $verboseStats;

	/** @var Collation */
	private $collation;

	/** @var string */
	private $collationName;

	/** @var string|null */
	private $targetTable;

	private bool $normalization = false;

	/** @var IDatabase */
	private $dbr;

	/** @var IMaintainableDatabase */
	private $dbw;

	/** @var NamespaceInfo */
	private $namespaceInfo;

	public function __construct() {
		parent::__construct();

		$this->addDescription( <<<TEXT
This script will find all rows in the categorylinks table whose collation is
out-of-date (cl_collation is not the same as \$wgCategoryCollation) and
repopulate cl_sortkey using the page title and cl_sortkey_prefix. If all
collations are up-to-date, it will do nothing.
TEXT
		);

		$this->setBatchSize( 100 );
		$this->addOption( 'force', 'Run on all rows, even if the collation is ' .
			'supposed to be up-to-date.', false, false, 'f' );
		$this->addOption( 'previous-collation', 'Set the previous value of ' .
			'$wgCategoryCollation here to speed up this script, especially if your ' .
			'categorylinks table is large. This will only update rows with that ' .
			'collation, though, so it may miss out-of-date rows with a different, ' .
			'even older collation.', false, true );
		$this->addOption( 'target-collation', 'Set this to the new collation type to ' .
			'use instead of $wgCategoryCollation. Usually you should not use this, ' .
			'you should just update $wgCategoryCollation in LocalSettings.php.',
			false, true );
		$this->addOption( 'target-table', 'Copy rows from categorylinks into the ' .
			'specified table instead of updating them in place.', false, true );
		$this->addOption( 'only-migrate-normalization', 'Only backfill cl_collation_id ' .
			'field from cl_collation', false );
		$this->addOption( 'remote', 'Use Shellbox to calculate the new sort keys ' .
			'remotely.' );
		$this->addOption( 'dry-run', 'Don\'t actually change the collations, just ' .
			'compile statistics.' );
		$this->addOption( 'verbose-stats', 'Show more statistics.' );
	}

	/**
	 * Get services and initialise member variables
	 */
	private function init() {
		$services = $this->getServiceContainer();
		$this->namespaceInfo = $services->getNamespaceInfo();

		if ( $this->hasOption( 'target-collation' ) ) {
			$this->collationName = $this->getOption( 'target-collation' );
		} else {
			$this->collationName = $this->getConfig()->get( MainConfigNames::CategoryCollation );
		}
		if ( $this->hasOption( 'remote' ) ) {
			$realCollationName = 'remote-' . $this->collationName;
		} else {
			$realCollationName = $this->collationName;
		}
		$this->collation = $services->getCollationFactory()->makeCollation( $realCollationName );

		// Collation check: in some cases the constructor will work,
		// but this will raise an exception, breaking all category pages
		$this->collation->getSortKey( 'MediaWiki' );

		$this->force = $this->getOption( 'force' );
		$this->dryRun = $this->getOption( 'dry-run' );
		$this->verboseStats = $this->getOption( 'verbose-stats' );
		$this->dbw = $this->getPrimaryDB();
		$this->dbr = $this->getReplicaDB();
		$this->targetTable = $this->getOption( 'target-table' );
		$this->normalization = $this->getOption( 'only-migrate-normalization', false );
	}

	public function execute() {
		$this->init();
		$batchSize = $this->getBatchSize();

		if ( $this->normalization ) {
			$this->runNormalizationMigration();
			return;
		}

		if ( $this->targetTable ) {
			if ( !$this->dbw->tableExists( $this->targetTable, __METHOD__ ) ) {
				$this->output( "Creating table {$this->targetTable}\n" );
				$this->dbw->query(
					'CREATE TABLE ' . $this->dbw->tableName( $this->targetTable ) .
					' LIKE ' . $this->dbw->tableName( 'categorylinks' ),
					__METHOD__
				);
			}
		}

		$collationConds = [];
		if ( !$this->force && !$this->targetTable ) {
			if ( $this->hasOption( 'previous-collation' ) ) {
				$collationConds['cl_collation'] = $this->getOption( 'previous-collation' );
			} else {
				$collationConds[] = $this->dbr->expr( 'cl_collation', '!=', $this->collationName );
			}
		}
		$maxPageId = (int)$this->dbr->newSelectQueryBuilder()
			->select( 'MAX(page_id)' )
			->from( 'page' )
			->caller( __METHOD__ )->fetchField();
		$batchValue = 0;
		do {
			$this->output( "Selecting next $batchSize pages from cl_from = $batchValue... " );

			// cl_type must be selected as a number for proper paging because
			// enums suck.
			if ( $this->dbw->getType() === 'mysql' ) {
				$clType = 'cl_type+0 AS "cl_type_numeric"';
			} else {
				$clType = 'cl_type';
			}
			$res = $this->dbw->newSelectQueryBuilder()
				->select( [
					'cl_from', 'cl_to', 'cl_sortkey_prefix', 'cl_collation',
					'cl_sortkey', $clType, 'cl_timestamp',
					'page_namespace', 'page_title'
				] )
				->from( 'categorylinks' )
				// per T58041
				->straightJoin( 'page', null, 'cl_from = page_id' )
				->where( $collationConds )
				->andWhere(
					$this->dbw->expr( 'cl_from', '>=', $batchValue )
						->and( 'cl_from', '<', $batchValue + $this->getBatchSize() )
				)
				->orderBy( 'cl_from' )
				->caller( __METHOD__ )->fetchResultSet();
			$this->output( "processing... " );

			if ( $res->numRows() ) {
				if ( $this->targetTable ) {
					$this->copyBatch( $res );
				} else {
					$this->updateBatch( $res );
				}
			}
			$batchValue += $this->getBatchSize();

			if ( $this->dryRun ) {
				$this->output( "{$this->numRowsProcessed} rows would be updated so far.\n" );
			} else {
				$this->output( "{$this->numRowsProcessed} done.\n" );
			}
		} while ( $maxPageId >= $batchValue );

		if ( !$this->dryRun ) {
			$this->output( "{$this->numRowsProcessed} rows processed\n" );
		}

		if ( $this->verboseStats ) {
			$this->output( "\n" );
			$this->showSortKeySizeHistogram();
		}
	}

	/**
	 * Update a set of rows in the categorylinks table
	 */
	private function updateBatch( IResultWrapper $res ) {
		if ( !$this->dryRun ) {
			$this->beginTransaction( $this->dbw, __METHOD__ );
		}
		foreach ( $res as $row ) {
			$title = Title::newFromRow( $row );
			if ( !$row->cl_collation ) {
				# This is an old-style row, so the sortkey needs to be
				# converted.
				if ( $row->cl_sortkey === $title->getText()
					|| $row->cl_sortkey === $title->getPrefixedText()
				) {
					$prefix = '';
				} else {
					# Custom sortkey, so use it as a prefix
					$prefix = $row->cl_sortkey;
				}
			} else {
				$prefix = $row->cl_sortkey_prefix;
			}
			# cl_type will be wrong for lots of pages if cl_collation is 0,
			# so let's update it while we're here.
			$type = $this->namespaceInfo->getCategoryLinkType( $row->page_namespace );
			$newSortKey = $this->collation->getSortKey(
				$title->getCategorySortkey( $prefix ) );
			$this->updateSortKeySizeHistogram( $newSortKey );
			// Truncate to 230 bytes to avoid DB error
			$newSortKey = substr( $newSortKey, 0, 230 );

			if ( $this->dryRun ) {
				// Add 1 to the count if the sortkey was changed. (Note that this doesn't count changes in
				// other fields, if any, those usually only happen when upgrading old MediaWikis.)
				$this->numRowsProcessed += ( $row->cl_sortkey !== $newSortKey );
			} else {
				$this->dbw->newUpdateQueryBuilder()
					->update( 'categorylinks' )
					->set( [
						'cl_sortkey' => $newSortKey,
						'cl_sortkey_prefix' => $prefix,
						'cl_collation' => $this->collationName,
						'cl_type' => $type,
						'cl_timestamp = cl_timestamp',
					] )
					->where( [ 'cl_from' => $row->cl_from, 'cl_to' => $row->cl_to ] )
					->caller( __METHOD__ )
					->execute();
				$this->numRowsProcessed++;
			}
		}
		if ( !$this->dryRun ) {
			$this->commitTransaction( $this->dbw, __METHOD__ );
		}
	}

	/**
	 * Copy a set of rows to the target table
	 */
	private function copyBatch( IResultWrapper $res ) {
		$sortKeyInputs = [];
		foreach ( $res as $row ) {
			$title = Title::newFromRow( $row );
			$sortKeyInputs[] = $title->getCategorySortkey( $row->cl_sortkey_prefix );
		}
		$sortKeys = $this->collation->getSortKeys( $sortKeyInputs );
		$rowsToInsert = [];
		foreach ( $res as $i => $row ) {
			if ( !isset( $sortKeys[$i] ) ) {
				throw new RuntimeException( 'Unable to get sort key' );
			}
			$newSortKey = $sortKeys[$i];
			$this->updateSortKeySizeHistogram( $newSortKey );
			// Truncate to 230 bytes to avoid DB error
			$newSortKey = substr( $newSortKey, 0, 230 );
			$type = $this->namespaceInfo->getCategoryLinkType( $row->page_namespace );
			$rowsToInsert[] = [
				'cl_from' => $row->cl_from,
				'cl_to' => $row->cl_to,
				'cl_sortkey' => $newSortKey,
				'cl_sortkey_prefix' => $row->cl_sortkey_prefix,
				'cl_collation' => $this->collationName,
				'cl_type' => $type,
				'cl_timestamp' => $row->cl_timestamp
			];
		}
		if ( $this->dryRun ) {
			$this->numRowsProcessed += count( $rowsToInsert );
		} else {
			$this->beginTransaction( $this->dbw, __METHOD__ );
			$this->dbw->newInsertQueryBuilder()
				->insertInto( $this->targetTable )
				->ignore()
				->rows( $rowsToInsert )
				->caller( __METHOD__ )->execute();
			$this->numRowsProcessed += $this->dbw->affectedRows();
			$this->commitTransaction( $this->dbw, __METHOD__ );
		}
	}

	/**
	 * Update the verbose statistics
	 */
	private function updateSortKeySizeHistogram( string $key ) {
		if ( !$this->verboseStats ) {
			return;
		}
		$length = strlen( $key );
		if ( !isset( $this->sizeHistogram[$length] ) ) {
			$this->sizeHistogram[$length] = 0;
		}
		$this->sizeHistogram[$length]++;
	}

	/**
	 * Show the verbose statistics
	 */
	private function showSortKeySizeHistogram() {
		if ( !$this->sizeHistogram ) {
			return;
		}
		$maxLength = max( array_keys( $this->sizeHistogram ) );
		if ( $maxLength === 0 ) {
			return;
		}
		$numBins = 20;
		$coarseHistogram = array_fill( 0, $numBins, 0 );
		$coarseBoundaries = [];
		$boundary = 0;
		for ( $i = 0; $i < $numBins - 1; $i++ ) {
			$boundary += $maxLength / $numBins;
			$coarseBoundaries[$i] = round( $boundary );
		}
		$coarseBoundaries[$numBins - 1] = $maxLength + 1;
		$raw = '';
		for ( $i = 0; $i <= $maxLength; $i++ ) {
			if ( $raw !== '' ) {
				$raw .= ', ';
			}
			$val = $this->sizeHistogram[$i] ?? 0;
			for ( $coarseIndex = 0; $coarseIndex < $numBins - 1; $coarseIndex++ ) {
				// @phan-suppress-next-line PhanTypePossiblyInvalidDimOffset False positive
				if ( $coarseBoundaries[$coarseIndex] > $i ) {
					$coarseHistogram[$coarseIndex] += $val;
					break;
				}
			}
			if ( $coarseIndex === ( $numBins - 1 ) ) {
				$coarseHistogram[$coarseIndex] += $val;
			}
			$raw .= $val;
		}

		$this->output( "Sort key size histogram\nRaw data: $raw\n\n" );

		$maxBinVal = max( $coarseHistogram );
		$scale = (int)( 60 / $maxBinVal );
		$prevBoundary = 0;
		for ( $coarseIndex = 0; $coarseIndex < $numBins; $coarseIndex++ ) {
			$val = $coarseHistogram[$coarseIndex] ?? 0;
			// @phan-suppress-next-line PhanTypePossiblyInvalidDimOffset False positive
			$boundary = $coarseBoundaries[$coarseIndex];
			$this->output(
				sprintf( "%-10s %-10d |%s\n",
					$prevBoundary . '-' . ( $boundary - 1 ) . ': ',
					$val,
					str_repeat( '*', $scale * $val )
				)
			);
			$prevBoundary = $boundary;
		}
	}

	private function runNormalizationMigration() {
		$maxPageId = (int)$this->dbr->newSelectQueryBuilder()
			->select( 'MAX(page_id)' )
			->from( 'page' )
			->caller( __METHOD__ )->fetchField();
		$batchValue = 0;
		$batchSize = $this->getBatchSize();

		$collationNameStore = new NameTableStore(
			$this->getServiceContainer()->getDBLoadBalancer(),
			$this->getServiceContainer()->getMainWANObjectCache(),
			LoggerFactory::getInstance( 'SecondaryDataUpdate' ),
			'collation',
			'collation_id',
			'collation_name'
		);
		do {
			$this->output( "Selecting next $batchSize pages from cl_from = $batchValue... " );

			$res = $this->dbw->newSelectQueryBuilder()
				->select( [ 'cl_collation' ] )
				->distinct()
				->from( 'categorylinks' )
				->where( [ 'cl_collation_id' => 0 ] )
				->andWhere(
					$this->dbw->expr( 'cl_from', '>=', $batchValue )
						->and( 'cl_from', '<', $batchValue + $this->getBatchSize() )
				)
				->caller( __METHOD__ )->fetchResultSet();
			$this->output( "processing... " );

			if ( $res->numRows() && !$this->dryRun ) {
				foreach ( $res as $row ) {
					$collationName = $row->cl_collation;
					$collationId = $collationNameStore->acquireId( $collationName );
					$this->dbw->newUpdateQueryBuilder()
						->update( 'categorylinks' )
						->set( [ 'cl_collation_id' => $collationId ] )
						->where( [ 'cl_collation' => $collationName ] )
						->andWhere(
							$this->dbw->expr( 'cl_from', '>=', $batchValue )
								->and( 'cl_from', '<', $batchValue + $this->getBatchSize() )
						)
						->caller( __METHOD__ )->execute();
					$this->numRowsProcessed += $this->dbw->affectedRows();
				}

				$this->waitForReplication();
			}
			$batchValue += $this->getBatchSize();

			$this->output( "{$this->numRowsProcessed} done.\n" );
		} while ( $maxPageId >= $batchValue );

		if ( !$this->dryRun ) {
			$this->output( "{$this->numRowsProcessed} rows processed\n" );
		}
	}
}

// @codeCoverageIgnoreStart
$maintClass = UpdateCollation::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
