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
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use Status;
use User;
use Wikimedia\IPUtils;

/**
 * Backend class for blocking utils
 *
 * This service should contain any methods that are useful
 * to more than one blocking-related class and doesn't fit any
 * other service.
 *
 * For now, this includes only
 * - block target parsing
 * - block target validation
 *
 * @since 1.36
 */
class BlockUtils {
	/** @var ServiceOptions */
	private $options;

	/** @var UserFactory */
	private $userFactory;

	/**
	 * @internal Only for use by ServiceWiring
	 */
	public const CONSTRUCTOR_OPTIONS = [
		'BlockCIDRLimit',
	];

	/**
	 * @param ServiceOptions $options
	 * @param UserFactory $userFactory
	 */
	public function __construct(
		ServiceOptions $options,
		UserFactory $userFactory
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->options = $options;
		$this->userFactory = $userFactory;
	}

	/**
	 * From an existing block, get the target and the type of target.
	 *
	 * Note that, except for null, it is always safe to treat the target
	 * as a string; for User objects this will return User::__toString()
	 * which in turn gives User::getName().
	 *
	 * If the type is not null, it will be an AbstractBlock::TYPE_ constant.
	 *
	 * @param string|UserIdentity|null $target
	 * @return array [ User|String|null, int|null ]
	 */
	public function parseBlockTarget( $target ) : array {
		// We may have been through this before
		if ( $target instanceof UserIdentity ) {
			$userObj = $this->userFactory->newFromUserIdentity( $target );
			if ( IPUtils::isValid( $target->getName() ) ) {
				return [ $userObj, AbstractBlock::TYPE_IP ];
			} else {
				return [ $userObj, AbstractBlock::TYPE_USER ];
			}
		} elseif ( $target === null ) {
			return [ null, null ];
		}

		$target = trim( $target );

		if ( IPUtils::isValid( $target ) ) {
			// We can still create a User if it's an IP address, but we need to turn
			// off validation checking (which would exclude IP addresses)
			return [
				$this->userFactory->newFromName(
					IPUtils::sanitizeIP( $target ),
					UserFactory::RIGOR_NONE
				),
				AbstractBlock::TYPE_IP
			];

		} elseif ( IPUtils::isValidRange( $target ) ) {
			// Can't create a User from an IP range
			return [ IPUtils::sanitizeRange( $target ), AbstractBlock::TYPE_RANGE ];
		}

		// Consider the possibility that this is not a username at all
		// but actually an old subpage (T31797)
		if ( strpos( $target, '/' ) !== false ) {
			// An old subpage, drill down to the user behind it
			$target = explode( '/', $target )[0];
		}

		$userObj = $this->userFactory->newFromName( $target );
		if ( $userObj instanceof User ) {
			// Note that since numbers are valid usernames, a $target of "12345" will be
			// considered a User.  If you want to pass a block ID, prepend a hash "#12345",
			// since hash characters are not valid in usernames or titles generally.
			return [ $userObj, AbstractBlock::TYPE_USER ];
		} elseif ( preg_match( '/^#\d+$/', $target ) ) {
			// Autoblock reference in the form "#12345"
			return [ substr( $target, 1 ), AbstractBlock::TYPE_AUTO ];
		} else {
			return [ null, null ];
		}
	}

	/**
	 * Validate block target
	 *
	 * @param string|UserIdentity $value
	 *
	 * @return Status
	 */
	public function validateTarget( $value ) : Status {
		list( $target, $type ) = $this->parseBlockTarget( $value );

		$status = Status::newGood( $target );

		switch ( $type ) {
			case AbstractBlock::TYPE_USER:
				if ( $target->isAnon() ) {
					$status->fatal(
						'nosuchusershort',
						wfEscapeWikiText( $target->getName() )
					);
				}
				break;

			case AbstractBlock::TYPE_RANGE:
				list( $ip, $range ) = explode( '/', $target, '2' );

				if ( IPUtils::isIPv4( $ip ) ) {
					$status->merge( $this->validateIPv4Range( $range ) );
				} elseif ( IPUtils::isIPv6( $ip ) ) {
					$status->merge( $this->validateIPv6Range( $range ) );
				} else {
					// Something is FUBAR
					$status->fatal( 'badipaddress' );
				}
				break;

			case AbstractBlock::TYPE_IP:
				// All is well
				break;

			default:
				$status->fatal( 'badipaddress' );
				break;
		}

		return $status;
	}

	/**
	 * Validate an IPv4 range
	 *
	 * @param int $range
	 *
	 * @return Status
	 */
	private function validateIPv4Range( int $range ) : Status {
		$status = Status::newGood();
		$blockCIDRLimit = $this->options->get( 'BlockCIDRLimit' );

		if ( $blockCIDRLimit['IPv4'] == 32 ) {
			// Range block effectively disabled
			$status->fatal( 'range_block_disabled' );
		} elseif ( $range > 32 ) {
			// Such a range cannot exist
			$status->fatal( 'ip_range_invalid' );
		} elseif ( $range < $blockCIDRLimit['IPv4'] ) {
			$status->fatal( 'ip_range_toolarge', $blockCIDRLimit['IPv4'] );
		}

		return $status;
	}

	/**
	 * Validate an IPv6 range
	 *
	 * @param int $range
	 *
	 * @return Status
	 */
	private function validateIPv6Range( int $range ) : Status {
		$status = Status::newGood();
		$blockCIDRLimit = $this->options->get( 'BlockCIDRLimit' );

		if ( $blockCIDRLimit['IPv6'] == 128 ) {
			// Range block effectively disabled
			$status->fatal( 'range_block_disabled' );
		} elseif ( $range > 128 ) {
			// Dodgy range - such a range cannot exist
			$status->fatal( 'ip_range_invalid' );
		} elseif ( $range < $blockCIDRLimit['IPv6'] ) {
			$status->fatal( 'ip_range_toolarge', $blockCIDRLimit['IPv6'] );
		}

		return $status;
	}
}
