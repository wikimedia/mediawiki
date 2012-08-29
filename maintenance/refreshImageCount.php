<?php
/**
 * Quickie hack; patch-ss_images.sql uses variables which don't
 * replicate properly.
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

class RefreshImageCount extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Resets ss_image count, forcing slaves to pick it up.";
	}

	public function execute() {
		$dbw = wfGetDB( DB_MASTER );

		// Load the current value from the master
		$count = $dbw->selectField( 'site_stats', 'ss_images' );

		$this->output( wfWikiID() . ": forcing ss_images to $count\n" );

		// First set to NULL so that it changes on the master
		$dbw->update( 'site_stats',
			array( 'ss_images' => null ),
			array( 'ss_row_id' => 1 ) );

		// Now this update will be forced to go out
		$dbw->update( 'site_stats',
			array( 'ss_images' => $count ),
			array( 'ss_row_id' => 1 ) );
			}
}

$maintClass = "RefreshImageCount";
require_once( RUN_MAINTENANCE_IF_MAIN );

