<?php

use MediaWiki\MediaWikiServices;

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers ApiQueryWatchlistRaw
 */
class ApiQueryWatchlistRawIntegrationTest extends ApiTestCase {

	protected function setUp() {
		parent::setUp();
		self::$users['ApiQueryWatchlistRawIntegrationTestUser']
			= $this->getMutableTestUser();
		self::$users['ApiQueryWatchlistRawIntegrationTestUser2']
			= $this->getMutableTestUser();
	}

	private function getLoggedInTestUser() {
		return self::$users['ApiQueryWatchlistRawIntegrationTestUser']->getUser();
	}

	private function getNotLoggedInTestUser() {
		return self::$users['ApiQueryWatchlistRawIntegrationTestUser2']->getUser();
	}

	private function getWatchedItemStore() {
		return MediaWikiServices::getInstance()->getWatchedItemStore();
	}

	private function doListWatchlistRawRequest( array $params = [] ) {
		return $this->doApiRequest( array_merge(
			[ 'action' => 'query', 'list' => 'watchlistraw' ],
			$params
		), null, false, $this->getLoggedInTestUser() );
	}

	private function doGeneratorWatchlistRawRequest( array $params = [] ) {
		return $this->doApiRequest( array_merge(
			[ 'action' => 'query', 'generator' => 'watchlistraw' ],
			$params
		), null, false, $this->getLoggedInTestUser() );
	}

	private function getItemsFromApiResponse( array $response ) {
		return $response[0]['watchlistraw'];
	}

	public function testListWatchlistRaw_returnsWatchedItems() {
		$store = $this->getWatchedItemStore();
		$store->addWatch(
			$this->getLoggedInTestUser(),
			new TitleValue( 0, 'ApiQueryWatchlistRawIntegrationTestPage' )
		);

		$result = $this->doListWatchlistRawRequest();

		$this->assertArrayHasKey( 'watchlistraw', $result[0] );

		$this->assertEquals(
			[
				[
					'ns' => 0,
					'title' => 'ApiQueryWatchlistRawIntegrationTestPage',
				],
			],
			$this->getItemsFromApiResponse( $result )
		);
	}

	public function testPropChanged_addsNotificationTimestamp() {
		$target = new TitleValue( 0, 'ApiQueryWatchlistRawIntegrationTestPage' );
		$otherUser = $this->getNotLoggedInTestUser();

		$store = $this->getWatchedItemStore();

		$store->addWatch( $this->getLoggedInTestUser(), $target );
		$store->updateNotificationTimestamp(
			$otherUser,
			$target,
			'20151212010101'
		);

		$result = $this->doListWatchlistRawRequest( [ 'wrprop' => 'changed' ] );

		$this->assertEquals(
			[
				[
					'ns' => 0,
					'title' => 'ApiQueryWatchlistRawIntegrationTestPage',
					'changed' => '2015-12-12T01:01:01Z',
				],
			],
			$this->getItemsFromApiResponse( $result )
		);
	}

	public function testNamespaceParam() {
		$store = $this->getWatchedItemStore();

		$store->addWatchBatchForUser( $this->getLoggedInTestUser(), [
			new TitleValue( 0, 'ApiQueryWatchlistRawIntegrationTestPage' ),
			new TitleValue( 1, 'ApiQueryWatchlistRawIntegrationTestPage' ),
		] );

		$result = $this->doListWatchlistRawRequest( [ 'wrnamespace' => '0' ] );

		$this->assertEquals(
			[
				[
					'ns' => 0,
					'title' => 'ApiQueryWatchlistRawIntegrationTestPage',
				],
			],
			$this->getItemsFromApiResponse( $result )
		);
	}

