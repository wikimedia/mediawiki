<?php

/**
 * Interface for settings lists.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA
 *
 * @since 0.1
 *
 * @file
 * @ingroup Settings
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
interface Settings extends IteratorAggregate, ArrayAccess, Serializable {

	/**
	 * Gets the value of the specified setting.
	 *
	 * @since 0.1
	 *
	 * @param string $settingName
	 *
	 * @throws MWException
	 * @return mixed
	 */
	public function getSetting( $settingName );

	/**
	 * Sets the value of the specified setting.
	 *
	 * @since 0.1
	 *
	 * @param string $settingName
	 * @param mixed $settingValue
	 */
	public function setSetting( $settingName, $settingValue );

	/**
	 * Sets the value of the specified setting.
	 *
	 * @since 0.1
	 *
	 * @param string $settingName
	 *
	 * @return boolean
	 */
	public function hasSetting( $settingName );

	/**
	 * Returns an instance of the settings class.
	 *
	 * @since 0.1
	 *
	 * @param boolean $rebuild
	 *
	 * @return Settings
	 */
	public static function singleton( $rebuild = false );

	/**
	 * Shortcut for ::singleton->getSetting.
	 * @see Settings::getSetting
	 *
	 * @since 0.1
	 *
	 * @param string $settingName
	 *
	 * @return mixed
	 */
	public static function get( $settingName );

	/**
	 * Shortcut for ::singleton->setSetting.
	 * @see Settings::setSetting
	 *
	 * @since 0.1
	 *
	 * @param string $settingName
	 * @param mixed $settingValue
	 */
	public static function set( $settingName, $settingValue );

	/**
	 * Shortcut for ::singleton->hasSetting.
	 * @see Settings::hasSetting
	 *
	 * @since 0.1
	 *
	 * @param string $settingName
	 *
	 * @return boolean
	 */
	public static function has( $settingName );

}

