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

namespace MediaWiki\Logger\Monolog;

use Kafka\MetaDataFromKafka;
use Kafka\Produce;
use MediaWiki\Logger\LoggerFactory;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

/**
 * Log handler sends log events to a kafka server.
 *
 * Constructor options array arguments:
 * * alias: map from monolog channel to kafka topic name. When no
 *	  alias exists the topic "monolog_$channel" will be used.
 * * swallowExceptions: Swallow exceptions that occur while talking to
 *    kafka. Defaults to false.
 * * logExceptions: Log exceptions talking to kafka here. Either null,
 *    the name of a channel to log to, or an object implementing
 *    FormatterInterface. Defaults to null.
 *
 * Requires the nmred/kafka-php library, version >= 1.3.0
 *
 * @since 1.26
 * @author Erik Bernhardson <ebernhardson@wikimedia.org>
 * @copyright © 2015 Erik Bernhardson and Wikimedia Foundation.
 */
class KafkaHandler extends AbstractProcessingHandler {
	/**
	 * @var Produce Sends requests to kafka
	 */
	protected $produce;

	/**
	 * @var array Optional handler configuration
	 */
	protected $options;

	/**
	 * @var array Map from topic name to partition this request produces to
	 */
	protected $partitions = [];

	/**
	 * @var array defaults for constructor options
	 */
	private static $defaultOptions = [
		'alias' => [], // map from monolog channel to kafka topic
		'swallowExceptions' => false, // swallow exceptions sending records
		'logExceptions' => null, // A PSR3 logger to inform about errors
		'requireAck' => 0,
	];

	/**
	 * The kafka protocol documentation didn't list the possible errors, so these
	 * are extracted from apache/kafka@e6ca328 from the file:
	 *   clients/src/main/java/org/apache/kafka/common/protocol/Errors.java
	 * It seems likely this should be part of nmred/kafka-php rather than this
	 * file, but this will work for now.
	 *
	 * @var array
	 */
	// @codingStandardsIgnoreStart For long lines that can't be broken up
	private static $errors = [
	    -1 => [
	        'UNKNOWN',
	        'The server experienced an unexpected error when processing the request',
	    ],
	    0 => [
	        'NONE',
	        null
	    ],
	    1 => [
	        'OFFSET_OUT_OF_RANGE',
	        'The requested offset is not within the range of offsets maintained by the server.',
	    ],
	    2 => [
	        'CORRUPT_MESSAGE',
			'This message has failed its CRC checksum, exceeds the valid size, or is otherwise corrupt.',
	    ],
	    3 => [
	        'UNKNOWN_TOPIC_OR_PARTITION',
	        'This server does not host this topic-partition.',
	    ],
	    4 => [
	        'INVALID_FETCH_SIZE',
	        'The requested fetch size is invalid.',
	    ],
	    5 => [
	        'LEADER_NOT_AVAILABLE',
			'There is no leader for this topic-partition as we are in the middle of a leadership election.',
	    ],
	    6 => [
	        'NOT_LEADER_FOR_PARTITION',
	        'This server is not the leader for that topic-partition.',
	    ],
	    7 => [
	        'REQUEST_TIMED_OUT',
	        'The request timed out.',
	    ],
	    8 => [
	        'BROKER_NOT_AVAILABLE',
	        'The broker is not available.',
	    ],
	    9 => [
	        'REPLICA_NOT_AVAILABLE',
	        'The replica is not available for the requested topic-partition',
	    ],
	    10 => [
	        'MESSAGE_TOO_LARGE',
	        'The request included a message larger than the max message size the server will accept.',
	    ],
	    11 => [
	        'STALE_CONTROLLER_EPOCH',
	        'The controller moved to another broker.',
	    ],
	    12 => [
	        'OFFSET_METADATA_TOO_LARGE',
	        'The metadata field of the offset request was too large.',
	    ],
	    13 => [
	        'NETWORK_EXCEPTION',
	        'The server disconnected before a response was received.',
	    ],
	    14 => [
	        'GROUP_LOAD_IN_PROGRESS',
	        'The coordinator is loading and hence can\'t process requests for this group.',
	    ],
	    15 => [
	        'GROUP_COORDINATOR_NOT_AVAILABLE',
	        'The group coordinator is not available.',
	    ],
	    16 => [
	        'NOT_COORDINATOR_FOR_GROUP',
	        'This is not the correct coordinator for this group.',
	    ],
	    17 => [
	        'INVALID_TOPIC_EXCEPTION',
	        'The request attempted to perform an operation on an invalid topic.',
	    ],
	    18 => [
	        'RECORD_LIST_TOO_LARGE',
			'The request included message batch larger than the configured segment size on the server.',
	    ],
	    19 => [
	        'NOT_ENOUGH_REPLICAS',
	        'Messages are rejected since there are fewer in-sync replicas than required.',
	    ],
	    20 => [
	        'NOT_ENOUGH_REPLICAS_AFTER_APPEND',
	        'Messages are written to the log, but to fewer in-sync replicas than required.',
	    ],
	    21 => [
	        'INVALID_REQUIRED_ACKS',
	        'Produce request specified an invalid value for required acks.',
	    ],
	    22 => [
	        'ILLEGAL_GENERATION',
	        'Specified group generation id is not valid.',
	    ],
	    23 => [
	        'INCONSISTENT_GROUP_PROTOCOL',
	        'The group member\'s supported protocols are incompatible with those of existing members.',
	    ],
	    24 => [
	        'INVALID_GROUP_ID',
	        'The configured groupId is invalid',
	    ],
	    25 => [
	        'UNKNOWN_MEMBER_ID',
	        'The coordinator is not aware of this member.',
	    ],
	    26 => [
	        'INVALID_SESSION_TIMEOUT',
			'The session timeout is not within the range allowed by the broker (as configured by group.min.session.timeout.ms and group.max.session.timeout.ms).',
	    ],
	    27 => [
	        'REBALANCE_IN_PROGRESS',
	        'The group is rebalancing, so a rejoin is needed.',
	    ],
	    28 => [
	        'INVALID_COMMIT_OFFSET_SIZE',
	        'The committing offset data size is not valid',
	    ],
	    29 => [
	        'TOPIC_AUTHORIZATION_FAILED',
	        'Topic authorization failed.',
	    ],
	    30 => [
	        'GROUP_AUTHORIZATION_FAILED',
	        'Group authorization failed.',
	    ],
	    31 => [
	        'CLUSTER_AUTHORIZATION_FAILED',
	        'Cluster authorization failed.',
	    ],
	    32 => [
	        'INVALID_TIMESTAMP',
	        'The timestamp of the message is out of acceptable range.',
	    ],
	    33 => [
	        'UNSUPPORTED_SASL_MECHANISM',
	        'The broker does not support the requested SASL mechanism.',
	    ],
	    34 => [
	        'ILLEGAL_SASL_STATE',
	        'Request is not valid given the current SASL state.',
	    ],
	    35 => [
	        'UNSUPPORTED_VERSION',
	        'The version of API is not supported.',
	    ],
	];
	// @codingStandardsIgnoreEnd

