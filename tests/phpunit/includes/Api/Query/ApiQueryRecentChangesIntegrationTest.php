<?php

namespace MediaWiki\Tests\Api\Query;

use MediaWiki\CommentStore\CommentStoreComment;
use MediaWiki\Content\WikitextContent;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Permissions\Authority;
use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Tests\Api\ApiTestCase;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\Title\TitleValue;
use MediaWiki\User\User;
use MediaWiki\Watchlist\WatchedItemQueryService;

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers \MediaWiki\Api\ApiQueryRecentChanges
 */
class ApiQueryRecentChangesIntegrationTest extends ApiTestCase {
	use MockAuthorityTrait;
	use TempUserTestTrait;

	private function getLoggedInTestUser() {
		return $this->getTestUser()->getUser();
	}

	private function doPageEdit( Authority $performer, $target, $summary ) {
		static $i = 0;

		$this->editPage(
			$target,
			__CLASS__ . $i++,
			$summary,
			NS_MAIN,
			$performer
		);
	}

	private function doMinorPageEdit( User $user, LinkTarget $target, $summary ) {
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromLinkTarget( $target );
		$page->doUserEditContent(
			$page->getContentHandler()->unserializeContent( __CLASS__ ),
			$user,
			$summary,
			EDIT_MINOR
		);
	}

	private function doBotPageEdit( User $user, LinkTarget $target, $summary ) {
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromLinkTarget( $target );
		$page->doUserEditContent(
			$page->getContentHandler()->unserializeContent( __CLASS__ ),
			$user,
			$summary,
			EDIT_FORCE_BOT
		);
	}

	private function doAnonPageEdit( LinkTarget $target, $summary ) {
		$this->disableAutoCreateTempUser();
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromLinkTarget( $target );
		$page->doUserEditContent(
			$page->getContentHandler()->unserializeContent( __CLASS__ ),
			$this->getServiceContainer()->getUserFactory()->newAnonymous(),
			$summary
		);
	}

	private function doTempPageEdit( LinkTarget $target, $summary ) {
		// Set up temp user config
		$this->enableAutoCreateTempUser();
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromLinkTarget( $target );
		$page->doUserEditContent(
			$page->getContentHandler()->unserializeContent( __CLASS__ ),
			$this->mockTempUltimateAuthority(),
			$summary
		);
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

	private function getItemsFromRecentChangesResult( array $result ) {
		return $result[0]['query']['recentchanges'];
	}

	public function testListRecentChanges_returnsRCInfo() {
		$target = new TitleValue( NS_MAIN, 'ApiQueryRecentChangesIntegrationTestPage' );
		$this->doPageEdit( $this->getLoggedInTestUser(), $target, 'Create the page' );

		$result = $this->doListRecentChangesRequest();
		$items = $this->getItemsFromRecentChangesResult( $result );

		// Default contains at least props for 'title', 'timestamp', and 'ids'.
		$this->assertCount( 1, $items );
		$item = $items[0];
		foreach ( [
			'pageid',
			'revid',
			'old_revid',
			'rcid',
			'timestamp',
		] as $key ) {
			// Assert key but ignore value
			$this->assertArrayHasKey( $key, $item );
			unset( $item[ $key ] );
		}

		// The rest must equal exactly, with no additional keys (e.g. 'minor' or 'bot').
		$this->assertEquals(
			[
				'type' => 'new',
				'ns' => NS_MAIN,
				'title' => 'ApiQueryRecentChangesIntegrationTestPage',
			],
			$item
		);
	}

	public function testIdsPropParameter() {
		$target = new TitleValue( NS_MAIN, 'ApiQueryRecentChangesIntegrationTestPage' );
		$this->doPageEdit( $this->getLoggedInTestUser(), $target, 'Create the page' );
		$result = $this->doListRecentChangesRequest( [ 'rcprop' => 'ids' ] );
		$items = $this->getItemsFromRecentChangesResult( $result );

		$this->assertCount( 1, $items );
		$item = $items[0];
		foreach ( [
			'pageid',
			'revid',
			'old_revid',
			'rcid',
		] as $key ) {
			// Assert key but ignore value
			$this->assertArrayHasKey( $key, $item );
			unset( $item[ $key ] );
		}

		$this->assertEquals(
			[
				'type' => 'new',
			],
			$item
		);
	}

	public function testTitlePropParameter() {
		$this->doPageEdits(
			$this->getLoggedInTestUser(),
			[
				[
					'target' => new TitleValue( NS_MAIN, 'Thing' ),
					'summary' => 'Create the page',
				],
				[
					'target' => new TitleValue( NS_TALK, 'Thing' ),
					'summary' => 'Create Talk page',
				],
			]
		);

		$result = $this->doListRecentChangesRequest( [ 'rcprop' => 'title' ] );

		$this->assertEquals(
			[
				[
					'type' => 'new',
					'ns' => NS_TALK,
					'title' => 'Talk:Thing',
				],
				[
					'type' => 'new',
					'ns' => NS_MAIN,
					'title' => 'Thing',
				],
			],
			$this->getItemsFromRecentChangesResult( $result )
		);
	}

	public function testFlagsPropParameter() {
		$this->doPageEdits(
			$this->getLoggedInTestUser(),
			[
				[
					'summary' => 'Create the page',
					'target' => new TitleValue( NS_MAIN, 'Regularpage' ),
				],
				[
					'summary' => 'Create the page for minor change',
					'target' => new TitleValue( NS_MAIN, 'Minorpage' ),
				],
				[
					'summary' => 'Make minor content',
					'target' => new TitleValue( NS_MAIN, 'Minorpage' ),
					'minorEdit' => true,
				],
				[
					'summary' => 'Create the page as a bot',
					'target' => new TitleValue( NS_MAIN, 'Botpage' ),
					'botEdit' => true,
				],
			]
		);

		$result = $this->doListRecentChangesRequest( [ 'rcprop' => 'flags' ] );

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
			$this->getItemsFromRecentChangesResult( $result )
		);
	}

