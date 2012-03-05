<?php
/**
 * Re-assign users from an old group to a new one
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

class MigrateUserGroup extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Re-assign users from an old group to a new one";
		$this->addArg( 'oldgroup', 'Old user group key', true );
		$this->addArg( 'newgroup', 'New user group key', true );
		$this->setBatchSize( 200 );
	}

	public function execute() {
		$count = 0;
		$oldGroup = $this->getArg( 0 );
		$newGroup = $this->getArg( 1 );
		$dbw = wfGetDB( DB_MASTER );
		$start = $dbw->selectField( 'user_groups', 'MIN(ug_user)',
			array( 'ug_group' => $oldGroup ), __FUNCTION__ );
		$end = $dbw->selectField( 'user_groups', 'MAX(ug_user)',
			array( 'ug_group' => $oldGroup ), __FUNCTION__ );
		if ( $start === null ) {
			$this->error( "Nothing to do - no users in the '$oldGroup' group", true );
		}
		# Do remaining chunk
		$end += $this->mBatchSize - 1;
		$blockStart = $start;
		$blockEnd = $start + $this->mBatchSize - 1;
		// Migrate users over in batches...
		while ( $blockEnd <= $end ) {
			$this->output( "Doing users $blockStart to $blockEnd\n" );
			$dbw->begin();
			$dbw->update( 'user_groups',
				array( 'ug_group' => $newGroup ),
				array( 'ug_group' => $oldGroup,
					"ug_user BETWEEN $blockStart AND $blockEnd" )
			);
			$count += $dbw->affectedRows();
			$dbw->commit();
			$blockStart += $this->mBatchSize;
			$blockEnd += $this->mBatchSize;
			wfWaitForSlaves( 5 );
		}
		$this->output( "Done! $count user(s) in group '$oldGroup' are now in '$newGroup' instead.\n" );
	}
}

$maintClass = "MigrateUserGroup";
require_once( DO_MAINTENANCE );
