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
class SearchUpdateTest extends MediaWikiTestCase {

	/**
	 * @var SearchUpdate
	 */
	private $su;

	protected function setUp() {
		parent::setUp();
		$this->setMwGlobals( 'wgSearchType', 'MockSearch' );
		$this->su = new SearchUpdate( 0, "" );
	}

	public function updateText( $text ) {
		return trim( $this->su->updateText( $text ) );
	}

	/**
	 * @covers SearchUpdate::updateText
	 */
	public function testUpdateText() {
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

	/**
	 * @covers SearchUpdate::updateText
	 * Test bug 32712
	 * Test if unicode quotes in article links make its search index empty
	 */
	public function testUnicodeLinkSearchIndexError() {
		$text = "text „http://example.com“ text";
		$result = $this->updateText( $text );
		$processed = preg_replace( '/Q/u', 'Q', $result );
		$this->assertTrue(
			$processed != '',
			'Link surrounded by unicode quotes should not fail UTF-8 validation'
		);
	}
}
