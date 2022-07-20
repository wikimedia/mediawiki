<?php

use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Page\PageReference;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\User\UserIdentityValue;

/**
 * @group Database
 */
class RecentChangeTest extends MediaWikiIntegrationTestCase {
	use MockAuthorityTrait;
	use MockTitleTrait;

	protected $title;
	protected $target;
	protected $user;
	protected $user_comment;

	protected function setUp(): void {
		parent::setUp();

		$this->title = new PageIdentityValue( 17, NS_MAIN, 'SomeTitle', PageIdentity::LOCAL );
		$this->target = new PageIdentityValue( 78, NS_MAIN, 'TestTarget', PageIdentity::LOCAL );

		$user = $this->getTestUser()->getUser();
		$this->user = new UserIdentityValue( $user->getId(), $user->getName() );

		$this->user_comment = '<User comment about action>';
	}

	public function provideAttribs() {
		$attribs = [
			'rc_timestamp' => wfTimestamp( TS_MW ),
			'rc_namespace' => NS_USER,
			'rc_title' => 'Tony',
			'rc_type' => RC_EDIT,
			'rc_source' => RecentChange::SRC_EDIT,
			'rc_minor' => 0,
			'rc_cur_id' => 77,
			'rc_user' => 858173476,
			'rc_user_text' => 'Tony',
			'rc_comment' => '',
			'rc_comment_text' => '',
			'rc_comment_data' => null,
			'rc_this_oldid' => 70,
			'rc_last_oldid' => 71,
			'rc_bot' => 0,
			'rc_ip' => '',
			'rc_patrolled' => 0,
			'rc_new' => 0,
			'rc_old_len' => 80,
			'rc_new_len' => 88,
			'rc_deleted' => 0,
			'rc_logid' => 0,
			'rc_log_type' => null,
			'rc_log_action' => '',
			'rc_params' => '',
		];

		yield 'external user' => [
			[
				'rc_type' => RC_EXTERNAL,
				'rc_source' => 'foo',
				'rc_user' => 0,
				'rc_user_text' => 'm>External User',
			] + $attribs
		];

		yield 'anon user' => [
			[
				'rc_type' => RC_EXTERNAL,
				'rc_source' => 'foo',
				'rc_user' => 0,
				'rc_user_text' => '192.168.0.1',
			] + $attribs
		];

		yield 'special title' => [
			[
				'rc_namespace' => NS_SPECIAL,
				'rc_title' => 'Log',
				'rc_type' => RC_LOG,
				'rc_source' => RecentChange::SRC_LOG,
			] + $attribs
		];

		yield 'no title' => [
			[
				'rc_namespace' => NS_MAIN,
				'rc_title' => '',
				'rc_type' => RC_LOG,
				'rc_source' => RecentChange::SRC_LOG,
			] + $attribs
		];
	}

	/**
	 * @covers RecentChange::save
	 * @covers RecentChange::newFromId
	 * @covers RecentChange::getTitle
	 * @covers RecentChange::getPerformerIdentity
	 * @dataProvider provideAttribs
	 */
	public function testDatabaseRoundTrip( $attribs ) {
		$this->hideDeprecated( 'RecentChange::getPerformer' );
		$rc = new RecentChange;
		$rc->mAttribs = $attribs;
		$rc->mExtra = [
			'pageStatus' => 'changed'
		];
		$rc->save();
		$id = $rc->getAttribute( 'rc_id' );

		$rc = RecentChange::newFromId( $id );

		$actualAttribs = array_intersect_key( $rc->mAttribs, $attribs );
		$this->assertArrayEquals( $attribs, $actualAttribs, false, true );

		$user = new UserIdentityValue( $attribs['rc_user'] ?? 0, $attribs['rc_user_text'] );
		$this->assertTrue( $user->equals( $rc->getPerformerIdentity() ) );
		$this->assertTrue( $user->equals( $rc->getPerformer() ) );

		if ( empty( $attribs['rc_title'] ) ) {
			$this->assertNull( $rc->getPage() );
		} else {
			$title = Title::makeTitle( $attribs['rc_namespace'], $attribs['rc_title'] );
			$this->assertTrue( $title->isSamePageAs( $rc->getTitle() ) );
			$this->assertTrue( $title->isSamePageAs( $rc->getPage() ) );
		}
	}

