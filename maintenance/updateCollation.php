<?php
/**
 * Find all pages in the categorylinks table where collation data
 * is not fully populated for all collations, and populate cl_sortkey
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

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script that will find all rows in the categorylinks table
 * whose collation is out-of-date.
 *
 * @ingroup Maintenance
 */
class UpdateCollation extends Maintenance {
	const BATCH_SIZE = 10000; // Number of rows to process in one batch
	const SYNC_INTERVAL = 20; // Wait for slaves after this many batches

	public $sizeHistogram = array();

	public function __construct() {
		parent::__construct();

		$this->mDescription = <<<TEXT
This script will find all pages in the categorylinks table where collation
data is not fully populated for all collations, and populate cl_sortkey
using the page title and cl_sortkey_prefix.  If all collations are
up-to-date, it will do nothing.
TEXT;

		$this->addOption( 'force', 'Run on all rows, even if the collation is ' .
			'supposed to be up-to-date.' );
		$this->addOption( 'previous-collation', 'Set the previous value of ' .
			'$wgCategoryCollations here. Usually you do not need to use this, ' .
			'as it has been stored automatically in previous run of this script. ',
			false, true );
		$this->addOption( 'no-previous-collation', 'Do not use previous collation ' .
			'information, which will allow the script to use better queries, but ' .
			'it may miss out-of-date rows with different, even older collations. ' );
		$this->addOption( 'target-collation', 'Set this to the new collation type to ' .
			'use instead of $wgCategoryCollation. Usually you should not use this, ' .
			'you should just update $wgCategoryCollation in LocalSettings.php.' .
			'Join all values with "," if multiple target collations are to be used.',
			false, true );
		$this->addOption( 'dry-run', 'Don\'t actually change the collations, just ' .
			'compile statistics.' );
		$this->addOption( 'verbose-stats', 'Show more statistics.' );
	}

	public function writeUpdateLog( $dbw, $dryRun, $collationNames ) {
		if ( !$dryRun ) {
			$dbw->replace( 'updatelog', array( 'ul_key' ), array(
				'ul_key' => 'collations',
				'ul_value' => serialize( $collationNames ),
			), __METHOD__ );
		}
	}

