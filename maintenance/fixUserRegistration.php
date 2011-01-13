<?php
/**
 * Fix the user_registration field.
 * In particular, for values which are NULL, set them to the date of the first edit
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

class FixUserRegistration extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Fix the user_registration field";
	}

	public function execute() {
		$dbr = wfGetDB( DB_SLAVE );
		$dbw = wfGetDB( DB_MASTER );

		// Get user IDs which need fixing
		$res = $dbr->select( 'user', 'user_id', 'user_registration IS NULL', __METHOD__ );
		foreach ( $res as $row ) {
			$id = $row->user_id;
			// Get first edit time
			$timestamp = $dbr->selectField( 'revision', 'MIN(rev_timestamp)', array( 'rev_user' => $id ), __METHOD__ );
			// Update
			if ( !empty( $timestamp ) ) {
				$dbw->update( 'user', array( 'user_registration' => $timestamp ), array( 'user_id' => $id ), __METHOD__ );
				$this->output( "$id $timestamp\n" );
			} else {
				$this->output( "$id NULL\n" );
			}
		}
		$this->output( "\n" );
	}
}

$maintClass = "FixUserRegistration";
require_once( RUN_MAINTENANCE_IF_MAIN );
