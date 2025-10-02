<?php
/**
 * Delete archived (deleted from public) revisions from the database
 *
 * Shamelessly stolen from deleteOldRevisions.php by Rob Church :)
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Maintenance
 */

use MediaWiki\Maintenance\Maintenance;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script to delete archived (deleted from public) revisions
 * from the database.
 *
 * @ingroup Maintenance
 */
class DeleteArchivedRevisions extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription(
			"Deletes all archived revisions\nThese revisions will no longer be restorable" );
		$this->addOption( 'delete', 'Performs the deletion' );
	}

	public function execute() {
		$dbw = $this->getPrimaryDB();

		if ( !$this->hasOption( 'delete' ) ) {
			$count = $dbw->newSelectQueryBuilder()
				->select( 'COUNT(*)' )
				->from( 'archive' )
				->caller( __METHOD__ )
				->fetchField();
			$this->output( "Found $count revisions to delete.\n" );
			$this->output( "Please run the script again with the --delete option "
				. "to really delete the revisions.\n" );
			return;
		}

		$this->output( "Deleting archived revisions..." );
		$dbw->newDeleteQueryBuilder()
			->deleteFrom( 'archive' )
			->where( '*' )
			->caller( __METHOD__ )->execute();
		$count = $dbw->affectedRows();
		$this->output( "done. $count revisions deleted.\n" );

		if ( $count ) {
			$this->purgeRedundantText( true );
		}
	}
}

// @codeCoverageIgnoreStart
$maintClass = DeleteArchivedRevisions::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
