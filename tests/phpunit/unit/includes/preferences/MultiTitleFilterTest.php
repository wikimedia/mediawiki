<?php
/**
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
 */

use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Page\PageSelectQueryBuilder;
use MediaWiki\Page\PageStore;
use MediaWiki\Preferences\MultiTitleFilter;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFactory;

/**
 * @group Preferences
 * @coversDefaultClass \MediaWiki\Preferences\MultiTitleFilter
 */
class MultiTitleFilterTest extends MediaWikiUnitTestCase {

	/**
	 * @covers ::__construct
	 */
	public function testConstructNoArgs() {
		$this->assertInstanceOf( MultiTitleFilter::class, new MultiTitleFilter() );
	}

	/**
	 * @covers ::__construct
	 */
	public function testConstructTitleFactory() {
		$this->assertInstanceOf(
			MultiTitleFilter::class,
			new MultiTitleFilter( new TitleFactory() )
		);
	}

	/**
	 * @covers ::filterForForm
	 * @dataProvider filterForFormDataProvider
	 */
	public function testFilterForForm( $expected, $inputValue, $newFromIDsReturnValue ) {
		$titleFormatter = $this->createMock( TitleFormatter::class );
		$titleFormatter->method( 'getPrefixedText' )
			->willReturnOnConsecutiveCalls(
				...array_map(
					static function ( $t ) {
						return $t->getPrefixedText();
					},
					$newFromIDsReturnValue
				)
			);
		$pageStore = $this->getPageStore( $newFromIDsReturnValue );
		$multiTitleFilter = new MultiTitleFilter( null, $pageStore, $titleFormatter );
		$this->assertSame( $expected, $multiTitleFilter->filterForForm( $inputValue ) );
	}

	private function getPageStore( $mockFetchPageRecordsReturn ): PageStore {
		$pageSelectQueryBuilder = $this->getMockBuilder( PageSelectQueryBuilder::class )
			->disableOriginalConstructor()
			->onlyMethods( [ 'fetchPageRecords' ] )
			->getMock();
		$pageSelectQueryBuilder->method( 'fetchPageRecords' )
			->willReturn( new ArrayIterator( $mockFetchPageRecordsReturn ) );

		$pageStore = $this->createMock( PageStore::class );
		$pageStore->method( 'newSelectQueryBuilder' )
			->willReturn( $pageSelectQueryBuilder );

		return $pageStore;
	}

	public function filterForFormDataProvider(): array {
		return [
			[
				'',
				'',
				[]
			],
			[
				'',
				"2\n\3\n\42",
				[]
			],
			[
				"Foo\nBar",
				"2\n\3\n\42",
				[
					$this->getMockTitle( 'Foo' ),
					$this->getMockTitle( 'Bar' )
				]
			]
		];
	}

	/**
	 * @covers ::filterFromForm
	 * @dataProvider filterFromFormDataProvider
	 */
	public function testFilterFromForm( $expected, $titles, $newFromTextValue ) {
		$pageStore = $this->createMock( PageStore::class );
		$pageStore->method( 'getPageByText' )
			->willReturnOnConsecutiveCalls( $newFromTextValue );
		$multiTitleFilter = new MultiTitleFilter( null, $pageStore );
		$this->assertSame( $expected, $multiTitleFilter->filterFromForm( $titles ) );
	}

	public function filterFromFormDataProvider(): array {
		return [
			[
				null,
				'',
				$this->getMockPageIdentityValue( 0, 'Foo' ),
			],
			[
				"42",
				"Foo",
				$this->getMockPageIdentityValue( 42, 'Foo' )
			],
			[
				"",
				"Bar",
				$this->getMockPageIdentityValue( 0, 'Bar' )
			]

		];
	}

	private function getMockPageIdentityValue( int $pageId, string $dbKey ) {
		return new PageIdentityValue( $pageId, NS_MAIN, $dbKey, PageIdentityValue::LOCAL );
	}

	private function getMockTitle( $getTextResult, $articleId = 0 ) {
		$title = $this->createMock( Title::class );
		$title->method( 'getPrefixedText' )->willReturn( $getTextResult );
		$title->method( 'getArticleID' )->willReturn( $articleId );
		return $title;
	}

}
