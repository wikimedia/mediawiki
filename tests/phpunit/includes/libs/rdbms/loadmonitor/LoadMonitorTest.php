<?php

/**
 * Holds tests for LoadMonitor MediaWiki class.
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

use Wikimedia\Rdbms\DatabaseDomain;
use Wikimedia\Rdbms\LoadMonitor;
use Wikimedia\Rdbms\LoadBalancer;
use Wikimedia\Rdbms\LoadMonitorNull;

/**
 * @covers \Wikimedia\Rdbms\LoadMonitor
 */
class LoadMonitorTest extends PHPUnit\Framework\TestCase {
	private function makeLoadMonitor( array $lmOverrides = [] ) {
		$lb = $this->getMockBuilder( LoadBalancer::class )->setConstructorArgs( [
			[
				'servers' => [
					[
						'host'        => 'localhost:3306',
						'dbname'      => 'mywiki',
						'tablePrefix' => 'unittest_',
						'user'        => 'wikiuser',
						'password'    => 'wikipassword',
						'type'        => 'mysql',
						'load'        => 0,
					],
					[
						'host'        => 'localhost:3307',
						'dbname'      => 'mywiki',
						'tablePrefix' => 'unittest_',
						'user'        => 'wikiuser',
						'password'    => 'wikipassword',
						'type'        => 'mysql',
						'load'        => 100,
					],
					[
						'host'        => 'localhost:3308',
						'dbname'      => 'mywiki',
						'tablePrefix' => 'unittest_',
						'user'        => 'wikiuser',
						'password'    => 'wikipassword',
						'type'        => 'mysql',
						'load'        => 100,
					],
					[
						'host'        => 'localhost:3309',
						'dbname'      => 'mywiki',
						'tablePrefix' => 'unittest_',
						'user'        => 'wikiuser',
						'password'    => 'wikipassword',
						'type'        => 'mysql',
						'load'        => 100,
					],
				],
				'localDomain' => new DatabaseDomain( 'mywiki', null, '' ),
				'loadMonitorClass' => LoadMonitorNull::class
			]
		] )->setMethods( [
			'getServerName' ,
			'openConnection',
			'getAnyOpenConnection'
		] )->getMock();

		$mockDB1 = $this->getMockBuilder( DatabaseMysqli::class )
			->disableOriginalConstructor()
			->setMethods( [ 'getLag' ] )
			->getMock();
		$mockDB2 = $this->getMockBuilder( DatabaseMysqli::class )
			->disableOriginalConstructor()
			->setMethods( [ 'getLag' ] )
			->getMock();
		$mockDB3 = $this->getMockBuilder( DatabaseMysqli::class )
			->disableOriginalConstructor()
			->setMethods( [ 'getLag' ] )
			->getMock();
		$mockDB4 = $this->getMockBuilder( DatabaseMysqli::class )
			->disableOriginalConstructor()
			->setMethods( [ 'getLag' ] )
			->getMock();

		$mockDB1->ut_override_lag = 0;
		$mockDB1->method( 'getLag' )->willReturnCallback( function () use ( $mockDB1 ) {
			return $mockDB1->ut_override_lag;
		});
		$mockDB2->ut_override_lag = 0;
		$mockDB2->method( 'getLag' )->willReturnCallback( function () use ( $mockDB2 ) {
			return $mockDB2->ut_override_lag;
		});
		$mockDB3->ut_override_lag = 0;
		$mockDB3->method( 'getLag' )->willReturnCallback( function () use ( $mockDB3 ) {
			return $mockDB3->ut_override_lag;
		});
		$mockDB4->ut_override_lag = 0;
		$mockDB4->method( 'getLag' )->willReturnCallback( function () use ( $mockDB4 ) {
			return $mockDB4->ut_override_lag;
		});

		$lb->ut_override_is_up = [ true, true, true, true ];
		$lb->method( 'getServerName' )->willReturnMap( [
			[ 0, 'db1' ],
			[ 1, 'db2' ],
			[ 2, 'db3' ],
			[ 3, 'db4' ]
		] );
		$lb->method( 'openConnection' )->willReturnCallback( function (
			$i, $domain, $flags
		) use ( $lb, $mockDB1, $mockDB2, $mockDB3, $mockDB4 ) {
			if ( $lb->ut_override_is_up[$i] === false ) {
				return null;
			}

			if ( $i === 0 ) {
				return $mockDB1;
			} elseif ( $i === 1 ) {
				return $mockDB2;
			} elseif ( $i === 2 ) {
				return $mockDB3;
			} elseif ( $i === 3 ) {
				return $mockDB4;
			}

			throw new RuntimeException( "Bad index $i" );
		} );

		$srvCache = new HashBagOStuff();
		$wanCache = new WANObjectCache( [ 'cache' => new HashBagOStuff() ] );

		return [
			new LoadMonitor(
				$lb,
				$srvCache,
				$wanCache,
				$lmOverrides + [
					'movingAveRatioConnFail' => .2,
					'movingAveRatioSyncFail' => .8
				]
			),
			$lb,
			$srvCache,
			$wanCache
		];
	}

