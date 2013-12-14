<?php
/**
 * Remove hidden, unused or default preferences from the database.
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
 * @author TyA <tya.wiki@gmail.com>
 * @see [[bugzilla:30976]]
 * @ingroup Maintenance
 */

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script that removes hidden preferences from the database.
 *
 * @ingroup Maintenance
 */
class CleanupPreferences extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = 'Delete hidden, unused or default preferences from database';
		$this->addOption( 'dry-run', 'Print only a statistic over deletable rows' );
		$this->addOption( 'start',
			'Key to start the script from in format "user" or "user,property"', false, true );
		$this->addOption( 'hidden', 'Remove also hidden prefs (from $wgHiddenPrefs)' );
		$this->setBatchSize( 150 );
	}

	public function execute() {
		global $wgHiddenPrefs;

		$dbw = wfGetDB( DB_MASTER );
		$dryRun = $this->hasOption( 'dry-run' );
		$hidden = $this->hasOption( 'hidden' );

		$defaultOptions = User::getDefaultOptions();

		$cond = array();
		if ( $this->hasOption( 'start' ) ) {
			$start = $this->getOption( 'start' );
			$startArray = explode( ',', $start, 2 );
			$cond = $this->buildSelectCond( $dbw, $startArray[0],
				count( $startArray ) >= 2 ? $startArray[1] : null );
			$this->output( "Start by > " . $start . "\n" );
		}

		$countAll = 0;
		$count = 0;
		$deletedAll = 0;
		$deleted = 0;
		$statsDeletedProperty = array();
		do {
			// select next batch
			$res = $dbw->select(
				'user_properties',
				array( 'up_user', 'up_property', 'up_value' ),
				$cond,
				__METHOD__,
				array( 'LIMIT' => $this->mBatchSize, 'ORDER BY' => array( 'up_user', 'up_property' ) )
			);
			$count = $res->numRows();
			$countAll += $count;

			$lastRow = null;
			$deleteRows = array();
			foreach ( $res as $row ) {
				$deleteTask = false;
				$property = $row->up_property;

				//Check the row
				if ( !isset( $defaultOptions[$property] ) ) {
					// unused or old preference -> delete
					$deleteTask = 'unused';
				} elseif ( $hidden && isset( $wgHiddenPrefs[$property] ) ) {
					// marked as hidden
					$deleteTask = 'hidden';
				} elseif ( $defaultOptions[$row->up_property] === $row->up_value ) {
					// row has same value as default -> delete
					$deleteTask = 'default';
				}

				if ( $deleteTask !== false ) {
					if ( !isset( $statsDeletedProperty[$deleteTask] ) ) {
						$statsDeletedProperty[$deleteTask] = array();
					}
					if ( !isset( $statsDeletedProperty[$deleteTask][$property] ) ) {
						$statsDeletedProperty[$deleteTask][$property] = 0;
					}
					$statsDeletedProperty[$deleteTask][$property]++;

					$deleteRows[] = $row;
				}

				$lastRow = $row;
			}

			// Delete unused rows
			$toDelete = count( $deleteRows );
			$deletedAll += $toDelete;
			if ( !$dryRun && $toDelete ) {
				$dbw->delete(
					'user_properties',
					$this->buildDeleteCond( $dbw, $deleteRows ),
					__METHOD__
				);

				$this->output( "Deleted " . $toDelete . " rows\n" );

				wfWaitForSlaves();
			}

			// build condition to get the next batch
			if ( $lastRow !== null && $count === $this->mBatchSize ) {
				$cond = $this->buildSelectCond( $dbw, $lastRow->up_user, $lastRow->up_property );
				$this->output( "Last batch ends by " . $lastRow->up_user . "," . $lastRow->up_property . "\n" );
			}
		} while ( $count === $this->mBatchSize );

		$this->output( "\n" );

		if ( $deletedAll > 0 ) {
			if ( $dryRun ) {
				$this->output( "Would delete the following properties:\n" );
				$this->printStats( $statsDeletedProperty );
				$this->output( 'Would delete ' . $deletedAll . ' rows out of ' . $countAll . ' rows' );
			} else {
				$this->output( "Deleted the following properties:\n" );
				$this->printStats( $statsDeletedProperty );
				$this->output( 'Deleted ' . $deletedAll . ' rows out of ' . $countAll . ' rows' );
			}
		} else {
			$this->output( 'Nothing to do!' );
		}
	}

	/**
	 * Build a condition for the select to start the next batch from
	 *
	 * @param DatabaseBase $db A database connection
	 * @param int|string $user
	 * @param string|null $property
	 * @return string
	 */
	private function buildSelectCond( DatabaseBase $db, $user, $property ) {
		if ( $property === null ) {
			return 'up_user > ' . $db->addQuotes( $user );
		}

		return 'up_user > ' . $db->addQuotes( $user ) . ' OR ' .
			'(up_user = ' . $db->addQuotes( $user ) . ' AND ' .
			'up_property > ' . $db->addQuotes( $property ) . ')';
	}

	/**
	 * Build a condition for the delete from a list of rows
	 *
	 * @param DatabaseBase $db A database connection
	 * @param array $rows Rows to build condition from
	 * @return string
	 */
	private function buildDeleteCond( DatabaseBase $db, array $rows ) {
		$conds = array();
		foreach ( $rows as $row ) {
			$conds[] = $db->makeList(
				// the unique index of the table
				array( 'up_user' => $row->up_user, 'up_property' => $row->up_property ),
				LIST_AND
			);
		}

		return $db->makeList( $conds, LIST_OR );
	}

	/**
	 * Print the statistic over deleted or deletable rows
	 *
	 * @param array $statsDeletedProperty
	 */
	private function printStats( array $statsDeletedProperty ) {
		foreach ( $statsDeletedProperty as $type => $stats ) {
			$this->output( $type . ":\n" );
			foreach ( $stats as $property => $count ) {
				$this->output( ' "' . $property . '" (' . $count . ")\n" );
			}
		}
	}
}

$maintClass = 'CleanupPreferences'; // Tells it to run the class
require_once RUN_MAINTENANCE_IF_MAIN;
