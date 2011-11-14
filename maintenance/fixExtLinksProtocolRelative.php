<?php
/**
 * Fixes any entries for protocol-relative URLs in the externallinks table,
 * replacing each protocol-relative entry with two entries, one for http
 * and one for https.
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

class FixExtLinksProtocolRelative extends LoggedUpdateMaintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Fixes any entries in the externallinks table containing protocol-relative URLs";
	}

	protected function getUpdateKey() {
		return 'fix protocol-relative URLs in externallinks';
	}

	protected function updateSkippedMessage() {
		return 'protocol-relative URLs in externallinks table already fixed.';
	}
	
	protected function doDBUpdates() {
		$db = wfGetDB( DB_MASTER );
		if ( !$db->tableExists( 'externallinks' ) ) {
			$this->error( "externallinks table does not exist" );
			return false;
		}
		$this->output( "Fixing protocol-relative entries in the externallinks table...\n" );
		$res = $db->select( 'externallinks', array( 'el_from', 'el_to', 'el_index' ),
			array( 'el_index' . $db->buildLike( '//', $db->anyString() ) ),
			__METHOD__
		);
		$count = 0;
		foreach ( $res as $row ) {
			$count++;
			if ( $count % 100 == 0 ) {
				$this->output( $count );
				wfWaitForSlaves();
			}
			$db->insert( 'externallinks',
				array(
					array(
						'el_from' => $row->el_from,
						'el_to' => $row->el_to,
						'el_index' => "http:{$row->el_index}",
					),
					array(
						'el_from' => $row->el_from,
						'el_to' => $row->el_to,
						'el_index' => "https:{$row->el_index}",
					)
				), __METHOD__, array( 'IGNORE' )
			);
			$db->delete( 'externallinks', array( 'el_index' => $row->el_index, 'el_from' => $row->el_from, 'el_to' => $row->el_to ), __METHOD__ );
		}
		$this->output( "Done, $count rows updated.\n" );
		return true;
	}
}

$maintClass = "FixExtLinksProtocolRelative";
require_once( RUN_MAINTENANCE_IF_MAIN );
