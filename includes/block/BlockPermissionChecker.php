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

namespace MediaWiki\Block;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\Permissions\Authority;
use MediaWiki\User\UserIdentity;

/**
 * Block permissions
 *
 * This class is responsible for making sure a user has permission to block.
 *
 * This class is usable for both blocking and unblocking.
 *
 * @since 1.35
 */
class BlockPermissionChecker {
	/**
	 * @var UserIdentity|string|null Block target or null when unknown
	 */
	private $target;

	/**
	 * @var Authority Block performer
	 */
	private $performer;

	/**
	 * @internal only for use by ServiceWiring and BlockPermissionCheckerFactory
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::EnableUserEmail,
	];

	/** @var ServiceOptions */
	private $options;

	/**
	 * @param ServiceOptions $options
	 * @param BlockUtils $blockUtils
	 * @param UserIdentity|string|null $target
	 * @param Authority $performer
	 */
	public function __construct(
		ServiceOptions $options,
		BlockUtils $blockUtils,
		$target,
		Authority $performer
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->options = $options;
		[ $this->target, ] = $blockUtils->parseBlockTarget( $target );
		$this->performer = $performer;
	}

	/**
	 * Check the base permission that applies to either block or unblock
	 *
	 * @since 1.36
	 * @param bool $checkHideuser
	 * @return bool|string
	 */
	public function checkBasePermissions( $checkHideuser = false ) {
		if ( !$this->performer->isAllowed( 'block' ) ) {
			return 'badaccess-group0';
		}

		if (
			$checkHideuser &&
			!$this->performer->isAllowed( 'hideuser' )
		) {
			return 'unblock-hideuser';
		}

		return true;
	}

	/**
	 * Checks block-related permissions (doesn't check any other permissions)
	 *
	 * T17810: Site-wide blocked admins should not be able to block/unblock
	 * others with one exception; they can block the user who blocked them,
	 * to reduce advantage of a malicious account blocking all admins (T150826).
	 *
	 * T208965: Partially blocked admins can block and unblock others as normal.
	 *
	 * @return bool|string True when checks passed, message code for failures
	 */
	public function checkBlockPermissions() {
		$block = $this->performer->getBlock(); // TODO: pass disposition parameter
		if ( !$block ) {
			// User is not blocked, process as normal
			return true;
		}

		if ( !$block->isSitewide() ) {
			// T208965: Partially blocked admins should have full access
			return true;
		}

		$performerIdentity = $this->performer->getUser();

		if (
			$this->target instanceof UserIdentity &&
			$this->target->getId() === $performerIdentity->getId()
		) {
			// Blocked admin is trying to alter their own block

			// Self-blocked admins can always remove or alter their block
			if ( $block->getBlocker() && $performerIdentity->equals( $block->getBlocker() ) ) {
				return true;
			}

			// Users with 'unblockself' right can unblock themselves or alter their own block
			if ( $this->performer->isAllowed( 'unblockself' ) ) {
				return true;
			} else {
				return 'ipbnounblockself';
			}
		}

		if (
			$this->target instanceof UserIdentity &&
			$block->getBlocker() &&
			$this->target->equals( $block->getBlocker() )
		) {
			// T150826: Blocked admins can always block the admin who blocked them
			return true;
		}

		// User is blocked and no exception took effect
		return 'ipbblocked';
	}

	/**
	 * Check permission to block emailing
	 *
	 * @since 1.36
	 * @return bool
	 */
	public function checkEmailPermissions() {
		return $this->options->get( MainConfigNames::EnableUserEmail ) &&
			$this->performer->isAllowed( 'blockemail' );
	}
}
