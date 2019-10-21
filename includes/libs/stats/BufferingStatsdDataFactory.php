<?php
/**
 * Copyright 2015
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

use Liuggio\StatsdClient\Entity\StatsdData;
use Liuggio\StatsdClient\Entity\StatsdDataInterface;
use Liuggio\StatsdClient\Factory\StatsdDataFactory;

/**
 * A factory for application metric data.
 *
 * This class prepends a context-specific prefix to each metric key and keeps
 * a reference to each constructed metric in an internal array buffer.
 *
 * @since 1.25
 */
class BufferingStatsdDataFactory extends StatsdDataFactory implements IBufferingStatsdDataFactory {
	protected $buffer = [];
	/**
	 * Collection enabled?
	 * @var bool
	 */
	protected $enabled = true;
	/**
	 * @var string
	 */
	private $prefix;

	public function __construct( $prefix ) {
		parent::__construct();
		$this->prefix = $prefix;
	}

	/**
	 * Normalize a metric key for StatsD
	 *
	 * Replace occurences of '::' with dots and any other non-alphanumeric
	 * characters with underscores. Combine runs of dots or underscores.
	 * Then trim leading or trailing dots or underscores.
	 *
	 * @param string $key
	 * @since 1.26
	 * @return string
	 */
	private static function normalizeMetricKey( $key ) {
		$key = preg_replace( '/[:.]+/', '.', $key );
		$key = preg_replace( '/[^a-z0-9.]+/i', '_', $key );
		$key = trim( $key, '_.' );
		return str_replace( [ '._', '_.' ], '.', $key );
	}

	public function produceStatsdData(
		$key, $value = 1, $metric = StatsdDataInterface::STATSD_METRIC_COUNT
	) {
		$entity = $this->produceStatsdDataEntity();
		if ( !$this->enabled ) {
			return $entity;
		}
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
		// Don't bother buffering a counter update with a delta of zero.
		if ( !( $metric === StatsdDataInterface::STATSD_METRIC_COUNT && !$value ) ) {
			$this->buffer[] = $entity;
		}
		return $entity;
	}

	public function hasData() {
		return !empty( $this->buffer );
	}

	/**
	 * @since 1.30
	 * @return StatsdData[]
	 */
	public function getData() {
		return $this->buffer;
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
