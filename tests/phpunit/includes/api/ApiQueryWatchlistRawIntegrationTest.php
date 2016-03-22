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
			= new TestUser( 'ApiQueryWatchlistRawIntegrationTestUser' );
		$this->doLogin( 'ApiQueryWatchlistRawIntegrationTestUser' );
	}

	private function getTestUser() {
		return ( new TestUser( 'ApiQueryWatchlistRawIntegrationTestUser' ) )->getUser();
	}

	public function addDBData() {
		$user = $this->getTestUser();
		$store = MediaWikiServices::getInstance()->getWatchedItemStore();
		$store->addWatchBatchForUser( $user, [
			new TitleValue( 0, 'ApiQueryWatchlistRawIntegrationTestPage1' ),
			new TitleValue( 1, 'ApiQueryWatchlistRawIntegrationTestPage1' ),
			new TitleValue( 0, 'ApiQueryWatchlistRawIntegrationTestPageB' ),
			new TitleValue( 1, 'ApiQueryWatchlistRawIntegrationTestPageB' ),
			new TitleValue( 0, 'ApiQueryWatchlistRawIntegrationTestPageA' ),
			new TitleValue( 1, 'ApiQueryWatchlistRawIntegrationTestPageA' ),
			new TitleValue( 0, 'ApiQueryWatchlistRawIntegrationTestPage2' ),
		] );
		$otherUser = ( new TestUser( 'ApiQueryWatchlistRawIntegrationTestUser2' ) )->getUser();
		$store->updateNotificationTimestamp(
			$otherUser,
			new TitleValue( 0, 'ApiQueryWatchlistRawIntegrationTestPage2' ),
			'20151212010101'
		);
	}

	private function doListWatchlistRawRequest( array $params = [] ) {
		return $this->doApiRequest( array_merge(
			[ 'action' => 'query', 'list' => 'watchlistraw' ],
			$params
		) );
	}

	private function doGeneratorWatchlistRawRequest( array $params = [] ) {
		return $this->doApiRequest( array_merge(
			[ 'action' => 'query', 'generator' => 'watchlistraw' ],
			$params
		) );
	}

	private function getPagesFromApiResponse( array $response ) {
		return $response[0]['watchlistraw'];
	}

	public function testListWatchlistRaw_returnsWatchedItems() {
		$result = $this->doListWatchlistRawRequest();

		$this->assertArrayHasKey( 'watchlistraw', $result[0] );

		$pages = $this->getPagesFromApiResponse( $result );
		$this->assertNotEmpty( $pages );

		$resultHasExpectedPage = false;
		foreach ( $pages as $page ) {
			if ( $page['ns'] === 0 && $page['title'] === 'ApiQueryWatchlistRawIntegrationTestPage1' ) {
				$resultHasExpectedPage = true;
				break;
			}
		}
		$this->assertTrue( $resultHasExpectedPage );
	}

	public function testPropChanged_addsNotificationTimestamp() {
		$result = $this->doListWatchlistRawRequest( [ 'wrprop' => 'changed' ] );

		$pages = $this->getPagesFromApiResponse( $result );
		$this->assertNotEmpty( $pages );

		$resultHasChangedField = false;
		foreach ( $pages as $page ) {
			if ( array_key_exists( 'changed', $page ) ) {
				$resultHasChangedField = true;
				break;
			}
		}
		$this->assertTrue( $resultHasChangedField );
	}

	public function testNamespaceParam() {
		$result = $this->doListWatchlistRawRequest( [ 'wrnamespace' => '0' ] );

		$pages = $this->getPagesFromApiResponse( $result );
		$this->assertNotEmpty( $pages );

		$resultHasOnlySubjectPages = true;
		foreach ( $pages as $page ) {
			if ( $page['ns'] !== 0 ) {
				$resultHasOnlySubjectPages = false;
				break;
			}
		}
		$this->assertTrue( $resultHasOnlySubjectPages );
	}

	public function testShowChanged() {
		$result = $this->doListWatchlistRawRequest( [ 'wrprop' => 'changed', 'wrshow' => 'changed', ] );

		$pages = $this->getPagesFromApiResponse( $result );
		$this->assertNotEmpty( $pages );

		$resultHasItemWithoutChangedField = false;
		foreach ( $pages as $page ) {
			if ( !array_key_exists( 'changed', $page ) ) {
				$resultHasItemWithoutChangedField = true;
				break;
			}
		}
		$this->assertFalse( $resultHasItemWithoutChangedField );
	}

	public function testShowNotChanged() {
		$result = $this->doListWatchlistRawRequest( [
			'wrprop' => 'changed',
			'wrshow' => '!changed',
		] );

		$pages = $this->getPagesFromApiResponse( $result );
		$this->assertNotEmpty( $pages );

		$resultHasItemWithChangedField = false;
		foreach ( $pages as $page ) {
			if ( array_key_exists( 'changed', $page ) ) {
				$resultHasItemWithChangedField = true;
				break;
			}
		}
		$this->assertFalse( $resultHasItemWithChangedField );
	}

	public function testLimit() {
		$resultWithoutLimit = $this->doListWatchlistRawRequest();
		$resultWithLimit = $this->doListWatchlistRawRequest( [ 'wrlimit' => 5 ] );

		$pagesWithoutLimit = $this->getPagesFromApiResponse( $resultWithoutLimit );
		$pagesWithLimit = $this->getPagesFromApiResponse( $resultWithLimit );

		$this->assertGreaterThan( 5, count( $pagesWithoutLimit ) );
		$this->assertArrayNotHasKey( 'continue', $resultWithoutLimit[0] );

		$this->assertCount( 5, $pagesWithLimit );
		$this->assertArrayHasKey( 'continue', $resultWithLimit[0] );
		$this->assertArrayHasKey( 'wrcontinue', $resultWithLimit[0]['continue'] );
	}

	public function testDirAscending() {
		$result = $this->doListWatchlistRawRequest( [ 'wrdir' => 'ascending' ] );

		$pages = $this->getPagesFromApiResponse( $result );
		$this->assertNotEmpty( $pages );

		$resultsNotInAscOrder = false;
		$previous = array_shift( $pages );
		foreach ( $pages as $page ) {
			if ( $previous['ns'] > $page['ns'] || (
					$previous['ns'] === $page['ns'] && $previous['title'] > $page['title']
				) ) {
				$resultsNotInAscOrder = true;
				break;
			}
		}
		$this->assertFalse( $resultsNotInAscOrder );
	}

	public function testDirDescending() {
		$result = $this->doListWatchlistRawRequest( [ 'wrdir' => 'descending' ] );

		$pages = $this->getPagesFromApiResponse( $result );
		$this->assertNotEmpty( $pages );

		$resultsNotInDescOrder = false;
		$previous = array_shift( $pages );
		foreach ( $pages as $page ) {
			if ( $previous['ns'] < $page['ns'] || (
					$previous['ns'] === $page['ns'] && $previous['title'] < $page['title']
				) ) {
				$resultsNotInDescOrder = true;
				break;
			}
		}
		$this->assertFalse( $resultsNotInDescOrder );
	}

	public function testAscendingIsDefaultOrder() {
		$resultNoDir = $this->doListWatchlistRawRequest();
		$resultAscDir = $this->doListWatchlistRawRequest( [ 'wrdir' => 'ascending' ] );

		$pagesNoDir = $this->getPagesFromApiResponse( $resultNoDir );
		$pagesAscDir = $this->getPagesFromApiResponse( $resultAscDir );

		$this->assertEquals( $pagesAscDir, $pagesNoDir );
	}

	public function testFromTitle() {
		$result = $this->doListWatchlistRawRequest( [
			'wrfromtitle' => 'ApiQueryWatchlistRawIntegrationTestPageA',
		] );

		$pages = $this->getPagesFromApiResponse( $result );
		$this->assertNotEmpty( $pages );

		$this->assertEquals( 'ApiQueryWatchlistRawIntegrationTestPageA', $pages[0]['title'] );
		$this->assertEquals( 0, $pages[0]['ns'] );

		$resultContainsNotExpectedPage = false;
		foreach ( $pages as $page ) {
			if ( $page['title'] === 'ApiQueryWatchlistRawIntegrationTestPage1' ) {
				$resultContainsNotExpectedPage = true;
				break;
			}
		}
		$this->assertFalse( $resultContainsNotExpectedPage );
	}

	public function testToTitle() {
		$result = $this->doListWatchlistRawRequest( [
			'wrtotitle' => 'ApiQueryWatchlistRawIntegrationTestPageA',
		] );

		$pages = $this->getPagesFromApiResponse( $result );
		$this->assertNotEmpty( $pages );

		$lastIndex = count( $pages ) - 1;
		$this->assertEquals( 'ApiQueryWatchlistRawIntegrationTestPageA', $pages[$lastIndex]['title'] );
		$this->assertEquals( 0, $pages[$lastIndex]['ns'] );

		$resultContainsNotExpectedPage = false;
		foreach ( $pages as $page ) {
			if ( $page['title'] === 'ApiQueryWatchlistRawIntegrationTestPageB' ) {
				$resultContainsNotExpectedPage = true;
				break;
			}
		}
		$this->assertFalse( $resultContainsNotExpectedPage );
	}

	public function testContinue() {
		$result = $this->doListWatchlistRawRequest( [
			'wrcontinue' => '0|ApiQueryWatchlistRawIntegrationTestPageA',
		] );

		$pages = $this->getPagesFromApiResponse( $result );
		$this->assertNotEmpty( $pages );

		$this->assertEquals( 'ApiQueryWatchlistRawIntegrationTestPageA', $pages[0]['title'] );
		$this->assertEquals( 0, $pages[0]['ns'] );

		$resultContainsNotExpectedPage = false;
		foreach ( $pages as $page ) {
			if ( $page['title'] === 'ApiQueryWatchlistRawIntegrationTestPage1' ) {
				$resultContainsNotExpectedPage = true;
				break;
			}
		}
		$this->assertFalse( $resultContainsNotExpectedPage );
	}

	public function fromTitleToTitleContinueComboProvider() {
		return [
			[
				[
					'wrfromtitle' => 'ApiQueryWatchlistRawIntegrationTestPage1',
					'wrtotitle' => 'ApiQueryWatchlistRawIntegrationTestPageA',
				],
				[
					[ 'ns' => 0, 'title' => 'ApiQueryWatchlistRawIntegrationTestPage1' ],
					[ 'ns' => 0, 'title' => 'ApiQueryWatchlistRawIntegrationTestPage2' ],
					[ 'ns' => 0, 'title' => 'ApiQueryWatchlistRawIntegrationTestPageA' ],
				],
				[
					[ 'ns' => 0, 'title' => 'ApiQueryWatchlistRawIntegrationTestPageB' ],
					[ 'ns' => 1, 'title' => 'ApiQueryWatchlistRawIntegrationTestPage1' ],
				]
			],
			[
				[
					'wrfromtitle' => 'ApiQueryWatchlistRawIntegrationTestPage1',
					'wrcontinue' => '0|ApiQueryWatchlistRawIntegrationTestPageA',
				],
				[
					[ 'ns' => 0, 'title' => 'ApiQueryWatchlistRawIntegrationTestPageA' ],
				],
				[
					[ 'ns' => 0, 'title' => 'ApiQueryWatchlistRawIntegrationTestPage1' ],
					[ 'ns' => 0, 'title' => 'ApiQueryWatchlistRawIntegrationTestPage2' ],
				]
			],
			[
				[
					'wrtotitle' => 'ApiQueryWatchlistRawIntegrationTestPageA',
					'wrcontinue' => '0|ApiQueryWatchlistRawIntegrationTestPage2',
				],
				[
					[ 'ns' => 0, 'title' => 'ApiQueryWatchlistRawIntegrationTestPage2' ],
					[ 'ns' => 0, 'title' => 'ApiQueryWatchlistRawIntegrationTestPageA' ],
				],
				[
					[ 'ns' => 0, 'title' => 'ApiQueryWatchlistRawIntegrationTestPage1' ],
					[ 'ns' => 0, 'title' => 'ApiQueryWatchlistRawIntegrationTestPageB' ],
					[ 'ns' => 1, 'title' => 'ApiQueryWatchlistRawIntegrationTestPage1' ],
				]
			],
			[
				[
					'wrfromtitle' => 'ApiQueryWatchlistRawIntegrationTestPage1',
					'wrtotitle' => 'ApiQueryWatchlistRawIntegrationTestPageA',
					'wrcontinue' => '0|ApiQueryWatchlistRawIntegrationTestPageA',
				],
				[
					[ 'ns' => 0, 'title' => 'ApiQueryWatchlistRawIntegrationTestPageA' ],
				],
				[
					[ 'ns' => 0, 'title' => 'ApiQueryWatchlistRawIntegrationTestPage1' ],
					[ 'ns' => 0, 'title' => 'ApiQueryWatchlistRawIntegrationTestPage2' ],
					[ 'ns' => 0, 'title' => 'ApiQueryWatchlistRawIntegrationTestPageB' ],
					[ 'ns' => 1, 'title' => 'ApiQueryWatchlistRawIntegrationTestPage1' ],
				]
			],
		];
	}

	/**
	 * @dataProvider fromTitleToTitleContinueComboProvider
	 */
	public function testFromTitleToTitleContinueCombo(
		array $params,
		array $expectedPages,
		array $notExpectedPages
	) {
		$result = $this->doListWatchlistRawRequest( $params );

		$pages = $this->getPagesFromApiResponse( $result );
		$this->assertNotEmpty( $pages );

		$expectedPagesFoundCount = 0;
		$resultContainsNotExpectedPage = false;
		foreach ( $pages as $page ) {
			if ( in_array( $page, $expectedPages ) ) {
				$expectedPagesFoundCount ++;
			}
			if ( in_array( $page, $notExpectedPages ) ) {
				$resultContainsNotExpectedPage = true;
				break;
			}
		}
		$this->assertFalse( $resultContainsNotExpectedPage );
		$this->assertEquals( count( $expectedPages ), $expectedPagesFoundCount );
	}

	public function fromTitleToTitleContinueSelfContradictoryComboProvider() {
		return [
			[
				[
					'wrfromtitle' => 'ApiQueryWatchlistRawIntegrationTestPageA',
					'wrtotitle' => 'ApiQueryWatchlistRawIntegrationTestPage1',
				]
			],
			[
				[
					'wrfromtitle' => 'ApiQueryWatchlistRawIntegrationTestPage1',
					'wrtotitle' => 'ApiQueryWatchlistRawIntegrationTestPageA',
					'wrdir' => 'descending',
				]
			],
			[
				[
					'wrtotitle' => 'ApiQueryWatchlistRawIntegrationTestPage1',
					'wrcontinue' => '0|ApiQueryWatchlistRawIntegrationTestPageA',
				]
			],
		];
	}

	/**
	 * @dataProvider fromTitleToTitleContinueSelfContradictoryComboProvider
	 */
	public function testFromTitleToTitleContinueSelfContradictoryCombo( array $params ) {
		$result = $this->doListWatchlistRawRequest( $params );
		$pages = $this->getPagesFromApiResponse( $result );
		$this->assertEmpty( $pages );
		$this->assertArrayNotHasKey( 'continue', $result[0] );
	}

	public function testOwnerAndTokenParams() {
		$otherUser = ( new TestUser( 'ApiQueryWatchlistRawIntegrationTestUser2' ) )->getUser();
		$otherUser->setOption( 'watchlisttoken', '1234567890' );
		$otherUser->saveSettings();

		$store = MediaWikiServices::getInstance()->getWatchedItemStore();
		$store->addWatchBatchForUser( $otherUser, [
			new TitleValue( 0, 'ApiQueryWatchlistRawIntegrationTestPage1' ),
			new TitleValue( 1, 'ApiQueryWatchlistRawIntegrationTestPage1' ),
		] );

		$result = $this->doListWatchlistRawRequest( [
			'wrowner' => 'ApiQueryWatchlistRawIntegrationTestUser2',
			'wrtoken' => '1234567890',
		] );

		$pages = $this->getPagesFromApiResponse( $result );

		$this->assertCount( 2, $pages );
		$this->assertEquals(
			[
				'ns' => 0,
				'title' => 'ApiQueryWatchlistRawIntegrationTestPage1',
			], $pages[0]
		);
		$this->assertEquals(
			[
				'ns' => 1,
				'title' => 'Talk:ApiQueryWatchlistRawIntegrationTestPage1',
			], $pages[1]
		);
	}

	public function testOwnerAndTokenParams_wrongToken() {
		$otherUser = ( new TestUser( 'ApiQueryWatchlistRawIntegrationTestUser2' ) )->getUser();
		$otherUser->setOption( 'watchlisttoken', '1234567890' );
		$otherUser->saveSettings();

		$this->setExpectedException( UsageException::class, 'Incorrect watchlist token provided' );

		$this->doListWatchlistRawRequest( [
			'wrowner' => 'ApiQueryWatchlistRawIntegrationTestUser2',
			'wrtoken' => 'wrong-token',
		] );
	}

	public function testOwnerAndTokenParams_userHasNoWatchlistToken() {
		$this->setExpectedException( UsageException::class, 'Incorrect watchlist token provided' );

		$this->doListWatchlistRawRequest( [
			'wrowner' => 'ApiQueryWatchlistRawIntegrationTestUser2',
			'wrtoken' => 'some-watchlist-token',
		] );
	}

	public function testGeneratorWatchlistRawPropInfo_returnsWatchedItems() {
		$result = $this->doGeneratorWatchlistRawRequest( [ 'prop' => 'info' ] );

		$this->assertArrayHasKey( 'query', $result[0] );
		$this->assertArrayHasKey( 'pages', $result[0]['query'] );

		$pages = $result[0]['query']['pages'];
		$this->assertNotEmpty( $pages );

		$resultHasExpectedPage = false;
		foreach ( $pages as $page ) {
			if ( $page['ns'] === 0 && $page['title'] === 'ApiQueryWatchlistRawIntegrationTestPage1' ) {
				$resultHasExpectedPage = true;
				break;
			}
		}
		$this->assertTrue( $resultHasExpectedPage );
	}

}
