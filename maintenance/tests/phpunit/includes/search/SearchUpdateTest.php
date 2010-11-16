<?php

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

/**
 * @group Search
 */
class SearchUpdateTest extends PHPUnit_Framework_TestCase {
	static $searchType;

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
		global $wgSearchType;

		self::$searchType  = $wgSearchType;
		$wgSearchType = 'MockSearch';
	}

	function tearDown() {
		global $wgSearchType;

		$wgSearchType = self::$searchType;
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
