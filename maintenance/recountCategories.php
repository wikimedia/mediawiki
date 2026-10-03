<?php
/**
 * Refreshes category counts.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Maintenance
 */

use MediaWiki\Deferred\LinksUpdate\CategoryLinksTable;
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
				$cleanup = $this->createChild( CleanupEmptyCategories::class );
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

		// First, let's find out which categories have drifted and need to be updated.
		// Use local database for category table (category table is not in virtual domain)
		$dbrLocal = $this->getDB( DB_REPLICA, 'vslow' );
		$candidates = [];
		$res = $dbrLocal->newSelectQueryBuilder()
			->select( [ 'cat_id', 'cat_title', 'cat_count' => "cat_{$mode}" ] )
			->from( 'category' )
			->where( $dbrLocal->expr( 'cat_id', '>', (int)$this->minimumId ) )
			->orderBy( 'cat_id' )
			->limit( $this->getBatchSize() )
			->caller( __METHOD__ )->fetchResultSet();

		foreach ( $res as $row ) {
			$candidates[(int)$row->cat_id] = [ 'title' => $row->cat_title, 'count' => (int)$row->cat_count ];
		}

		if ( !$candidates ) {
			return false;
		}

		// In the next batch, start where this query left off. The rows selected
		// in this iteration shouldn't be selected again after being updated, but
		// we still keep track of where we are up to, as extra protection against
		// infinite loops.
		$this->minimumId = array_key_last( $candidates );

		$linkConds = [ 'lt_namespace' => NS_CATEGORY ];
		if ( $mode === 'subcats' ) {
			$linkConds['cl_type'] = 'subcat';
		} elseif ( $mode === 'files' ) {
			$linkConds['cl_type'] = 'file';
		}

		// The query counts the categorylinks for each category on the replica DB,
		// but this data can't be used for updating the master, so we only use it to
		// find the drifted categories and don't write it.
		$connectionProvider = $this->getServiceContainer()->getConnectionProvider();
		$dbrLinks = $connectionProvider->getReplicaDatabase( CategoryLinksTable::VIRTUAL_DOMAIN, 'vslow' );
		$res = $dbrLinks->newSelectQueryBuilder()
			->select( [ 'lt_title', 'link_count' => 'COUNT(*)' ] )
			->from( 'categorylinks' )
			->join( 'linktarget', null, 'cl_target_id = lt_id' )
			->where( $linkConds )
			->andWhere( [ 'lt_title' => array_column( $candidates, 'title' ) ] )
			->groupBy( 'lt_title' )
			->caller( __METHOD__ )->fetchResultSet();

		$replicaCounts = [];
		foreach ( $res as $row ) {
			$replicaCounts[$row->lt_title] = (int)$row->link_count;
		}

		$driftedIds = [];
		foreach ( $candidates as $id => $candidate ) {
			if ( ( $replicaCounts[$candidate['title']] ?? 0 ) !== $candidate['count'] ) {
				$driftedIds[] = $id;
			}
		}

		if ( !$driftedIds ) {
			return 0;
		}

		$this->output( "Updating cat_{$mode} field on up to " .
			count( $driftedIds ) . " rows...\n" );

		// Now, on master, find the correct counts for these categories.
		$driftedTitles = [];
		foreach ( $driftedIds as $id ) {
			$driftedTitles[] = $candidates[$id]['title'];
		}

		$dbwLinks = $connectionProvider->getPrimaryDatabase( CategoryLinksTable::VIRTUAL_DOMAIN );
		$res = $dbwLinks->newSelectQueryBuilder()
			->select( [ 'lt_title', 'link_count' => 'COUNT(*)' ] )
			->from( 'categorylinks' )
			->join( 'linktarget', null, 'cl_target_id = lt_id' )
			->where( $linkConds )
			->andWhere( [ 'lt_title' => $driftedTitles ] )
			->groupBy( 'lt_title' )
			->caller( __METHOD__ )->fetchResultSet();

		$primaryCounts = [];
		foreach ( $res as $row ) {
			$primaryCounts[$row->lt_title] = (int)$row->link_count;
		}

		// Update the category counts on the rows we just identified.
		// This logic is equivalent to Category::refreshCounts, except here, we
		// don't remove rows when cat_pages is zero and the category description page
		// doesn't exist - instead we print a suggestion to run
		// cleanupEmptyCategories.php.
		$dbw = $this->getPrimaryDB();
		$affectedRows = 0;
		foreach ( $driftedIds as $id ) {
			$count = $primaryCounts[$candidates[$id]['title']] ?? 0;
			$dbw->newUpdateQueryBuilder()
				->update( 'category' )
				->set( [ "cat_{$mode}" => $count ] )
				->where( [
					'cat_id' => $id,
					$dbw->expr( "cat_{$mode}", '!=', $count ),
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
