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

use Liuggio\StatsdClient\Factory\StatsdDataFactory;

/**
 * A factory for application metric data.
 *
 * This class prepends a context-specific prefix to each metric key and keeps
 * a reference to each constructed metric in an internal array buffer.
 *
 * @since 1.25
 */
class BufferingStatsdDataFactory extends StatsdDataFactory {
	protected $buffer = array();

	public function __construct( $prefix ) {
		parent::__construct();
		$this->prefix = $prefix;
	}

	public function produceStatsdData( $key, $value = 1, $metric = self::STATSD_METRIC_COUNT ) {
		$this->buffer[] = $entity = $this->produceStatsdDataEntity();
		if ( $key !== null ) {
			$prefixedKey = ltrim( $this->prefix . '.' . $key, '.' );
			$entity->setKey( $prefixedKey );
		}
		if ( $value !== null ) {
			$entity->setValue( $value );
		}
		if ( $metric !== null ) {
			$entity->setMetric( $metric );
		}
		return $entity;
	}

	public function getBuffer() {
		return $this->buffer;
	}
}
