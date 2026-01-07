<?php
/**
 * Copyright 2014
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Config;

use function array_key_exists;

/**
 * Accesses configuration settings from $GLOBALS
 *
 * @newable
 * @since 1.23
 */
class GlobalVarConfig implements Config {

	/**
	 * Prefix to use for configuration variables
	 * @var string
	 */
	private $prefix;

	/**
	 * Default builder function
	 * @return self
	 */
	public static function newInstance() {
		return new self();
	}

	/**
	 * @stable to call
	 *
	 * @param string $prefix
	 */
	public function __construct( $prefix = 'wg' ) {
		$this->prefix = $prefix;
	}

	/**
	 * @inheritDoc
	 */
	public function get( $name ) {
		$var = $this->prefix . $name;

		// Fast path combines check and retrieval.
		$value = $GLOBALS[$var] ?? null;
		if ( $value !== null ) {
			return $value;
		}

		// Slow path: the value is either explicitly null or missing.
		// We have to pay the price of array_key_exists() here to distinguish the two.
		if ( array_key_exists( $var, $GLOBALS ) ) {
			return null;
		}

		throw new ConfigException( __METHOD__ . ": undefined option: '$name'" );
	}

	/**
	 * @inheritDoc
	 */
	public function has( $name ) {
		$var = $this->prefix . $name;
		// (T317951) Don't call array_key_exists unless we have to, as it's slow
		// on PHP 8.1+ for $GLOBALS. When the key is set but is explicitly set
		// to null, we still need to fall back to array_key_exists, but that's
		// rarer.
		return isset( $GLOBALS[$var] ) || array_key_exists( $var, $GLOBALS );
	}
}

/** @deprecated class alias since 1.41 */
class_alias( GlobalVarConfig::class, 'GlobalVarConfig' );
