<?php
/**
 * Abstract class to construct tests for ORMTable deriving classes.
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
 * @since 1.21
 *
 * @ingroup Test
 *
 * @group ORM
 * @group Database
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 * @author Daniel Kinzler
 */
class ORMTableTest extends MediaWikiTestCase {

	/**
	 * @since 1.21
	 * @return string
	 */
	protected function getTableClass() {
		return 'PageORMTableForTesting';
	}

	/**
	 * @since 1.21
	 * @return IORMTable
	 */
	public function getTable() {
		$class = $this->getTableClass();

		return $class::singleton();
	}

	/**
	 * @since 1.21
	 * @return string
	 */
	public function getRowClass() {
		return $this->getTable()->getRowClass();
	}

	/**
	 * @since 1.21
	 */
	public function testSingleton() {
		$class = $this->getTableClass();

		$this->assertInstanceOf( $class, $class::singleton() );
		$this->assertTrue( $class::singleton() === $class::singleton() );
	}

	/**
	 * @since 1.21
	 */
	public function testIgnoreErrorsOverride() {
		$table = $this->getTable();

		$db = $table->getReadDbConnection();
		$db->ignoreErrors( true );

		try {
			$table->rawSelect( "this is invalid" );
			$this->fail( "An invalid query should trigger a DBQueryError even if ignoreErrors is enabled." );
		} catch ( DBQueryError $ex ) {
			$this->assertTrue( true, "just making phpunit happy" );
		}

		$db->ignoreErrors( false );
	}
}

/**
 * Dummy ORM table for testing, reading Title objects from the page table.
 *
 * @since 1.21
 */

class PageORMTableForTesting extends ORMTable {

	/**
	 * @see ORMTable::getName
	 *
	 * @return string
	 */
	public function getName() {
		return 'page';
	}

	/**
	 * @see ORMTable::getRowClass
	 *
	 * @return string
	 */
	public function getRowClass() {
		return 'Title';
	}

	/**
	 * @see ORMTable::newRow
	 *
	 * @return IORMRow
	 */
	public function newRow( array $data, $loadDefaults = false ) {
		return Title::makeTitle( $data['namespace'], $data['title'] );
	}

	/**
	 * @see ORMTable::getFields
	 *
	 * @return array
	 */
	public function getFields() {
		return array(
			'id' => 'int',
			'namespace' => 'int',
			'title' => 'str',
		);
	}

	/**
	 * @see ORMTable::getFieldPrefix
	 *
	 * @return string
	 */
	protected function getFieldPrefix() {
		return 'page_';
	}
}
