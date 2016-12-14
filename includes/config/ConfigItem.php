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
 * Interface, which holds information about a specific config item.
 *
 * @since 1.29
 */
interface ConfigItem {
	/**
	 * Returns the name of this configuration option.
	 *
	 * @return string
	 */
	public function getName();

	/**
	 * Returns the current value of the configuration.
	 *
	 * This function may get the current value using a configuration storage backend.
	 *
	 * @see \Config::get()
	 *
	 * @return mixed
	 */
	public function getValue();

	/**
	 * Returns the default value of this configuration. This value may be the same as the value
	 * of this::getValue().
	 *
	 * @return mixed
	 */
	public function getDefaultValue();

	/**
	 * Returns true, if the current value is the default value. It depends upon the
	 * implementation of this interface, if this returns true if this::getValue() ===
	 * this::getDefaultValue().
	 *
	 * @return mixed
	 */
	public function isDefaultValue();

	/**
	 * Returns the description of this config option or null, if there's no description.
	 *
	 * @return boolean
	 */
	public function getDescription();

	/**
	 * Sets the provider which should be used to get the value of this config option. The
	 * provider may be used by getValue().
	 *
	 * @param \Config $provider
	 */
	public function setValueProvider( \Config $provider );

	/**
	 * Returns the provider set to this configuration item, or null if there's no registered
	 * provider.
	 *
	 * @return \Config
	 */
	public function getValueProvider();

	/**
	 * Sets the ConfigProvider which provides this configurationb object.
	 *
	 * @return ConfigProvider
	 */
	public function setProvider( ConfigProvider $provider );

	/**
	 * Returns the ConfigProvider which provided this configuration object.
	 *
	 * @return ConfigProvider
	 */
	public function getProvider();

	/**
	 * Creates a new ConfigItem from the given object, which should contain at least this required
	 * information:
	 *  * name: The name of the configuration
	 *  * defaultvalue: The default value of the option
	 *  * provider: An object of the context by which this configuration item is provided
	 *
	 * Other options are:
	 *  * description: The description of the configuration
	 *  * valueprovider: The provider, from which the value should be retrieved
	 *  * config: Instead of valueprovider, the name of the config that can be retrieved from
	 *    ConfigFactory
	 *
	 * @param array $array
	 * @return ConfigItem
	 */
	public static function newFromArray( array $array );

	/**
	 * Sets the MediaWikiServices object which can be used to retrieve other services.
	 *
	 * @param MediaWikiServices $services
	 * @return mixed
	 */
	public function setMediaWikiServices(MediaWikiServices $services);
}