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

use MediaWiki\Block\Block;
use MediaWiki\Exception\ErrorPageError;
use MediaWiki\Exception\PermissionsError;
use MediaWiki\Exception\ThrottledError;
use MediaWiki\Exception\UserBlockedError;
use StatusValue;

/**
 * A StatusValue for permission errors.
 *
 * @todo Add compat code for PermissionManager::getPermissionErrors
 *       and additional info about user blocks.
 *
 * @unstable
 * @since 1.36
 */
class PermissionStatus extends StatusValue {

	/** @var ?Block */
	private $block = null;

	/** @var bool */
	private $rateLimitExceeded = false;

	/** @var ?string */
	private $permission = null;

	/**
	 * Returns the user block that contributed to permissions being denied,
	 * if such a block was provided via setBlock().
	 *
	 * This is intended to be used to provide additional information to the user that
	 * allows them to determine the reason for them being denied an action.
	 *
	 * @since 1.37
	 *
	 * @return ?Block
	 */
	public function getBlock(): ?Block {
		return $this->block;
	}

	/**
	 * Returns true when permissions were denied because the user is blocked.
	 *
	 * @since 1.41
	 *
	 * @return bool
	 */
	public function isBlocked(): bool {
		return $this->block !== null;
	}

	/**
	 * @since 1.37
	 * @internal
	 * @param Block $block
	 */
	public function setBlock( Block $block ): void {
		$this->block = $block;
		$this->setOK( false );
	}

	/**
	 * @return static
	 */
	public static function newEmpty() {
		return new static();
	}

	/**
	 * Call this to indicate that the user is over the rate limit for some action.
	 * @since 1.41
	 * @internal
	 * Will cause isRateLimitExceeded() to return true.
	 */
	public function setRateLimitExceeded() {
		$this->rateLimitExceeded = true;
		$this->fatal( 'actionthrottledtext' );
	}

	/**
	 * Whether the user is over the rate limit for some action.
	 * @since 1.41
	 * @return bool True if setRateLimitExceeded() was called.
	 */
	public function isRateLimitExceeded(): bool {
		return $this->rateLimitExceeded;
	}

	/**
	 * Sets the name of the permission that is being checked.
	 *
	 * @since 1.41
	 * @internal
	 */
	public function setPermission( string $permission ) {
		$this->permission = $permission;
	}

	/**
	 * Returns the name of the permission that was being checked.
	 *
	 * @return string|null The permission, if known
	 * @since 1.41
	 */
	public function getPermission(): ?string {
		return $this->permission;
	}

	/**
	 * Will throw an appropriate ErrorPageError if isOK() returns false.
	 * If isOK() returns true, this method does nothing.
	 *
	 * This is a convenience method for use in user interaction code,
	 * such as subclasses of SpecialPage.
	 *
	 * @unstable Introduced in 1.41, but unstable since the future of ErrorPageError is unclear (T281935).
	 *
	 * @throws ErrorPageError
	 * @return void
	 */
	public function throwErrorPageError(): void {
		if ( $this->isOK() ) {
			return;
		}

		$block = $this->getBlock();
		if ( $block ) {
			throw new UserBlockedError( $block );
		}

		if ( $this->isRateLimitExceeded() ) {
			throw new ThrottledError();
		}

		$messages = $this->getStatusArray( 'error' );

		throw new PermissionsError( $this->permission, $messages );
	}

}
