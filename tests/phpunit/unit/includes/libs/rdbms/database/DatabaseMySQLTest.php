<?php
/**
 * Holds tests for DatabaseMySQL class.
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
 * @author Antoine Musso
 * @copyright © 2013 Antoine Musso
 * @copyright © 2013 Wikimedia Foundation and contributors
 */

namespace Wikimedia\Tests\Rdbms;

use MediaWiki\Tests\Unit\Libs\Rdbms\AddQuoterMock;
use MediaWikiCoversValidator;
use PHPUnit\Framework\TestCase;
use Wikimedia\Rdbms\DatabaseDomain;
use Wikimedia\Rdbms\DatabaseMySQL;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IDatabaseForOwner;
use Wikimedia\Rdbms\MySQLPrimaryPos;
use Wikimedia\Rdbms\Platform\MySQLPlatform;
use Wikimedia\Rdbms\Replication\MysqlReplicationReporter;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \Wikimedia\Rdbms\DatabaseMySQL
 */
class DatabaseMySQLTest extends TestCase {

	use MediaWikiCoversValidator;

	/**
	 * @covers \Wikimedia\Rdbms\MySQLPrimaryPos
	 */
	public function testBinLogName() {
		$pos = new MySQLPrimaryPos( "db1052.2424/4643", 1 );

		$this->assertEquals( "db1052.2424", $pos->getLogFile() );
		$this->assertEquals( [ 2424, 4643 ], $pos->getLogPosition() );
	}

	/**
	 * @dataProvider provideComparePositions
	 * @covers \Wikimedia\Rdbms\MySQLPrimaryPos
	 */
	public function testHasReached(
		MySQLPrimaryPos $lowerPos, MySQLPrimaryPos $higherPos, $match, $hetero
	) {
		if ( $match ) {
			if ( $hetero ) {
				// Each position is has one channel higher than the other
				$this->assertFalse( $higherPos->hasReached( $lowerPos ) );
			} else {
				$this->assertTrue( $higherPos->hasReached( $lowerPos ) );
			}
			$this->assertTrue( $lowerPos->hasReached( $lowerPos ) );
			$this->assertTrue( $higherPos->hasReached( $higherPos ) );
			$this->assertFalse( $lowerPos->hasReached( $higherPos ) );
		} else { // channels don't match

			$this->assertFalse( $higherPos->hasReached( $lowerPos ) );
			$this->assertFalse( $lowerPos->hasReached( $higherPos ) );
		}
	}

	public static function provideComparePositions() {
		$now = microtime( true );

		return [
			// Binlog style
			[
				new MySQLPrimaryPos( 'db1034-bin.000976/843431247', $now ),
				new MySQLPrimaryPos( 'db1034-bin.000976/843431248', $now ),
				true,
				false
			],
			[
				new MySQLPrimaryPos( 'db1034-bin.000976/999', $now ),
				new MySQLPrimaryPos( 'db1034-bin.000976/1000', $now ),
				true,
				false
			],
			[
				new MySQLPrimaryPos( 'db1034-bin.000976/999', $now ),
				new MySQLPrimaryPos( 'db1035-bin.000976/1000', $now ),
				false,
				false
			],
			// MySQL GTID style
			[
				new MySQLPrimaryPos( '3E11FA47-71CA-11E1-9E33-C80AA9429562:1-23', $now ),
				new MySQLPrimaryPos( '3E11FA47-71CA-11E1-9E33-C80AA9429562:5-24', $now ),
				true,
				false
			],
			[
				new MySQLPrimaryPos( '3E11FA47-71CA-11E1-9E33-C80AA9429562:5-99', $now ),
				new MySQLPrimaryPos( '3E11FA47-71CA-11E1-9E33-C80AA9429562:1-100', $now ),
				true,
				false
			],
			[
				new MySQLPrimaryPos( '3E11FA47-71CA-11E1-9E33-C80AA9429562:1-99', $now ),
				new MySQLPrimaryPos( '1E11FA47-71CA-11E1-9E33-C80AA9429562:1-100', $now ),
				false,
				false
			],
			// MariaDB GTID style
			[
				new MySQLPrimaryPos( '255-11-23', $now ),
				new MySQLPrimaryPos( '255-11-24', $now ),
				true,
				false
			],
			[
				new MySQLPrimaryPos( '255-11-99', $now ),
				new MySQLPrimaryPos( '255-11-100', $now ),
				true,
				false
			],
			[
				new MySQLPrimaryPos( '255-11-999', $now ),
				new MySQLPrimaryPos( '254-11-1000', $now ),
				false,
				false
			],
			[
				new MySQLPrimaryPos( '255-11-23,256-12-50', $now ),
				new MySQLPrimaryPos( '255-11-24', $now ),
				true,
				false
			],
			[
				new MySQLPrimaryPos( '255-11-99,256-12-50,257-12-50', $now ),
				new MySQLPrimaryPos( '255-11-1000', $now ),
				true,
				false
			],
			[
				new MySQLPrimaryPos( '255-11-23,256-12-50', $now ),
				new MySQLPrimaryPos( '255-11-24,155-52-63', $now ),
				true,
				false
			],
			[
				new MySQLPrimaryPos( '255-11-99,256-12-50,257-12-50', $now ),
				new MySQLPrimaryPos( '255-11-1000,256-12-51', $now ),
				true,
				false
			],
			[
				new MySQLPrimaryPos( '255-11-99,256-12-50', $now ),
				new MySQLPrimaryPos( '255-13-1000,256-14-49', $now ),
				true,
				true
			],
			[
				new MySQLPrimaryPos( '253-11-999,255-11-999', $now ),
				new MySQLPrimaryPos( '254-11-1000', $now ),
				false,
				false
			],
		];
	}

