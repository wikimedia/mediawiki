<?php

/**
 * Holds tests for ChronologyProtector abstract MediaWiki class.
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

use Wikimedia\Rdbms\ChronologyProtector;

/**
 * @group Database
 * @covers \Wikimedia\Rdbms\ChronologyProtector::__construct
 * @covers \Wikimedia\Rdbms\ChronologyProtector::getClientId
 */
class ChronologyProtectorTest extends PHPUnit\Framework\TestCase {
	/**
	 * @dataProvider clientIdProvider
	 * @param array $client
	 * @param string $secret
	 * @param string $expectedId
	 */
	public function testClientId( array $client, $secret, $expectedId ) {
		$bag = new HashBagOStuff();
		$cp = new ChronologyProtector( $bag, $client, null, $secret );

		$this->assertEquals( $expectedId, $cp->getClientId() );
	}

	public function clientIdProvider() {
		return [
			[
				[
					'ip' => '127.0.0.1',
					'agent' => "Totally-Not-FireFox"
				],
				'',
				'45e93a9c215c031d38b7c42d8e4700ca',
			],
			[
				[
					'ip' => '127.0.0.7',
					'agent' => "Totally-Not-FireFox"
				],
				'',
				'b1d604117b51746c35c3df9f293c84dc'
			],
			[
				[
					'ip' => '127.0.0.1',
					'agent' => "Totally-FireFox"
				],
				'',
				'731b4e06a65e2346b497fc811571c4d7'
			],
			[
				[
					'ip' => '127.0.0.1',
					'agent' => "Totally-Not-FireFox"
				],
				'secret',
				'defff51ded73cd901253d874c9b2077d'
			]
		];
	}
}
