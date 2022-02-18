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
use MediaWiki\Page\PageIdentity;
use MediaWiki\Permissions\Authority;
use MediaWiki\User\UserIdentity;

/**
 * A cached RevisionStoreRecord.  Ensures that changes performed "behind the back"
 * of the cache do not cause the revision record to deliver stale data.
 *
 * @internal
 * @since 1.33
 */
class RevisionStoreCacheRecord extends RevisionStoreRecord {

	/**
	 * @var null|callable ( int $revId ): [ int $rev_deleted, UserIdentity $user ]
	 */
	private $mCallback;

	/**
	 * @note Avoid calling this constructor directly. Use the appropriate methods
	 * in RevisionStore instead.
	 *
	 * @param callable $callback Callback for loading data.
	 *        Signature: function ( int $revId ): [ int $rev_deleted, UserIdentity $user ]
	 * @param PageIdentity $page The page this RevisionRecord is associated with.
	 * @param UserIdentity $user
	 * @param CommentStoreComment $comment
	 * @param \stdClass $row A row from the revision table. Use RevisionStore::getQueryInfo() to build
	 *        a query that yields the required fields.
	 * @param RevisionSlots $slots The slots of this revision.
	 * @param false|string $wikiID Relevant wiki id or self::LOCAL for the current one.
	 */
	public function __construct(
		callable $callback,
		PageIdentity $page,
		UserIdentity $user,
		CommentStoreComment $comment,
		$row,
		RevisionSlots $slots,
		$wikiID = self::LOCAL
	) {
		parent::__construct( $page, $user, $comment, $row, $slots, $wikiID );
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
	 * @param Authority|null $performer
	 *
	 * @return UserIdentity The identity of the revision author, null if access is forbidden.
	 */
	public function getUser( $audience = self::FOR_PUBLIC, Authority $performer = null ) {
		if ( $this->mCallback ) {
			$this->loadFreshRow();
		}
		return parent::getUser( $audience, $performer );
	}

	/**
	 * Load a fresh row from the database to ensure we return updated information
	 *
	 * @throws RevisionAccessException if the row could not be loaded
	 */
	private function loadFreshRow() {
		list( $freshRevDeleted, $freshUser ) = call_user_func( $this->mCallback, $this->mId );

		// Set to null to ensure we do not make unnecessary queries for subsequent getter calls,
		// and to allow the closure to be freed.
		$this->mCallback = null;

		if ( $freshRevDeleted !== null && $freshUser !== null ) {
			$this->mDeleted = intval( $freshRevDeleted );
			$this->mUser = $freshUser;
		} else {
			throw new RevisionAccessException(
				'Unable to load fresh row for rev_id: {rev_id}',
				[ 'rev_id' => $this->mId ]
			);
		}
	}

}
