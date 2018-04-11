<?php

use MediaWiki\Linker\LinkTarget;
use MediaWiki\MediaWikiServices;

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers ApiQueryRecentChanges
 */
class ApiQueryRecentChangesIntegrationTest extends ApiTestCase {

	public function __construct( $name = null, array $data = [], $dataName = '' ) {
		parent::__construct( $name, $data, $dataName );

		$this->tablesUsed[] = 'recentchanges';
		$this->tablesUsed[] = 'page';
	}

	protected function setUp() {
		parent::setUp();

		self::$users['ApiQueryRecentChangesIntegrationTestUser'] = $this->getMutableTestUser();
		wfGetDB( DB_MASTER )->delete( 'recentchanges', '*', __METHOD__ );
	}

	private function getLoggedInTestUser() {
		return self::$users['ApiQueryRecentChangesIntegrationTestUser']->getUser();
	}

	private function doPageEdit( User $user, LinkTarget $target, $summary ) {
		static $i = 0;

		$title = Title::newFromLinkTarget( $target );
		$page = WikiPage::factory( $title );
		$page->doEditContent(
			ContentHandler::makeContent( __CLASS__ . $i++, $title ),
			$summary,
			0,
			false,
			$user
		);
	}

	private function doMinorPageEdit( User $user, LinkTarget $target, $summary ) {
		$title = Title::newFromLinkTarget( $target );
		$page = WikiPage::factory( $title );
		$page->doEditContent(
			ContentHandler::makeContent( __CLASS__, $title ),
			$summary,
			EDIT_MINOR,
			false,
			$user
		);
	}

	private function doBotPageEdit( User $user, LinkTarget $target, $summary ) {
		$title = Title::newFromLinkTarget( $target );
		$page = WikiPage::factory( $title );
		$page->doEditContent(
			ContentHandler::makeContent( __CLASS__, $title ),
			$summary,
			EDIT_FORCE_BOT,
			false,
			$user
		);
	}

