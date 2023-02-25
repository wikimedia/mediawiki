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

namespace Wikimedia\Stats\Formatters;

use Wikimedia\Stats\Metrics\CounterMetric;
use Wikimedia\Stats\Metrics\GaugeMetric;
use Wikimedia\Stats\Metrics\NullMetric;
use Wikimedia\Stats\Metrics\TimingMetric;

/**
 * Metrics Formatter Interface
 *
 * @author Cole White
 * @since 1.41
 */
interface FormatterInterface {
	/**
	 * Renders metric to line format.
	 *
	 * @param string $prefix
	 * @param CounterMetric|GaugeMetric|TimingMetric|NullMetric $metric
	 * @return string[]
	 */
	public function getFormattedSamples( string $prefix, $metric ): array;
}
