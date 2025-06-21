<?php
/**
 * Copyright 2015 Ori Livneh
 *
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

namespace Wikimedia\Stats;

use Liuggio\StatsdClient\Entity\StatsdData;
use Liuggio\StatsdClient\Entity\StatsdDataInterface;
use Liuggio\StatsdClient\Factory\StatsdDataFactory;

/**
 * MediaWiki's adaption of StatsdDataFactory that provides buffering and metric prefixing.
 *
 * The buffering functionality exists as a performance optimisation to reduce network
 * traffic and StatsD processing by maximally utilizing StatsdClient's ability to
 * compress counter increments, and send all data in a few large UDP packets
 * over a single connection.
 *
 * These buffers are sent from MediaWikiEntryPoint::emitBufferedStats. For web requests,
 * this happens post-send. For command-line scripts, this happens periodically from a database
 * callback (see MWLBFactory::applyGlobalState).
 *
 * @todo Evaluate upstream's StatsdService class, which implements similar buffering logic
 * and was released in statsd-php-client 1.0.13, shortly after we implemented this here
 * for statsd-php-client 1.0.12 at the time.
 *
 * @since 1.25
 * @method StatsdData produceStatsdDataEntity() We use StatsdData::setKey, which is not in
 *  StatsdDataInterface https://gerrit.wikimedia.org/r/643976
 */
class BufferingStatsdDataFactory extends StatsdDataFactory implements IBufferingStatsdDataFactory {
	/** @var array */
	protected $buffer = [];
	/** @var bool */
	protected $enabled = true;
	/** @var string */
	private $prefix;

	public function __construct( string $prefix ) {
		parent::__construct();
		$this->prefix = $prefix;
	}

	//
	// Methods for StatsdDataFactoryInterface
	//

	/**
	 * @param string $key
	 * @param float|int $time
	 * @return void
	 */
	public function timing( $key, $time ) {
		if ( !$this->enabled ) {
			return;
		}
		$this->buffer[] = [ $key, $time, StatsdDataInterface::STATSD_METRIC_TIMING ];
	}

	/**
	 * @param string $key
	 * @param float|int $value
	 * @return void
	 */
	public function gauge( $key, $value ) {
		if ( !$this->enabled ) {
			return;
		}
		$this->buffer[] = [ $key, $value, StatsdDataInterface::STATSD_METRIC_GAUGE ];
	}

	/**
	 * @param string $key
	 * @param float|int $value
	 * @return array
	 */
	public function set( $key, $value ) {
		if ( !$this->enabled ) {
			return [];
		}
		$this->buffer[] = [ $key, $value, StatsdDataInterface::STATSD_METRIC_SET ];
		return [];
	}

	/**
	 * @param string $key
	 * @return array
	 */
	public function increment( $key ) {
		if ( !$this->enabled ) {
			return [];
		}
		$this->buffer[] = [ $key, 1, StatsdDataInterface::STATSD_METRIC_COUNT ];
		return [];
	}

	/**
	 * @param string $key
	 * @return void
	 */
	public function decrement( $key ) {
		if ( !$this->enabled ) {
			return;
		}
		$this->buffer[] = [ $key, -1, StatsdDataInterface::STATSD_METRIC_COUNT ];
	}

	/**
	 * @param string $key
	 * @param int $delta
	 * @return void
	 */
	public function updateCount( $key, $delta ) {
		if ( !$this->enabled ) {
			return;
		}
		$this->buffer[] = [ $key, $delta, StatsdDataInterface::STATSD_METRIC_COUNT ];
	}

	/**
	 * Normalize a metric key for StatsD
	 *
	 * The following are changes you may rely on:
	 *
	 * - Non-alphanumerical characters are converted to underscores.
	 * - Empty segments are removed, e.g. "foo..bar" becomes "foo.bar".
	 *   This is mainly for StatsD-Graphite-Carbon setups where each segment is a directory
	 *   and must have a non-empty name.
	 * - Deliberately invalid input that looks like `__METHOD__` (namespaced PHP class and method)
	 *   is changed from "\\Namespace\\Class::method" to "Namespace_Class.method".
	 *   This is used by ProfilerOutputStats.
	 *
	 * @param string $key
	 * @return string
	 */
	private static function normalizeMetricKey( $key ) {
		$key = strtr( $key, [ '::' => '.' ] );
		$key = preg_replace( '/[^a-zA-Z0-9.]+/', '_', $key );
		$key = trim( $key, '_.' );
		return strtr( $key, [ '..' => '.' ] );
	}

	public function produceStatsdData(
		$key, $value = 1, $metric = StatsdDataInterface::STATSD_METRIC_COUNT
	) {
		$entity = $this->produceStatsdDataEntity();
		if ( $key !== null ) {
			$key = self::normalizeMetricKey( "{$this->prefix}.{$key}" );
			$entity->setKey( $key );
		}
		if ( $value !== null ) {
			$entity->setValue( $value );
		}
		if ( $metric !== null ) {
			$entity->setMetric( $metric );
		}
		return $entity;
	}

	//
	// Methods for IBufferingStatsdDataFactory
	//

	public function hasData() {
		return (bool)$this->buffer;
	}

	/**
	 * @since 1.30
	 * @return StatsdData[]
	 */
	public function getData() {
		$data = [];
		foreach ( $this->buffer as [ $key, $val, $metric ] ) {
			// Optimization: Don't bother transmitting a counter update with a delta of zero
			if ( $metric === StatsdDataInterface::STATSD_METRIC_COUNT && !$val ) {
				continue;
			}

			// Optimisation: Avoid produceStatsdData cost during web requests (T288702).
			// Instead, we do it here in getData() right before the data is transmitted.
			$data[] = $this->produceStatsdData( $key, $val, $metric );
		}

		return $data;
	}

	public function clearData() {
		$this->buffer = [];
	}

	public function getDataCount() {
		return count( $this->buffer );
	}

	public function setEnabled( $enabled ) {
		$this->enabled = $enabled;
	}
}

/** @deprecated class alias since 1.43 */
class_alias( BufferingStatsdDataFactory::class, 'BufferingStatsdDataFactory' );
