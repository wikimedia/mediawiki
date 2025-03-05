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
	 * Note: We are not using /i (case-insensitive flag)
	 * or \d (digit character class escape) here because
	 * their behavior changes with respect to locale settings.
	 *
	 * @param string $entity
	 * @return string
	 */
	public static function normalizeString( string $entity ): string {
		$entity = preg_replace( '/[^a-zA-Z0-9]+/', '_', $entity );
		return trim( $entity, '_' );
	}
}