	/**
	 * @param Produce $produce Kafka instance to produce through
	 * @param array $options optional handler configuration
	 * @param int $level The minimum logging level at which this handler will be triggered
	 * @param bool $bubble Whether the messages that are handled can bubble up the stack or not
	 */
	public function __construct(
		Produce $produce, array $options, $level = Logger::DEBUG, $bubble = true
	) {
		parent::__construct( $level, $bubble );
		$this->produce = $produce;
		$this->options = array_merge( self::$defaultOptions, $options );
	}

	/**
	 * Constructs the necessary support objects and returns a KafkaHandler
	 * instance.
	 *
	 * @param string[] $kafkaServers
	 * @param array $options
	 * @param int $level The minimum logging level at which this handle will be triggered
	 * @param bool $bubble Whether the messages that are handled can bubble the stack or not
	 * @return KafkaHandler
	 */
	public static function factory(
		$kafkaServers, array $options = [], $level = Logger::DEBUG, $bubble = true
	) {
		$metadata = new MetaDataFromKafka( $kafkaServers );
		$produce = new Produce( $metadata );

		if ( isset( $options['sendTimeout'] ) ) {
			$timeOut = $options['sendTimeout'];
			$produce->getClient()->setStreamOption( 'SendTimeoutSec', 0 );
			$produce->getClient()->setStreamOption( 'SendTimeoutUSec',
				intval( $timeOut * 1000000 )
			);
		}
		if ( isset( $options['recvTimeout'] ) ) {
			$timeOut = $options['recvTimeout'];
			$produce->getClient()->setStreamOption( 'RecvTimeoutSec', 0 );
			$produce->getClient()->setStreamOption( 'RecvTimeoutUSec',
				intval( $timeOut * 1000000 )
			);
		}
		if ( isset( $options['logExceptions'] ) && is_string( $options['logExceptions'] ) ) {
			$options['logExceptions'] = LoggerFactory::getInstance( $options['logExceptions'] );
		}

		if ( isset( $options['requireAck'] ) ) {
			$produce->setRequireAck( $options['requireAck'] );
		}

		return new self( $produce, $options, $level, $bubble );
	}

