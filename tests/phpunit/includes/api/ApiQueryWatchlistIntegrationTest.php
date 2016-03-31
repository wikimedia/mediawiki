<?php

use MediaWiki\MediaWikiServices;

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers ApiQueryWatchlist
 */
class ApiQueryWatchlistIntegrationTest extends ApiTestCase {

	public function __construct( $name = null, array $data = [], $dataName = '' ) {
		parent::__construct( $name, $data, $dataName );
		$this->tablesUsed = array_unique(
			array_merge( $this->tablesUsed, [ 'watchlist', 'recentchanges', 'page' ] )
		);
	}

	protected function setUp() {
		parent::setUp();
		self::$users['ApiQueryWatchlistIntegrationTestUser']
			= new TestUser( 'ApiQueryWatchlistIntegrationTestUser' );
		$this->doLogin( 'ApiQueryWatchlistIntegrationTestUser' );
	}

	private function doPageEdit( User $user, $dbKey, $content, $summary ) {
		$title = Title::newFromDBkey( $dbKey );
		$page = WikiPage::factory( $title );
		$page->doEditContent(
			ContentHandler::makeContent( $content, $title ),
			$summary,
			0,
			false,
			$user
		);
	}

	private function doMinorPageEdit( User $user, $dbKey, $content, $summary ) {
		$title = Title::newFromDBkey( $dbKey );
		$page = WikiPage::factory( $title );
		$page->doEditContent(
			ContentHandler::makeContent( $content, $title ),
			$summary,
			EDIT_MINOR,
			false,
			$user
		);
	}

	private function doBotPageEdit( User $user, $dbKey, $content, $summary ) {
		$title = Title::newFromDBkey( $dbKey );
		$page = WikiPage::factory( $title );
		$page->doEditContent(
			ContentHandler::makeContent( $content, $title ),
			$summary,
			EDIT_FORCE_BOT,
			false,
			$user
		);
	}

	private function doAnonPageEdit( $dbKey, $content, $summary ) {
		$title = Title::newFromDBkey( $dbKey );
		$page = WikiPage::factory( $title );
		$page->doEditContent(
			ContentHandler::makeContent( $content, $title ),
			$summary
		);
	}

	public function addDBData() {
		$user = ( new TestUser( 'ApiQueryWatchlistIntegrationTestUser' ) )->getUser();
		$otherUser = ( new TestUser( 'ApiQueryWatchlistIntegrationTestUser2' ) )->getUser();

		$this->doPageEdit(
			$user,
			'ApiQueryWatchlistIntegrationTestPage1',
			'Some Content',
			'Create the page'
		);
		$this->doPageEdit(
			$user,
			'ApiQueryWatchlistIntegrationTestPage1',
			'Some Other Content',
			'Change content'
		);
		$this->doPageEdit(
			$user,
			'Talk:ApiQueryWatchlistIntegrationTestPage1',
			'Some Talk Page Cotnent',
			'Create Talk page'
		);
		$this->doPageEdit(
			$otherUser,
			'ApiQueryWatchlistIntegrationTestPage2',
			'Some text',
			'Create the page'
		);
		$this->doPageEdit(
			$user,
			'ApiQueryWatchlistIntegrationTestPage3',
			'Some Content',
			'Create the page'
		);
		$this->doMinorPageEdit(
			$user,
			'ApiQueryWatchlistIntegrationTestPage3',
			'Some Other Content',
			'Change content'
		);
		$this->doBotPageEdit(
			$user,
			'ApiQueryWatchlistIntegrationTestPage4',
			'Some Other Content',
			'Create the page with a bot'
		);
		$this->doAnonPageEdit(
			'ApiQueryWatchlistIntegrationTestPageA',
			'Some Other Content',
			'Create the page anonymously'
		);

		$store = MediaWikiServices::getInstance()->getWatchedItemStore();
		$store->addWatchBatchForUser( $user, [
			new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPage1' ),
			new TitleValue( 1, 'ApiQueryWatchlistIntegrationTestPage1' ),
			new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPage2' ),
			new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPage3' ),
			new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPage4' ),
			new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPageA' ),
		] );
		$store->addWatchBatchForUser( $otherUser, [
			new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPage1' ),
			new TitleValue( 1, 'ApiQueryWatchlistIntegrationTestPage1' ),
		] );
		$store->updateNotificationTimestamp(
			$otherUser,
			new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPage2' ),
			'20151212010101'
		);
	}

