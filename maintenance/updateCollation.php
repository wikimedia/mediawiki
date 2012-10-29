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

#$optionsWithArgs = array( 'begin', 'max-slave-lag' );

require_once( __DIR__ . '/Maintenance.php' );

/**
 * Maintenance script that will find all rows in the categorylinks table
 * whose collation is out-of-date.
 *
 * @ingroup Maintenance
 */
class UpdateCollation extends Maintenance {
	const BATCH_SIZE = 50; // Number of rows to process in one batch
	const SYNC_INTERVAL = 20; // Wait for slaves after this many batches

	public $sizeHistogram = array();

	public function __construct() {
		parent::__construct();

		$this->mDescription = <<<TEXT
This script will find all pages in the categorylinks table where collation
data is not fully populated for all collations, and populate cl_sortkey
using the page title and cl_sortkey_prefix.  If everything's collation is
up-to-date, it will do nothing.
TEXT;

		$this->addOption( 'force', 'Run on all rows, even if the collation is ' .
			'supposed to be up-to-date. Unused rows are also cleared.' );
		$this->addOption( 'target-collation', 'Set this to the new collation type to ' .
			'use instead of $wgCategoryCollation. Usually you should not use this, ' .
			'you should just update $wgCategoryCollation in LocalSettings.php.' .
			'Join all values with "," if multiple target collations are to be used.',
			false, true );
		$this->addOption( 'dry-run', 'Don\'t actually change the collations, just ' .
			'compile statistics.' );
		$this->addOption( 'verbose-stats', 'Show more statistics.' );
	}

