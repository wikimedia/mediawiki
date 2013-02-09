<?php

/**
 * Tests for the LazyDBConnectionProvider class.
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
 * @group Database
 * @group LazyDBConnectionProviderTest
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class LazyDBConnectionProviderTest extends MediaWikiTestCase {

	public function constructorProvider() {
		$dbIds = array(
			DB_MASTER,
			DB_SLAVE,
		);

		return $this->arrayWrap( $dbIds );
	}

	/**
	 * @dataProvider constructorProvider
	 *
	 * @param int $dbId
	 */
	public function testConstructor( $dbId ) {
		new LazyDBConnectionProvider( $dbId );

		$this->assertTrue( true );
	}

	public function instanceProvider() {
		$instances = array();

		$instances[] = new LazyDBConnectionProvider( DB_MASTER );
		$instances[] = new LazyDBConnectionProvider( DB_SLAVE );

		return $this->arrayWrap( $instances );
	}

	/**
	 * @dataProvider instanceProvider
	 *
	 * @param DBConnectionProvider $connProvider
	 */
	public function testGetConnection( DBConnectionProvider $connProvider ) {
		$connection = $connProvider->getConnection();

		$this->assertInstanceOf(
			'DatabaseBase',
			$connProvider->getConnection(),
			'The connection is an instance of DatabaseBase when obtaining it after object construction'
		);

		$this->assertTrue(
			$connection === $connProvider->getConnection(),
			'The connection remains the same for successive calls'
		);

		$connProvider->releaseConnection();

		$this->assertInstanceOf(
			'DatabaseBase',
			$connProvider->getConnection(),
			'The connection is an instance of DatabaseBase when obtaining it after doing a connection release'
		);
	}

}
