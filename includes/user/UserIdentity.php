<?php
/**
 * Interface for objects representing user identity.
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

namespace MediaWiki\User;

/**
 * Interface for objects representing user identity.
 *
 * This represents the identity of a user in the context of page revisions and log entries.
 *
 * @since 1.31
 */
interface UserIdentity {

	/**
	 * @since 1.31
	 *
	 * @return int The user ID. May be 0 for anonymous users or for users with no local account.
	 */
	public function getId();

	/**
	 * @since 1.31
	 *
	 * @return string The user's logical name. May be an IPv4 or IPv6 address for anonymous users.
	 */
	public function getName();

	/**
	 * @since 1.31
	 *
	 * @return int The user's actor ID. May be 0 if no actor ID is set.
	 */
	public function getActorId();

	// TODO: we may want to (optionally?) provide a global ID, see CentralIdLookup.

}
