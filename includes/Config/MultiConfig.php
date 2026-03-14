<?php
/**
 * Copyright 2014
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Config;

/**
 * Provides a fallback sequence for Config objects
 *
 * @since 1.24
 */
class MultiConfig implements Config {

	/**
	 * @param Config[] $configs
	 *   Array of Config objects to use
	 *   Order matters, the Config objects
	 *   will be checked in order to see
	 *   whether they have the requested setting
	 */
	public function __construct(
		private readonly array $configs,
	) {
	}

	/**
	 * @inheritDoc
	 */
	public function get( $name ) {
		foreach ( $this->configs as $config ) {
			if ( $config->has( $name ) ) {
				return $config->get( $name );
			}
		}

		throw new ConfigException( __METHOD__ . ": undefined option: '$name'" );
	}

	/**
	 * @inheritDoc
	 */
	public function has( $name ) {
		foreach ( $this->configs as $config ) {
			if ( $config->has( $name ) ) {
				return true;
			}
		}

		return false;
	}
}

/** @deprecated class alias since 1.41 */
class_alias( MultiConfig::class, 'MultiConfig' );