	private function doListWatchlistRequest( array $params = [] ) {
		return $this->doApiRequest(
			array_merge(
				[ 'action' => 'query', 'list' => 'watchlist' ],
				$params
			)
		);
	}

	private function doGeneratorWatchlistRequest( array $params = [] ) {
		return $this->doApiRequest(
			array_merge(
				[ 'action' => 'query', 'generator' => 'watchlist' ],
				$params
			)
		);
	}

	public function testListWatchlist_returnsWatchedItemsWithRCInfo() {
		$result = $this->doListWatchlistRequest();

		$this->assertArrayHasKey( 'query', $result[0] );
		$this->assertArrayHasKey( 'watchlist', $result[0]['query'] );

		$resultHasExpectedPage = false;
		foreach ( $result[0]['query']['watchlist'] as $item ) {
			if ( $item['ns'] === 0 && $item['title'] === 'ApiQueryWatchlistIntegrationTestPage1' ) {
				$resultHasExpectedPage = true;
				$this->assertArrayHasKey( 'type', $item );
				break;
			}
		}
		$this->assertTrue( $resultHasExpectedPage );
	}

	public function propParamProvider() {
		return [
			[ 'ids', [ 'pageid', 'revid', 'old_revid' ] ],
			[
				'title',
				[ 'ns', 'title' ],
				[
					[ 'ns' => 0, 'title' => 'ApiQueryWatchlistIntegrationTestPageA' ]
				]
			],
			[
				'flags',
				[ 'bot','new', 'minor' ],
				[
					[ 'new' => true, 'bot' => false, 'minor' => false ],
					[ 'bot' => true, ],
					[ 'new' => false, 'minor' => true, ],
				]
			],
			[
				'user',
				[ 'user' ],
				[ 'anon' => true ],
			],
			[
				'userid',
				[ 'userid', 'user' ],
				[
					[ 'anon' => true, 'userid' => 0, 'user' => 0, ],
				]
			],
			[
				'comment',
				[ 'comment' ],
				[
					[ 'comment' => 'Create the page anonymously' ],
					[ 'comment' => 'Create the page with a bot' ],
				]
			],
			[ 'parsedcomment', [ 'parsedcomment' ] ],
			[ 'timestamp', [ 'timestamp' ] ],
			[
				'sizes',
				[ 'oldlen', 'newlen' ],
				[
					0 => [ 'oldlen' => 0, 'newlen' => 18 ],
					2 => [ 'oldlen' => 12, 'newlen' => 18 ],
				],
			],
			[
				'notificationtimestamp',
				[ 'notificationtimestamp' ],
				[ 3 => [ 'notificationtimestamp' => '2015-12-12T01:01:01Z' ] ],
			],
			// TODO: patrol prop
			// TODO: loginfo prop
		];
	}

	/**
	 * @dataProvider propParamProvider
	 */
	public function testPropParameter_addsProperties(
		$prop,
		array $expectedFields,
		array $expectedValues = []
	) {
		$result = $this->doListWatchlistRequest( [ 'wlprop' => $prop, ] );

		$this->assertNotEmpty( $result[0]['query']['watchlist'] );

		$resultHasExpectedFields = true;
		$resultHasExpectedValues = true;
		foreach ( $result[0]['query']['watchlist'] as $index => $item ) {
			foreach ( $expectedFields as $field ) {
				if ( !array_key_exists( $field, $item ) ) {
					$resultHasExpectedFields = false;
					break;
				}
			}
			if ( isset( $expectedValues[$index] ) ) {
				foreach ( $expectedValues[$index] as $field => $value ) {
					if ( $item[$field] !== $value ) {
						$resultHasExpectedValues = false;
						break;
					}
				}
			}
		}
		$this->assertTrue( $resultHasExpectedFields );
		$this->assertTrue( $resultHasExpectedValues );
	}