	private function doAnonPageEdit( LinkTarget $target, $summary ) {
		$title = Title::newFromLinkTarget( $target );
		$page = WikiPage::factory( $title );
		$page->doEditContent(
			ContentHandler::makeContent( __CLASS__, $title ),
			$summary,
			0,
			false,
			User::newFromId( 0 )
		);
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
					$singleEditData['summary']
				);
				continue;
			}
			if ( array_key_exists( 'botEdit', $singleEditData ) && $singleEditData['botEdit'] ) {
				$this->doBotPageEdit(
					$user,
					$singleEditData['target'],
					$singleEditData['summary']
				);
				continue;
			}
			$this->doPageEdit(
				$user,
				$singleEditData['target'],
				$singleEditData['summary']
			);
		}
	}

	private function doListRecentChangesRequest( array $params = [] ) {
		return $this->doApiRequest(
			array_merge(
				[ 'action' => 'query', 'list' => 'recentchanges' ],
				$params
			),
			null,
			false,
			$this->getLoggedInTestUser()
		);
	}

	private function doGeneratorRecentChangesRequest( array $params = [] ) {
		return $this->doApiRequest(
			array_merge(
				[ 'action' => 'query', 'generator' => 'recentchanges' ],
				$params
			),
			null,
			false,
			$this->getLoggedInTestUser()
		);
	}

	private function getItemsFromApiResponse( array $response ) {
		return $response[0]['query']['recentchanges'];
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

	public function testListRecentChanges_returnsRCInfo() {
		$target = new TitleValue( 0, 'ApiQueryRecentChangesIntegrationTestPage' );
		$this->doPageEdit( $this->getLoggedInTestUser(), $target, 'Create the page' );

		$result = $this->doListRecentChangesRequest();

		$this->assertArrayHasKey( 'query', $result[0] );
		$this->assertArrayHasKey( 'recentchanges', $result[0]['query'] );

		$items = $this->getItemsFromApiResponse( $result );
		$this->assertCount( 1, $items );
		$item = $items[0];
		$this->assertArraySubset(
			[
				'type' => 'new',
				'ns' => $target->getNamespace(),
				'title' => $this->getPrefixedText( $target ),
			],
			$item
		);
		$this->assertArrayNotHasKey( 'bot', $item );
		$this->assertArrayNotHasKey( 'new', $item );
		$this->assertArrayNotHasKey( 'minor', $item );
		$this->assertArrayHasKey( 'pageid', $item );
		$this->assertArrayHasKey( 'revid', $item );
		$this->assertArrayHasKey( 'old_revid', $item );
	}

	public function testIdsPropParameter() {
		$target = new TitleValue( 0, 'ApiQueryRecentChangesIntegrationTestPage' );
		$this->doPageEdit( $this->getLoggedInTestUser(), $target, 'Create the page' );

		$result = $this->doListRecentChangesRequest( [ 'rcprop' => 'ids', ] );
		$items = $this->getItemsFromApiResponse( $result );

		$this->assertCount( 1, $items );
		$this->assertArrayHasKey( 'pageid', $items[0] );
		$this->assertArrayHasKey( 'revid', $items[0] );
		$this->assertArrayHasKey( 'old_revid', $items[0] );
		$this->assertEquals( 'new', $items[0]['type'] );
	}

	public function testTitlePropParameter() {
		$subjectTarget = new TitleValue( 0, 'ApiQueryRecentChangesIntegrationTestPage' );
		$talkTarget = new TitleValue( 1, 'ApiQueryRecentChangesIntegrationTestPage' );
		$this->doPageEdits(
			$this->getLoggedInTestUser(),
			[
				[
					'target' => $subjectTarget,
					'summary' => 'Create the page',
				],
				[
					'target' => $talkTarget,
					'summary' => 'Create Talk page',
				],
			]
		);

		$result = $this->doListRecentChangesRequest( [ 'rcprop' => 'title', ] );

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
		$normalEditTarget = new TitleValue( 0, 'ApiQueryRecentChangesIntegrationTestPage' );
		$minorEditTarget = new TitleValue( 0, 'ApiQueryRecentChangesIntegrationTestPageM' );
		$botEditTarget = new TitleValue( 0, 'ApiQueryRecentChangesIntegrationTestPageB' );
		$this->doPageEdits(
			$this->getLoggedInTestUser(),
			[
				[
					'target' => $normalEditTarget,
					'summary' => 'Create the page',
				],
				[
					'target' => $minorEditTarget,
					'summary' => 'Create the page',
				],
				[
					'target' => $minorEditTarget,
					'summary' => 'Change content',
					'minorEdit' => true,
				],
				[
					'target' => $botEditTarget,
					'summary' => 'Create the page with a bot',
					'botEdit' => true,
				],
			]
		);

		$result = $this->doListRecentChangesRequest( [ 'rcprop' => 'flags', ] );

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
		$userEditTarget = new TitleValue( 0, 'ApiQueryRecentChangesIntegrationTestPage' );
		$anonEditTarget = new TitleValue( 0, 'ApiQueryRecentChangesIntegrationTestPageA' );
		$this->doPageEdit( $this->getLoggedInTestUser(), $userEditTarget, 'Create the page' );
		$this->doAnonPageEdit( $anonEditTarget, 'Create the page' );

		$result = $this->doListRecentChangesRequest( [ 'rcprop' => 'user', ] );

		$this->assertEquals(
			[
				[
					'type' => 'new',
					'anon' => true,
					'user' => User::newFromId( 0 )->getName(),
				],
				[
					'type' => 'new',
					'user' => $this->getLoggedInTestUser()->getName(),
				],
			],
			$this->getItemsFromApiResponse( $result )
		);
	}

	public function testUserIdPropParameter() {
		$user = $this->getLoggedInTestUser();
		$userEditTarget = new TitleValue( 0, 'ApiQueryRecentChangesIntegrationTestPage' );
		$anonEditTarget = new TitleValue( 0, 'ApiQueryRecentChangesIntegrationTestPageA' );
		$this->doPageEdit( $user, $userEditTarget, 'Create the page' );
		$this->doAnonPageEdit( $anonEditTarget, 'Create the page' );

		$result = $this->doListRecentChangesRequest( [ 'rcprop' => 'userid', ] );

		$this->assertEquals(
			[
				[
					'type' => 'new',
					'anon' => true,
					'userid' => 0,
				],
				[
					'type' => 'new',
					'userid' => $user->getId(),
				],
			],
			$this->getItemsFromApiResponse( $result )
		);
	}

	public function testCommentPropParameter() {
		$target = new TitleValue( 0, 'ApiQueryRecentChangesIntegrationTestPage' );
		$this->doPageEdit( $this->getLoggedInTestUser(), $target, 'Create the <b>page</b>' );

		$result = $this->doListRecentChangesRequest( [ 'rcprop' => 'comment', ] );

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
		$target = new TitleValue( 0, 'ApiQueryRecentChangesIntegrationTestPage' );
		$this->doPageEdit( $this->getLoggedInTestUser(), $target, 'Create the <b>page</b>' );

		$result = $this->doListRecentChangesRequest( [ 'rcprop' => 'parsedcomment', ] );

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
		$target = new TitleValue( 0, 'ApiQueryRecentChangesIntegrationTestPage' );
		$this->doPageEdit( $this->getLoggedInTestUser(), $target, 'Create the page' );

		$result = $this->doListRecentChangesRequest( [ 'rcprop' => 'timestamp', ] );
		$items = $this->getItemsFromApiResponse( $result );

		$this->assertCount( 1, $items );
		$this->assertArrayHasKey( 'timestamp', $items[0] );
		$this->assertInternalType( 'string', $items[0]['timestamp'] );
	}

	public function testSizesPropParameter() {
		$target = new TitleValue( 0, 'ApiQueryRecentChangesIntegrationTestPage' );
		$this->doPageEdit( $this->getLoggedInTestUser(), $target, 'Create the page' );

		$result = $this->doListRecentChangesRequest( [ 'rcprop' => 'sizes', ] );

		$this->assertEquals(
			[
				[
					'type' => 'new',
					'oldlen' => 0,
					'newlen' => 38,
				],
			],
			$this->getItemsFromApiResponse( $result )
		);
	}

	private function createPageAndDeleteIt( LinkTarget $target ) {
		$this->doPageEdit( $this->getLoggedInTestUser(),
			$target,
			'Create the page that will be deleted'
		);
		$this->deletePage( $target, 'Important Reason' );
	}

	public function testLoginfoPropParameter() {
		$target = new TitleValue( 0, 'ApiQueryRecentChangesIntegrationTestPage' );
		$this->createPageAndDeleteIt( $target );

		$result = $this->doListRecentChangesRequest( [ 'rcprop' => 'loginfo', ] );

		$items = $this->getItemsFromApiResponse( $result );
		$this->assertCount( 1, $items );
		$this->assertArraySubset(
			[
				'type' => 'log',
				'logtype' => 'delete',
				'logaction' => 'delete',
				'logparams' => [],
			],
			$items[0]
		);
		$this->assertArrayHasKey( 'logid', $items[0] );
	}

	public function testEmptyPropParameter() {
		$user = $this->getLoggedInTestUser();
		$target = new TitleValue( 0, 'ApiQueryRecentChangesIntegrationTestPage' );
		$this->doPageEdit( $user, $target, 'Create the page' );

		$result = $this->doListRecentChangesRequest( [ 'rcprop' => '', ] );

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
		$subjectTarget = new TitleValue( 0, 'ApiQueryRecentChangesIntegrationTestPage' );
		$talkTarget = new TitleValue( 1, 'ApiQueryRecentChangesIntegrationTestPage' );
		$this->doPageEdits(
			$this->getLoggedInTestUser(),
			[
				[
					'target' => $subjectTarget,
					'summary' => 'Create the page',
				],
				[
					'target' => $talkTarget,
					'summary' => 'Create the talk page',
				],
			]
		);

		$result = $this->doListRecentChangesRequest( [ 'rcnamespace' => '0', ] );

		$items = $this->getItemsFromApiResponse( $result );
		$this->assertCount( 1, $items );
		$this->assertArraySubset(
			[
				'ns' => 0,
				'title' => $this->getPrefixedText( $subjectTarget ),
			],
			$items[0]
		);
	}

	public function testShowAnonParams() {
		$target = new TitleValue( 0, 'ApiQueryRecentChangesIntegrationTestPage' );
		$this->doAnonPageEdit( $target, 'Create the page' );

		$resultAnon = $this->doListRecentChangesRequest( [
			'rcprop' => 'user',
			'rcshow' => WatchedItemQueryService::FILTER_ANON
		] );
		$resultNotAnon = $this->doListRecentChangesRequest( [
			'rcprop' => 'user',
			'rcshow' => WatchedItemQueryService::FILTER_NOT_ANON
		] );

		$items = $this->getItemsFromApiResponse( $resultAnon );
		$this->assertCount( 1, $items );
		$this->assertArraySubset( [ 'anon' => true ], $items[0] );
		$this->assertEmpty( $this->getItemsFromApiResponse( $resultNotAnon ) );
	}

	public function testNewAndEditTypeParameters() {
		$subjectTarget = new TitleValue( 0, 'ApiQueryRecentChangesIntegrationTestPage' );
		$talkTarget = new TitleValue( 1, 'ApiQueryRecentChangesIntegrationTestPage' );
		$this->doPageEdits(
			$this->getLoggedInTestUser(),
			[
				[
					'target' => $subjectTarget,
					'summary' => 'Create the page',
				],
				[
					'target' => $subjectTarget,
					'summary' => 'Change the content',
				],
				[
					'target' => $talkTarget,
					'summary' => 'Create Talk page',
				],
			]
		);

		$resultNew = $this->doListRecentChangesRequest( [ 'rcprop' => 'title', 'rctype' => 'new' ] );
		$resultEdit = $this->doListRecentChangesRequest( [ 'rcprop' => 'title', 'rctype' => 'edit' ] );

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
		$subjectTarget = new TitleValue( 0, 'ApiQueryRecentChangesIntegrationTestPage' );
		$talkTarget = new TitleValue( 1, 'ApiQueryRecentChangesIntegrationTestPage' );
		$this->createPageAndDeleteIt( $subjectTarget );
		$this->doPageEdit( $this->getLoggedInTestUser(), $talkTarget, 'Create Talk page' );

		$result = $this->doListRecentChangesRequest( [ 'rcprop' => 'title', 'rctype' => 'log' ] );

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
			'rc_user_text' => 'm>External User',
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
		$subjectTarget = new TitleValue( 0, 'ApiQueryRecentChangesIntegrationTestPage' );
		$talkTarget = new TitleValue( 1, 'ApiQueryRecentChangesIntegrationTestPage' );
		$this->doPageEdit( $user, $subjectTarget, 'Create the page' );
		$this->doPageEdit( $user, $talkTarget, 'Create Talk page' );

		$rc = $this->getExternalRC( $subjectTarget );
		$rc->save();

		$result = $this->doListRecentChangesRequest( [ 'rcprop' => 'title', 'rctype' => 'external' ] );

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
		$subjectTarget = new TitleValue( 0, 'ApiQueryRecentChangesIntegrationTestPage' );
		$categoryTarget = new TitleValue( NS_CATEGORY, 'ApiQueryRecentChangesIntegrationTestCategory' );
		$this->doPageEdits(
			$user,
			[
				[
					'target' => $categoryTarget,
					'summary' => 'Create the category',
				],
				[
					'target' => $subjectTarget,
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

		$result = $this->doListRecentChangesRequest( [ 'rcprop' => 'title', 'rctype' => 'categorize' ] );

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
		$target1 = new TitleValue( 0, 'ApiQueryRecentChangesIntegrationTestPage' );
		$target2 = new TitleValue( 1, 'ApiQueryRecentChangesIntegrationTestPage' );
		$target3 = new TitleValue( 0, 'ApiQueryRecentChangesIntegrationTestPage2' );
		$this->doPageEdits(
			$this->getLoggedInTestUser(),
			[
				[
					'target' => $target1,
					'summary' => 'Create the page',
				],
				[
					'target' => $target2,
					'summary' => 'Create Talk page',
				],
				[
					'target' => $target3,
					'summary' => 'Create the page',
				],
			]
		);

		$resultWithoutLimit = $this->doListRecentChangesRequest( [ 'rcprop' => 'title' ] );
		$resultWithLimit = $this->doListRecentChangesRequest( [ 'rclimit' => 2, 'rcprop' => 'title' ] );

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
		$this->assertArrayHasKey( 'rccontinue', $resultWithLimit[0]['continue'] );
	}

	public function testAllRevParam() {
		$target = new TitleValue( 0, 'ApiQueryRecentChangesIntegrationTestPage' );
		$this->doPageEdits(
			$this->getLoggedInTestUser(),
			[
				[
					'target' => $target,
					'summary' => 'Create the page',
				],
				[
					'target' => $target,
					'summary' => 'Change the content',
				],
			]
		);

		$resultAllRev = $this->doListRecentChangesRequest( [ 'rcprop' => 'title', 'rcallrev' => '', ] );
		$resultNoAllRev = $this->doListRecentChangesRequest( [ 'rcprop' => 'title' ] );

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
		$subjectTarget = new TitleValue( 0, 'ApiQueryRecentChangesIntegrationTestPage' );
		$talkTarget = new TitleValue( 1, 'ApiQueryRecentChangesIntegrationTestPage' );
		$this->doPageEdits(
			$this->getLoggedInTestUser(),
			[
				[
					'target' => $subjectTarget,
					'summary' => 'Create the page',
				],
				[
					'target' => $talkTarget,
					'summary' => 'Create Talk page',
				],
			]
		);

		$resultDirOlder = $this->doListRecentChangesRequest(
			[ 'rcdir' => 'older', 'rcprop' => 'title' ]
		);
		$resultDirNewer = $this->doListRecentChangesRequest(
			[ 'rcdir' => 'newer', 'rcprop' => 'title' ]
		);

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
		$target = new TitleValue( 0, 'ApiQueryRecentChangesIntegrationTestPage' );
		$this->doPageEdit( $this->getLoggedInTestUser(), $target, 'Create the page' );

		$resultStart = $this->doListRecentChangesRequest( [
			'rcstart' => '20010115000000',
			'rcdir' => 'newer',
			'rcprop' => 'title',
		] );
		$resultEnd = $this->doListRecentChangesRequest( [
			'rcend' => '20010115000000',
			'rcdir' => 'newer',
			'rcprop' => 'title',
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
		$target1 = new TitleValue( 0, 'ApiQueryRecentChangesIntegrationTestPage' );
		$target2 = new TitleValue( 1, 'ApiQueryRecentChangesIntegrationTestPage' );
		$target3 = new TitleValue( 0, 'ApiQueryRecentChangesIntegrationTestPage2' );
		$this->doPageEdits(
			$this->getLoggedInTestUser(),
			[
				[
					'target' => $target1,
					'summary' => 'Create the page',
				],
				[
					'target' => $target2,
					'summary' => 'Create Talk page',
				],
				[
					'target' => $target3,
					'summary' => 'Create the page',
				],
			]
		);

		$firstResult = $this->doListRecentChangesRequest( [ 'rclimit' => 2, 'rcprop' => 'title' ] );
		$this->assertArrayHasKey( 'continue', $firstResult[0] );
		$this->assertArrayHasKey( 'rccontinue', $firstResult[0]['continue'] );

		$continuationParam = $firstResult[0]['continue']['rccontinue'];

		$continuedResult = $this->doListRecentChangesRequest(
			[ 'rccontinue' => $continuationParam, 'rcprop' => 'title' ]
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

	public function testGeneratorRecentChangesPropInfo_returnsRCPages() {
		$target = new TitleValue( 0, 'ApiQueryRecentChangesIntegrationTestPage' );
		$this->doPageEdit( $this->getLoggedInTestUser(), $target, 'Create the page' );

		$result = $this->doGeneratorRecentChangesRequest( [ 'prop' => 'info' ] );

		$this->assertArrayHasKey( 'query', $result[0] );
		$this->assertArrayHasKey( 'pages', $result[0]['query'] );

		// $result[0]['query']['pages'] uses page ids as keys. Page ids don't matter here, so drop them
		$pages = array_values( $result[0]['query']['pages'] );

		$this->assertCount( 1, $pages );
		$this->assertArraySubset(
			[
				'ns' => $target->getNamespace(),
				'title' => $this->getPrefixedText( $target ),
				'new' => true,
			],
			$pages[0]
		);
	}

}
