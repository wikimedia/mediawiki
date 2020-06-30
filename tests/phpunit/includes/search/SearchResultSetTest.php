<?php

class SearchResultSetTest extends MediaWikiIntegrationTestCase {
	/**
	 * @covers SearchResultSet::getIterator
	 * @covers BaseSearchResultSet::next
	 * @covers BaseSearchResultSet::rewind
	 */
	public function testIterate() {
		$result = SearchResult::newFromTitle( Title::newMainPage() );
		$resultSet = new MockSearchResultSet( [ $result ] );
		$this->assertSame( 1, $resultSet->numRows() );
		$count = 0;
		foreach ( $resultSet as $iterResult ) {
			$this->assertEquals( $result, $iterResult );
			$count++;
		}
		$this->assertSame( 1, $count );

		$this->hideDeprecated( 'BaseSearchResultSet::rewind' );
		$this->hideDeprecated( 'BaseSearchResultSet::next' );
		$resultSet->rewind();
		$count = 0;
		while ( ( $iterResult = $resultSet->next() ) !== false ) {
			$this->assertEquals( $result, $iterResult );
			$count++;
		}
		$this->assertSame( 1, $count );
	}

	/**
	 * @covers SearchResultSetTrait::augmentResult
	 * @covers SearchResultSetTrait::setAugmentedData
	 */
	public function testDelayedResultAugment() {
		$result = SearchResult::newFromTitle( Title::newMainPage() );
		$resultSet = new MockSearchResultSet( [ $result ] );
		$resultSet->augmentResult( $result );
		$this->assertEquals( [], $result->getExtensionData() );
		$resultSet->setAugmentedData( 'foo', [
			$result->getTitle()->getArticleID() => 'bar'
		] );
		$this->assertEquals( [ 'foo' => 'bar' ], $result->getExtensionData() );
	}

	/**
	 * @covers SearchResultSet::shrink
	 * @covers SearchResultSet::count
	 * @covers SearchResultSet::hasMoreResults
	 */
	public function testHasMoreResults() {
		$result = SearchResult::newFromTitle( Title::newMainPage() );
		$resultSet = new MockSearchResultSet( array_fill( 0, 3, $result ) );
		$this->assertCount( 3, $resultSet );
		$this->assertFalse( $resultSet->hasMoreResults() );
		$resultSet->shrink( 3 );
		$this->assertFalse( $resultSet->hasMoreResults() );
		$resultSet->shrink( 2 );
		$this->assertTrue( $resultSet->hasMoreResults() );
	}

	/**
	 * @covers SearchResultSet::shrink
	 */
	public function testShrink() {
		$results = array_fill( 0, 3, SearchResult::newFromTitle( Title::newMainPage() ) );
		$resultSet = new MockSearchResultSet( $results );
		$this->assertCount( 3, $resultSet->extractResults() );
		$this->assertCount( 3, $resultSet->extractTitles() );
		$resultSet->shrink( 1 );
		$this->assertCount( 1, $resultSet->extractResults() );
		$this->assertCount( 1, $resultSet->extractTitles() );
	}
}