	/**
	 * @covers RecentChange::newFromRow
	 * @covers RecentChange::loadFromRow
	 * @covers RecentChange::getAttributes
	 * @covers RecentChange::getPerformerIdentity
	 * @covers RecentChange::getPerformer
	 */
	public function testNewFromRow() {
		$this->hideDeprecated( 'RecentChange::getPerformer' );
		$user = $this->getTestUser()->getUser();

		$row = (object)[
			'rc_foo' => 'AAA',
			'rc_timestamp' => '20150921134808',
			'rc_deleted' => 'bar',
			'rc_comment_text' => 'comment',
			'rc_comment_data' => null,
			'rc_user' => $user->getId(), // lookup by id
		];

		$rc = RecentChange::newFromRow( $row );

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
		$this->assertTrue( $user->equals( $rc->getPerformer() ) );

		$row = (object)[
			'rc_foo' => 'AAA',
			'rc_timestamp' => '20150921134808',
			'rc_deleted' => 'bar',
			'rc_comment' => 'comment',
			'rc_user_text' => $user->getName(), // lookup by name
		];
		$rc = @RecentChange::newFromRow( $row );

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
		$this->assertTrue( $user->equals( $rc->getPerformer() ) );
	}

	/**
	 * @covers RecentChange::notifyNew
	 * @covers RecentChange::newFromId
	 * @covers RecentChange::getAttributes
	 * @covers RecentChange::getPerformerIdentity
	 * @covers RecentChange::getPerformer
	 */
	public function testNotifyNew() {
		$this->hideDeprecated( 'RecentChange::getPerformer' );
		$now = MWTimestamp::now();
		$rc = RecentChange::notifyNew(
			$now,
			$this->title,
			false,
			$this->user,
			$this->user_comment,
			false
		);

		$expected = [
			'rc_timestamp' => $now,
			'rc_deleted' => 0,
			'rc_comment_text' => $this->user_comment,
			'rc_user' => $this->user->getId(),
			'rc_user_text' => $this->user->getName()
		];

		$actual = array_intersect_key( $rc->getAttributes(), $expected );

		$this->assertEquals( $expected, $actual );
		$this->assertTrue( $this->user->equals( $rc->getPerformerIdentity() ) );
		$this->assertTrue( $this->user->equals( $rc->getPerformer() ) );

		$rc = RecentChange::newFromId( $rc->getAttribute( 'rc_id' ) );

		$actual = array_intersect_key( $rc->getAttributes(), $expected );

		$this->assertEquals( $expected, $actual );
		$this->assertTrue( $this->user->equals( $rc->getPerformerIdentity() ) );
		$this->assertTrue( $this->user->equals( $rc->getPerformer() ) );
	}

	/**
	 * @covers RecentChange::notifyNew
	 * @covers RecentChange::newFromId
	 * @covers RecentChange::getAttributes
	 * @covers RecentChange::getPerformerIdentity
	 * @covers RecentChange::getPerformer
	 */
	public function testNotifyEdit() {
		$this->hideDeprecated( 'RecentChange::getPerformer' );
		$now = MWTimestamp::now();
		$rc = RecentChange::notifyEdit(
			$now,
			$this->title,
			false,
			$this->user,
			$this->user_comment,
			0,
			$now,
			false
		);

		$expected = [
			'rc_timestamp' => $now,
			'rc_deleted' => 0,
			'rc_comment_text' => $this->user_comment,
			'rc_user' => $this->user->getId(),
			'rc_user_text' => $this->user->getName()
		];

		$actual = array_intersect_key( $rc->getAttributes(), $expected );

		$this->assertEquals( $expected, $actual );
		$this->assertTrue( $this->user->equals( $rc->getPerformerIdentity() ) );
		$this->assertTrue( $this->user->equals( $rc->getPerformer() ) );

		$rc = RecentChange::newFromId( $rc->getAttribute( 'rc_id' ) );

		$actual = array_intersect_key( $rc->getAttributes(), $expected );

		$this->assertEquals( $expected, $actual );
		$this->assertTrue( $this->user->equals( $rc->getPerformerIdentity() ) );
		$this->assertTrue( $this->user->equals( $rc->getPerformer() ) );
	}

