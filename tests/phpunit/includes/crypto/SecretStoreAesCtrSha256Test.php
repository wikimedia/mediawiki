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

class SecretStoreAesCtrSha256Test extends MediaWikiTestCase {

	/**
	 * @covers SecretStoreAesCtrSha256::seal
	 */
	public function testSeal() {
		$ss = new SecretStoreAesCtrSha256(
			array(
				'2015' => 'foo1',
				'2015' => 'bar2',
				'2016' => 'baz3',
			),
			'2015'
		);
		$sealed = $ss->seal( 'something secret', 'test-secret', 1 );
		# Non-deterministic, so let's make sure it looks about right
		$this->assertEquals( 3, count( explode( '.', $sealed ) ) );
		$this->assertEquals( 'something secret', $ss->unseal( $sealed, 'test-secret', 1 ) );
	}

	/**
	 * @covers SecretStoreAesCtrSha256::unseal
	 */
	public function testUnseal() {
		$ss = new SecretStoreAesCtrSha256( array( 'foo', 'bar', 'baz' ), 0 );
		$sealed = "2.sza2nZRR4c+WjbSS11lRaw==.IXuvYVJ5njSDch78F9YV0KZjyopqQRA5jOa9Zb+1MpsZwfbzbog=";
		$this->assertEquals( 'SECRET', $ss->unseal( $sealed, 'test-secret', 2001 ) );
	}

	/**
	 * @expectedException Exception
	 */
	public function testTamperDetection() {
		$ss = new SecretStoreAesCtrSha256( array( 'foo', 'bar', 'baz' ), 0 );
		$sealed = "2.sza2nZRR4c+WjbSS11lRaw==.IXuwYVJ5njSDch78F9YV0KZjyopqQRA5jOa9Zb+1MpsZwfbzbog=";
		$this->assertEquals( 'SECRET', $ss->unseal( $sealed, 'test-secret', 2001 ) );
	}

}
