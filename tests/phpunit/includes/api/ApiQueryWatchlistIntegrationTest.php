<?php

use MediaWiki\Linker\LinkTarget;
use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\SlotRecord;

/**
 * @group medium
 * @group API
 * @group Database
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
		self::$users['ApiQueryWatchlistIntegrationTestUser'] = $this->getMutableTestUser();
		self::$users['ApiQueryWatchlistIntegrationTestUser2'] = $this->getMutableTestUser();
	}

	private function getLoggedInTestUser() {
		return self::$users['ApiQueryWatchlistIntegrationTestUser']->getUser();
	}

	private function getNonLoggedInTestUser() {
		return self::$users['ApiQueryWatchlistIntegrationTestUser2']->getUser();
	}

	private function doPageEdit( User $user, LinkTarget $target, $content, $summary ) {
		$title = Title::newFromLinkTarget( $target );
		$page = WikiPage::factory( $title );
		$page->doEditContent(
			ContentHandler::makeContent( $content, $title ),
			$summary,
			0,
			false,
			$user
		);
	}

	private function doMinorPageEdit( User $user, LinkTarget $target, $content, $summary ) {
		$title = Title::newFromLinkTarget( $target );
		$page = WikiPage::factory( $title );
		$page->doEditContent(
			ContentHandler::makeContent( $content, $title ),
			$summary,
			EDIT_MINOR,
			false,
			$user
		);
	}

	private function doBotPageEdit( User $user, LinkTarget $target, $content, $summary ) {
		$title = Title::newFromLinkTarget( $target );
		$page = WikiPage::factory( $title );
		$page->doEditContent(
			ContentHandler::makeContent( $content, $title ),
			$summary,
			EDIT_FORCE_BOT,
			false,
			$user
		);
	}

	private function doAnonPageEdit( LinkTarget $target, $content, $summary ) {
		$title = Title::newFromLinkTarget( $target );
		$page = WikiPage::factory( $title );
		$page->doEditContent(
			ContentHandler::makeContent( $content, $title ),
			$summary,
			0,
			false,
			User::newFromId( 0 )
		);
	}

	private function doPatrolledPageEdit(
		User $user,
		LinkTarget $target,
		$content,
		$summary,
		User $patrollingUser
	) {
		$title = Title::newFromLinkTarget( $target );
		$summary = CommentStoreComment::newUnsavedComment( trim( $summary ) );
		$page = WikiPage::factory( $title );

		$updater = $page->newPageUpdater( $user );
		$updater->setContent( SlotRecord::MAIN, ContentHandler::makeContent( $content, $title ) );
		$rev = $updater->saveRevision( $summary );

		$rc = MediaWikiServices::getInstance()->getRevisionStore()->getRecentChange( $rev );
		$rc->doMarkPatrolled( $patrollingUser, false, [] );
	}

	private function deletePage( LinkTarget $target, $reason ) {
		$title = Title::newFromLinkTarget( $target );
		$page = WikiPage::factory( $title );
		$page->doDeleteArticleReal( $reason );
	}

	/**
	 * Performs a batch of page edits as a specified user
	 * @param User $user
	 * @param array $editData associative array, keys:
	 *                        - target    => LinkTarget page to edit
	 *                        - content   => string new content
	 *                        - summary   => string edit summary
	 *                        - minorEdit => bool mark as minor edit if true (defaults to false)
	 *                        - botEdit   => bool mark as bot edit if true (defaults to false)
	 */
	private function doPageEdits( User $user, array $editData ) {
		foreach ( $editData as $singleEditData ) {
			if ( array_key_exists( 'minorEdit', $singleEditData ) && $singleEditData['minorEdit'] ) {
				$this->doMinorPageEdit(
					$user,
					$singleEditData['target'],
					$singleEditData['content'],
					$singleEditData['summary']
				);
				continue;
			}
			if ( array_key_exists( 'botEdit', $singleEditData ) && $singleEditData['botEdit'] ) {
				$this->doBotPageEdit(
					$user,
					$singleEditData['target'],
					$singleEditData['content'],
					$singleEditData['summary']
				);
				continue;
			}
			$this->doPageEdit(
				$user,
				$singleEditData['target'],
				$singleEditData['content'],
				$singleEditData['summary']
			);
		}
	}

	private function getWatchedItemStore() {
		return MediaWikiServices::getInstance()->getWatchedItemStore();
	}

	/**
	 * @param User $user
	 * @param LinkTarget[] $targets
	 */
	private function watchPages( User $user, array $targets ) {
		$store = $this->getWatchedItemStore();
		$store->addWatchBatchForUser( $user, $targets );
	}

	private function doListWatchlistRequest( array $params = [], $user = null ) {
		if ( $user === null ) {
			$user = $this->getLoggedInTestUser();
		}
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
			), null, false, $this->getLoggedInTestUser()
		);
	}

	private function getItemsFromApiResponse( array $response ) {
		return $response[0]['query']['watchlist'];
	}

	/**
	 * Convenience method to assert that actual items array fetched from API is equal to the expected
	 * array, Unlike assertEquals this only checks if values of specified keys are equal in both
	 * arrays. This could be used e.g. not to compare IDs that could change between test run
	 * but only stable keys.
	 * Optionally this also checks that specified keys are present in the actual item without
	 * performing any checks on the related values.
	 *
	 * @param array $actualItems               array of actual items (associative arrays)
	 * @param array $expectedItems             array of expected items (associative arrays),
	 *                                         those items have less keys than actual items
	 * @param array $keysUsedInValueComparison list of keys of the actual item that will be used
	 *                                         in the comparison of values
	 * @param array $requiredKeys              optional, list of keys that must be present in the
	 *                                         actual items. Values of those keys are not checked.
	 */
	private function assertArraySubsetsEqual(
		array $actualItems,
		array $expectedItems,
		array $keysUsedInValueComparison,
		array $requiredKeys = []
	) {
		$this->assertCount( count( $expectedItems ), $actualItems );

		// not checking values of all keys of the actual item, so removing unwanted keys from comparison
		$actualItemsOnlyComparedValues = array_map(
			function ( array $item ) use ( $keysUsedInValueComparison ) {
				return array_intersect_key( $item, array_flip( $keysUsedInValueComparison ) );
			},
			$actualItems
		);

		$this->assertEquals(
			$expectedItems,
			$actualItemsOnlyComparedValues
		);

		// Check that each item in $actualItems contains all of keys specified in $requiredKeys
		$actualItemsKeysOnly = array_map( 'array_keys', $actualItems );
		foreach ( $actualItemsKeysOnly as $keysOfTheItem ) {
			$this->assertEmpty( array_diff( $requiredKeys, $keysOfTheItem ) );
		}
	}

	private function getTitleFormatter() {
		return new MediaWikiTitleCodec(
			Language::factory( 'en' ),
			MediaWikiServices::getInstance()->getGenderCache()
		);
	}

	private function getPrefixedText( LinkTarget $target ) {
		$formatter = $this->getTitleFormatter();
		return $formatter->getPrefixedText( $target );
	}

	private function cleanTestUsersWatchlist() {
		$user = $this->getLoggedInTestUser();
		$store = $this->getWatchedItemStore();
		$items = $store->getWatchedItemsForUser( $user );
		foreach ( $items as $item ) {
			$store->removeWatch( $user, $item->getLinkTarget() );
		}
	}

	public function testListWatchlist_returnsWatchedItemsWithRCInfo() {
		// Clean up after previous tests that might have added something to the watchlist of
		// the user with the same user ID as user used here as the test user
		$this->cleanTestUsersWatchlist();

		$user = $this->getLoggedInTestUser();
		$target = new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPage' );
		$this->doPageEdit(
			$user,
			$target,
			'Some Content',
			'Create the page'
		);
		$this->watchPages( $user, [ $target ] );

		$result = $this->doListWatchlistRequest();

		$this->assertArrayHasKey( 'query', $result[0] );
		$this->assertArrayHasKey( 'watchlist', $result[0]['query'] );

		$this->assertArraySubsetsEqual(
			$this->getItemsFromApiResponse( $result ),
			[
				[
					'type' => 'new',
					'ns' => $target->getNamespace(),
					'title' => $this->getPrefixedText( $target ),
					'bot' => false,
					'new' => true,
					'minor' => false,
				]
			],
			[ 'type', 'ns', 'title', 'bot', 'new', 'minor' ],
			[ 'pageid', 'revid', 'old_revid' ]
		);
	}

	public function testIdsPropParameter() {
		$user = $this->getLoggedInTestUser();
		$target = new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPage' );
		$this->doPageEdit(
			$user,
			$target,
			'Some Content',
			'Create the page'
		);
		$this->watchPages( $user, [ $target ] );

		$result = $this->doListWatchlistRequest( [ 'wlprop' => 'ids', ] );
		$items = $this->getItemsFromApiResponse( $result );

		$this->assertCount( 1, $items );
		$this->assertArrayHasKey( 'pageid', $items[0] );
		$this->assertArrayHasKey( 'revid', $items[0] );
		$this->assertArrayHasKey( 'old_revid', $items[0] );
		$this->assertEquals( 'new', $items[0]['type'] );
	}

	public function testTitlePropParameter() {
		$user = $this->getLoggedInTestUser();
		$subjectTarget = new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPage' );
		$talkTarget = new TitleValue( 1, 'ApiQueryWatchlistIntegrationTestPage' );
		$this->doPageEdits(
			$user,
			[
				[
					'target' => $subjectTarget,
					'content' => 'Some Content',
					'summary' => 'Create the page',
				],
				[
					'target' => $talkTarget,
					'content' => 'Some Talk Page Content',
					'summary' => 'Create Talk page',
				],
			]
		);
		$this->watchPages( $user, [ $subjectTarget, $talkTarget ] );

		$result = $this->doListWatchlistRequest( [ 'wlprop' => 'title', ] );

		$this->assertEquals(
			[
				[
					'type' => 'new',
					'ns' => $talkTarget->getNamespace(),
					'title' => $this->getPrefixedText( $talkTarget ),
				],
				[
					'type' => 'new',
					'ns' => $subjectTarget->getNamespace(),
					'title' => $this->getPrefixedText( $subjectTarget ),
				],
			],
			$this->getItemsFromApiResponse( $result )
		);
	}

	public function testFlagsPropParameter() {
		$user = $this->getLoggedInTestUser();
		$normalEditTarget = new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPage' );
		$minorEditTarget = new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPageM' );
		$botEditTarget = new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPageB' );
		$this->doPageEdits(
			$user,
			[
				[
					'target' => $normalEditTarget,
					'content' => 'Some Content',
					'summary' => 'Create the page',
				],
				[
					'target' => $minorEditTarget,
					'content' => 'Some Content',
					'summary' => 'Create the page',
				],
				[
					'target' => $minorEditTarget,
					'content' => 'Slightly Better Content',
					'summary' => 'Change content',
					'minorEdit' => true,
				],
				[
					'target' => $botEditTarget,
					'content' => 'Some Content',
					'summary' => 'Create the page with a bot',
					'botEdit' => true,
				],
			]
		);
		$this->watchPages( $user, [ $normalEditTarget, $minorEditTarget, $botEditTarget ] );

		$result = $this->doListWatchlistRequest( [ 'wlprop' => 'flags', ] );

		$this->assertEquals(
			[
				[
					'type' => 'new',
					'new' => true,
					'minor' => false,
					'bot' => true,
				],
				[
					'type' => 'edit',
					'new' => false,
					'minor' => true,
					'bot' => false,
				],
				[
					'type' => 'new',
					'new' => true,
					'minor' => false,
					'bot' => false,
				],
			],
			$this->getItemsFromApiResponse( $result )
		);
	}

	public function testUserPropParameter() {
		$user = $this->getLoggedInTestUser();
		$userEditTarget = new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPage' );
		$anonEditTarget = new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPageA' );
		$this->doPageEdit(
			$user,
			$userEditTarget,
			'Some Content',
			'Create the page'
		);
		$this->doAnonPageEdit(
			$anonEditTarget,
			'Some Content',
			'Create the page'
		);
		$this->watchPages( $user, [ $userEditTarget, $anonEditTarget ] );

		$result = $this->doListWatchlistRequest( [ 'wlprop' => 'user', ] );

		$this->assertEquals(
			[
				[
					'type' => 'new',
					'anon' => true,
					'user' => User::newFromId( 0 )->getName(),
				],
				[
					'type' => 'new',
					'user' => $user->getName(),
				],
			],
			$this->getItemsFromApiResponse( $result )
		);
	}

	public function testUserIdPropParameter() {
		$user = $this->getLoggedInTestUser();
		$userEditTarget = new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPage' );
		$anonEditTarget = new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPageA' );
		$this->doPageEdit(
			$user,
			$userEditTarget,
			'Some Content',
			'Create the page'
		);
		$this->doAnonPageEdit(
			$anonEditTarget,
			'Some Content',
			'Create the page'
		);
		$this->watchPages( $user, [ $userEditTarget, $anonEditTarget ] );

		$result = $this->doListWatchlistRequest( [ 'wlprop' => 'userid', ] );

		$this->assertEquals(
			[
				[
					'type' => 'new',
					'anon' => true,
					'user' => 0,
					'userid' => 0,
				],
				[
					'type' => 'new',
					'user' => $user->getId(),
					'userid' => $user->getId(),
				],
			],
			$this->getItemsFromApiResponse( $result )
		);
	}

	public function testCommentPropParameter() {
		$user = $this->getLoggedInTestUser();
		$target = new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPage' );
		$this->doPageEdit(
			$user,
			$target,
			'Some Content',
			'Create the <b>page</b>'
		);
		$this->watchPages( $user, [ $target ] );

		$result = $this->doListWatchlistRequest( [ 'wlprop' => 'comment', ] );

		$this->assertEquals(
			[
				[
					'type' => 'new',
					'comment' => 'Create the <b>page</b>',
				],
			],
			$this->getItemsFromApiResponse( $result )
		);
	}

	public function testParsedCommentPropParameter() {
		$user = $this->getLoggedInTestUser();
		$target = new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPage' );
		$this->doPageEdit(
			$user,
			$target,
			'Some Content',
			'Create the <b>page</b>'
		);
		$this->watchPages( $user, [ $target ] );

		$result = $this->doListWatchlistRequest( [ 'wlprop' => 'parsedcomment', ] );

		$this->assertEquals(
			[
				[
					'type' => 'new',
					'parsedcomment' => 'Create the &lt;b&gt;page&lt;/b&gt;',
				],
			],
			$this->getItemsFromApiResponse( $result )
		);
	}

	public function testTimestampPropParameter() {
		$user = $this->getLoggedInTestUser();
		$target = new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPage' );
		$this->doPageEdit(
			$user,
			$target,
			'Some Content',
			'Create the page'
		);
		$this->watchPages( $user, [ $target ] );

		$result = $this->doListWatchlistRequest( [ 'wlprop' => 'timestamp', ] );
		$items = $this->getItemsFromApiResponse( $result );

		$this->assertCount( 1, $items );
		$this->assertArrayHasKey( 'timestamp', $items[0] );
		$this->assertInternalType( 'string', $items[0]['timestamp'] );
	}

	public function testSizesPropParameter() {
		$user = $this->getLoggedInTestUser();
		$target = new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPage' );
		$this->doPageEdit(
			$user,
			$target,
			'Some Content',
			'Create the page'
		);
		$this->watchPages( $user, [ $target ] );

		$result = $this->doListWatchlistRequest( [ 'wlprop' => 'sizes', ] );

		$this->assertEquals(
			[
				[
					'type' => 'new',
					'oldlen' => 0,
					'newlen' => 12,
				],
			],
			$this->getItemsFromApiResponse( $result )
		);
	}

	public function testNotificationTimestampPropParameter() {
		$otherUser = $this->getNonLoggedInTestUser();
		$target = new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPage' );
		$this->doPageEdit(
			$otherUser,
			$target,
			'Some Content',
			'Create the page'
		);
		$store = $this->getWatchedItemStore();
		$store->addWatch( $this->getLoggedInTestUser(), $target );
		$store->updateNotificationTimestamp(
			$otherUser,
			$target,
			'20151212010101'
		);

		$result = $this->doListWatchlistRequest( [ 'wlprop' => 'notificationtimestamp', ] );

		$this->assertEquals(
			[
				[
					'type' => 'new',
					'notificationtimestamp' => '2015-12-12T01:01:01Z',
				],
			],
			$this->getItemsFromApiResponse( $result )
		);
	}

	private function setupPatrolledSpecificFixtures( User $user ) {
		$target = new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPage' );

		$this->doPatrolledPageEdit(
			$user,
			$target,
			'Some Content',
			'Create the page (this gets patrolled)',
			$user
		);

		$this->watchPages( $user, [ $target ] );
	}

	public function testPatrolPropParameter() {
		$testUser = static::getTestSysop();
		$user = $testUser->getUser();
		$this->setupPatrolledSpecificFixtures( $user );

		$result = $this->doListWatchlistRequest( [ 'wlprop' => 'patrol', ], $user );

		$this->assertEquals(
			[
				[
					'type' => 'new',
					'patrolled' => true,
					'unpatrolled' => false,
					'autopatrolled' => false,
				]
			],
			$this->getItemsFromApiResponse( $result )
		);
	}

	private function createPageAndDeleteIt( LinkTarget $target ) {
		$this->doPageEdit(
			$this->getLoggedInTestUser(),
			$target,
			'Some Content',
			'Create the page that will be deleted'
		);
		$this->deletePage( $target, 'Important Reason' );
	}

	public function testLoginfoPropParameter() {
		$target = new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPage' );
		$this->createPageAndDeleteIt( $target );

		$this->watchPages( $this->getLoggedInTestUser(), [ $target ] );

		$result = $this->doListWatchlistRequest( [ 'wlprop' => 'loginfo', ] );

		$this->assertArraySubsetsEqual(
			$this->getItemsFromApiResponse( $result ),
			[
				[
					'type' => 'log',
					'logtype' => 'delete',
					'logaction' => 'delete',
					'logparams' => [],
				],
			],
			[ 'type', 'logtype', 'logaction', 'logparams' ],
			[ 'logid' ]
		);
	}

	public function testEmptyPropParameter() {
		$user = $this->getLoggedInTestUser();
		$target = new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPage' );
		$this->doPageEdit(
			$user,
			$target,
			'Some Content',
			'Create the page'
		);
		$this->watchPages( $user, [ $target ] );

		$result = $this->doListWatchlistRequest( [ 'wlprop' => '', ] );

		$this->assertEquals(
			[
				[
					'type' => 'new',
				]
			],
			$this->getItemsFromApiResponse( $result )
		);
	}

	public function testNamespaceParam() {
		$user = $this->getLoggedInTestUser();
		$subjectTarget = new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPage' );
		$talkTarget = new TitleValue( 1, 'ApiQueryWatchlistIntegrationTestPage' );
		$this->doPageEdits(
			$user,
			[
				[
					'target' => $subjectTarget,
					'content' => 'Some Content',
					'summary' => 'Create the page',
				],
				[
					'target' => $talkTarget,
					'content' => 'Some Content',
					'summary' => 'Create the talk page',
				],
			]
		);
		$this->watchPages( $user, [ $subjectTarget, $talkTarget ] );

		$result = $this->doListWatchlistRequest( [ 'wlnamespace' => '0', ] );

		$this->assertArraySubsetsEqual(
			$this->getItemsFromApiResponse( $result ),
			[
				[
					'ns' => 0,
					'title' => $this->getPrefixedText( $subjectTarget ),
				],
			],
			[ 'ns', 'title' ]
		);
	}

	public function testUserParam() {
		$user = $this->getLoggedInTestUser();
		$otherUser = $this->getNonLoggedInTestUser();
		$subjectTarget = new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPage' );
		$talkTarget = new TitleValue( 1, 'ApiQueryWatchlistIntegrationTestPage' );
		$this->doPageEdit(
			$user,
			$subjectTarget,
			'Some Content',
			'Create the page'
		);
		$this->doPageEdit(
			$otherUser,
			$talkTarget,
			'What is this page about?',
			'Create the talk page'
		);
		$this->watchPages( $user, [ $subjectTarget, $talkTarget ] );

		$result = $this->doListWatchlistRequest( [
			'wlprop' => 'user|title',
			'wluser' => $otherUser->getName(),
		] );

		$this->assertEquals(
			[
				[
					'type' => 'new',
					'ns' => $talkTarget->getNamespace(),
					'title' => $this->getPrefixedText( $talkTarget ),
					'user' => $otherUser->getName(),
				],
			],
			$this->getItemsFromApiResponse( $result )
		);
	}

	public function testExcludeUserParam() {
		$user = $this->getLoggedInTestUser();
		$otherUser = $this->getNonLoggedInTestUser();
		$subjectTarget = new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPage' );
		$talkTarget = new TitleValue( 1, 'ApiQueryWatchlistIntegrationTestPage' );
		$this->doPageEdit(
			$user,
			$subjectTarget,
			'Some Content',
			'Create the page'
		);
		$this->doPageEdit(
			$otherUser,
			$talkTarget,
			'What is this page about?',
			'Create the talk page'
		);
		$this->watchPages( $user, [ $subjectTarget, $talkTarget ] );

		$result = $this->doListWatchlistRequest( [
			'wlprop' => 'user|title',
			'wlexcludeuser' => $otherUser->getName(),
		] );

		$this->assertEquals(
			[
				[
					'type' => 'new',
					'ns' => $subjectTarget->getNamespace(),
					'title' => $this->getPrefixedText( $subjectTarget ),
					'user' => $user->getName(),
				]
			],
			$this->getItemsFromApiResponse( $result )
		);
	}

	public function testShowMinorParams() {
		$user = $this->getLoggedInTestUser();
		$target = new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPage' );
		$this->doPageEdits(
			$user,
			[
				[
					'target' => $target,
					'content' => 'Some Content',
					'summary' => 'Create the page',
				],
				[
					'target' => $target,
					'content' => 'Slightly Better Content',
					'summary' => 'Change content',
					'minorEdit' => true,
				],
			]
		);
		$this->watchPages( $user, [ $target ] );

		$resultMinor = $this->doListWatchlistRequest( [
			'wlshow' => WatchedItemQueryService::FILTER_MINOR,
			'wlprop' => 'flags'
		] );
		$resultNotMinor = $this->doListWatchlistRequest( [
			'wlshow' => WatchedItemQueryService::FILTER_NOT_MINOR, 'wlprop' => 'flags'
		] );

		$this->assertArraySubsetsEqual(
			$this->getItemsFromApiResponse( $resultMinor ),
			[
				[ 'minor' => true, ]
			],
			[ 'minor' ]
		);
		$this->assertEmpty( $this->getItemsFromApiResponse( $resultNotMinor ) );
	}

	public function testShowBotParams() {
		$user = $this->getLoggedInTestUser();
		$target = new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPage' );
		$this->doBotPageEdit(
			$user,
			$target,
			'Some Content',
			'Create the page'
		);
		$this->watchPages( $user, [ $target ] );

		$resultBot = $this->doListWatchlistRequest( [
			'wlshow' => WatchedItemQueryService::FILTER_BOT
		] );
		$resultNotBot = $this->doListWatchlistRequest( [
			'wlshow' => WatchedItemQueryService::FILTER_NOT_BOT
		] );

		$this->assertArraySubsetsEqual(
			$this->getItemsFromApiResponse( $resultBot ),
			[
				[ 'bot' => true ],
			],
			[ 'bot' ]
		);
		$this->assertEmpty( $this->getItemsFromApiResponse( $resultNotBot ) );
	}

	public function testShowAnonParams() {
		$user = $this->getLoggedInTestUser();
		$target = new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPage' );
		$this->doAnonPageEdit(
			$target,
			'Some Content',
			'Create the page'
		);
		$this->watchPages( $user, [ $target ] );

		$resultAnon = $this->doListWatchlistRequest( [
			'wlprop' => 'user',
			'wlshow' => WatchedItemQueryService::FILTER_ANON
		] );
		$resultNotAnon = $this->doListWatchlistRequest( [
			'wlprop' => 'user',
			'wlshow' => WatchedItemQueryService::FILTER_NOT_ANON
		] );

		$this->assertArraySubsetsEqual(
			$this->getItemsFromApiResponse( $resultAnon ),
			[
				[ 'anon' => true ],
			],
			[ 'anon' ]
		);
		$this->assertEmpty( $this->getItemsFromApiResponse( $resultNotAnon ) );
	}

	public function testShowUnreadParams() {
		$user = $this->getLoggedInTestUser();
		$otherUser = $this->getNonLoggedInTestUser();
		$subjectTarget = new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPage' );
		$talkTarget = new TitleValue( 1, 'ApiQueryWatchlistIntegrationTestPage' );
		$this->doPageEdit(
			$user,
			$subjectTarget,
			'Some Content',
			'Create the page'
		);
		$this->doPageEdit(
			$otherUser,
			$talkTarget,
			'Some Content',
			'Create the talk page'
		);
		$store = $this->getWatchedItemStore();
		$store->addWatchBatchForUser( $user, [ $subjectTarget, $talkTarget ] );
		$store->updateNotificationTimestamp(
			$otherUser,
			$talkTarget,
			'20151212010101'
		);

		$resultUnread = $this->doListWatchlistRequest( [
			'wlprop' => 'notificationtimestamp|title',
			'wlshow' => WatchedItemQueryService::FILTER_UNREAD
		] );
		$resultNotUnread = $this->doListWatchlistRequest( [
			'wlprop' => 'notificationtimestamp|title',
			'wlshow' => WatchedItemQueryService::FILTER_NOT_UNREAD
		] );

		$this->assertEquals(
			[
				[
					'type' => 'new',
					'notificationtimestamp' => '2015-12-12T01:01:01Z',
					'ns' => $talkTarget->getNamespace(),
					'title' => $this->getPrefixedText( $talkTarget )
				]
			],
			$this->getItemsFromApiResponse( $resultUnread )
		);
		$this->assertEquals(
			[
				[
					'type' => 'new',
					'notificationtimestamp' => '',
					'ns' => $subjectTarget->getNamespace(),
					'title' => $this->getPrefixedText( $subjectTarget )
				]
			],
			$this->getItemsFromApiResponse( $resultNotUnread )
		);
	}

	public function testShowPatrolledParams() {
		$user = static::getTestSysop()->getUser();
		$this->setupPatrolledSpecificFixtures( $user );

		$resultPatrolled = $this->doListWatchlistRequest( [
			'wlprop' => 'patrol',
			'wlshow' => WatchedItemQueryService::FILTER_PATROLLED
		], $user );
		$resultNotPatrolled = $this->doListWatchlistRequest( [
			'wlprop' => 'patrol',
			'wlshow' => WatchedItemQueryService::FILTER_NOT_PATROLLED
		], $user );

		$this->assertEquals(
			[
				[
					'type' => 'new',
					'patrolled' => true,
					'unpatrolled' => false,
					'autopatrolled' => false,
				]
			],
			$this->getItemsFromApiResponse( $resultPatrolled )
		);
		$this->assertEmpty( $this->getItemsFromApiResponse( $resultNotPatrolled ) );
	}

	public function testNewAndEditTypeParameters() {
		$user = $this->getLoggedInTestUser();
		$subjectTarget = new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPage' );
		$talkTarget = new TitleValue( 1, 'ApiQueryWatchlistIntegrationTestPage' );
		$this->doPageEdits(
			$user,
			[
				[
					'target' => $subjectTarget,
					'content' => 'Some Content',
					'summary' => 'Create the page',
				],
				[
					'target' => $subjectTarget,
					'content' => 'Some Other Content',
					'summary' => 'Change the content',
				],
				[
					'target' => $talkTarget,
					'content' => 'Some Talk Page Content',
					'summary' => 'Create Talk page',
				],
			]
		);
		$this->watchPages( $user, [ $subjectTarget, $talkTarget ] );

		$resultNew = $this->doListWatchlistRequest( [ 'wlprop' => 'title', 'wltype' => 'new' ] );
		$resultEdit = $this->doListWatchlistRequest( [ 'wlprop' => 'title', 'wltype' => 'edit' ] );

		$this->assertEquals(
			[
				[
					'type' => 'new',
					'ns' => $talkTarget->getNamespace(),
					'title' => $this->getPrefixedText( $talkTarget ),
				],
			],
			$this->getItemsFromApiResponse( $resultNew )
		);
		$this->assertEquals(
			[
				[
					'type' => 'edit',
					'ns' => $subjectTarget->getNamespace(),
					'title' => $this->getPrefixedText( $subjectTarget ),
				],
			],
			$this->getItemsFromApiResponse( $resultEdit )
		);
	}

	public function testLogTypeParameters() {
		$user = $this->getLoggedInTestUser();
		$subjectTarget = new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPage' );
		$talkTarget = new TitleValue( 1, 'ApiQueryWatchlistIntegrationTestPage' );
		$this->createPageAndDeleteIt( $subjectTarget );
		$this->doPageEdit(
			$user,
			$talkTarget,
			'Some Talk Page Content',
			'Create Talk page'
		);
		$this->watchPages( $user, [ $subjectTarget, $talkTarget ] );

		$result = $this->doListWatchlistRequest( [ 'wlprop' => 'title', 'wltype' => 'log' ] );

		$this->assertEquals(
			[
				[
					'type' => 'log',
					'ns' => $subjectTarget->getNamespace(),
					'title' => $this->getPrefixedText( $subjectTarget ),
				],
			],
			$this->getItemsFromApiResponse( $result )
		);
	}

	private function getExternalRC( LinkTarget $target ) {
		$title = Title::newFromLinkTarget( $target );

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
			'rc_user_text' => 'ext>External User',
			'rc_comment' => '',
			'rc_comment_text' => '',
			'rc_comment_data' => null,
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

	public function testExternalTypeParameters() {
		$user = $this->getLoggedInTestUser();
		$subjectTarget = new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPage' );
		$talkTarget = new TitleValue( 1, 'ApiQueryWatchlistIntegrationTestPage' );
		$this->doPageEdit(
			$user,
			$subjectTarget,
			'Some Content',
			'Create the page'
		);
		$this->doPageEdit(
			$user,
			$talkTarget,
			'Some Talk Page Content',
			'Create Talk page'
		);

		$rc = $this->getExternalRC( $subjectTarget );
		$rc->save();

		$this->watchPages( $user, [ $subjectTarget, $talkTarget ] );

		$result = $this->doListWatchlistRequest( [ 'wlprop' => 'title', 'wltype' => 'external' ] );

		$this->assertEquals(
			[
				[
					'type' => 'external',
					'ns' => $subjectTarget->getNamespace(),
					'title' => $this->getPrefixedText( $subjectTarget ),
				],
			],
			$this->getItemsFromApiResponse( $result )
		);
	}

	public function testCategorizeTypeParameter() {
		$user = $this->getLoggedInTestUser();
		$subjectTarget = new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPage' );
		$categoryTarget = new TitleValue( NS_CATEGORY, 'ApiQueryWatchlistIntegrationTestCategory' );
		$this->doPageEdits(
			$user,
			[
				[
					'target' => $categoryTarget,
					'content' => 'Some Content',
					'summary' => 'Create the category',
				],
				[
					'target' => $subjectTarget,
					'content' => 'Some Content [[Category:ApiQueryWatchlistIntegrationTestCategory]]t',
					'summary' => 'Create the page and add it to the category',
				],
			]
		);
		$title = Title::newFromLinkTarget( $subjectTarget );
		$revision = Revision::newFromTitle( $title );

		$rc = RecentChange::newForCategorization(
			$revision->getTimestamp(),
			Title::newFromLinkTarget( $categoryTarget ),
			$user,
			$revision->getComment(),
			$title,
			0,
			$revision->getId(),
			null,
			false
		);
		$rc->save();

		$this->watchPages( $user, [ $subjectTarget, $categoryTarget ] );

		$result = $this->doListWatchlistRequest( [ 'wlprop' => 'title', 'wltype' => 'categorize' ] );

		$this->assertEquals(
			[
				[
					'type' => 'categorize',
					'ns' => $categoryTarget->getNamespace(),
					'title' => $this->getPrefixedText( $categoryTarget ),
				],
			],
			$this->getItemsFromApiResponse( $result )
		);
	}

	public function testLimitParam() {
		$user = $this->getLoggedInTestUser();
		$target1 = new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPage' );
		$target2 = new TitleValue( 1, 'ApiQueryWatchlistIntegrationTestPage' );
		$target3 = new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPage2' );
		$this->doPageEdits(
			$user,
			[
				[
					'target' => $target1,
					'content' => 'Some Content',
					'summary' => 'Create the page',
				],
				[
					'target' => $target2,
					'content' => 'Some Talk Page Content',
					'summary' => 'Create Talk page',
				],
				[
					'target' => $target3,
					'content' => 'Some Other Content',
					'summary' => 'Create the page',
				],
			]
		);
		$this->watchPages( $user, [ $target1, $target2, $target3 ] );

		$resultWithoutLimit = $this->doListWatchlistRequest( [ 'wlprop' => 'title' ] );
		$resultWithLimit = $this->doListWatchlistRequest( [ 'wllimit' => 2, 'wlprop' => 'title' ] );

		$this->assertEquals(
			[
				[
					'type' => 'new',
					'ns' => $target3->getNamespace(),
					'title' => $this->getPrefixedText( $target3 )
				],
				[
					'type' => 'new',
					'ns' => $target2->getNamespace(),
					'title' => $this->getPrefixedText( $target2 )
				],
				[
					'type' => 'new',
					'ns' => $target1->getNamespace(),
					'title' => $this->getPrefixedText( $target1 )
				],
			],
			$this->getItemsFromApiResponse( $resultWithoutLimit )
		);
		$this->assertEquals(
			[
				[
					'type' => 'new',
					'ns' => $target3->getNamespace(),
					'title' => $this->getPrefixedText( $target3 )
				],
				[
					'type' => 'new',
					'ns' => $target2->getNamespace(),
					'title' => $this->getPrefixedText( $target2 )
				],
			],
			$this->getItemsFromApiResponse( $resultWithLimit )
		);
		$this->assertArrayHasKey( 'continue', $resultWithLimit[0] );
		$this->assertArrayHasKey( 'wlcontinue', $resultWithLimit[0]['continue'] );
	}

	public function testAllRevParam() {
		$user = $this->getLoggedInTestUser();
		$target = new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPage' );
		$this->doPageEdits(
			$user,
			[
				[
					'target' => $target,
					'content' => 'Some Content',
					'summary' => 'Create the page',
				],
				[
					'target' => $target,
					'content' => 'Some Other Content',
					'summary' => 'Change the content',
				],
			]
		);
		$this->watchPages( $user, [ $target ] );

		$resultAllRev = $this->doListWatchlistRequest( [ 'wlprop' => 'title', 'wlallrev' => '', ] );
		$resultNoAllRev = $this->doListWatchlistRequest( [ 'wlprop' => 'title' ] );

		$this->assertEquals(
			[
				[
					'type' => 'edit',
					'ns' => $target->getNamespace(),
					'title' => $this->getPrefixedText( $target ),
				],
			],
			$this->getItemsFromApiResponse( $resultNoAllRev )
		);
		$this->assertEquals(
			[
				[
					'type' => 'edit',
					'ns' => $target->getNamespace(),
					'title' => $this->getPrefixedText( $target ),
				],
				[
					'type' => 'new',
					'ns' => $target->getNamespace(),
					'title' => $this->getPrefixedText( $target ),
				],
			],
			$this->getItemsFromApiResponse( $resultAllRev )
		);
	}

	public function testDirParams() {
		$user = $this->getLoggedInTestUser();
		$subjectTarget = new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPage' );
		$talkTarget = new TitleValue( 1, 'ApiQueryWatchlistIntegrationTestPage' );
		$this->doPageEdits(
			$user,
			[
				[
					'target' => $subjectTarget,
					'content' => 'Some Content',
					'summary' => 'Create the page',
				],
				[
					'target' => $talkTarget,
					'content' => 'Some Talk Page Content',
					'summary' => 'Create Talk page',
				],
			]
		);
		$this->watchPages( $user, [ $subjectTarget, $talkTarget ] );

		$resultDirOlder = $this->doListWatchlistRequest( [ 'wldir' => 'older', 'wlprop' => 'title' ] );
		$resultDirNewer = $this->doListWatchlistRequest( [ 'wldir' => 'newer', 'wlprop' => 'title' ] );

		$this->assertEquals(
			[
				[
					'type' => 'new',
					'ns' => $talkTarget->getNamespace(),
					'title' => $this->getPrefixedText( $talkTarget )
				],
				[
					'type' => 'new',
					'ns' => $subjectTarget->getNamespace(),
					'title' => $this->getPrefixedText( $subjectTarget )
				],
			],
			$this->getItemsFromApiResponse( $resultDirOlder )
		);
		$this->assertEquals(
			[
				[
					'type' => 'new',
					'ns' => $subjectTarget->getNamespace(),
					'title' => $this->getPrefixedText( $subjectTarget )
				],
				[
					'type' => 'new',
					'ns' => $talkTarget->getNamespace(),
					'title' => $this->getPrefixedText( $talkTarget )
				],
			],
			$this->getItemsFromApiResponse( $resultDirNewer )
		);
	}

	public function testStartEndParams() {
		$user = $this->getLoggedInTestUser();
		$target = new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPage' );
		$this->doPageEdit(
			$user,
			$target,
			'Some Content',
			'Create the page'
		);
		$this->watchPages( $user, [ $target ] );

		$resultStart = $this->doListWatchlistRequest( [
			'wlstart' => '20010115000000',
			'wldir' => 'newer',
			'wlprop' => 'title',
		] );
		$resultEnd = $this->doListWatchlistRequest( [
			'wlend' => '20010115000000',
			'wldir' => 'newer',
			'wlprop' => 'title',
		] );

		$this->assertEquals(
			[
				[
					'type' => 'new',
					'ns' => $target->getNamespace(),
					'title' => $this->getPrefixedText( $target ),
				]
			],
			$this->getItemsFromApiResponse( $resultStart )
		);
		$this->assertEmpty( $this->getItemsFromApiResponse( $resultEnd ) );
	}

	public function testContinueParam() {
		$user = $this->getLoggedInTestUser();
		$target1 = new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPage' );
		$target2 = new TitleValue( 1, 'ApiQueryWatchlistIntegrationTestPage' );
		$target3 = new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPage2' );
		$this->doPageEdits(
			$user,
			[
				[
					'target' => $target1,
					'content' => 'Some Content',
					'summary' => 'Create the page',
				],
				[
					'target' => $target2,
					'content' => 'Some Talk Page Content',
					'summary' => 'Create Talk page',
				],
				[
					'target' => $target3,
					'content' => 'Some Other Content',
					'summary' => 'Create the page',
				],
			]
		);
		$this->watchPages( $user, [ $target1, $target2,	$target3 ] );

		$firstResult = $this->doListWatchlistRequest( [ 'wllimit' => 2, 'wlprop' => 'title' ] );
		$this->assertArrayHasKey( 'continue', $firstResult[0] );
		$this->assertArrayHasKey( 'wlcontinue', $firstResult[0]['continue'] );

		$continuationParam = $firstResult[0]['continue']['wlcontinue'];

		$continuedResult = $this->doListWatchlistRequest(
			[ 'wlcontinue' => $continuationParam, 'wlprop' => 'title' ]
		);

		$this->assertEquals(
			[
				[
					'type' => 'new',
					'ns' => $target3->getNamespace(),
					'title' => $this->getPrefixedText( $target3 ),
				],
				[
					'type' => 'new',
					'ns' => $target2->getNamespace(),
					'title' => $this->getPrefixedText( $target2 ),
				],
			],
			$this->getItemsFromApiResponse( $firstResult )
		);
		$this->assertEquals(
			[
				[
					'type' => 'new',
					'ns' => $target1->getNamespace(),
					'title' => $this->getPrefixedText( $target1 )
				]
			],
			$this->getItemsFromApiResponse( $continuedResult )
		);
	}

	public function testOwnerAndTokenParams() {
		$target = new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPage' );
		$this->doPageEdit(
			$this->getLoggedInTestUser(),
			$target,
			'Some Content',
			'Create the page'
		);

		$otherUser = $this->getNonLoggedInTestUser();
		$otherUser->setOption( 'watchlisttoken', '1234567890' );
		$otherUser->saveSettings();

		$this->watchPages( $otherUser, [ $target ] );

		$reloadedUser = User::newFromName( $otherUser->getName() );
		$this->assertEquals( '1234567890', $reloadedUser->getOption( 'watchlisttoken' ) );

		$result = $this->doListWatchlistRequest( [
			'wlowner' => $otherUser->getName(),
			'wltoken' => '1234567890',
			'wlprop' => 'title',
		] );

		$this->assertEquals(
			[
				[
					'type' => 'new',
					'ns' => $target->getNamespace(),
					'title' => $this->getPrefixedText( $target )
				]
			],
			$this->getItemsFromApiResponse( $result )
		);
	}

	public function testOwnerAndTokenParams_wrongToken() {
		$otherUser = $this->getNonLoggedInTestUser();
		$otherUser->setOption( 'watchlisttoken', '1234567890' );
		$otherUser->saveSettings();

		$this->setExpectedException( ApiUsageException::class, 'Incorrect watchlist token provided' );

		$this->doListWatchlistRequest( [
			'wlowner' => $otherUser->getName(),
			'wltoken' => 'wrong-token',
		] );
	}

	public function testOwnerAndTokenParams_noWatchlistTokenSet() {
		$this->setExpectedException( ApiUsageException::class, 'Incorrect watchlist token provided' );

		$this->doListWatchlistRequest( [
			'wlowner' => $this->getNonLoggedInTestUser()->getName(),
			'wltoken' => 'some-token',
		] );
	}

	public function testGeneratorWatchlistPropInfo_returnsWatchedPages() {
		$user = $this->getLoggedInTestUser();
		$target = new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPage' );
		$this->doPageEdit(
			$user,
			$target,
			'Some Content',
			'Create the page'
		);
		$this->watchPages( $user, [ $target ] );

		$result = $this->doGeneratorWatchlistRequest( [ 'prop' => 'info' ] );

		$this->assertArrayHasKey( 'query', $result[0] );
		$this->assertArrayHasKey( 'pages', $result[0]['query'] );

		// $result[0]['query']['pages'] uses page ids as keys. Page ids don't matter here, so drop them
		$pages = array_values( $result[0]['query']['pages'] );

		$this->assertArraySubsetsEqual(
			$pages,
			[
				[
					'ns' => $target->getNamespace(),
					'title' => $this->getPrefixedText( $target ),
					'new' => true,
				]
			],
			[ 'ns', 'title', 'new' ]
		);
	}

	public function testGeneratorWatchlistPropRevisions_returnsWatchedItemsRevisions() {
		$user = $this->getLoggedInTestUser();
		$target = new TitleValue( 0, 'ApiQueryWatchlistIntegrationTestPage' );
		$this->doPageEdits(
			$user,
			[
				[
					'target' => $target,
					'content' => 'Some Content',
					'summary' => 'Create the page',
				],
				[
					'target' => $target,
					'content' => 'Some Other Content',
					'summary' => 'Change the content',
				],
			]
		);
		$this->watchPages( $user, [ $target ] );

		$result = $this->doGeneratorWatchlistRequest( [ 'prop' => 'revisions', 'gwlallrev' => '' ] );

		$this->assertArrayHasKey( 'query', $result[0] );
		$this->assertArrayHasKey( 'pages', $result[0]['query'] );

		// $result[0]['query']['pages'] uses page ids as keys. Page ids don't matter here, so drop them
		$pages = array_values( $result[0]['query']['pages'] );

		$this->assertCount( 1, $pages );
		$this->assertEquals( 0, $pages[0]['ns'] );
		$this->assertEquals( $this->getPrefixedText( $target ), $pages[0]['title'] );
		$this->assertArraySubsetsEqual(
			$pages[0]['revisions'],
			[
				[
					'comment' => 'Create the page',
					'user' => $user->getName(),
					'minor' => false,
				],
				[
					'comment' => 'Change the content',
					'user' => $user->getName(),
					'minor' => false,
				],
			],
			[ 'comment', 'user', 'minor' ]
		);
	}

}
