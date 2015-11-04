<?php
namespace MediaWiki;

use GlobalVarConfig;
use Config;
use InvalidArgumentException;
use RuntimeException;
use SiteLookup;
use SiteStore;
use DBSiteStore;
use Wikimedia\Assert\Assert;

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
 */

 /**
 * MediaWikiServices is the service locator for the application scope of MediaWiki.
 * It acts as a top factory/registry for top level services, and builds the object
 * net that defines the services available to MediaWiki's application logic. In this
 * way, it acts as the anchor of MediaWiki's dependency injection mechanism.
 *
 * Services are defined either as private methods with names starting with "new",
 * or by calling defineService().
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
	 * @var callable[]
	 */
	private $serviceConstructors = array();

	/**
	 * @var callable[]
	 */
	private $serviceModifiers = array();

	/**
	 * @param Config $config
	 */
	public function __construct( Config $config ) {
		$this->config = $config;
	}

	/**
	 * Returns true if a service is defined for $name, that is, if a call to getService( $name )
	 * would return a service instance.
	 *
	 * @param string $name
	 *
	 * @return bool
	 */
	public function hasService( $name ) {
		return isset( $this->serviceConstructors[$name] ) || method_exists( $this, "new$name" );
	}

	/**
	 * Define a new service. The service must not be known already.
	 *
	 * @see getService().
	 * @see replaceService().
	 * @see modifyService().
	 *
	 * @param string $name The name of the service to register, for use with getService().
	 * @param callable $constructor Callback that returns a service instance.
	 *        Will be called with this MediaWikiServices instances as the only parameter.
	 *
	 * @throws RuntimeException if there is already a service registered as $name.
	 */
	public function defineService( $name, $constructor ) {
		Assert::parameterType( 'callable', $constructor, '$constructor' );

		if ( $this->hasService( $name ) ) {
			throw new RuntimeException( 'Service already defined: ' . $name );
		}

		$this->serviceConstructors[$name] = $constructor;
	}

	/**
	 * Replace an already defined service.
	 * This does not remove any modifiers defined for the service.
	 *
	 * @see defineService().
	 * @see modifyService().
	 *
	 * @note This causes any previously instantiated instance of the service to be discarded.
	 *
	 * @param string $name The name of the service to register.
	 * @param callable $constructor Callback that returns a service instance.
	 *        Will be called with this MediaWikiServices instances as the only parameter.
	 *        The callback must return a service compatible with the originally defined service.
	 *
	 * @throws RuntimeException if $name is not a known service.
	 */
	public function replaceService( $name, $constructor ) {
		Assert::parameterType( 'callable', $constructor, '$constructor' );

		if ( !$this->hasService( $name ) ) {
			throw new RuntimeException( 'Service not defined: ' . $name );
		}

		$this->serviceConstructors[$name] = $constructor;
		unset( $this->services[$name] );
	}

	/**
	 * @return Config
	 */
	public function getConfig() {
		return $this->config;
	}

	/**
	 * Defines a modifier for a known service.
	 * When multiple modifiers are defined for the same service, they are stacked:
	 * Each modifier is called on the result of the previous modifier.
	 *
	 * @note This causes any previously instantiated instance of the service to be discarded.
	 *
	 * @see defineService().
	 * @see replaceService().
	 *
	 * @param string $name The name of the service to modify.
	 * @param callable $modifier Callback that returns a service instance based on an existing
	 *        service instance. This will typically be a decorator wrapping the original service.
	 *        Will be called with the original service as the first and MediaWikiServices as
	 *        the second parameter.
	 *        The callback must return a service compatible with the originally defined service.
	 *
	 * @throws RuntimeException if $name is not a known service.
	 */
	public function modifyService( $name, $modifier ) {
		Assert::parameterType( 'callable', $modifier, '$modifier' );

		if ( !$this->hasService( $name ) ) {
			throw new RuntimeException( 'Service not defined: ' . $name );
		}

		$this->serviceModifiers[$name][] = $modifier;
		unset( $this->services[$name] );
	}

	/**
	 * Returns a service object of the kind associated with $name.
	 * Services instances are instantiated lazily, on demand.
	 * This method may or may not return the same service instance
	 * when called multiple times with the same $name.
	 *
	 * @see defineService().
	 * @see modifyService().
	 *
	 * @param string $name The service name
	 *
	 * @throws InvalidArgumentException if $name is not a known service.
	 * @return object The service instance
	 */
	public function getService( $name ) {
		if ( !isset( $this->services[$name] ) ) {
			$this->services[$name] = $this->createService( $name );
		}

		return $this->services[$name];
	}

	/**
	 * @param string $name
	 *
	 * @throws InvalidArgumentException if $name is not a known service.
	 * @return object
	 */
	private function createService( $name ) {
		if ( isset( $this->serviceConstructors[$name] ) ) {
			$service = call_user_func( $this->serviceConstructors[$name], $this );
		} elseif ( method_exists( $this, "new$name" ) ) {
			$method = "new$name";
			$service = $this->$method();
		} else {
			throw new InvalidArgumentException( 'Unknown service: ' . $name );
		}

		if ( isset( $this->serviceModifiers[$name] ) ) {
			foreach ( $this->serviceModifiers[$name] as $modifier ) {
				$service = call_user_func( $modifier, $service, $this );
			}
		}

		return $service;
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
