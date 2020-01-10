<?php
/**
 * A RevisionStoreRecord loaded from the cache.
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

namespace MediaWiki\Revision;

use CommentStoreComment;
use InvalidArgumentException;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use Title;
use User;

/**
 * A cached RevisionStoreRecord.  Ensures that changes performed "behind the back"
 * of the cache do not cause the revision record to deliver stale data.
 *
 * @since 1.33
 */
class RevisionStoreCacheRecord extends RevisionStoreRecord {

	/**
	 * @var callable
	 */
	private $mCallback;

	/**
	 * @note Avoid calling this constructor directly. Use the appropriate methods
	 * in RevisionStore instead.
	 *
	 * @param callable $callback Callback for loading data.  Signature: function ( $id ): object
	 * @param Title $title The title of the page this Revision is associated with.
	 * @param UserIdentity $user
	 * @param CommentStoreComment $comment
	 * @param object $row A row from the revision table. Use RevisionStore::getQueryInfo() to build
	 *        a query that yields the required fields.
	 * @param RevisionSlots $slots The slots of this revision.
	 * @param bool|string $dbDomain DB domain of the relevant wiki or false for the current one.
	 */
	public function __construct(
		$callback,
		Title $title,
		UserIdentity $user,
		CommentStoreComment $comment,
		$row,
		RevisionSlots $slots,
		$dbDomain = false
	) {
		parent::__construct( $title, $user, $comment, $row, $slots, $dbDomain );
		$this->mCallback = $callback;
	}

	/**
	 * Overridden to ensure that we return a fresh value and not a cached one.
	 *
	 * @return int
	 */
	public function getVisibility() {
		if ( $this->mCallback ) {
			$this->loadFreshRow();
		}
		return parent::getVisibility();
	}

	/**
	 * Overridden to ensure that we return a fresh value and not a cached one.
	 *
	 * @param int $audience
	 * @param User|null $user
	 *
	 * @return UserIdentity The identity of the revision author, null if access is forbidden.
	 */
	public function getUser( $audience = self::FOR_PUBLIC, User $user = null ) {
		if ( $this->mCallback ) {
			$this->loadFreshRow();
		}
		return parent::getUser( $audience, $user );
	}

	/**
	 * Load a fresh row from the database to ensure we return updated information
	 *
	 * @throws RevisionAccessException if the row could not be loaded
	 */
	private function loadFreshRow() {
		$freshRow = call_user_func( $this->mCallback, $this->mId );

		// Set to null to ensure we do not make unnecessary queries for subsequent getter calls,
		// and to allow the closure to be freed.
		$this->mCallback = null;

		if ( $freshRow ) {
			$this->mDeleted = intval( $freshRow->rev_deleted );

			try {
				$this->mUser = User::newFromAnyId(
					$freshRow->rev_user ?? null,
					$freshRow->rev_user_text ?? null,
					$freshRow->rev_actor ?? null
				);
			} catch ( InvalidArgumentException $ex ) {
				wfWarn(
					__METHOD__
					. ': '
					. $this->mTitle->getPrefixedDBkey()
					. ': '
					. $ex->getMessage()
				);
				$this->mUser = new UserIdentityValue( 0, 'Unknown user', 0 );
			}
		} else {
			throw new RevisionAccessException(
				'Unable to load fresh row for rev_id: ' . $this->mId
			);
		}
	}

}
