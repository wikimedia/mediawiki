<?php

/**
 * Various HTTP related functions.
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
 */
use Wikimedia\Assert\Assert;

/**
 * A helper class implementing a lazy-initialization registry of singletons.
 * This is intended to be used inside more specialized registries / factories.
 */
class LazyRegistry {

	/**
	 * @var string
	 */
	private $instanceType;

	/**
	 * @var callable[] by name
	 */
	private $constructors;

	/**
	 * @var array
	 */
	private $constructorParameters;

	/**
	 * @var object[] by name
	 */
	private $instances = array();

	/**
	 * @param string $instanceType Name of the class instances in this registry are expected to have.
	 * @param callable[] $constructors Constructor callbacks, by name. Will be called with $constructorParameters.
	 * @param array $constructorParameters The parameters to pass to the constructor callbacks given in $constructors.
	 */
	public function __construct( $instanceType, array $constructors, array $constructorParameters = array() ) {
		Assert::parameterElementType( 'callable', $constructors, '$constructors' );

		$this->constructors = $constructors;
		$this->constructorParameters = $constructorParameters;
		$this->instanceType = $instanceType;
	}

	/**
	 * @param string $name
	 *
	 * @return T
	 */
	public function getInstance( $name ) {
		if ( !isset( $this->instances[$name] ) ) {
			$this->instances[$name] = $this->newInstance( $name );
		}

		return $this->instances[$name];
	}

	/**
	 * @param $name
	 *
	 * @throws MWException
	 * @return T
	 */
	private function newInstance( $name ) {
		if ( !isset( $this->constructors[$name] ) ) {
			throw new OutOfBoundsException( 'No constructor registered for ' . $name );
		}

		$instance = call_user_func_array( $name, $this->constructorParameters );

		Assert::postcondition(
			is_subclass_of( $instance, $this->instanceType ),
			"Constructor for $name returned an instance incompatible with {$this->instanceType}: "
				. get_class( $instance)
		);

		return $instance;
	}

}
