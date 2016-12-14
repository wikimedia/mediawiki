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
use MediaWiki\ConfigProvider\ConfigProvider;
use MediaWiki\MediaWikiServices;

/**
 * A descriptive object for a configuration option.
 *
 * @since 1.29
 */
class ConfigItemImpl implements ConfigItem {

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var mixed
	 */
	private $defaultValue;

	/**
	 * @var string
	 */
	private $description;

	/**
	 * @var \Config
	 */
	private $valueProvider;

	/**
	 * @var string
	 */
	private $configFactoryName;

	/**
	 * @var ConfigProvider
	 */
	private $provider;

	/**
	 * @var MediaWikiServices
	 */
	private $services;

	public function getName() {
		return $this->name;
	}

	public function getValue() {
		if ( $this->getValueProvider() === null ) {
			throw new \ConfigException(
				'No provider set to retrieve the value of the config item: ' . $this->getName() );
		}

		return $this->getValueProvider()->get( $this->getName() );
	}

	public function getDefaultValue() {
		return $this->defaultValue;
	}

	public function isDefaultValue() {
		return $this->getValue() === $this->getDefaultValue();
	}

	public function getDescription() {
		return $this->description;
	}

	public function setValueProvider( \Config $provider ) {
		$this->valueProvider = $provider;
	}

	public function getValueProvider() {
		if (
			$this->valueProvider === null &&
			$this->configFactoryName !== null &&
			$this->services !== null
		) {
			$this->valueProvider = $this->services
				->getConfigFactory()
				->makeConfig( $this->configFactoryName );
		}
		return $this->valueProvider;
	}

	public function setProvider( ConfigProvider $provider ) {
		$this->provider = $provider;
	}

	public function getProvider() {
		return $this->provider;
	}

	public static function newFromArray( array $arr ) {
		if ( !array_key_exists( 'name', $arr ) ) {
			throw new \ConfigException( 'The name for a ConfigItem is mandatory. None given.' );
		}
		if ( !array_key_exists( 'defaultvalue', $arr ) ) {
			throw new \ConfigException(
				'The default value for a ConfigItem is mandatory. None given.' );
		}
		if ( !array_key_exists( 'provider', $arr ) ) {
			throw new \ConfigException(
				'The provider for a ConfigItem is mandatory. None given.' );
		}

		$retval = new self();
		$retval->name = $arr['name'];
		$retval->provider = $arr['provider'];
		$retval->defaultValue = $arr['defaultvalue'];
		if ( isset( $arr['description'] ) ) {
			$retval->description = $arr['description'];
		}
		if ( isset( $arr['valueprovider'] ) ) {
			$retval->valueProvider = $arr['valueprovider'];
		}
		if ( isset( $arr['config'] ) ) {
			$retval->configFactoryName = $arr['config'];
		}

		return $retval;
	}

	public function setMediaWikiServices(MediaWikiServices $services) {
		$this->services = $services;
	}

	public function __toString() {
		return $this->getName();
	}
}