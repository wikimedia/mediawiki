<?php

use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Search\SearchUpdate;

/**
 * @group Search
 * @covers \MediaWiki\Search\SearchUpdate
 */
class SearchUpdateTest extends MediaWikiIntegrationTestCase {

	private SearchUpdate $su;

	protected function setUp(): void {
		parent::setUp();
		$pageIdentity = PageIdentityValue::localIdentity( 42, NS_MAIN, 'Main_Page' );
		$this->su = new SearchUpdate( 0, $pageIdentity );
	}

	public function updateText( $text ) {
		return trim( $this->su->updateText( $text ) );
	}

	/**
	 * @dataProvider provideUpdateTextCases
	 */
	public function testUpdateText( string $input, ?string $expected, string $message ) {
		$result = $this->updateText( $input );
		$this->assertSame( $expected, $result, $message );
	}

	public function provideUpdateTextCases(): array {
		return [
			[ '<div>TeSt</div>', 'test', 'HTML stripped, text lowercased' ],
			[ <<<EOT
<table style="color:red; font-size:100px">
	<tr class="scary"><td><div>foo</div></td><tr>bar</td></tr>
	<tr><td>boz</td><tr>quux</td></tr>
</table>
EOT
			, 'foo bar boz quux', 'Stripping HTML tables' ],
			[ 'a https://example.com example', 'a example', 'Stripping bare links' ],
			[ 'a [https://example.com/] example', 'a example', 'External links' ],
			[ 'a [https://example.com/ weblink] example', 'a weblink example', 'External labeled links' ],
			[ 'a [[Wikilink]] example', 'a wikilink example', 'Stripping wikilinks' ],
			[ 'a [[Wikilink|example]] link', 'a wikilink example link', 'Stripping labeled wikilinks' ],
			[ 'a [[game]]s link', 'a game games link', 'Link trails' ],
			[ 'a [[testof|game]]s link', 'a testof game games link', 'Link trails with labels' ],
			[ "dogs' and cat's tails", 'dogs and cat cat\'s tails', 'Possessive apostrophes' ],
			[ "a ''Wiki'' example", 'a  wiki  example', "Strip wiki '' (italics)" ],
			[ "a'''Wiki'''example", 'a wiki example', "Strip wiki ''' (bold)" ],
			[ "a '''''Wiki''''' example", 'a  wiki  example', "Strip wiki ''''' (bold+italics)" ],
			[ "''''", '', 'Only quotes trimmed to empty' ],
			[ 'a > b', 'a b', 'Handle unclosed tags' ],
		];
	}

	public function testT20609() {
		$result = $this->updateText( str_pad( "foo <barbarbar \n", 10000, 'x' ) );
		$this->assertNotEquals( '', $result, 'T20609' );
	}

	/**
	 * T34712: Test if unicode quotes in article links make its search index empty
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
