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
		$this->channel = $this->config->get( 'KafkaEventChannel' );
		if ( !$this->channel ) {
			throw new InvalidArgumentException( "KafkaEventChannel must be configured" );
		}
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
	 * Encode event for transmission.
	 * @param array $event
	 * @return string
	 */
	public function serializeEvent( array $event ) {
		if ( !empty( $event['sbt'] ) ) {
			unset($event['sbt']);
			$event['val'] = str_replace('$UNIXTIME$', strval(time()), $event['val']);
		}
		return json_encode($event);
	}

	/**
	 * (non-PHPdoc)
	 *
	 * @see EventRelayer::doNotify()
	 *
	 */
	protected function doNotify( $channel, array $events ) {
		$jsonEvents = array_map( [ $this, 'serializeEvent' ], $events );
		$producer = $this->getKafkaProducer();
		$producer->setMessages( $this->channel, 0, $jsonEvents );
		$producer->send();
	}
}
