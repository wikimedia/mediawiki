<?php

/**
 * Abstract class to construct tests for ORMRow deriving classes.
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
 * @group ORM
 *
 * The database group has as a side effect that temporal database tables are created. This makes
 * it possible to test without poisoning a production database.
 * @group Database
 *
 * Some of the tests takes more time, and needs therefor longer time before they can be aborted
 * as non-functional. The reason why tests are aborted is assumed to be set up of temporal databases
 * that hold the first tests in a pending state awaiting access to the database.
 * @group medium
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
abstract class ORMRowTest extends \MediaWikiTestCase {

	/**
	 * @since 1.20
	 * @return string
	 */
	protected abstract function getRowClass();

	/**
	 * @since 1.20
	 * @return IORMTable
	 */
	protected abstract function getTableInstance();

	/**
	 * @since 1.20
	 * @return array
	 */
	public abstract function constructorTestProvider();

	/**
	 * @since 1.20
	 * @param IORMRow $row
	 * @param array $data
	 */
	protected function verifyFields( IORMRow $row, array $data ) {
		foreach ( array_keys( $data ) as $fieldName ) {
			$this->assertEquals( $data[$fieldName], $row->getField( $fieldName ) );
		}
	}

	/**
	 * @since 1.20
	 * @param array $data
	 * @param boolean $loadDefaults
	 * @return IORMRow
	 */
	protected function getRowInstance( array $data, $loadDefaults ) {
		$class = $this->getRowClass();
		return new $class( $this->getTableInstance(), $data, $loadDefaults );
	}

	/**
	 * @since 1.20
	 * @return array
	 */
	protected function getMockValues() {
		return array(
			'id' => 1,
			'str' => 'foobar4645645',
			'int' => 42,
			'float' => 4.2,
			'bool' => true,
			'array' => array( 42, 'foobar' ),
			'blob' => new stdClass()
		);
	}

	/**
	 * @since 1.20
	 * @return array
	 */
	protected function getMockFields() {
		$mockValues = $this->getMockValues();
		$mockFields = array();

		foreach ( $this->getTableInstance()->getFields() as $name => $type ) {
			if ( $name !== 'id' ) {
				$mockFields[$name] = $mockValues[$type];
			}
		}

		return $mockFields;
	}

	/**
	 * @since 1.20
	 * @return array of IORMRow
	 */
	public function instanceProvider() {
		$instances = array();

		foreach ( $this->constructorTestProvider() as $arguments ) {
			$instances[] = array( call_user_func_array( array( $this, 'getRowInstance' ), $arguments ) );
		}

		return $instances;
	}

	/**
	 * @dataProvider constructorTestProvider
	 */
	public function testConstructor( array $data, $loadDefaults ) {
		$this->verifyFields( $this->getRowInstance( $data, $loadDefaults ), $data );
	}

	/**
	 * @dataProvider constructorTestProvider
	 */
	public function testSave( array $data, $loadDefaults ) {
		$item = $this->getRowInstance( $data, $loadDefaults );

		$this->assertTrue( $item->save() );

		$this->assertTrue( $item->hasIdField() );
		$this->assertTrue( is_integer( $item->getId() ) );

		$id = $item->getId();

		$this->assertTrue( $item->save() );

		$this->assertEquals( $id, $item->getId() );

		$this->verifyFields( $item, $data );
	}

	/**
	 * @dataProvider constructorTestProvider
	 */
	public function testRemove( array $data, $loadDefaults ) {
		$item = $this->getRowInstance( $data, $loadDefaults );

		$this->assertTrue( $item->save() );

		$this->assertTrue( $item->remove() );

		$this->assertFalse( $item->hasIdField() );

		$this->assertTrue( $item->save() );

		$this->verifyFields( $item, $data );

		$this->assertTrue( $item->remove() );

		$this->assertFalse( $item->hasIdField() );

		$this->verifyFields( $item, $data );
	}

	/**
	 * @dataProvider instanceProvider
	 */
	public function testSetField( IORMRow $item ) {
		foreach ( $this->getMockFields() as $name => $value ) {
			$item->setField( $name, $value );
			$this->assertEquals( $value, $item->getField( $name ) );
		}
	}

	/**
	 * @since 1.20
	 * @param array $expected
	 * @param IORMRow $item
	 */
	protected function assertFieldValues( array $expected, IORMRow $item ) {
		foreach ( $expected as $name => $type ) {
			if ( $name !== 'id' ) {
				$this->assertEquals( $expected[$name], $item->getField( $name ) );
			}
		}
	}

	/**
	 * @dataProvider instanceProvider
	 */
	public function testSetFields( IORMRow $item ) {
		$originalValues = $item->getFields();

		$item->setFields( array(), false );

		foreach ( $item->getTable()->getFields() as $name => $type ) {
			$originalHas = array_key_exists( $name, $originalValues );
			$newHas = $item->hasField( $name );

			$this->assertEquals( $originalHas, $newHas );

			if ( $originalHas && $newHas ) {
				$this->assertEquals( $originalValues[$name], $item->getField( $name ) );
			}
		}

		$mockFields = $this->getMockFields();

		$item->setFields( $mockFields, false );

		$this->assertFieldValues( $originalValues, $item );

		$item->setFields( $mockFields, true );

		$this->assertFieldValues( $mockFields, $item );
	}

	// TODO: test all of the methods!

}