	/**
	 * @covers LoadMonitor::getLagTimes()
	 */
	public function testGetLagTimes() {
		list( $lm, $lb, $srvCache, $wanCache ) = $this->makeLoadMonitor();

		$this->assertSame( [ 0, 0, 0 ], $lm->getLagTimes( [ 0, 1, 2 ], $lm::DOMAIN_ANY ) );

		$now = microtime( true );
		$lm->setMockTime( $now );
		$srvCache->setMockTime( $now );
		$wanCache->setMockTime( $now );

		$conn1 = $lb->openConnection( 1, $lb::DOMAIN_ANY, $lb::CONN_TRX_AUTOCOMMIT );
		$conn2 = $lb->openConnection( 2, $lb::DOMAIN_ANY, $lb::CONN_TRX_AUTOCOMMIT );
		$this->assertNotEquals( null, $conn1 );
		$this->assertNotEquals( null, $conn2 );

		$conn1->ut_override_lag = 1;
		$conn2->ut_override_lag = 5;

		$this->assertSame( [ 0, 0, 0 ], $lm->getLagTimes( [ 0, 1, 2 ], $lm::DOMAIN_ANY ) );

		$now += 120;

		$this->assertSame( [ 0, 1, 5 ], $lm->getLagTimes( [ 0, 1, 2 ], $lm::DOMAIN_ANY ) );
	}

	/**
	 * @covers LoadMonitor::scaleLoads()
	 * @dataProvider provideScaleLoads
	 */
	public function testScaleLoads( $dbsDownIter, $expScaledLoadsIter ) {
		list( $lm, $lb, $srvCache, $wanCache ) = $this->makeLoadMonitor();

		$now = 1527937048;
		$lm->setMockTime( $now );
		$srvCache->setMockTime( $now );
		$wanCache->setMockTime( $now );

		$conn1 = $lb->openConnection( 1, $lb::DOMAIN_ANY, $lb::CONN_TRX_AUTOCOMMIT );
		$conn2 = $lb->openConnection( 2, $lb::DOMAIN_ANY, $lb::CONN_TRX_AUTOCOMMIT );
		$this->assertNotEquals( null, $conn1 );
		$this->assertNotEquals( null, $conn2 );

		static $nominalWeights = [ 0, 1000, 1000, 1000 ];

		foreach ( $dbsDownIter as $i => $dbsDown ) {
			$lb->ut_override_is_up[0] = !in_array( 0, $dbsDown, true );
			$lb->ut_override_is_up[1] = !in_array( 1, $dbsDown, true );
			$lb->ut_override_is_up[2] = !in_array( 2, $dbsDown, true );

			if ( !$lb->ut_override_is_up ) { // sanity check
				$this->assertNull(
					$lb->openConnection( $i, $lb::DOMAIN_ANY, $lb::CONN_TRX_AUTOCOMMIT ) );
				$this->assertNull(
					$lb->getAnyOpenConnection( $i, $lb::DOMAIN_ANY, $lb::CONN_TRX_AUTOCOMMIT ) );
			}

			$weights = $nominalWeights;
			$lm->scaleLoads( $weights, $lm::DOMAIN_ANY );

			$this->assertEquals( $expScaledLoadsIter[$i], $weights, "Gauges; iteration $i" );

			$now += $lm::TIME_TILL_REFRESH * 2;
		}
	}