	/**
	 * @covers RecentChange::notifyNew
	 * @covers RecentChange::newFromId
	 * @covers RecentChange::getAttributes
	 * @covers RecentChange::getPerformerIdentity
	 * @covers RecentChange::getPerformer
	 */
	public function testNewLogEntry() {
		$this->hideDeprecated( 'RecentChange::getPerformer' );
		$now = MWTimestamp::now();
		$logPage = new PageReferenceValue( NS_SPECIAL, 'Log/test', PageReference::LOCAL );

		$rc = RecentChange::newLogEntry(
			$now,
			$logPage,
			$this->user,
			'action comment',
			'192.168.0.2',
			'test',
			'testing',
			$this->title,
			$this->user_comment,
			'a|b|c',
			7
		);

		$expected = [
			'rc_timestamp' => $now,
			'rc_comment_text' => $this->user_comment,
			'rc_user' => $this->user->getId(),
			'rc_user_text' => $this->user->getName(),
			'rc_title' => $this->title->getDBkey(),
			'rc_logid' => 7,
			'rc_log_type' => 'test',
			'rc_log_action' => 'testing',
		];

		$actual = array_intersect_key( $rc->getAttributes(), $expected );

		$this->assertEquals( $expected, $actual );
		$this->assertTrue( $this->user->equals( $rc->getPerformerIdentity() ) );
		$this->assertTrue( $this->user->equals( $rc->getPerformer() ) );
		$this->assertTrue( $this->title->isSamePageAs( $rc->getPage() ) );
		$this->assertTrue( $this->title->isSamePageAs( $rc->getTitle() ) );
	}

	public function provideParseParams() {
		// $expected, $raw
		yield 'extracting an array' => [
			[
				'root' => [
					'A' => 1,
					'B' => 'two'
				]
			],
			'a:1:{s:4:"root";a:2:{s:1:"A";i:1;s:1:"B";s:3:"two";}}'
		];

		yield 'null' => [ null, null ];
		yield 'false' => [ null, serialize( false ) ];
		yield 'non-array' => [ null, 'not-an-array' ];
	}

	/**
	 * @covers RecentChange::parseParams
	 * @dataProvider provideParseParams
	 * @param array $expectedParseParams
	 * @param string|null $rawRcParams
	 */
	public function testParseParams( $expectedParseParams, $rawRcParams ) {
		$rc = new RecentChange;
		$rc->setAttribs( [ 'rc_params' => $rawRcParams ] );

		$actualParseParams = $rc->parseParams();

		$this->assertEquals( $expectedParseParams, $actualParseParams );
	}

	/**
	 * @return array
	 */
	public function provideIsInRCLifespan() {
		return [
			[ 6000, -3000, 0, true ],
			[ 3000, -6000, 0, false ],
			[ 6000, -3000, 6000, true ],
			[ 3000, -6000, 6000, true ],
		];
	}

	/**
	 * @covers RecentChange::isInRCLifespan
	 * @dataProvider provideIsInRCLifespan
	 */
	public function testIsInRCLifespan( $maxAge, $offset, $tolerance, $expected ) {
		$this->overrideConfigValue( MainConfigNames::RCMaxAge, $maxAge );
		// Calculate this here instead of the data provider because the provider
		// is expanded early on and the full test suite may take longer than 100 minutes
		// when coverage is enabled.
		$timestamp = time() + $offset;
		$this->assertEquals( $expected, RecentChange::isInRCLifespan( $timestamp, $tolerance ) );
	}

	public function provideRCTypes() {
		return [
			[ RC_EDIT, 'edit' ],
			[ RC_NEW, 'new' ],
			[ RC_LOG, 'log' ],
			[ RC_EXTERNAL, 'external' ],
			[ RC_CATEGORIZE, 'categorize' ],
		];
	}

	/**
	 * @dataProvider provideRCTypes
	 * @covers RecentChange::parseFromRCType
	 */
	public function testParseFromRCType( $rcType, $type ) {
		$this->assertEquals( $type, RecentChange::parseFromRCType( $rcType ) );
	}

	/**
	 * @dataProvider provideRCTypes
	 * @covers RecentChange::parseToRCType
	 */
	public function testParseToRCType( $rcType, $type ) {
		$this->assertEquals( $rcType, RecentChange::parseToRCType( $type ) );
	}

	public function provideCategoryContent() {
		return [
			[ true ],
			[ false ],
		];
	}