	/**
	 * @dataProvider provideCommonDomainGTIDs
	 * @covers \Wikimedia\Rdbms\MySQLPrimaryPos
	 */
	public function testGetRelevantActiveGTIDs( MySQLPrimaryPos $pos, MySQLPrimaryPos $ref, $gtids ) {
		$this->assertEquals( $gtids, MySQLPrimaryPos::getRelevantActiveGTIDs( $pos, $ref ) );
	}

	public static function provideCommonDomainGTIDs() {
		return [
			[
				new MySQLPrimaryPos( '255-13-99,256-12-50,257-14-50', 1 ),
				new MySQLPrimaryPos( '255-11-1000', 1 ),
				[ '255-13-99' ]
			],
			[
				( new MySQLPrimaryPos( '255-13-99,256-12-50,257-14-50', 1 ) )
					->setActiveDomain( 257 ),
				new MySQLPrimaryPos( '255-11-1000,257-14-30', 1 ),
				[ '257-14-50' ]
			],
			[
				new MySQLPrimaryPos(
					'2E11FA47-71CA-11E1-9E33-C80AA9429562:1-5,' .
					'3E11FA47-71CA-11E1-9E33-C80AA9429562:20-99,' .
					'7E11FA47-71CA-11E1-9E33-C80AA9429562:1-30',
					1
				),
				new MySQLPrimaryPos(
					'1E11FA47-71CA-11E1-9E33-C80AA9429562:30-100,' .
					'3E11FA47-71CA-11E1-9E33-C80AA9429562:30-66',
					1
				),
				[ '3E11FA47-71CA-11E1-9E33-C80AA9429562:20-99' ]
			]
		];
	}

	/**
	 * @dataProvider provideLagAmounts
	 */
	public function testPtHeartbeat( $lag ) {
		/** @var IDatabase $db */
		$db = $this->getMockBuilder( IDatabaseForOwner::class )
			->disableOriginalConstructor()
			->getMock();
		$db->setLBInfo( 'replica', true );

		$replicationReporter = $this->getMockBuilder( MysqlReplicationReporter::class )
			->disableOriginalConstructor()
			->onlyMethods( [ 'fetchSecondsSinceHeartbeat' ] )
			->getMock();

		TestingAccessWrapper::newFromObject( $replicationReporter )->lagDetectionMethod = 'pt-heartbeat';
		$replicationReporter->method( 'fetchSecondsSinceHeartbeat' )->willReturn( $lag );

		$lagEst = $replicationReporter->getLag( $db );

		$this->assertGreaterThan( $lag - 0.010, $lagEst, "Correct heatbeat lag" );
		$this->assertLessThan( $lag + 0.010, $lagEst, "Correct heatbeat lag" );
	}

