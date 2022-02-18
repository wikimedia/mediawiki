<?php
/**
 * Sample Data Class
 *
 * A container for a metric sample to be passed to the rendering function.
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
 * @license GPL-2.0-or-later
 * @author Cole White
 * @since 1.38
 */

declare( strict_types=1 );

namespace Wikimedia\Metrics;

class Sample {

	/** @var string[] */
	private $labels;

	/** @var float */
	private $value;

	/** @param array $data associative array:
	 *  - labels: (string[]) Array of label values associated with the metric
	 *  - value: (numeric) The metric value
	 */
	public function __construct( array $data ) {
		$this->labels = $data[ 'labels' ];
		$this->value = $data[ 'value' ];
	}

	/** @return string[] */
	public function getLabels(): array {
		return $this->labels;
	}

	/** @return float */
	public function getValue(): float {
		return (float)$this->value;
	}
}
