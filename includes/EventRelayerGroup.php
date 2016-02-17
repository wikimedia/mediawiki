<?php
/**
 * Factory class for spawning EventRelayer objects using configuration
 *
 * @author Aaron Schulz
 * @since 1.27
 */
class EventRelayerGroup {
	/** @var array[] */
	protected $configByChannel = [];

	/** @var EventRelayer[] */
	protected $relayers = [];

	/** @var EventRelayerGroup */
	protected static $instance = null;

	/**
	 * @param Config $config
	 */
	protected function __construct( Config $config ) {
		$this->configByChannel = $config->get( 'EventRelayerConfig' );
	}

	/**
	 * @return EventRelayerGroup
	 */
	public static function singleton() {
		if ( !self::$instance ) {
			self::$instance = new self( RequestContext::getMain()->getConfig() );
		}

		return self::$instance;
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
