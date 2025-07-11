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

use MediaWiki\MainConfigNames;
use MediaWiki\Maintenance\Maintenance;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script that refreshes category membership counts in the category
 * table.
 *
 * @ingroup Maintenance
 */
class RecountCategories extends Maintenance {
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
for all three modes. Alternatively, just one mode can be run if required.
TEXT
		);
		$this->addOption(
			'mode',
			'(REQUIRED) Which category count column to recompute: "pages", "subcats", "files" or "all".',
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

		$this->addOption(
			'skip-cleanup',
			'Skip running cleanupEmptyCategories if the "page" mode is selected',
			false,
			false
		);

		$this->setBatchSize( 500 );
	}

	public function execute() {
		$originalMode = $this->getOption( 'mode' );
		if ( !in_array( $originalMode, [ 'pages', 'subcats', 'files', 'all' ] ) ) {
			$this->fatalError( 'Please specify a valid mode: one of "pages", "subcats", "files" or "all".' );
		}

		if ( $originalMode === 'all' ) {
			$modes = [ 'pages', 'subcats', 'files' ];
		} else {
			$modes = [ $originalMode ];
		}

		foreach ( $modes as $mode ) {
			$this->output( "Starting to recount {$mode} counts.\n" );
			$this->minimumId = intval( $this->getOption( 'begin', 0 ) );

			// do the work, batch by batch
			$affectedRows = 0;
			// phpcs:ignore Generic.CodeAnalysis.AssignmentInCondition.FoundInWhileCondition
			while ( ( $result = $this->doWork( $mode ) ) !== false ) {
				$affectedRows += $result;
				usleep( $this->getOption( 'throttle', 0 ) * 1000 );
			}

			$this->output( "Updated the {$mode} counts of $affectedRows categories.\n" );
		}

		// Finished
		$this->output( "Done!\n" );
		if ( $originalMode !== 'all' ) {
			$this->output( "Now run the script using the other --mode options if you haven't already.\n" );
		}

		if ( in_array( 'pages', $modes ) ) {
			if ( $this->hasOption( 'skip-cleanup' ) ) {
				$this->output(
					"Also run 'php cleanupEmptyCategories.php --mode remove' to remove empty,\n" .
					"nonexistent categories from the category table.\n\n" );
			} else {
				$this->output( "Running cleanupEmptyCategories.php\n" );
				$cleanup = $this->runChild( CleanupEmptyCategories::class );
				'@phan-var CleanupEmptyCategories $cleanup';
				// Pass no options into the child because of a parameter collision between "mode", which
				// both scripts use but set to different values. We'll just use the defaults.
				$cleanup->loadParamsAndArgs( $this->mSelf, [], [] );
				// Force execution because we want to run it regardless of whether it's been run before.
				$cleanup->setForce( true );
				$cleanup->execute();
			}
		}
	}

	protected function doWork( string $mode ): int|false {
		$this->output( "Finding up to {$this->getBatchSize()} drifted rows " .
			"greater than cat_id {$this->minimumId}...\n" );

		$dbr = $this->getDB( DB_REPLICA, 'vslow' );

		$migrationStage = $this->getServiceContainer()->getMainConfig()->get(
			MainConfigNames::CategoryLinksSchemaMigrationStage
		);

		if ( $migrationStage & SCHEMA_COMPAT_READ_OLD ) {
			$queryBuilder = $dbr->newSelectQueryBuilder()
				->select( 'COUNT(*)' )
				->from( 'categorylinks' )
				->where( 'cl_to = cat_title' );
		} else {
			$queryBuilder = $dbr->newSelectQueryBuilder()
				->select( 'COUNT(*)' )
				->from( 'categorylinks' )
				->join( 'linktarget', null, 'cl_target_id = lt_id' )
				->where( 'lt_title = cat_title' );
		}

		if ( $mode === 'subcats' ) {
			$queryBuilder->andWhere( [ 'cl_type' => 'subcat' ] );
		} elseif ( $mode === 'files' ) {
			$queryBuilder->andWhere( [ 'cl_type' => 'file' ] );
		}

		$countingSubquery = $queryBuilder->caller( __METHOD__ )->getSQL();

		// First, let's find out which categories have drifted and need to be updated.
		// The query counts the categorylinks for each category on the replica DB,
		// but this data can't be used for updating the master, so we don't include it
		// in the results.
		$idsToUpdate = $dbr->newSelectQueryBuilder()
			->select( 'cat_id' )
			->from( 'category' )
			->where( [ $dbr->expr( 'cat_id', '>', (int)$this->minimumId ), "cat_{$mode} != ($countingSubquery)" ] )
			->limit( $this->getBatchSize() )
			->caller( __METHOD__ )->fetchFieldValues();
		if ( !$idsToUpdate ) {
			return false;
		}
		$this->output( "Updating cat_{$mode} field on " .
			count( $idsToUpdate ) . " rows...\n" );

		// In the next batch, start where this query left off. The rows selected
		// in this iteration shouldn't be selected again after being updated, but
		// we still keep track of where we are up to, as extra protection against
		// infinite loops.
		$this->minimumId = end( $idsToUpdate );

		// Now, on master, find the correct counts for these categories.
		$dbw = $this->getPrimaryDB();
		$res = $dbw->newSelectQueryBuilder()
			->select( [ 'cat_id', 'count' => "($countingSubquery)" ] )
			->from( 'category' )
			->where( [ 'cat_id' => $idsToUpdate ] )
			->caller( __METHOD__ )->fetchResultSet();

		// Update the category counts on the rows we just identified.
		// This logic is equivalent to Category::refreshCounts, except here, we
		// don't remove rows when cat_pages is zero and the category description page
		// doesn't exist - instead we print a suggestion to run
		// cleanupEmptyCategories.php.
		$affectedRows = 0;
		foreach ( $res as $row ) {
			$dbw->newUpdateQueryBuilder()
				->update( 'category' )
				->set( [ "cat_{$mode}" => $row->count ] )
				->where( [
					'cat_id' => $row->cat_id,
					$dbw->expr( "cat_{$mode}", '!=', (int)$row->count ),
				] )
				->caller( __METHOD__ )
				->execute();
			$affectedRows += $dbw->affectedRows();
		}

		$this->waitForReplication();

		return $affectedRows;
	}
}

// @codeCoverageIgnoreStart
$maintClass = RecountCategories::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
