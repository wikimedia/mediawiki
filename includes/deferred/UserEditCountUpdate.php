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

namespace MediaWiki\Deferred;

use MediaWiki\HookContainer\HookRunner;
use MediaWiki\MediaWikiServices;
use MediaWiki\User\UserIdentity;
use RuntimeException;
use Wikimedia\Assert\Assert;
use Wikimedia\Rdbms\RawSQLValue;

/**
 * Handles increment the edit count for a given set of users
 */
class UserEditCountUpdate implements DeferrableUpdate, MergeableUpdate {
	/**
	 * We need to keep a single copy of the relevant UserIdentity to be able to pass to UserEditTracker
	 *
	 * @var UserEditCountInfo[] Map of (user ID => UserEditCountInfo)
	 */
	private $infoByUser;

	/**
	 * @param UserIdentity $user
	 * @param int $increment
	 */
	public function __construct( UserIdentity $user, $increment ) {
		if ( !$user->getId() ) {
			throw new RuntimeException( "Got anonymous user" );
		}
		$this->infoByUser = [
			$user->getId() => new UserEditCountInfo( $user, $increment ),
		];
	}

	public function merge( MergeableUpdate $update ) {
		/** @var UserEditCountUpdate $update */
		Assert::parameterType( __CLASS__, $update, '$update' );
		'@phan-var UserEditCountUpdate $update';

		foreach ( $update->infoByUser as $userId => $info ) {
			if ( !isset( $this->infoByUser[$userId] ) ) {
				$this->infoByUser[$userId] = $info;
			} else {
				$this->infoByUser[$userId]->merge( $info );
			}
		}
	}

	/**
	 * Commits the provided user edit count increments to the database
	 */
	public function doUpdate() {
		$mwServices = MediaWikiServices::getInstance();
		$lb = $mwServices->getDBLoadBalancer();
		$dbw = $lb->getConnection( DB_PRIMARY );
		$editTracker = $mwServices->getUserEditTracker();
		$fname = __METHOD__;

		( new AutoCommitUpdate( $dbw, __METHOD__, function () use ( $lb, $dbw, $fname, $editTracker ) {
			foreach ( $this->infoByUser as $userId => $info ) {
				$dbw->newUpdateQueryBuilder()
					->update( 'user' )
					->set( [
						'user_editcount' => new RawSQLValue(
							'user_editcount+' . (int)$info->getIncrement()
						)
					] )
					->where( [ 'user_id' => $userId, 'user_editcount IS NOT NULL' ] )
					->caller( $fname )->execute();
				// Lazy initialization check...
				if ( $dbw->affectedRows() == 0 ) {
					// The user_editcount is probably NULL (e.g. not initialized).
					// Since this update runs after the new revisions were committed,
					// wait for the replica DB to catch up so they will be counted.
					$dbr = $lb->getConnection( DB_REPLICA );
					// If $dbr is actually the primary DB, then clearing the snapshot
					// is harmless and waitForPrimaryPos() will just no-op.
					$dbr->flushSnapshot( $fname );
					$lb->waitForPrimaryPos( $dbr );
					$editTracker->initializeUserEditCount( $info->getUser() );
				}

				// Clear the edit count in the UserEditTracker cache.
				$editTracker->clearUserEditCache( $info->getUser() );
			}
		} ) )->doUpdate();

		$hookRunner = new HookRunner( $mwServices->getHookContainer() );
		$hookRunner->onUserEditCountUpdate( array_values( $this->infoByUser ) );
	}
}

/** @deprecated class alias since 1.42 */
class_alias( UserEditCountUpdate::class, 'UserEditCountUpdate' );
