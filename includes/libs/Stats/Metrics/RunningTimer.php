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
 * @file
 */

namespace Wikimedia\Stats\Metrics;

use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * RunningTimer Implementation
 *
 * A class to help TimingMetrics handle instances of recursion.
 *
 * @author Cole White
 * @since 1.45
 */
class RunningTimer {

	/** @var TimingMetric|NullMetric */
	private $metric;
	private ?float $startTime;

	public function __construct( float $startTime, TimingMetric $metric ) {
		$this->startTime = $startTime;
		$this->metric = $metric;
	}

	/**
	 * Handle for MetricInterface::setLabel
	 *
	 * @see MetricInterface::setLabel
	 * @return self
	 */
	public function setLabel( string $key, string $value ) {
		$this->metric = $this->metric->setLabel( $key, $value );
		return $this;
	}

	/**
	 * Handle for MetricInterface::setLabels
	 *
	 * @see MetricInterface::setLabels
	 * @return self
	 */
	public function setLabels( array $labels ) {
		$this->metric = $this->metric->setLabels( $labels );
		return $this;
	}

	/**
	 * Stop the running timer.
	 */
	public function stop() {
		if ( $this->startTime === null ) {
			trigger_error(
				"Stats: ({$this->metric->getName()}) cannot call stop() more than once on a RunningTimer.",
				E_USER_WARNING
			);
			return;
		}
		$this->metric->observeNanoseconds( ConvertibleTimestamp::hrtime() - $this->startTime );
		$this->startTime = null;
	}
}