	public static function provideLagAmounts() {
		return [
			[ 0 ],
			[ 0.3 ],
			[ 6.5 ],
			[ 10.1 ],
			[ 200.2 ],
			[ 400.7 ],
			[ 600.22 ],
			[ 1000.77 ],
		];
	}

	/**
	 * @dataProvider provideGtidData
	 * @covers \Wikimedia\Rdbms\MySQLPrimaryPos
	 * @covers \Wikimedia\Rdbms\DatabaseMySQL
	 */
	public function testServerGtidTable( $gtable, $rBLtable, $mBLtable, $rGTIDs, $mGTIDs ) {
		$db = $this->getMockBuilder( IDatabase::class )
			->disableOriginalConstructor()
			->getMock();
		$replicationReporter = $this->getMockBuilder( MysqlReplicationReporter::class )
			->disableOriginalConstructor()
			->onlyMethods( [
				'useGTIDs',
				'getServerGTIDs',
				'getServerRoleStatus',
				'getServerId',
				'getServerUUID'
			] )
			->getMock();

		$replicationReporter->method( 'useGTIDs' )->willReturn( true );
		$replicationReporter->method( 'getServerGTIDs' )->willReturn( $gtable );
		$replicationReporter->method( 'getServerRoleStatus' )->willReturnCallback(
			static function ( $db, $role ) use ( $rBLtable, $mBLtable ) {
				if ( $role === 'SLAVE' ) {
					return $rBLtable;
				} elseif ( $role === 'MASTER' ) {
					return $mBLtable;
				}

				return null;
			}
		);
		$replicationReporter->method( 'getServerId' )->willReturn( 1 );
		$replicationReporter->method( 'getServerUUID' )->willReturn( '2E11FA47-71CA-11E1-9E33-C80AA9429562' );

		if ( is_array( $rGTIDs ) ) {
			$this->assertEquals( $rGTIDs, $replicationReporter->getReplicaPos( $db )->getGTIDs() );
		} else {
			$this->assertFalse( $replicationReporter->getReplicaPos( $db ) );
		}
		if ( is_array( $mGTIDs ) ) {
			$this->assertEquals( $mGTIDs, $replicationReporter->getPrimaryPos( $db )->getGTIDs() );
		} else {
			$this->assertFalse( $replicationReporter->getPrimaryPos( $db ) );
		}
	}

	public static function provideGtidData() {
		return [
			// MariaDB
			[
				[
					'gtid_domain_id' => 100,
					'gtid_current_pos' => '100-13-77',
					'gtid_binlog_pos' => '100-13-77',
					'gtid_slave_pos' => null // master
				],
				[
					'Relay_Master_Log_File' => 'host.1600',
					'Exec_Master_Log_Pos' => '77'
				],
				[
					'File' => 'host.1600',
					'Position' => '77'
				],
				[],
				[ '100' => '100-13-77' ]
			],
			[
				[
					'gtid_domain_id' => 100,
					'gtid_current_pos' => '100-13-77',
					'gtid_binlog_pos' => '100-13-77',
					'gtid_slave_pos' => '100-13-77' // replica
				],
				[
					'Relay_Master_Log_File' => 'host.1600',
					'Exec_Master_Log_Pos' => '77'
				],
				[],
				[ '100' => '100-13-77' ],
				[ '100' => '100-13-77' ]
			],
			[
				[
					'gtid_current_pos' => '100-13-77',
					'gtid_binlog_pos' => '100-13-77',
					'gtid_slave_pos' => '100-13-77' // replica
				],
				[
					'Relay_Master_Log_File' => 'host.1600',
					'Exec_Master_Log_Pos' => '77'
				],
				[],
				[ '100' => '100-13-77' ],
				[ '100' => '100-13-77' ]
			],
			// MySQL
			[
				[
					'gtid_executed' => '2E11FA47-71CA-11E1-9E33-C80AA9429562:1-77'
				],
				[
					'Relay_Master_Log_File' => 'host.1600',
					'Exec_Master_Log_Pos' => '77'
				],
				[], // only a replica
				[ '2E11FA47-71CA-11E1-9E33-C80AA9429562'
					=> '2E11FA47-71CA-11E1-9E33-C80AA9429562:1-77' ],
				// replica/master use same var
				[ '2E11FA47-71CA-11E1-9E33-C80AA9429562'
					=> '2E11FA47-71CA-11E1-9E33-C80AA9429562:1-77' ],
			],
			[
				[
					'gtid_executed' => '2E11FA47-71CA-11E1-9E33-C80AA9429562:1-49,' .
						'2E11FA47-71CA-11E1-9E33-C80AA9429562:51-77'
				],
				[
					'Relay_Master_Log_File' => 'host.1600',
					'Exec_Master_Log_Pos' => '77'
				],
				[], // only a replica
				[ '2E11FA47-71CA-11E1-9E33-C80AA9429562'
					=> '2E11FA47-71CA-11E1-9E33-C80AA9429562:51-77' ],
				// replica/master use same var
				[ '2E11FA47-71CA-11E1-9E33-C80AA9429562'
					=> '2E11FA47-71CA-11E1-9E33-C80AA9429562:51-77' ],
			],
			[
				[
					'gtid_executed' => null, // not enabled?
					'gtid_binlog_pos' => null
				],
				[
					'Relay_Master_Log_File' => 'host.1600',
					'Exec_Master_Log_Pos' => '77'
				],
				[], // only a replica
				[], // binlog fallback
				false
			],
			[
				[
					'gtid_executed' => null, // not enabled?
					'gtid_binlog_pos' => null
				],
				[], // no replication
				[], // no replication
				false,
				false
			]
		];
	}

