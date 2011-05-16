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
 * @ingroup Config
 */
abstract class Conf {
	/**
	 * The Wiki ID (usually $wgDBname)
	 * @var String
	 */
	private $wikiId;

	/**
	 * Singleton
	 * @var Conf
	 */
	private static $__instance;

	/**
	 * Stores of the core defaults, extension defaults and wiki overrides
	 *
	 * @var array
	 */
	protected $defaults, $extensionDefaults, $values = array();

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
	}

	/**
	 * Load customized settings from whatever the data store is
	 */
	abstract protected function initChangedSettings();

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
		if( !self::$__instance ) {
			global $wgConfiguration;
			self::$__instance = self::newFromSettings( $wgConfiguration );
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
	 * On failure, 
	 * @param $name String Name of setting to get
	 * @return mixed
	 */
	public function retrieveSetting( $name ) {
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
	 * What is the wiki ID for this site?
	 * @return String
	 */
	public function getWikiId() {
		return $this->wikiId;
	}
}
