<?php

use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Page\PageProps;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\RecentChanges\RecentChangeStore;
use MediaWiki\Title\Title;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;

/**
 * @group Database
 */
class RecentChangeStoreTest extends MediaWikiIntegrationTestCase {

	private RecentChangeStore $recentChangeStore;
	protected PageIdentity $title;
	protected PageIdentity $target;
	protected UserIdentity $user;

	private const USER_COMMENT = '<User comment about action>';

	protected function setUp(): void {
		parent::setUp();

		$this->recentChangeStore = $this->getServiceContainer()->getRecentChangeStore();

		$this->title = PageIdentityValue::localIdentity( 17, NS_MAIN, 'SomeTitle' );
		$this->target = PageIdentityValue::localIdentity( 78, NS_MAIN, 'TestTarget' );

		$user = $this->getTestUser()->getUser();
		$this->user = new UserIdentityValue( $user->getId(), $user->getName() );

		$this->overrideConfigValues( [
			MainConfigNames::CanonicalServer => 'https://example.org',
			MainConfigNames::ServerName => 'example.org',
			MainConfigNames::ScriptPath => '/w',
			MainConfigNames::Script => '/w/index.php',
			MainConfigNames::UseRCPatrol => false,
			MainConfigNames::UseNPPatrol => false,
			MainConfigNames::RCFeeds => [],
			MainConfigNames::RCEngines => [],
		] );
	}

	/**
	 * @covers \MediaWiki\RecentChanges\RecentChangeStore::newRecentChangeFromRow
	 * @covers \MediaWiki\RecentChanges\RecentChange::loadFromRow
	 * @covers \MediaWiki\RecentChanges\RecentChange::getAttributes
	 * @covers \MediaWiki\RecentChanges\RecentChange::getPerformerIdentity
	 */
	public function testNewFromRow() {
		$user = $this->getTestUser()->getUser();

		$row = (object)[
			'rc_foo' => 'AAA',
			'rc_timestamp' => '20150921134808',
			'rc_deleted' => 'bar',
			'rc_comment_text' => 'comment',
			'rc_comment_data' => null,
			'rc_user' => $user->getId(), // lookup by id
		];

		$rc = $this->recentChangeStore->newRecentChangeFromRow( $row );

		$expected = [
			'rc_foo' => 'AAA',
			'rc_timestamp' => '20150921134808',
			'rc_deleted' => 'bar',
			'rc_comment' => 'comment',
			'rc_comment_text' => 'comment',
			'rc_comment_data' => null,
			'rc_user' => $user->getId(),
			'rc_user_text' => $user->getName()
		];
		$this->assertEquals( $expected, $rc->getAttributes() );
		$this->assertTrue( $user->equals( $rc->getPerformerIdentity() ) );

		$row = (object)[
			'rc_foo' => 'AAA',
			'rc_timestamp' => '20150921134808',
			'rc_deleted' => 'bar',
			'rc_comment' => 'comment',
			'rc_user_text' => $user->getName(), // lookup by name
		];
		$rc = @$this->recentChangeStore->newRecentChangeFromRow( $row );

		$expected = [
			'rc_foo' => 'AAA',
			'rc_timestamp' => '20150921134808',
			'rc_deleted' => 'bar',
			'rc_comment' => 'comment',
			'rc_comment_text' => 'comment',
			'rc_comment_data' => null,
			'rc_user' => $user->getId(),
			'rc_user_text' => $user->getName()
		];
		$this->assertEquals( $expected, $rc->getAttributes() );
		$this->assertEquals( $expected, $rc->getAttributes() );
		$this->assertTrue( $user->equals( $rc->getPerformerIdentity() ) );
	}

	/**
	 * @covers \MediaWiki\RecentChanges\RecentChangeStore::createNewPageRecentChange
	 * @covers \MediaWiki\RecentChanges\RecentChangeStore::insertRecentChange
	 * @covers \MediaWiki\RecentChanges\RecentChangeStore::getRecentChangeById
	 * @covers \MediaWiki\RecentChanges\RecentChange::getAttributes
	 * @covers \MediaWiki\RecentChanges\RecentChange::getPerformerIdentity
	 */
	public function testCreateNewPageRecentChange() {
		$now = MWTimestamp::now();
		$rc = $this->recentChangeStore->createNewPageRecentChange(
			$now,
			$this->title,
			false,
			$this->user,
			self::USER_COMMENT,
			false
		);
		$this->recentChangeStore->insertRecentChange( $rc );

		$expected = [
			'rc_timestamp' => $now,
			'rc_deleted' => 0,
			'rc_comment_text' => self::USER_COMMENT,
			'rc_user' => $this->user->getId(),
			'rc_user_text' => $this->user->getName()
		];

		$actual = array_intersect_key( $rc->getAttributes(), $expected );

		$this->assertEquals( $expected, $actual );
		$this->assertTrue( $this->user->equals( $rc->getPerformerIdentity() ) );

		$rc = $this->recentChangeStore->getRecentChangeById( $rc->getAttribute( 'rc_id' ) );

		$actual = array_intersect_key( $rc->getAttributes(), $expected );

		$this->assertEquals( $expected, $actual );
		$this->assertTrue( $this->user->equals( $rc->getPerformerIdentity() ) );
	}