	public function testEmptyPropParameter() {
		$result = $this->doListWatchlistRequest( [ 'wlprop' => '', ] );

		$this->assertNotEmpty( $result[0]['query']['watchlist'] );

		$resultHasUnexpectedFields = false;
		foreach ( $result[0]['query']['watchlist'] as $item ) {
			if ( array_keys( $item ) !==  [ 'type' ] ) {
				$resultHasUnexpectedFields = true;
			}
		}
		$this->assertFalse( $resultHasUnexpectedFields );
	}

	public function testNamespaceParam() {
		$result = $this->doListWatchlistRequest( [ 'wlnamespace' => '0', ] );

		$this->assertNotEmpty( $result[0]['query']['watchlist'] );

		$resultHasTalkPage = false;
		foreach ( $result[0]['query']['watchlist'] as $item ) {
			if ( $item['ns'] === 1 ) {
				$resultHasTalkPage = true;
				break;
			}
		}
		$this->assertFalse( $resultHasTalkPage );
	}

	public function testUserParam() {
		$result = $this->doListWatchlistRequest( [
			'wlprop' => 'user',
			'wluser' => 'ApiQueryWatchlistIntegrationTestUser2',
		] );

		$this->assertNotEmpty( $result[0]['query']['watchlist'] );

		$resultHasEditByOtherUsers = false;
		foreach ( $result[0]['query']['watchlist'] as $item ) {
			if ( $item['user'] !== 'ApiQueryWatchlistIntegrationTestUser2' ) {
				$resultHasEditByOtherUsers = true;
				break;
			}
		}
		$this->assertFalse( $resultHasEditByOtherUsers );
	}

	public function testExcludeUserParam() {
		$result = $this->doListWatchlistRequest( [
			'wlprop' => 'user',
			'wlexcludeuser' => 'ApiQueryWatchlistIntegrationTestUser2',
		] );

		$this->assertNotEmpty( $result[0]['query']['watchlist'] );

		$resultHasEditByExcludedUser = false;
		foreach ( $result[0]['query']['watchlist'] as $item ) {
			if ( $item['user'] === 'ApiQueryWatchlistIntegrationTestUser2' ) {
				$resultHasEditByExcludedUser = true;
				break;
			}
		}
		$this->assertFalse( $resultHasEditByExcludedUser );
	}

	public function testShowMinorParams() {
		$resultMinor = $this->doListWatchlistRequest( [ 'wlshow' => 'minor' ] );
		$resultNotMinor = $this->doListWatchlistRequest( [ 'wlshow' => '!minor' ] );

		$this->assertNotEmpty( $resultMinor[0]['query']['watchlist'] );

		$resultHasOnlyMinorItems = true;
		foreach ( $resultMinor[0]['query']['watchlist'] as $item ) {
			if ( !$item['minor'] ) {
				$resultHasOnlyMinorItems = false;
				break;
			}
		}
		$this->assertTrue( $resultHasOnlyMinorItems );

		$this->assertNotEmpty( $resultNotMinor[0]['query']['watchlist'] );

		$resultHasNoMinorItems = true;
		foreach ( $resultNotMinor[0]['query']['watchlist'] as $item ) {
			if ( $item['minor'] ) {
				$resultHasNoMinorItems = false;
				break;
			}
		}
		$this->assertTrue( $resultHasNoMinorItems );
	}

	public function testShowBotParams() {
		$resultBot = $this->doListWatchlistRequest( [ 'wlshow' => 'bot' ] );
		$resultNotBot = $this->doListWatchlistRequest( [ 'wlshow' => '!bot' ] );

		$this->assertNotEmpty( $resultBot[0]['query']['watchlist'] );

		$resultHasOnlyBotItems = true;
		foreach ( $resultBot[0]['query']['watchlist'] as $item ) {
			if ( !$item['bot'] ) {
				$resultHasOnlyBotItems = false;
				break;
			}
		}
		$this->assertTrue( $resultHasOnlyBotItems );

		$this->assertNotEmpty( $resultNotBot[0]['query']['watchlist'] );

		$resultHasNoBotItems = true;
		foreach ( $resultNotBot[0]['query']['watchlist'] as $item ) {
			if ( $item['bot'] ) {
				$resultHasNoBotItems = false;
				break;
			}
		}
		$this->assertTrue( $resultHasNoBotItems );
	}

