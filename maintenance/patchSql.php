<?php
/**
 * Manually run an SQL patch outside of the general updaters.
 * This ensures that the DB options (charset, prefix, engine) are correctly set.
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