	public function testShowChangedParams() {
		$subjectTarget = new TitleValue( 0, 'ApiQueryWatchlistRawIntegrationTestPage' );
		$talkTarget = new TitleValue( 1, 'ApiQueryWatchlistRawIntegrationTestPage' );
		$otherUser = $this->getNotLoggedInTestUser();

		$store = $this->getWatchedItemStore();

		$store->addWatchBatchForUser( $this->getLoggedInTestUser(), [
			$subjectTarget,
			$talkTarget,
		] );
		$store->updateNotificationTimestamp(
			$otherUser,
			$subjectTarget,
			'20151212010101'
		);

		$resultChanged = $this->doListWatchlistRawRequest(
			[ 'wrprop' => 'changed', 'wrshow' => WatchedItemQueryService::FILTER_CHANGED ]
		);
		$resultNotChanged = $this->doListWatchlistRawRequest(
			[ 'wrprop' => 'changed', 'wrshow' => WatchedItemQueryService::FILTER_NOT_CHANGED ]
		);

		$this->assertEquals(
			[
				[
					'ns' => 0,
					'title' => 'ApiQueryWatchlistRawIntegrationTestPage',
					'changed' => '2015-12-12T01:01:01Z',
				],
			],
			$this->getItemsFromApiResponse( $resultChanged )
		);

		$this->assertEquals(
			[
				[
					'ns' => 1,
					'title' => 'Talk:ApiQueryWatchlistRawIntegrationTestPage',
				],
			],
			$this->getItemsFromApiResponse( $resultNotChanged )
		);
	}

	public function testLimitParam() {
		$store = $this->getWatchedItemStore();

		$store->addWatchBatchForUser( $this->getLoggedInTestUser(), [
			new TitleValue( 0, 'ApiQueryWatchlistRawIntegrationTestPage1' ),
			new TitleValue( 1, 'ApiQueryWatchlistRawIntegrationTestPage1' ),
			new TitleValue( 0, 'ApiQueryWatchlistRawIntegrationTestPage2' ),
		] );

		$resultWithoutLimit = $this->doListWatchlistRawRequest();
		$resultWithLimit = $this->doListWatchlistRawRequest( [ 'wrlimit' => 2 ] );

		$this->assertEquals(
			[
				[
					'ns' => 0,
					'title' => 'ApiQueryWatchlistRawIntegrationTestPage1',
				],
				[
					'ns' => 0,
					'title' => 'ApiQueryWatchlistRawIntegrationTestPage2',
				],
				[
					'ns' => 1,
					'title' => 'Talk:ApiQueryWatchlistRawIntegrationTestPage1',
				],
			],
			$this->getItemsFromApiResponse( $resultWithoutLimit )
		);
		$this->assertEquals(
			[
				[
					'ns' => 0,
					'title' => 'ApiQueryWatchlistRawIntegrationTestPage1',
				],
				[
					'ns' => 0,
					'title' => 'ApiQueryWatchlistRawIntegrationTestPage2',
				],
			],
			$this->getItemsFromApiResponse( $resultWithLimit )
		);

		$this->assertArrayNotHasKey( 'continue', $resultWithoutLimit[0] );
		$this->assertArrayHasKey( 'continue', $resultWithLimit[0] );
		$this->assertArrayHasKey( 'wrcontinue', $resultWithLimit[0]['continue'] );
	}

