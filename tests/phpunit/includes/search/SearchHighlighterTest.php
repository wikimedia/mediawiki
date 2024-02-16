<?php

/**
 * @group Search
 */
class SearchHighlighterTest extends \MediaWikiIntegrationTestCase {
	/**
	 * @dataProvider provideHighlightSimple
	 * @covers \SearchHighlighter::highlightSimple
	 */
	public function testHighlightSimple( string $wikiText, string $searchTerm, string $expectedOutput, int $contextChars ) {
		$highlighter = new \SearchHighlighter( false );
		$actual = $highlighter->highlightSimple( $wikiText, [ $searchTerm ], 1, $contextChars );
		$this->assertEquals( $expectedOutput, $actual );
	}

	public static function provideHighlightSimple() {
		return [
			'no match' => [
				'this is a very simple text.',
				'cannotmatch',
				'',
				10
			],
			'match a single word at the end of the string' => [
				'this is a very simple text.',
				'text',
				"this is a very simple <span class=\"searchmatch\">text</span>.\n",
				40
			],
			'utf-8 sequences should not be broken' => [
				"text with long trailing UTF-8 sequences: " . str_repeat( "\u{1780}", 6 ) . ".",
				'text',
				"<span class=\"searchmatch\">text</span> with long trailing UTF-8 sequences: " . str_repeat( "\u{1780}", 5 ) . "\n",
				41
			],
		];
	}
}
