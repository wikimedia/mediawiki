<?php
namespace MediaWiki\Services;

use InvalidArgumentException;
use RuntimeException;
use Wikimedia\Assert\Assert;

/**
 * Generic service container.
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
 * ServiceContainer provides a generic service to manage named services using
 * lazy instantiation based on constructor callbacks (aka factory methods).
 *
 * Services managed by an instance of ServiceContainer may or may not implement
 * a common interface.
 *
 * @note When using ServiceContainer to manage a set of services, consider
 * creating a wrapper or a subclass that provides access to the services via
 * getter methods with more meaningful names and more specific return type
 * declarations.
 *
 * @see docs/injection.txt for an overview of using dependency injection in the
 *      MediaWiki code base.
 */
class ServiceContainer {

	/**
	 * @var object[]
	 */
	private $services = array();

	/**
	 * @var callable[]
	 */
	private $serviceConstructors = array();

	/**
	 * @var array
	 */
	private $extraInstantiationParams;

	/**
	 * @param array $extraInstantiationParams Any additional parameters to be pased to the
	 * constructor callback when instantiating a service. This is typically used to provide
	 * access to additional ServiceContainers or Config objects.
	 */
	public function __construct( array $extraInstantiationParams = array() ) {
		$this->extraInstantiationParams = $extraInstantiationParams;
	}

	/**
	 * @param array $wiringFiles A list of PGP files to load wiring information from.
	 * Each file is loaded using PHP's include mechanism. Each file is expected to
	 * return an associative array that maps service names to constructor callbacks.
	 */
	public function loadWiringFiles( array $wiringFiles ) {
		foreach ( $wiringFiles as $file ) {
			// the wiring file is required to return an array of callbacks.
			$wiring = require $file;

			Assert::postcondition(
				is_array( $wiring ),
				"Wiring file $file is expected to return an array!"
			);

			$this->applyWiring( $wiring );
		}
	}

	/**
	 * Registers multiple services (aka a "wiring").
	 *
	 * @param array $serviceConstructors An associative array mapping service names to
	 *        constructor callbacks.
	 */
	public function applyWiring( array $serviceConstructors ) {
		Assert::parameterElementType( 'callable', $serviceConstructors, '$serviceConstructors' );

		foreach ( $serviceConstructors as $name => $constructor ) {
			$this->defineService( $name, $constructor );
		}
	}

	/**
	 * Provides an argument array for a constructior (or cleanup) callback.
	 *
	 * @param mixed $arg1... Any required arguments for the callback.
	 *
	 * @return array An array containing all parameters passed to this method, with
	 * the contents of $this->extraInstantiationParams appended.
	 */
	private function getCallbackArgs() {
		$args = func_get_args();
		$args = array_merge( $args, $this->extraInstantiationParams );
		return $args;
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
	 * @see resetService().
	 *
	 * @param string $name The name of the service to register, for use with getService().
	 * @param callable $constructor Callback that returns a service instance.
	 *        Will be called with this MediaWikiServices instance as the only parameter.
	 *        Any extra instantiation parameters provided to the constructor will be
	 *        passed as subsequent parameters when invokin the callback.
	 *
	 * @throws RuntimeException if there is already a service registered as $name.
	 */
	public function defineService( $name, $constructor ) {
		Assert::parameterType( 'string', $name, '$name' );
		Assert::parameterType( 'callable', $constructor, '$constructor' );

		if ( $this->hasService( $name ) ) {
			throw new RuntimeException( 'Service already defined: ' . $name );
		}

		$this->serviceConstructors[$name] = $constructor;
	}

	/**
	 * Replace an already defined service.
	 *
	 * @see defineService().
	 * @see resetService().
	 *
	 * @note This causes any previously instantiated instance of the service to be discarded.
	 *
	 * @param string $name The name of the service to register.
	 * @param callable $constructor Callback that returns a service instance.
	 *        Will be called with this MediaWikiServices instance as the only parameter.
	 *        The callback must return a service compatible with the originally defined service.
	 *        Any extra instantiation parameters provided to the constructor will be
	 *        passed as subsequent parameters when invokin the callback.
	 * @param callable|null $cleanup Callback to perform cleanup on any existing service object.
	 *        Will be called with the old service instance as the first and this MediaWikiServices
	 *        instance as the second parameter.
	 *        Any extra instantiation parameters provided to the constructor will be
	 *        passed as subsequent parameters when invokin the callback.
	 *
	 * @throws RuntimeException if $name is not a known service.
	 */
	public function replaceService( $name, $constructor, $cleanup = null ) {
		Assert::parameterType( 'string', $name, '$name' );
		Assert::parameterType( 'callable', $constructor, '$constructor' );
		Assert::parameterType( 'callable|null', $cleanup, '$cleanup' );

		if ( !$this->hasService( $name ) ) {
			throw new RuntimeException( 'Service not defined: ' . $name );
		}

		$this->serviceConstructors[$name] = $constructor;
		$this->resetService( $name, $cleanup );
	}

	/**
	 * Discards an existing service instane, causing a fresh instance to be created when
	 * getService() is next called with $name.
	 *
	 * @note Usage of this method should be avoided, since appropriate cleanup requires
	 * knowledge about the implementation details of the previous service. This method
	 * is only needed when a service depends on global state that has been changed,
	 * making it necessary to re-create the service based on the new global state.
	 * Ideally, services would not depend on any global state.
	 *
	 * @see defineService().
	 * @see replaceService().
	 * @see getService().
	 *
	 * @param string $name The name of the service to register.
	 * @param callable|null $cleanup Callback to perform cleanup on any existing service object.
	 *        Will be called with the old service instance as the first and this MediaWikiServices
	 *        instance as the second parameter.
	 *        Any extra instantiation parameters provided to the constructor will be
	 *        passed as subsequent parameters when invokin the callback.
	 *
	 * @throws RuntimeException if $name is not a known service.
	 */
	public function resetService( $name, $cleanup = null ) {
		Assert::parameterType( 'string', $name, '$name' );
		Assert::parameterType( 'callable|null', $cleanup, '$cleanup' );

		if ( !$this->hasService( $name ) ) {
			throw new RuntimeException( 'Service not defined: ' . $name );
		}

		if ( $cleanup !== null && isset( $this->services[$name] ) ) {
			call_user_func_array(
				$cleanup,
				$this->getCallbackArgs( $this->services[$name], $this )
			);
		}
		unset( $this->services[$name] );
	}

	/**
	 * Returns a service object of the kind associated with $name.
	 * Services instances are instantiated lazily, on demand.
	 * This method may or may not return the same service instance
	 * when called multiple times with the same $name.
	 *
	 * @note Rather than calling this method directly, it is recommended to provide
	 * getters with more meaningful names and more specific return types, using
	 * a subclass or wrapper.
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
			$service = call_user_func_array(
				$this->serviceConstructors[$name],
				$this->getCallbackArgs( $this )
			);
		} else {
			throw new InvalidArgumentException( 'Unknown service: ' . $name );
		}

		return $service;
	}

}