	/**
	 * @covers \Wikimedia\Rdbms\MySQLPrimaryPos
	 */
	public function testSerialize() {
		$pos = new MySQLPrimaryPos( '3E11FA47-71CA-11E1-9E33-C80AA9429562:99', 53636363 );
		$roundtripPos = unserialize( serialize( $pos ) );

		$this->assertEquals( $pos, $roundtripPos );

		$pos = new MySQLPrimaryPos( '255-11-23', 53636363 );
		$roundtripPos = unserialize( serialize( $pos ) );

		$this->assertEquals( $pos, $roundtripPos );
	}

	public function testBuildIntegerCast() {
		$db = $this->createPartialMock( DatabaseMySQL::class, [] );
		TestingAccessWrapper::newFromObject( $db )->platform = new MySQLPlatform( new AddQuoterMock() );

		/** @var IDatabase $db */
		$output = $db->buildIntegerCast( 'fieldName' );
		$this->assertSame( 'CAST( fieldName AS SIGNED )', $output );
	}

	/**
	 * @covers \Wikimedia\Rdbms\Platform\MySQLPlatform
	 */
	public function testNormalizeJoinType() {
		$platform = new MySQLPlatform( new AddQuoterMock() );
		$sql = $platform->selectSQLText(
			[ 'a', 'b' ],
			'aa',
			[],
			'',
			[],
			[ 'b' => [ 'STRAIGHT_JOIN', 'bb=aa' ] ]
		);
		$this->assertSame(
			'SELECT  aa  FROM `a` STRAIGHT_JOIN `b` ON ((bb=aa))    ',
			$sql
		);
	}

	/**
	 * @covers \Wikimedia\Rdbms\Platform\MySQLPlatform
	 */
	public function testNormalizeJoinTypeSqb() {
		$db = $this->createPartialMock( DatabaseMySQL::class, [] );

		TestingAccessWrapper::newFromObject( $db )->currentDomain =
			new DatabaseDomain( null, null, '' );
		TestingAccessWrapper::newFromObject( $db )->platform =
			new MySQLPlatform( new AddQuoterMock() );

		/** @var IDatabase $db */
		$sql = $db->newSelectQueryBuilder()
			->select( 'aa' )
			->from( 'a' )
			->straightJoin( 'b', null, [ 'bb=aa' ] )
			->getSQL();
		$this->assertSame(
			'SELECT  aa  FROM `a` STRAIGHT_JOIN `b` ON ((bb=aa))    ',
			$sql
		);
	}

