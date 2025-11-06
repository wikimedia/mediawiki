<?php

/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Block;

use InvalidArgumentException;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\Permissions\Authority;
use MediaWiki\User\UserIdentity;
use Wikimedia\Rdbms\IDBAccessObject;

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
	 * Legacy target state
	 * @var BlockTarget|null Block target or null when unknown
	 */
	private $target;

	/**
	 * @var BlockTargetFactory
	 */
	private $blockTargetFactory;

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

	private ServiceOptions $options;

	/**
	 * @param ServiceOptions $options
	 * @param BlockTargetFactory $blockTargetFactory For legacy branches only
	 * @param Authority $performer
	 */
	public function __construct(
		ServiceOptions $options,
		BlockTargetFactory $blockTargetFactory,
		Authority $performer
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->options = $options;
		$this->blockTargetFactory = $blockTargetFactory;
		$this->performer = $performer;
	}

	/**
	 * @internal To support deprecated method BlockPermissionCheckerFactory::newBlockPermissionChecker()
	 * @param UserIdentity|string $target
	 * @return void
	 */
	public function setTarget( $target ) {
		$this->target = $this->blockTargetFactory->newFromLegacyUnion( $target );
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
	 * @param BlockTarget|UserIdentity|string|null $target The target of the
	 *   proposed block or unblock operation. Passing null for this parameter
	 *   is deprecated. This parameter will soon be required. Passing a
	 *   UserIdentity or string for this parameter is deprecated. Pass a
	 *   BlockTarget in new code.
	 * @param int $freshness Indicates whether slightly stale data is acceptable
	 *   in exchange for a fast response.
	 * @return bool|string True when checks passed, message code for failures
	 */
	public function checkBlockPermissions(
		$target = null,
		$freshness = IDBAccessObject::READ_NORMAL
	) {
		if ( $target === null ) {
			if ( $this->target ) {
				wfDeprecatedMsg(
					'Passing null to checkBlockPermissions() for $target is deprecated since 1.44',
					'1.44' );
				$target = $this->target;
			} else {
				throw new InvalidArgumentException( 'A target is required' );
			}
		} elseif ( !( $target instanceof BlockTarget ) ) {
			$target = $this->blockTargetFactory->newFromLegacyUnion( $target );
			if ( !$target ) {
				throw new InvalidArgumentException( 'Invalid block target' );
			}
		}

		$block = $this->performer->getBlock( $freshness );
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
			$target instanceof UserBlockTarget &&
			$target->getUserIdentity()->getId() === $performerIdentity->getId()
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
			$target instanceof UserBlockTarget &&
			$block->getBlocker() &&
			$target->getUserIdentity()->equals( $block->getBlocker() )
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
