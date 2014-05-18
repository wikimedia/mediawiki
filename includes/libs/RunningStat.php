<?php
/**
 * Compute running mean, variance, and extrema of a stream of numbers.
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
 * @ingroup Profiler
 */

// Needed due to PHP non-bug <https://bugs.php.net/bug.php?id=49828>.
define( 'NEGATIVE_INF', -INF );

/**
 * Represents a running summary of a stream of numbers.
 *
 * RunningStat instances are accumulator-like objects that provide a set of
 * continuously-updated summary statistics for a stream of numbers, without
 * requiring that each value be stored. The measures it provides are the
 * arithmetic mean, variance, standard deviation, and extrema (min and max);
 * together they describe the central tendency and statistical dispersion of a
 * set of values.
 *
 * One RunningStat instance can be merged into another; the resultant
 * RunningStat has the state it would have had if it had accumulated each
 * individual point. This allows data to be summarized in parallel and in
 * stages without loss of fidelity.
 *
 * Based on a C++ implementation by John D. Cook:
 *  <http://www.johndcook.com/standard_deviation.html>
 *  <http://www.johndcook.com/skewness_kurtosis.html>
 *
 * The in-line documentation for this class incorporates content from the
 * English Wikipedia articles "Variance", "Algorithms for calculating
 * variance", and "Standard deviation".
 *
 * @since 1.23
 */
class RunningStat implements Countable {

	/** @var int Number of samples. **/
	public $n = 0;

	/** @var float The first moment (or mean, or expected value). **/
	public $m1 = 0.0;

	/** @var float The second central moment (or variance). **/
	public $m2 = 0.0;

	/** @var float The least value in the the set. **/
	public $min = INF;

	/** @var float The most value in the set. **/
	public $max = NEGATIVE_INF;

	/**
	 * Count the number of accumulated values.
	 * @return int Number of values
	 */
	public function count() {
		return $this->n;
	}

	/**
	 * Add a number to the data set.
	 * @param int|float $x Value to add
	 */
	public function push( $x ) {
		$x = (float) $x;

		$this->min = min( $this->min, $x );
		$this->max = max( $this->max, $x );

		$n1 = $this->n;
		$this->n += 1;
		$delta = $x - $this->m1;
		$delta_n = $delta / $this->n;
		$this->m1 += $delta_n;
		$this->m2 += $delta * $delta_n * $n1;
	}

	/**
	 * Get the mean, or expected value.
	 *
	 * The arithmetic mean is the sum of all measurements divided by the number
	 * of observations in the data set.
	 *
	 * @return float Mean
	 */
	public function getMean() {
		return $this->m1;
	}

	/**
	 * Get the estimated variance.
	 *
	 * Variance measures how far a set of numbers is spread out. A small
	 * variance indicates that the data points tend to be very close to the
	 * mean (and hence to each other), while a high variance indicates that the
	 * data points are very spread out from the mean and from each other.
	 *
	 * @return float Estimated variance
	 */
	public function getVariance() {
		if ( $this->n === 0 ) {
			// The variance of the empty set is undefined.
			return NAN;
		} elseif ( $this->n === 1 ) {
			return 0.0;
		} else {
			return $this->m2 / ( $this->n - 1.0 );
		}
	}

	/**
	 * Get the estimated stanard deviation.
	 *
	 * The standard deviation of a statistical population is the square root of
	 * its variance. It shows shows how much variation from the mean exists. In
	 * addition to expressing the variability of a population, the standard
	 * deviation is commonly used to measure confidence in statistical conclusions.
	 *
	 * @return float Estimated standard deviation
	 */
	public function getStdDev() {
		return sqrt( $this->getVariance() );
	}

	/**
	 * Merge another RunningStat instance into this instance.
	 *
	 * This instance then has the state it would have had if all the data had
	 * been accumulated by it alone.
	 *
	 * @param RunningStat RunningStat instance to merge into this one
	 */
	public function merge( RunningStat $other ) {
		// If the other RunningStat is empty, there's nothing to do.
		if ( $other->n === 0 ) {
			return;
		}

		// If this RunningStat is empty, copy values from other RunningStat.
		if ( $this->n === 0 ) {
			$this->n = $other->n;
			$this->m1 = $other->m1;
			$this->m2 = $other->m2;
			$this->min = $other->min;
			$this->max = $other->max;
			return;
		}

		$n = $this->n + $other->n;
		$delta = $other->m1 - $this->m1;
		$delta2 = $delta * $delta;

		$this->m1 = ( ( $this->n * $this->m1 ) + ( $other->n * $other->m1 ) ) / $n;
		$this->m2 = $this->m2 + $other->m2 + ( $delta2 * $this->n * $other->n / $n );
		$this->min = min( $this->min, $other->min );
		$this->max = max( $this->max, $other->max );
		$this->n = $n;
	}
}
