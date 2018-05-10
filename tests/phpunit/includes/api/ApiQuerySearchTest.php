<?php

/**
 * @group medium
 * @covers ApiQuerySearch
 */
class ApiQuerySearchTest extends ApiTestCase {
	public function provideSearchResults() {
		return [
			'empty search result' => [ [], [] ],
			'has search results' => [
				[ 'Zomg' ],
				[ $this->mockResult( 'Zomg' ) ],
			],
			'filters broken search results' => [
				[ 'A', 'B' ],
				[
					$this->mockResult( 'a' ),
					$this->mockResult( 'Zomg' )->setBrokenTitle( true ),
					$this->mockResult( 'b' ),
				],
			],
			'filters results with missing revision' => [
				[ 'B', 'A' ],
				[
					$this->mockResult( 'Zomg' )->setMissingRevision( true ),
					$this->mockResult( 'b' ),
					$this->mockResult( 'a' ),
				],
			],
		];
	}

	/**
	 * @dataProvider provideSearchResults
	 */
	public function testSearchResults( $expect, $hits, array $params = [] ) {
		MockSearchEngine::addMockResults( 'my query', $hits );
		list( $response, $request ) = $this->doApiRequest( $params + [
			'action' => 'query',
			'list' => 'search',
			'srsearch' => 'my query',
		] );
		$titles = [];
		foreach ( $response['query']['search'] as $result ) {
			$titles[] = $result['title'];
		}
		$this->assertEquals( $expect, $titles );
	}

	public function provideInterwikiResults() {
		return [
			'empty' => [ [], [] ],
			'one wiki response' => [
				[ 'utwiki' => [ 'Qwerty' ] ],
				[
					SearchResultSet::SECONDARY_RESULTS => [
						'utwiki' => new MockSearchResultSet( [
							$this->mockResult( 'Qwerty' )->setInterwikiPrefix( 'utwiki' ),
						] ),
					],
				]
			],
		];
	}

	/**
	 * @dataProvider provideInterwikiResults
	 */
	public function testInterwikiResults( $expect, $hits, array $params = [] ) {
		MockSearchEngine::setMockInterwikiResults( $hits );
		list( $response, $request ) = $this->doApiRequest( $params + [
			'action' => 'query',
			'list' => 'search',
			'srsearch' => 'my query',
			'srinterwiki' => true,
		] );
		if ( !$expect ) {
			$this->assertArrayNotHasKey( 'interwikisearch', $response['query'] );
			return;
		}
		$results = [];
		$this->assertArrayHasKey( 'interwikisearchinfo', $response['query'] );
		foreach ( $response['query']['interwikisearch'] as $wiki => $wikiResults ) {
			$results[$wiki] = [];
			foreach ( $wikiResults as $wikiResult ) {
				$results[$wiki][] = $wikiResult['title'];
			}
		}
		$this->assertEquals( $expect, $results );
	}

	public function setUp() {
		parent::setUp();
		MockSearchEngine::clearMockResults();
		$this->registerMockSearchEngine();
	}

	private function registerMockSearchEngine() {
		$this->setMwGlobals( [
			'wgSearchType' => MockSearchEngine::class,
		] );
	}

	private function mockResult( $title ) {
		return MockSearchResult::newFromtitle( Title::newFromText( $title ) );
	}

}
