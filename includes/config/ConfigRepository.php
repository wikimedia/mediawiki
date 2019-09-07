<?php
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

namespace MediaWiki\Config;

use Wikimedia\Assert\Assert;
use Wikimedia\Services\SalvageableService;

/**
 * Object which holds currently registered configuration options.
 *
 * @since 1.32
 */
class ConfigRepository implements SalvageableService {
	/** @var \ConfigFactory */
	private $configFactory;

	/** @var array */
	private $configItems = [
		'private' => [],
		'public' => [],
	];

	/**
	 * @param \ConfigFactory $configFactory
	 */
	public function __construct( \ConfigFactory $configFactory ) {
		$this->configFactory = $configFactory;
	}

	/**
	 * Returns true, if this repository contains a configuration with a specific name.
	 *
	 * @param string $name The name of the config to check the existence of
	 * @param bool $alsoPrivate If set to true, will check the private config options, too
	 * @return bool
	 */
	public function has( $name, $alsoPrivate = false ) {
		return isset( $this->configItems['public'][$name] ) ||
			( $alsoPrivate && isset( $this->configItems['private'][$name] ) );
	}

	/**
	 * Returns the ConfigItem with the given name, if there's one. Throws a ConfigException
	 * otherwise.
	 *
	 * @param string $name The name of the configuration option to get
	 * @return array
	 * @throws \ConfigException
	 */
	public function get( $name ) {
		if ( !$this->has( $name, true ) ) {
			throw new \ConfigException( 'The configuration option ' . $name . ' does not exist.' );
		}

		return $this->configItems['public'][$name] ?? $this->configItems['private'][$name];
	}

	/**
	 * Returns an array of all configuration items saved in this ConfigRepository. This includes
	 * all configuration options, including the ones marked as private and public.
	 *
	 * Note: This function does not do any permission checks or something similar. You should not
	 * use this function, if the output will be available to the public audience! Use
	 * ConfigRepository::getPublic() instead.
	 *
	 * @return array
	 */
	public function getAll() {
		return array_merge( $this->configItems['private'], $this->configItems['public'] );
	}

	/**
	 * Returns an array of all public configuration options saved in this ConfigRepository.
	 *
	 * @return array
	 */
	public function getPublic() {
		return $this->configItems['public'];
	}

	/**
	 * Returns the current value of the configuration option. If no ConfigRegistry was provided
	 * when the config was added to the repository, the default value will be returned.
	 *
	 * @param string $name The name of the configuration option to get the value of
	 * @return mixed
	 * @throws \ConfigException
	 */
	public function getValueOf( $name ) {
		$config = $this->get( $name );
		if ( !isset( $config['configregistry'] ) ) {
			return $config['value'];
		}

		return $this->configFactory->makeConfig( $config['configregistry'] )->get( $name );
	}

	/**
	 * Returns the description of the given config option, This can be either a localized
	 * description, if one such, or the (maybe english only) description provided in the
	 * definition of the configuration. If both is not provided an empty string is returned.
	 *
	 * @param string $name The name of the configuration option to get the description of
	 * @return string HTML-escaped string
	 */
	public function getDescriptionOf( $name ) {
		$config = $this->get( $name );
		if ( isset( $config['descriptionmsg'] ) ) {
			return wfMessage( $config['descriptionmsg'] )->escaped();
		}
		if ( isset( $config['description'] ) ) {
			return htmlspecialchars( $config['description'] );
		}
		return '';
	}

	/**
	 * Adds the definition of a configuration to this repository.
	 *
	 * @param string $name the name of the config
	 * @param array $config Options of this config. Values are:
	 *  - value: The default value of this configuration, required
	 *  - providedby: The name of the provider of this config (an extension, core, ...), required
	 *  - configregistry: The name of the config to retrieve the value with, required
	 *  - public: whether this option is public or not, if not set, the option is considered as
	 *    "private", optional
	 *  - description: the not localized description of this config option, optional
	 *  - descriptionmsg: The message key of the localized description of this configuration
	 *    option, optional
	 * @throws \ConfigException
	 */
	public function add( $name, array $config ) {
		if ( $this->has( $name ) ) {
			throw new \ConfigException( 'A configuration with the name ' . $name .
				'does already exist. It is provided by: ' .
				$this->get( $name )['providedby'] );
		}
		if ( isset( $config['public'] ) && $config['public'] ) {
			$this->configItems['public'][$name] = $config;
		} else {
			$this->configItems['private'][$name] = $config;
		}
	}

	/**
	 * Returns true, if there're no elements in this instance, otherwise false.
	 *
	 * @param bool $includePrivate Whether configuration options, that are marked as private
	 * should be included in this check.
	 * @return bool
	 */
	public function isEmpty( $includePrivate = false ) {
		if ( $includePrivate ) {
			return empty( $this->configItems['private'] ) &&
				empty( $this->configItems[ 'public'] );
		}
		return empty( $this->configItems['public'] );
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
		/** @var self $other */
		'@phan-var self $other';

		foreach ( $other->configItems['public'] as $name => $otherConfig ) {
			if ( isset( $this->configItems['public'][$name] ) ) {
				continue;
			}

			$this->add( $name, $otherConfig );
		}
		foreach ( $other->configItems['private'] as $name => $otherConfig ) {
			if ( isset( $this->configItems['private'][$name] ) ) {
				continue;
			}

			$this->add( $name, $otherConfig );
		}

		// disable $other
		$other->configItems = [];
	}
}
