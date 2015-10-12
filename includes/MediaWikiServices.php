<?php
namespace MediaWiki;

use ConfigFactory;
use GlobalVarConfig;
use Config;
use Hooks;
use InvalidArgumentException;
use LBFactory;
use LoadBalancer;
use RuntimeException;
use SiteLookup;
use SiteStore;
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
 * Its implemented as a simple configurable DI container.
 * MediaWikiServices acts as a top level factory/registry for top level services, and builds
 * the network of service objects that defines MediaWiki's application logic.
 * It acts as an entry point to MediaWiki's dependency injection mechanism.
 *
 * Services are defined in the "wiring" array passed to the constructor,
 * or by calling defineService().
 */
class MediaWikiServices {

	/**
	 * Returns the global default instance of the top level service locator.
	 *
	 * The default instance is initialized using the service constructor callbacks
	 * defined in ServiceWiring.php.
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
			// NOTE: constructing GlobalVarConfig here is not particularly pretty,
			// but some information from the global scope has to be injected here,
			// even if it's just a file name or database credentials to load
			// configuration from.
			$instance = new self( new GlobalVarConfig() );

			// Load the array containing the default wiring.
			$wiring = require __DIR__ . '/../ServiceWiring.php';

			foreach ( $wiring as $name => $constructor ) {
				$instance->defineService( $name, $constructor );
			}

			// Provide a traditional hook point to allow extensions to configure services.
			Hooks::run( 'MediaWikiServices', array( $instance ) );
		}

		return $instance;
	}

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
	 * @param Config $config The Config object to be registered as the 'BootstrapConfig' service.
	 *        This has to contain at least the information needed to set up the 'ConfigFactory' service.
	 */
	public function __construct( Config $config ) {
		// register the given Config object as the bootstrap config service.
		$this->defineService( 'BootstrapConfig', function() use ( $config ) {
			return $config;
		} );
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
		return isset( $this->serviceConstructors[$name] );
	}

	/**
	 * @return string[]
	 */
	public function getServiceNames() {
		return array_keys( $this->serviceConstructors );
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
	 * Returns the Config object containing the bootstrap configuration.
	 * Bootstrap configuration would typically include database credentials
	 * and other information that may be needed before the ConfigFactory
	 * service can be instantiated.
	 *
	 * @note This should only be used during bootstrapping, in particular
	 * when creating the MainConfig service. Application logic should
	 * use getMainConfig() to get a Config instances.
	 *
	 * @return Config
	 */
	public function getBootstrapConfig() {
		return $this->getService( 'BootstrapConfig' );
	}

	/**
	 * @return ConfigFactory
	 */
	public function getConfigFactory() {
		return $this->getService( 'ConfigFactory' );
	}

	/**
	 * Returns the Config object that provides configuration for MediaWiki core.
	 * This may or may not be the same object that is returned by getBootstrapConfig().
	 *
	 * @return Config
	 */
	public function getMainConfig() {
		return $this->getService( 'MainConfig' );
	}

	/**
	 * @return SiteLookup
	 */
	public function getSiteLookup() {
		return $this->getService( 'SiteLookup' );
	}

	/**
	 * @return SiteStore
	 */
	public function getSiteStore() {
		return $this->getService( 'SiteStore' );
	}

	/**
	 * @return LBFactory
	 */
	public function getDBLoadBalancerFactory() {
		return $this->getService( 'DBLoadBalancerFactory' );
	}

	/**
	 * @return LoadBalancer The main DB load balancer for the local wiki.
	 */
	public function getDBLoadBalancer() {
		return $this->getService( 'DBLoadBalancer' );
	}

	///////////////////////////////////////////////////////////////////////////
	// NOTE: When adding a service getter here, don't forget to add a test
	// case for it in MediaWikiServicesTest::provideGetters() and in
	// MediaWikiServicesTest::provideGetService()!
	///////////////////////////////////////////////////////////////////////////

}
