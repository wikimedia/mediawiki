<?php
/**
 * Testing secret store
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

class SecretStoreTest extends MediaWikiTestCase {

	/**
	 * @covers SecretStore::updateForKey
	 * @covers SecretStore::getInstance
	 */
	public function testUpdateForKey() {
		$ssConfig = array(
			'class' => 'SecretStoreAesCtrSha256',
			'secrets' => array( 'foo', 'bar', 'baz' ),
			'defaultSecret' => 0,
		);
		$ss = SecretStore::getInstance( $ssConfig );
		$sealed = $ss->seal( 'another secret', 'test-secret', 42 );
		$this->assertEquals( 3, count( explode( '.', $sealed ) ) );
		$resealed = $ss->updateForKey( 1, $sealed, 'test-secret', 42 );
		$pieces = explode( '.', $resealed );
		$this->assertEquals( 3, count( $pieces ) );
		$this->assertEquals( '1', $pieces[0] );
		$this->assertNotEquals( $sealed, $resealed );
		$this->assertEquals( 'another secret', $ss->unseal( $resealed, 'test-secret', 42 ) );
	}

}
