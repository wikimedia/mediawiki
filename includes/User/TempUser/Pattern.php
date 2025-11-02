<?php

namespace MediaWiki\User\TempUser;

use Stringable;
use UnexpectedValueException;
use Wikimedia\Rdbms\LikeValue;
use Wikimedia\Rdbms\Platform\ISQLPlatform;

/**
 * Helper for TempUserConfig representing string patterns with "$1" indicating
 * variable substitution.
 *
 * @internal
 */
class Pattern implements Stringable {
	/** @var string */
	private $debugName;
	/** @var string */
	private $pattern;
	/** @var string */
	private $prefix;
	/** @var string */
	private $suffix;

	/**
	 * @param string $debugName The name of the pattern, for use in error messages
	 * @param string $pattern The pattern itself
	 */
	public function __construct( string $debugName, string $pattern ) {
		$this->debugName = $debugName;
		$this->pattern = $pattern;
	}

	/**
	 * Does the pattern match the given name?
	 * @param string $name
	 * @return bool
	 */
	public function isMatch( string $name ) {
		$this->init();
		$match = true;
		if ( $this->prefix !== '' ) {
			$match = str_starts_with( $name, $this->prefix );
		}
		if ( $match && $this->suffix !== '' ) {
			$match = str_ends_with( $name, $this->suffix )
				&& strlen( $name ) >= strlen( $this->prefix ) + strlen( $this->suffix );
		}
		return $match;
	}

	/**
	 * Substitute the serial number into the pattern.
	 *
	 * @param string $mappedSerial
	 * @param ?string $year
	 * @return string
	 */
	public function generate( $mappedSerial, ?string $year = null ) {
		$this->init();
		return $this->prefix .
			( $year ? $year . '-' : '' ) .
			$mappedSerial .
			$this->suffix;
	}

	/**
	 * Convert the pattern to an SQL like clause
	 *
	 * @deprecated since 1.42. Use toLikeValue() instead
	 * @param ISQLPlatform $db
	 * @return string
	 */
	public function buildLike( ISQLPlatform $db ) {
		wfDeprecated( __METHOD__, '1.42' );
		$this->init();
		return $db->buildLike(
			$this->prefix,
			$db->anyString(),
			$this->suffix
		);
	}

	/**
	 * Convert the pattern to an SQL builder "LIKE" value that matches it
	 *
	 * @param ISQLPlatform $db
	 * @return LikeValue
	 */
	public function toLikeValue( ISQLPlatform $db ): LikeValue {
		$this->init();
		return new LikeValue(
			$this->prefix,
			$db->anyString(),
			$this->suffix
		);
	}

	/**
	 * Extract the variable part of the string (matching $1 or YYYY-$1),
	 * or null if there is no match
	 *
	 * @param string $name
	 * @return ?string
	 */
	public function extract( string $name ) {
		if ( $this->isMatch( $name ) ) {
			return substr( $name,
				strlen( $this->prefix ),
				strlen( $name ) - strlen( $this->prefix ) - strlen( $this->suffix ) );
		}
		return null;
	}

	/**
	 * Initialise the prefix and suffix
	 */
	private function init() {
		if ( $this->prefix === null ) {
			$varPos = strpos( $this->pattern, '$1' );
			if ( $varPos === false ) {
				throw new UnexpectedValueException( __CLASS__ .
					"pattern {$this->debugName} must be of the form \"prefix \$1 suffix\"" );
			}
			$this->prefix = substr( $this->pattern, 0, $varPos );
			$this->suffix = substr( $this->pattern, $varPos + strlen( '$1' ) );
		}
	}

	public function __toString() {
		return $this->pattern;
	}
}
