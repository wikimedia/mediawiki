<?php

use MediaWiki\Page\PageIdentity;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\User\UserIdentityValue;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @group Database
 */
class RecentChangeTest extends MediaWikiIntegrationTestCase {
	use MockAuthorityTrait;

	protected $title;
	protected $target;
	protected $user;
	protected $user_comment;
	protected $context;

	protected function setUp() : void {
		parent::setUp();

		$this->title = Title::newFromText( 'SomeTitle' );
		$this->target = Title::newFromText( 'TestTarget' );

		$user = $this->getTestUser()->getUser();
		$this->user = new UserIdentityValue( $user->getId(), $user->getName() );

		$this->user_comment = '<User comment about action>';
		$this->context = RequestContext::newExtraneousContext( $this->title );
	}

	public function provideAttribs() {
		yield [
			[
				'rc_timestamp' => wfTimestamp( TS_MW ),
				'rc_namespace' => NS_USER,
				'rc_title' => 'Acme',
				'rc_type' => RC_EXTERNAL,
				'rc_source' => 'foo',
				'rc_minor' => 0,
				'rc_cur_id' => 77,
				'rc_user' => 0,
				'rc_user_text' => 'm>External User',
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
			]
		];

		yield [
			[
				'rc_timestamp' => wfTimestamp( TS_MW ),
				'rc_namespace' => NS_USER,
				'rc_title' => 'Acme',
				'rc_type' => RC_EXTERNAL,
				'rc_source' => 'foo',
				'rc_minor' => 0,
				'rc_cur_id' => 77,
				'rc_user' => 0,
				'rc_user_text' => '192.168.0.1',
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
			]
		];
	}

	/**
	 * @covers RecentChange::save
	 * @covers RecentChange::newFromId
	 * @covers RecentChange::getTitle
	 * @covers RecentChange::getPerformerIdentity
	 * @dataProvider provideAttribs
	 */
	public function testAttribs( $attribs ) {
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

		$title = Title::makeTitle( $attribs['rc_namespace'], $attribs['rc_title'] );
		$this->assertTrue( $title->isSamePageAs( $rc->getTitle() ) );
	}

	/**
	 * @covers RecentChange::newFromRow
	 * @covers RecentChange::loadFromRow
	 * @covers RecentChange::getAttributes
	 * @covers RecentChange::getPerformerIdentity
	 * @covers RecentChange::getPerformer
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

		Wikimedia\suppressWarnings();
		$rc = RecentChange::newFromRow( $row );
		Wikimedia\restoreWarnings();

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
		$this->setMwGlobals( 'wgRCMaxAge', $maxAge );
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

	/**
	 * @return MockObject|PageProps
	 */
	private function getMockPageProps() {
		return $this->getMockBuilder( PageProps::class )
			->disableOriginalConstructor()
			->getMock();
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
		$categoryTitle = Title::newFromText( 'CategoryPage', NS_CATEGORY );

		$pageProps = $this->getMockPageProps();
		$pageProps->expects( $this->once() )
			->method( 'getProperties' )
			->with( $categoryTitle, 'hiddencat' )
			->will( $this->returnValue( $isHidden ? [ $categoryTitle->getArticleID() => '' ] : [] ) );

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
		$performer = $this->mockRegisteredAuthority( function (
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
		$this->setTemporaryHook( 'MarkPatrolled', function () {
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
		$this->setMwGlobals( [
			'wgUseRCPatrol' => false
		] );
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
