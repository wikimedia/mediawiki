<?php
/**
 * Copyright 2014
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
 */

/**
 * Provides a fallback sequence for Config objects
 *
 * @since 1.24
 */
class MultiConfig implements Config {

	/**
	 * Array of Config objects to use
	 * Order matters, the Config objects
	 * will be checked in order to see
	 * whether they have the requested setting
	 *
	 * @var Config[]
	 */
	private $configs;

	/**
	 * @param Config[] $configs
	 */
	public function __construct( array $configs ) {
		$this->configs = $configs;
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