	/**
	 * {@inheritDoc}
	 */
	protected function write( array $record ) {
		if ( $record['formatted'] !== null ) {
			$this->addMessages( $record['channel'], [ $record['formatted'] ] );
			$this->send();
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function handleBatch( array $batch ) {
		$channels = [];
		foreach ( $batch as $record ) {
			if ( $record['level'] < $this->level ) {
				continue;
			}
			$channels[$record['channel']][] = $this->processRecord( $record );
		}

		$formatter = $this->getFormatter();
		foreach ( $channels as $channel => $records ) {
			$messages = [];
			foreach ( $records as $idx => $record ) {
				$message = $formatter->format( $record );
				if ( $message !== null ) {
					$messages[] = $message;
				}
			}
			if ( $messages ) {
				$this->addMessages( $channel, $messages );
			}
		}

		$this->send();
	}

	/**
	 * Send any records in the kafka client internal queue.
	 */
	protected function send() {
		try {
			$response = $this->produce->send();
		} catch ( \Kafka\Exception $e ) {
			$ignore = $this->warning(
				'Error sending records to kafka: {exception}',
				[ 'exception' => $e ] );
			if ( !$ignore ) {
				throw $e;
			} else {
				return;
			}
		}

		if ( is_bool( $response ) ) {
			return;
		}

		$errors = [];
		foreach ( $response as $topicName => $partitionResponse ) {
			foreach ( $partitionResponse as $partition => $info ) {
				if ( $info['errCode'] === 0 ) {
					// no error
					continue;
				}
				if ( isset( self::$errors[$info['errCode']] ) ) {
					$error = self::$errors[$info['errCode']];
				} else {
					$error = self::$errors[-1];
				}

				$errors[] = sprintf(
					'Error producing to %s (errno %d): %s',
					$topicName,
					$info['errCode'],
					$error[1]
				);
			}
		}

		if ( $errors ) {
			$error = implode( "\n", $errors );
			if ( $this->warning( $error ) ) {
				throw new \RuntimeException( $error );
			}
		}
	}

	/**
	 * @param string $topic Name of topic to get partition for
	 * @return int|null The random partition to produce to for this request,
	 *  or null if a partition could not be determined.
	 */
	protected function getRandomPartition( $topic ) {
		if ( !array_key_exists( $topic, $this->partitions ) ) {
			try {
				$partitions = $this->produce->getAvailablePartitions( $topic );
			} catch ( \Kafka\Exception $e ) {
				$ignore = $this->warning(
					'Error getting metadata for kafka topic {topic}: {exception}',
					[ 'topic' => $topic, 'exception' => $e ] );
				if ( $ignore ) {
					return null;
				}
				throw $e;
			}
			if ( $partitions ) {
				$key = array_rand( $partitions );
				$this->partitions[$topic] = $partitions[$key];
			} else {
				$details = $this->produce->getClient()->getTopicDetail( $topic );
				$ignore = $this->warning(
					'No partitions available for kafka topic {topic}',
					[ 'topic' => $topic, 'kafka' => $details ]
				);
				if ( !$ignore ) {
					throw new \RuntimeException( "No partitions available for kafka topic $topic" );
				}
				$this->partitions[$topic] = null;
			}
		}
		return $this->partitions[$topic];
	}

	/**
	 * Adds records for a channel to the Kafka client internal queue.
	 *
	 * @param string $channel Name of Monolog channel records belong to
	 * @param array $records List of records to append
	 */
	protected function addMessages( $channel, array $records ) {
		if ( isset( $this->options['alias'][$channel] ) ) {
			$topic = $this->options['alias'][$channel];
		} else {
			$topic = "monolog_$channel";
		}
		$partition = $this->getRandomPartition( $topic );
		if ( $partition !== null ) {
			$this->produce->setMessages( $topic, $partition, $records );
		}
	}

	/**
	 * @param string $message PSR3 compatible message string
	 * @param array $context PSR3 compatible log context
	 * @return bool true if caller should ignore warning
	 */
	protected function warning( $message, array $context = [] ) {
		if ( $this->options['logExceptions'] instanceof LoggerInterface ) {
			$this->options['logExceptions']->warning( $message, $context );
		}
		return $this->options['swallowExceptions'];
	}
}
