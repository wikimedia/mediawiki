<?php
/**
 * Delete revisions which refer to a nonexisting page.
 * Sometimes manual deletion done in a rush leaves crap in the database.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Maintenance
 * @author Rob Church <robchur@gmail.com>
 * @todo More efficient cleanup of text records
 */

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

use MediaWiki\Maintenance\Maintenance;
use Wikimedia\Rdbms\IDatabase;

/**
 * Maintenance script that deletes revisions which refer to a nonexisting page.
 *
 * @ingroup Maintenance
 */
class DeleteOrphanedRevisions extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription(
			'Maintenance script to delete revisions which refer to a nonexisting page' );
		$this->addOption( 'report', 'Prints out a count of affected revisions but doesn\'t delete them' );
	}

	public function execute() {
		$this->output( "Delete Orphaned Revisions\n" );

		$report = $this->hasOption( 'report' );

		$dbw = $this->getPrimaryDB();
		$this->beginTransactionRound( __METHOD__ );

		# Find all the orphaned revisions
		$this->output( "Checking for orphaned revisions..." );
		$revisions = $dbw->newSelectQueryBuilder()
			->select( 'rev_id' )
			->from( 'revision' )
			->leftJoin( 'page', null, 'rev_page = page_id' )
			->where( [ 'page_namespace' => null ] )
			->caller( 'deleteOrphanedRevisions' )
			->fetchFieldValues();

		# Stash 'em all up for deletion (if needed)
		$count = count( $revisions );
		$this->output( "found {$count}.\n" );

		# Nothing to do?
		if ( $report || $count === 0 ) {
			$this->commitTransactionRound( __METHOD__ );
			return;
		}

		# Delete each revision
		$this->output( "Deleting..." );
		$this->deleteRevs( $revisions, $dbw );
		$this->output( "done.\n" );

		# Close the transaction and call the script to purge unused text records
		$this->commitTransactionRound( __METHOD__ );
		$this->purgeRedundantText( true );
	}

	/**
	 * Delete one or more revisions from the database
	 * Do this inside a transaction
	 *
	 * @param int[] $id Array of revision id values
	 * @param IDatabase $dbw Primary DB handle
	 */
	private function deleteRevs( array $id, IDatabase $dbw ) {
		$dbw->newDeleteQueryBuilder()
			->deleteFrom( 'revision' )
			->where( [ 'rev_id' => $id ] )
			->caller( __METHOD__ )->execute();

		// Delete from ip_changes should a record exist.
		$dbw->newDeleteQueryBuilder()
			->deleteFrom( 'ip_changes' )
			->where( [ 'ipc_rev_id' => $id ] )
			->caller( __METHOD__ )->execute();
	}
}

// @codeCoverageIgnoreStart
$maintClass = DeleteOrphanedRevisions::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
