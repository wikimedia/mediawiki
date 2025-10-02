<?php
/**
 * Manually run an SQL patch outside of the general updaters.
 * This ensures that the DB options (charset, prefix, engine) are correctly set.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Maintenance
 */

use MediaWiki\Installer\DatabaseUpdater;
use MediaWiki\Maintenance\Maintenance;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script that manually runs an SQL patch outside of the general updaters.
 *
 * @ingroup Maintenance
 */
class PatchSql extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Run an SQL file into the DB, replacing prefix and charset vars' );
		$this->addArg(
			'patch-name',
			'Name of the patch file, either full path or in sql/$dbtype/'
		);
	}

	/** @inheritDoc */
	public function getDbType() {
		return Maintenance::DB_ADMIN;
	}

	public function execute() {
		$dbw = $this->getDB( DB_PRIMARY );
		$updater = DatabaseUpdater::newForDB( $dbw, true, $this );

		foreach ( $this->getArgs() as $name ) {
			$files = [
				$name,
				$updater->patchPath( $dbw, $name ),
				$updater->patchPath( $dbw, "patch-$name.sql" ),
			];
			foreach ( $files as $file ) {
				if ( file_exists( $file ) ) {
					$this->output( "$file ...\n" );
					$dbw->sourceFile( $file );
					continue 2;
				}
			}
			$this->error( "Could not find $name\n" );
		}
		$this->output( "done.\n" );
	}
}

// @codeCoverageIgnoreStart
$maintClass = PatchSql::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
