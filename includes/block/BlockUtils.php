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
use MediaWiki\User\UserIdentity;
use Status;
use Wikimedia\IPUtils;

/**
 * Backend class for blocking utils
 *
 * For now, this includes only block target validation,
 * but this service should contain any methods that are useful
 * to more than one blocking-related class and doesn't fit any
 * other service.
 *
 * @since 1.36
 */
class BlockUtils {
	/** @var ServiceOptions */
	private $options;

	/**
	 * @internal Only for use by ServiceWiring
	 */
	public const CONSTRUCTOR_OPTIONS = [
		'BlockCIDRLimit',
	];

	/**
	 * @param ServiceOptions $options
	 */
	public function __construct(
		ServiceOptions $options
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->options = $options;
	}

	/**
	 * Validate block target
	 *
	 * @param string|UserIdentity $value
	 *
	 * @return Status
	 */
	public function validateTarget( $value ) {
		list( $target, $type ) = AbstractBlock::parseTarget( $value );

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
					$status->merge( $this->validateIPv4Target( $ip, $range ) );
				} elseif ( IPUtils::isIPv6( $ip ) ) {
					$status->merge( $this->validateIPv6Target( $ip, $range ) );
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
	 * Validate IPv4 target
	 *
	 * @param string $ip
	 * @param int $range
	 *
	 * @return Status
	 */
	private function validateIPv4Target( string $ip, int $range ) : Status {
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
	 * Validate IPv6 target
	 *
	 * @param string $ip
	 * @param int $range
	 *
	 * @return Status
	 */
	private function validateIPv6Target( string $ip, int $range ) : Status {
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
