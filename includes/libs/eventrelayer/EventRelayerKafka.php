<?php
use Kafka\Produce;

/**
 * Event relayer for Apache Kafka.
 * Configuring for WANCache:
 * 'relayerConfig' => [ 'class' => 'EventRelayerKafka',
 * 			'KafkaEventChannel' => 'wancache-purge', 'KafkaEventHost' => 'localhost:9092' ],
 */
class EventRelayerKafka extends EventRelayer {

	/**
	 * Configuration.
	 *
	 * @var Config
	 */
	protected $config;

	/**
	 * Kafka producer.
	 *
	 * @var Produce
	 */
	protected $producer;

	/**
	 * Create Kafka producer.
	 *
	 * @param Config $config
	 */
	public function __construct( array $params ) {
		$this->config = new HashConfig( $params );
		if ( !$this->config->has( 'KafkaEventHost' ) ) {
			throw new InvalidArgumentException( "KafkaEventHost must be configured" );
		}
	}

	/**
	 * Get the producer object from kafka-php.
	 * @return Produce
	 */
	protected function getKafkaProducer() {
		if ( !$this->producer ) {
			$this->producer = Produce::getInstance( null, null, $this->config->get( 'KafkaEventHost' ) );
		}
		return $this->producer;
	}

	/**
	 * (non-PHPdoc)
	 *
	 * @see EventRelayer::doNotify()
	 *
	 */
	protected function doNotify( $channel, array $events ) {
		$jsonEvents = array_map( 'json_encode', $events );
		try {
			$producer = $this->getKafkaProducer();
			$producer->setMessages( $channel, 0, $jsonEvents );
			$producer->send();
		} catch(\Kafka\Exception $e) {
			$this->logWarning( "Sending events failed: $e" );
			return false;
		}
		return true;
	}
}
