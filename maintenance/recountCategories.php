<?php
/**
 * Refreshes category counts.
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

require_once __DIR__ . '/Maintenance.php';

use MediaWiki\MediaWikiServices;

/**
 * Maintenance script that refreshes category membership counts in the category
 * table.
 *
 * (The populateCategory.php script will also recalculate counts, but
 * recountCategories only updates rows that need to be updated, making it more
 * efficient.)
 *
 * @ingroup Maintenance
 */
class RecountCategories extends Maintenance {
	/** @var string */
	private $mode;

	/** @var int */
	private $minimumId;

	public function __construct() {
		parent::__construct();
		$this->addDescription( <<<'TEXT'
This script refreshes the category membership counts stored in the category
table. As time passes, these counts often drift from the actual number of
category members. The script identifies rows where the value in the category
table does not match the number of categorylinks rows for that category, and
updates the category table accordingly.

To fully refresh the data in the category table, you need to run this script
three times: once in each mode. Alternatively, just one mode can be run if
required.
TEXT
		);
		$this->addOption(
			'mode',
			'(REQUIRED) Which category count column to recompute: "pages", "subcats" or "files".',
			true,
			true
		);
		$this->addOption(
			'begin',
			'Only recount categories with cat_id greater than the given value',
			false,
			true
		);
		$this->addOption(
			'throttle',
			'Wait this many milliseconds after each batch. Default: 0',
			false,
			true
		);

		$this->setBatchSize( 500 );
	}

	public function execute() {
		$this->mode = $this->getOption( 'mode' );
		if ( !in_array( $this->mode, [ 'pages', 'subcats', 'files' ] ) ) {
			$this->fatalError( 'Please specify a valid mode: one of "pages", "subcats" or "files".' );
		}

		$this->minimumId = intval( $this->getOption( 'begin', 0 ) );

		// do the work, batch by batch
		$affectedRows = 0;
		while ( ( $result = $this->doWork() ) !== false ) {
			$affectedRows += $result;
			usleep( $this->getOption( 'throttle', 0 ) * 1000 );
		}

		$this->output( "Done! Updated the {$this->mode} counts of $affectedRows categories.\n" .
			"Now run the script using the other --mode options if you haven't already.\n" );
		if ( $this->mode === 'pages' ) {
			$this->output(
				"Also run 'php cleanupEmptyCategories.php --mode remove' to remove empty,\n" .
				"nonexistent categories from the category table.\n\n" );
		}
	}

	protected function doWork() {
		$this->output( "Finding up to {$this->getBatchSize()} drifted rows " .
			"starting at cat_id {$this->getBatchSize()}...\n" );

		$countingConds = [ 'cl_to = cat_title' ];
		if ( $this->mode === 'subcats' ) {
			$countingConds['cl_type'] = 'subcat';
		} elseif ( $this->mode === 'files' ) {
			$countingConds['cl_type'] = 'file';
		}

		$dbr = $this->getDB( DB_REPLICA, 'vslow' );
		$countingSubquery = $dbr->selectSQLText( 'categorylinks',
			'COUNT(*)',
			$countingConds,
			__METHOD__ );

		// First, let's find out which categories have drifted and need to be updated.
		// The query counts the categorylinks for each category on the replica DB,
		// but this data can't be used for updating the master, so we don't include it
		// in the results.
		$idsToUpdate = $dbr->selectFieldValues( 'category',
			'cat_id',
			[
				'cat_id > ' . $this->minimumId,
				"cat_{$this->mode} != ($countingSubquery)"
			],
			__METHOD__,
			[ 'LIMIT' => $this->getBatchSize() ]
		);
		if ( !$idsToUpdate ) {
			return false;
		}
		$this->output( "Updating cat_{$this->mode} field on " .
			count( $idsToUpdate ) . " rows...\n" );

		// In the next batch, start where this query left off. The rows selected
		// in this iteration shouldn't be selected again after being updated, but
		// we still keep track of where we are up to, as extra protection against
		// infinite loops.
		$this->minimumId = end( $idsToUpdate );

		// Now, on master, find the correct counts for these categories.
		$dbw = $this->getDB( DB_MASTER );
		$res = $dbw->select( 'category',
			[ 'cat_id', 'count' => "($countingSubquery)" ],
			[ 'cat_id' => $idsToUpdate ],
			__METHOD__ );

		// Update the category counts on the rows we just identified.
		// This logic is equivalent to Category::refreshCounts, except here, we
		// don't remove rows when cat_pages is zero and the category description page
		// doesn't exist - instead we print a suggestion to run
		// cleanupEmptyCategories.php.
		$affectedRows = 0;
		foreach ( $res as $row ) {
			$dbw->update( 'category',
				[ "cat_{$this->mode}" => $row->count ],
				[
					'cat_id' => $row->cat_id,
					"cat_{$this->mode} != " . (int)( $row->count ),
				],
				__METHOD__ );
			$affectedRows += $dbw->affectedRows();
		}

		MediaWikiServices::getInstance()->getDBLoadBalancerFactory()->waitForReplication();

		return $affectedRows;
	}
}

$maintClass = RecountCategories::class;
require_once RUN_MAINTENANCE_IF_MAIN;
