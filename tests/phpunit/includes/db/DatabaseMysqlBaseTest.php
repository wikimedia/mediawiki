<?php
/**
 * Holds tests for DatabaseMysqlBase MediaWiki class.
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
 * @author Bryan Davis
 * @copyright © 2013 Antoine Musso
 * @copyright © 2013 Bryan Davis
 * @copyright © 2013 Wikimedia Foundation Inc.
 */

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

	// From DatabaseMysql
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

class DatabaseMysqlBaseTest extends MediaWikiTestCase {
	/**
	 * @dataProvider provideDiapers
	 * @covers DatabaseMysqlBase::addIdentifierQuotes
	 */
	public function testAddIdentifierQuotes( $expected, $in ) {
		$db = new FakeDatabaseMysqlBase();
		$quoted = $db->addIdentifierQuotes( $in );
		$this->assertEquals( $expected, $quoted );
	}

	/**
	 * Feeds testAddIdentifierQuotes
	 *
	 * Named per bug 20281 convention.
	 */
	function provideDiapers() {
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

	function getMockForViews() {
		$db = $this->getMockBuilder( 'DatabaseMysql' )
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
	 * @covers DatabaseMysqlBase::listViews
	 */
	function testListviews() {
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
	 * @dataProvider provideComparePositions
	 */
	function testHasReached( MySQLMasterPos $lowerPos, MySQLMasterPos $higherPos, $match ) {
		if ( $match ) {
			$this->assertTrue( $lowerPos->channelsMatch( $higherPos ) );

			$this->assertTrue( $higherPos->hasReached( $lowerPos ) );
			$this->assertTrue( $higherPos->hasReached( $higherPos ) );
			$this->assertTrue( $lowerPos->hasReached( $lowerPos ) );
			$this->assertFalse( $lowerPos->hasReached( $higherPos ) );
		} else { // channels don't match
			$this->assertFalse( $lowerPos->channelsMatch( $higherPos ) );

			$this->assertFalse( $higherPos->hasReached( $lowerPos ) );
			$this->assertFalse( $lowerPos->hasReached( $higherPos ) );
		}
	}

	function provideComparePositions() {
		return [
			// Binlog style
			[
				new MySQLMasterPos( 'db1034-bin.000976', '843431247' ),
				new MySQLMasterPos( 'db1034-bin.000976', '843431248' ),
				true
			],
			[
				new MySQLMasterPos( 'db1034-bin.000976', '999' ),
				new MySQLMasterPos( 'db1034-bin.000976', '1000' ),
				true
			],
			[
				new MySQLMasterPos( 'db1034-bin.000976', '999' ),
				new MySQLMasterPos( 'db1035-bin.000976', '1000' ),
				false
			],
			// MySQL GTID style
			[
				new MySQLMasterPos( 'db1-bin.2', '1', '3E11FA47-71CA-11E1-9E33-C80AA9429562:23' ),
				new MySQLMasterPos( 'db1-bin.2', '2', '3E11FA47-71CA-11E1-9E33-C80AA9429562:24' ),
				true
			],
			[
				new MySQLMasterPos( 'db1-bin.2', '1', '3E11FA47-71CA-11E1-9E33-C80AA9429562:99' ),
				new MySQLMasterPos( 'db1-bin.2', '2', '3E11FA47-71CA-11E1-9E33-C80AA9429562:100' ),
				true
			],
			[
				new MySQLMasterPos( 'db1-bin.2', '1', '3E11FA47-71CA-11E1-9E33-C80AA9429562:99' ),
				new MySQLMasterPos( 'db1-bin.2', '2', '1E11FA47-71CA-11E1-9E33-C80AA9429562:100' ),
				false
			],
			// MariaDB GTID style
			[
				new MySQLMasterPos( 'db1-bin.2', '1', '255-11-23' ),
				new MySQLMasterPos( 'db1-bin.2', '2', '255-11-24' ),
				true
			],
			[
				new MySQLMasterPos( 'db1-bin.2', '1', '255-11-99' ),
				new MySQLMasterPos( 'db1-bin.2', '2', '255-11-100' ),
				true
			],
			[
				new MySQLMasterPos( 'db1-bin.2', '1', '255-11-999' ),
				new MySQLMasterPos( 'db1-bin.2', '2', '254-11-1000' ),
				false
			],
		];
	}

	/**
	 * @dataProvider provideChannelPositions
	 */
	function testChannelsMatch( MySQLMasterPos $pos1, MySQLMasterPos $pos2, $matches ) {
		$this->assertEquals( $matches, $pos1->channelsMatch( $pos2 ) );
		$this->assertEquals( $matches, $pos2->channelsMatch( $pos1 ) );
	}

	function provideChannelPositions() {
		return [
			[
				new MySQLMasterPos( 'db1034-bin.000876', '44' ),
				new MySQLMasterPos( 'db1034-bin.000976', '74' ),
				true
			],
			[
				new MySQLMasterPos( 'db1052-bin.000976', '999' ),
				new MySQLMasterPos( 'db1052-bin.000976', '1000' ),
				true
			],
			[
				new MySQLMasterPos( 'db1066-bin.000976', '9999' ),
				new MySQLMasterPos( 'db1035-bin.000976', '10000' ),
				false
			],
			[
				new MySQLMasterPos( 'db1066-bin.000976', '9999' ),
				new MySQLMasterPos( 'trump2016.000976', '10000' ),
				false
			],
		];
	}

	/**
	 * @dataProvider provideLagAmounts
	 */
	function testPtHeartbeat( $lag ) {
		$db = $this->getMockBuilder( 'DatabaseMysql' )
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

		$this->assertGreaterThan( $lag - .010, $lagEst, "Correct heatbeat lag" );
		$this->assertLessThan( $lag + .010, $lagEst, "Correct heatbeat lag" );
	}

	function provideLagAmounts() {
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
}