	public static function provideScaleLoads() {
		return [
			'scenario1' => [
				[
					0 => [],
					1 => [],
					2 => [],
					3 => [ 1 ],
					4 => [ 1 ],
					5 => [ 1 ],
					6 => [ 1 ],
					7 => [ 1 ],
					8 => [ 1 ],
					9 => [ 1 ],
					10 => [ 1 ],
					11 => [ 1 ],
					12 => [ 1 ],
					13 => [ 1 ],
					14 => [ 1 ],
					15 => [ 1 ],
					16 => [ 1 ],
				 	17 => [ 1 ],
					18 => [],
				 	19 => [ 1 ],
					20 => [ 1 ],
				 	21 => [],
					22 => [ 1, 2 ],
					23 => [ 2 ],
				 	24 => [ 1, 2 ],
					25 => [ 1 ],
					26 => [ 1 ],
				 	27 => [],
					28 => [],
					29 => [],
					30 => [],
					31 => [],
				],
				[
					0 => [ 0, 1000, 1000, 1000 ],
					1 => [ 0, 1000, 1000, 1000 ],
					2 => [ 0, 1000, 1000, 1000 ],
					3 => [ 0, 800, 1000, 1000 ],
					4 => [ 0, 641, 1000, 1000 ],
					5 => [ 0, 513, 1000, 1000 ],
					6 => [ 0, 410, 1000, 1000 ],
					7 => [ 0, 328, 1000, 1000 ],
					8 => [ 0, 263, 1000, 1000 ],
					9 => [ 0, 210, 1000, 1000 ],
					10 => [ 0, 168, 1000, 1000 ],
					11 => [ 0, 135, 1000, 1000 ],
					12 => [ 0, 108, 1000, 1000 ],
					13 => [ 0, 86, 1000, 1000 ],
					14 => [ 0, 69, 1000, 1000 ],
					15 => [ 0, 55, 1000, 1000 ],
					16 => [ 0, 44, 1000, 1000 ],
					17 => [ 0, 36, 1000, 1000 ],
					18 => [ 0, 229, 1000, 1000 ],
					19 => [ 0, 183, 1000, 1000 ],
					20 => [ 0, 147, 1000, 1000 ],
					21 => [ 0, 317, 1000, 1000 ],
					22 => [ 0, 254, 800, 1000 ],
					23 => [ 0, 403, 641, 1000 ],
					24 => [ 0, 323, 513, 1000 ],
					25 => [ 0, 258, 610, 1000 ],
					26 => [ 0, 207, 688, 1000 ],
					27 => [ 0, 365, 751, 1000 ],
					28 => [ 0, 492, 801, 1000 ],
					29 => [ 0, 594, 841, 1000 ],
					30 => [ 0, 675, 873, 1000 ],
					31 => [ 0, 740, 898, 1000 ],
				]
			]
		];
	}

