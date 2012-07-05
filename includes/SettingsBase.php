<?php

/**
 * Base class for settings lists.
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
 * @since 1.20
 *
 * @file
 * @ingroup Settings
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
abstract class SettingsBase extends ArrayObject implements Settings {

	/**
	 * Keeps track of if the settings have been build already.
	 * @since 1.20
	 * @var boolean
	 */
	protected $buildSettings = false;

	/**
	 * @see Settings::singleton
	 *
	 * @since 1.20
	 *
	 * @param boolean $rebuild
	 *
	 * @return Settings
	 */
	public static function singleton( $rebuild = false ) {
		static $instances = array();
		$class = get_called_class();

		if ( $rebuild || !array_key_exists( $class, $instances ) ) {
			$instances[$class] = new static( array( 'do not use' ) );
		}

		return $instances[$class];
	}

	/**
	 * @see Settings::get
	 *
	 * @since 1.20
	 *
	 * @param string $settingName
	 *
	 * @return mixed
	 */
	public static function get( $settingName ) {
		return static::singleton()->offsetGet( $settingName );
	}

	/**
	 * @see Settings::set
	 *
	 * @since 1.20
	 *
	 * @param string $settingName
	 * @param mixed $settingValue
	 */
	public static function set( $settingName, $settingValue ) {
		static::singleton()->offsetSet( $settingName, $settingValue );
	}

	/**
	 * @see Settings::has
	 *
	 * @since 1.20
	 *
	 * @param string $settingName
	 *
	 * @return boolean
	 */
	public static function has( $settingName ) {
		return static::singleton()->offsetExists( $settingName );
	}

	/**
	 * @see ArrayObject::offsetExists
	 *
	 * @since 1.20
	 *
	 * @param mixed $settingName
	 *
	 * @return boolean
	 */
	public function offsetExists( $settingName ) {
		$this->constructSettings();
		return parent::offsetExists( $settingName );
	}

	/**
	 * @see ArrayObject::offsetSet
	 *
	 * @since 1.20
	 *
	 * @param mixed $settingName
	 * @param mixed $settingValue
	 */
	public function offsetSet( $settingName, $settingValue ) {
		$this->constructSettings();
		parent::offsetSet( $settingName, $settingValue );
	}

	/**
	 * @see ArrayObject::offsetGet
	 *
	 * @since 1.20
	 *
	 * @param mixed $settingName
	 *
	 * @return mixed
	 */
	public function offsetGet( $settingName ) {
		$this->constructSettings();
		return parent::offsetGet( $settingName );
	}

	/**
	 * Constructor.
	 * Should be protected, but since PHP fails, you'll just have to pretend it actually is.
	 *
	 * @since 1.20
	 *
	 * @param array|null $doNoUse
	 */
	public function __construct( $doNoUse = null ) {
		if ( $doNoUse !== array( 'do not use' ) ) {
			throw new MWException( 'You should not create new instances of SettingsBase, use the singleton method instead.' );
		}

		parent::__construct( array() );
	}

	/**
	 * Builds the settings as they should be seen by the MediaWiki install
	 * and adds them to the settings list.
	 *
	 * @since 1.20
	 */
	protected function constructSettings() {
		if ( !$this->buildSettings ) {
			$this->buildSettings = true;

			foreach ( $this->getConstructedSettings() as $name => $value ) {
				$this[$name] = $value;
			}
		}
	}

	/**
	 * Returns the settings as they should be seen by the MediaWiki install.
	 * This means obtaining the set settings and merging them with the default ones.
	 *
	 * @since 1.20
	 *
	 * @return array
	 */
	protected function getConstructedSettings() {
		return array_merge(
			$this->getDefaultSettings(),
			$this->getSetSettings()
		);
	}

	/**
	 * Returns the default values for the settings.
	 * setting name (string) => setting value (mixed)
	 *
	 * @since 1.20
	 *
	 * @return array
	 */
	protected function getDefaultSettings() {
		return array();
	}

	/**
	 * Returns the settings that have a value set that should
	 * override the default.
	 *
	 * @since 1.20
	 *
	 * @return mixed
	 */
	protected abstract function getSetSettings();

	/**
	 * @see Settings::getSetting
	 *
	 * @since 1.20
	 *
	 * @param string $settingName
	 *
	 * @throws MWException
	 * @return mixed
	 */
	public function getSetting( $settingName ) {
		if ( !$this->offsetExists( $settingName ) ) {
			throw new MWException( 'Attempt to get non-existing setting "' . $settingName . '"' );
		}

		return $this[$settingName];
	}

	/**
	 * @see Settings::setSetting
	 *
	 * @since 1.20
	 *
	 * @param string $settingName
	 * @param mixed $settingValue
	 */
	public function setSetting( $settingName, $settingValue ) {
		$this[$settingName] = $settingValue;
	}

	/**
	 * @see Settings::hasSetting
	 *
	 * @since 1.20
	 *
	 * @param string $settingName
	 *
	 * @return boolean
	 */
	public function hasSetting( $settingName ) {
		return $this->offsetExists( $settingName );
	}

}