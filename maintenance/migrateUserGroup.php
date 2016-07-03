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
 * @file
 * @ingroup Maintenance
 */

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script that re-assigns users from an old group to a new one.
 *
 * @ingroup Maintenance
 */
class MigrateUserGroup extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Re-assign users from an old group to a new one' );
		$this->addArg( 'oldgroup', 'Old user group key', true );
		$this->addArg( 'newgroup', 'New user group key', true );
		$this->setBatchSize( 200 );
	}

	public function execute() {
		$count = 0;
		$oldGroup = $this->getArg( 0 );
		$newGroup = $this->getArg( 1 );
		$dbw = $this->getDB( DB_MASTER );
		$start = $dbw->selectField( 'user_groups', 'MIN(ug_user)',
			[ 'ug_group' => $oldGroup ], __FUNCTION__ );
		$end = $dbw->selectField( 'user_groups', 'MAX(ug_user)',
			[ 'ug_group' => $oldGroup ], __FUNCTION__ );
		if ( $start === null ) {
			$this->error( "Nothing to do - no users in the '$oldGroup' group", true );
		}
		# Do remaining chunk
		$end += $this->mBatchSize - 1;
		$blockStart = $start;
		$blockEnd = $start + $this->mBatchSize - 1;
		// Migrate users over in batches...
		while ( $blockEnd <= $end ) {
			$affected = 0;
			$this->output( "Doing users $blockStart to $blockEnd\n" );

			$this->beginTransaction( $dbw, __METHOD__ );
			$dbw->update( 'user_groups',
				[ 'ug_group' => $newGroup ],
				[ 'ug_group' => $oldGroup,
					"ug_user BETWEEN $blockStart AND $blockEnd" ],
				__METHOD__,
				[ 'IGNORE' ]
			);
			$affected += $dbw->affectedRows();
			// Delete rows that the UPDATE operation above had to ignore.
			// This happens when a user is in both the old and new group.
			// Updating the row for the old group membership failed since
			// user/group is UNIQUE.
			$dbw->delete( 'user_groups',
				[ 'ug_group' => $oldGroup,
					"ug_user BETWEEN $blockStart AND $blockEnd" ],
				__METHOD__
			);
			$affected += $dbw->affectedRows();
			$this->commitTransaction( $dbw, __METHOD__ );

			// Clear cache for the affected users (bug 40340)
			if ( $affected > 0 ) {
				// XXX: This also invalidates cache of unaffected users that
				// were in the new group and not in the group.
				$res = $dbw->select( 'user_groups', 'ug_user',
					[ 'ug_group' => $newGroup,
						"ug_user BETWEEN $blockStart AND $blockEnd" ],
					__METHOD__
				);
				if ( $res !== false ) {
					foreach ( $res as $row ) {
						$user = User::newFromId( $row->ug_user );
						$user->invalidateCache();
					}
				}
			}

			$count += $affected;
			$blockStart += $this->mBatchSize;
			$blockEnd += $this->mBatchSize;
			wfWaitForSlaves();
		}
		$this->output( "Done! $count users in group '$oldGroup' are now in '$newGroup' instead.\n" );
	}
}

$maintClass = "MigrateUserGroup";
require_once RUN_MAINTENANCE_IF_MAIN;
