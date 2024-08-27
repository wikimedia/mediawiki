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

use MediaWiki\User\User;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

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
		$dbw = $this->getPrimaryDB();
		$batchSize = $this->getBatchSize();
		$userGroupManager = $this->getServiceContainer()->getUserGroupManager();
		$start = $dbw->newSelectQueryBuilder()
			->select( 'MIN(ug_user)' )
			->from( 'user_groups' )
			->where( [ 'ug_group' => $oldGroup ] )
			->caller( __FUNCTION__ )->fetchField();
		$end = $dbw->newSelectQueryBuilder()
			->select( 'MAX(ug_user)' )
			->from( 'user_groups' )
			->where( [ 'ug_group' => $oldGroup ] )
			->caller( __FUNCTION__ )->fetchField();
		if ( $start === null ) {
			$this->fatalError( "Nothing to do - no users in the '$oldGroup' group" );
		}
		# Do remaining chunk
		$end += $batchSize - 1;
		$blockStart = $start;
		$blockEnd = $start + $batchSize - 1;
		// Migrate users over in batches...
		while ( $blockEnd <= $end ) {
			$affected = 0;
			$this->output( "Doing users $blockStart to $blockEnd\n" );

			$this->beginTransaction( $dbw, __METHOD__ );
			// Find the users already in the new group, so that we can exclude them from the UPDATE query
			// and instead delete the rows.
			$usersAlreadyInNewGroup = $dbw->newSelectQueryBuilder()
				->select( 'ug_user' )
				->from( 'user_groups' )
				->where( [
					'ug_group' => $newGroup,
					$dbw->expr( 'ug_user', '>=', (int)$blockStart ),
					$dbw->expr( 'ug_user', '<=', (int)$blockEnd ),
				] )
				->caller( __METHOD__ )
				->fetchFieldValues();

			// Update the user group for the users which do not already have the new group.
			$updateQueryBuilder = $dbw->newUpdateQueryBuilder()
				->update( 'user_groups' )
				->set( [ 'ug_group' => $newGroup ] )
				->where( [
					'ug_group' => $oldGroup,
					$dbw->expr( 'ug_user', '>=', (int)$blockStart ),
					$dbw->expr( 'ug_user', '<=', (int)$blockEnd ),
				] )
				->caller( __METHOD__ );
			if ( count( $usersAlreadyInNewGroup ) ) {
				$updateQueryBuilder->where( $dbw->expr( 'ug_user', '!=', $usersAlreadyInNewGroup ) );
			}
			$updateQueryBuilder->execute();
			$affected += $dbw->affectedRows();

			// Delete rows that the UPDATE operation above had to ignore.
			// This happens when a user is in both the old and new group, and as such the UPDATE would have failed.
			if ( count( $usersAlreadyInNewGroup ) ) {
				$dbw->newDeleteQueryBuilder()
					->deleteFrom( 'user_groups' )
					->where( [
						'ug_group' => $oldGroup,
						$dbw->expr( 'ug_user', '=', $usersAlreadyInNewGroup ),
					] )
					->caller( __METHOD__ )->execute();
				$affected += $dbw->affectedRows();
			}
			$this->commitTransaction( $dbw, __METHOD__ );

			// Clear cache for the affected users (T42340)
			if ( $affected > 0 ) {
				// XXX: This also invalidates cache of unaffected users that
				// were in the new group and not in the group.
				$res = $dbw->newSelectQueryBuilder()
					->select( 'ug_user' )
					->from( 'user_groups' )
					->where( [
						'ug_group' => $newGroup,
						$dbw->expr( 'ug_user', '>=', (int)$blockStart ),
						$dbw->expr( 'ug_user', '<=', (int)$blockEnd ),
					] )
					->caller( __METHOD__ )->fetchResultSet();
				if ( $res !== false ) {
					foreach ( $res as $row ) {
						$user = User::newFromId( $row->ug_user );
						$user->invalidateCache();
						$userGroupManager->clearCache( $user );
					}
				}
			}

			$count += $affected;
			$blockStart += $batchSize;
			$blockEnd += $batchSize;
		}
		$this->output( "Done! $count users in group '$oldGroup' are now in '$newGroup' instead.\n" );
	}
}

// @codeCoverageIgnoreStart
$maintClass = MigrateUserGroup::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
