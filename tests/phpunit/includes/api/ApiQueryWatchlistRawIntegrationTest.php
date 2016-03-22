<?php

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

	public function addDBDataOnce() {
		// TODO: is this needed at all? wl can include things that don't exist
		$this->insertPage( 'ApiQueryWatchlistRawIntegrationTestPage1' );
		$this->insertPage( 'ApiQueryWatchlistRawIntegrationTestPage2' );
		$this->insertPage( 'ApiQueryWatchlistRawIntegrationTestPageA' );
		$this->insertPage( 'ApiQueryWatchlistRawIntegrationTestPageB' );

		$testUser = new TestUser( 'ApiQueryWatchlistRawIntegrationTestUser' );
		$user = $testUser->getUser();
		$store = WatchedItemStore::getDefaultInstance();
		$store->addWatchBatch( [
			[ $user, new TitleValue( 0, 'ApiQueryWatchlistRawIntegrationTestPage1' ) ],
			[ $user, new TitleValue( 1, 'ApiQueryWatchlistRawIntegrationTestPage1' ) ],
			[ $user, new TitleValue( 0, 'ApiQueryWatchlistRawIntegrationTestPageB' ) ],
			[ $user, new TitleValue( 1, 'ApiQueryWatchlistRawIntegrationTestPageB' ) ],
			[ $user, new TitleValue( 0, 'ApiQueryWatchlistRawIntegrationTestPageA' ) ],
			[ $user, new TitleValue( 1, 'ApiQueryWatchlistRawIntegrationTestPageA' ) ],
			[ $user, new TitleValue( 0, 'ApiQueryWatchlistRawIntegrationTestPage2' ) ],
		] );
		$otherUser = ( new TestUser( 'ApiQueryWatchlistRawIntegrationTestUser_otherUser' ) )->getUser();
		$store->updateNotificationTimestamp(
			$otherUser,
			new TitleValue( 0, 'ApiQueryWatchlistRawIntegrationTestPage2' ),
			'20151212010101'
		);
	}

	public function testListWatchlistRaw_returnsWatchedItems() {
		$result = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'watchlistraw',
		] );
		$resultHasExpectedPage = false;
		foreach ( $result[0]['watchlistraw'] as $page ) {
			if ( $page['ns'] === 0 && $page['title'] === 'ApiQueryWatchlistRawIntegrationTestPage1' ) {
				$resultHasExpectedPage = true;
			}
		}
		$this->assertTrue( $resultHasExpectedPage );
	}

	public function testPropChanged_addsNotificationTimestamp() {
		$result = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'watchlistraw',
			'wrprop' => 'changed',
		] );
		$resultHasChangedField = false;
		foreach ( $result[0]['watchlistraw'] as $page ) {
			if ( array_key_exists( 'changed', $page ) ) {
				$resultHasChangedField = true;
				break;
			}
		}
		$this->assertTrue( $resultHasChangedField );
	}

	public function testNamespaceParam() {
		$result = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'watchlistraw',
			'wrnamespace' => '0',
		] );
		$resultHasTalkPage = false;
		foreach ( $result[0]['watchlistraw'] as $page ) {
			if ( $page['ns'] === 1 ) {
				$resultHasTalkPage = true;
				break;
			}
		}
		$this->assertFalse( $resultHasTalkPage );
	}

	public function testShowChanged() {
		$result = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'watchlistraw',
			'wrprop' => 'changed',
			'wrshow' => 'changed',
		] );
		$resultHasItemWithoutChangedField = false;
		foreach ( $result[0]['watchlistraw'] as $page ) {
			if ( !array_key_exists( 'changed', $page ) ) {
				$resultHasItemWithoutChangedField = true;
				break;
			}
		}
		$this->assertFalse( $resultHasItemWithoutChangedField );
	}

	public function testShowNotChanged() {
		$result = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'watchlistraw',
			'wrprop' => 'changed',
			'wrshow' => '!changed',
		] );
		$resultHasItemWithChangedField = false;
		foreach ( $result[0]['watchlistraw'] as $page ) {
			if ( array_key_exists( 'changed', $page ) ) {
				$resultHasItemWithChangedField = true;
				break;
			}
		}
		$this->assertFalse( $resultHasItemWithChangedField );
	}

	public function testLimit() {
		$resultWithoutLimit = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'watchlistraw',
		] );
		$this->assertGreaterThan( 5, count( $resultWithoutLimit[0]['watchlistraw'] ) );
		$this->assertCount(
			5,
			$this->doApiRequest( [
				'action' => 'query',
				'list' => 'watchlistraw',
				'wrlimit' => 5,
			] )[0]['watchlistraw']
		);
	}

	public function testDirAscending() {
		$result = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'watchlistraw',
			'wrdir' => 'ascending',
		] );
		$resultsNotInAscOrder = false;
		$pages = $result[0]['watchlistraw'];
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
		$result = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'watchlistraw',
			'wrdir' => 'descending',
		] );
		$resultsNotInDescOrder = false;
		$pages = $result[0]['watchlistraw'];
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
		$resultNoDir = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'watchlistraw',
		] );
		$resultAscDir = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'watchlistraw',
			'wrdir' => 'ascending',
		] );
		$this->assertEquals( $resultAscDir[0]['watchlistraw'], $resultNoDir[0]['watchlistraw'] );
	}

	public function testFromTitle() {
		$result = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'watchlistraw',
			'wrfromtitle' => 'ApiQueryWatchlistRawIntegrationTestPageA',
		] );
		$pages = $result[0]['watchlistraw'];
		$this->assertEquals( 'ApiQueryWatchlistRawIntegrationTestPageA', $pages[0]['title'] );
		$this->assertEquals( 0, $pages[0]['ns'] );
		$resultContainsNotExpectedPage = false;
		foreach ( $pages as $page ) {
			if ( $page['title'] === 'ApiQueryWatchlistRawIntegrationTestPage1' ) {
				$resultContainsNotExpectedPage = true;
			}
		}
		$this->assertFalse( $resultContainsNotExpectedPage );
	}

	public function testToTitle() {
		$result = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'watchlistraw',
			'wrtotitle' => 'ApiQueryWatchlistRawIntegrationTestPageA',
		] );
		$pages = $result[0]['watchlistraw'];
		$lastIndex = count( $pages ) - 1;
		$this->assertEquals( 'ApiQueryWatchlistRawIntegrationTestPageA', $pages[$lastIndex]['title'] );
		$this->assertEquals( 0, $pages[$lastIndex]['ns'] );
		$resultContainsNotExpectedPage = false;
		foreach ( $pages as $page ) {
			if ( $page['title'] === 'ApiQueryWatchlistRawIntegrationTestPageB' ) {
				$resultContainsNotExpectedPage = true;
			}
		}
		$this->assertFalse( $resultContainsNotExpectedPage );
	}

	public function testContinue() {
		$result = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'watchlistraw',
			'wrcontinue' => '0|ApiQueryWatchlistRawIntegrationTestPageA',
		] );
		$pages = $result[0]['watchlistraw'];
		$this->assertEquals( 'ApiQueryWatchlistRawIntegrationTestPageA', $pages[0]['title'] );
		$this->assertEquals( 0, $pages[0]['ns'] );
		$resultContainsNotExpectedPage = false;
		foreach ( $pages as $page ) {
			if ( $page['title'] === 'ApiQueryWatchlistRawIntegrationTestPage1' ) {
				$resultContainsNotExpectedPage = true;
			}
		}
		$this->assertFalse( $resultContainsNotExpectedPage );
	}

}
