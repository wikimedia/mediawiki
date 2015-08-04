<?php
/**
 * Normalise email addresses into lowercase.
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
 * @author Junehyeon Bae (devunt)
 */

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script that makes all email addresses lowercase.
 *
 * @ingroup Maintenance
 */
class NormaliseEmailAddress extends LoggedUpdateMaintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Normalises email address";
	}

	protected function getUpdateKey() {
		return 'normalise email address';
	}

	protected function updateSkippedMessage() {
		return 'email addresses are already normalised.';
	}

	protected function doDBUpdates() {
		$db = wfGetDB( DB_MASTER );
		if ( !$db->tableExists( 'user' ) ) {
			$this->error( "user table does not exist" );

			return false;
		}
		$this->output( "Normalising email addresses\n" );
		$db->query( 'UPDATE user SET user_email = LOWER(user_email)', __METHOD__ );
		wfWaitForSlaves();
		$this->output( "email address normalisation complete ...\n" );

		return true;
	}
}

$maintClass = "NormaliseEmailAddress";
require_once RUN_MAINTENANCE_IF_MAIN;