	public function testUserPropParameter() {
		$userEditTarget = new TitleValue( NS_MAIN, 'Foo' );
		$anonEditTarget = new TitleValue( NS_MAIN, 'Bar' );
		$tempEditTarget = new TitleValue( NS_MAIN, 'Baz' );
		$this->doPageEdit( $this->getLoggedInTestUser(), $userEditTarget, 'Create the page' );
		$this->doAnonPageEdit( $anonEditTarget, 'Create the page' );

		// Test that querying for anonymous edits works even if temporary accounts are disabled
		$this->disableAutoCreateTempUser();
		$result = $this->doListRecentChangesRequest( [
			'rcprop' => 'user',
			'rcshow' => WatchedItemQueryService::FILTER_NOT_ANON,
		] );
		$this->assertEquals(
			[
				[
					'type' => 'new',
					'user' => $this->getLoggedInTestUser()->getName(),
				],
			],
			$this->getItemsFromRecentChangesResult( $result )
		);

		// Test that temporary accounts are treated as anonymous
		$this->doTempPageEdit( $tempEditTarget, 'Create the page' );
		$result = $this->doListRecentChangesRequest( [ 'rcprop' => 'user' ] );
		$this->assertEquals(
			[
				[
					'type' => 'new',
					'temp' => true,
					'user' => '~2024-1',
				],
				[
					'type' => 'new',
					'anon' => true,
					'user' => '127.0.0.1',
				],
				[
					'type' => 'new',
					'user' => $this->getLoggedInTestUser()->getName(),
				],
			],
			$this->getItemsFromRecentChangesResult( $result )
		);
	}

