<?php
namespace MediaWiki\Services;

use InvalidArgumentException;
use RuntimeException;
use Wikimedia\Assert\Assert;

/**
 * Service wiring manager.
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
 * ServiceWiring is a generic helper class for managing the constructor callbacks
 * (wiring) for a ServiceContainer or factory.
 *
 * @note ServiceWiring should be used as a helper for implementing service factories,
 * it should not be used as factory directly. Application logic should never access
 * a ServiceWiring object.
 *
 * @see docs/injection.txt for an overview of using dependency injection in the
 *      MediaWiki code base.
 */
class ServiceWiring {

	/**
	 * @var callable[]
	 */
	private $serviceConstructors = array();

	/**
	 * @var array
	 */
	private $instantiationParams;

	/**
	 * @param array $instantiationParams A list of parameters that should be
	 * passed to the constructor callback when instantiating a service. These
	 * parameters are passed before any additional parameters that may be
	 * provided to the createService() method.
	 */
	public function __construct( array $instantiationParams = array() ) {
		$this->instantiationParams = $instantiationParams;
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
	 *
	 * @throws RuntimeException if $name is not a known service.
	 */
	public function replaceService( $name, $constructor ) {
		Assert::parameterType( 'string', $name, '$name' );
		Assert::parameterType( 'callable', $constructor, '$constructor' );

		if ( !$this->hasService( $name ) ) {
			throw new RuntimeException( 'Service not defined: ' . $name );
		}

		$this->serviceConstructors[$name] = $constructor;
	}

	/**
	 * @param string $name
	 *
	 * @throws InvalidArgumentException if $name is not a known service.
	 * @return object
	 */
	public function createService( $name ) {
		if ( !isset( $this->serviceConstructors[$name] ) ) {
			throw new InvalidArgumentException( 'Unknown service: ' . $name );
		}

		$args = func_get_args();
		array_shift( $args ); // remove the first parameter, which is $name
		$args = array_merge( $this->instantiationParams, $args );

		$service = call_user_func_array(
			$this->serviceConstructors[$name],
			$args
		);

		return $service;
	}

}
