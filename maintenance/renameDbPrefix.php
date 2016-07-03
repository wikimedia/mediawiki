<?php
/**
 * Change the prefix of database tables.
 * Run this script to after changing $wgDBprefix on a wiki.
 * The wiki will have to get downtime to do this correctly.
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

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script that changes the prefix of database tables.
 *
 * @ingroup Maintenance
 */
class RenameDbPrefix extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addOption( "old", "Old db prefix [0 for none]", true, true );
		$this->addOption( "new", "New db prefix [0 for none]", true, true );
	}

	public function getDbType() {
		return Maintenance::DB_ADMIN;
	}

	public function execute() {
		global $wgDBname;

		// Allow for no old prefix
		if ( $this->getOption( 'old', 0 ) === '0' ) {
			$old = '';
		} else {
			// Use nice safe, sane, prefixes
			preg_match( '/^[a-zA-Z]+_$/', $this->getOption( 'old' ), $m );
			$old = isset( $m[0] ) ? $m[0] : false;
		}
		// Allow for no new prefix
		if ( $this->getOption( 'new', 0 ) === '0' ) {
			$new = '';
		} else {
			// Use nice safe, sane, prefixes
			preg_match( '/^[a-zA-Z]+_$/', $this->getOption( 'new' ), $m );
			$new = isset( $m[0] ) ? $m[0] : false;
		}

		if ( $old === false || $new === false ) {
			$this->error( "Invalid prefix!", true );
		}
		if ( $old === $new ) {
			$this->output( "Same prefix. Nothing to rename!\n", true );
		}

		$this->output( "Renaming DB prefix for tables of $wgDBname from '$old' to '$new'\n" );
		$count = 0;

		$dbw = $this->getDB( DB_MASTER );
		$res = $dbw->query( "SHOW TABLES " . $dbw->buildLike( $old, $dbw->anyString() ) );
		foreach ( $res as $row ) {
			// XXX: odd syntax. MySQL outputs an oddly cased "Tables of X"
			// sort of message. Best not to try $row->x stuff...
			$fields = get_object_vars( $row );
			// Silly for loop over one field...
			foreach ( $fields as $table ) {
				// $old should be regexp safe ([a-zA-Z_])
				$newTable = preg_replace( '/^' . $old . '/', $new, $table );
				$this->output( "Renaming table $table to $newTable\n" );
				$dbw->query( "RENAME TABLE $table TO $newTable" );
			}
			$count++;
		}
		$this->output( "Done! [$count tables]\n" );
	}
}

$maintClass = "RenameDbPrefix";
require_once RUN_MAINTENANCE_IF_MAIN;
