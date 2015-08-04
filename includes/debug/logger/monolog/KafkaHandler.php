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
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

/**
 * Log handler sends log events to a kafka server.
 *
 * Constructor options array arguments:
 * * alias: map from monolog channel to kafka topic name. if no
 *	  alias exists the topic "monolog_$channel" will be used
 *
 * Requires the nmred/kafka-php library, version >= 1.3.0
 *
 * @since 1.26
 * @author Erik Bernhardson <ebernhardson@wikimedia.org>
 * @copyright © 2015 Erik Bernhardson and Wikimedia Foundation.
 */
class KafkaHandler extends AbstractProcessingHandler {
	// @var Produce Sends requests to kafka
	protected $produce;

	// @var array Optional handler configuration
	protected $options;

	// @var array Map from topic name to partition this request
	//  produces to
	protected $partitions = array();

	/**
	 * @param Produce $produce Kafka instance to produce through
	 * @param array $options optional handler configuration
	 * @param string $level The minimum logging level at which this handler
	 *   will be triggered
	 * @param bool $bubble Whether the messages that are handled can bubble up
	 *   the stack or not
	 */
	public function __construct( Produce $produce, array $options, $level = Logger::DEBUG, $bubble = true ) {
		parent::__construct( $level, $bubble );
		$this->produce = $produce;
		$this->options = array_merge( $options, array(
			'alias' => array(), // map from monolog channel to kafka topic
			'formatBatch' => true, // use when formatBatch does not return an array of records
		) );
	}

	/**
	 * Constructs the necessary support objects and returns a KafkaHandler
	 * instance.
	 *
	 * @param string[] $kafkaServers
	 * @param array $options
	 * @return KafkaHandler
	 */
	public static function factory( $kafkaServers, array $options = array(), $level = Logger::DEBUG, $bubble = true ) {
		$metadata = new MetaDataFromKafka( (array)$kafkaServers );
		$produce = new Produce( $metadata );
		return new self( $produce, $options, $level, $bubble );
	}

	/**
	 * {@inheritDoc}
	 */
	protected function write( array $record ) {
		$this->addMessages( $record['channel'], array( $record['formatted'] ) );
		$this->produce->send();
	}

	/**
	* {@inheritDoc}
	*/
	public function handleBatch( array $records ) {
		$topics = array();
		foreach ( $records as $record ) {
			if ( $record['level'] < $this->level ) {
				continue;
			}
			$topics[$record['channel']][] = $this->processRecord( $record );
		}

		if (!empty( $topics ) ) {
			$formatter = $this->getFormatter();
			foreach ( $topics as $channel => $messages ) {
				if ( $this->options['formatBatch'] ) {
					$formatted = $formatter->formatBatch( $messages );
				} else {
					$formatted = array_map( array( $formatter, 'format' ), $messages );
				}
				$this->addMessages( $channel, $formatted );
			}
			$this->produce->send();
		}
	}

	/**
	 * @param string $topic Name of topic to get partition for
	 * @return int The random partition to produce to for this request.
	 */
	protected function getRandomPartition( $topic ) {
		if ( !isset( $this->partitions[$topic] ) ) {
			$partitions = $this->produce->getAvailablePartitions( $topic );
			$key = array_rand( $partitions );
			$this->partitions[$topic] = $partitions[$key];
		}
		return $this->partitions[$topic];
	}

	/**
	 * Adds records for a channel to $this->produce internal queue.
	 *
	 * @var string $topic Name of channel records are from
	 * @var array $records List of records to append
	 */
	protected function addMessages( $channel, array $records ) {
		if ( isset( $this->alias[$channel] ) ) {
			$topic = $this->alias[$channel];
		} else {
			$topic = "monolog_$channel";
		}
		$this->produce->setMessages(
			$topic,
			$this->getRandomPartition( $topic ),
			$records
		);
	}
}
