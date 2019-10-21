<?php
/**
 * Class for temporary blocks created on enforcement.
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

namespace MediaWiki\Block;

use IContextSource;

/**
 * System blocks are temporary blocks that are created on enforcement (e.g.
 * from IP blacklists) and are not saved to the database. The target of a
 * system block is an IP address. System blocks do not give rise to
 * autoblocks and are not tracked with cookies.
 *
 * @since 1.34
 */
class SystemBlock extends AbstractBlock {
	/** @var string|null */
	private $systemBlockType;

	/**
	 * Create a new block with specified parameters on a user, IP or IP range.
	 *
	 * @param array $options Parameters of the block, with options supported by
	 *  `AbstractBlock::__construct`, and also:
	 *  - systemBlock: (string) Indicate that this block is automatically created by
	 *    MediaWiki rather than being stored in the database. Value is a string to
	 *    return from self::getSystemBlockType().
	 */
	public function __construct( array $options = [] ) {
		parent::__construct( $options );

		$defaults = [
			'systemBlock' => null,
		];

		$options += $defaults;

		$this->systemBlockType = $options['systemBlock'];
	}

	/**
	 * Get the system block type, if any. A SystemBlock can have the following types:
	 * - 'proxy': the IP is blacklisted in $wgProxyList
	 * - 'dnsbl': the IP is associated with a blacklisted domain in $wgDnsBlacklistUrls
	 * - 'wgSoftBlockRanges': the IP is covered by $wgSoftBlockRanges
	 * - 'global-block': for backwards compatability with the UserIsBlockedGlobally hook
	 *
	 * @since 1.29
	 * @return string|null
	 */
	public function getSystemBlockType() {
		return $this->systemBlockType;
	}

	/**
	 * @inheritDoc
	 */
	public function getPermissionsError( IContextSource $context ) {
		$params = $this->getBlockErrorParams( $context );

		// TODO: Clean up error messages params so we don't have to do this (T227174)
		$params[ 4 ] = $this->getSystemBlockType();

		$msg = 'systemblockedtext';

		array_unshift( $params, $msg );

		return $params;
	}

	/**
	 * @inheritDoc
	 */
	public function appliesToPasswordReset() {
		switch ( $this->getSystemBlockType() ) {
			case null:
			case 'global-block':
				return $this->isCreateAccountBlocked();
			case 'proxy':
				return true;
			case 'dnsbl':
			case 'wgSoftBlockRanges':
				return false;
			default:
				return true;
		}
	}

}
