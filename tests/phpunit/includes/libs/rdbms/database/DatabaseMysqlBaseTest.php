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

use Wikimedia\Rdbms\MySQLMasterPos;
use Wikimedia\TestingAccessWrapper;

class DatabaseMysqlBaseTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;
	use PHPUnit4And6Compat;

	/**
	 * @dataProvider provideDiapers
	 * @covers Wikimedia\Rdbms\DatabaseMysqlBase::addIdentifierQuotes
	 */
	public function testAddIdentifierQuotes( $expected, $in ) {
		$db = $this->getMockBuilder( DatabaseMysqli::class )
			->disableOriginalConstructor()
			->setMethods( null )
			->getMock();

		$quoted = $db->addIdentifierQuotes( $in );
		$this->assertEquals( $expected, $quoted );
	}

	/**
	 * Feeds testAddIdentifierQuotes
	 *
	 * Named per T22281 convention.
	 */
	public static function provideDiapers() {
		return [
			// Format: expected, input
			[ '``', '' ],

			// Yeah I really hate loosely typed PHP idiocies nowadays
			[ '``', null ],

			// Dear codereviewer, guess what addIdentifierQuotes()
			// will return with thoses:
			[ '``', false ],
			[ '`1`', true ],

			// We never know what could happen
			[ '`0`', 0 ],
			[ '`1`', 1 ],

			// Whatchout! Should probably use something more meaningful
			[ "`'`", "'" ],  # single quote
			[ '`"`', '"' ],  # double quote
			[ '````', '`' ], # backtick
			[ '`’`', '’' ],  # apostrophe (look at your encyclopedia)

			// sneaky NUL bytes are lurking everywhere
			[ '``', "\0" ],
			[ '`xyzzy`', "\0x\0y\0z\0z\0y\0" ],

			// unicode chars
			[
				"`\u{0001}a\u{FFFF}b`",
				"\u{0001}a\u{FFFF}b"
			],
			[
				"`\u{0001}\u{FFFF}`",
				"\u{0001}\u{0000}\u{FFFF}\u{0000}"
			],
			[ '`☃`', '☃' ],
			[ '`メインページ`', 'メインページ' ],
			[ '`Басты_бет`', 'Басты_бет' ],

			// Real world:
			[ '`Alix`', 'Alix' ],  # while( ! $recovered ) { sleep(); }
			[ '`Backtick: ```', 'Backtick: `' ],
			[ '`This is a test`', 'This is a test' ],
		];
	}

	private function getMockForViews() {
		$db = $this->getMockBuilder( DatabaseMysqli::class )
			->disableOriginalConstructor()
			->setMethods( [ 'fetchRow', 'query', 'getDBname' ] )
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
	 * @covers Wikimedia\Rdbms\DatabaseMysqlBase::listViews
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
	 * @covers Wikimedia\Rdbms\MySQLMasterPos
	 */
	public function testBinLogName() {
		$pos = new MySQLMasterPos( "db1052.2424/4643", 1 );

		$this->assertEquals( "db1052", $pos->getLogName() );
		$this->assertEquals( "db1052.2424", $pos->getLogFile() );
		$this->assertEquals( [ 2424, 4643 ], $pos->getLogPosition() );
	}

	/**
	 * @dataProvider provideComparePositions
	 * @covers Wikimedia\Rdbms\MySQLMasterPos
	 */
	public function testHasReached(
		MySQLMasterPos $lowerPos, MySQLMasterPos $higherPos, $match, $hetero
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
				new MySQLMasterPos( 'db1034-bin.000976/843431247', $now ),
				new MySQLMasterPos( 'db1034-bin.000976/843431248', $now ),
				true,
				false
			],
			[
				new MySQLMasterPos( 'db1034-bin.000976/999', $now ),
				new MySQLMasterPos( 'db1034-bin.000976/1000', $now ),
				true,
				false
			],
			[
				new MySQLMasterPos( 'db1034-bin.000976/999', $now ),
				new MySQLMasterPos( 'db1035-bin.000976/1000', $now ),
				false,
				false
			],
			// MySQL GTID style
			[
				new MySQLMasterPos( '3E11FA47-71CA-11E1-9E33-C80AA9429562:1-23', $now ),
				new MySQLMasterPos( '3E11FA47-71CA-11E1-9E33-C80AA9429562:5-24', $now ),
				true,
				false
			],
			[
				new MySQLMasterPos( '3E11FA47-71CA-11E1-9E33-C80AA9429562:5-99', $now ),
				new MySQLMasterPos( '3E11FA47-71CA-11E1-9E33-C80AA9429562:1-100', $now ),
				true,
				false
			],
			[
				new MySQLMasterPos( '3E11FA47-71CA-11E1-9E33-C80AA9429562:1-99', $now ),
				new MySQLMasterPos( '1E11FA47-71CA-11E1-9E33-C80AA9429562:1-100', $now ),
				false,
				false
			],
			// MariaDB GTID style
			[
				new MySQLMasterPos( '255-11-23', $now ),
				new MySQLMasterPos( '255-11-24', $now ),
				true,
				false
			],
			[
				new MySQLMasterPos( '255-11-99', $now ),
				new MySQLMasterPos( '255-11-100', $now ),
				true,
				false
			],
			[
				new MySQLMasterPos( '255-11-999', $now ),
				new MySQLMasterPos( '254-11-1000', $now ),
				false,
				false
			],
			[
				new MySQLMasterPos( '255-11-23,256-12-50', $now ),
				new MySQLMasterPos( '255-11-24', $now ),
				true,
				false
			],
			[
				new MySQLMasterPos( '255-11-99,256-12-50,257-12-50', $now ),
				new MySQLMasterPos( '255-11-1000', $now ),
				true,
				false
			],
			[
				new MySQLMasterPos( '255-11-23,256-12-50', $now ),
				new MySQLMasterPos( '255-11-24,155-52-63', $now ),
				true,
				false
			],
			[
				new MySQLMasterPos( '255-11-99,256-12-50,257-12-50', $now ),
				new MySQLMasterPos( '255-11-1000,256-12-51', $now ),
				true,
				false
			],
			[
				new MySQLMasterPos( '255-11-99,256-12-50', $now ),
				new MySQLMasterPos( '255-13-1000,256-14-49', $now ),
				true,
				true
			],
			[
				new MySQLMasterPos( '253-11-999,255-11-999', $now ),
				new MySQLMasterPos( '254-11-1000', $now ),
				false,
				false
			],
		];
	}

	/**
	 * @dataProvider provideChannelPositions
	 * @covers Wikimedia\Rdbms\MySQLMasterPos
	 */
	public function testChannelsMatch( MySQLMasterPos $pos1, MySQLMasterPos $pos2, $matches ) {
		$this->assertEquals( $matches, $pos1->channelsMatch( $pos2 ) );
		$this->assertEquals( $matches, $pos2->channelsMatch( $pos1 ) );

		$roundtripPos = new MySQLMasterPos( (string)$pos1, 1 );
		$this->assertEquals( (string)$pos1, (string)$roundtripPos );
	}

	public static function provideChannelPositions() {
		$now = microtime( true );

		return [
			[
				new MySQLMasterPos( 'db1034-bin.000876/44', $now ),
				new MySQLMasterPos( 'db1034-bin.000976/74', $now ),
				true
			],
			[
				new MySQLMasterPos( 'db1052-bin.000976/999', $now ),
				new MySQLMasterPos( 'db1052-bin.000976/1000', $now ),
				true
			],
			[
				new MySQLMasterPos( 'db1066-bin.000976/9999', $now ),
				new MySQLMasterPos( 'db1035-bin.000976/10000', $now ),
				false
			],
			[
				new MySQLMasterPos( 'db1066-bin.000976/9999', $now ),
				new MySQLMasterPos( 'trump2016.000976/10000', $now ),
				false
			],
		];
	}

	/**
	 * @dataProvider provideCommonDomainGTIDs
	 * @covers Wikimedia\Rdbms\MySQLMasterPos
	 */
	public function testGetRelevantActiveGTIDs( MySQLMasterPos $pos, MySQLMasterPos $ref, $gtids ) {
		$this->assertEquals( $gtids, MySQLMasterPos::getRelevantActiveGTIDs( $pos, $ref ) );
	}

	public static function provideCommonDomainGTIDs() {
		return [
			[
				new MySQLMasterPos( '255-13-99,256-12-50,257-14-50', 1 ),
				new MySQLMasterPos( '255-11-1000', 1 ),
				[ '255-13-99' ]
			],
			[
				( new MySQLMasterPos( '255-13-99,256-12-50,257-14-50', 1 ) )
					->setActiveDomain( 257 ),
				new MySQLMasterPos( '255-11-1000,257-14-30', 1 ),
				[ '257-14-50' ]
			],
			[
				new MySQLMasterPos(
					'2E11FA47-71CA-11E1-9E33-C80AA9429562:1-5,' .
					'3E11FA47-71CA-11E1-9E33-C80AA9429562:20-99,' .
					'7E11FA47-71CA-11E1-9E33-C80AA9429562:1-30',
					1
				),
				new MySQLMasterPos(
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
	 * @covers Wikimedia\Rdbms\DatabaseMysqlBase::getLag
	 * @covers Wikimedia\Rdbms\DatabaseMysqlBase::getLagFromPtHeartbeat
	 */
	public function testPtHeartbeat( $lag ) {
		$db = $this->getMockBuilder( DatabaseMysqli::class )
			->disableOriginalConstructor()
			->setMethods( [
				'getLagDetectionMethod', 'getHeartbeatData', 'getMasterServerInfo' ] )
			->getMock();

		$db->method( 'getLagDetectionMethod' )
			->willReturn( 'pt-heartbeat' );

		$db->method( 'getMasterServerInfo' )
			->willReturn( [ 'serverId' => 172, 'asOf' => time() ] );

		// Fake the current time.
		list( $nowSecFrac, $nowSec ) = explode( ' ', microtime() );
		$now = (float)$nowSec + (float)$nowSecFrac;
		// Fake the heartbeat time.
		// Work arounds for weak DataTime microseconds support.
		$ptTime = $now - $lag;
		$ptSec = (int)$ptTime;
		$ptSecFrac = ( $ptTime - $ptSec );
		$ptDateTime = new DateTime( "@$ptSec" );
		$ptTimeISO = $ptDateTime->format( 'Y-m-d\TH:i:s' );
		$ptTimeISO .= ltrim( number_format( $ptSecFrac, 6 ), '0' );

		$db->method( 'getHeartbeatData' )
			->with( [ 'server_id' => 172 ] )
			->willReturn( [ $ptTimeISO, $now ] );

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
	 * @covers Wikimedia\Rdbms\MySQLMasterPos
	 * @covers Wikimedia\Rdbms\DatabaseMysqlBase::getReplicaPos
	 * @covers Wikimedia\Rdbms\DatabaseMysqlBase::getMasterPos
	 */
	public function testServerGtidTable( $gtable, $rBLtable, $mBLtable, $rGTIDs, $mGTIDs ) {
		$db = $this->getMockBuilder( DatabaseMysqli::class )
			->disableOriginalConstructor()
			->setMethods( [
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
			function ( $role ) use ( $rBLtable, $mBLtable ) {
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

		if ( is_array( $rGTIDs ) ) {
			$this->assertEquals( $rGTIDs, $db->getReplicaPos()->getGTIDs() );
		} else {
			$this->assertEquals( false, $db->getReplicaPos() );
		}
		if ( is_array( $mGTIDs ) ) {
			$this->assertEquals( $mGTIDs, $db->getMasterPos()->getGTIDs() );
		} else {
			$this->assertEquals( false, $db->getMasterPos() );
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
	 * @covers Wikimedia\Rdbms\MySQLMasterPos
	 */
	public function testSerialize() {
		$pos = new MySQLMasterPos( '3E11FA47-71CA-11E1-9E33-C80AA9429562:99', 53636363 );
		$roundtripPos = unserialize( serialize( $pos ) );

		$this->assertEquals( $pos, $roundtripPos );

		$pos = new MySQLMasterPos( '255-11-23', 53636363 );
		$roundtripPos = unserialize( serialize( $pos ) );

		$this->assertEquals( $pos, $roundtripPos );
	}

	/**
	 * @covers Wikimedia\Rdbms\DatabaseMysqlBase::isInsertSelectSafe
	 * @dataProvider provideInsertSelectCases
	 */
	public function testInsertSelectIsSafe( $insertOpts, $selectOpts, $row, $safe ) {
		$db = $this->getMockBuilder( DatabaseMysqli::class )
			->disableOriginalConstructor()
			->setMethods( [ 'getReplicationSafetyInfo' ] )
			->getMock();
		$db->method( 'getReplicationSafetyInfo' )->willReturn( (object)$row );
		$dbw = TestingAccessWrapper::newFromObject( $db );

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
		$db = $this->getMockBuilder( DatabaseMysqli::class )
			->disableOriginalConstructor()
			->setMethods( null )
			->getMock();
		$output = $db->buildIntegerCast( 'fieldName' );
		$this->assertSame( 'CAST( fieldName AS SIGNED )', $output );
	}

	/**
	 * @covers Wikimedia\Rdbms\Database::setIndexAliases
	 */
	public function testIndexAliases() {
		$db = $this->getMockBuilder( DatabaseMysqli::class )
			->disableOriginalConstructor()
			->setMethods( [ 'mysqlRealEscapeString', 'dbSchema', 'tablePrefix' ] )
			->getMock();
		$db->method( 'mysqlRealEscapeString' )->willReturnCallback(
			function ( $s ) {
				return str_replace( "'", "\\'", $s );
			}
		);

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
	 * @covers Wikimedia\Rdbms\Database::setTableAliases
	 */
	public function testTableAliases() {
		$db = $this->getMockBuilder( DatabaseMysqli::class )
			->disableOriginalConstructor()
			->setMethods( [ 'mysqlRealEscapeString', 'dbSchema', 'tablePrefix' ] )
			->getMock();
		$db->method( 'mysqlRealEscapeString' )->willReturnCallback(
			function ( $s ) {
				return str_replace( "'", "\\'", $s );
			}
		);

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
}
