<?php
/**
 * A class for computing running mean, variance and extrema (min/max) of a
 * stream of numbers. Variance is computed using an online algorithm, so
 * storing individual points is not required.

 * Based on a C++ implementation by John D. Cook:
 * <http://www.johndcook.com/standard_deviation.html>
 * <http://www.johndcook.com/skewness_kurtosis.html>
 *
 * The in-line documentation for this class incorporates content from the
 * English Wikipedia articles "Variance", "Algorithms for calculating
 * variance", and "Standard deviation".
 */
class RunningStat {

	/** @var int: Number of samples. **/
	var $n = 0;

	/** @var double: The first moment (or mean, or expected value). **/
	var $m1 = 0.0;

	/** @var double: The second central moment (or variance). **/
	var $m2 = 0.0;

	/** @var double|null: The least value in the the set. **/
	var $min = null;

	/** @var double|null: The most value in the set. **/
	var $max = null;

	/**
	 * Add a number to the data set.
	 * @param int|double $x Value to add.
	 */
	function push( $x ) {
		$x = (double) $x;
		if ( $this->n === 0 ) {
			$this->min = $x;
			$this->max = $x;
		} else {
			$this->min = min( $this->min, $x );
			$this->max = max( $this->max, $x );
		}

		$n1 = $this->n;
		$this->n += 1;
		$delta = $x - $this->m1;
		$delta_n = $delta / $this->n;
		$this->m1 += $delta_n;
        $this->m2 += $delta * $delta_n * $n1;
	}

	/**
	 * Specify data which should be serialized to JSON
	 * @return array JSON-serializable associative array.
	 */
	function jsonSerialize() {
		return array(
			'n'   => $this->n,
			'm1'  => $this->m1,
			'm2'  => $this->m2,
			'min' => $this->min,
			'max' => $this->max,
		);
	}

	/**
	 * Get the mean, or expected value.
	 *
	 * The arithmetic mean is the sum of all measurements divided by the number
	 * of observations in the data set.
	 *
	 * @return double Estimated mean
	 */
    function getMean() {
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
	 * @return double Estimated variance
	 */
	function getVariance() {
		if ( $this->n === 0 ) {
			// The variance of the empty set is undefined.
			return null;
		} elseif ( $this->n === 1 ) {
			return 0;
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
	 * @return double Estimated standard deviation
	 */
	function getStdDev() {
		return sqrt( $this->getVariance() );
	}

	/**
	 * Merge another RunningStat instance into this instance.
	 *
	 * This instance then has the state it would have had if all the data had
	 * been accumulated by it alone.
	 *
	 * @param RunningStat RunningStat instance to merge into this one.
	 */
	function merge( $other ) {
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

		$this->m1 = ( $this->n * $this->m1 + $other->n * $other->m1 ) / $n;
		$this->m2 = $this->m2 + $other->m2 + $delta2 * $this->n * $other->n / $n;
		$this->min = min( $this->min, $other->min );
		$this->max = max( $this->max, $other->max );
		$this->n = $n;
	}
}
