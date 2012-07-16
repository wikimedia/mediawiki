<?php
/**
 * Base configuration class.
 *
 * Get some configuration variable:
 *   $mySetting = Conf::get( 'mySetting' );
 *
 * Copyright © 2011 Chad Horohoe <chadh@wikimedia.org>
 * http://www.mediawiki.org/
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
 * @defgroup Config Config
 */
abstract class Conf {
	/**
	 * A special value to return when default config items do not exist. Use
	 * this to differentiate from 'null' which may be a valid config value.
	 *
	 * Please don't ever make this a default (or accepted) value for your
	 * configuration. It's liable to Break Something.
	 */
	const NO_SUCH_DEFAULT_CONFIG = 'mw-no-such-default-config';

	/**
	 * The Wiki ID (usually $wgDBname)
	 * @var String $wikiId
	 */
	private $wikiId;

	/**
	 * Singleton
	 * @var Conf $__instance
	 */
	private static $__instance;

	/**
	 * Store core defaults
	 * @var Array $defaults
	 */
	protected $defaults;
	/**
	 * Store extension defaults
	 * @var Array $extensionDefaults
	 */
	protected $extensionDefaults;
	/**
	 * Store wiki overrides
	 * @var Array $values
	 */
	protected $values = array();

	/**
	 * Constructor. Children should call this if implementing.
	 * @param $confConfig Array of config vars
	 */
	protected function __construct( $confConfig ) {
		$this->wikiId = $confConfig['wikiId'];
		$this->defaults = (array)(new DefaultSettings);
		// @todo implement this:
		// $this->initExtensionDefaults();
		$this->initChangedSettings();
		if( isset( $confConfig['exposeGlobals'] ) ) {
			$this->exposeGlobals();
		}
	}

	/**
	 * Expose all config variables as globals for back-compat. Ewwww.
	 */
	private function exposeGlobals() {
		$allVars = $this->defaults + $this->extensionDefaults + $this->values;
		foreach( $allVars as $name => $value ) {
			$var = 'wg' . ucfirst( $name );
			$GLOBALS[$var] = $value;
		}
	}

	/**
	 * Load customized settings from whatever the data store is
	 */
	abstract protected function initChangedSettings();

	/**
	 * Apply a setting to the backend store
	 * @param $name String Name of the setting
	 * @param $value mixed Value to store
	 */
	abstract protected function writeSetting( $name, $value );

	/**
	 * Initialize a new child class based on a configuration array
	 * @param $conf Array of configuration settings, see $wgConfiguration
	 *   for details
	 * @return Conf
	 */
	private static function newFromSettings( $conf ) {
		$class = ucfirst( $conf['type'] ) . 'Conf';
		if( !class_exists( $class ) ) {
			throw new MWException( '$wgConfiguration misconfigured with invalid "type"' );
		}
		return new $class( $conf );
	}

	/**
	 * Get the singleton if we don't want a specific wiki
	 * @param $wiki String An id for a remote wiki
	 * @return Conf child
	 */
	public static function load( $wiki = false ) {
		throw new MWException( "Not working yet, don't attempt to use this" );
		if( !self::$__instance ) {
			/**global $wgConfiguration;
			self::$__instance = self::newFromSettings( $wgConfiguration );*/
		}
		if( $wiki && $wiki != self::$__instance->getWikiId() ) {
			// Load configuration for a different wiki, not sure how
			// we're gonna do this yet
			return null;
		}
		return self::$__instance;
	}

	/**
	 * Get a property from the configuration database, falling back
	 * to DefaultSettings if undefined
	 * @param $name String Name of setting to retrieve.
	 * @param $wiki String An id for a remote wiki
	 * @return mixed
	 */
	public static function get( $name, $wiki = false ) {
		return self::load( $wiki )->retrieveSetting( $name );
	}

	/**
	 * Actually get the setting, checking overrides, extensions, then core.
	 *
	 * @param $name String Name of setting to get
	 * @return mixed
	 */
	public function retrieveSetting( $name ) {
		// isset() is ok here, because the default is to return null anyway.
		if( isset( $this->values[$name] ) ) {
			return $this->values[$name];
		} elseif( isset( $this->extensionDefaults[$name] ) ) {
			return $this->extensionDefaults[$name];
		} elseif( isset( $this->defaults[$name] ) ) {
			return $this->defaults[$name];
		} else {
			wfDebug( __METHOD__ . " called for unknown configuration item '$name'\n" );
			return null;
		}
	}

	/**
	 * Apply a setting to the configuration object.
	 * @param $name String Name of the config item
	 * @param $value mixed Any value to use for the key
	 * @param $write bool Whether to write to the static copy (db, file, etc)
	 */
	public function applySetting( $name, $value, $write = false ) {
		$this->values[$name] = $value;
		if( $write && ( $value !== $this->getDefaultSetting( $name ) ) ) {
			$this->writeSetting( $name, $value );
		}
	}

	/**
	 * Get the default for a given setting name. Check core and then extensions.
	 * Will return NO_SUCH_DEFAULT_CONFIG if the config item does not exist.
	 *
	 * @param $name String Name of setting
	 * @return mixed
	 */
	public function getDefaultSetting( $name ) {
		// Use array_key_exists() here, to make sure we return a default
		// that's really set to null.
		if( array_key_exists( $name, $this->defaults ) ) {
			return $this->defaults[$name];
		} elseif( array_key_exists( $name, $this->extensionDefaults ) ) {
			return $this->extensionDefaults[$name];
		} else {
			wfDebug( __METHOD__ . " called for unknown configuration item '$name'\n" );
			return self::NO_SUCH_DEFAULT_CONFIG;
		}
	}

	/**
	 * What is the wiki ID for this site?
	 * @return String
	 */
	public function getWikiId() {
		return $this->wikiId;
	}
}
