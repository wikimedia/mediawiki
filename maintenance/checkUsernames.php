<?php
/**
 * Check that database usernames are actually valid.
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
 * Maintenance script to check that database usernames are actually valid.
 *
 * An existing usernames can become invalid if User::isValidUserName()
 * is altered or if we change the $wgMaxNameChars
 *
 * @ingroup Maintenance
 */
class CheckUsernames extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->mDescription = "Verify that database usernames are actually valid";
		$this->setBatchSize( 1000 );
	}

	function execute() {
		$dbr = wfGetDB( DB_SLAVE );

		$maxUserId = 0;
		do {
			$res = $dbr->select( 'user',
				array( 'user_id', 'user_name' ),
				array( 'user_id > ' . $maxUserId ),
				__METHOD__,
				array(
					'ORDER BY' => 'user_id',
					'LIMIT' => $this->mBatchSize,
				)
			);

			foreach ( $res as $row ) {
				if ( !User::isValidUserName( $row->user_name ) ) {
					$this->output( sprintf( "Found: %6d: '%s'\n", $row->user_id, $row->user_name ) );
					wfDebugLog( 'checkUsernames', $row->user_name );
				}
			}
			$maxUserId = $row->user_id;
		} while ( $res->numRows() );
	}
}

$maintClass = "CheckUsernames";
require_once RUN_MAINTENANCE_IF_MAIN;
