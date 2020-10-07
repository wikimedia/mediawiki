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
				[ $this->mockResultClosure( 'Zomg' ) ],
			],
			'filters broken search results' => [
				[ 'A', 'B' ],
				[
					$this->mockResultClosure( 'a' ),
					$this->mockResultClosure( 'Zomg', [ 'setBrokenTitle' => true ] ),
					$this->mockResultClosure( 'b' ),
				],
			],
			'filters results with missing revision' => [
				[ 'B', 'A' ],
				[
					$this->mockResultClosure( 'Zomg', [ 'setMissingRevision' => true ] ),
					$this->mockResultClosure( 'b' ),
					$this->mockResultClosure( 'a' ),
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
					ISearchResultSet::SECONDARY_RESULTS => [
						'utwiki' => new MockSearchResultSet( [
							$this->mockResultClosure(
								'Qwerty',
								[ 'setInterwikiPrefix' => 'utwiki' ]
							),
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

	protected function setUp() : void {
		parent::setUp();
		MockSearchEngine::clearMockResults();
		$this->registerMockSearchEngine();
	}

	private function registerMockSearchEngine() {
		$this->setMwGlobals( [
			'wgSearchType' => MockSearchEngine::class,
		] );
	}

	/**
	 * Returns a closure that evaluates to a MockSearchResult, to be resolved by
	 * MockSearchEngine::addMockResults() or MockresultSet::extractResults().
	 *
	 * This is needed because MockSearchResults cannot be instantiated in a data provider,
	 * since they load revisions. This would hit the "real" database instead of the mock
	 * database, which in turn may cause cache pollution and other inconsistencies, see T202641.
	 *
	 * @param string $title
	 * @param array $setters
	 * @return callable function(): MockSearchResult
	 */
	private function mockResultClosure( $title, $setters = [] ) {
		return function () use ( $title, $setters ){
			$result = new MockSearchResult( Title::newFromText( $title ) );

			foreach ( $setters as $method => $param ) {
				$result->$method( $param );
			}

			return $result;
		};
	}

}
