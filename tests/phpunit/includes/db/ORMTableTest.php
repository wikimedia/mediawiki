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
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
abstract class ORMTableTest extends MediaWikiTestCase {

	/**
	 * @since 1.21
	 * @return string
	 */
	protected abstract function getTableClass();

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

}