	/**
	 * @covers \Wikimedia\Rdbms\Database
	 * @covers \Wikimedia\Rdbms\Platform\SQLPlatform
	 * @covers \Wikimedia\Rdbms\Platform\MySQLPlatform
	 */
	public function testTableAliases() {
		$db = $this->getMockBuilder( DatabaseMySQL::class )
			->disableOriginalConstructor()
			->onlyMethods( [ 'strencode', 'dbSchema', 'tablePrefix' ] )
			->getMock();
		$db->method( 'strencode' )->willReturnCallback(
			static function ( $s ) {
				return str_replace( "'", "\\'", $s );
			}
		);
		$wdb = TestingAccessWrapper::newFromObject( $db );
		$wdb->platform = new MySQLPlatform( new AddQuoterMock() );

		/** @var IDatabase $db */
		$db->setTableAliases( [
			'meow' => [ 'dbname' => 'feline', 'schema' => null, 'prefix' => '' ]
		] );
		$sql = $db->newSelectQueryBuilder()
			->select( 'field' )
			->from( 'meow' )
			->where( [ 'a' => 'x' ] )
			->caller( __METHOD__ )->getSQL();

		$this->assertSameSql(
			"SELECT  field  FROM `feline`.`meow` `meow`    WHERE a = 'x'  ",
			$sql
		);

		$db->setTableAliases( [
			'meow' => [ 'dbname' => 'feline', 'schema' => null, 'prefix' => 'cat_' ]
		] );
		$sql = $db->newSelectQueryBuilder()
			->select( 'field' )
			->from( 'meow' )
			->where( [ 'a' => 'x' ] )
			->caller( __METHOD__ )->getSQL();

		$this->assertSameSql(
			"SELECT  field  FROM `feline`.`cat_meow` `meow`    WHERE a = 'x'  ",
			$sql
		);

		$db->setTableAliases( [] );
		$sql = $db->newSelectQueryBuilder()
			->select( 'field' )
			->from( 'meow' )
			->where( [ 'a' => 'x' ] )
			->caller( __METHOD__ )->getSQL();

		$this->assertSameSql(
			"SELECT  field  FROM `meow`    WHERE a = 'x'  ",
			$sql
		);
	}

	/**
	 * @covers \Wikimedia\Rdbms\DatabaseMySQL
	 * @covers \Wikimedia\Rdbms\Platform\SQLPlatform
	 * @covers \Wikimedia\Rdbms\Platform\MySQLPlatform
	 */
	public function testMaxExecutionTime() {
		$db = $this->getMockBuilder( DatabaseMySQL::class )
			->disableOriginalConstructor()
			->onlyMethods( [ 'getServerVersion', 'dbSchema', 'tablePrefix' ] )
			->getMock();
		$db->method( 'getServerVersion' )->willReturn( '10.4.28-MariaDB-log' );
		$wdb = TestingAccessWrapper::newFromObject( $db );
		$wdb->platform = new MySQLPlatform( new AddQuoterMock() );

		$sql = $wdb->selectSQLText( 'image',
			'img_metadata',
			'*',
			'',
			[ 'MAX_EXECUTION_TIME' => 1 ]
		);

		$this->assertSameSql(
			"SET STATEMENT max_statement_time=0.001 FOR SELECT  img_metadata  FROM `image`     ",
			$sql
		);
	}

	/**
	 * @covers \Wikimedia\Rdbms\Database
	 * @covers \Wikimedia\Rdbms\DatabaseMySQL
	 */
	public function testStreamStatementEnd() {
		/** @var DatabaseMySQL $db */
		$db = $this->getMockForAbstractClass( DatabaseMySQL::class, [], '', false );
		$sql = '';

		$newLine = "delimiter\n!! ?";
		$this->assertFalse( $db->streamStatementEnd( $sql, $newLine ) );
		$this->assertSame( '', $newLine );

		$newLine = 'JUST A TEST!!!';
		$this->assertTrue( $db->streamStatementEnd( $sql, $newLine ) );
		$this->assertSame( 'JUST A TEST!', $newLine );
	}

	private function assertSameSql( $expected, $actual, $message = '' ) {
		$this->assertSame( trim( $expected ), trim( $actual ), $message );
	}
}
