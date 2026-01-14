<?php

use MediaWiki\Title\Title;

class SearchNearMatchResultSetTest extends PHPUnit\Framework\TestCase {
	use MediaWikiCoversValidator;

	/**
	 * @covers \MediaWiki\Search\SearchNearMatchResultSet::__construct
	 * @covers \MediaWiki\Search\SearchNearMatchResultSet::numRows
	 */
	public function testNumRows() {
		$resultSet = new SearchNearMatchResultSet( null );
		$this->assertSame( 0, $resultSet->numRows() );

		$resultSet = new SearchNearMatchResultSet( Title::newMainPage() );
		$this->assertSame( 1, $resultSet->numRows() );
	}
}
