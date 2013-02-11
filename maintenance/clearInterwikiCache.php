<?php
/**
 * Clear the cache of interwiki prefixes for all local wikis.
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

require_once( __DIR__ . '/Maintenance.php' );

/**
 * Maintenance script to clear the cache of interwiki prefixes for all local wikis.
 *
 * @ingroup Maintenance
 */
class ClearInterwikiCache extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->mDescription = "Clear all interwiki links for all languages from the cache";
	}

	public function execute() {
		global $wgLocalDatabases, $wgMemc;
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select( 'interwiki', array( 'iw_prefix' ), false );
		$prefixes = array();
		foreach ( $res as $row ) {
			$prefixes[] = $row->iw_prefix;
		}

		foreach ( $wgLocalDatabases as $db ) {
			$this->output( "$db..." );
			foreach ( $prefixes as $prefix ) {
				$wgMemc->delete( "$db:interwiki:$prefix" );
			}
			$this->output( "done\n" );
		}
	}
}

$maintClass = "ClearInterwikiCache";
require_once( RUN_MAINTENANCE_IF_MAIN );
