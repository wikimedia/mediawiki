<?php
/**
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
 * @since 1.42
 */

namespace MediaWiki\Tests\Unit\Permissions;

use MediaWiki\Permissions\RateLimitSubject;
use MediaWiki\User\UserIdentityValue;
use MediaWikiUnitTestCase;

/**
 * @group Permissions
 * @covers \MediaWiki\Permissions\RateLimitSubject
 */
class RateLimitSubjectTest extends MediaWikiUnitTestCase {

	/**
	 * Provide data for testConstructorAndGetters
	 */
	public static function provideIp(): array {
		return [
			'null' => [ null, true ],
			'empty' => [ '', true ],
			'ipv4' => [ '10.0.0.1', true ],
			'ipv6' => [ '2001:db8:1::', true ],
		];
	}

	/**
	 * Test that constructing a RateLimitSubject with different IPs and then fetching properties through getters
	 * returns the values passed to the constructor.
	 * @dataProvider provideIp
	 */
	public function testConstructorAndGetters( $ip, $expected ) {
		$actor = new UserIdentityValue( 12, 'Test' );
		$flags = [ 'foo' => true, 'bar' => false ];

		$subject = new RateLimitSubject( $actor, $ip, $flags );
		$this->assertInstanceOf( RateLimitSubject::class, $subject );

		$this->assertSame( $actor, $subject->getUser(), 'The getUser method did not return the expected actor.' );
		$this->assertSame( $expected ? $ip : null, $subject->getIp(),
			'The getIp method did not return the expected IP.' );
		$this->assertTrue( $subject->is( 'foo' ), 'The is method did not return true for flag "foo".' );
		$this->assertFalse( $subject->is( 'bar' ), 'The is method did not return false for flag "bar".' );
		$this->assertFalse( $subject->is( 'baz' ),
			'The is method returned true for an undefined flag "baz", expected false.' );
	}

}
