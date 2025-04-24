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

declare( strict_types=1 );

namespace Wikimedia\Stats;

use InvalidArgumentException;
use Wikimedia\Stats\Exceptions\InvalidConfigurationException;

/**
 * Functionality common to all metric types.
 *
 * @author Cole White
 * @since 1.38
 */
class StatsUtils {

	public const RE_VALID_NAME_AND_LABEL_NAME = "/^[a-zA-Z_][a-zA-Z0-9_]*$/";
	public const DEFAULT_SAMPLE_RATE = 1.0;

	/**
	 * Validates the new sample rate.  Throws InvalidArgumentException if provided an invalid rate.
	 *
	 * @param float $newSampleRate
	 * @throws InvalidArgumentException
	 */
	public static function validateNewSampleRate( float $newSampleRate ): void {
		if ( $newSampleRate < 0.0 || $newSampleRate > 1.0 ) {
			throw new InvalidArgumentException( "Sample rate can only be between 0.0 and 1.0. Got: " . $newSampleRate );
		}
	}

	/**
	 * Returns a subset of samples based on configured sample rate.
	 *
	 * @param float $sampleRate
	 * @param array $samples
	 * @return array
	 */
	public static function getFilteredSamples( float $sampleRate, array $samples ): array {
		if ( $sampleRate === 1.0 ) {
			return $samples;
		}
		$output = [];
		$randMax = mt_getrandmax();
		foreach ( $samples as $sample ) {
			if ( mt_rand() / $randMax < $sampleRate ) {
				$output[] = $sample;
			}
		}
		return $output;
	}

	/**
	 * Determines if provided string is a valid name.
	 *
	 * @param string $name
	 * @return void
	 * @throws InvalidArgumentException
	 * @throws InvalidConfigurationException
	 */
	public static function validateMetricName( string $name ) {
		if ( $name === "" ) {
			throw new InvalidArgumentException( "Stats: Metric name cannot be empty." );
		}
		if ( !preg_match( self::RE_VALID_NAME_AND_LABEL_NAME, $name ) ) {
			throw new InvalidConfigurationException( "Invalid metric name: '" . $name . "'" );
		}
	}

	/**
	 * Determines if provided string is a valid label key.
	 *
	 * @param string $key
	 * @return void
	 * @throws InvalidArgumentException
	 * @throws InvalidConfigurationException
	 */
	public static function validateLabelKey( string $key ) {
		if ( $key === "" ) {
			throw new InvalidArgumentException( "Stats: Label key cannot be empty." );
		}
		if ( !preg_match( self::RE_VALID_NAME_AND_LABEL_NAME, $key ) ) {
			throw new InvalidConfigurationException( "Invalid label key: '" . $key . "'" );
		}
	}

	public static function validateLabelValue( string $value ) {
		if ( $value === "" ) {
			throw new InvalidArgumentException( "Stats: Label value cannot be empty." );
		}
	}

	/**
	 * Normalize an array of strings.
	 *
	 * @param string[] $entities
	 * @return string[]
	 */
	public static function normalizeArray( array $entities ): array {
		$normalizedEntities = [];
		foreach ( $entities as $entity ) {
			$normalizedEntities[] = self::normalizeString( $entity );
		}
		return $normalizedEntities;
	}

	/**
	 * Normalize strings to a metrics-compatible format.
	 *
	 * Replace all other non-alphanumeric characters with an underscore.
	 * Trim leading or trailing underscores.
	 *
	 * @param string $entity
	 * @return string
	 */
	public static function normalizeString( string $entity ): string {
		$entity = preg_replace( '/[^a-z\d]+/i', '_', $entity );
		return trim( $entity, "_" );
	}

	/**
	 * The E12 series
	 * @see https://en.wikipedia.org/wiki/E_series_of_preferred_numbers
	 */
	private const E12 = [
		1.0, 1.2, 1.5, 1.8, 2.2, 2.7, 3.3, 3.9, 4.7, 5.6, 6.8, 8.2, 10.0
	];

	/**
	 * Make a set of HistogramMetric buckets from a mean and skip value.
	 *
	 * Beware: this is for storing non-time data in histograms, like byte
	 * sizes, or time data outside of the range [5ms, 60s].
	 *
	 * Avoid changing the buckets once a metric has been deployed,
	 * as it may generate excessive churn.
	 *
	 * That said, this method quantizes the mean so modest shifts should
	 * maintain most buckets, and multiplying or dividing the "skip"
	 * by a small factor should also maintain commonality.
	 *
	 * The range of buckets for typical skips is roughly:
	 *
	 *     $skip = 1: [0.5*mean, 2*mean]
	 *     $skip = 2: [0.2*mean, 5*mean]
	 *     $skip = 3: [0.1*mean, 10*mean]
	 *     $skip = 4: [0.05*mean, 20*mean]
	 *     $skip = 5: [0.02*mean, 50*mean]
	 *     $skip = 6: [0.01*mean, 100*mean]
	 *     ...
	 *     $skip = 12: [0.001*mean, 10000*mean]
	 *
	 * @param float $mean The mean value expected.
	 * @param int $skip The range of values expected.  With $skip = 1,
	 *  each bucket will be greater than the last by a factor of 10^(1/12),
	 *  which means 12 buckets per decade of range.  This is the E12 series.
	 *  With $skip = 2 we take every other bucket (6 buckets per decade),
	 *  $skip = 3 means every third bucket (4 buckets per decade),
	 *  $skip = 4 means every fourth bucket (3 buckets per decade), etc.
	 *  The ::getTiming() metric effectively uses $skip = 4, which
	 *  corresponds roughly to the usual `[0.1, 0.2, 0.5, 1]`
	 *  progression, and that is the default value.  As mentioned
	 *  above, take great care when changing $skip on metrics already
	 *  in production.
	 * @return float[] An array of 9 buckets, centered around the mean
	 */
	public static function makeBucketsFromMean( float $mean, int $skip ): array {
		// assert $mean > 0 and $skip > 0
		if ( $mean <= 0 ) {
			throw new InvalidArgumentException( 'mean must be positive' );
		}
		if ( $skip < 1 ) {
			throw new InvalidArgumentException( 'skip must be at least 1' );
		}
		// Find the appropriate starting location in the E12 series.
		$pos = (int)round( log10( $mean ) * 12 );
		// Further quantize $pos according to $skip, so changes in $mean
		// don't shift all the buckets
		$pos -= ( $pos % $skip );
		// Compute buckets around the quantized starting position
		// By using the E12 series and powers of ten our cutoffs will
		// be compact (not too many digits) and consistent.
		return array_map( static function ( $x ) use ( $pos, $skip ) {
			$y = $pos + ( $x * $skip );
			$rem = $y % 12;
			if ( $rem < 0 ) {
				$rem += 12;
			}
			$decade = intdiv( $y - $rem, 12 ); // floor($y/12)
			// Use an explicit round() here to ensure float math doesn't create
			// extra tiny variances.
			return round( ( 10 ** $decade ) * self::E12[$rem], 1 - $decade );
		}, [
			// 9 buckets, centered around the (quantized) mean
			-4, -3, -2, -1, 0, 1, 2, 3, 4
		] );
	}
}
