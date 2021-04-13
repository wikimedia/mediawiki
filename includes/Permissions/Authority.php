<?php
/**
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

namespace MediaWiki\Permissions;

use MediaWiki\Page\PageIdentity;
use MediaWiki\User\UserIdentity;

/**
 * This interface represents the authority associated the current execution context,
 * such as a web request. The authority determines which actions can or cannot be performed
 * withing that execution context.
 *
 * See the individual implementations for information on how that authority is determined.
 *
 * @since 1.36
 */
interface Authority {

	/**
	 * Returns the performer of the actions associated with this authority.
	 *
	 * Actions performed under this authority should generally be attributed
	 * to the user identity returned by this method.
	 *
	 * @return UserIdentity
	 */
	public function getUser(): UserIdentity;

	/**
	 * Checks whether this authority has the given permission in general.
	 * For some permissions, exceptions may exist, both positive and negative, on a per-target basis.
	 *
	 * @param string $permission
	 *
	 * @return bool
	 */
	public function isAllowed( string $permission ): bool;

	/**
	 * Checks whether this authority has any of the given permissions in general.
	 *
	 * Implementations must ensure that this method returns true if isAllowed would return true
	 * for any of the given permissions. Calling isAllowedAny() with one parameter must be
	 * equivalent to calling isAllowed(). Calling isAllowedAny() with no parameter is not allowed.
	 *
	 * @see isAllowed
	 *
	 * @param string ...$permissions Permissions to test. At least one must be given.
	 * @return bool True if user is allowed to perform *any* of the given actions
	 */
	public function isAllowedAny( ...$permissions ): bool;

	/**
	 * Checks whether this authority has any of the given permissions in general.
	 *
	 * Implementations must ensure that this method returns false if isAllowed would return false
	 * for any of the given permissions. Calling isAllowedAll() with one parameter must be
	 * equivalent to calling isAllowed(). Calling isAllowedAny() with no parameter is not allowed.
	 *
	 * @see isAllowed
	 *
	 * @param string ...$permissions Permissions to test. At least one must be given.
	 * @return bool True if the user is allowed to perform *all* of the given actions
	 */
	public function isAllowedAll( ...$permissions ): bool;

	/**
	 * Checks whether this authority can probably perform the given action on the given target page.
	 * This method offers a fast, lightweight check, and may produce false positives.
	 * It is intended for determining which UI elements should be offered to the user.
	 *
	 * @param string $action
	 * @param PageIdentity $target
	 * @param PermissionStatus|null $status aggregator for failures
	 *
	 * @return bool
	 */
	public function probablyCan(
		string $action,
		PageIdentity $target,
		PermissionStatus $status = null
	): bool;

	/**
	 * Checks whether this authority can perform the given action on the given target page.
	 * This method performs a thorough check, but does not protect against race conditions.
	 * It is intended to be used when a user is intending to perform an action, but has not
	 * yet committed to it. For example, when a user goes to the edit page of an article, this
	 * method may be used to determine whether the user should be presented with a warning and
	 * a read-only view instead.
	 *
	 * @param string $action
	 * @param PageIdentity $target
	 * @param PermissionStatus|null $status aggregator for failures
	 *
	 * @return bool
	 */
	public function definitelyCan(
		string $action,
		PageIdentity $target,
		PermissionStatus $status = null
	): bool;

	/**
	 * Authorize read access. This should be used immediately before performing read access on
	 * restricted information.
	 *
	 * Calling this method may have non-trivial side-effects, such as incrementing a rate limit
	 * counter.
	 *
	 * @param string $action
	 * @param PageIdentity $target
	 * @param PermissionStatus|null $status aggregator for failures
	 *
	 * @return bool
	 */
	public function authorizeRead(
		string $action,
		PageIdentity $target,
		PermissionStatus $status = null
	): bool;

	/**
	 * Authorize write access. This should be used immediately before updating
	 * persisted information.
	 *
	 * Calling this method may have non-trivial side-effects, such as incrementing a rate limit
	 * counter.
	 *
	 * @param string $action
	 * @param PageIdentity $target
	 * @param PermissionStatus|null $status aggregator for failures
	 *
	 * @return bool
	 */
	public function authorizeWrite(
		string $action,
		PageIdentity $target,
		PermissionStatus $status = null
	): bool;

}
