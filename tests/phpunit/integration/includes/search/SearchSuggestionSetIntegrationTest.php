<?php

/**
 * Test for filter utilities.
 *
 * @license GPL-2.0-or-later
 */

use MediaWiki\Title\Title;

class SearchSuggestionSetIntegrationTest extends MediaWikiIntegrationTestCase {

	public static function provideTitles(): iterable {
		$mainspaceTitle1 = Title::makeTitle( NS_MAIN, 'Title' );
		$mainspaceTitle1->resetArticleID( 10 );
		yield 'Array of 1 Title with NS:0' => [ [ $mainspaceTitle1 ], 1 ];

		$mainspaceTitle2 = Title::makeTitle( NS_MAIN, 'Title1' );
		$mainspaceTitle2->resetArticleID( 20 );
		$mainspaceTitle3 = Title::makeTitle( NS_MAIN, 'Title2' );
		$mainspaceTitle3->resetArticleID( 30 );
		yield 'Array of 2 Titles with NS:0' => [
			[ $mainspaceTitle2, $mainspaceTitle3 ],
			2
		];

		$talkTitle = Title::makeTitle( NS_TALK, 'Test' );
		$talkTitle->resetArticleID( 40 );
		yield 'Array of another Title with NS:1' => [ [ $talkTitle ], 1 ];
	}

	/**
	 * NOTE: This is made an integration test because SearchSuggestion::fromText()
	 *   calls Title::isValid() when following the execution and that tries to
	 *   access MediaWiki services to get a Title Parser object which is not possible
	 *   in a unit test as services are not available. That's why this ends up being
	 *   an integration test instead.
	 *
	 * @covers \SearchSuggestionSet::fromTitles
	 * @dataProvider provideTitles
	 */
	public function testFromTitles( array $titles, $expected ): void {
		$actual = SearchSuggestionSet::fromTitles( $titles );

		$this->assertSame( $expected, $actual->getSize() );
		$this->assertInstanceOf( SearchSuggestionSet::class, $actual );
		$this->assertCount( $expected, $actual->getSuggestions() );
	}
}