	public function testDirParams() {
		$store = $this->getWatchedItemStore();

		$store->addWatchBatchForUser( $this->getLoggedInTestUser(), [
			new TitleValue( 0, 'ApiQueryWatchlistRawIntegrationTestPage1' ),
			new TitleValue( 1, 'ApiQueryWatchlistRawIntegrationTestPage1' ),
			new TitleValue( 0, 'ApiQueryWatchlistRawIntegrationTestPage2' ),
		] );

		$resultDirAsc = $this->doListWatchlistRawRequest( [ 'wrdir' => 'ascending' ] );
		$resultDirDesc = $this->doListWatchlistRawRequest( [ 'wrdir' => 'descending' ] );

		$this->assertEquals(
			[
				[
					'ns' => 0,
					'title' => 'ApiQueryWatchlistRawIntegrationTestPage1',
				],
				[
					'ns' => 0,
					'title' => 'ApiQueryWatchlistRawIntegrationTestPage2',
				],
				[
					'ns' => 1,
					'title' => 'Talk:ApiQueryWatchlistRawIntegrationTestPage1',
				],
			],
			$this->getItemsFromApiResponse( $resultDirAsc )
		);

		$this->assertEquals(
			[
				[
					'ns' => 1,
					'title' => 'Talk:ApiQueryWatchlistRawIntegrationTestPage1',
				],
				[
					'ns' => 0,
					'title' => 'ApiQueryWatchlistRawIntegrationTestPage2',
				],
				[
					'ns' => 0,
					'title' => 'ApiQueryWatchlistRawIntegrationTestPage1',
				],
			],
			$this->getItemsFromApiResponse( $resultDirDesc )
		);
	}

	public function testAscendingIsDefaultOrder() {
		$store = $this->getWatchedItemStore();

		$store->addWatchBatchForUser( $this->getLoggedInTestUser(), [
			new TitleValue( 0, 'ApiQueryWatchlistRawIntegrationTestPage1' ),
			new TitleValue( 1, 'ApiQueryWatchlistRawIntegrationTestPage1' ),
			new TitleValue( 0, 'ApiQueryWatchlistRawIntegrationTestPage2' ),
		] );

		$resultNoDir = $this->doListWatchlistRawRequest();
		$resultAscDir = $this->doListWatchlistRawRequest( [ 'wrdir' => 'ascending' ] );

		$this->assertEquals(
			$this->getItemsFromApiResponse( $resultNoDir ),
			$this->getItemsFromApiResponse( $resultAscDir )
		);
	}

	public function testFromTitleParam() {
		$store = $this->getWatchedItemStore();

		$store->addWatchBatchForUser( $this->getLoggedInTestUser(), [
			new TitleValue( 0, 'ApiQueryWatchlistRawIntegrationTestPage1' ),
			new TitleValue( 0, 'ApiQueryWatchlistRawIntegrationTestPage2' ),
			new TitleValue( 0, 'ApiQueryWatchlistRawIntegrationTestPage3' ),
		] );

		$result = $this->doListWatchlistRawRequest( [
			'wrfromtitle' => 'ApiQueryWatchlistRawIntegrationTestPage2',
		] );

		$this->assertEquals(
			[
				[
					'ns' => 0,
					'title' => 'ApiQueryWatchlistRawIntegrationTestPage2',
				],
				[
					'ns' => 0,
					'title' => 'ApiQueryWatchlistRawIntegrationTestPage3',
				],
			],
			$this->getItemsFromApiResponse( $result )
		);
	}

	public function testToTitleParam() {
		$store = $this->getWatchedItemStore();

		$store->addWatchBatchForUser( $this->getLoggedInTestUser(), [
			new TitleValue( 0, 'ApiQueryWatchlistRawIntegrationTestPage1' ),
			new TitleValue( 0, 'ApiQueryWatchlistRawIntegrationTestPage2' ),
			new TitleValue( 0, 'ApiQueryWatchlistRawIntegrationTestPage3' ),
		] );

		$result = $this->doListWatchlistRawRequest( [
			'wrtotitle' => 'ApiQueryWatchlistRawIntegrationTestPage2',
		] );

		$this->assertEquals(
			[
				[
					'ns' => 0,
					'title' => 'ApiQueryWatchlistRawIntegrationTestPage1',
				],
				[
					'ns' => 0,
					'title' => 'ApiQueryWatchlistRawIntegrationTestPage2',
				],
			],
			$this->getItemsFromApiResponse( $result )
		);
	}

