<?php
namespace MediaWiki\Config;

/**
 * Copyright 2016
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
use MediaWiki\MediaWikiServices;
use MediaWiki\Services\SalvageableService;
use Wikimedia\Assert\Assert;

/**
 * Object which holds currently registered configuration options.
 *
 * @since 1.29
 */
class ConfigRepository implements \Iterator, SalvageableService  {
	private $services;

	private $configItems = [];

	public function setMediaWikiServices( MediaWikiServices $services ) {
		$this->services = $services;
	}

	/**
	 * Returns true, if this repository contains a configuration with a specific name.
	 *
	 * @param $name
	 * @return bool
	 */
	public function has( $name ) {
		return isset($configItems[$name]);
	}

	/**
	 * Returns the ConfigItem with the given name, if there's one. Throws a ConfigException
	 * otherwise.
	 *
	 * @param $name
	 * @return ConfigItem
	 * @throws \ConfigException
	 */
	public function get( $name ) {
		if ( !$this->has( $name ) ) {
			throw new \ConfigException( 'The configuration option ' . $name . ' does not exist.' );
		}
		return $this->configItems[$name];
	}

	/**
	 * Adds the definition of a configuration to this repository.
	 *
	 * @param ConfigItem $item
	 * @throws \ConfigException
	 */
	public function add( ConfigItem $item ) {
		if ($this->has($item->getName())) {
			throw new \ConfigException( 'A configuration with the name ' . $item->getName() .
				'does already exist. It is provided by: ' .
				$this->get( $item->getName() )->getProvider()->getName() );
		}
		$item->setMediaWikiServices( $this->services );
		$this->configItems[] = $item;
	}

	public function current () {
		return current( $this->configItems );
	}

	public function key () {
		return key( $this->configItems );
	}
	public function next () {
		return next( $this->configItems );
	}
	public function rewind () {
		reset( $this->configItems );
	}
	public function valid () {
		return current( $this->configItems ) !== false;
	}

	/**
	 * Re-uses existing Cache objects from $other. Cache objects are only re-used if the
	 * registered factory function for both is the same.
	 *
	 * @see SalvageableService::salvage()
	 *
	 * @param SalvageableService $other The object to salvage state from. $other must have the
	 * exact same type as $this.
	 */
	public function salvage( SalvageableService $other ) {
		Assert::parameterType( self::class, $other, '$other' );

		/** @var ConfigRepository $other */
		$otherCurrentObj = $other->current();
		foreach ( $other->configItems as $name => $otherConfig ) {
			if ( isset( $this->configItems[$name] ) ) {
				continue;
			}

			$this->configItems[$name] = $otherConfig;

			// recover the pointer of the other config repository
			if ($otherCurrentObj === $otherConfig) {
				end( $this->configItems );
			}
		}

		// disable $other
		$other->configItems = [];
	}
}