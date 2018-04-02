<?php

/**
 * @license GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ComposerVersionNormalizer {

	/**
	 * Ensures there is a dash in between the version and the stability suffix.
	 *
	 * Examples:
	 * - 1.23RC => 1.23-RC
	 * - 1.23alpha => 1.23-alpha
	 * - 1.23alpha3 => 1.23-alpha3
	 * - 1.23-beta => 1.23-beta
	 *
	 * @param string $version
	 *
	 * @return string
	 * @throws InvalidArgumentException
	 */
	public function normalizeSuffix( $version ) {
		if ( !is_string( $version ) ) {
			throw new InvalidArgumentException( '$version must be a string' );
		}

		return preg_replace( '/^(\d[\d\.]*)([a-zA-Z]+)(\d*)$/', '$1-$2$3', $version, 1 );
	}

	/**
	 * Ensures the version has four levels.
	 * Version suffixes are supported, as long as they start with a dash.
	 *
	 * Examples:
	 * - 1.19 => 1.19.0.0
	 * - 1.19.2.3 => 1.19.2.3
	 * - 1.19-alpha => 1.19.0.0-alpha
	 * - 1337 => 1337.0.0.0
	 *
	 * @param string $version
	 *
	 * @return string
	 * @throws InvalidArgumentException
	 */
	public function normalizeLevelCount( $version ) {
		if ( !is_string( $version ) ) {
			throw new InvalidArgumentException( '$version must be a string' );
		}

		$dashPosition = strpos( $version, '-' );

		if ( $dashPosition !== false ) {
			$suffix = substr( $version, $dashPosition );
			$version = substr( $version, 0, $dashPosition );
		}

		$version = implode( '.', array_pad( explode( '.', $version ), 4, '0' ) );

		if ( $dashPosition !== false ) {
			$version .= $suffix;
		}

		return $version;
	}
}
