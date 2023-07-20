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

use IDBAccessObject;
use MediaWiki\Block\Block;
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
	 * @var int Fetch information quickly, slightly stale data is acceptable.
	 * @see IDBAccessObject::READ_NORMAL
	 */
	public const READ_NORMAL = IDBAccessObject::READ_NORMAL;

	/**
	 * @var int Fetch information reliably, stale data is not acceptable.
	 * @see IDBAccessObject::READ_LATEST
	 */
	public const READ_LATEST = IDBAccessObject::READ_LATEST;

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
	 * Returns any user block affecting the Authority.
	 *
	 * @param int $freshness Indicates whether slightly stale data is acceptable in,
	 *        exchange for a fast response.
	 *
	 * @return ?Block
	 * @since 1.37
	 */
	public function getBlock( int $freshness = self::READ_NORMAL ): ?Block;

	/**
	 * Checks whether this authority has the given permission in general.
	 * For some permissions, exceptions may exist, both positive and negative, on a per-target basis.
	 * This method offers a fast, lightweight check, but may produce false positives.
	 * It is intended for determining which UI elements should be offered to the user.
	 *
	 * This method will not apply rate limit checks or evaluate user blocks.
	 *
	 * @param string $permission
	 * @param PermissionStatus|null $status
	 *
	 * @return bool
	 * @see isDefinitelyAllowed
	 *
	 * @see probablyCan
	 */
	public function isAllowed( string $permission, PermissionStatus $status = null ): bool;

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
	 * This method offers a fast, lightweight check, but may produce false positives.
	 * It is intended for determining which UI elements should be offered to the user.
	 * This method will not apply rate limit checks or evaluate user blocks.
	 *
	 * @see definitelyCan
	 * @see isAllowed
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
	 * This method may apply rate limit checks and evaluate user blocks.
	 *
	 * @see probablyCan
	 * @see isDefinitelyAllowed
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
	 * Checks whether this authority is allowed to perform the given action.
	 * This method performs a thorough check, but does not protect against race conditions.
	 * It is intended to be used when a user is intending to perform an action, but has not
	 * yet committed to it. For example, when a user visits their preferences page, this
	 * method may be used to determine whether the user should have the option to change their
	 * email address.
	 *
	 * This method may apply rate limit checks and evaluate user blocks.
	 *
	 * @since 1.41
	 *
	 * @see isAllowed
	 * @see definitelyCan
	 *
	 * @param string $action
	 * @param PermissionStatus|null $status aggregator for failures
	 *
	 * @return bool
	 */
	public function isDefinitelyAllowed(
		string $action,
		PermissionStatus $status = null
	): bool;

	/**
	 * Authorize an action. This should be used immediately before performing the action.
	 *
	 * Calling this method may have non-trivial side-effects, such as incrementing a rate limit
	 * counter.
	 *
	 * @since 1.41
	 *
	 * @see isDefinitelyAllowed
	 * @see authorizeRead
	 * @see authorizeWrite
	 *
	 * @param string $action
	 * @param PermissionStatus|null $status aggregator for failures
	 *
	 * @return bool
	 */
	public function authorizeAction(
		string $action,
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
	 * @return bool If the user can perform the action
	 * @see authorizeAction
	 * @see authorizeWrite
	 *
	 * @see definitelyCan
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
	 * @return bool If the user can perform the action
	 * @see authorizeAction
	 * @see authorizeRead
	 *
	 * @see definitelyCan
	 */
	public function authorizeWrite(
		string $action,
		PageIdentity $target,
		PermissionStatus $status = null
	): bool;

	/**
	 * Get whether the user is registered.
	 *
	 * @return bool
	 * @since 1.39
	 */
	public function isRegistered(): bool;

	/**
	 * Is the user an autocreated temporary user?
	 *
	 * @since 1.39
	 * @return bool
	 */
	public function isTemp(): bool;

	/**
	 * Is the user a normal non-temporary registered user?
	 *
	 * @since 1.39
	 * @return bool
	 */
	public function isNamed(): bool;
}
