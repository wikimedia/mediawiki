<?php
/**
 * Holds tests for DatabaseMysqlBase MediaWiki class.
 *
 * Copyright © 2013 Antoine Musso
 * Copyright © 2013 Wikimedia Foundation Inc.
 * FIXME GPLv2 license header there.
 */

/**
 * Mock class around abstract class so we can call concrete methods.
 *
 */
class MockDatabaseMysqlBase extends DatabaseMysqlBase {
	// From DatabaseBase
	protected function closeConnection() {}
	protected function doQuery( $sql ) {}

	// From DatabaseMysql
	protected function mysqlConnect( $realServer ) {}
	protected function mysqlFreeResult( $res ) {}
	protected function mysqlFetchObject( $res ) {}
	protected function mysqlFetchArray( $res ) {}
	protected function mysqlNumRows( $res ) {}
	protected function mysqlNumFields( $res ) {}
	protected function mysqlFieldName( $res, $n ) {}
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
	 */
	function testAddIdentifierQuotes( $expected, $in ) {
		$db = new MockDatabaseMysqlBase();
		$quoted = $db->addIdentifierQuotes( $in );
		$this->assertEquals($expected, $quoted);
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
			array( '``', true ),
			array( '``', array() ),
			array( '``', new stdClass() ),

			// We never now what could happen
			array( '`0`', 0 ),
			array( '`1`', 1 ),

			// Whatchout! Should probably use something more meaningful
			array( '`\'`', '\'' ),  # single quote
			array( '`"`', '"' ),   # double quote
			array( '```', '`' ),   # backtick
			array( '`’`', '’' ),   # apostrophe (look at your encyclopedia)

			// Real world:
			array( '`Alix`', 'Alix' ),  # while( ! $recovered ) { sleep(); }
			array( '`Backtick: ```', 'Backtick: `' ),
		);
	}

}
