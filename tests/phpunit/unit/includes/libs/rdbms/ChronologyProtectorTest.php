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

use PHPUnit\Framework\TestCase;
use Wikimedia\ObjectCache\HashBagOStuff;
use Wikimedia\Rdbms\ChronologyProtector;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\MySQLPrimaryPos;

/**
 * @covers \Wikimedia\Rdbms\ChronologyProtector
 */
class ChronologyProtectorTest extends TestCase {
	/**
	 * @dataProvider clientIdProvider
	 * @param array $client
	 * @param string $secret
	 * @param string $expectedId
	 */
	public function testClientId( array $client, $secret, $expectedId ) {
		$bag = new HashBagOStuff();
		$cp = new ChronologyProtector( $bag, $secret, false );
		$cp->setRequestInfo( $client );

		$this->assertEquals( $expectedId, $cp->getClientId() );
	}

	public static function clientIdProvider() {
		return [
			[
				[
					'IPAddress' => '127.0.0.1',
					'UserAgent' => "Totally-Not-FireFox"
				],
				'',
				'45e93a9c215c031d38b7c42d8e4700ca',
			],
			[
				[
					'IPAddress' => '127.0.0.7',
					'UserAgent' => "Totally-Not-FireFox"
				],
				'',
				'b1d604117b51746c35c3df9f293c84dc'
			],
			[
				[
					'IPAddress' => '127.0.0.1',
					'UserAgent' => "Totally-FireFox"
				],
				'',
				'731b4e06a65e2346b497fc811571c4d7'
			],
			[
				[
					'IPAddress' => '127.0.0.1',
					'UserAgent' => "Totally-Not-FireFox"
				],
				'secret',
				'defff51ded73cd901253d874c9b2077d'
			]
		];
	}

	/**
	 * @covers \Wikimedia\Rdbms\ChronologyProtector
	 * @covers \Wikimedia\Rdbms\MySQLPrimaryPos
	 */
	public function testPositionMarshalling() {
		$replicationPos = '1-2-3';
		$time = 100;

		$lb = $this->createMock( ILoadBalancer::class );
		$lb->method( 'getClusterName' )->willReturn( 'test' );
		$lb->method( 'getServerName' )->willReturn( 'primary' );
		$lb->method( 'hasOrMadeRecentPrimaryChanges' )->willReturn( true );
		$lb->method( 'hasStreamingReplicaServers' )->willReturn( true );
		$lb->method( 'getPrimaryPos' )->willReturnCallback(
			static function () use ( &$replicationPos, &$time ) {
				return new MySQLPrimaryPos( $replicationPos, $time );
			}
		);

		$client = [
			'IPAddress' => '127.0.0.1',
			'UserAgent' => "Burninator",
			'ChronologyClientId' => 'random id'
		];

		$secret = '0815';

		$bag = new HashBagOStuff();
		$cp = new ChronologyProtector( $bag, $secret, false );
		$cp->setRequestInfo( $client );

		$clientPostIndex = 0;
		$cp->stageSessionPrimaryPos( $lb );
		$cp->persistSessionReplicationPositions( $clientPostIndex );

		// Do it a second time so the values that were written the first
		// time get read from the cache.
		$replicationPos = '3-4-5';
		$time++;
		$cp->stageSessionPrimaryPos( $lb );
		$cp->persistSessionReplicationPositions( $clientPostIndex );

		$waitForPos = $cp->getSessionPrimaryPos( $lb );
		$this->assertNotNull( $waitForPos );
		$this->assertSame( $time, $waitForPos->asOfTime() );
		$this->assertSame( "$replicationPos", "$waitForPos" );
	}

	public function testCPPosIndexCookieValues() {
		$time = 1526522031;
		$agentId = md5( 'Ramsey\'s Loyal Presa Canario' );

		$this->assertEquals(
			'3@542#c47dcfb0566e7d7bc110a6128a45c93a',
			ChronologyProtector::makeCookieValueFromCPIndex( 3, 542, $agentId )
		);

		$this->assertEquals(
			'1@542#c47dcfb0566e7d7bc110a6128a45c93a',
			ChronologyProtector::makeCookieValueFromCPIndex( 1, 542, $agentId )
		);

		$this->assertSame(
			null,
			ChronologyProtector::getCPInfoFromCookieValue( "5#$agentId", $time - 10 )['index'],
			'No time set'
		);
		$this->assertSame(
			null,
			ChronologyProtector::getCPInfoFromCookieValue( "5@$time", $time - 10 )['index'],
			'No agent set'
		);
		$this->assertSame(
			null,
			ChronologyProtector::getCPInfoFromCookieValue( "0@$time#$agentId", $time - 10 )['index'],
			'Bad index'
		);

		$this->assertSame(
			2,
			ChronologyProtector::getCPInfoFromCookieValue( "2@$time#$agentId", $time - 10 )['index'],
			'Fresh'
		);
		$this->assertSame(
			2,
			ChronologyProtector::getCPInfoFromCookieValue( "2@$time#$agentId", $time + 9 - 10 )['index'],
			'Almost stale'
		);
		$this->assertSame(
			null,
			ChronologyProtector::getCPInfoFromCookieValue( "0@$time#$agentId", $time + 9 - 10 )['index'],
			'Almost stale; bad index'
		);
		$this->assertSame(
			null,
			ChronologyProtector::getCPInfoFromCookieValue( "2@$time#$agentId", $time + 11 - 10 )['index'],
			'Stale'
		);

		$this->assertSame(
			$agentId,
			ChronologyProtector::getCPInfoFromCookieValue( "5@$time#$agentId", $time - 10 )['clientId'],
			'Live (client ID)'
		);
		$this->assertSame(
			null,
			ChronologyProtector::getCPInfoFromCookieValue( "5@$time#$agentId", $time + 11 - 10 )['clientId'],
			'Stale (client ID)'
		);
	}
}