	public function execute() {
		global $wgCategoryCollations, $wgMiserMode;

		$dbw = $this->getDB( DB_MASTER );
		$force = $this->getOption( 'force' );
		$dryRun = $this->getOption( 'dry-run' );
		$verboseStats = $this->getOption( 'verbose-stats' );
		if ( $this->hasOption( 'target-collation' ) ) {
			$collationNames = explode( ',', $this->getOption( 'target-collation' ) );
		} else {
			$collationNames = $wgCategoryCollations;
		}

		$options = array(
			'LIMIT' => self::BATCH_SIZE,
			'STRAIGHT_JOIN',
			'GROUP BY' => array( 'cl_from', 'cl_to' ),
		);

		if ( $force || $dryRun ) {
			$options['ORDER BY'] = 'cl_from, cl_to';
			$vars = array();
		} else {
			$vars = array(
				'count_all' => 'COUNT(*)',
				'count_cur' => '(' . $dbw->selectSQLText(
					array( 'icl' => 'categorylinks' ),
					'COUNT(*)',
					array(
						'icl.cl_from = ocl.cl_from',
						'icl.cl_to = ocl.cl_to',
						'icl.cl_collation' => $collationNames,
					),
					__METHOD__
				) . ')',
			);
			$options['HAVING'] = $dbw->makeList( array(
				'count_all <> count_cur',
				'count_cur <> ' . count( $collationNames ),
			), LIST_OR );

			// DatabaseBase::estimateRowCount() works incorrectly. Bug 41705.
			if ( !( $wgMiserMode && $dbw instanceof DatabaseMysql ) ) {
				$count = $dbw->selectField(
					array( 't' => '(' . $dbw->selectSQLText(
						array( 'ocl' => 'categorylinks' ),
						$vars,
						array(),
						__METHOD__,
						$options
					) . ')' ),
					'COUNT(*)',
					array(),
					__METHOD__
				);
			} else {
				$count = $dbw->estimateRowCount(
					array( 'ocl' => 'categorylinks' ),
					$vars,
					array(),
					__METHOD__,
					$options
				);
			}
			if ( $count == 0 ) {
				$this->output( "Collations up-to-date.\n" );
				return;
			}
			$this->output( "Fixing collation for $count pairs.\n" );
		}

		$count = 0;
		$batchCount = 0;
		$batchConds = array();
		$vars += array(
			'cl_from', 'cl_to', 'page_namespace', 'page_title'
		);
		do {
			$this->output( "Selecting next " . self::BATCH_SIZE . " rows..." );
			$res = $dbw->select(
				array( 'ocl' => 'categorylinks', 'page' ),
				$vars,
				array_merge( $batchConds, array( 'cl_from = page_id' ) ),
				__METHOD__,
				$options
			);
			$this->output( " processing..." );

			if ( !$dryRun ) {
				$dbw->begin( __METHOD__ );
			}
			foreach ( $res as $row ) {
				$title = Title::newFromRow( $row );
				$clres = $dbw->select(
					'categorylinks',
					array( 'cl_collation', 'cl_sortkey_prefix', 'cl_sortkey' ),
					array(
						'cl_from' => $row->cl_from,
						'cl_to' => $row->cl_to,
					),
					__METHOD__
				);
				$prefix = array();
				while ( $clrow = $dbw->fetchObject( $clres ) ) {
					if ( !$clrow->cl_collation ) {
						# This is an old-style row, so the sortkey needs to be
						# converted.
						if ( $clrow->cl_sortkey == $title->getText()
							|| $clrow->cl_sortkey == $title->getPrefixedText() ) {
							$prefix[''] = '';
						} else {
							# Custom sortkey, use it as a prefix
							$prefix[''] = $clrow->cl_sortkey;
						}
					} else {
						$prefix[$clrow->cl_collation] = $clrow->cl_sortkey_prefix;
					}
				}

				$collationsToTry = array_unique( array_merge( $collationNames, array_keys( $prefix ) ) );
				foreach ( $collationsToTry as $collationToTry ) {
					if ( isset( $prefix[$collationToTry] ) ) {
						$defaultPrefix = $prefix[$collationToTry];
						break;
					}
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

				foreach ( $collationNames as $collationName ) {
					if ( isset( $prefix[$collationName] ) ) {
						$collationPrefix = $prefix[$collationName];
						$doUpdate = false;
					} else {
						$collationPrefix = $defaultPrefix;
						$doUpdate = true;
					}
					$newSortKey = Collation::getInstance( $collationName )->getSortKey(
						$title->getCategorySortkey( $collationPrefix ) );
					if ( $verboseStats ) {
						$this->updateSortKeySizeHistogram( $newSortKey );
					}
					if ( !$dryRun ) {
						if ( $doUpdate ) {
							$dbw->insert(
								'categorylinks',
								array(
									'cl_from' => $row->cl_from,
									'cl_to' => $row->cl_to,
									'cl_collation' => $collationName,
									'cl_sortkey_prefix' => $collationPrefix,
									'cl_sortkey' => $newSortKey,
									'cl_type' => $type,
									'cl_timestamp' => $dbw->timestamp(),
								),
								__METHOD__
							);
						} elseif ( $force ) {
							$dbw->update(
								'categorylinks',
								array(
									'cl_sortkey_prefix' => $collationPrefix,
									'cl_sortkey' => $newSortKey,
									'cl_type' => $type,
								),
								array(
									'cl_from' => $row->cl_from,
									'cl_to' => $row->cl_to,
									'cl_collation' => $collationName,
								),
								__METHOD__
							);
						}
						unset( $prefix[$collationName] );
					}
				}

				if ( !$dryRun ) {
					foreach ( $prefix as $collationName => $collationPrefix ) {
						$dbw->delete(
							'categorylinks',
							array(
								'cl_from' => $row->cl_from,
								'cl_to' => $row->cl_to,
								'cl_collation' => $collationName,
							),
							__METHOD__
						);
					}
				}
			}
			if ( !$dryRun ) {
				$dbw->commit( __METHOD__ );
			}

			if ( ( $force || $dryRun ) && $row ) {
				$encFrom = $dbw->addQuotes( $row->cl_from );
				$encTo = $dbw->addQuotes( $row->cl_to );
				$batchConds = array(
					"(cl_from = $encFrom AND cl_to > $encTo) " .
					" OR cl_from > $encFrom" );
			}

			$count += $res->numRows();
			$this->output( "$count done.\n" );

			if ( !$dryRun && ++$batchCount % self::SYNC_INTERVAL == 0 ) {
				$this->output( "Waiting for slaves ... " );
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
		$coarseBoundaries = array();
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
require_once( RUN_MAINTENANCE_IF_MAIN );