	public function testShowAnonParams() {
		$resultAnon = $this->doListWatchlistRequest( [
			'wlprop' => 'user',
			'wlshow' => 'anon'
		] );
		$resultNotAnon = $this->doListWatchlistRequest( [
			'wlprop' => 'user',
			'wlshow' => '!anon'
		] );

		$this->assertNotEmpty( $resultAnon[0]['query']['watchlist'] );

		$resultHasOnlyAnonItems = true;
		foreach ( $resultAnon[0]['query']['watchlist'] as $item ) {
			if ( !isset( $item['anon'] ) ) {
				$resultHasOnlyAnonItems = false;
				break;
			}
		}
		$this->assertTrue( $resultHasOnlyAnonItems );

		$this->assertNotEmpty( $resultNotAnon[0]['query']['watchlist'] );

		$resultHasNoAnonItems = true;
		foreach ( $resultNotAnon[0]['query']['watchlist'] as $item ) {
			if ( isset( $item['anon'] ) && $item['anon'] ) {
				$resultHasNoAnonItems = false;
				break;
			}
		}
		$this->assertTrue( $resultHasNoAnonItems );
	}

	public function testShowUnreadParams() {
		$resultUnread = $this->doListWatchlistRequest( [
			'wlprop' => 'timestamp|notificationtimestamp',
			'wlshow' => 'unread'
		] );
		$resultNotUnread = $this->doListWatchlistRequest( [
			'wlprop' => 'timestamp|notificationtimestamp',
			'wlshow' => '!unread'
		] );

		$this->assertNotEmpty( $resultUnread[0]['query']['watchlist'] );

		$resultHasOnlyUnreadItems = true;
		foreach ( $resultUnread[0]['query']['watchlist'] as $item ) {
			if ( !$item['notificationtimestamp'] ) {
				$resultHasOnlyUnreadItems = false;
				break;
			}
		}
		$this->assertTrue( $resultHasOnlyUnreadItems );

		$this->assertNotEmpty( $resultNotUnread[0]['query']['watchlist'] );

		$resultHasNoUnreadItems = true;
		foreach ( $resultNotUnread[0]['query']['watchlist'] as $item ) {
			if ( $item['notificationtimestamp'] && $item['notificationtimestamp'] < $item['timestamp'] ) {
				$resultHasNoUnreadItems = false;
				break;
			}
		}
		$this->assertTrue( $resultHasNoUnreadItems );
	}

	// TODO: wlshow=patrolled/!patrolled

	public function typeParamValueProvider() {
		return [
			[ 'new' ],
			[ 'edit' ],
			// TODO: [ 'categorize' ],
		];
	}

	/**
	 * @dataProvider typeParamValueProvider
	 */
	public function testTypeParam( $type ) {
		$result = $this->doListWatchlistRequest( [ 'wltype' => $type, ] );

		$this->assertNotEmpty( $result[0]['query']['watchlist'] );

		$resultHasOnlyItemsOfGivenType = true;
		foreach ( $result[0]['query']['watchlist'] as $item ) {
			if ( $item['type'] !== $type ) {
				$resultHasOnlyItemsOfGivenType = false;
				break;
			}
		}
		$this->assertTrue( $resultHasOnlyItemsOfGivenType );
	}

	public function testLimitParam() {
		$resultWithoutLimit = $this->doListWatchlistRequest();
		$resultWithLimit = $this->doListWatchlistRequest( [ 'wllimit' => 5, ] );

		$this->assertGreaterThan( 5, count( $resultWithoutLimit[0]['query']['watchlist'] ) );
		$this->assertCount( 5, $resultWithLimit[0]['query']['watchlist'] );
		$this->assertArrayHasKey( 'continue', $resultWithLimit[0] );
		$this->assertArrayHasKey( 'wlcontinue', $resultWithLimit[0]['continue'] );
	}

