<?php
namespace Wikimedia\Services;

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
class ServiceContainer implements DestructibleService {

	/**
	 * @var object[]
	 */
	private $services = [];

	/**
	 * @var callable[]
	 */
	private $serviceInstantiators = [];

	/**
	 * @var callable[][]
	 */
	private $serviceManipulators = [];

	/**
	 * @var bool[] disabled status, per service name
	 */
	private $disabled = [];

	/**
	 * @var array
	 */
	private $extraInstantiationParams;

	/**
	 * @var bool
	 */
	private $destroyed = false;

	/**
	 * @param array $extraInstantiationParams Any additional parameters to be passed to the
	 * instantiator function when creating a service. This is typically used to provide
	 * access to additional ServiceContainers or Config objects.
	 */
	public function __construct( array $extraInstantiationParams = [] ) {
		$this->extraInstantiationParams = $extraInstantiationParams;
	}

	/**
	 * Destroys all contained service instances that implement the DestructibleService
	 * interface. This will render all services obtained from this ServiceContainer
	 * instance unusable. In particular, this will disable access to the storage backend
	 * via any of these services. Any future call to getService() will throw an exception.
	 *
	 * @see resetGlobalInstance()
	 */
	public function destroy() {
		foreach ( $this->getServiceNames() as $name ) {
			$service = $this->peekService( $name );
			if ( $service !== null && $service instanceof DestructibleService ) {
				$service->destroy();
			}
		}

		// Break circular references due to the $this reference in closures, by
		// erasing the instantiator array. This allows the ServiceContainer to
		// be deleted when it goes out of scope.
		$this->serviceInstantiators = [];
		// Also remove the services themselves, to avoid confusion.
		$this->services = [];
		$this->destroyed = true;
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
	 * Imports all wiring defined in $container. Wiring defined in $container
	 * will override any wiring already defined locally. However, already
	 * existing service instances will be preserved.
	 *
	 * @since 1.28
	 *
	 * @param ServiceContainer $container
	 * @param string[] $skip A list of service names to skip during import
	 */
	public function importWiring( ServiceContainer $container, $skip = [] ) {
		$newInstantiators = array_diff_key(
			$container->serviceInstantiators,
			array_flip( $skip )
		);

		$this->serviceInstantiators = array_merge(
			$this->serviceInstantiators,
			$newInstantiators
		);

		$newManipulators = array_diff(
			array_keys( $container->serviceManipulators ),
			$skip
		);

		foreach ( $newManipulators as $name ) {
			if ( isset( $this->serviceManipulators[$name] ) ) {
				$this->serviceManipulators[$name] = array_merge(
					$this->serviceManipulators[$name],
					$container->serviceManipulators[$name]
				);
			} else {
				$this->serviceManipulators[$name] = $container->serviceManipulators[$name];
			}
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
	 * Returns the service instance for $name only if that service has already been instantiated.
	 * This is intended for situations where services get destroyed/cleaned up, so we can
	 * avoid creating a service just to destroy it again.
	 *
	 * @note This is intended for internal use and for test fixtures.
	 * Application logic should use getService() instead.
	 *
	 * @see getService().
	 *
	 * @param string $name
	 *
	 * @return object|null The service instance, or null if the service has not yet been instantiated.
	 * @throws RuntimeException if $name does not refer to a known service.
	 */
	public function peekService( $name ) {
		if ( !$this->hasService( $name ) ) {
			throw new NoSuchServiceException( $name );
		}

		return $this->services[$name] ?? null;
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
	 * @see redefineService().
	 *
	 * @param string $name The name of the service to register, for use with getService().
	 * @param callable $instantiator Callback that returns a service instance.
	 *        Will be called with this ServiceContainer instance as the only parameter.
	 *        Any extra instantiation parameters provided to the constructor will be
	 *        passed as subsequent parameters when invoking the instantiator.
	 *
	 * @throws RuntimeException if there is already a service registered as $name.
	 */
	public function defineService( $name, callable $instantiator ) {
		Assert::parameterType( 'string', $name, '$name' );

		if ( $this->hasService( $name ) ) {
			throw new ServiceAlreadyDefinedException( $name );
		}

		$this->serviceInstantiators[$name] = $instantiator;
	}

	/**
	 * Replace an already defined service.
	 *
	 * @see defineService().
	 *
	 * @note This will fail if the service was already instantiated. If the service was previously
	 * disabled, it will be re-enabled by this call. Any manipulators registered for the service
	 * will remain in place.
	 *
	 * @param string $name The name of the service to register.
	 * @param callable $instantiator Callback function that returns a service instance.
	 *        Will be called with this ServiceContainer instance as the only parameter.
	 *        The instantiator must return a service compatible with the originally defined service.
	 *        Any extra instantiation parameters provided to the constructor will be
	 *        passed as subsequent parameters when invoking the instantiator.
	 *
	 * @throws NoSuchServiceException if $name is not a known service.
	 * @throws CannotReplaceActiveServiceException if the service was already instantiated.
	 */
	public function redefineService( $name, callable $instantiator ) {
		Assert::parameterType( 'string', $name, '$name' );

		if ( !$this->hasService( $name ) ) {
			throw new NoSuchServiceException( $name );
		}

		if ( isset( $this->services[$name] ) ) {
			throw new CannotReplaceActiveServiceException( $name );
		}

		$this->serviceInstantiators[$name] = $instantiator;
		unset( $this->disabled[$name] );
	}

	/**
	 * Add a service manipulator callback for the given service.
	 * This method may be used by extensions that need to wrap, replace, or re-configure a
	 * service. It would typically be called from a MediaWikiServices hook handler.
	 *
	 * The manipulator callback is called just after the service is instantiated.
	 * It can call methods on the service to change configuration, or wrap or otherwise
	 * replace it.
	 *
	 * @see defineService().
	 * @see redefineService().
	 *
	 * @note This will fail if the service was already instantiated.
	 *
	 * @since 1.32
	 *
	 * @param string $name The name of the service to manipulate.
	 * @param callable $manipulator Callback function that manipulates, wraps or replaces a
	 * service instance. The callback receives the new service instance and this
	 * ServiceContainer as parameters, as well as any extra instantiation parameters specified
	 * when constructing this ServiceContainer. If the callback returns a value, that
	 * value replaces the original service instance.
	 *
	 * @throws NoSuchServiceException if $name is not a known service.
	 * @throws CannotReplaceActiveServiceException if the service was already instantiated.
	 */
	public function addServiceManipulator( $name, callable $manipulator ) {
		Assert::parameterType( 'string', $name, '$name' );

		if ( !$this->hasService( $name ) ) {
			throw new NoSuchServiceException( $name );
		}

		if ( isset( $this->services[$name] ) ) {
			throw new CannotReplaceActiveServiceException( $name );
		}

		$this->serviceManipulators[$name][] = $manipulator;
	}

	/**
	 * Disables a service.
	 *
	 * @note Attempts to call getService() for a disabled service will result
	 * in a DisabledServiceException. Calling peekService for a disabled service will
	 * return null. Disabled services are listed by getServiceNames(). A disabled service
	 * can be enabled again using redefineService().
	 *
	 * @note If the service was already active (that is, instantiated) when getting disabled,
	 * and the service instance implements DestructibleService, destroy() is called on the
	 * service instance.
	 *
	 * @see redefineService()
	 * @see resetService()
	 *
	 * @param string $name The name of the service to disable.
	 *
	 * @throws RuntimeException if $name is not a known service.
	 */
	public function disableService( $name ) {
		$this->resetService( $name );

		$this->disabled[$name] = true;
	}

	/**
	 * Resets a service by dropping the service instance.
	 * If the service instances implements DestructibleService, destroy()
	 * is called on the service instance.
	 *
	 * @warning This is generally unsafe! Other services may still retain references
	 * to the stale service instance, leading to failures and inconsistencies. Subclasses
	 * may use this method to reset specific services under specific instances, but
	 * it should not be exposed to application logic.
	 *
	 * @note This is declared final so subclasses can not interfere with the expectations
	 * disableService() has when calling resetService().
	 *
	 * @see redefineService()
	 * @see disableService().
	 *
	 * @param string $name The name of the service to reset.
	 * @param bool $destroy Whether the service instance should be destroyed if it exists.
	 *        When set to false, any existing service instance will effectively be detached
	 *        from the container.
	 *
	 * @throws RuntimeException if $name is not a known service.
	 */
	final protected function resetService( $name, $destroy = true ) {
		Assert::parameterType( 'string', $name, '$name' );

		$instance = $this->peekService( $name );

		if ( $destroy && $instance instanceof DestructibleService ) {
			$instance->destroy();
		}

		unset( $this->services[$name] );
		unset( $this->disabled[$name] );
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
	 * @throws NoSuchServiceException if $name is not a known service.
	 * @throws ContainerDisabledException if this container has already been destroyed.
	 * @throws ServiceDisabledException if the requested service has been disabled.
	 *
	 * @return object The service instance
	 */
	public function getService( $name ) {
		if ( $this->destroyed ) {
			throw new ContainerDisabledException();
		}

		if ( isset( $this->disabled[$name] ) ) {
			throw new ServiceDisabledException( $name );
		}

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
			$service = ( $this->serviceInstantiators[$name] )(
				$this,
				...$this->extraInstantiationParams
			);

			if ( isset( $this->serviceManipulators[$name] ) ) {
				foreach ( $this->serviceManipulators[$name] as $callback ) {
					$ret = call_user_func_array(
						$callback,
						array_merge( [ $service, $this ], $this->extraInstantiationParams )
					);

					// If the manipulator callback returns an object, that object replaces
					// the original service instance. This allows the manipulator to wrap
					// or fully replace the service.
					if ( $ret !== null ) {
						$service = $ret;
					}
				}
			}

			// NOTE: when adding more wiring logic here, make sure importWiring() is kept in sync!
		} else {
			throw new NoSuchServiceException( $name );
		}

		return $service;
	}

	/**
	 * @param string $name
	 * @return bool Whether the service is disabled
	 * @since 1.28
	 */
	public function isServiceDisabled( $name ) {
		return isset( $this->disabled[$name] );
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.33
 */
class_alias( ServiceContainer::class, 'MediaWiki\Services\ServiceContainer' );
