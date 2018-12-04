<?php
/**
 * User edit count incrementing.
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
 */

use Wikimedia\Assert\Assert;
use MediaWiki\MediaWikiServices;

/**
 * Handles increment the edit count for a given set of users
 */
class UserEditCountUpdate implements DeferrableUpdate, MergeableUpdate {
	/** @var array[] Map of (user ID => ('increment': int, 'instances': User[])) */
	private $infoByUser;

	/**
	 * @param User $user
	 * @param int $increment
	 */
	public function __construct( User $user, $increment ) {
		if ( !$user->getId() ) {
			throw new RuntimeException( "Got user ID of zero" );
		}
		$this->infoByUser = [
			$user->getId() => [ 'increment' => $increment, 'instances' => [ $user ] ]
		];
	}

	public function merge( MergeableUpdate $update ) {
		/** @var UserEditCountUpdate $update */
		Assert::parameterType( __CLASS__, $update, '$update' );

		foreach ( $update->infoByUser as $userId => $info ) {
			if ( !isset( $this->infoByUser[$userId] ) ) {
				$this->infoByUser[$userId] = [ 'increment' => 0, 'instances' => [] ];
			}
			// Merge the increment amount
			$this->infoByUser[$userId]['increment'] += $info['increment'];
			// Merge the list of User instances to update in doUpdate()
			foreach ( $info['instances'] as $user ) {
				if ( !in_array( $user, $this->infoByUser[$userId]['instances'], true ) ) {
					$this->infoByUser[$userId]['instances'][] = $user;
				}
			}
		}
	}

	/**
	 * Purges the list of URLs passed to the constructor.
	 */
	public function doUpdate() {
		$lb = MediaWikiServices::getInstance()->getDBLoadBalancer();
		$dbw = $lb->getConnection( DB_MASTER );
		$fname = __METHOD__;

		( new AutoCommitUpdate( $dbw, __METHOD__, function () use ( $lb, $dbw, $fname ) {
			foreach ( $this->infoByUser as $userId => $info ) {
				$dbw->update(
					'user',
					[ 'user_editcount=user_editcount+' . (int)$info['increment'] ],
					[ 'user_id' => $userId, 'user_editcount IS NOT NULL' ],
					$fname
				);
				/** @var User[] $affectedInstances */
				$affectedInstances = $info['instances'];
				// Lazy initialization check...
				if ( $dbw->affectedRows() == 0 ) {
					// No rows will be "affected" if user_editcount is NULL.
					// Check if the generic "replica" connection is not the master.
					$dbr = $lb->getConnection( DB_REPLICA );
					if ( $dbr !== $dbw ) {
						// This method runs after the new revisions were committed.
						// Wait for the replica to catch up so they will all be counted.
						$dbr->flushSnapshot( $fname );
						$lb->safeWaitForMasterPos( $dbr );
					}
					$affectedInstances[0]->initEditCountInternal();
				}
				$newCount = (int)$dbw->selectField(
					'user',
					[ 'user_editcount' ],
					[ 'user_id' => $userId ],
					$fname
				);

				// Update the edit count in the instance caches. This is mostly useful
				// for maintenance scripts, where deferred updates might run immediately
				// and user instances might be reused for a long time.
				foreach ( $affectedInstances as $affectedInstance ) {
					$affectedInstance->setEditCountInternal( $newCount );
				}
				// Clear the edit count in user cache too
				$affectedInstances[0]->invalidateCache();
			}
		} ) )->doUpdate();
	}
}
