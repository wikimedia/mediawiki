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

use MediaWiki\MediaWikiServices;
use MediaWiki\User\UserIdentity;
use Wikimedia\Assert\Assert;

/**
 * Handles increment the edit count for a given set of users
 */
class UserEditCountUpdate implements DeferrableUpdate, MergeableUpdate {
	/** @var array[] Map of (user ID => ('increment': int, 'instances': UserIdentity[])) */
	private $infoByUser;

	/**
	 * @param UserIdentity $user
	 * @param int $increment
	 */
	public function __construct( UserIdentity $user, $increment ) {
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
		'@phan-var UserEditCountUpdate $update';

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
		$mwServices = MediaWikiServices::getInstance();
		$lb = $mwServices->getDBLoadBalancer();
		$dbw = $lb->getConnectionRef( DB_PRIMARY );
		$userFactory = $mwServices->getUserFactory();
		$editTracker = $mwServices->getUserEditTracker();
		$fname = __METHOD__;

		( new AutoCommitUpdate( $dbw, __METHOD__, function () use ( $lb, $dbw, $fname, $userFactory, $editTracker ) {
			foreach ( $this->infoByUser as $userId => $info ) {
				$dbw->update(
					'user',
					[ 'user_editcount=user_editcount+' . (int)$info['increment'] ],
					[ 'user_id' => $userId, 'user_editcount IS NOT NULL' ],
					$fname
				);
				/** @var UserIdentity[] $affectedInstances */
				$affectedInstances = $info['instances'];
				// Lazy initialization check...
				if ( $dbw->affectedRows() == 0 ) {
					// The user_editcount is probably NULL (e.g. not initialized).
					// Since this update runs after the new revisions were committed,
					// wait for the replica DB to catch up so they will be counted.
					$dbr = $lb->getConnectionRef( DB_REPLICA );
					// If $dbr is actually the primary DB, then clearing the snapshot
					// is harmless and waitForMasterPos() will just no-op.
					$dbr->flushSnapshot( $fname );
					$lb->waitForMasterPos( $dbr );
					$editTracker->initializeUserEditCount( $affectedInstances[0] );
				}
				$newCount = (int)$dbw->selectField(
					'user',
					'user_editcount',
					[ 'user_id' => $userId ],
					$fname
				);

				// Update the edit count in the instance caches. This is mostly useful
				// for maintenance scripts, where deferred updates might run immediately
				// and user instances might be reused for a long time. Only applies to
				// instances where we have User objects, if we have UserIdentity only
				// then invalidating the cache should be enough
				foreach ( $affectedInstances as $affectedInstance ) {
					if ( $affectedInstance instanceof User ) {
						$affectedInstance->setEditCountInternal( $newCount );
					}
				}
				// Clear the edit count in user cache too
				$userFactory->newFromUserIdentity( $affectedInstances[0] )->invalidateCache();
				// And the cache in UserEditTracker
				$editTracker->clearUserEditCache( $affectedInstances[0] );
			}
		} ) )->doUpdate();
	}
}
