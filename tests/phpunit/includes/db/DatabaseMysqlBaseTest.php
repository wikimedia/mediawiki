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
	// From DatabaseBase
	function __construct() {
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

	protected function mysqlPing() {
	}

	protected function mysqlRealEscapeString( $s ) {

	}

	// From interface DatabaseType
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

		$db->expects( $this->any() )
			->method( 'query' )
			->with( $this->anything() )
			->will(
				$this->returnValue( null )
			);

		$db->expects( $this->any() )
			->method( 'fetchRow' )
			->with( $this->anything() )
			->will( $this->onConsecutiveCalls(
				[ 'Tables_in_' => 'view1' ],
				[ 'Tables_in_' => 'view2' ],
				[ 'Tables_in_' => 'myview' ],
				false  # no more rows
			) );
		return $db;
	}
	/**
	 * @covers DatabaseMysqlBase::listViews
	 */
	function testListviews() {
		$db = $this->getMockForViews();

		// The first call populate an internal cache of views
		$this->assertEquals( [ 'view1', 'view2', 'myview' ],
			$db->listViews() );
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
	 * @covers DatabaseMysqlBase::isView
	 * @dataProvider provideViewExistanceChecks
	 */
	function testIsView( $isView, $viewName ) {
		$db = $this->getMockForViews();

		switch ( $isView ) {
			case true:
				$this->assertTrue( $db->isView( $viewName ),
					"$viewName should be considered a view" );
			break;

			case false:
				$this->assertFalse( $db->isView( $viewName ),
					"$viewName has not been defined as a view" );
			break;
		}

	}

	function provideViewExistanceChecks() {
		return [
			// format: whether it is a view, view name
			[ true, 'view1' ],
			[ true, 'view2' ],
			[ true, 'myview' ],

			[ false, 'user' ],

			[ false, 'view10' ],
			[ false, 'my' ],
			[ false, 'OH_MY_GOD' ],  # they killed kenny!
		];
	}

	/**
	 * @dataProvider provideComparePositions
	 */
	function testHasReached( MySQLMasterPos $lowerPos, MySQLMasterPos $higherPos ) {
		$this->assertTrue( $higherPos->hasReached( $lowerPos ) );
		$this->assertTrue( $higherPos->hasReached( $higherPos ) );
		$this->assertTrue( $lowerPos->hasReached( $lowerPos ) );
		$this->assertFalse( $lowerPos->hasReached( $higherPos ) );
	}

	function provideComparePositions() {
		return [
			[
				new MySQLMasterPos( 'db1034-bin.000976', '843431247' ),
				new MySQLMasterPos( 'db1034-bin.000976', '843431248' )
			],
			[
				new MySQLMasterPos( 'db1034-bin.000976', '999' ),
				new MySQLMasterPos( 'db1034-bin.000976', '1000' )
			],
			[
				new MySQLMasterPos( 'db1034-bin.000976', '999' ),
				new MySQLMasterPos( 'db1035-bin.000976', '1000' )
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

		$db->expects( $this->any() )
			->method( 'getLagDetectionMethod' )
			->will( $this->returnValue( 'pt-heartbeat' ) );

		$db->expects( $this->any() )
			->method( 'getMasterServerInfo' )
			->will( $this->returnValue( [ 'serverId' => 172, 'asOf' => time() ] ) );

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

		$db->expects( $this->any() )
			->method( 'getHeartbeatData' )
			->with( [ 'server_id' => 172 ] )
			->will( $this->returnValue( [ $ptTimeISO, $now ] ) );

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
