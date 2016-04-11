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
 * lazy instantiation based on instantiator callback functions.
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
	private $services = [];

	/**
	 * @var callable[]
	 */
	private $serviceInstantiators = [];

	/**
	 * @var array
	 */
	private $extraInstantiationParams;

	/**
	 * @param array $extraInstantiationParams Any additional parameters to be passed to the
	 * instantiator function when creating a service. This is typically used to provide
	 * access to additional ServiceContainers or Config objects.
	 */
	public function __construct( array $extraInstantiationParams = [] ) {
		$this->extraInstantiationParams = $extraInstantiationParams;
	}

	/**
	 * @param array $wiringFiles A list of PHP files to load wiring information from.
	 * Each file is loaded using PHP's include mechanism. Each file is expected to
	 * return an associative array that maps service names to instantiator functions.
	 */
	public function loadWiringFiles( array $wiringFiles ) {
		foreach ( $wiringFiles as $file ) {
			// the wiring file is required to return an array of instantiators.
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
	 * @param array $serviceInstantiators An associative array mapping service names to
	 *        instantiator functions.
	 */
	public function applyWiring( array $serviceInstantiators ) {
		Assert::parameterElementType( 'callable', $serviceInstantiators, '$serviceInstantiators' );

		foreach ( $serviceInstantiators as $name => $instantiator ) {
			$this->defineService( $name, $instantiator );
		}
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
		return isset( $this->serviceInstantiators[$name] );
	}

	/**
	 * @return string[]
	 */
	public function getServiceNames() {
		return array_keys( $this->serviceInstantiators );
	}

	/**
	 * Define a new service. The service must not be known already.
	 *
	 * @see getService().
	 * @see replaceService().
	 *
	 * @param string $name The name of the service to register, for use with getService().
	 * @param callable $instantiator Callback that returns a service instance.
	 *        Will be called with this MediaWikiServices instance as the only parameter.
	 *        Any extra instantiation parameters provided to the constructor will be
	 *        passed as subsequent parameters when invoking the instantiator.
	 *
	 * @throws RuntimeException if there is already a service registered as $name.
	 */
	public function defineService( $name, callable $instantiator ) {
		Assert::parameterType( 'string', $name, '$name' );

		if ( $this->hasService( $name ) ) {
			throw new RuntimeException( 'Service already defined: ' . $name );
		}

		$this->serviceInstantiators[$name] = $instantiator;
	}

	/**
	 * Replace an already defined service.
	 *
	 * @see defineService().
	 *
	 * @note This causes any previously instantiated instance of the service to be discarded.
	 *
	 * @param string $name The name of the service to register.
	 * @param callable $instantiator Callback function that returns a service instance.
	 *        Will be called with this MediaWikiServices instance as the only parameter.
	 *        The instantiator must return a service compatible with the originally defined service.
	 *        Any extra instantiation parameters provided to the constructor will be
	 *        passed as subsequent parameters when invoking the instantiator.
	 *
	 * @throws RuntimeException if $name is not a known service.
	 */
	public function redefineService( $name, callable $instantiator ) {
		Assert::parameterType( 'string', $name, '$name' );

		if ( !$this->hasService( $name ) ) {
			throw new RuntimeException( 'Service not defined: ' . $name );
		}

		if ( isset( $this->services[$name] ) ) {
			throw new RuntimeException( 'Cannot redefine a service that is already in use: ' . $name );
		}

		$this->serviceInstantiators[$name] = $instantiator;
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
	 * @see redefineService().
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
		if ( isset( $this->serviceInstantiators[$name] ) ) {
			$service = call_user_func_array(
				$this->serviceInstantiators[$name],
				array_merge( [ $this ], $this->extraInstantiationParams )
			);
		} else {
			throw new InvalidArgumentException( 'Unknown service: ' . $name );
		}

		return $service;
	}

}
