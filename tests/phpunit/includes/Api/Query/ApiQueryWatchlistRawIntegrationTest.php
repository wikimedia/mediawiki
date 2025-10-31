<?php

namespace MediaWiki\Tests\Api\Query;

use MediaWiki\Page\PageReferenceValue;
use MediaWiki\Tests\Api\ApiTestCase;
use MediaWiki\User\User;
use MediaWiki\Watchlist\WatchedItemQueryService;

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers \MediaWiki\Api\ApiQueryWatchlistRaw
 */
class ApiQueryWatchlistRawIntegrationTest extends ApiTestCase {
	// TODO: This test should use Authority, but can't due to User::saveSettings
	/** @var User */
	private $loggedInUser;
	/** @var User */
	private $notLoggedInUser;

	protected function setUp(): void {
		parent::setUp();

		$this->loggedInUser = $this->getMutableTestUser()->getUser();
		$this->notLoggedInUser = $this->getMutableTestUser()->getUser();
	}

	private function getLoggedInTestUser(): User {
		return $this->loggedInUser;
	}

	private function getNotLoggedInTestUser(): User {
		return $this->notLoggedInUser;
	}

	private function getWatchedItemStore() {
		return $this->getServiceContainer()->getWatchedItemStore();
	}

	private static function makeTitle( int $ns, string $dbKey ): PageReferenceValue {
		return PageReferenceValue::localReference( $ns, $dbKey );
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
			self::makeTitle( NS_MAIN, 'ApiQueryWatchlistRawIntegrationTestPage' )
		);

		$result = $this->doListWatchlistRawRequest();

		$this->assertArrayHasKey( 'watchlistraw', $result[0] );

