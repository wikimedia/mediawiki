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

/**
 * Maintenance script that will find all rows in the categorylinks table
 * whose collation is out-of-date.
 *
 * @ingroup Maintenance
 */
class UpdateCollation extends Maintenance {
	const BATCH_SIZE = 100; // Number of rows to process in one batch
	const SYNC_INTERVAL = 5; // Wait for replica DBs after this many batches

	public $sizeHistogram = [];

	public function __construct() {
		parent::__construct();

		global $wgCategoryCollation;
		$this->addDescription( <<<TEXT
This script will find all rows in the categorylinks table whose collation is
out-of-date (cl_collation != '$wgCategoryCollation') and repopulate cl_sortkey
using the page title and cl_sortkey_prefix.  If all collations are
up-to-date, it will do nothing.
TEXT
		);

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
		$this->addOption( 'dry-run', 'Don\'t actually change the collations, just ' .
			'compile statistics.' );
		$this->addOption( 'verbose-stats', 'Show more statistics.' );
	}

	public function execute() {
		global $wgCategoryCollation;

		$dbw = $this->getDB( DB_MASTER );
		$dbr = $this->getDB( DB_REPLICA );
		$force = $this->getOption( 'force' );
		$dryRun = $this->getOption( 'dry-run' );
		$verboseStats = $this->getOption( 'verbose-stats' );
		if ( $this->hasOption( 'target-collation' ) ) {
			$collationName = $this->getOption( 'target-collation' );
			$collation = Collation::factory( $collationName );
		} else {
			$collationName = $wgCategoryCollation;
			$collation = Collation::singleton();
		}

		// Collation sanity check: in some cases the constructor will work,
		// but this will raise an exception, breaking all category pages
		$collation->getFirstLetter( 'MediaWiki' );

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
			'LIMIT' => self::BATCH_SIZE,
			'ORDER BY' => $orderBy,
			'STRAIGHT_JOIN' // per T58041
		];

		if ( $force ) {
			$collationConds = [];
		} else {
			if ( $this->hasOption( 'previous-collation' ) ) {
				$collationConds['cl_collation'] = $this->getOption( 'previous-collation' );
			} else {
				$collationConds = [ 0 =>
					'cl_collation != ' . $dbw->addQuotes( $collationName )
				];
			}

			$count = $dbr->estimateRowCount(
				'categorylinks',
				'*',
				$collationConds,
				__METHOD__
			);
			// Improve estimate if feasible
			if ( $count < 1000000 ) {
				$count = $dbr->selectField(
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
			if ( $dryRun ) {
				$this->output( "$count rows would be updated.\n" );
			} else {
				$this->output( "Fixing collation for $count rows.\n" );
			}
			wfWaitForSlaves();
		}
		$count = 0;
		$batchCount = 0;
		$batchConds = [];
		do {
			$this->output( "Selecting next " . self::BATCH_SIZE . " rows..." );

			// cl_type must be selected as a number for proper paging because
			// enums suck.
			if ( $dbw->getType() === 'mysql' ) {
				$clType = 'cl_type+0 AS "cl_type_numeric"';
			} else {
				$clType = 'cl_type';
			}
			$res = $dbw->select(
				[ 'categorylinks', 'page' ],
				[ 'cl_from', 'cl_to', 'cl_sortkey_prefix', 'cl_collation',
					'cl_sortkey', $clType,
					'page_namespace', 'page_title'
				],
				array_merge( $collationConds, $batchConds, [ 'cl_from = page_id' ] ),
				__METHOD__,
				$options
			);
			$this->output( " processing..." );

			if ( !$dryRun ) {
				$this->beginTransaction( $dbw, __METHOD__ );
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
				if ( $title->getNamespace() == NS_CATEGORY ) {
					$type = 'subcat';
				} elseif ( $title->getNamespace() == NS_FILE ) {
					$type = 'file';
				} else {
					$type = 'page';
				}
				$newSortKey = $collation->getSortKey(
					$title->getCategorySortkey( $prefix ) );
				if ( $verboseStats ) {
					$this->updateSortKeySizeHistogram( $newSortKey );
				}

				if ( !$dryRun ) {
					$dbw->update(
						'categorylinks',
						[
							'cl_sortkey' => $newSortKey,
							'cl_sortkey_prefix' => $prefix,
							'cl_collation' => $collationName,
							'cl_type' => $type,
							'cl_timestamp = cl_timestamp',
						],
						[ 'cl_from' => $row->cl_from, 'cl_to' => $row->cl_to ],
						__METHOD__
					);
				}
				if ( $row ) {
					$batchConds = [ $this->getBatchCondition( $row, $dbw ) ];
				}
			}
			if ( !$dryRun ) {
				$this->commitTransaction( $dbw, __METHOD__ );
			}

			$count += $res->numRows();
			$this->output( "$count done.\n" );

			if ( !$dryRun && ++$batchCount % self::SYNC_INTERVAL == 0 ) {
				$this->output( "Waiting for replica DBs ... " );
				wfWaitForSlaves();
				$this->output( "done\n" );
			}
		} while ( $res->numRows() == self::BATCH_SIZE );

		$this->output( "$count rows processed\n" );

		if ( $verboseStats ) {
			$this->output( "\n" );
			$this->showSortKeySizeHistogram();
		}
	}

	/**
	 * Return an SQL expression selecting rows which sort above the given row,
	 * assuming an ordering of cl_collation, cl_to, cl_type, cl_from
	 * @param stdClass $row
	 * @param Database $dbw
	 * @return string
	 */
	function getBatchCondition( $row, $dbw ) {
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
				$cond .= " OR ($prefix AND $inequality)";
				$prefix .= " AND $equality";
			}
		}

		return $cond;
	}

	function updateSortKeySizeHistogram( $key ) {
		$length = strlen( $key );
		if ( !isset( $this->sizeHistogram[$length] ) ) {
			$this->sizeHistogram[$length] = 0;
		}
		$this->sizeHistogram[$length]++;
	}

	function showSortKeySizeHistogram() {
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
			if ( !isset( $this->sizeHistogram[$i] ) ) {
				$val = 0;
			} else {
				$val = $this->sizeHistogram[$i];
			}
			for ( $coarseIndex = 0; $coarseIndex < $numBins - 1; $coarseIndex++ ) {
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
		$scale = 60 / $maxBinVal;
		$prevBoundary = 0;
		for ( $coarseIndex = 0; $coarseIndex < $numBins; $coarseIndex++ ) {
			if ( !isset( $coarseHistogram[$coarseIndex] ) ) {
				$val = 0;
			} else {
				$val = $coarseHistogram[$coarseIndex];
			}
			$boundary = $coarseBoundaries[$coarseIndex];
			$this->output( sprintf( "%-10s %-10d |%s\n",
				$prevBoundary . '-' . ( $boundary - 1 ) . ': ',
				$val,
				str_repeat( '*', $scale * $val ) ) );
			$prevBoundary = $boundary;
		}
	}
}

$maintClass = "UpdateCollation";
require_once RUN_MAINTENANCE_IF_MAIN;
