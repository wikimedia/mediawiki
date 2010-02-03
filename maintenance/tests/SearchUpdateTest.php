<?php

class DatabaseMock extends DatabaseBase {
	function __construct( $server = false, $user = false, $password = false, $dbName = false,
		$failFunction = false, $flags = 0, $tablePrefix = 'get from global' )
	{
		$this->mConn = true;
		$this->mOpened = true;
	}

	function open( $server, $user, $password, $dbName ) { return true; }
	function doQuery( $sql ) {}
	function fetchObject( $res ) {}
	function fetchRow( $res ) {}
	function numRows( $res ) {}
	function numFields( $res ) {}
	function fieldName( $res, $n ) {}
	function insertId() {}
	function dataSeek( $res, $row ) {}
	function lastErrno() { return 0; }
	function lastError() { return ''; }
	function affectedRows() {}
	function fieldInfo( $table, $field ) {}
	function strencode( $s ) {}
	function getSoftwareLink() {}
	function getServerVersion() {}
	function getType() {}
}

class MockSearch extends SearchEngine {
	public static $id;
	public static $title;
	public static $text;

	public function __construct( $db ) {
	}

	public function update( $id, $title, $text ) {
		self::$id = $id;
		self::$title = $title;
		self::$text = $text;
	}
}

class SearchUpdateTest extends PHPUnit_Framework_TestCase {

	function update( $text, $title = 'Test', $id = 1 ) {
		$u = new SearchUpdate( $id, $title, $text );
		$u->doUpdate();
		return array( MockSearch::$title, MockSearch::$text );
	}

	function updateText( $text ) {
		list( $title, $resultText ) = $this->update( $text );
		$resultText = trim( $resultText ); // abstract from some implementation details
		return $resultText;
	}

	function setUp() {
		global $wgSearchType, $wgDBtype, $wgLBFactoryConf, $wgDBservers;
		$wgSearchType = 'MockSearch';
		$wgDBtype = 'mock';
		$wgLBFactoryConf['class'] = 'LBFactory_Simple';
		$wgDBservers = null;
		LBFactory::destroyInstance();
	}

	function tearDown() {
		LBFactory::destroyInstance();
	}

	function testUpdateText() {
		$this->assertEquals(
			'test',
			$this->updateText( '<div>TeSt</div>' ),
			'HTML stripped, text lowercased'
		);

		$this->assertEquals(
			'foo bar boz quux',
			$this->updateText( <<<EOT
<table style="color:red; font-size:100px">
	<tr class="scary"><td><div>foo</div></td><tr>bar</td></tr>
	<tr><td>boz</td><tr>quux</td></tr>
</table>
EOT
			), 'Stripping HTML tables' );

		$this->assertEquals(
			'a b',
			$this->updateText( 'a > b' ),
			'Handle unclosed tags'
		);

		$text = str_pad( "foo <barbarbar \n", 10000, 'x' );

		$this->assertNotEquals(
			'',
			$this->updateText( $text ),
			'Bug 18609'
		);
	}
}
