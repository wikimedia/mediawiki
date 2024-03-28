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

require_once __DIR__ . '/Maintenance.php';

use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IMaintainableDatabase;
use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\LBFactory;

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
	private $dryRun;

	/** @var bool */
	private $force;

	/** @var bool */
	private $verboseStats;

	/** @var Collation */
	private $collation;

	/** @var string */
	private $collationName;

	/** @var string|null */
	private $targetTable;

	/** @var IDatabase */
	private $dbr;

	/** @var IMaintainableDatabase */
	private $dbw;

	/** @var LBFactory */
	private $lbFactory;

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
		$services = MediaWikiServices::getInstance();
		$this->namespaceInfo = $services->getNamespaceInfo();
		$this->lbFactory = $services->getDBLoadBalancerFactory();

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
		$this->dbw = $this->getDB( DB_PRIMARY );
		$this->dbr = $this->getDB( DB_REPLICA );
		$this->targetTable = $this->getOption( 'target-table' );
	}

	public function execute() {
		$this->init();
		$batchSize = $this->getBatchSize();

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

		// Locally at least, (my local is a rather old version of mysql)
		// mysql seems to filesort if there is both an equality
		// (but not for an inequality) condition on cl_collation in the
		// WHERE and it is also the first item in the ORDER BY.
		if ( $this->hasOption( 'previous-collation' ) ) {
			$orderBy = 'cl_to, cl_type, cl_from';
		} else {
			$orderBy = 'cl_collation, cl_to, cl_type, cl_from';
		}
		$options = [
			'LIMIT' => $batchSize,
			'ORDER BY' => $orderBy,
			'STRAIGHT_JOIN' // per T58041
		];

		$collationConds = [];
		if ( !$this->force && !$this->targetTable ) {
			if ( $this->hasOption( 'previous-collation' ) ) {
				$collationConds['cl_collation'] = $this->getOption( 'previous-collation' );
			} else {
				$collationConds = [
					0 => 'cl_collation != ' . $this->dbr->addQuotes( $this->collationName )
				];
			}

			$count = $this->dbr->estimateRowCount(
				'categorylinks',
				'*',
				$collationConds,
				__METHOD__
			);
			// Improve estimate if feasible
			if ( $count < 1000000 ) {
				$count = $this->dbr->selectField(
					'categorylinks',
					'COUNT(*)',
					$collationConds,
					__METHOD__
				);
			}
			if ( $count == 0 ) {
				$this->output( "Collations up-to-date.\n" );

				return;
			}
			if ( $this->dryRun ) {
				$this->output( "$count rows would be updated.\n" );
			} else {
				$this->output( "Fixing collation for $count rows.\n" );
			}
		}
		$batchConds = [];
		do {
			$this->output( "Selecting next $batchSize rows..." );

			// cl_type must be selected as a number for proper paging because
			// enums suck.
			if ( $this->dbw->getType() === 'mysql' ) {
				$clType = 'cl_type+0 AS "cl_type_numeric"';
			} else {
				$clType = 'cl_type';
			}
			$res = $this->dbw->select(
				[ 'categorylinks', 'page' ],
				[
					'cl_from', 'cl_to', 'cl_sortkey_prefix', 'cl_collation',
					'cl_sortkey', $clType, 'cl_timestamp',
					'page_namespace', 'page_title'
				],
				array_merge( $collationConds, $batchConds, [ 'cl_from = page_id' ] ),
				__METHOD__,
				$options
			);
			$this->output( " processing..." );

			if ( $res->numRows() ) {
				if ( $this->targetTable ) {
					$this->copyBatch( $res );
				} else {
					$this->updateBatch( $res );
				}
				$res->seek( $res->numRows() - 1 );
				$lastRow = $res->fetchObject();
				$batchConds = [ $this->getBatchCondition( $lastRow, $this->dbw ) ];
			}

			if ( $this->dryRun ) {
				$this->output( "{$this->numRowsProcessed} rows would be updated so far.\n" );
			} else {
				$this->output( "{$this->numRowsProcessed} done.\n" );
			}
		} while ( $res->numRows() == $batchSize );

		if ( !$this->dryRun ) {
			$this->output( "{$this->numRowsProcessed} rows processed\n" );
		}

		if ( $this->verboseStats ) {
			$this->output( "\n" );
			$this->showSortKeySizeHistogram();
		}
	}

	/**
	 * Return an SQL expression selecting rows which sort above the given row,
	 * assuming an ordering of cl_collation, cl_to, cl_type, cl_from
	 * @param stdClass $row
	 * @param IDatabase $dbw
	 * @return string
	 */
	private function getBatchCondition( $row, $dbw ) {
		if ( $this->hasOption( 'previous-collation' ) ) {
			$fields = [ 'cl_to', 'cl_type', 'cl_from' ];
		} else {
			$fields = [ 'cl_collation', 'cl_to', 'cl_type', 'cl_from' ];
		}
		$first = true;
		$cond = false;
		$prefix = false;
		foreach ( $fields as $field ) {
			if ( $dbw->getType() === 'mysql' && $field === 'cl_type' ) {
				// Range conditions with enums are weird in mysql
				// This must be a numeric literal, or it won't work.
				$encValue = intval( $row->cl_type_numeric );
			} else {
				$encValue = $dbw->addQuotes( $row->$field );
			}
			$inequality = "$field > $encValue";
			$equality = "$field = $encValue";
			if ( $first ) {
				$cond = $inequality;
				$prefix = $equality;
				$first = false;
			} else {
				// @phan-suppress-next-line PhanTypeSuspiciousStringExpression False positive
				$cond .= " OR ($prefix AND $inequality)";
				$prefix .= " AND $equality";
			}
		}

		return $cond;
	}

	/**
	 * Update a set of rows in the categorylinks table
	 *
	 * @param IResultWrapper $res The rows to update
	 */
	private function updateBatch( $res ) {
		if ( !$this->dryRun ) {
			$this->beginTransaction( $this->dbw, __METHOD__ );
		}
		foreach ( $res as $row ) {
			$title = Title::newFromRow( $row );
			if ( !$row->cl_collation ) {
				# This is an old-style row, so the sortkey needs to be
				# converted.
				if ( $row->cl_sortkey == $title->getText()
					|| $row->cl_sortkey == $title->getPrefixedText()
				) {
					$prefix = '';
				} else {
					# Custom sortkey, use it as a prefix
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
				$this->dbw->update(
					'categorylinks',
					[
						'cl_sortkey' => $newSortKey,
						'cl_sortkey_prefix' => $prefix,
						'cl_collation' => $this->collationName,
						'cl_type' => $type,
						'cl_timestamp = cl_timestamp',
					],
					[ 'cl_from' => $row->cl_from, 'cl_to' => $row->cl_to ],
					__METHOD__
				);
				$this->numRowsProcessed++;
			}
		}
		if ( !$this->dryRun ) {
			$this->commitTransaction( $this->dbw, __METHOD__ );
		}
	}

	/**
	 * Copy a set of rows to the target table
	 *
	 * @param IResultWrapper $res
	 */
	private function copyBatch( $res ) {
		$sortKeyInputs = [];
		foreach ( $res as $row ) {
			$title = Title::newFromRow( $row );
			$sortKeyInputs[] = $title->getCategorySortkey( $row->cl_sortkey_prefix );
		}
		$sortKeys = $this->collation->getSortKeys( $sortKeyInputs );
		$rowsToInsert = [];
		foreach ( $res as $i => $row ) {
			if ( !isset( $sortKeys[$i] ) ) {
				throw new MWException( 'Unable to get sort key' );
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
			$this->dbw->insert( $this->targetTable, $rowsToInsert, __METHOD__, [ 'IGNORE' ] );
			$this->numRowsProcessed += $this->dbw->affectedRows();
			$this->commitTransaction( $this->dbw, __METHOD__ );
		}
	}

	/**
	 * Update the verbose statistics
	 *
	 * @param string $key
	 */
	private function updateSortKeySizeHistogram( $key ) {
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
		if ( $maxLength == 0 ) {
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
			if ( $coarseIndex == $numBins - 1 ) {
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
			$this->output( sprintf( "%-10s %-10d |%s\n",
				$prevBoundary . '-' . ( $boundary - 1 ) . ': ',
				$val,
				str_repeat( '*', $scale * $val ) ) );
			$prevBoundary = $boundary;
		}
	}
}

$maintClass = UpdateCollation::class;
require_once RUN_MAINTENANCE_IF_MAIN;
