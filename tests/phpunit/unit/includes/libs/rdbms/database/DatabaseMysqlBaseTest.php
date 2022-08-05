<?php
/**
 * Holds tests for DatabaseMysqlBase class.
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

use MediaWiki\Tests\Unit\Libs\Rdbms\AddQuoterMock;
use Wikimedia\Rdbms\DatabaseDomain;
use Wikimedia\Rdbms\DatabaseMysqlBase;
use Wikimedia\Rdbms\DatabaseMysqli;
use Wikimedia\Rdbms\FakeResultWrapper;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IMaintainableDatabase;
use Wikimedia\Rdbms\MySQLPrimaryPos;
use Wikimedia\Rdbms\Platform\MySQLPlatform;
use Wikimedia\TestingAccessWrapper;

class DatabaseMysqlBaseTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	private function getMockForViews(): IMaintainableDatabase {
		$db = $this->getMockBuilder( DatabaseMysqli::class )
			->disableOriginalConstructor()
			->onlyMethods( [ 'query', 'getDBname' ] )
			->getMock();

		$db->method( 'query' )
			->with( $this->anything() )
			->willReturn( new FakeResultWrapper( [
				(object)[ 'Tables_in_' => 'view1' ],
				(object)[ 'Tables_in_' => 'view2' ],
				(object)[ 'Tables_in_' => 'myview' ]
			] ) );
		$db->method( 'getDBname' )->willReturn( '' );

		return $db;
	}

	/**
	 * @covers \Wikimedia\Rdbms\DatabaseMysqlBase::listViews
	 */
	public function testListviews() {
		$db = $this->getMockForViews();

		$this->assertEquals( [ 'view1', 'view2', 'myview' ],
			$db->listViews() );

		// Prefix filtering
		$this->assertEquals( [ 'view1', 'view2' ],
			$db->listViews( 'view' ) );
		$this->assertEquals( [ 'myview' ],
			$db->listViews( 'my' ) );
		$this->assertEquals( [],
			$db->listViews( 'UNUSED_PREFIX' ) );
		$this->assertEquals( [ 'view1', 'view2', 'myview' ],
			$db->listViews( '' ) );
	}

	/**
	 * @covers \Wikimedia\Rdbms\MySQLPrimaryPos
	 */
	public function testBinLogName() {
		$pos = new MySQLPrimaryPos( "db1052.2424/4643", 1 );

		$this->assertEquals( "db1052", $pos->getLogName() );
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
			$this->assertTrue( $lowerPos->channelsMatch( $higherPos ) );

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
			$this->assertFalse( $lowerPos->channelsMatch( $higherPos ) );

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
	 * @dataProvider provideChannelPositions
	 * @covers \Wikimedia\Rdbms\MySQLPrimaryPos
	 */
	public function testChannelsMatch( MySQLPrimaryPos $pos1, MySQLPrimaryPos $pos2, $matches ) {
		$this->assertEquals( $matches, $pos1->channelsMatch( $pos2 ) );
		$this->assertEquals( $matches, $pos2->channelsMatch( $pos1 ) );

		$roundtripPos = new MySQLPrimaryPos( (string)$pos1, 1 );
		$this->assertEquals( (string)$pos1, (string)$roundtripPos );
	}

	public static function provideChannelPositions() {
		$now = microtime( true );

		return [
			[
				new MySQLPrimaryPos( 'db1034-bin.000876/44', $now ),
				new MySQLPrimaryPos( 'db1034-bin.000976/74', $now ),
				true
			],
			[
				new MySQLPrimaryPos( 'db1052-bin.000976/999', $now ),
				new MySQLPrimaryPos( 'db1052-bin.000976/1000', $now ),
				true
			],
			[
				new MySQLPrimaryPos( 'db1066-bin.000976/9999', $now ),
				new MySQLPrimaryPos( 'db1035-bin.000976/10000', $now ),
				false
			],
			[
				new MySQLPrimaryPos( 'db1066-bin.000976/9999', $now ),
				new MySQLPrimaryPos( 'trump2016.000976/10000', $now ),
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
	 * @covers \Wikimedia\Rdbms\DatabaseMysqlBase::getLag
	 * @covers \Wikimedia\Rdbms\DatabaseMysqlBase::getLagFromPtHeartbeat
	 */
	public function testPtHeartbeat( $lag ) {
		$db = $this->getMockBuilder( DatabaseMysqli::class )
			->disableOriginalConstructor()
			->onlyMethods( [
				'getLagDetectionMethod', 'fetchSecondsSinceHeartbeat', 'getPrimaryServerInfo' ] )
			->getMock();

		$db->method( 'getLagDetectionMethod' )
			->willReturn( 'pt-heartbeat' );

		$db->method( 'getPrimaryServerInfo' )
			->willReturn( [ 'serverId' => 172, 'asOf' => time() ] );

		$db->setLBInfo( 'replica', true );

		$db->method( 'fetchSecondsSinceHeartbeat' )
			->with( [ 'server_id' => 172 ] )
			->willReturn( $lag );

		/** @var IDatabase $db */
		$db->setLBInfo( 'clusterMasterHost', 'db1052' );
		$lagEst = $db->getLag();

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
	 * @covers \Wikimedia\Rdbms\DatabaseMysqlBase::getReplicaPos
	 * @covers \Wikimedia\Rdbms\DatabaseMysqlBase::getPrimaryPos
	 */
	public function testServerGtidTable( $gtable, $rBLtable, $mBLtable, $rGTIDs, $mGTIDs ) {
		$db = $this->getMockBuilder( DatabaseMysqli::class )
			->disableOriginalConstructor()
			->onlyMethods( [
				'useGTIDs',
				'getServerGTIDs',
				'getServerRoleStatus',
				'getServerId',
				'getServerUUID'
			] )
			->getMock();

		$db->method( 'useGTIDs' )->willReturn( true );
		$db->method( 'getServerGTIDs' )->willReturn( $gtable );
		$db->method( 'getServerRoleStatus' )->willReturnCallback(
			static function ( $role ) use ( $rBLtable, $mBLtable ) {
				if ( $role === 'SLAVE' ) {
					return $rBLtable;
				} elseif ( $role === 'MASTER' ) {
					return $mBLtable;
				}

				return null;
			}
		);
		$db->method( 'getServerId' )->willReturn( 1 );
		$db->method( 'getServerUUID' )->willReturn( '2E11FA47-71CA-11E1-9E33-C80AA9429562' );

		/** @var DatabaseMysqlBase $db */
		if ( is_array( $rGTIDs ) ) {
			$this->assertEquals( $rGTIDs, $db->getReplicaPos()->getGTIDs() );
		} else {
			$this->assertFalse( $db->getReplicaPos() );
		}
		if ( is_array( $mGTIDs ) ) {
			$this->assertEquals( $mGTIDs, $db->getPrimaryPos()->getGTIDs() );
		} else {
			$this->assertFalse( $db->getPrimaryPos() );
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

	/**
	 * @covers \Wikimedia\Rdbms\DatabaseMysqlBase::isInsertSelectSafe
	 * @dataProvider provideInsertSelectCases
	 */
	public function testInsertSelectIsSafe( $insertOpts, $selectOpts, $row, $safe ) {
		$db = $this->getMockBuilder( DatabaseMysqli::class )
			->disableOriginalConstructor()
			->onlyMethods( [ 'getReplicationSafetyInfo' ] )
			->getMock();
		$db->method( 'getReplicationSafetyInfo' )->willReturn( (object)$row );
		$dbw = TestingAccessWrapper::newFromObject( $db );

		/** @var Database $dbw */
		$this->assertEquals( $safe, $dbw->isInsertSelectSafe( $insertOpts, $selectOpts ) );
	}

	public function provideInsertSelectCases() {
		return [
			[
				[],
				[],
				[
					'innodb_autoinc_lock_mode' => '2',
					'binlog_format' => 'ROW',
				],
				true
			],
			[
				[],
				[ 'LIMIT' => 100 ],
				[
					'innodb_autoinc_lock_mode' => '2',
					'binlog_format' => 'ROW',
				],
				true
			],
			[
				[],
				[ 'LIMIT' => 100 ],
				[
					'innodb_autoinc_lock_mode' => '0',
					'binlog_format' => 'STATEMENT',
				],
				false
			],
			[
				[],
				[],
				[
					'innodb_autoinc_lock_mode' => '2',
					'binlog_format' => 'STATEMENT',
				],
				false
			],
			[
				[ 'NO_AUTO_COLUMNS' ],
				[ 'LIMIT' => 100 ],
				[
					'innodb_autoinc_lock_mode' => '0',
					'binlog_format' => 'STATEMENT',
				],
				false
			],
			[
				[],
				[],
				[
					'innodb_autoinc_lock_mode' => 0,
					'binlog_format' => 'STATEMENT',
				],
				true
			],
			[
				[ 'NO_AUTO_COLUMNS' ],
				[],
				[
					'innodb_autoinc_lock_mode' => 2,
					'binlog_format' => 'STATEMENT',
				],
				true
			],
			[
				[ 'NO_AUTO_COLUMNS' ],
				[],
				[
					'innodb_autoinc_lock_mode' => 0,
					'binlog_format' => 'STATEMENT',
				],
				true
			],

		];
	}

	/**
	 * @covers \Wikimedia\Rdbms\DatabaseMysqlBase::buildIntegerCast
	 */
	public function testBuildIntegerCast() {
		$db = $this->createPartialMock( DatabaseMysqli::class, [] );
		TestingAccessWrapper::newFromObject( $db )->platform = new MySQLPlatform( new AddQuoterMock() );

		/** @var IDatabase $db */
		$output = $db->buildIntegerCast( 'fieldName' );
		$this->assertSame( 'CAST( fieldName AS SIGNED )', $output );
	}

	/**
	 * @covers \Wikimedia\Rdbms\Platform\MySQLPlatform::normalizeJoinType
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
	 * @covers \Wikimedia\Rdbms\Platform\MySQLPlatform::normalizeJoinType
	 */
	public function testNormalizeJoinTypeSqb() {
		$db = $this->createPartialMock( DatabaseMysqli::class, [] );

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
	 * @covers \Wikimedia\Rdbms\Database::setIndexAliases
	 */
	public function testIndexAliases() {
		$db = $this->getMockBuilder( DatabaseMysqli::class )
			->disableOriginalConstructor()
			->onlyMethods( [ 'mysqlRealEscapeString', 'dbSchema', 'tablePrefix' ] )
			->getMock();
		$db->method( 'mysqlRealEscapeString' )->willReturnCallback(
			static function ( $s ) {
				return str_replace( "'", "\\'", $s );
			}
		);
		$wdb = TestingAccessWrapper::newFromObject( $db );
		$wdb->platform = new MySQLPlatform( new AddQuoterMock() );

		/** @var IDatabase $db */
		$db->setIndexAliases( [ 'a_b_idx' => 'a_c_idx' ] );
		$sql = $db->selectSQLText(
			'zend', 'field', [ 'a' => 'x' ], __METHOD__, [ 'USE INDEX' => 'a_b_idx' ] );

		$this->assertEquals(
			"SELECT  field  FROM `zend`  FORCE INDEX (a_c_idx)  WHERE a = 'x'  ",
			$sql
		);

		$db->setIndexAliases( [] );
		$sql = $db->selectSQLText(
			'zend', 'field', [ 'a' => 'x' ], __METHOD__, [ 'USE INDEX' => 'a_b_idx' ] );

		$this->assertEquals(
			"SELECT  field  FROM `zend`  FORCE INDEX (a_b_idx)  WHERE a = 'x'  ",
			$sql
		);
	}

	/**
	 * @covers \Wikimedia\Rdbms\Database::setTableAliases
	 */
	public function testTableAliases() {
		$db = $this->getMockBuilder( DatabaseMysqli::class )
			->disableOriginalConstructor()
			->onlyMethods( [ 'mysqlRealEscapeString', 'dbSchema', 'tablePrefix' ] )
			->getMock();
		$db->method( 'mysqlRealEscapeString' )->willReturnCallback(
			static function ( $s ) {
				return str_replace( "'", "\\'", $s );
			}
		);
		$wdb = TestingAccessWrapper::newFromObject( $db );
		$wdb->platform = new MySQLPlatform( new AddQuoterMock() );

		/** @var IDatabase $db */
		$db->setTableAliases( [
			'meow' => [ 'dbname' => 'feline', 'schema' => null, 'prefix' => 'cat_' ]
		] );
		$sql = $db->selectSQLText( 'meow', 'field', [ 'a' => 'x' ], __METHOD__ );

		$this->assertEquals(
			"SELECT  field  FROM `feline`.`cat_meow`    WHERE a = 'x'  ",
			$sql
		);

		$db->setTableAliases( [] );
		$sql = $db->selectSQLText( 'meow', 'field', [ 'a' => 'x' ], __METHOD__ );

		$this->assertEquals(
			"SELECT  field  FROM `meow`    WHERE a = 'x'  ",
			$sql
		);
	}

	/**
	 * @covers \Wikimedia\Rdbms\DatabaseMysqlBase::selectSQLText
	 */
	public function testMaxExecutionTime() {
		$db = $this->getMockBuilder( DatabaseMysqli::class )
			->disableOriginalConstructor()
			->onlyMethods( [ 'getMySqlServerVariant', 'dbSchema', 'tablePrefix' ] )
			->getMock();
		$db->method( 'getMySqlServerVariant' )->willReturn( [ 'MariaDB', '10.4.21' ] );
		TestingAccessWrapper::newFromObject( $db )->platform =
			new MySQLPlatform( new AddQuoterMock() );

		/** @var IDatabase $db */
		$sql = $db->selectSQLText( 'image',
			'img_metadata',
			'*',
			'',
			[ 'MAX_EXECUTION_TIME' => 1 ]
		);

		$this->assertEquals(
			"SET STATEMENT max_statement_time=0.001 FOR SELECT  img_metadata  FROM `image`     ",
			$sql
		);
	}

	/**
	 * @covers \Wikimedia\Rdbms\Database::streamStatementEnd
	 * @covers \Wikimedia\Rdbms\DatabaseMysqlBase::streamStatementEnd
	 */
	public function testStreamStatementEnd() {
		/** @var DatabaseMysqlBase $db */
		$db = $this->getMockForAbstractClass( DatabaseMysqlBase::class, [], '', false );
		$sql = '';

		$newLine = "delimiter\n!! ?";
		$this->assertFalse( $db->streamStatementEnd( $sql, $newLine ) );
		$this->assertSame( '', $newLine );

		$newLine = 'JUST A TEST!!!';
		$this->assertTrue( $db->streamStatementEnd( $sql, $newLine ) );
		$this->assertSame( 'JUST A TEST!', $newLine );
	}
}
