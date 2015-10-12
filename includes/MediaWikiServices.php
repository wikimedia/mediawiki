<?php
namespace MediaWiki;

use GlobalVarConfig;
use Config;
use SiteLookup;
use SiteStore;
use DBSiteStore;

/**
 * Service locator for MediaWiki core services.
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
 *
 * @since 1.27
 *
 *
 * MediaWikiServices is the service locator for the application scope of MediaWiki.
 * It acts as a top factory/registry for top level services, and defines the object
 * net that defines the services available to MediaWiki's application logic. In this
 * way, it acts as the heart of MediaWiki's dependency injection mechanism.
 */
class MediaWikiServices {

	/**
	 * Returns the global default instance of the top level service locator.
	 *
	 * @note This should only be called by static functions! The instance returned here
	 * should not be passed around! Objects that need access to a service should have
	 * that service injected into the constructor, never a service locator!
	 *
	 * @return MediaWikiServices
	 */
	public static function getInstance() {
		static $instance = null;

		if ( $instance === null ) {
			$instance = new self( new GlobalVarConfig() );
		}

		return $instance;
	}

	/**
	 * @var Config
	 */
	private $config;

	/**
	 * @var object[]
	 */
	private $services = array();

	/**
	 * @param Config $config
	 */
	public function __construct( Config $config ) {
		$this->config = $config;
	}

	/**
	 * @return Config
	 */
	public function getConfig() {
		return $this->config;
	}

	/**
	 * @param string $name
	 *
	 * @return object
	 */
	private function getService( $name ) {
		if ( !isset( $this->services[$name] ) ) {
			$this->services[$name] = $this->createService( $name );
		}

		return $this->services[$name];
	}

	/**
	 * @param string $name
	 *
	 * @return object
	 */
	private function createService( $name ) {
		$method = "new$name";
		return $this->$method();
	}

	/**
	 * @return SiteLookup
	 */
	public function getSiteLookup() {
		return $this->getSiteStore();
	}

	/**
	 * @return SiteStore
	 */
	public function getSiteStore() {
		return $this->getService( 'SiteStore' );
	}

	/**
	 * @note should be called by createService() only!
	 */
	private function newSiteStore() {
		return new DBSiteStore();
	}

}
