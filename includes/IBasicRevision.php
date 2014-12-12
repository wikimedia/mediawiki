<?php
/**
 * Interface for revisions consisting of content, user, comment and timestamp.
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

interface IBasicRevision {

	// Revision deletion constants
	const DELETED_CONTENT = 1;
	const DELETED_COMMENT = 2;
	const DELETED_USER = 4;
	const DELETED_RESTRICTED = 8;
	const SUPPRESSED_USER = 12; // convenience

	// Audience options for accessors
	const FOR_PUBLIC = 1;
	const FOR_THIS_USER = 2;
	const RAW = 3;

	/**
	 * Returns the length or size of the content in this revision, or null if unknown.
	 *
	 * @return int|null
	 */
	public function getSize();

	/**
	 * Returns the base36 sha1 of the content in this revision, or null if unknown.
	 *
	 * @return string|null
	 */
	public function getSha1();

	/**
	 * Returns the title of the page associated with this entry or null.
	 *
	 * Will do a query, when title is not set and id is given.
	 *
	 * @return Title|null
	 */
	public function getTitle();

	/**
	 * Fetch revision comment if it's available to the specified audience.
	 * If the specified audience does not have access to the comment, an
	 * empty string will be returned.
	 *
	 * @param int $audience One of:
	 *   IBasicRevision::FOR_PUBLIC       to be displayed to all users
	 *   IBasicRevision::FOR_THIS_USER    to be displayed to the given user
	 *   IBasicRevision::RAW              get the text regardless of permissions
	 * @param User $user User object to check for, only if FOR_THIS_USER is passed
	 *   to the $audience parameter
	 * @return string
	 */
	public function getComment( $audience = self::FOR_PUBLIC, User $user = null );

	/**
	 * @param int $field One of DELETED_* bitfield constants
	 *
	 * @return bool
	 */
	public function isDeleted( $field );

	/**
	 * Get the deletion bitfield of the revision
	 *
	 * @return int
	 */
	public function getVisibility();

	/**
	 * @return string
	 */
	public function getTimestamp();

	/**
	 * @return bool
	 */
	public function isCurrent();

	/**
	 * Determine if the current user is allowed to view a particular
	 * field of this revision, if it's marked as deleted.
	 *
	 * @param int $field One of self::DELETED_CONTENT,
	 *                              self::DELETED_COMMENT,
	 *                              self::DELETED_USER
	 * @param User|null $user User object to check, or null to use $wgUser
	 * @return bool
	 */
	public function userCan( $field, User $user = null );
}
