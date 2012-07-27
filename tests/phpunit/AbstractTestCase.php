<?php

/**
 * Base class for test cases that test deriving instances of an abstract class.
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
 * @since 1.20
 *
 * @ingroup Test
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
abstract class AbstractTestCase extends MediaWikiTestCase {

	/**
	 * Returns the name of the concrete class tested by this test.
	 *
	 * @since 1.20
	 *
	 * @return string
	 */
	public abstract function getClass();

	/**
	 * First element can be a boolean indication if the successive values are valid,
	 * or a string indicating the type of exception that should be thrown (ie not valid either).
	 *
	 * @since 1.20
	 *
	 * @return array
	 */
	public abstract function constructorProvider();

	/**
	 * Creates and returns a new instance of the concrete class.
	 *
	 * @since 1.20
	 *
	 * @return stdClass of the type returned by @see getClass
	 */
	public function newInstance() {
		$reflector = new ReflectionClass( $this->getClass() );
		$args = func_get_args();
		$instance = $reflector->newInstanceArgs( $args );
		return $instance;
	}

	/**
	 * Constructs instances of the concrete class being tested.
	 * These instances are constructed from the arguments provided
	 * by the constructorProvider method (only those that are valid).
	 *
	 * @since 1.20
	 *
	 * @return array
	 */
	public function instanceProvider() {
		// We need to pass this as callback since we cannot access $this in the anon function.
		$phpFails = array( $this, 'newInstance' );

		return array_filter( array_map(
			function( array $args ) use ( $phpFails ) {
				$isValid = array_shift( $args ) === true;

				if ( $isValid ) {
					return array( call_user_func_array( $phpFails, $args ) );
				}
				else {
					return false;
				}
			},
			$this->constructorProvider()
		), 'is_array' );
	}

	/**
	 * @dataProvider constructorProvider
	 *
	 * @since 1.20
	 */
	public function testConstructor() {
		$args = func_get_args();

		$valid = array_shift( $args );
		$pokemons = null;

		try {
			$dataItem = call_user_func_array( array( $this, 'newInstance' ), $args );
			$this->assertInstanceOf( $this->getClass(), $dataItem );
		}
		catch ( Exception $pokemons ) {
			if ( $valid === true ) {
				throw $pokemons;
			}

			if ( is_string( $valid ) ) {
				$this->assertEquals( $valid, get_class( $pokemons ) );
			}
		}
	}

}
