<?php
/**
 * Delete old (non-current) revisions from the database
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Maintenance
 * @author Rob Church <robchur@gmail.com>
 */

use MediaWiki\Maintenance\Maintenance;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script that deletes old (non-current) revisions from the database.
 *
 * @ingroup Maintenance
 */
class DeleteOldRevisions extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Delete old (non-current) revisions from the database' );
		$this->addOption( 'delete', 'Actually perform the deletion' );
		$this->addArg( 'page_id', 'List of page ids to work on', false, true );
	}

	public function execute() {
		$this->output( "Delete old revisions\n\n" );
		$this->doDelete( $this->hasOption( 'delete' ), $this->getArgs( 'page_id' ) );
	}

	private function doDelete( bool $delete = false, array $pageIds = [] ) {
		# Data should come off the master, wrapped in a transaction
		$dbw = $this->getPrimaryDB();
		$this->beginTransactionRound( __METHOD__ );

		$pageConds = [];
		$revConds = [];

		# If a list of page_ids was provided, limit results to that set of page_ids
		if ( count( $pageIds ) > 0 ) {
			$pageConds['page_id'] = $pageIds;
			$revConds['rev_page'] = $pageIds;
			$this->output( "Limiting to page IDs " . implode( ',', $pageIds ) . "\n" );
		}

		# Get "active" revisions from the page table
		$this->output( "Searching for active revisions..." );
		$latestRevs = $dbw->newSelectQueryBuilder()
			->select( 'page_latest' )
			->from( 'page' )
			->where( $pageConds )
			->caller( __METHOD__ )
			->fetchFieldValues();
		$this->output( "done.\n" );

		# Get all revisions that aren't in this set
		$this->output( "Searching for inactive revisions..." );
		if ( count( $latestRevs ) > 0 ) {
			$revConds[] = $dbw->expr( 'rev_id', '!=', $latestRevs );
		}
		$oldRevs = $dbw->newSelectQueryBuilder()
			->select( 'rev_id' )
			->from( 'revision' )
			->where( $revConds )
			->caller( __METHOD__ )
			->fetchFieldValues();
		$this->output( "done.\n" );

		# Inform the user of what we're going to do
		$count = count( $oldRevs );
		$this->output( "$count old revisions found.\n" );

		# Delete as appropriate
		if ( $delete && $count ) {
			$this->output( "Deleting..." );
			$dbw->newDeleteQueryBuilder()
				->deleteFrom( 'revision' )
				->where( [ 'rev_id' => $oldRevs ] )
				->caller( __METHOD__ )->execute();
			$dbw->newDeleteQueryBuilder()
				->deleteFrom( 'ip_changes' )
				->where( [ 'ipc_rev_id' => $oldRevs ] )
				->caller( __METHOD__ )->execute();
			$this->output( "done.\n" );
		}

		# Purge redundant text records
		$this->commitTransactionRound( __METHOD__ );
		if ( $delete ) {
			$this->purgeRedundantText( true );
		}
	}
}

// @codeCoverageIgnoreStart
$maintClass = DeleteOldRevisions::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