	public function testAllRevParam() {
		$result = $this->doListWatchlistRequest( [ 'wlallrev' => '', ] );

		$this->assertCount( 8, $result[0]['query']['watchlist'] );

		$resultHasCreateRevisionOfThePage = false;
		$resultHasEditRevisionOfThePage = false;
		foreach ( $result[0]['query']['watchlist'] as $item ) {
			if ( $item['title'] === 'ApiQueryWatchlistIntegrationTestPage1' ) {
				if ( $item['type'] == 'new' ) {
					$resultHasCreateRevisionOfThePage = true;
				} elseif ( $item['type'] === 'edit' ) {
					$resultHasEditRevisionOfThePage = true;
				}
			}
		}
		$this->assertTrue( $resultHasCreateRevisionOfThePage );
		$this->assertTrue( $resultHasEditRevisionOfThePage );
	}

	public function testEnumerateToOlder() {
		$result = $this->doListWatchlistRequest( [ 'wldir' => 'older', ] );

		$this->assertCount( 6, $result[0]['query']['watchlist'] );
		$this->assertEquals(
			'ApiQueryWatchlistIntegrationTestPageA',
			$result[0]['query']['watchlist'][0]['title']
		);
		$this->assertEquals(
			'ApiQueryWatchlistIntegrationTestPage4',
			$result[0]['query']['watchlist'][1]['title']
		);
		$this->assertEquals(
			'ApiQueryWatchlistIntegrationTestPage3',
			$result[0]['query']['watchlist'][2]['title']
		);
		$this->assertEquals(
			'ApiQueryWatchlistIntegrationTestPage2',
			$result[0]['query']['watchlist'][3]['title']
		);
		$this->assertEquals(
			'Talk:ApiQueryWatchlistIntegrationTestPage1',
			$result[0]['query']['watchlist'][4]['title']
		);
		$this->assertEquals(
			'ApiQueryWatchlistIntegrationTestPage1',
			$result[0]['query']['watchlist'][5]['title']
		);
	}

	public function testEnumerateToNewer() {
		$result = $this->doListWatchlistRequest( [ 'wldir' => 'newer', ] );

		$this->assertCount( 6, $result[0]['query']['watchlist'] );
		$this->assertEquals(
			'ApiQueryWatchlistIntegrationTestPage1',
			$result[0]['query']['watchlist'][0]['title']
		);
		$this->assertEquals(
			'Talk:ApiQueryWatchlistIntegrationTestPage1',
			$result[0]['query']['watchlist'][1]['title']
		);
		$this->assertEquals(
			'ApiQueryWatchlistIntegrationTestPage2',
			$result[0]['query']['watchlist'][2]['title']
		);
		$this->assertEquals(
			'ApiQueryWatchlistIntegrationTestPage3',
			$result[0]['query']['watchlist'][3]['title']
		);
		$this->assertEquals(
			'ApiQueryWatchlistIntegrationTestPage4',
			$result[0]['query']['watchlist'][4]['title']
		);
		$this->assertEquals(
			'ApiQueryWatchlistIntegrationTestPageA',
			$result[0]['query']['watchlist'][5]['title']
		);
	}

	public function testEnumerateFromTimestamp() {
		$result = $this->doListWatchlistRequest( [
			'wlstart' => '20010115000000',
			'wldir' => 'newer',
		] );

		$this->assertCount( 6, $result[0]['query']['watchlist'] );
	}

	public function testEnumerateUntilTimestamp() {
		$result = $this->doListWatchlistRequest( [
			'wlend' => '20010115000000',
			'wldir' => 'newer',
		] );

		$this->assertEmpty( $result[0]['query']['watchlist'] );
	}