		$this->assertEquals(
			[
				[
					'ns' => NS_MAIN,
					'title' => 'ApiQueryWatchlistRawIntegrationTestPage',
				],
			],
			$this->getItemsFromApiResponse( $result )
		);
	}

	public function testPropChanged_addsNotificationTimestamp() {
		$target = self::makeTitle( NS_MAIN, 'ApiQueryWatchlistRawIntegrationTestPage' );
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
					'ns' => NS_MAIN,
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
			self::makeTitle( NS_MAIN, 'ApiQueryWatchlistRawIntegrationTestPage' ),
			self::makeTitle( NS_TALK, 'ApiQueryWatchlistRawIntegrationTestPage' ),
		] );

		$result = $this->doListWatchlistRawRequest( [ 'wrnamespace' => '0' ] );

		$this->assertEquals(
			[
				[
					'ns' => NS_MAIN,
					'title' => 'ApiQueryWatchlistRawIntegrationTestPage',
				],
			],
			$this->getItemsFromApiResponse( $result )
		);
	}

	public function testShowChangedParams() {
		$subjectTarget = self::makeTitle( NS_MAIN, 'ApiQueryWatchlistRawIntegrationTestPage' );
		$talkTarget = self::makeTitle( NS_TALK, 'ApiQueryWatchlistRawIntegrationTestPage' );
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
					'ns' => NS_MAIN,
					'title' => 'ApiQueryWatchlistRawIntegrationTestPage',
					'changed' => '2015-12-12T01:01:01Z',
				],
			],
			$this->getItemsFromApiResponse( $resultChanged )
		);

		$this->assertEquals(
			[
				[
					'ns' => NS_TALK,
					'title' => 'Talk:ApiQueryWatchlistRawIntegrationTestPage',
				],
			],
			$this->getItemsFromApiResponse( $resultNotChanged )
		);
	}

	public function testLimitParam() {
		$store = $this->getWatchedItemStore();

		$store->addWatchBatchForUser( $this->getLoggedInTestUser(), [
			self::makeTitle( NS_MAIN, 'ApiQueryWatchlistRawIntegrationTestPage1' ),
			self::makeTitle( NS_TALK, 'ApiQueryWatchlistRawIntegrationTestPage1' ),
			self::makeTitle( NS_MAIN, 'ApiQueryWatchlistRawIntegrationTestPage2' ),
		] );

		$resultWithoutLimit = $this->doListWatchlistRawRequest();
		$resultWithLimit = $this->doListWatchlistRawRequest( [ 'wrlimit' => 2 ] );

		$this->assertEquals(
			[
				[
					'ns' => NS_MAIN,
					'title' => 'ApiQueryWatchlistRawIntegrationTestPage1',
				],
				[
					'ns' => NS_MAIN,
					'title' => 'ApiQueryWatchlistRawIntegrationTestPage2',
				],
				[
					'ns' => NS_TALK,
					'title' => 'Talk:ApiQueryWatchlistRawIntegrationTestPage1',
				],
			],
			$this->getItemsFromApiResponse( $resultWithoutLimit )
		);
		$this->assertEquals(
			[
				[
					'ns' => NS_MAIN,
					'title' => 'ApiQueryWatchlistRawIntegrationTestPage1',
				],
				[
					'ns' => NS_MAIN,
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
			self::makeTitle( NS_MAIN, 'ApiQueryWatchlistRawIntegrationTestPage1' ),
			self::makeTitle( NS_TALK, 'ApiQueryWatchlistRawIntegrationTestPage1' ),
			self::makeTitle( NS_MAIN, 'ApiQueryWatchlistRawIntegrationTestPage2' ),
		] );

		$resultDirAsc = $this->doListWatchlistRawRequest( [ 'wrdir' => 'ascending' ] );
		$resultDirDesc = $this->doListWatchlistRawRequest( [ 'wrdir' => 'descending' ] );

		$this->assertEquals(
			[
				[
					'ns' => NS_MAIN,
					'title' => 'ApiQueryWatchlistRawIntegrationTestPage1',
				],
				[
					'ns' => NS_MAIN,
					'title' => 'ApiQueryWatchlistRawIntegrationTestPage2',
				],
				[
					'ns' => NS_TALK,
					'title' => 'Talk:ApiQueryWatchlistRawIntegrationTestPage1',
				],
			],
			$this->getItemsFromApiResponse( $resultDirAsc )
		);

		$this->assertEquals(
			[
				[
					'ns' => NS_TALK,
					'title' => 'Talk:ApiQueryWatchlistRawIntegrationTestPage1',
				],
				[
					'ns' => NS_MAIN,
					'title' => 'ApiQueryWatchlistRawIntegrationTestPage2',
				],
				[
					'ns' => NS_MAIN,
					'title' => 'ApiQueryWatchlistRawIntegrationTestPage1',
				],
			],
			$this->getItemsFromApiResponse( $resultDirDesc )
		);
	}

	public function testAscendingIsDefaultOrder() {
		$store = $this->getWatchedItemStore();

		$store->addWatchBatchForUser( $this->getLoggedInTestUser(), [
			self::makeTitle( NS_MAIN, 'ApiQueryWatchlistRawIntegrationTestPage1' ),
			self::makeTitle( NS_TALK, 'ApiQueryWatchlistRawIntegrationTestPage1' ),
			self::makeTitle( NS_MAIN, 'ApiQueryWatchlistRawIntegrationTestPage2' ),
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
			self::makeTitle( NS_MAIN, 'ApiQueryWatchlistRawIntegrationTestPage1' ),
			self::makeTitle( NS_MAIN, 'ApiQueryWatchlistRawIntegrationTestPage2' ),
			self::makeTitle( NS_MAIN, 'ApiQueryWatchlistRawIntegrationTestPage3' ),
		] );

		$result = $this->doListWatchlistRawRequest( [
			'wrfromtitle' => 'ApiQueryWatchlistRawIntegrationTestPage2',
		] );

		$this->assertEquals(
			[
				[
					'ns' => NS_MAIN,
					'title' => 'ApiQueryWatchlistRawIntegrationTestPage2',
				],
				[
					'ns' => NS_MAIN,
					'title' => 'ApiQueryWatchlistRawIntegrationTestPage3',
				],
			],
			$this->getItemsFromApiResponse( $result )
		);
	}

	public function testToTitleParam() {
		$store = $this->getWatchedItemStore();

		$store->addWatchBatchForUser( $this->getLoggedInTestUser(), [
			self::makeTitle( NS_MAIN, 'ApiQueryWatchlistRawIntegrationTestPage1' ),
			self::makeTitle( NS_MAIN, 'ApiQueryWatchlistRawIntegrationTestPage2' ),
			self::makeTitle( NS_MAIN, 'ApiQueryWatchlistRawIntegrationTestPage3' ),
		] );

		$result = $this->doListWatchlistRawRequest( [
			'wrtotitle' => 'ApiQueryWatchlistRawIntegrationTestPage2',
		] );

		$this->assertEquals(
			[
				[
					'ns' => NS_MAIN,
					'title' => 'ApiQueryWatchlistRawIntegrationTestPage1',
				],
				[
					'ns' => NS_MAIN,
					'title' => 'ApiQueryWatchlistRawIntegrationTestPage2',
				],
			],
			$this->getItemsFromApiResponse( $result )
		);
	}

	public function testContinueParam() {
		$store = $this->getWatchedItemStore();

		$store->addWatchBatchForUser( $this->getLoggedInTestUser(), [
			self::makeTitle( NS_MAIN, 'ApiQueryWatchlistRawIntegrationTestPage1' ),
			self::makeTitle( NS_MAIN, 'ApiQueryWatchlistRawIntegrationTestPage2' ),
			self::makeTitle( NS_MAIN, 'ApiQueryWatchlistRawIntegrationTestPage3' ),
		] );

		$firstResult = $this->doListWatchlistRawRequest( [ 'wrlimit' => 2 ] );
		$continuationParam = $firstResult[0]['continue']['wrcontinue'];

		$this->assertSame( '0|ApiQueryWatchlistRawIntegrationTestPage3', $continuationParam );

		$continuedResult = $this->doListWatchlistRawRequest( [ 'wrcontinue' => $continuationParam ] );

		$this->assertEquals(
			[
				[
					'ns' => NS_MAIN,
					'title' => 'ApiQueryWatchlistRawIntegrationTestPage3',
				]
			],
			$this->getItemsFromApiResponse( $continuedResult )
		);
	}

	public static function fromTitleToTitleContinueComboProvider() {
		return [
			[
				[
					'wrfromtitle' => 'ApiQueryWatchlistRawIntegrationTestPage1',
					'wrtotitle' => 'ApiQueryWatchlistRawIntegrationTestPage2',
				],
				[
					[ 'ns' => NS_MAIN, 'title' => 'ApiQueryWatchlistRawIntegrationTestPage1' ],
					[ 'ns' => NS_MAIN, 'title' => 'ApiQueryWatchlistRawIntegrationTestPage2' ],
				],
			],
			[
				[
					'wrfromtitle' => 'ApiQueryWatchlistRawIntegrationTestPage1',
					'wrcontinue' => '0|ApiQueryWatchlistRawIntegrationTestPage3',
				],
				[
					[ 'ns' => NS_MAIN, 'title' => 'ApiQueryWatchlistRawIntegrationTestPage3' ],
				],
			],
			[
				[
					'wrtotitle' => 'ApiQueryWatchlistRawIntegrationTestPage3',
					'wrcontinue' => '0|ApiQueryWatchlistRawIntegrationTestPage2',
				],
				[
					[ 'ns' => NS_MAIN, 'title' => 'ApiQueryWatchlistRawIntegrationTestPage2' ],
					[ 'ns' => NS_MAIN, 'title' => 'ApiQueryWatchlistRawIntegrationTestPage3' ],
				],
			],
			[
				[
					'wrfromtitle' => 'ApiQueryWatchlistRawIntegrationTestPage1',
					'wrtotitle' => 'ApiQueryWatchlistRawIntegrationTestPage3',
					'wrcontinue' => '0|ApiQueryWatchlistRawIntegrationTestPage3',
				],
				[
					[ 'ns' => NS_MAIN, 'title' => 'ApiQueryWatchlistRawIntegrationTestPage3' ],
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
			self::makeTitle( NS_MAIN, 'ApiQueryWatchlistRawIntegrationTestPage1' ),
			self::makeTitle( NS_MAIN, 'ApiQueryWatchlistRawIntegrationTestPage2' ),
			self::makeTitle( NS_MAIN, 'ApiQueryWatchlistRawIntegrationTestPage3' ),
		] );

		$result = $this->doListWatchlistRawRequest( $params );

		$this->assertEquals( $expectedItems, $this->getItemsFromApiResponse( $result ) );
	}

	public static function fromTitleToTitleContinueSelfContradictoryComboProvider() {
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
			self::makeTitle( NS_MAIN, 'ApiQueryWatchlistRawIntegrationTestPage1' ),
			self::makeTitle( NS_MAIN, 'ApiQueryWatchlistRawIntegrationTestPage2' ),
		] );

		$result = $this->doListWatchlistRawRequest( $params );

		$this->assertSame( [], $this->getItemsFromApiResponse( $result ) );
		$this->assertArrayNotHasKey( 'continue', $result[0] );
	}

	public function testOwnerAndTokenParams() {
		$services = $this->getServiceContainer();
		$userOptionsManager = $services->getUserOptionsManager();

		$otherUser = $this->getNotLoggedInTestUser();
		$userOptionsManager->setOption( $otherUser, 'watchlisttoken', '1234567890' );
		$otherUser->saveSettings();

		$store = $this->getWatchedItemStore();
		$store->addWatchBatchForUser( $otherUser, [
			self::makeTitle( NS_MAIN, 'ApiQueryWatchlistRawIntegrationTestPage1' ),
			self::makeTitle( NS_TALK, 'ApiQueryWatchlistRawIntegrationTestPage1' ),
		] );

		$services->getMainWANObjectCache()->clearProcessCache();
		$result = $this->doListWatchlistRawRequest( [
			'wrowner' => $otherUser->getName(),
			'wrtoken' => '1234567890',
		] );

		$this->assertEquals(
			[
				[
					'ns' => NS_MAIN,
					'title' => 'ApiQueryWatchlistRawIntegrationTestPage1',
				],
				[
					'ns' => NS_TALK,
					'title' => 'Talk:ApiQueryWatchlistRawIntegrationTestPage1',
				],
			],
			$this->getItemsFromApiResponse( $result )
		);
	}

	public function testOwnerAndTokenParams_wrongToken() {
		$userOptionsManager = $this->getServiceContainer()->getUserOptionsManager();

		$otherUser = $this->getNotLoggedInTestUser();
		$userOptionsManager->setOption( $otherUser, 'watchlisttoken', '1234567890' );
		$otherUser->saveSettings();

		$this->expectApiErrorCode( 'bad_wltoken' );

		$this->doListWatchlistRawRequest( [
			'wrowner' => $otherUser->getName(),
			'wrtoken' => 'wrong-token',
		] );
	}

	public function testOwnerAndTokenParams_userHasNoWatchlistToken() {
		$this->expectApiErrorCode( 'bad_wltoken' );

		$this->doListWatchlistRawRequest( [
			'wrowner' => $this->getNotLoggedInTestUser()->getName(),
			'wrtoken' => 'some-watchlist-token',
		] );
	}

	public function testGeneratorWatchlistRawPropInfo_returnsWatchedItems() {
		$store = $this->getWatchedItemStore();
		$store->addWatch(
			$this->getLoggedInTestUser(),
			self::makeTitle( NS_MAIN, 'ApiQueryWatchlistRawIntegrationTestPage' )
		);

		$result = $this->doGeneratorWatchlistRawRequest( [ 'prop' => 'info' ] );

		$this->assertArrayHasKey( 'query', $result[0] );
		$this->assertArrayHasKey( 'pages', $result[0]['query'] );
		$this->assertCount( 1, $result[0]['query']['pages'] );

		// $result[0]['query']['pages'] uses page ids as keys
		$item = array_values( $result[0]['query']['pages'] )[0];

		$this->assertSame( NS_MAIN, $item['ns'] );
		$this->assertEquals( 'ApiQueryWatchlistRawIntegrationTestPage', $item['title'] );
	}

}
