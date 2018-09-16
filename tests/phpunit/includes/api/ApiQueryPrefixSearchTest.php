<?php

/**
 * @group API
 * @group medium
 * @group Database
 *
 * @covers ApiQueryPrefixSearch
 */
class ApiQueryPrefixSearchTest extends ApiTestCase {
	const TEST_QUERY = 'unittest';

	public function setUp() {
		parent::setUp();
		$this->setMwGlobals( [
			'wgSearchType' => MockCompletionSearchEngine::class,
		] );
		MockCompletionSearchEngine::clearMockResults();
		$results = [];
		foreach ( range( 0, 10 ) as $i ) {
			$title = "Search_Result_$i";
			$results[] = $title;
			$this->editPage( $title, 'hi there' );
		}
		MockCompletionSearchEngine::addMockResults( self::TEST_QUERY, $results );
	}

	public function offsetContinueProvider() {
		return [
			'no offset' => [ 2, 2, 0, 2 ],
			'with offset' => [ 7, 2, 5, 2 ],
			'past end, no offset' => [ null, 11, 0, 20 ],
			'past end, with offset' => [ null, 5, 6, 10 ],
		];
	}

	/**
	 * @dataProvider offsetContinueProvider
	 */
	public function testOffsetContinue( $expectedOffset, $expectedResults, $offset, $limit ) {
		$response = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'prefixsearch',
			'pssearch' => self::TEST_QUERY,
			'psoffset' => $offset,
			'pslimit' => $limit,
		] );
		$result = $response[0];
		$this->assertArrayNotHasKey( 'warnings', $result );
		$suggestions = $result['query']['prefixsearch'];
		$this->assertCount( $expectedResults, $suggestions );
		if ( $expectedOffset == null ) {
			$this->assertArrayNotHasKey( 'continue', $result );
		} else {
			$this->assertArrayHasKey( 'continue', $result );
			$this->assertEquals( $expectedOffset, $result['continue']['psoffset'] );
		}
	}
}