	/**
	 * @covers \MediaWiki\RecentChanges\RecentChangeStore::createEditRecentChange
	 * @covers \MediaWiki\RecentChanges\RecentChangeStore::insertRecentChange
	 * @covers \MediaWiki\RecentChanges\RecentChangeStore::getRecentChangeById
	 * @covers \MediaWiki\RecentChanges\RecentChange::getAttributes
	 * @covers \MediaWiki\RecentChanges\RecentChange::getPerformerIdentity
	 */
	public function testCreateEditRecentChange() {
		$now = MWTimestamp::now();
		$rc = $this->recentChangeStore->createEditRecentChange(
			$now,
			$this->title,
			false,
			$this->user,
			self::USER_COMMENT,
			0,
			false
		);
		$this->recentChangeStore->insertRecentChange( $rc );

		$expected = [
			'rc_timestamp' => $now,
			'rc_deleted' => 0,
			'rc_comment_text' => self::USER_COMMENT,
			'rc_user' => $this->user->getId(),
			'rc_user_text' => $this->user->getName()
		];

		$actual = array_intersect_key( $rc->getAttributes(), $expected );

		$this->assertEquals( $expected, $actual );
		$this->assertTrue( $this->user->equals( $rc->getPerformerIdentity() ) );

		$rc = $this->recentChangeStore->getRecentChangeById( $rc->getAttribute( 'rc_id' ) );

		$actual = array_intersect_key( $rc->getAttributes(), $expected );

		$this->assertEquals( $expected, $actual );
		$this->assertTrue( $this->user->equals( $rc->getPerformerIdentity() ) );
	}

	/**
	 * @covers \MediaWiki\RecentChanges\RecentChangeStore::createLogRecentChange
	 * @covers \MediaWiki\RecentChanges\RecentChangeStore::insertRecentChange
	 * @covers \MediaWiki\RecentChanges\RecentChangeStore::getRecentChangeById
	 * @covers \MediaWiki\RecentChanges\RecentChange::getAttributes
	 * @covers \MediaWiki\RecentChanges\RecentChange::getPerformerIdentity
	 */
	public function testCreateLogRecentChange() {
		$now = MWTimestamp::now();
		$logPage = PageReferenceValue::localReference( NS_SPECIAL, 'Log/test' );

		$rc = $this->recentChangeStore->createLogRecentChange(
			$now,
			$logPage,
			$this->user,
			'action comment',
			'192.168.0.2',
			'test',
			'testing',
			$this->title,
			self::USER_COMMENT,
			'a|b|c',
			7,
			'',
			42,
			false,
			true
		);
		$this->recentChangeStore->insertRecentChange( $rc );

		$expected = [
			'rc_timestamp' => $now,
			'rc_comment_text' => self::USER_COMMENT,
			'rc_user' => $this->user->getId(),
			'rc_user_text' => $this->user->getName(),
			'rc_title' => $this->title->getDBkey(),
			'rc_logid' => 7,
			'rc_log_type' => 'test',
			'rc_log_action' => 'testing',
			'rc_this_oldid' => 42,
			'rc_patrolled' => RecentChange::PRC_AUTOPATROLLED,
			'rc_bot' => 1,
		];

		$actual = array_intersect_key( $rc->getAttributes(), $expected );

		$this->assertEquals( $expected, $actual );
		$this->assertTrue( $this->user->equals( $rc->getPerformerIdentity() ) );
		$this->assertTrue( $this->title->isSamePageAs( $rc->getPage() ) );
		$this->assertTrue( $this->title->isSamePageAs( $rc->getTitle() ) );
	}

	public static function provideCategoryContent() {
		return [
			[ true ],
			[ false ],
		];
	}

	/**
	 * @dataProvider provideCategoryContent
	 * @covers \MediaWiki\RecentChanges\RecentChangeStore::createCategorizationRecentChange
	 */
	public function testHiddenCategoryChange( $isHidden ) {
		$categoryTitle = Title::makeTitle( NS_CATEGORY, 'CategoryPage' );

		$pageProps = $this->createMock( PageProps::class );
		$pageProps->expects( $this->once() )
			->method( 'getProperties' )
			->with( $categoryTitle, 'hiddencat' )
			->willReturn( $isHidden ? [ $categoryTitle->getArticleID() => '' ] : [] );

		$this->setService( 'PageProps', $pageProps );

		$rc = $this->recentChangeStore->createCategorizationRecentChange(
			'0',
			$categoryTitle,
			$this->user,
			self::USER_COMMENT,
			$this->title,
			$categoryTitle->getLatestRevID(),
			$categoryTitle->getLatestRevID(),
			false
		);
		$this->recentChangeStore->insertRecentChange( $rc );

		$this->assertEquals( $isHidden, $rc->getParam( 'hidden-cat' ) );
	}
}
