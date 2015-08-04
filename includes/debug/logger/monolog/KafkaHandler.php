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

use AvroSchema;
use Kafka\Produce;
use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use RuntimeException;

/**
 * Log handler that supports sending log events to a kafka server.
 * This Handler should be used with the AvroFormatter.
 *
 * @since 1.26
 * @author Erik Bernhardson <ebernhardson@wikimedia.org>
 * @copyright © 2015 Erik Bernhardson and Wikimedia Foundation.
 */
class KafkaHandler extends AbstractProcessingHandler {
	// @var Produce Sends requests to kafka
	protected $produce;
	// @var array Handler config options
	protected $options;
	// @var array The topic partition to produce to
	protected $partitions = array();

	/**
	 * @param Produce $produce Kafka instance to produce through
	 * @param array $options
	 * @param string $level The minimum logging level at which this handler
	 *   will be triggered
	 * @param bool $bubble Whether the messages that are handled can bubble up
	 *   the stack or not
	 */
	public function __construct( Produce $produce, array $options, $level = Logger::DEBUG, $bubble = true ) {
		parent::__construct( $level, $bubble );
		$this->produce = $produce;
		$this->options = array_merge(
			array(
				'schemas' => array(), // Map from channel name to avro schema
				'ignore_error' => false, // Suppress Kafka exceptions
			),
			$options
		);
	}

	public static function factory( $kafkaServers, array $options ) {
		$metadata = new \Kafka\MetaDataFromKafka( (array)$kafkaServers );
		$produce = new \Kafka\Produce( $metadata );
		return new self( $produce, $options );
	}

	/**
	 * {@inheritDoc}
	 */
	protected function write( array $record ) {
		$this->bulkSend( $record['channel'], array( $record['formatted'] ) );
		$this->produce->send();
	}

	/**
	 * @return array
	 */
	public function getOptions() {
		return $this->options;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getDefaultFormatter() {
		return new AvroFormatter( $this->options['schemas'] );
	}

	/**
	 * {@inheritDoc}
	 */
	public function handleBatch( array $records ) {
		$byTopic = array();
		foreach ( $records as $record ) {
			if ( $record['level'] < $this->level ) {
				continue;
			}
			$byTopic[$record['channel']][] = $this->processRecord( $record );
		}

		if (!empty( $messages ) ) {
			foreach ( $byTopic as $channel => $messages ) {
				$formatted  = $this->getFormatter()->formatBatch( $messages );
				$this->bulkSend( $channel, $formatted );
			}
			$this->produce->send();
		}
	}

	/**
	 * @param string $topic Name of topic to get partition for
	 * @return int The random partition to produce to for this request.
	 */
	protected function getPartition( $topic ) {
		if ( !isset( $this->partitions[$topic] ) ) {
			$partitions = $this->produce->getAvailablePartitions( $topic );
			$key = array_rand( $partitions );
			$this->partitions[$topic] = $partitions[$key];
		}
		return $this->partitions[$topic];
	}

	/**
	 * @var string[] $records
	 * @throws RuntimeException
	 */
	protected function bulkSend( $topic, array $records, $send = true ) {
		try {
			$this->produce->setMessages(
				$topic,
				$this->getPartition( $topic ),
				$records
			);
		} catch ( \Kafka\Exception $e ) {
			if ( !$this->options['ignore_error'] ) {
				throw new RuntimeException( "Error sending messages to Kafka", 0, $e );
			}
		}
	}
}
