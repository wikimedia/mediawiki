<?php

use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\Title\Title;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use MediaWiki\Utils\MWTimestamp;

/**
 * @group Database
 */
class RecentChangeTest extends MediaWikiIntegrationTestCase {
	use MockAuthorityTrait;
	use MockTitleTrait;
	use TempUserTestTrait;

	protected PageIdentity $title;
	protected PageIdentity $target;
	protected UserIdentity $user;

	private const USER_COMMENT = '<User comment about action>';

	protected function setUp(): void {
		parent::setUp();

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

	public static function provideAttribs() {
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
				'rc_log_type' => 'delete',
				'rc_log_action' => 'delete',
			] + $attribs
		];

		yield 'no title' => [
			[
				'rc_namespace' => NS_MAIN,
				'rc_title' => '',
				'rc_type' => RC_LOG,
				'rc_source' => RecentChange::SRC_LOG,
				'rc_log_type' => 'delete',
				'rc_log_action' => 'delete',
			] + $attribs
		];
	}

	/**
	 * @covers \MediaWiki\RecentChanges\RecentChange::save
	 * @covers \MediaWiki\RecentChanges\RecentChange::newFromId
	 * @covers \MediaWiki\RecentChanges\RecentChange::getTitle
	 * @covers \MediaWiki\RecentChanges\RecentChange::getPerformerIdentity
	 * @dataProvider provideAttribs
	 */
	public function testDatabaseRoundTrip( $attribs ) {
		$rc_user = $attribs['rc_user'] ?? 0;
		if ( !$rc_user ) {
			$this->disableAutoCreateTempUser();
		}
		$rc = new RecentChange;
		$rc->setAttribs( $attribs );
		$rc->setExtra( [
			'pageStatus' => 'changed'
		] );
		$rc->save();
		$id = $rc->getAttribute( 'rc_id' );

		$rc = RecentChange::newFromId( $id );

		$actualAttribs = array_intersect_key( $rc->getAttributes(), $attribs );
		$this->assertArrayEquals( $attribs, $actualAttribs, false, true );

		$user = new UserIdentityValue( $rc_user, $attribs['rc_user_text'] );
		$this->assertTrue( $user->equals( $rc->getPerformerIdentity() ) );

		if ( empty( $attribs['rc_title'] ) ) {
			$this->assertNull( $rc->getPage() );
		} else {
			$title = Title::makeTitle( $attribs['rc_namespace'], $attribs['rc_title'] );
			$this->assertTrue( $title->isSamePageAs( $rc->getTitle() ) );
			$this->assertTrue( $title->isSamePageAs( $rc->getPage() ) );
		}
	}

	public static function provideParseParams() {
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
	 * @covers \MediaWiki\RecentChanges\RecentChange::parseParams
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
	 * @covers \MediaWiki\RecentChanges\RecentChange::getNotifyUrl
	 */
	public function testGetNotifyUrlForEdit() {
		$rc = new RecentChange;
		$rc->mAttribs = [
			'rc_id' => 60,
			'rc_timestamp' => '20110401090000',
			'rc_namespace' => NS_MAIN,
			'rc_title' => 'Example',
			'rc_type' => RC_EDIT,
			'rc_source' => RecentChange::SRC_EDIT,
			'rc_cur_id' => 42,
			'rc_this_oldid' => 50,
			'rc_last_oldid' => 30,
			'rc_patrolled' => 0,
		];
		$this->assertSame(
			'https://example.org/w/index.php?diff=50&oldid=30',
			$rc->getNotifyUrl(), 'Notify url'
		);

		$this->overrideConfigValue( MainConfigNames::UseRCPatrol, true );
		$this->assertSame(
			'https://example.org/w/index.php?diff=50&oldid=30&rcid=60',
			$rc->getNotifyUrl(), 'Notify url (RC Patrol)'
		);
	}

	/**
	 * @covers \MediaWiki\RecentChanges\RecentChange::getNotifyUrl
	 */
	public function testGetNotifyUrlForCreate() {
		$rc = new RecentChange;
		$rc->mAttribs = [
			'rc_id' => 60,
			'rc_timestamp' => '20110401090000',
			'rc_namespace' => NS_MAIN,
			'rc_title' => 'Example',
			'rc_type' => RC_NEW,
			'rc_source' => RecentChange::SRC_NEW,
			'rc_cur_id' => 42,
			'rc_this_oldid' => 50,
			'rc_last_oldid' => 0,
			'rc_patrolled' => 0,
		];
		$this->assertSame(
			'https://example.org/w/index.php?oldid=50',
			$rc->getNotifyUrl(), 'Notify url'
		);

		$this->overrideConfigValue( MainConfigNames::UseNPPatrol, true );
		$this->assertSame(
			'https://example.org/w/index.php?oldid=50&rcid=60',
			$rc->getNotifyUrl(), 'Notify url (NP Patrol)'
		);
	}

	/**
	 * @covers \MediaWiki\RecentChanges\RecentChange::getNotifyUrl
	 */
	public function testGetNotifyUrlForLog() {
		$rc = new RecentChange;
		$rc->mAttribs = [
			'rc_id' => 60,
			'rc_timestamp' => '20110401090000',
			'rc_namespace' => NS_MAIN,
			'rc_title' => 'Example',
			'rc_type' => RC_LOG,
			'rc_source' => RecentChange::SRC_LOG,
			'rc_cur_id' => 42,
			'rc_this_oldid' => 50,
			'rc_last_oldid' => 0,
			'rc_patrolled' => 2,
			'rc_logid' => 160,
			'rc_log_type' => 'delete',
			'rc_log_action' => 'delete',
		];
		$this->assertSame( null, $rc->getNotifyUrl(), 'Notify url' );
	}

	/**
	 * @return array
	 */
	public static function provideIsInRCLifespan() {
		return [
			[ 6000, -3000, 0, true ],
			[ 3000, -6000, 0, false ],
			[ 6000, -3000, 6000, true ],
			[ 3000, -6000, 6000, true ],
		];
	}

	/**
	 * @covers \MediaWiki\RecentChanges\RecentChange::isInRCLifespan
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

	public static function provideRCTypes() {
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
	 * @covers \MediaWiki\RecentChanges\RecentChange::parseFromRCType
	 */
	public function testParseFromRCType( $rcType, $type ) {
		$this->assertEquals( $type, RecentChange::parseFromRCType( $rcType ) );
	}

	/**
	 * @dataProvider provideRCTypes
	 * @covers \MediaWiki\RecentChanges\RecentChange::parseToRCType
	 */
	public function testParseToRCType( $rcType, $type ) {
		$this->assertEquals( $rcType, RecentChange::parseToRCType( $type ) );
	}

	private function getDummyEditRecentChange(): RecentChange {
		return RecentChange::notifyEdit(
			MWTimestamp::now(),
			$this->title,
			false,
			$this->user,
			self::USER_COMMENT,
			0,
			MWTimestamp::now(),
			false
		);
	}

	/**
	 * @covers \MediaWiki\RecentChanges\RecentChange::markPatrolled
	 */
	public function testMarkPatrolledPermissions() {
		$rc = $this->getDummyEditRecentChange();
		$performer = $this->mockRegisteredAuthority( static function (
			string $permission,
			PageIdentity $page,
			PermissionStatus $status
		) {
			if ( $permission === 'patrol' ) {
				$status->fatal( 'missing-patrol' );
				return false;
			}
			return true;
		} );
		$status = $rc->markPatrolled(
			$performer
		);
		$this->assertStatusError( 'missing-patrol', $status );
	}

	/**
	 * @covers \MediaWiki\RecentChanges\RecentChange::markPatrolled
	 */
	public function testMarkPatrolledPermissions_Hook() {
		$rc = $this->getDummyEditRecentChange();
		$this->setTemporaryHook( 'MarkPatrolled', static function () {
			return false;
		} );
		$status = $rc->markPatrolled( $this->mockRegisteredUltimateAuthority() );
		$this->assertStatusError( 'hookaborted', $status );
	}

	/**
	 * @covers \MediaWiki\RecentChanges\RecentChange::markPatrolled
	 */
	public function testMarkPatrolledPermissions_Self() {
		$rc = $this->getDummyEditRecentChange();
		$status = $rc->markPatrolled(
			$this->mockUserAuthorityWithoutPermissions( $this->user, [ 'autopatrol' ] )
		);
		$this->assertStatusError( 'markedaspatrollederror-noautopatrol', $status );
	}

	/**
	 * @covers \MediaWiki\RecentChanges\RecentChange::markPatrolled
	 */
	public function testMarkPatrolledPermissions_NoRcPatrol() {
		$rc = $this->getDummyEditRecentChange();
		$status = $rc->markPatrolled( $this->mockRegisteredUltimateAuthority() );
		$this->assertStatusError( 'rcpatroldisabled', $status );
	}

	/**
	 * @covers \MediaWiki\RecentChanges\RecentChange::markPatrolled
	 */
	public function testMarkPatrolled() {
		$this->overrideConfigValue( MainConfigNames::UseRCPatrol, true );
		$rc = $this->getDummyEditRecentChange();
		$status = $rc->markPatrolled(
			$this->mockUserAuthorityWithPermissions( $this->user, [ 'patrol', 'autopatrol' ] )
		);
		$this->assertStatusGood( $status );

		$reloadedRC = RecentChange::newFromId( $rc->getAttribute( 'rc_id' ) );
		$this->assertSame( '1', $reloadedRC->getAttribute( 'rc_patrolled' ) );
	}
}
