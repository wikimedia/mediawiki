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
		return array(
			// Format: expected, input
			array( '``', '' ),

			// Yeah I really hate loosely typed PHP idiocies nowadays
			array( '``', null ),

			// Dear codereviewer, guess what addIdentifierQuotes()
			// will return with thoses:
			array( '``', false ),
			array( '`1`', true ),

			// We never know what could happen
			array( '`0`', 0 ),
			array( '`1`', 1 ),

			// Whatchout! Should probably use something more meaningful
			array( "`'`", "'" ),  # single quote
			array( '`"`', '"' ),  # double quote
			array( '````', '`' ), # backtick
			array( '`’`', '’' ),  # apostrophe (look at your encyclopedia)

			// sneaky NUL bytes are lurking everywhere
			array( '``', "\0" ),
			array( '`xyzzy`', "\0x\0y\0z\0z\0y\0" ),

			// unicode chars
			array(
				self::createUnicodeString( '`\u0001a\uFFFFb`' ),
				self::createUnicodeString( '\u0001a\uFFFFb' )
			),
			array(
				self::createUnicodeString( '`\u0001\uFFFF`' ),
				self::createUnicodeString( '\u0001\u0000\uFFFF\u0000' )
			),
			array( '`☃`', '☃' ),
			array( '`メインページ`', 'メインページ' ),
			array( '`Басты_бет`', 'Басты_бет' ),

			// Real world:
			array( '`Alix`', 'Alix' ),  # while( ! $recovered ) { sleep(); }
			array( '`Backtick: ```', 'Backtick: `' ),
			array( '`This is a test`', 'This is a test' ),
		);
	}

	private static function createUnicodeString( $str ) {
		return json_decode( '"' . $str . '"' );
	}

	function getMockForViews() {
		$db = $this->getMockBuilder( 'DatabaseMysql' )
			->disableOriginalConstructor()
			->setMethods( array( 'fetchRow', 'query' ) )
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
				array( 'Tables_in_' => 'view1' ),
				array( 'Tables_in_' => 'view2' ),
				array( 'Tables_in_' => 'myview' ),
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
		$this->assertEquals( array( 'view1', 'view2', 'myview' ),
			$db->listViews() );
		$this->assertEquals( array( 'view1', 'view2', 'myview' ),
			$db->listViews() );

		// Prefix filtering
		$this->assertEquals( array( 'view1', 'view2' ),
			$db->listViews( 'view' ) );
		$this->assertEquals( array( 'myview' ),
			$db->listViews( 'my' ) );
		$this->assertEquals( array(),
			$db->listViews( 'UNUSED_PREFIX' ) );
		$this->assertEquals( array( 'view1', 'view2', 'myview' ),
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
		return array(
			// format: whether it is a view, view name
			array( true, 'view1' ),
			array( true, 'view2' ),
			array( true, 'myview' ),

			array( false, 'user' ),

			array( false, 'view10' ),
			array( false, 'my' ),
			array( false, 'OH_MY_GOD' ),  # they killed kenny!
		);
	}

	function testMasterPos() {
		$pos1 = new MySQLMasterPos( 'db1034-bin.000976', '843431247' );
		$pos2 = new MySQLMasterPos( 'db1034-bin.000976', '843431248' );

		$this->assertTrue( $pos1->hasReached( $pos1 ) );
		$this->assertTrue( $pos2->hasReached( $pos2 ) );
		$this->assertTrue( $pos2->hasReached( $pos1 ) );
		$this->assertFalse( $pos1->hasReached( $pos2 ) );
	}

	/**
	 * @dataProvider provideLagAmounts
	 */
	function testPtHeartbeat( $lag ) {
		$db = $this->getMockBuilder( 'DatabaseMysql' )
			->disableOriginalConstructor()
			->setMethods( array(
				'getLagDetectionMethod', 'getHeartbeatData', 'getMasterServerInfo' ) )
			->getMock();

		$db->expects( $this->any() )
			->method( 'getLagDetectionMethod' )
			->will( $this->returnValue( 'pt-heartbeat' ) );

		$db->expects( $this->any() )
			->method( 'getMasterServerInfo' )
			->will( $this->returnValue( array( 'serverId' => 172, 'asOf' => time() ) ) );

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
			->with( 172 )
			->will( $this->returnValue( array( $ptTimeISO, $now ) ) );

		$db->setLBInfo( 'clusterMasterHost', 'db1052' );
		$lagEst = $db->getLag();

		$this->assertGreaterThan( $lag - .010, $lagEst, "Correct heatbeat lag" );
		$this->assertLessThan( $lag + .010, $lagEst, "Correct heatbeat lag" );
	}

	function provideLagAmounts() {
		return array(
			array( 0 ),
			array( 0.3 ),
			array( 6.5 ),
			array( 10.1 ),
			array( 200.2 ),
			array( 400.7 ),
			array( 600.22 ),
			array( 1000.77 ),
		);
	}
}
