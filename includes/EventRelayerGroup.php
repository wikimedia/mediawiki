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

use MediaWiki\MediaWikiServices;

/**
 * Factory class for spawning EventRelayer objects using configuration
 *
 * @since 1.27
 */
class EventRelayerGroup {
	/** @var array[] */
	protected $configByChannel = [];

	/** @var EventRelayer[] */
	protected $relayers = [];

	/**
	 * @param array[] $config Channel configuration
	 */
	public function __construct( array $config ) {
		$this->configByChannel = $config;
	}

	/**
	 * @deprecated since 1.27 Use MediaWikiServices::getInstance()->getEventRelayerGroup()
	 * @return EventRelayerGroup
	 */
	public static function singleton() {
		wfDeprecated( __METHOD__, '1.27' );
		return MediaWikiServices::getInstance()->getEventRelayerGroup();
	}

	/**
	 * @param string $channel
	 * @return EventRelayer Relayer instance that handles the given channel
	 */
	public function getRelayer( $channel ) {
		$channelKey = isset( $this->configByChannel[$channel] )
			? $channel
			: 'default';

		if ( !isset( $this->relayers[$channelKey] ) ) {
			if ( !isset( $this->configByChannel[$channelKey] ) ) {
				throw new UnexpectedValueException( "No config for '$channelKey'" );
			}

			$config = $this->configByChannel[$channelKey];
			$class = $config['class'];

			$this->relayers[$channelKey] = new $class( $config );
		}

		return $this->relayers[$channelKey];
	}
}
