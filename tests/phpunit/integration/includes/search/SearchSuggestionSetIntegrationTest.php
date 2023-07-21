<?php

/**
 * Test for filter utilities.
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
 */

use MediaWiki\Title\Title;

class SearchSuggestionSetIntegrationTest extends MediaWikiIntegrationTestCase {
	/** @return iterable */
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
	 * @covers SearchSuggestionSet::fromTitles
	 * @dataProvider provideTitles
	 */
	public function testFromTitles( array $titles, $expected ): void {
		$actual = SearchSuggestionSet::fromTitles( $titles );

		$this->assertSame( $expected, $actual->getSize() );
		$this->assertInstanceOf( SearchSuggestionSet::class, $actual );
		$this->assertCount( $expected, $actual->getSuggestions() );
	}
}
