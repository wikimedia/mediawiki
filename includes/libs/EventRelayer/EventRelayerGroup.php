<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace Wikimedia\EventRelayer;

use UnexpectedValueException;

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

/** @deprecated class alias since 1.41 */
class_alias( EventRelayerGroup::class, 'EventRelayerGroup' );
