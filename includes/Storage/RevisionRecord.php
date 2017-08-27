<?php
/**
 * Value object representing a page revision.
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

namespace MediaWiki\Storage;

use Content;
use OutOfBoundsException;
use User;

/**
 * Value object representing a page revision.
 *
 * MCR migration note: this is a drop-in replacement for some aspects of Revision.
 *
 * @since 1.30
 */
interface RevisionRecord {

	// RevisionRecord deletion constants
	const DELETED_TEXT = 1;
	const DELETED_COMMENT = 2;
	const DELETED_USER = 4;
	const DELETED_RESTRICTED = 8;
	const SUPPRESSED_USER = 12; // convenience
	const SUPPRESSED_ALL = 15; // convenience

	// Audience options for accessors
	const FOR_PUBLIC = 1;
	const FOR_THIS_USER = 2;
	const RAW = 3;

	/**
	 * Returns the Content of the given slot.
	 *
	 * Note that for mutable Content objects, each call to this method will return a
	 * fresh clone.
	 *
	 * @note This is free to load Content from whatever subsystem is necessary,
	 * performing potentially expensive operations and triggering I/O-related
	 * failure modes.
	 *
	 * @param string $slot The role name of the desired slot
	 * @param int $audience
	 * @param User $user
	 *
	 * @return Content
	 */
	public function getContent( $slot, $audience = self::FOR_PUBLIC, User $user = null );

	/**
	 * Returns the slot names (roles) of all slots present in this revision.
	 *
	 * @return string[]
	 */
	public function getSlotNames();

	/**
	 * Get revision ID
	 *
	 * @return int|null
	 */
	public function getId();

	/**
	 * Get parent revision ID (the original previous page revision)
	 *
	 * @return int|null
	 */
	public function getParentId();

	/**
	 * Returns the length of the text in this revision, or null if unknown.
	 *
	 * @return int|null
	 */
	public function getSize();

	/**
	 * Returns the base36 sha1 of the text in this revision, or null if unknown.
	 *
	 * @return string|null
	 */
	public function getSha1();

	/**
	 * Get the page ID
	 *
	 * @return int|null
	 */
	public function getPage();

	/**
	 * Get the ID of the wiki this revision belongs to.
	 *
	 * @return string|false The symbolic wiki ID, or false for the local wiki.
	 */
	public function getWiki();

	/**
	 * Fetch revision's user id if it's available to the specified audience.
	 * If the specified audience does not have access to it, zero will be
	 * returned.
	 *
	 * @param int $audience One of:
	 *   RevisionRecord::FOR_PUBLIC       to be displayed to all users
	 *   RevisionRecord::FOR_THIS_USER    to be displayed to the given user
	 *   RevisionRecord::RAW              get the ID regardless of permissions
	 * @param User|null $user User object to check for, only if FOR_THIS_USER is passed
	 *   to the $audience parameter
	 * @return int
	 */
	public function getUser( $audience = self::FOR_PUBLIC, User $user = null );

	/**
	 * Fetch revision's username if it's available to the specified audience.
	 * If the specified audience does not have access to the username, an
	 * empty string will be returned.
	 *
	 * @param int $audience One of:
	 *   RevisionRecord::FOR_PUBLIC       to be displayed to all users
	 *   RevisionRecord::FOR_THIS_USER    to be displayed to the given user
	 *   RevisionRecord::RAW              get the text regardless of permissions
	 * @param User|null $user User object to check for, only if FOR_THIS_USER is passed
	 *   to the $audience parameter
	 * @return string
	 */
	public function getUserText( $audience = self::FOR_PUBLIC, User $user = null );

	/**
	 * Fetch revision comment if it's available to the specified audience.
	 * If the specified audience does not have access to the comment, an
	 * empty string will be returned.
	 *
	 * // FIXME: return a ContentStoreContent! See Ic3a434c.
	 *
	 * @param int $audience One of:
	 *   RevisionRecord::FOR_PUBLIC       to be displayed to all users
	 *   RevisionRecord::FOR_THIS_USER    to be displayed to the given user
	 *   RevisionRecord::RAW              get the text regardless of permissions
	 * @param User|null $user User object to check for, only if FOR_THIS_USER is passed
	 *   to the $audience parameter
	 * @return string
	 */
	function getComment( $audience = self::FOR_PUBLIC, User $user = null );

	/**
	 * @return bool
	 */
	public function isMinor();

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

}