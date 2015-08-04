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
use Kafka\MetaDataFromKafka;
use Kafka\Produce;
use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Handler\BufferHandler;
use Monolog\Logger;
use RuntimeException;

/**
 * Log handler sends log events to a kafka server.
 *
 * Constructor options array arguments:
 * * alias: map from monolog channel to kafka topic name. if no
 *	  alias exists the topic "monolog_$channel" will be used
 * * ignore_error: When set to true kafka errors are swallowed.
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
		$this->options = array_merge( $options, array(
			'alias' => array(), // map from monolog channel to kafka topic
		) );
	}

	/**
	 * Constructs the necessary support objects and returns a KafkaHandler
	 * instance.
	 *
	 * @param string[] $kafkaServers
	 * @param array $options
	 * @return HandlerInterface
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
				$formatted = $formatter->formatBatch( $messages );
				$this->addMessages( $channel, $formatted );
			}
			$this->produce->send();
		}
	}

	/**
	 * @var string $channel
	 * @return string
	 */
	protected function resolveAlias( $channel ) {
		if ( isset( $this->alias[$channel] ) ) {
			return $this->alias[$channel];
		}
		return "monolog_$channel";
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
	 * @var string $topic
	 * @var array $records
	 */
	protected function addMessages( $channel, array $records ) {
		$topic = $this->resolveAlias( $channel );
		$this->produce->setMessages(
			$topic,
			$this->getPartition( $topic ),
			$records
		);
	}
}
