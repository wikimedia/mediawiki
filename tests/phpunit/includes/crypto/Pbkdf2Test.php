<?php
/**
 * IETF test vectors for PBKDF2 https://www.ietf.org/rfc/rfc6070.txt
 * This is meant to test mediawiki's php implementation of pbkdf2,
 * hash_pbkdf2 should just work.
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


class Pbkdf2Test extends MediaWikiTestCase {

	/**
	 * @covers Pbkdf2::hash
	 * @dataProvider providePbkdf2Tests
	 */
	public function testHash( $algo, $password, $salt, $rounds, $length, $output ) {

		$this->assertSame(
			$output,
			bin2hex( Pbkdf2::hash( $algo, $password, $salt, $rounds, $length ) )
		);
	}

	public function providePbkdf2Tests() {
		return array(
			array(
				'sha1',
				'password',
				'salt',
				1,
				20,
				'0c60c80f961f0e71f3a9b524af6012062fe037a6'
			),
			array(
				'sha1',
				'password',
				'salt',
				2,
				20,
				'ea6c014dc72d6f8ccd1ed92ace1d41f0d8de8957'
			),
			array(
				'sha1',
				'password',
				'salt',
				4096,
				20,
				'4b007901b765489abead49d926f721d065a429c1'
			),
			/* takes about 25 seconds to run
			array(
				'sha1',
				'password',
				'salt',
				16777216,
				20,
				'eefe3d61cd4da4e4e9945b3d6ba2158c2634e984'
			),
			*/
			array(
				'sha1',
				'passwordPASSWORDpassword',
				'saltSALTsaltSALTsaltSALTsaltSALTsalt',
				4096,
				25,
				'3d2eec4fe41c849b80c8d83662c0e44a8b291a964cf2f07038'
			),
			array(
				'sha1',
				'passwordPASSWORDpassword',
				'saltSALTsaltSALTsaltSALTsaltSALTsalt',
				4096,
				25,
				'3d2eec4fe41c849b80c8d83662c0e44a8b291a964cf2f07038'
			),

			array(
				'sha1',
				'passwordPASSWORDpassword',
				'saltSALTsaltSALTsaltSALTsaltSALTsalt',
				4096,
				25,
				'3d2eec4fe41c849b80c8d83662c0e44a8b291a964cf2f07038'
			),
			array(
				'sha1',
				"pass\0word",
				"sa\0lt",
				4096,
				16,
				'56fa6aa75548099dcc37d7f03425e0c3'
			),
		);
	}

}