	public function testContinueParam() {
		$store = $this->getWatchedItemStore();

		$store->addWatchBatchForUser( $this->getLoggedInTestUser(), [
			new TitleValue( 0, 'ApiQueryWatchlistRawIntegrationTestPage1' ),
			new TitleValue( 0, 'ApiQueryWatchlistRawIntegrationTestPage2' ),
			new TitleValue( 0, 'ApiQueryWatchlistRawIntegrationTestPage3' ),
		] );

		$firstResult = $this->doListWatchlistRawRequest( [ 'wrlimit' => 2 ] );
		$continuationParam = $firstResult[0]['continue']['wrcontinue'];

		$this->assertEquals( '0|ApiQueryWatchlistRawIntegrationTestPage3', $continuationParam );

		$continuedResult = $this->doListWatchlistRawRequest( [ 'wrcontinue' => $continuationParam ] );

		$this->assertEquals(
			[
				[
					'ns' => 0,
					'title' => 'ApiQueryWatchlistRawIntegrationTestPage3',
				]
			],
			$this->getItemsFromApiResponse( $continuedResult )
		);
	}

	public function fromTitleToTitleContinueComboProvider() {
		return [
			[
				[
					'wrfromtitle' => 'ApiQueryWatchlistRawIntegrationTestPage1',
					'wrtotitle' => 'ApiQueryWatchlistRawIntegrationTestPage2',
				],
				[
					[ 'ns' => 0, 'title' => 'ApiQueryWatchlistRawIntegrationTestPage1' ],
					[ 'ns' => 0, 'title' => 'ApiQueryWatchlistRawIntegrationTestPage2' ],
				],
			],
			[
				[
					'wrfromtitle' => 'ApiQueryWatchlistRawIntegrationTestPage1',
					'wrcontinue' => '0|ApiQueryWatchlistRawIntegrationTestPage3',
				],
				[
					[ 'ns' => 0, 'title' => 'ApiQueryWatchlistRawIntegrationTestPage3' ],
				],
			],
			[
				[
					'wrtotitle' => 'ApiQueryWatchlistRawIntegrationTestPage3',
					'wrcontinue' => '0|ApiQueryWatchlistRawIntegrationTestPage2',
				],
				[
					[ 'ns' => 0, 'title' => 'ApiQueryWatchlistRawIntegrationTestPage2' ],
					[ 'ns' => 0, 'title' => 'ApiQueryWatchlistRawIntegrationTestPage3' ],
				],
			],
			[
				[
					'wrfromtitle' => 'ApiQueryWatchlistRawIntegrationTestPage1',
					'wrtotitle' => 'ApiQueryWatchlistRawIntegrationTestPage3',
					'wrcontinue' => '0|ApiQueryWatchlistRawIntegrationTestPage3',
				],
				[
					[ 'ns' => 0, 'title' => 'ApiQueryWatchlistRawIntegrationTestPage3' ],
				],
			],
		];
	}

	/**
	 * @dataProvider fromTitleToTitleContinueComboProvider
	 */
	public function testFromTitleToTitleContinueCombo( array $params, array $expectedItems ) {
		$store = $this->getWatchedItemStore();

		$store->addWatchBatchForUser( $this->getLoggedInTestUser(), [
			new TitleValue( 0, 'ApiQueryWatchlistRawIntegrationTestPage1' ),
			new TitleValue( 0, 'ApiQueryWatchlistRawIntegrationTestPage2' ),
			new TitleValue( 0, 'ApiQueryWatchlistRawIntegrationTestPage3' ),
		] );

		$result = $this->doListWatchlistRawRequest( $params );

		$this->assertEquals( $expectedItems, $this->getItemsFromApiResponse( $result ) );
	}

	public function fromTitleToTitleContinueSelfContradictoryComboProvider() {
		return [
			[
				[
					'wrfromtitle' => 'ApiQueryWatchlistRawIntegrationTestPage2',
					'wrtotitle' => 'ApiQueryWatchlistRawIntegrationTestPage1',
				]
			],
			[
				[
					'wrfromtitle' => 'ApiQueryWatchlistRawIntegrationTestPage1',
					'wrtotitle' => 'ApiQueryWatchlistRawIntegrationTestPage2',
					'wrdir' => 'descending',
				]
			],
			[
				[
					'wrtotitle' => 'ApiQueryWatchlistRawIntegrationTestPage1',
					'wrcontinue' => '0|ApiQueryWatchlistRawIntegrationTestPage2',
				]
			],
		];
	}

