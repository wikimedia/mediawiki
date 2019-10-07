<?php

use PHPUnit\Framework\MockObject\MockObject;

/**
 * @group Search
 * @covers FauxSearchResultSet
 */
class FauxSearchResultSetTest extends MediaWikiUnitTestCase {

	public function testConstruct() {
		$titles = array_map( [ $this, 'getTitleMock' ], [ 'Foo', 'Bar', 'Baz' ] );
		$rs = new FauxSearchResultSet( $titles );
		$titleTexts = [];
		foreach ( $rs as $result ) {
			/** @var $result SearchResult */
			$titleTexts[] = $result->getTitle()->getPrefixedText();
		}
		$this->assertSame( [ 'Foo', 'Bar', 'Baz' ], $titleTexts );
		$this->assertSame( $titles, $rs->extractTitles() );
	}

	public function testGetTotalHits() {
		$titles = array_map( [ $this, 'getTitleMock' ], [ 'Foo', 'Bar', 'Baz' ] );
		$rs = new FauxSearchResultSet( $titles );
		$this->assertSame( 3, $rs->getTotalHits() );
		$this->assertFalse( $rs->hasMoreResults() );

		$rs = new FauxSearchResultSet( $titles, 5 );
		$this->assertCount( 3, $rs );
		$this->assertSame( 5, $rs->getTotalHits() );
		$this->assertTrue( $rs->hasMoreResults() );

		$rs = new FauxSearchResultSet( $titles, 3 );
		$this->assertFalse( $rs->hasMoreResults() );
		$rs = new FauxSearchResultSet( $titles, 1 );
		$this->assertSame( 3, $rs->getTotalHits() );
		$this->assertFalse( $rs->hasMoreResults() );
	}

	/**
	 * @param $titleText
	 * @return Title|MockObject
	 */
	private function getTitleMock( $titleText ) {
		$title = $this->getMockBuilder( Title::class )
			->disableOriginalConstructor()
			->setMethods( [ 'getPrefixedText' ] )
			->getMock();
		$title->method( 'getPrefixedText' )->willReturn( $titleText );
		return $title;
	}

}