	public function execute() {
		global $wgCategoryCollations;

		$dbw = $this->getDB( DB_MASTER );
		$force = $this->getOption( 'force' );
		$dryRun = $this->getOption( 'dry-run' );
		$verboseStats = $this->getOption( 'verbose-stats' );
		if ( $this->hasOption( 'target-collation' ) ) {
			$collationNames = explode( ',', $this->getOption( 'target-collation' ) );
		} else {
			$collationNames = $wgCategoryCollations;
		}
		if ( $this->hasOption( 'previous-collation' ) ) {
			$previousCollationNames = explode( ',', $this->getOption( 'previous-collation' ) );
		} else {
			$previousCollationNames = array();
			if ( !$this->hasOption( 'no-previous-collation' ) ) {
				$previousCollations = $dbw->selectField(
					'updatelog',
					'ul_value',
					array( 'ul_key' => 'collations' ),
					__METHOD__
				);
				if ( $previousCollations !== false ) {
					$previousCollations = unserialize( $previousCollations );
				}
				if ( $previousCollations !== false ) {
					$previousCollationNames = $previousCollations;
				}
			}
		}
		$collationRemovals = array_diff( $previousCollationNames, $collationNames );

		// Collation sanity check: in some cases the constructor will work,
		// but this will raise an exception, breaking all category pages
		foreach ( Collation::getInstances() as $collation ) {
			$collation->getFirstLetter( 'MediaWiki' );
		}

		$options = array(
			'STRAIGHT_JOIN',
			'GROUP BY' => array( 'cl_to', 'cl_type', 'cl_from' ),
		);

		$previousCollationConds = array();
		$vars = array();
		if ( $force || $dryRun ) {
			$options['ORDER BY'] = 'cl_to, cl_type, cl_from';
		} else {
			if ( count( $collationRemovals ) > 0 ) {
				$previousCollationConds['cl_collation'] = $collationRemovals;
				$countVars = array( 'cl_from' );
			} else {
				$countVars = $vars = array(
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
			}

			$count = $dbw->selectField(
				' (' . $dbw->selectSQLText(
					array( 'ocl' => 'categorylinks' ),
					$countVars,
					$previousCollationConds,
					__METHOD__,
					$options
				) . ') t',
				'COUNT(*)',
				array(),
				__METHOD__
			);
			if ( $count == 0 ) {
				$this->output( "Collations up-to-date.\n" );

				$this->writeUpdateLog( $dbw, $dryRun, $collationNames );
				return;
			}
			$this->output( "Fixing collation for $count pairs.\n" );
		}

		$count = 0;
		$batchCount = 0;
		$batchConds = array();
		$vars += array(
			'cl_from', 'cl_to', 'cl_type', 'page_namespace', 'page_title'
		);
		$options['GROUP BY'] = array(
			'cl_to', 'cl_type', 'cl_from', 'page_namespace', 'page_title'
		);
		$options['LIMIT'] = self::BATCH_SIZE;
		do {
			$this->output( "Selecting next " . self::BATCH_SIZE . " pages..." );
			$res = $dbw->select(
				array( 'ocl' => 'categorylinks', 'page' ),
				$vars,
				array_merge(
					$batchConds, $previousCollationConds,
					array( 'cl_from = page_id' )
				),
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
				$prefixes = array();
				foreach ( $clres as $clrow ) {
					if ( !$clrow->cl_collation ) {
						# This is an old-style row, so the sortkey needs to be
						# converted.
						if ( $clrow->cl_sortkey == $title->getText()
							|| $clrow->cl_sortkey == $title->getPrefixedText() ) {
							$prefixes[''] = '';
						} else {
							# Custom sortkey, use it as a prefix
							$prefixes[''] = $clrow->cl_sortkey;
						}
					} else {
						$prefixes[$clrow->cl_collation] = $clrow->cl_sortkey_prefix;
					}
				}

				$collationsToTry = array_unique( array_merge( $collationNames, array_keys( $prefixes ) ) );
				foreach ( $collationsToTry as $collationToTry ) {
					if ( isset( $prefixes[$collationToTry] ) ) {
						$defaultPrefix = $prefixes[$collationToTry];
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
					if ( isset( $prefixes[$collationName] ) ) {
						$collationPrefix = $prefixes[$collationName];
						// This "unset" need to happen before the following "if",
						// so don't move these statements.
						unset( $prefixes[$collationName] );
						if ( !$verboseStats && !$force ) {
							// It's unnecessary to proceed to calculate
							// a sort key when it's not going to be used.
							continue;
						}
						$insert = false;
					} else {
						$collationPrefix = $defaultPrefix;
						$insert = true;
					}
					$newSortKey = Collation::getInstance( $collationName )->getSortKey(
						$title->getCategorySortkey( $collationPrefix ) );
					if ( $verboseStats ) {
						$this->updateSortKeySizeHistogram( $newSortKey );
					}
					if ( !$dryRun ) {
						if ( $insert ) {
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
					}
				}

				if ( !$dryRun ) {
					foreach ( $prefixes as $collationName => $collationPrefix ) {
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
				if ( ( $force || $dryRun ) && $row ) {
					$batchConds = array( $this->getBatchCondition( $row, $dbw ) );
				}
			}
			if ( !$dryRun ) {
				$dbw->commit( __METHOD__ );
			}

			$count += $res->numRows();
			$this->output( "$count done.\n" );

			if ( !$dryRun && ++$batchCount % self::SYNC_INTERVAL == 0 ) {
				$this->output( "Waiting for slaves ... " );
				wfWaitForSlaves();
				$this->output( "done\n" );
			}
		} while ( $res->numRows() == self::BATCH_SIZE );

		$this->output( "$count pages processed\n" );
		$this->writeUpdateLog( $dbw, $dryRun, $collationNames );

		if ( $verboseStats ) {
			$this->output( "\n" );
			$this->showSortKeySizeHistogram();
		}
	}

	/**
	 * Return an SQL expression selecting rows which sort above the given row,
	 * assuming an ordering of cl_to, cl_type, cl_from
	 */
	function getBatchCondition( $row, $dbw ) {
		$fields = array( 'cl_to', 'cl_type', 'cl_from' );
		$first = true;
		$cond = false;
		$prefix = false;
		foreach ( $fields as $field ) {
			$encValue = $dbw->addQuotes( $row->$field );
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
require_once RUN_MAINTENANCE_IF_MAIN;
