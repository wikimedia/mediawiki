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

use MediaWiki\Preferences\MultiTitleFilter;

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
		$titleFactory = $this->getMockBuilder( TitleFactory::class )
			->disableOriginalConstructor()
			->getMock();
		$titleFactory->method( 'newFromIDs' )
			->willReturn( $newFromIDsReturnValue );
		$multiTitleFilter = new MultiTitleFilter( $titleFactory );
		$this->assertSame( $expected, $multiTitleFilter->filterForForm( $inputValue ) );
	}

	public function filterForFormDataProvider() :array {
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
		$titleFactory = $this->getMockBuilder( TitleFactory::class )
			->disableOriginalConstructor()
			->getMock();
		$titleFactory->method( 'newFromText' )
			->willReturn( $newFromTextValue );
		$multiTitleFilter = new MultiTitleFilter( $titleFactory );
		$this->assertSame( $expected, $multiTitleFilter->filterFromForm( $titles ) );
	}

	public function filterFromFormDataProvider() :array {
		return [
			[
				null,
				'',
				$this->getMockTitle( 'Foo' ),
			],
			[
				"42",
				"Foo",
				$this->getMockTitle( 'Foo', 42 )
			],
			[
				"",
				"Bar",
				$this->getMockTitle( 'Bar', 0 )
			]

		];
	}

	private function getMockTitle( $getTextResult, $articleId = 0 ) {
		$title = $this->getMockBuilder( Title::class )
			->disableOriginalConstructor()
			->getMock();
		$title->method( 'getPrefixedText' )->willReturn( $getTextResult );
		$title->method( 'getArticleID' )->willReturn( $articleId );
		return $title;
	}

}