	/**
	 * @dataProvider provideCategoryContent
	 * @covers RecentChange::newForCategorization
	 */
	public function testHiddenCategoryChange( $isHidden ) {
		$categoryTitle = Title::makeTitle( NS_CATEGORY, 'CategoryPage' );

		$pageProps = $this->createMock( PageProps::class );
		$pageProps->expects( $this->once() )
			->method( 'getProperties' )
			->with( $categoryTitle, 'hiddencat' )
			->willReturn( $isHidden ? [ $categoryTitle->getArticleID() => '' ] : [] );

		$this->setService( 'PageProps', $pageProps );

		$rc = RecentChange::newForCategorization(
			'0',
			$categoryTitle,
			$this->user,
			$this->user_comment,
			$this->title,
			$categoryTitle->getLatestRevID(),
			$categoryTitle->getLatestRevID(),
			'0',
			false
		);

		$this->assertEquals( $isHidden, $rc->getParam( 'hidden-cat' ) );
	}

	private function getDummyEditRecentChange(): RecentChange {
		return RecentChange::notifyEdit(
			MWTimestamp::now(),
			$this->title,
			false,
			$this->user,
			$this->user_comment,
			0,
			MWTimestamp::now(),
			false
		);
	}

	public function provideDoMarkPatrolledPermissions() {
		yield 'auto, no autopatrol' => [
			'lackingPermissions' => [ 'autopatrol' ],
			'auto' => true,
			'expectedError' => 'missing-autopatrol'
		];
		yield 'no patrol' => [
			'lackingPermissions' => [ 'patrol' ],
			'auto' => false,
			'expectedError' => 'missing-patrol'
		];
	}

	/**
	 * @dataProvider provideDoMarkPatrolledPermissions
	 * @covers RecentChange::doMarkPatrolled
	 */
	public function testDoMarkPatrolledPermissions(
		array $lackingPermissions,
		bool $auto,
		string $expectError
	) {
		$rc = $this->getDummyEditRecentChange();
		$performer = $this->mockRegisteredAuthority( static function (
			string $permission,
			PageIdentity $page,
			PermissionStatus $status
		) use ( $lackingPermissions ) {
			if ( in_array( $permission, $lackingPermissions ) ) {
				$status->fatal( "missing-$permission" );
				return false;
			}
			return true;
		} );
		$errors = $rc->doMarkPatrolled(
			$performer,
			$auto
		);
		$this->assertContains( [ $expectError ], $errors );
	}

	/**
	 * @covers RecentChange::doMarkPatrolled
	 */
	public function testDoMarkPatrolledPermissions_Hook() {
		$rc = $this->getDummyEditRecentChange();
		$this->setTemporaryHook( 'MarkPatrolled', static function () {
			return false;
		} );
		$errors = $rc->doMarkPatrolled( $this->mockRegisteredUltimateAuthority() );
		$this->assertContains( [ 'hookaborted' ], $errors );
	}

	/**
	 * @covers RecentChange::doMarkPatrolled
	 */
	public function testDoMarkPatrolledPermissions_Self() {
		$rc = $this->getDummyEditRecentChange();
		$errors = $rc->doMarkPatrolled(
			$this->mockUserAuthorityWithoutPermissions( $this->user, [ 'autopatrol' ] )
		);
		$this->assertContains( [ 'markedaspatrollederror-noautopatrol' ], $errors );
	}

	/**
	 * @covers RecentChange::doMarkPatrolled
	 */
	public function testDoMarkPatrolledPermissions_NoRcPatrol() {
		$this->overrideConfigValue( MainConfigNames::UseRCPatrol, false );
		$rc = $this->getDummyEditRecentChange();
		$errors = $rc->doMarkPatrolled( $this->mockRegisteredUltimateAuthority() );
		$this->assertContains( [ 'rcpatroldisabled' ], $errors );
	}

	/**
	 * @covers RecentChange::doMarkPatrolled
	 */
	public function testDoMarkPatrolled() {
		$rc = $this->getDummyEditRecentChange();
		$errors = $rc->doMarkPatrolled(
			$this->mockUserAuthorityWithPermissions( $this->user, [ 'patrol', 'autopatrol' ] )
		);
		$this->assertEmpty( $errors );

		$reloadedRC = RecentChange::newFromId( $rc->getAttribute( 'rc_id' ) );
		$this->assertSame( '1', $reloadedRC->getAttribute( 'rc_patrolled' ) );
	}
}