	/**
	 * @dataProvider fromTitleToTitleContinueSelfContradictoryComboProvider
	 */
	public function testFromTitleToTitleContinueSelfContradictoryCombo( array $params ) {
		$store = $this->getWatchedItemStore();

		$store->addWatchBatchForUser( $this->getLoggedInTestUser(), [
			new TitleValue( 0, 'ApiQueryWatchlistRawIntegrationTestPage1' ),
			new TitleValue( 0, 'ApiQueryWatchlistRawIntegrationTestPage2' ),
		] );

		$result = $this->doListWatchlistRawRequest( $params );

		$this->assertEmpty( $this->getItemsFromApiResponse( $result ) );
		$this->assertArrayNotHasKey( 'continue', $result[0] );
	}

	public function testOwnerAndTokenParams() {
		$otherUser = $this->getNotLoggedInTestUser();
		$otherUser->setOption( 'watchlisttoken', '1234567890' );
		$otherUser->saveSettings();

		$store = $this->getWatchedItemStore();
		$store->addWatchBatchForUser( $otherUser, [
			new TitleValue( 0, 'ApiQueryWatchlistRawIntegrationTestPage1' ),
			new TitleValue( 1, 'ApiQueryWatchlistRawIntegrationTestPage1' ),
		] );

		ObjectCache::getMainWANInstance()->clearProcessCache();
		$result = $this->doListWatchlistRawRequest( [
			'wrowner' => $otherUser->getName(),
			'wrtoken' => '1234567890',
		] );

		$this->assertEquals(
			[
				[
					'ns' => 0,
					'title' => 'ApiQueryWatchlistRawIntegrationTestPage1',
				],
				[
					'ns' => 1,
					'title' => 'Talk:ApiQueryWatchlistRawIntegrationTestPage1',
				],
			],
			$this->getItemsFromApiResponse( $result )
		);
	}

	public function testOwnerAndTokenParams_wrongToken() {
		$otherUser = $this->getNotLoggedInTestUser();
		$otherUser->setOption( 'watchlisttoken', '1234567890' );
		$otherUser->saveSettings();

		$this->setExpectedException( ApiUsageException::class, 'Incorrect watchlist token provided' );

		$this->doListWatchlistRawRequest( [
			'wrowner' => $otherUser->getName(),
			'wrtoken' => 'wrong-token',
		] );
	}

	public function testOwnerAndTokenParams_userHasNoWatchlistToken() {
		$this->setExpectedException( ApiUsageException::class, 'Incorrect watchlist token provided' );

		$this->doListWatchlistRawRequest( [
			'wrowner' => $this->getNotLoggedInTestUser()->getName(),
			'wrtoken' => 'some-watchlist-token',
		] );
	}

	public function testGeneratorWatchlistRawPropInfo_returnsWatchedItems() {
		$store = $this->getWatchedItemStore();
		$store->addWatch(
			$this->getLoggedInTestUser(),
			new TitleValue( 0, 'ApiQueryWatchlistRawIntegrationTestPage' )
		);

		$result = $this->doGeneratorWatchlistRawRequest( [ 'prop' => 'info' ] );

		$this->assertArrayHasKey( 'query', $result[0] );
		$this->assertArrayHasKey( 'pages', $result[0]['query'] );
		$this->assertCount( 1, $result[0]['query']['pages'] );

		// $result[0]['query']['pages'] uses page ids as keys
		$item = array_values( $result[0]['query']['pages'] )[0];

		$this->assertEquals( 0, $item['ns'] );
		$this->assertEquals( 'ApiQueryWatchlistRawIntegrationTestPage', $item['title'] );
	}

}