	public function testUserIdPropParameter() {
		$user = $this->getLoggedInTestUser();
		$userEditTarget = new TitleValue( NS_MAIN, 'Foo' );
		$anonEditTarget = new TitleValue( NS_MAIN, 'Bar' );
		$this->doPageEdit( $user, $userEditTarget, 'Create the page' );
		$this->doAnonPageEdit( $anonEditTarget, 'Create the page' );

		$result = $this->doListRecentChangesRequest( [ 'rcprop' => 'userid' ] );

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
			$this->getItemsFromRecentChangesResult( $result )
		);
	}

	public function testCommentPropParameter() {
		$target = new TitleValue( NS_MAIN, 'Thing' );
		$this->doPageEdit( $this->getLoggedInTestUser(), $target, 'Create the <b>page</b>' );

		$result = $this->doListRecentChangesRequest( [ 'rcprop' => 'comment' ] );

		$this->assertEquals(
			[
				[
					'type' => 'new',
					'comment' => 'Create the <b>page</b>',
				],
			],
			$this->getItemsFromRecentChangesResult( $result )
		);
	}

	public function testParsedCommentPropParameter() {
		$target = new TitleValue( NS_MAIN, 'Thing' );
		$this->doPageEdit( $this->getLoggedInTestUser(), $target, 'Create the <b>page</b>' );

		$result = $this->doListRecentChangesRequest( [ 'rcprop' => 'parsedcomment' ] );

		$this->assertEquals(
			[
				[
					'type' => 'new',
					'parsedcomment' => 'Create the &lt;b&gt;page&lt;/b&gt;',
				],
			],
			$this->getItemsFromRecentChangesResult( $result )
		);
	}

	public function testTimestampPropParameter() {
		$target = new TitleValue( NS_MAIN, 'Thing' );
		$this->doPageEdit( $this->getLoggedInTestUser(), $target, 'Create the page' );

		$result = $this->doListRecentChangesRequest( [ 'rcprop' => 'timestamp' ] );
		$items = $this->getItemsFromRecentChangesResult( $result );

		$this->assertCount( 1, $items );
		$this->assertIsString( $items[0]['timestamp'] );
	}

	public function testSizesPropParameter() {
		$target = new TitleValue( NS_MAIN, 'Thing' );
		$this->doPageEdit( $this->getLoggedInTestUser(), $target, 'Create the page' );

		$result = $this->doListRecentChangesRequest( [ 'rcprop' => 'sizes' ] );

		$this->assertEquals(
			[
				[
					'type' => 'new',
					'oldlen' => 0,
					// strlen( __CLASS__ ) - 2 = 64
					'newlen' => 64,
				],
			],
			$this->getItemsFromRecentChangesResult( $result )
		);
	}

	public function testSha1PropParameter() {
		$target = new TitleValue( NS_MAIN, 'Thing' );
		$this->doPageEdit( $this->getLoggedInTestUser(), $target, 'Create the page' );

		$result = $this->doListRecentChangesRequest( [ 'rcprop' => 'sha1' ] );
		$items = $this->getItemsFromRecentChangesResult( $result );

		$this->assertCount( 1, $items );
		$item = $items[0];
		$this->assertArrayHasKey( 'sha1', $item );
		$this->assertIsString( $item['sha1'] );
		$this->assertEquals( 'c83c5a32da4b735b037d43eeee7e379741773ce1', $item['sha1'] );
	}

	public function testSha1PropParameterWithMultipleSlots() {
		// Ensure an auxiliary slot exists for the test
		$slotRoleRegistry = $this->getServiceContainer()->getSlotRoleRegistry();
		if ( !$slotRoleRegistry->isDefinedRole( 'aux' ) ) {
			$slotRoleRegistry->defineRoleWithModel( 'aux', CONTENT_MODEL_WIKITEXT );
			$this->setService( 'SlotRoleRegistry', $slotRoleRegistry );
		}

		$target = new TitleValue( NS_MAIN, 'MultiSlotPage' );
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromLinkTarget( $target );
		$updater = $page->newPageUpdater( $this->getLoggedInTestUser() );
		$updater->setContent( SlotRecord::MAIN, new WikitextContent( 'Main slot content' ) );
		$updater->setContent( 'aux', new WikitextContent( 'Aux slot content' ) );
		$updater->saveRevision( CommentStoreComment::newUnsavedComment( 'Create multi-slot revision' ) );

		$result = $this->doListRecentChangesRequest( [ 'rcprop' => 'sha1', 'rctitle' => 'MultiSlotPage' ] );
		$items = $this->getItemsFromRecentChangesResult( $result );

		$this->assertCount( 1, $items );
		$item = $items[0];
		$this->assertArrayHasKey( 'sha1', $item );
		$this->assertIsString( $item['sha1'] );
		$this->assertEquals( '2a80176c16d08b348d45687ef559028a1235780a', $item['sha1'] );
	}

	public function testSha1PropParameterAbsentForLogs() {
		$target = new TitleValue( NS_MAIN, 'Thing' );
		$this->createPageAndDeleteIt( $target );

		$result = $this->doListRecentChangesRequest( [ 'rcprop' => 'sha1', 'rctype' => 'log' ] );
		$items = $this->getItemsFromRecentChangesResult( $result );

		$this->assertCount( 1, $items );
		$item = $items[0];
		$this->assertEquals( 'log', $item['type'] );
		$this->assertArrayNotHasKey( 'sha1', $item );
	}

	private function createPageAndDeleteIt( LinkTarget $target ) {
		$wikiPage = $this->getServiceContainer()->getWikiPageFactory()->newFromLinkTarget( $target );
		$this->doPageEdit( $this->getLoggedInTestUser(),
			$wikiPage,
			'Create the page that will be deleted'
		);
		$this->deletePage( $wikiPage, 'Important Reason' );
	}

	public function testLoginfoPropParameter() {
		$target = new TitleValue( NS_MAIN, 'Thing' );
		$this->createPageAndDeleteIt( $target );

		$result = $this->doListRecentChangesRequest( [ 'rcprop' => 'loginfo' ] );
		$items = $this->getItemsFromRecentChangesResult( $result );

		$this->assertCount( 1, $items );
		foreach ( [
			'logid',
		] as $key ) {
			// Assert key but ignore value
			$this->assertArrayHasKey( $key, $items[0] );
			unset( $items[0][ $key ] );
		}
		$this->assertEquals(
			[
				'type' => 'log',
				'logtype' => 'delete',
				'logaction' => 'delete',
				'logparams' => [],
			],
			$items[0]
		);
	}

	public function testEmptyPropParameter() {
		$user = $this->getLoggedInTestUser();
		$target = new TitleValue( NS_MAIN, 'Thing' );
		$this->doPageEdit( $user, $target, 'Create the page' );

		$result = $this->doListRecentChangesRequest( [ 'rcprop' => '' ] );

		$this->assertEquals(
			[
				[
					'type' => 'new',
				]
			],
			$this->getItemsFromRecentChangesResult( $result )
		);
	}

	public function testNamespaceParam() {
		$subjectTarget = new TitleValue( NS_MAIN, 'Foo' );
		$talkTarget = new TitleValue( NS_TALK, 'Foo' );
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

		$result = $this->doListRecentChangesRequest( [ 'rcnamespace' => '0', 'rcprop' => 'title' ] );
		$items = $this->getItemsFromRecentChangesResult( $result );

		$this->assertCount( 1, $items );
		$this->assertEquals(
			[
				'type' => 'new',
				'ns' => NS_MAIN,
				'title' => 'Foo',
			],
			$items[0]
		);
	}

	public function testShowAnonParams() {
		$target = new TitleValue( NS_MAIN, 'Thing' );
		$this->doAnonPageEdit( $target, 'Create the page' );

		$tempEditTarget = new TitleValue( NS_MAIN, 'Baz' );
		$this->doTempPageEdit( $tempEditTarget, 'Create the page' );

		$resultAnon = $this->doListRecentChangesRequest( [
			'rcprop' => 'user',
			'rcshow' => WatchedItemQueryService::FILTER_ANON
		] );
		$resultNotAnon = $this->doListRecentChangesRequest( [
			'rcprop' => 'user',
			'rcshow' => WatchedItemQueryService::FILTER_NOT_ANON
		] );

		$items = $this->getItemsFromRecentChangesResult( $resultAnon );
		$this->assertCount( 2, $items );
		$this->assertEquals(
			[
				[
					'type' => 'new',
					'temp' => true,
					'user' => '~2024-1',
				],
				[
					'type' => 'new',
					'anon' => true,
					'user' => '127.0.0.1',
				],
			],
			$items
		);
		$this->assertSame( [], $this->getItemsFromRecentChangesResult( $resultNotAnon ) );
	}

	public function testNewAndEditTypeParameters() {
		$subjectTarget = new TitleValue( NS_MAIN, 'Foo' );
		$talkTarget = new TitleValue( NS_TALK, 'Foo' );
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
					'ns' => NS_TALK,
					'title' => 'Talk:Foo',
				],
				[
					'type' => 'new',
					'ns' => NS_MAIN,
					'title' => 'Foo',
				],
			],
			$this->getItemsFromRecentChangesResult( $resultNew )
		);
		$this->assertEquals(
			[
				[
					'type' => 'edit',
					'ns' => NS_MAIN,
					'title' => 'Foo',
				],
			],
			$this->getItemsFromRecentChangesResult( $resultEdit )
		);
	}

	public function testLogTypeParameters() {
		$subjectTarget = new TitleValue( NS_MAIN, 'Foo' );
		$talkTarget = new TitleValue( NS_TALK, 'Foo' );
		$this->createPageAndDeleteIt( $subjectTarget );
		$this->doPageEdit( $this->getLoggedInTestUser(), $talkTarget, 'Create Talk page' );

		$result = $this->doListRecentChangesRequest( [ 'rcprop' => 'title', 'rctype' => 'log' ] );

		$this->assertEquals(
			[
				[
					'type' => 'log',
					'ns' => NS_MAIN,
					'title' => 'Foo',
				],
			],
			$this->getItemsFromRecentChangesResult( $result )
		);
	}

	private function getExternalRC( LinkTarget $target ) {
		$title = $this->getServiceContainer()->getTitleFactory()->newFromLinkTarget( $target );

		$rc = new RecentChange;
		$rc->mAttribs = [
			'rc_timestamp' => wfTimestamp( TS_MW ),
			'rc_namespace' => $title->getNamespace(),
			'rc_title' => $title->getDBkey(),
			'rc_type' => RC_EXTERNAL,
			'rc_source' => 'external',
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
		$this->getServiceContainer()->getRecentChangeStore()
			->addSourceForTest( 'external', [] );
		$user = $this->getLoggedInTestUser();
		$subjectTarget = new TitleValue( NS_MAIN, 'Foo' );
		$talkTarget = new TitleValue( NS_TALK, 'Foo' );
		$this->doPageEdit( $user, $subjectTarget, 'Create the page' );
		$this->doPageEdit( $user, $talkTarget, 'Create Talk page' );
		$rc = $this->getExternalRC( $subjectTarget );
		$rc->save();

		$result = $this->doListRecentChangesRequest( [ 'rcprop' => 'title', 'rctype' => 'external' ] );

		$this->assertEquals(
			[
				[
					'type' => 'external',
					'ns' => NS_MAIN,
					'title' => 'Foo',
				],
			],
			$this->getItemsFromRecentChangesResult( $result )
		);
	}

	public function testCategorizeTypeParameter() {
		$user = $this->getLoggedInTestUser();
		$subjectTarget = new TitleValue( NS_MAIN, 'Foo' );
		$categoryTarget = new TitleValue( NS_CATEGORY, 'Bar' );
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
		$titleFactory = $this->getServiceContainer()->getTitleFactory();
		$title = $titleFactory->newFromLinkTarget( $subjectTarget );
		$revision = $this->getServiceContainer()
			->getRevisionLookup()
			->getRevisionByTitle( $title );

		$recentChangeStore = $this->getServiceContainer()->getRecentChangeStore();
		$comment = $revision->getComment();
		$rc = $recentChangeStore->createCategorizationRecentChange(
			$revision->getTimestamp(),
			$titleFactory->newFromLinkTarget( $categoryTarget ),
			$user,
			$comment ? $comment->text : '',
			$title,
			0,
			$revision->getId(),
			false
		);
		$recentChangeStore->insertRecentChange( $rc );

		$result = $this->doListRecentChangesRequest( [ 'rcprop' => 'title', 'rctype' => 'categorize' ] );

		$this->assertEquals(
			[
				[
					'type' => 'categorize',
					'ns' => NS_CATEGORY,
					'title' => 'Category:Bar',
				],
			],
			$this->getItemsFromRecentChangesResult( $result )
		);
	}

	public function testLimitParam() {
		$this->doPageEdits(
			$this->getLoggedInTestUser(),
			[
				[
					'target' => new TitleValue( NS_MAIN, 'Foo' ),
					'summary' => 'Create the page',
				],
				[
					'target' => new TitleValue( NS_TALK, 'Foo' ),
					'summary' => 'Create Talk page',
				],
				[
					'target' => new TitleValue( NS_MAIN, 'Bar' ),
					'summary' => 'Create another page',
				],
			]
		);

		$resultWithoutLimit = $this->doListRecentChangesRequest( [ 'rcprop' => 'title' ] );
		$resultWithLimit = $this->doListRecentChangesRequest( [ 'rclimit' => 2, 'rcprop' => 'title' ] );

		$this->assertEquals(
			[
				[
					'type' => 'new',
					'ns' => NS_MAIN,
					'title' => 'Bar'
				],
				[
					'type' => 'new',
					'ns' => NS_TALK,
					'title' => 'Talk:Foo'
				],
				[
					'type' => 'new',
					'ns' => NS_MAIN,
					'title' => 'Foo'
				],
			],
			$this->getItemsFromRecentChangesResult( $resultWithoutLimit )
		);
		$this->assertEquals(
			[
				[
					'type' => 'new',
					'ns' => NS_MAIN,
					'title' => 'Bar'
				],
				[
					'type' => 'new',
					'ns' => NS_TALK,
					'title' => 'Talk:Foo'
				],
			],
			$this->getItemsFromRecentChangesResult( $resultWithLimit )
		);
		$this->assertArrayHasKey( 'rccontinue', $resultWithLimit[0]['continue'] );
	}

	public function testAllRevParam() {
		$target = new TitleValue( NS_MAIN, 'Thing' );
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

		$resultAllRev = $this->doListRecentChangesRequest( [ 'rcprop' => 'title', 'rcallrev' => '' ] );
		$resultNoAllRev = $this->doListRecentChangesRequest( [ 'rcprop' => 'title' ] );

		$this->assertEquals(
			[
				[
					'type' => 'edit',
					'ns' => NS_MAIN,
					'title' => 'Thing',
				],
				[
					'type' => 'new',
					'ns' => NS_MAIN,
					'title' => 'Thing',
				],
			],
			$this->getItemsFromRecentChangesResult( $resultNoAllRev )
		);
		$this->assertEquals(
			[
				[
					'type' => 'edit',
					'ns' => NS_MAIN,
					'title' => 'Thing',
				],
				[
					'type' => 'new',
					'ns' => NS_MAIN,
					'title' => 'Thing',
				],
			],
			$this->getItemsFromRecentChangesResult( $resultAllRev )
		);
	}

	public function testDirParams() {
		$subjectTarget = new TitleValue( NS_MAIN, 'Foo' );
		$talkTarget = new TitleValue( NS_TALK, 'Foo' );
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
					'ns' => NS_TALK,
					'title' => 'Talk:Foo'
				],
				[
					'type' => 'new',
					'ns' => NS_MAIN,
					'title' => 'Foo'
				],
			],
			$this->getItemsFromRecentChangesResult( $resultDirOlder )
		);
		$this->assertEquals(
			[
				[
					'type' => 'new',
					'ns' => NS_MAIN,
					'title' => 'Foo'
				],
				[
					'type' => 'new',
					'ns' => NS_TALK,
					'title' => 'Talk:Foo'
				],
			],
			$this->getItemsFromRecentChangesResult( $resultDirNewer )
		);
	}

	public function testTitleParams() {
		$this->doPageEdits(
			$this->getLoggedInTestUser(),
			[
				[
					'target' => new TitleValue( NS_MAIN, 'Foo' ),
					'summary' => 'Create the page',
				],
				[
					'target' => new TitleValue( NS_TALK, 'Bar' ),
					'summary' => 'Create the page',
				],
				[
					'target' => new TitleValue( NS_MAIN, 'Quux' ),
					'summary' => 'Create the page',
				],
			]
		);

		$result1 = $this->doListRecentChangesRequest(
			[
				'rctitle' => 'Foo',
				'rcprop' => 'title'
			]
		);
		$result2 = $this->doListRecentChangesRequest(
			[
				'rctitle' => 'Talk:Bar',
				'rcprop' => 'title'
			]
		);

		$this->assertEquals(
			[
				[
					'type' => 'new',
					'ns' => NS_MAIN,
					'title' => 'Foo'
				],
			],
			$this->getItemsFromRecentChangesResult( $result1 )
		);
		$this->assertEquals(
			[
				[
					'type' => 'new',
					'ns' => NS_TALK,
					'title' => 'Talk:Bar'
				],
			],
			$this->getItemsFromRecentChangesResult( $result2 )
		);
	}

	public function testStartEndParams() {
		$target = new TitleValue( NS_MAIN, 'Thing' );
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
					'ns' => NS_MAIN,
					'title' => 'Thing',
				]
			],
			$this->getItemsFromRecentChangesResult( $resultStart )
		);
		$this->assertSame( [], $this->getItemsFromRecentChangesResult( $resultEnd ) );
	}

	public function testContinueParam() {
		$this->doPageEdits(
			$this->getLoggedInTestUser(),
			[
				[
					'target' => new TitleValue( NS_MAIN, 'Foo' ),
					'summary' => 'Create the page',
				],
				[
					'target' => new TitleValue( NS_TALK, 'Foo' ),
					'summary' => 'Create Talk page',
				],
				[
					'target' => new TitleValue( NS_MAIN, 'Bar' ),
					'summary' => 'Create the page',
				],
			]
		);

		$firstResult = $this->doListRecentChangesRequest( [ 'rclimit' => 2, 'rcprop' => 'title' ] );

		$continuationParam = $firstResult[0]['continue']['rccontinue'];

		$continuedResult = $this->doListRecentChangesRequest(
			[ 'rccontinue' => $continuationParam, 'rcprop' => 'title' ]
		);

		$this->assertEquals(
			[
				[
					'type' => 'new',
					'ns' => NS_MAIN,
					'title' => 'Bar',
				],
				[
					'type' => 'new',
					'ns' => NS_TALK,
					'title' => 'Talk:Foo',
				],
			],
			$this->getItemsFromRecentChangesResult( $firstResult )
		);
		$this->assertEquals(
			[
				[
					'type' => 'new',
					'ns' => NS_MAIN,
					'title' => 'Foo',
				]
			],
			$this->getItemsFromRecentChangesResult( $continuedResult )
		);
	}

	public function testGeneratorRecentChangesPropInfo_returnsRCPages() {
		$target = new TitleValue( NS_MAIN, 'Thing' );
		$this->doPageEdit( $this->getLoggedInTestUser(), $target, 'Create the page' );

		$result = $this->doGeneratorRecentChangesRequest( [ 'prop' => 'info' ] );

		// $result[0]['query']['pages'] uses page ids as keys. Page ids don't matter here, so drop them
		$pages = array_values( $result[0]['query']['pages'] );
		$this->assertCount( 1, $pages );

		$page = $pages[0];
		foreach ( [
			'pageid',
			'touched',
			'lastrevid',
			'length',
		] as $key ) {
			// Assert key but ignore value
			$this->assertArrayHasKey( $key, $page );
			unset( $page[ $key ] );
		}

		$this->assertEquals(
			[
				'ns' => NS_MAIN,
				'title' => 'Thing',
				'new' => true,
				'contentmodel' => 'wikitext',
				'pagelanguage' => 'en',
				'pagelanguagehtmlcode' => 'en',
				'pagelanguagedir' => 'ltr',
			],
			$page
		);
	}

}
