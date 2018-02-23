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

use Wikimedia\Rdbms\TransactionProfiler;
use Wikimedia\Rdbms\DatabaseDomain;
use Wikimedia\Rdbms\MySQLMasterPos;
use Wikimedia\Rdbms\DatabaseMysqlBase;
use Wikimedia\Rdbms\DatabaseMysqli;
use Wikimedia\Rdbms\Database;

/**
 * Fake class around abstract class so we can call concrete methods.
 */
class FakeDatabaseMysqlBase extends DatabaseMysqlBase {
	// From Database
	function __construct() {
		$this->profiler = new ProfilerStub( [] );
		$this->trxProfiler = new TransactionProfiler();
		$this->cliMode = true;
		$this->connLogger = new \Psr\Log\NullLogger();
		$this->queryLogger = new \Psr\Log\NullLogger();
		$this->errorLogger = function ( Exception $e ) {
			wfWarn( get_class( $e ) . ": {$e->getMessage()}" );
		};
		$this->currentDomain = DatabaseDomain::newUnspecified();
	}

	protected function closeConnection() {
	}

	protected function doQuery( $sql ) {
	}

	protected function fetchAffectedRowCount() {
	}

	// From DatabaseMysqli
	protected function mysqlConnect( $realServer ) {
	}

	protected function mysqlSetCharset( $charset ) {
	}

	protected function mysqlFreeResult( $res ) {
	}

	protected function mysqlFetchObject( $res ) {
	}

	protected function mysqlFetchArray( $res ) {
	}

	protected function mysqlNumRows( $res ) {
	}

	protected function mysqlNumFields( $res ) {
	}

	protected function mysqlFieldName( $res, $n ) {
	}

	protected function mysqlFieldType( $res, $n ) {
	}

	protected function mysqlDataSeek( $res, $row ) {
	}

	protected function mysqlError( $conn = null ) {
	}

	protected function mysqlFetchField( $res, $n ) {
	}

	protected function mysqlRealEscapeString( $s ) {
	}

	function insertId() {
	}

	function lastErrno() {
	}

	function affectedRows() {
	}

	function getServerVersion() {
	}
}

class DatabaseMysqlBaseTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	/**
	 * @dataProvider provideDiapers
	 * @covers Wikimedia\Rdbms\DatabaseMysqlBase::addIdentifierQuotes
	 */
	public function testAddIdentifierQuotes( $expected, $in ) {
		$db = new FakeDatabaseMysqlBase();
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
				self::createUnicodeString( '`\u0001a\uFFFFb`' ),
				self::createUnicodeString( '\u0001a\uFFFFb' )
			],
			[
				self::createUnicodeString( '`\u0001\uFFFF`' ),
				self::createUnicodeString( '\u0001\u0000\uFFFF\u0000' )
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

	private static function createUnicodeString( $str ) {
		return json_decode( '"' . $str . '"' );
	}

	private function getMockForViews() {
		$db = $this->getMockBuilder( DatabaseMysqli::class )
			->disableOriginalConstructor()
			->setMethods( [ 'fetchRow', 'query' ] )
			->getMock();

		$db->method( 'query' )
			->with( $this->anything() )
			->willReturn( new FakeResultWrapper( [
				(object)[ 'Tables_in_' => 'view1' ],
				(object)[ 'Tables_in_' => 'view2' ],
				(object)[ 'Tables_in_' => 'myview' ]
			] ) );

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

	public function testBinLogName() {
		$pos = new MySQLMasterPos( "db1052.2424/4643", 1 );

		$this->assertEquals( "db1052", $pos->binlog );
		$this->assertEquals( "db1052.2424", $pos->getLogFile() );
		$this->assertEquals( [ 2424, 4643 ], $pos->pos );
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
				new MySQLMasterPos( '3E11FA47-71CA-11E1-9E33-C80AA9429562:23', $now ),
				new MySQLMasterPos( '3E11FA47-71CA-11E1-9E33-C80AA9429562:24', $now ),
				true,
				false
			],
			[
				new MySQLMasterPos( '3E11FA47-71CA-11E1-9E33-C80AA9429562:99', $now ),
				new MySQLMasterPos( '3E11FA47-71CA-11E1-9E33-C80AA9429562:100', $now ),
				true,
				false
			],
			[
				new MySQLMasterPos( '3E11FA47-71CA-11E1-9E33-C80AA9429562:99', $now ),
				new MySQLMasterPos( '1E11FA47-71CA-11E1-9E33-C80AA9429562:100', $now ),
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
	public function testCommonGtidDomains( MySQLMasterPos $pos, MySQLMasterPos $ref, $gtids ) {
		$this->assertEquals( $gtids, MySQLMasterPos::getCommonDomainGTIDs( $pos, $ref ) );
	}

	public static function provideCommonDomainGTIDs() {
		return [
			[
				new MySQLMasterPos( '255-13-99,256-12-50,257-14-50', 1 ),
				new MySQLMasterPos( '255-11-1000', 1 ),
				[ '255-13-99' ]
			],
			[
				new MySQLMasterPos(
					'2E11FA47-71CA-11E1-9E33-C80AA9429562:5,' .
					'3E11FA47-71CA-11E1-9E33-C80AA9429562:99,' .
					'7E11FA47-71CA-11E1-9E33-C80AA9429562:30',
					1
				),
				new MySQLMasterPos(
					'1E11FA47-71CA-11E1-9E33-C80AA9429562:100,' .
					'3E11FA47-71CA-11E1-9E33-C80AA9429562:66',
					1
				),
				[ '3E11FA47-71CA-11E1-9E33-C80AA9429562:99' ]
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
	 * @expectedException UnexpectedValueException
	 * @covers Wikimedia\Rdbms\Database::setFlag
	 */
	public function testDBOIgnoreSet() {
		$db = new FakeDatabaseMysqlBase();

		$db->setFlag( Database::DBO_IGNORE );
	}

	/**
	 * @expectedException UnexpectedValueException
	 * @covers Wikimedia\Rdbms\Database::clearFlag
	 */
	public function testDBOIgnoreClear() {
		$db = new FakeDatabaseMysqlBase();

		$db->clearFlag( Database::DBO_IGNORE );
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
}
