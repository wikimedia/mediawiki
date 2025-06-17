<?php

use MediaWiki\Title\Title;

/**
 * @group Search
 * @covers \FauxSearchResultSet
 */
class FauxSearchResultSetTest extends MediaWikiUnitTestCase {

	public function testConstruct() {
		$titles = array_map( $this->getTitleMock( ... ), [ 'Foo', 'Bar', 'Baz' ] );
		$rs = new FauxSearchResultSet( $titles );
		$titleTexts = [];
		foreach ( $rs as $result ) {
			/** @var SearchResult $result */
			$titleTexts[] = $result->getTitle()->getPrefixedText();
		}
		$this->assertSame( [ 'Foo', 'Bar', 'Baz' ], $titleTexts );
		$this->assertSame( $titles, $rs->extractTitles() );
	}

	public function testGetTotalHits() {
		$titles = array_map( $this->getTitleMock( ... ), [ 'Foo', 'Bar', 'Baz' ] );
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
	 * @param string $titleText
	 * @return Title
	 */
	private function getTitleMock( $titleText ) {
		$title = $this->getMockBuilder( Title::class )
			->disableOriginalConstructor()
			->onlyMethods( [ 'getPrefixedText' ] )
			->getMock();
		$title->method( 'getPrefixedText' )->willReturn( $titleText );
		return $title;
	}

}
