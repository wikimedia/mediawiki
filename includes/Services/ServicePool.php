<?php
namespace MediaWiki\Services;

use InvalidArgumentException;
use RuntimeException;
use Wikimedia\Assert\Assert;

/**
 * Generic service pool.
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
 * ServicePool provides a generic service to manage parametrized services using
 * lazy instantiation based on a single constructor callback used of all services.
 *
 * Services managed by an instance of ServicePool are expected to implement a common
 * interface.
 *
 * @note When using ServiceContainer to manage a set of services, consider
 * creating a wrapper or a subclass that provides access to the services via
 * getter methods with more meaningful names and more specific return type
 * declarations.
 *
 * @see docs/injection.txt for an overview of using dependency injection in the
 *      MediaWiki code base.
 */
class ServicePool implements DestructibleService {

	/**
	 * @var object[]
	 */
	private $services = array();

	/**
	 * @var callable
	 */
	private $serviceConstructor;

	/**
	 * @var callable
	 */
	private $parameterIdentity;

	/**
	 * @var callable
	 */
	private $parameterNormalize;

	/**
	 * @var boolean
	 */
	private $destroyed = false;

	/**
	 * @param callable $serviceConstructor Callback that constructs a service based on the arguments
	 *        passed to getService(). Arguments are passed through.
	 * @param callable|null $parameterIdentity A function for calculating an identity value
	 *        (e.g. a hash) from the parameters passed to getService().
	 *        Will be called with the original parameters as an array.
	 * @param callable|null $parameterNormalize A function for normalizing the parameters passed to
	 *        getService(). Will be called with the original parameters as an array.
	 *        Shall return a normalized version of the parameter array.
	 */
	public function __construct(
		$serviceConstructor,
		$parameterIdentity = null,
		$parameterNormalize = null
	) {
		Assert::parameterType( 'callable', $serviceConstructor, '$serviceConstructor' );
		Assert::parameterType( 'callable|null', $parameterIdentity, '$parameterIdentity' );
		Assert::parameterType( 'callable|null', $parameterNormalize, '$parameterNormalize' );

		$this->serviceConstructor = $serviceConstructor;
		$this->parameterIdentity = $parameterIdentity;
		$this->parameterNormalize = $parameterNormalize;
	}

	/**
	 * Returns an ID based on the parameter array
	 *
	 * @param array $parameters
	 *
	 * @return string|int
	 */
	private function getParameterId( array $parameters ) {
		if ( $this->parameterIdentity ) {
			return call_user_func_array( $this->parameterIdentity, $parameters );
		} else {
			return sha1( serialize( $parameters ) );
		}
	}

	/**
	 * Applies normalization to the parameter array
	 *
	 * @param array $parameters
	 *
	 * @return array normalized version of $parameters
	 */
	private function normalizeParameters( array $parameters ) {
		if ( $this->parameterNormalize ) {
			return call_user_func( $this->parameterNormalize, $parameters );
		} else {
			return $parameters;
		}
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
	public function getService() {
		if ( $this->destroyed ) {
			throw new ContainerDisabledException();
		}

		$params = func_get_args();
		$params = $this->normalizeParameters( $params );
		$id = $this->getParameterId( $params );

		if ( !isset( $this->services[$id] ) ) {
			$this->services[$id] = $this->createService( $params );
		}

		return $this->services[$id];
	}

	/**
	 * @param array $parameters
	 *
	 * @return object
	 */
	private function createService( array $parameters ) {
		$service = call_user_func_array( $this->serviceConstructor, $parameters );
		return $service;
	}

	/**
	 * Destroys all contained service instances that implement the DestructibleService
	 * interface. This will render all services obtained from this ServicePool
	 * instance unusable. Any future call to getService() will throw an exception.
	 */
	public function destroy() {
		foreach ( $this->services as $service ) {
			if ( $service instanceof DestructibleService ) {
				$service->destroy();
			}
		}

		$this->destroyed = true;
		$this->services = array();
	}

	/**
	 * Returns all active (already instantiated) service instances.
	 *
	 * @return object[]
	 */
	public function getActiveInstances() {
		return $this->services;
	}

}
