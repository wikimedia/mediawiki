<?php
/**
 * Clean up empty categories in the category table.
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

use MediaWiki\Category\Category;
use MediaWiki\Maintenance\LoggedUpdateMaintenance;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script to clean up empty categories in the category table.
 *
 * @ingroup Maintenance
 * @since 1.28
 */
class CleanupEmptyCategories extends LoggedUpdateMaintenance {

	public function __construct() {
		parent::__construct();
		$this->addDescription(
			<<<TEXT
This script will clean up the category table by removing entries for empty
categories without a description page and adding entries for empty categories
with a description page. It will print out progress indicators every batch. The
script is perfectly safe to run on large, live wikis, and running it multiple
times is harmless. You may want to use the throttling options if it's causing
too much load; they will not affect correctness.

If the script is stopped and later resumed, you can use the --mode and --begin
options with the last printed progress indicator to pick up where you left off.

When the script has finished, it will make a note of this in the database, and
will not run again without the --force option.
TEXT
		);

		$this->addOption(
			'mode',
			'"add" empty categories with description pages, "remove" empty categories '
			. 'without description pages, or "both"',
			false,
			true
		);
		$this->addOption(
			'begin',
			'Only do categories whose names are alphabetically after the provided name',
			false,
			true
		);
		$this->addOption(
			'throttle',
			'Wait this many milliseconds after each batch. Default: 0',
			false,
			true
		);
	}

	/** @inheritDoc */
	protected function getUpdateKey() {
		return 'cleanup empty categories';
	}

	/** @inheritDoc */
	protected function doDBUpdates() {
		$mode = $this->getOption( 'mode', 'both' );
		$begin = $this->getOption( 'begin', '' );
		$throttle = $this->getOption( 'throttle', 0 );

		if ( !in_array( $mode, [ 'add', 'remove', 'both' ] ) ) {
			$this->output( "--mode must be 'add', 'remove', or 'both'.\n" );
			return false;
		}

		$dbw = $this->getPrimaryDB();

		$throttle = intval( $throttle );

		if ( $mode === 'add' || $mode === 'both' ) {
			if ( $begin !== '' ) {
				$where = [ $dbw->expr( 'page_title', '>', $begin ) ];
			} else {
				$where = [];
			}

			$this->output( "Adding empty categories with description pages...\n" );
			while ( true ) {
				# Find which category to update
				$rows = $dbw->newSelectQueryBuilder()
					->select( 'page_title' )
					->from( 'page' )
					->leftJoin( 'category', null, 'page_title = cat_title' )
					->where( $where )
					->andWhere( [ 'page_namespace' => NS_CATEGORY, 'cat_title' => null ] )
					->orderBy( 'page_title' )
					->limit( $this->getBatchSize() )
					->caller( __METHOD__ )->fetchResultSet();
				if ( !$rows || $rows->numRows() <= 0 ) {
					break;
				}

				foreach ( $rows as $row ) {
					$name = $row->page_title;
					$where = [ $dbw->expr( 'page_title', '>', $name ) ];

					# Use the row to update the category count
					$cat = Category::newFromName( $name );
					if ( !is_object( $cat ) ) {
						$this->output( "The category named $name is not valid?!\n" );
					} else {
						$cat->refreshCounts();
					}
				}
				// @phan-suppress-next-line PhanPossiblyUndeclaredVariable $rows has at at least one item
				$this->output( "--mode=$mode --begin=$name\n" );

				$this->waitForReplication();
				usleep( $throttle * 1000 );
			}

			$begin = '';
		}

		if ( $mode === 'remove' || $mode === 'both' ) {
			if ( $begin !== '' ) {
				$where = [ $dbw->expr( 'cat_title', '>', $begin ) ];
			} else {
				$where = [];
			}

			$this->output( "Removing empty categories without description pages...\n" );
			while ( true ) {
				# Find which category to update
				$rows = $dbw->newSelectQueryBuilder()
					->select( 'cat_title' )
					->from( 'category' )
					->leftJoin( 'page', null, [ 'page_namespace' => NS_CATEGORY, 'page_title = cat_title' ] )
					->where( $where )
					->andWhere( [ 'page_title' => null, 'cat_pages' => 0 ] )
					->orderBy( 'cat_title' )
					->limit( $this->getBatchSize() )
					->caller( __METHOD__ )->fetchResultSet();
				if ( !$rows || $rows->numRows() <= 0 ) {
					break;
				}
				foreach ( $rows as $row ) {
					$name = $row->cat_title;
					$where = [ $dbw->expr( 'cat_title', '>', $name ) ];

					# Use the row to update the category count
					$cat = Category::newFromName( $name );
					if ( !is_object( $cat ) ) {
						$this->output( "The category named $name is not valid?!\n" );
					} else {
						$cat->refreshCounts();
					}
				}

				// @phan-suppress-next-line PhanPossiblyUndeclaredVariable rows contains at least one item
				$this->output( "--mode=remove --begin=$name\n" );

				$this->waitForReplication();
				usleep( $throttle * 1000 );
			}
		}

		$this->output( "Category cleanup complete.\n" );

		return true;
	}
}

// @codeCoverageIgnoreStart
$maintClass = CleanupEmptyCategories::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
