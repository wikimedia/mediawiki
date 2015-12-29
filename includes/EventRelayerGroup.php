<?php
/**
 * Factory class for spawning EventRelayer objects using configuration
 *
 * @author Aaron Schulz
 * @since 1.27
 */
class EventRelayerGroup {
	/** @var array[] */
	protected $configByChannel = array();

	/** @var EventRelayer[] */
	protected $relayers = array();

	/** @var EventRelayerGroup */
	protected static $instance = null;

	/**
	 * @param Config $config
	 */
	protected function __constuct( Config $config ) {
		$this->configByChannel = $config->get( 'EventRelayerConfig' );
	}

	/**
	 * @return EventRelayerGroup
	 */
	public static function singleton() {
		if ( !self::$instance ) {
			$config = RequestContext::getMain()->getConfig();
			self::$instance = new self( $config );
		}

		return self::$instance;
	}

	/**
	 * @param type $channel
	 * @return EventRelayer Relayer instance that handles the given channel
	 */
	public function getRelayer( $channel ) {
		$channelKey = isset( $this->configByChannel[$channel] )
			? $channel
			: 'default';

		if ( !isset( $this->relayers[$channelKey] ) ) {
			$config = $this->configByChannel[$channelKey];
			$class = $config['class'];

			$this->relayers[$channelKey] = new $class( $config );
		}

		return $this->relayers[$channelKey];
	}
}
