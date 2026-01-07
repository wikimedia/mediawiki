<?php
/**
 * Copyright 2014
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Config;

use ArrayIterator;
use Traversable;
use function array_key_exists;

/**
 * A Config instance which stores all settings as a member variable
 *
 * @since 1.24
 */
class HashConfig implements Config, MutableConfig, IterableConfig {

	/**
	 * Array of config settings
	 *
	 * @var array
	 */
	private $settings;

	/**
	 * @return HashConfig
	 */
	public static function newInstance() {
		return new HashConfig;
	}

	/**
	 * @param array $settings Any current settings to pre-load
	 */
	public function __construct( array $settings = [] ) {
		$this->settings = $settings;
	}

	/**
	 * @inheritDoc
	 */
	public function get( $name ) {
		if ( !$this->has( $name ) ) {
			throw new ConfigException( __METHOD__ . ": undefined option: '$name'" );
		}

		return $this->settings[$name];
	}

	/**
	 * @inheritDoc
	 * @since 1.24
	 */
	public function has( $name ) {
		return array_key_exists( $name, $this->settings );
	}

	/**
	 * @see MutableConfig::set
	 * @param string $name
	 * @param mixed $value
	 */
	public function set( $name, $value ) {
		$this->settings[$name] = $value;
	}

	/**
	 * @inheritDoc
	 * @since 1.38
	 * @return Traversable
	 */
	public function getIterator(): Traversable {
		return new ArrayIterator( $this->settings );
	}

	/**
	 * @inheritDoc
	 * @since 1.38
	 * @return string[]
	 */
	public function getNames(): array {
		return array_keys( $this->settings );
	}

	/**
	 * Clears all config variables.
	 * @since 1.39
	 */
	public function clear() {
		$this->settings = [];
	}
}

/** @deprecated class alias since 1.41 */
class_alias( HashConfig::class, 'HashConfig' );
