<?php
/**
 * Rename restriction level
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
 * Maintenance script that updates page_restrictions and
 * protected_titles tables to use a new name for a given
 * restriction level.
 *
 * @ingroup Maintenance
 */
class RenameRestrictions extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Rename a restriction level' );
		$this->addArg( 'oldlevel', 'Old name of restriction level', true );
		$this->addArg( 'newlevel', 'New name of restriction level', true );
	}

	public function execute() {
		$oldLevel = $this->getArg( 0 );
		$newLevel = $this->getArg( 1 );

		$dbw = $this->getPrimaryDB();
		$dbw->newUpdateQueryBuilder()
			->update( 'page_restrictions' )
			->set( [ 'pr_level' => $newLevel ] )
			->where( [ 'pr_level' => $oldLevel ] )
			->caller( __METHOD__ )
			->execute();
		$dbw->newUpdateQueryBuilder()
			->update( 'protected_titles' )
			->set( [ 'pt_create_perm' => $newLevel ] )
			->where( [ 'pt_create_perm' => $oldLevel ] )
			->caller( __METHOD__ )
			->execute();
	}

}

// @codeCoverageIgnoreStart
$maintClass = RenameRestrictions::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
