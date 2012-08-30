<?php

/**
 * Tests for the GenericArrayObject and deriving classes.
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
 * @group GenericArrayObject
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
abstract class GenericArrayObjectTest extends MediaWikiTestCase {

	/**
	 * Returns objects that can serve as elements in the concrete GenericArrayObject deriving class being tested.
	 *
	 * @since 1.20
	 *
	 * @return array
	 */
	public abstract function elementInstancesProvider();

	/**
	 * Returns the name of the concrete class being tested.
	 *
	 * @since 1.20
	 *
	 * @return string
	 */
	public abstract function getInstanceClass();

	/**
	 * Provides instances of the concrete class being tested.
	 *
	 * @since 1.20
	 *
	 * @return array
	 */
	public function instanceProvider() {
		$instances = array();

		foreach ( $this->elementInstancesProvider() as $elementInstances ) {
			$instances[] = $this->getNew( $elementInstances );
		}

		return $this->arrayWrap( $instances );
	}

	/**
	 * @since 1.20
	 *
	 * @param array $elements
	 *
	 * @return GenericArrayObject
	 */
	protected function getNew( array $elements = array() ) {
		$class = $this->getInstanceClass();
		return new $class( $elements );
	}

	/**
	 * @dataProvider elementInstancesProvider
	 *
	 * @since 1.20
	 *
	 * @param array $elements
	 */
	public function testConstructor( array $elements ) {
		$arrayObject = $this->getNew( $elements );

		$this->assertEquals( count( $elements ), $arrayObject->count() );
	}

	/**
	 * @dataProvider elementInstancesProvider
	 *
	 * @since 1.20
	 *
	 * @param array $elements
	 */
	public function testIsEmpty( array $elements ) {
		$arrayObject = $this->getNew( $elements );

		$this->assertEquals( $elements === array(), $arrayObject->isEmpty() );
	}

	/**
	 * @dataProvider instanceProvider
	 *
	 * @since 1.20
	 *
	 * @param GenericArrayObject $list
	 */
	public function testUnset( GenericArrayObject $list ) {
		if ( !$list->isEmpty() ) {
			$offset = $list->getIterator()->key();
			$count = $list->count();
			$list->offsetUnset( $offset );
			$this->assertEquals( $count - 1, $list->count() );
		}

		if ( !$list->isEmpty() ) {
			$offset = $list->getIterator()->key();
			$count = $list->count();
			unset( $list[$offset] );
			$this->assertEquals( $count - 1, $list->count() );
		}

		$exception = null;
		try { $list->offsetUnset( 'sdfsedtgsrdysftu' ); } catch ( \Exception $exception ){}
		$this->assertInstanceOf( '\Exception', $exception );
	}

	/**
	 * @dataProvider elementInstancesProvider
	 *
	 * @since 1.20
	 *
	 * @param array $elements
	 */
	public function testAppend( array $elements ) {
		$list = $this->getNew();

		$listSize = count( $elements );

		foreach ( $elements as $element ) {
			$list->append( $element );
		}

		$this->assertEquals( $listSize, $list->count() );

		$list = $this->getNew();

		foreach ( $elements as $element ) {
			$list[] = $element;
		}

		$this->assertEquals( $listSize, $list->count() );

		$this->checkTypeChecks( function( GenericArrayObject $list, $element ) {
			$list->append( $element );
		} );
	}

	/**
	 * @since 1.20
	 *
	 * @param callback $function
	 */
	protected function checkTypeChecks( $function ) {
		$excption = null;
		$list = $this->getNew();

		$elementClass = $list->getObjectType();

		foreach ( array( 42, 'foo', array(), new \stdClass(), 4.2 ) as $element ) {
			$validValid = $element instanceof $elementClass;

			try{
				call_user_func( $function, $list, $element );
				$valid = true;
			}
			catch ( InvalidArgumentException $exception ) {
				$valid = false;
			}

			$this->assertEquals(
				$validValid,
				$valid,
				'Object of invalid type got successfully added to a GenericArrayObject'
			);
		}
	}

	/**
	 * @dataProvider elementInstancesProvider
	 *
	 * @since 1.20
	 *
	 * @param array $elements
	 */
	public function testOffsetSet( array $elements ) {
		if ( $elements === array() ) {
			$this->assertTrue( true );
			return;
		}

		$list = $this->getNew();

		$element = reset( $elements );
		$list->offsetSet( 42, $element );
		$this->assertEquals( $element, $list->offsetGet( 42 ) );

		$list = $this->getNew();

		$element = reset( $elements );
		$list['oHai'] = $element;
		$this->assertEquals( $element, $list['oHai'] );

		$list = $this->getNew();

		$element = reset( $elements );
		$list->offsetSet( 9001, $element );
		$this->assertEquals( $element, $list[9001] );

		$list = $this->getNew();

		$element = reset( $elements );
		$list->offsetSet( null, $element );
		$this->assertEquals( $element, $list[0] );

		$list = $this->getNew();
		$offset = 0;

		foreach ( $elements as $element ) {
			$list->offsetSet( null, $element );
			$this->assertEquals( $element, $list[$offset++] );
		}

		$this->assertEquals( count( $elements ), $list->count() );

		$this->checkTypeChecks( function( GenericArrayObject $list, $element ) {
			$list->offsetSet( mt_rand(), $element );
		} );
	}

}
