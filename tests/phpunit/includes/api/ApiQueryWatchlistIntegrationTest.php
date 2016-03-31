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

	private function getTestUser() {
		return ( new TestUser( 'ApiQueryWatchlistIntegrationTestUser' ) )->getUser();
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

	private function doPatrolledPageEdit(
		User $user,
		$dbKey,
		$content,
		$summary,
		User $patrollingUser
	) {
		$title = Title::newFromDBkey( $dbKey );
		$page = WikiPage::factory( $title );
		$status = $page->doEditContent(
			ContentHandler::makeContent( $content, $title ),
			$summary,
			0,
			false,
			$user
		);
		$rc = $status->value['revision']->getRecentChange();
		$rc->doMarkPatrolled( $patrollingUser, false, [] );
	}

	private function deletePage( $dbKey, $reason ) {
		$title = Title::newFromDBkey( $dbKey );
		$page = WikiPage::factory( $title );
		$page->doDeleteArticleReal( $reason );
	}

	private function getWatchedItemStore() {
		return MediaWikiServices::getInstance()->getWatchedItemStore();
	}

	public function addDBData() {
		$user = $this->getTestUser();
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

		$store = $this->getWatchedItemStore();
		$store->addWatchBatchForUser( $user, [
			new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPage1' ),
			new TitleValue( 1, 'ApiQueryWatchlistIntegrationTestPage1' ),
			new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPage2' ),
			new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPage3' ),
			new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPage4' ),
			new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPageA' ),
		] );
		$store->updateNotificationTimestamp(
			$otherUser,
			new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPage2' ),
			'20151212010101'
		);
	}

	private function doListWatchlistRequest( array $params = [], $user = null ) {
		return $this->doApiRequest(
			array_merge(
				[ 'action' => 'query', 'list' => 'watchlist' ],
				$params
			), null, false, $user
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

	private function getItemsFromApiResponse( array $response ) {
		return $response[0]['query']['watchlist'];
	}

	public function testListWatchlist_returnsWatchedItemsWithRCInfo() {
		$result = $this->doListWatchlistRequest();

		$this->assertArrayHasKey( 'query', $result[0] );
		$this->assertArrayHasKey( 'watchlist', $result[0]['query'] );

		$items = $this->getItemsFromApiResponse( $result );
		$this->assertNotEmpty( $items );

		$resultHasExpectedPage = false;
		foreach ( $items as $item ) {
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

		$items = $this->getItemsFromApiResponse( $result );
		$this->assertNotEmpty( $items );

		$resultHasExpectedFields = true;
		$resultHasExpectedValues = true;
		foreach ( $items as $index => $item ) {
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

	private function setupPatrolledSpecificFixtures( User $user ) {
		$adminUser = ( new TestUser(
			'ApiQueryWatchlistIntegrationTestAdminUser',
			'ApiQueryWatchlist Test Admin',
			'testadmin@1337.org',
			[ 'sysop' ]
		) )->getUser();

		$this->doPatrolledPageEdit(
			$user,
			'ApiQueryWatchlistIntegrationTestPage1',
			'Some Even Other Content',
			'Update the page (this gets patrolled)',
			$adminUser
		);
	}

	private function addPatrolRights( User $user ) {
		if ( $user->mRights === null ) {
			$user->mRights = [ 'viewmywatchlist', 'patrol' ];
		} else {
			$user->mRights = array_merge( $user->mRights, [ 'viewmywatchlist', 'patrol' ] );
		}
	}

	public function testPatrolPropParameter_addsPatrolledProperties() {
		$user = $this->getTestUser();
		$this->setupPatrolledSpecificFixtures( $user );

		$previousRights = $user->mRights;
		$this->addPatrolRights( $user );

		$result = $this->doListWatchlistRequest( [ 'wlprop' => 'title|patrol', ], $user );

		$user->mRights = $previousRights;

		$items = $this->getItemsFromApiResponse( $result );
		$this->assertNotEmpty( $items );

		$resultHasExpectedFields = true;
		$expectedPageIsPatrolled = false;
		foreach ( $items as $index => $item ) {
			if ( !array_key_exists( 'patrolled', $item ) || !array_key_exists( 'unpatrolled', $item ) ) {
				$resultHasExpectedFields = false;
				break;
			}
			if ( $item['title'] === 'ApiQueryWatchlistIntegrationTestPage1' ) {
				if ( $item['patrolled'] && !$item['unpatrolled'] ) {
					$expectedPageIsPatrolled = true;
				}
			}
		}
		$this->assertTrue( $resultHasExpectedFields );
		$this->assertTrue( $expectedPageIsPatrolled );
	}

	private function setupLogSpecificFixtures() {
		$user = $this->getTestUser();

		$this->doPageEdit(
			$user,
			'ApiQueryWatchlistIntegrationTestPageD',
			'Some Content',
			'Create the page that will be deleted'
		);
		$this->deletePage( 'ApiQueryWatchlistIntegrationTestPageD', 'Important Reason' );

		$store = $this->getWatchedItemStore();
		$store->addWatch( $user, new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPageD' ) );
	}

	public function testLoginfoPropParameters_addsLogProperties() {
		$this->setupLogSpecificFixtures();

		$result = $this->doListWatchlistRequest( [ 'wlprop' => 'title|loginfo', ] );

		$items = $this->getItemsFromApiResponse( $result );
		$this->assertNotEmpty( $items );

		$expectedPageHasLogFields = false;
		$logFieldsHaveExpectedValues = false;
		foreach ( $items as $item ) {
			if ( $item['title'] === 'ApiQueryWatchlistIntegrationTestPageD' ) {
				if ( $this->pageHasLogInformationFields( $item ) ) {
					$expectedPageHasLogFields = true;
				}
				if ( $item['logtype'] === 'delete' && $item['logaction'] === 'delete' ) {
					$logFieldsHaveExpectedValues = true;
				}
				break;
			}
		}
		$this->assertTrue( $expectedPageHasLogFields );
		$this->assertTrue( $logFieldsHaveExpectedValues );
	}

	private function pageHasLogInformationFields( array $page ) {
		return array_key_exists( 'logid', $page ) && array_key_exists( 'logtype', $page )
			&& array_key_exists( 'logaction', $page ) && array_key_exists( 'logparams', $page );
	}

	public function testEmptyPropParameter() {
		$result = $this->doListWatchlistRequest( [ 'wlprop' => '', ] );

		$items = $this->getItemsFromApiResponse( $result );
		$this->assertNotEmpty( $items );

		$resultHasUnexpectedFields = false;
		foreach ( $items as $item ) {
			if ( array_keys( $item ) !==  [ 'type' ] ) {
				$resultHasUnexpectedFields = true;
				break;
			}
		}
		$this->assertFalse( $resultHasUnexpectedFields );
	}

	public function testNamespaceParam() {
		$result = $this->doListWatchlistRequest( [ 'wlnamespace' => '0', ] );

		$items = $this->getItemsFromApiResponse( $result );
		$this->assertNotEmpty( $items );

		$resultHasTalkPage = false;
		foreach ( $items as $item ) {
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

		$items = $this->getItemsFromApiResponse( $result );
		$this->assertNotEmpty( $items );

		$resultHasEditByOtherUsers = false;
		foreach ( $items as $item ) {
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

		$items = $this->getItemsFromApiResponse( $result );
		$this->assertNotEmpty( $items );

		$resultHasEditByExcludedUser = false;
		foreach ( $items as $item ) {
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

		$itemsMinor = $this->getItemsFromApiResponse( $resultMinor );
		$this->assertNotEmpty( $itemsMinor );

		$resultHasOnlyMinorItems = true;
		foreach ( $itemsMinor as $item ) {
			if ( !$item['minor'] ) {
				$resultHasOnlyMinorItems = false;
				break;
			}
		}
		$this->assertTrue( $resultHasOnlyMinorItems );

		$itemsNotMinor = $this->getItemsFromApiResponse( $resultNotMinor );
		$this->assertNotEmpty( $itemsNotMinor );

		$resultHasNoMinorItems = true;
		foreach ( $itemsNotMinor as $item ) {
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

		$itemsBot = $this->getItemsFromApiResponse( $resultBot );
		$this->assertNotEmpty( $itemsBot );

		$resultHasOnlyBotItems = true;
		foreach ( $itemsBot as $item ) {
			if ( !$item['bot'] ) {
				$resultHasOnlyBotItems = false;
				break;
			}
		}
		$this->assertTrue( $resultHasOnlyBotItems );

		$itemsNotBot = $this->getItemsFromApiResponse( $resultNotBot );
		$this->assertNotEmpty( $itemsNotBot );

		$resultHasNoBotItems = true;
		foreach ( $itemsNotBot as $item ) {
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

		$itemsAnon = $this->getItemsFromApiResponse( $resultAnon );
		$this->assertNotEmpty( $itemsAnon );

		$resultHasOnlyAnonItems = true;
		foreach ( $itemsAnon as $item ) {
			if ( !isset( $item['anon'] ) ) {
				$resultHasOnlyAnonItems = false;
				break;
			}
		}
		$this->assertTrue( $resultHasOnlyAnonItems );

		$itemsNotAnon = $this->getItemsFromApiResponse( $resultNotAnon );
		$this->assertNotEmpty( $itemsNotAnon );

		$resultHasNoAnonItems = true;
		foreach ( $itemsNotAnon as $item ) {
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

		$itemsUnread = $this->getItemsFromApiResponse( $resultUnread );
		$this->assertNotEmpty( $itemsUnread );

		$resultHasOnlyUnreadItems = true;
		foreach ( $itemsUnread as $item ) {
			if ( !$item['notificationtimestamp'] ) {
				$resultHasOnlyUnreadItems = false;
				break;
			}
		}
		$this->assertTrue( $resultHasOnlyUnreadItems );

		$itemsNotUnread = $this->getItemsFromApiResponse( $resultNotUnread );
		$this->assertNotEmpty( $itemsNotUnread );

		$resultHasNoUnreadItems = true;
		foreach ( $itemsNotUnread as $item ) {
			if ( $item['notificationtimestamp'] && $item['notificationtimestamp'] < $item['timestamp'] ) {
				$resultHasNoUnreadItems = false;
				break;
			}
		}
		$this->assertTrue( $resultHasNoUnreadItems );
	}

	public function testShowPatrolledParams() {
		$user = $this->getTestUser();
		$this->setupPatrolledSpecificFixtures( $user );

		$previousRights = $user->mRights;
		$this->addPatrolRights( $user );

		$resultPatrolled = $this->doListWatchlistRequest( [
			'wlprop' => 'patrol',
			'wlshow' => 'patrolled'
		], $user );
		$resultNotPatrolled = $this->doListWatchlistRequest( [
			'wlprop' => 'patrol',
			'wlshow' => '!patrolled'
		], $user );

		$user->mRights = $previousRights;

		$itemsPatrolled = $this->getItemsFromApiResponse( $resultPatrolled );
		$this->assertNotEmpty( $itemsPatrolled );

		$resultHasOnlyPatrolledItems = true;
		foreach ( $itemsPatrolled as $item ) {
			if ( !isset( $item['patrolled'] ) ) {
				$resultHasOnlyPatrolledItems = false;
				break;
			}
		}
		$this->assertTrue( $resultHasOnlyPatrolledItems );

		$itemsNotPatrolled = $this->getItemsFromApiResponse( $resultNotPatrolled );
		$this->assertNotEmpty( $itemsNotPatrolled );

		$resultHasNoPatrolledItems = true;
		foreach ( $itemsNotPatrolled as $item ) {
			if ( isset( $item['patrolled'] ) && $item['patrolled'] ) {
				$resultHasNoPatrolledItems = false;
				break;
			}
		}
		$this->assertTrue( $resultHasNoPatrolledItems );
	}

	private function setupCategorizationSpecificFixtures() {
		$user = $this->getTestUser();

		$this->doPageEdit(
			$user,
			'Category:ApiQueryWatchlistIntegrationTestCategory',
			'Some Content',
			'Create the category'
		);

		$this->doPageEdit(
			$user,
			'ApiQueryWatchlistIntegrationTestPageC',
			'Some Content [[Category:ApiQueryWatchlistIntegrationTestCategory]]',
			'Create the page and add it to the category'
		);
		$title = Title::newFromDBkey( 'ApiQueryWatchlistIntegrationTestPageC' );
		$revision = Revision::newFromTitle( $title );

		$rc = RecentChange::newForCategorization(
			$revision->getTimestamp(),
			Title::newFromDBkey( 'Category:ApiQueryWatchlistIntegrationTestCategory' ),
			$user,
			$revision->getComment(),
			$title,
			0,
			$revision->getId(),
			null,
			false
		);
		$rc->save();

		$store = $this->getWatchedItemStore();
		$store->addWatch(
			$user,
			new TitleValue( NS_CATEGORY, 'ApiQueryWatchlistIntegrationTestCategory' )
		);
	}

	private function getExternalRC( $dbKey ) {
		$title = Title::newFromDBkey( $dbKey );

		$rc = new RecentChange;
		$rc->mTitle = $title;
		$rc->mAttribs = [
			'rc_timestamp' => wfTimestamp( TS_MW ),
			'rc_namespace' => $title->getNamespace(),
			'rc_title' => $title->getDBkey(),
			'rc_type' => RC_EXTERNAL,
			'rc_source' => 'foo',
			'rc_minor' => 0,
			'rc_cur_id' => $title->getArticleID(),
			'rc_user' => 0,
			'rc_user_text' => 'External User',
			'rc_comment' => '',
			'rc_this_oldid' => $title->getLatestRevID(),
			'rc_last_oldid' => $title->getLatestRevID(),
			'rc_bot' => 0,
			'rc_ip' => '',
			'rc_patrolled' => 0,
			'rc_new' => 0,
			'rc_old_len' => $title->getLength(),
			'rc_new_len' => $title->getLength(),
			'rc_deleted' => 0,
			'rc_logid' => 0,
			'rc_log_type' => null,
			'rc_log_action' => '',
			'rc_params' => '',
		];
		$rc->mExtra = [
			'prefixedDBkey' => $title->getPrefixedDBkey(),
			'lastTimestamp' => 0,
			'oldSize' => $title->getLength(),
			'newSize' => $title->getLength(),
			'pageStatus' => 'changed'
		];

		return $rc;
	}

	private function setupExternalChangeSpecificFixtures() {
		$user = $this->getTestUser();

		$this->doPageEdit(
			$user,
			'ApiQueryWatchlistIntegrationTestPageX',
			'Some Content',
			'Create the page'
		);

		$rc = $this->getExternalRC( 'ApiQueryWatchlistIntegrationTestPageX' );
		$rc->save();

		$store = $this->getWatchedItemStore();
		$store->addWatch( $user, new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPageX' ) );
	}

	public function typeParamValueProvider() {
		return [
			[ 'new' ],
			[ 'edit' ],
			[ 'log' ],
			[ 'external' ],
			[ 'categorize' ],
		];
	}

	/**
	 * @dataProvider typeParamValueProvider
	 */
	public function testTypeParam( $type ) {
		if ( $type === 'log' ) {
			$this->setupLogSpecificFixtures();
		} elseif ( $type === 'external' ) {
			$this->setupExternalChangeSpecificFixtures();
		} elseif ( $type === 'categorize' ) {
			$this->setupCategorizationSpecificFixtures();
		}

		$result = $this->doListWatchlistRequest( [ 'wltype' => $type, ] );

		$items = $this->getItemsFromApiResponse( $result );
		$this->assertNotEmpty( $items );

		$resultHasOnlyItemsOfGivenType = true;
		foreach ( $items as $item ) {
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

		$this->assertGreaterThan( 5, count( $this->getItemsFromApiResponse( $resultWithoutLimit ) ) );
		$this->assertCount( 5, $this->getItemsFromApiResponse( $resultWithLimit ) );
		$this->assertArrayHasKey( 'continue', $resultWithLimit[0] );
		$this->assertArrayHasKey( 'wlcontinue', $resultWithLimit[0]['continue'] );
	}

	public function testAllRevParam() {
		$result = $this->doListWatchlistRequest( [ 'wlallrev' => '', ] );

		$items = $this->getItemsFromApiResponse( $result );
		$this->assertCount( 8, $items );

		$resultHasCreateRevisionOfThePage = false;
		$resultHasEditRevisionOfThePage = false;
		foreach ( $items as $item ) {
			if ( $item['title'] === 'ApiQueryWatchlistIntegrationTestPage1' ) {
				if ( $item['type'] === 'new' ) {
					$resultHasCreateRevisionOfThePage = true;
				} elseif ( $item['type'] === 'edit' ) {
					$resultHasEditRevisionOfThePage = true;
				}
			}
		}
		$this->assertTrue( $resultHasCreateRevisionOfThePage );
		$this->assertTrue( $resultHasEditRevisionOfThePage );
	}

	public function dirParamValueProvider() {
		return [
			[
				'older',
				[
					'ApiQueryWatchlistIntegrationTestPageA',
					'ApiQueryWatchlistIntegrationTestPage4',
					'ApiQueryWatchlistIntegrationTestPage3',
					'ApiQueryWatchlistIntegrationTestPage2',
					'Talk:ApiQueryWatchlistIntegrationTestPage1',
					'ApiQueryWatchlistIntegrationTestPage1',
				]
			],
			[
				'newer',
				[
					'ApiQueryWatchlistIntegrationTestPage1',
					'Talk:ApiQueryWatchlistIntegrationTestPage1',
					'ApiQueryWatchlistIntegrationTestPage2',
					'ApiQueryWatchlistIntegrationTestPage3',
					'ApiQueryWatchlistIntegrationTestPage4',
					'ApiQueryWatchlistIntegrationTestPageA',
				]
			],
		];
	}

	/**
	 * @dataProvider dirParamValueProvider
	 */
	public function testDirParam( $dir, array $expectedTitles ) {
		$result = $this->doListWatchlistRequest( [ 'wldir' => $dir, ] );

		$items = $this->getItemsFromApiResponse( $result );

		$this->assertCount( count( $expectedTitles ), $items );
		foreach ( $expectedTitles as $index => $expectedTitle ) {
			$this->assertEquals( $expectedTitle, $items[$index]['title'] );
		}
	}

	public function testEnumerateFromTimestamp() {
		$result = $this->doListWatchlistRequest( [
			'wlstart' => '20010115000000',
			'wldir' => 'newer',
		] );

		$this->assertCount( 6, $this->getItemsFromApiResponse( $result ) );
	}

	public function testEnumerateUntilTimestamp() {
		$result = $this->doListWatchlistRequest( [
			'wlend' => '20010115000000',
			'wldir' => 'newer',
		] );

		$this->assertEmpty( $this->getItemsFromApiResponse( $result ) );
	}

	public function testContinueParam() {
		$firstResult = $this->doListWatchlistRequest( [ 'wllimit' => 5, ] );
		$this->assertArrayHasKey( 'continue', $firstResult[0] );

		$continuationParam = $firstResult[0]['continue']['wlcontinue'];

		$continuedResult = $this->doListWatchlistRequest( [ 'wlcontinue' => $continuationParam, ] );

		$items = $this->getItemsFromApiResponse( $continuedResult );
		$this->assertNotEmpty( $items );

		$this->assertCount( 1, $items );
		$this->assertEquals(
			'ApiQueryWatchlistIntegrationTestPage1',
			$items[0]['title']
		);
	}

	public function testOwnerAndTokenParams() {
		$otherUser = ( new TestUser( 'ApiQueryWatchlistIntegrationTestUser2' ) )->getUser();
		$otherUser->setOption( 'watchlisttoken', '1234567890' );
		$otherUser->saveSettings();

		$store = $this->getWatchedItemStore();
		$store->addWatchBatchForUser( $otherUser, [
			new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPage1' ),
			new TitleValue( 1, 'ApiQueryWatchlistIntegrationTestPage1' ),
		] );

		$result = $this->doListWatchlistRequest( [
			'wlowner' => 'ApiQueryWatchlistIntegrationTestUser2',
			'wltoken' => '1234567890',
		] );

		$items = $this->getItemsFromApiResponse( $result );

		$this->assertCount( 2, $items );
		$this->assertEquals(
			'Talk:ApiQueryWatchlistIntegrationTestPage1',
			$items[0]['title']
		);
		$this->assertEquals(
			'ApiQueryWatchlistIntegrationTestPage1',
			$items[1]['title']
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

		$pages = $result[0]['query']['pages'];
		$this->assertNotEmpty( $pages );

		$resultHasExpectedPage = false;
		foreach ( $pages as $page ) {
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

		$pages = $result[0]['query']['pages'];
		$this->assertNotEmpty( $pages );

		$resultHasExpectedPage = false;
		$resultHasExpectedRevision = false;
		foreach ( $pages as $page ) {
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