	/**
	 * @covers LoadMonitor::pingFailure()
	 * @covers LoadMonitor::getSyncFailureRate()
	 * @covers LoadMonitor::scaleLoads()
	 */
	public function testPingFailure() {
		list( $lm, $lb, $srvCache, $wanCache ) = $this->makeLoadMonitor( [
			'movingAveRatioConnFail' => .1
		] );

		$now = 1527937048;
		$lm->setMockTime( $now );
		$srvCache->setMockTime( $now );
		$wanCache->setMockTime( $now );

		static $nominalWeights = [ 0, 1000, 1000, 1000 ];

		$expectedScaledLoads = [
			0 => [ 0, 1000, 1000, 1000 ],
			1 => [ 0, 1000, 1000, 900 ],
			2 => [ 0, 1000, 1000, 810 ],
			3 => [ 0, 1000, 1000, 730 ],
			4 => [ 0, 1000, 1000, 657 ],
		];

		foreach ( $expectedScaledLoads as $i => $expectedScaledLoad ) {
			$weights = $nominalWeights;
			$srvCache->clear();
			$lm->scaleLoads( $weights, $lm::DOMAIN_ANY );

			$lm->pingFailure( 3, $lm::DOMAIN_ANY, $lm::TYPE_CONNECTION );
			$this->assertEquals( $expectedScaledLoad, $weights, "Conn fail ping: iteration #$i" );
		}

		// 1 in X replication wait failure rates
		$db2SyncFailFactor = 1000;
		$db3SyncFailFactor = 250;
		$db4SyncFailFactor = 1;

		// Set cached states value...
		$weights = $nominalWeights;
		$now += $lm::STATE_PRESERVE_TTL * 2;
		$lm->scaleLoads( $weights, $lm::DOMAIN_ANY );

		$intervalSec = .001; // 1000 hz
		for ( $i = 0; $i < 10000; ++$i ) {
			if ( $i % $db2SyncFailFactor === 0 ) {
				$lm->pingFailure( 1, $lm::DOMAIN_ANY, $lm::TYPE_POS_SYNC );
			}
			if ( $i % $db3SyncFailFactor === 0 ) {
				$lm->pingFailure( 2, $lm::DOMAIN_ANY, $lm::TYPE_POS_SYNC );
			}
			if ( $i % $db4SyncFailFactor === 0 ) {
				$lm->pingFailure( 3, $lm::DOMAIN_ANY, $lm::TYPE_POS_SYNC );
			}

			$now += $intervalSec;
		}

		$weights = $nominalWeights;
		$now += $lm::TIME_TILL_REFRESH * 2;
		$lm->scaleLoads( $weights, $lm::DOMAIN_ANY );

		$this->assertEquals(
			[ 0, 1000, 1000, 1000 ],
			$weights,
			"Weight after replication wait fail pings"
		);

		$this->assertEquals(
			0.0,
			$lm->getSyncFailureRate( 0, $lm::DOMAIN_ANY ),
			'db1 sync fail rate (hz)',
			0.001
		);
		$this->assertEquals(
			1.0,
			$lm->getSyncFailureRate( 1, $lm::DOMAIN_ANY ),
			'db2 sync fail rate (hz)',
			0.001
		);
		$this->assertEquals(
			4.0,
			$lm->getSyncFailureRate( 2, $lm::DOMAIN_ANY ),
			'db3 sync fail rate (hz)',
			0.001
		);
		$this->assertEquals(
			1000.072,
			$lm->getSyncFailureRate( 3, $lm::DOMAIN_ANY ),
			'db4 sync fail rate (hz)',
			0.001
		);

		$now += $lm::STATE_PRESERVE_TTL * 2;

		// 1 in X connection failure rates
		$db1ConnFailFactor = 900;
		$db2ConnFailFactor = 1000;
		$db3ConnFailFactor = 900;
		$db4ConnFailFactor = 2;

		// Set cached states value...
		$weights = $nominalWeights;
		$lm->scaleLoads( $weights, $lm::DOMAIN_ANY );
		$this->assertEquals( [ 0, 1000, 1000, 1000 ], $weights );

		$db4Pings = 0;
		$elapsed = 0;
		$intervalSec = .001; // 1000 hz
		for ( $i = 0; $i < 10000; ++$i ) {
			if ( $i % $db1ConnFailFactor === 0 ) {
				$lm->pingFailure( 0, $lm::DOMAIN_ANY, $lm::TYPE_CONNECTION );
			}
			if ( $i % $db2ConnFailFactor === 0 ) {
				$lm->pingFailure( 1, $lm::DOMAIN_ANY, $lm::TYPE_CONNECTION );
			}
			if ( $i % $db3ConnFailFactor === 0 ) {
				$lm->pingFailure( 2, $lm::DOMAIN_ANY, $lm::TYPE_CONNECTION );
			}
			if ( $i % $db4ConnFailFactor === 0 ) {
				++$db4Pings;
				$lm->pingFailure( 3, $lm::DOMAIN_ANY, $lm::TYPE_CONNECTION );
			}

			$now += $intervalSec;
			$elapsed += $intervalSec;
		}

		$weights = $nominalWeights;
		$lm->scaleLoads( $weights, $lm::DOMAIN_ANY );
		$this->assertEquals(
			[ 0, 349, 283, 1 ],
			$weights,
			"Weight after connection fail pings; " . $db4Pings / $elapsed . "/sec on db4"
		);

		$now += $lm::TIME_TILL_REFRESH * 2;

		$weights = $nominalWeights;
		$lm->scaleLoads( $weights, $lm::DOMAIN_ANY );
		$this->assertEquals(
			[ 0, 414, 355, 100 ],
			$weights,
			"Weight after connection fail pings; " . $db4Pings / $elapsed . "/sec on db4"
		);
	}
}
