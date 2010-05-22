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
 * @ingroup Maintenance
 */

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

class PatchSql extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Run an SQL file into the DB, replacing prefix and charset vars";
		$this->addArg( 'patch-name', 'Name of the patch file, either full path or in maintenance/archives' );
	}

	public function getDbType() {
		return Maintenance::DB_ADMIN;
	}

	public function execute() {
		$dbw = wfGetDB( DB_MASTER );
		foreach ( $this->mArgs as $arg ) {
			$files = array(
				$arg,
				DatabaseBase::patchPath( $arg ),
				DatabaseBase::patchPath( "patch-$arg.sql" ),
			);
			foreach ( $files as $file ) {
				if ( file_exists( $file ) ) {
					$this->output( "$file ...\n" );
					$dbw->sourceFile( $file );
					continue 2;
				}
			}
			$this->error( "Could not find $arg\n" );
		}
		$this->output( "done.\n" );
	}
}

$maintClass = "PatchSql";
require_once( DO_MAINTENANCE );
