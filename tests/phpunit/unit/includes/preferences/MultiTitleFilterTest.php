<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Page\PageSelectQueryBuilder;
use MediaWiki\Page\PageStore;
use MediaWiki\Preferences\MultiTitleFilter;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFactory;
use MediaWiki\Title\TitleFormatter;

/**
 * @group Preferences
 * @covers \MediaWiki\Preferences\MultiTitleFilter
 */
class MultiTitleFilterTest extends MediaWikiUnitTestCase {

	public function testConstructNoArgs() {
		$this->assertInstanceOf( MultiTitleFilter::class, new MultiTitleFilter() );
	}

	public function testConstructTitleFactory() {
		$this->assertInstanceOf(
			MultiTitleFilter::class,
			new MultiTitleFilter( new TitleFactory() )
		);
	}

	/**
	 * @dataProvider filterForFormDataProvider
	 */
	public function testFilterForForm( $expected, $inputValue, $newFromIDsReturnValue ) {
		$newFromIDsReturnValuePages = [];
		foreach ( $newFromIDsReturnValue as $s ) {
			$newFromIDsReturnValuePages[] = $this->getMockTitle( $s );
		}
		$titleFormatter = $this->createMock( TitleFormatter::class );
		$titleFormatter->method( 'getPrefixedText' )
			->willReturnOnConsecutiveCalls(
				...array_map(
					static function ( $t ) {
						return $t->getPrefixedText();
					},
					$newFromIDsReturnValuePages
				)
			);
		$pageStore = $this->getPageStore( $newFromIDsReturnValuePages );
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

	public static function filterForFormDataProvider(): array {
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
					'Foo',
					'Bar'
				]
			]
		];
	}

	/**
	 * @dataProvider filterFromFormDataProvider
	 */
	public function testFilterFromForm( $expected, $titles, $newFromTextValue ) {
		$pageStore = $this->createMock( PageStore::class );
		$pageStore->method( 'getPageByText' )
			->willReturnOnConsecutiveCalls( $this->getMockPageIdentityValue( ...$newFromTextValue ) );
		$multiTitleFilter = new MultiTitleFilter( null, $pageStore );
		$this->assertSame( $expected, $multiTitleFilter->filterFromForm( $titles ) );
	}

	public static function filterFromFormDataProvider(): array {
		return [
			[
				null,
				'',
				[ 0, 'Foo' ],
			],
			[
				"42",
				"Foo",
				[ 42, 'Foo' ]
			],
			[
				"",
				"Bar",
				[ 0, 'Bar' ]
			]

		];
	}

	private function getMockPageIdentityValue( int $pageId, string $dbKey ) {
		return PageIdentityValue::localIdentity( $pageId, NS_MAIN, $dbKey );
	}

	private function getMockTitle( $getTextResult, $articleId = 0 ) {
		$title = $this->createMock( Title::class );
		$title->method( 'getPrefixedText' )->willReturn( $getTextResult );
		$title->method( 'getArticleID' )->willReturn( $articleId );
		return $title;
	}

}
