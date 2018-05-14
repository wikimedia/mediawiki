<?php

/**
 * @group API
 * @group medium
 *
 * @covers ApiQueryPrefixSearch
 */
class ApiQueryPrefixSearchTest extends ApiTestCase {
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
		$this->registerMockSearchEngine();
		$response = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'prefixsearch',
			'pssearch' => 'example query terms',
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

	private function registerMockSearchEngine() {
		$this->setMwGlobals( [
			'wgSearchType' => MockCompletionSearchEngine::class,
		] );
	}
}
