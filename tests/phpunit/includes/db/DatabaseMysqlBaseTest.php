<?php
/**
 * Holds tests for DatabaseMysqlBase MediaWiki class.
 *
 * @section LICENSE
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
	function __construct() {}
	protected function closeConnection() {}
	protected function doQuery( $sql ) {}

	// From DatabaseMysql
	protected function mysqlConnect( $realServer ) {}
	protected function mysqlSetCharset( $charset ) {}
	protected function mysqlFreeResult( $res ) {}
	protected function mysqlFetchObject( $res ) {}
	protected function mysqlFetchArray( $res ) {}
	protected function mysqlNumRows( $res ) {}
	protected function mysqlNumFields( $res ) {}
	protected function mysqlFieldName( $res, $n ) {}
	protected function mysqlFieldType( $res, $n ) {}
	protected function mysqlDataSeek( $res, $row ) {}
	protected function mysqlError( $conn = null ) {}
	protected function mysqlFetchField( $res, $n ) {}
	protected function mysqlPing() {}

	// From interface DatabaseType
	function insertId() {}
	function lastErrno() {}
	function affectedRows() {}
	function getServerVersion() {}
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
			));
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

}