	public function testContinueParam() {
		$firstResult = $this->doListWatchlistRequest( [ 'wllimit' => 5, ] );
		$continuationParam = $firstResult[0]['continue']['wlcontinue'];

		$continuedResult = $this->doListWatchlistRequest( [ 'wlcontinue' => $continuationParam, ] );

		$this->assertCount( 1, $continuedResult[0]['query']['watchlist'] );
		$this->assertEquals(
			'ApiQueryWatchlistIntegrationTestPage1',
			$continuedResult[0]['query']['watchlist'][0]['title']
		);
	}

	public function testOwnerAndTokenParams() {
		$otherUser = ( new TestUser( 'ApiQueryWatchlistIntegrationTestUser2' ) )->getUser();
		$otherUser->setOption( 'watchlisttoken', '1234567890' );
		$otherUser->saveSettings();

		$result = $this->doListWatchlistRequest( [
			'wlowner' => 'ApiQueryWatchlistIntegrationTestUser2',
			'wltoken' => '1234567890',
		] );

		$this->assertCount( 2, $result[0]['query']['watchlist'] );
		$this->assertEquals(
			'Talk:ApiQueryWatchlistIntegrationTestPage1',
			$result[0]['query']['watchlist'][0]['title']
		);
		$this->assertEquals(
			'ApiQueryWatchlistIntegrationTestPage1',
			$result[0]['query']['watchlist'][1]['title']
		);
	}

	public function testOwnerAndTokenParams_wrongToken() {
		$otherUser = ( new TestUser( 'ApiQueryWatchlistIntegrationTestUser2' ) )->getUser();
		$otherUser->setOption( 'watchlisttoken', '1234567890' );
		$otherUser->saveSettings();

		$this->setExpectedException( UsageException::class, 'Incorrect watchlist token provided' );

		$this->doListWatchlistRequest( [
			'wlowner' => 'ApiQueryWatchlistIntegrationTestUser2',
			'wltoken' => 'wrong-token',
		] );
	}

	public function testOwnerAndTokenParams_noWatchlistTokenSet() {
		$this->setExpectedException( UsageException::class, 'Incorrect watchlist token provided' );

		$this->doListWatchlistRequest( [
			'wlowner' => 'ApiQueryWatchlistIntegrationTestUser2',
			'wltoken' => 'some-token',
		] );
	}

	public function testGeneratorWatchlistPropInfo_returnsWatchedPages() {
		$result = $this->doGeneratorWatchlistRequest( [ 'prop' => 'info' ] );

		$this->assertArrayHasKey( 'query', $result[0] );
		$this->assertArrayHasKey( 'pages', $result[0]['query'] );
		$this->assertNotEmpty( $result[0]['query']['pages'] );

		$resultHasExpectedPage = false;
		foreach ( $result[0]['query']['pages'] as $page ) {
			if ( $page['ns'] === 0 && $page['title'] === 'ApiQueryWatchlistIntegrationTestPage1' ) {
				$resultHasExpectedPage = true;
				break;
			}
		}
		$this->assertTrue( $resultHasExpectedPage );
	}

	public function testGeneratorWatchlistPropRevisions_returnsWatchedItemsRevisions() {
		$result = $this->doGeneratorWatchlistRequest( [ 'prop' => 'revisions', 'gwlallrev' => '' ] );

		$this->assertArrayHasKey( 'query', $result[0] );
		$this->assertArrayHasKey( 'pages', $result[0]['query'] );
		$this->assertNotEmpty( $result[0]['query']['pages'] );

		$resultHasExpectedPage = false;
		$resultHasExpectedRevision = false;
		foreach ( $result[0]['query']['pages'] as $page ) {
			if ( $page['ns'] === 0 && $page['title'] === 'ApiQueryWatchlistIntegrationTestPage1' ) {
				$resultHasExpectedPage = true;
				$this->assertArrayHasKey( 'revisions', $page );
				foreach ( $page['revisions'] as $revision ) {
					if ( $revision['user'] === 'ApiQueryWatchlistIntegrationTestUser' &&
						$revision['comment'] === 'Create the page' ) {
						$resultHasExpectedRevision = true;
						break;
					}
				}
			}
		}
		$this->assertTrue( $resultHasExpectedPage );
		$this->assertTrue( $resultHasExpectedRevision );
	}

}
