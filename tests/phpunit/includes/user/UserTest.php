<?php

use MediaWiki\Block\CompositeBlock;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\Restriction\NamespaceRestriction;
use MediaWiki\Block\Restriction\PageRestriction;
use MediaWiki\Block\SystemBlock;
use MediaWiki\MainConfigNames;
use MediaWiki\Permissions\RateLimiter;
use MediaWiki\Permissions\RateLimitSubject;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use MediaWiki\User\UserIdentityValue;
use Wikimedia\Assert\PreconditionException;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Database
 */
class UserTest extends MediaWikiIntegrationTestCase {
	use DummyServicesTrait;

	/** Constant for self::testIsBlockedFrom */
	private const USER_TALK_PAGE = '<user talk page>';

	/**
	 * @var User
	 */
	protected $user;

	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValues( [
			MainConfigNames::GroupPermissions => [],
			MainConfigNames::RevokePermissions => [],
			MainConfigNames::UseRCPatrol => true,
			MainConfigNames::WatchlistExpiry => true,
			MainConfigNames::AutoConfirmAge => 0,
			MainConfigNames::AutoConfirmCount => 0,
		] );

		$this->setUpPermissionGlobals();

		$this->user = $this->getTestUser( 'unittesters' )->getUser();
		$this->tablesUsed[] = 'revision';
	}

	private function setUpPermissionGlobals() {
		$this->setGroupPermissions( [
			// Data for regular $wgGroupPermissions test
			'unittesters' => [
				'test' => true,
				'runtest' => true,
				'writetest' => false,
				'nukeworld' => false,
				'autoconfirmed' => false,
			],
			'testwriters' => [
				'test' => true,
				'writetest' => true,
				'modifytest' => true,
				'autoconfirmed' => true,
			],
			// For the options and watchlist tests
			'*' => [
				'editmyoptions' => true,
				'editmywatchlist' => true,
				'viewmywatchlist' => true,
			],
			// For patrol tests
			'patroller' => [
				'patrol' => true,
			],
			// For account creation when blocked test
			'accountcreator' => [
				'createaccount' => true,
				'ipblock-exempt' => true
			],
			// For bot and ratelimit tests
			'bot' => [
				'bot' => true,
				'noratelimit' => true,
			]
		] );

		$this->overrideConfigValue(
			MainConfigNames::RevokePermissions,
			// Data for regular $wgRevokePermissions test
			[ 'formertesters' => [ 'runtest' => true ] ]
		);
	}

	private function setSessionUser( User $user, WebRequest $request ) {
		RequestContext::getMain()->setUser( $user );
		RequestContext::getMain()->setRequest( $request );
		TestingAccessWrapper::newFromObject( $user )->mRequest = $request;
		$request->getSession()->setUser( $user );
	}

	/**
	 * @covers User::getGroupPermissions
	 */
	public function testGroupPermissions() {
		$rights = User::getGroupPermissions( [ 'unittesters' ] );
		$this->assertContains( 'runtest', $rights );
		$this->assertNotContains( 'writetest', $rights );
		$this->assertNotContains( 'modifytest', $rights );
		$this->assertNotContains( 'nukeworld', $rights );

		$rights = User::getGroupPermissions( [ 'unittesters', 'testwriters' ] );
		$this->assertContains( 'runtest', $rights );
		$this->assertContains( 'writetest', $rights );
		$this->assertContains( 'modifytest', $rights );
		$this->assertNotContains( 'nukeworld', $rights );
	}

	/**
	 * @covers User::getGroupPermissions
	 */
	public function testRevokePermissions() {
		$rights = User::getGroupPermissions( [ 'unittesters', 'formertesters' ] );
		$this->assertNotContains( 'runtest', $rights );
		$this->assertNotContains( 'writetest', $rights );
		$this->assertNotContains( 'modifytest', $rights );
		$this->assertNotContains( 'nukeworld', $rights );
	}

	/**
	 * @dataProvider provideGetGroupsWithPermission
	 * @covers User::getGroupsWithPermission
	 */
	public function testGetGroupsWithPermission( array $expected, $right ) {
		$result = User::getGroupsWithPermission( $right );
		$this->assertArrayEquals( $expected, $result );
	}

	public static function provideGetGroupsWithPermission() {
		return [
			[
				[ 'unittesters', 'testwriters' ],
				'test'
			],
			[
				[ 'unittesters' ],
				'runtest'
			],
			[
				[ 'testwriters' ],
				'writetest'
			],
			[
				[ 'testwriters' ],
				'modifytest'
			],
		];
	}

	/**
	 * @covers User::isAllowedAny
	 * @covers User::isAllowedAll
	 * @covers User::isAllowed
	 * @covers User::isNewbie
	 */
	public function testIsAllowed() {
		$this->assertFalse(
			$this->user->isAllowed( 'writetest' ),
			'Basic isAllowed works with a group not granted a right'
		);
		$this->assertTrue(
			$this->user->isAllowedAny( 'test', 'writetest' ),
			'A user with only one of the rights can pass isAllowedAll'
		);
		$this->assertTrue(
			$this->user->isAllowedAll( 'test', 'runtest' ),
			'A user with multiple rights can pass isAllowedAll'
		);
		$this->assertFalse(
			$this->user->isAllowedAll( 'test', 'runtest', 'writetest' ),
			'A user needs all rights specified to pass isAllowedAll'
		);
		$this->assertTrue(
			$this->user->isNewbie(),
			'Unit testers are not autoconfirmed yet'
		);

		$user = $this->getTestUser( 'testwriters' )->getUser();
		$this->assertTrue(
			$user->isAllowed( 'test' ),
			'Basic isAllowed works with a group granted a right'
		);
		$this->assertTrue(
			$user->isAllowed( 'writetest' ),
			'Testwriters pass isAllowed with `writetest`'
		);
		$this->assertFalse(
			$user->isNewbie(),
			'Test writers are autoconfirmed'
		);
	}

	/**
	 * @covers User::useRCPatrol
	 * @covers User::useNPPatrol
	 * @covers User::useFilePatrol
	 */
	public function testPatrolling() {
		$user = $this->getTestUser( 'patroller' )->getUser();

		$this->assertTrue( $user->useRCPatrol() );
		$this->assertTrue( $user->useNPPatrol() );
		$this->assertTrue( $user->useFilePatrol() );

		$this->assertFalse( $this->user->useRCPatrol() );
		$this->assertFalse( $this->user->useNPPatrol() );
		$this->assertFalse( $this->user->useFilePatrol() );
	}

	/**
	 * @covers User::getGroups
	 * @covers User::getGroupMemberships
	 * @covers User::isBot
	 */
	public function testBot() {
		$user = $this->getTestUser( 'bot' )->getUser();

		$this->assertSame( $user->getGroups(), [ 'bot' ] );
		$this->assertArrayHasKey( 'bot', $user->getGroupMemberships() );
		$this->assertTrue( $user->isBot() );

		$this->assertArrayNotHasKey( 'bot', $this->user->getGroupMemberships() );
		$this->assertFalse( $this->user->isBot() );
	}

	/**
	 * Test User::editCount
	 * @group medium
	 * @covers User::getEditCount
	 */
	public function testGetEditCount() {
		$user = $this->getMutableTestUser()->getUser();

		// let the user have a few (3) edits
		$page = WikiPage::factory( Title::makeTitle( NS_HELP, 'UserTest_EditCount' ) );
		for ( $i = 0; $i < 3; $i++ ) {
			$page->doUserEditContent(
				ContentHandler::makeContent( (string)$i, $page->getTitle() ),
				$user,
				'test'
			);
		}

		$this->assertSame(
			3,
			$user->getEditCount(),
			'After three edits, the user edit count should be 3'
		);

		// increase the edit count
		$user->incEditCount();
		$user->clearInstanceCache();

		$this->assertSame(
			4,
			$user->getEditCount(),
			'After increasing the edit count manually, the user edit count should be 4'
		);
	}

	/**
	 * Test User::editCount
	 * @group medium
	 * @covers User::getEditCount
	 * @covers User::incEditCount
	 */
	public function testGetEditCountForAnons() {
		$user = User::newFromName( 'Anonymous' );

		$this->assertNull(
			$user->getEditCount(),
			'Edit count starts null for anonymous users.'
		);

		$this->assertNull(
			$user->incEditCount(),
			'Edit count cannot be increased for anonymous users'
		);

		$this->assertNull(
			$user->getEditCount(),
			'Edit count remains null for anonymous users despite calls to increase it.'
		);
	}

	/**
	 * Test User::editCount
	 * @group medium
	 * @covers User::incEditCount
	 */
	public function testIncEditCount() {
		$user = $this->getMutableTestUser()->getUser();
		$user->incEditCount();

		$reloadedUser = User::newFromId( $user->getId() );
		$reloadedUser->incEditCount();

		$this->assertSame(
			2,
			$reloadedUser->getEditCount(),
			'Increasing the edit count after a fresh load leaves the object up to date.'
		);
	}

	/**
	 * Test password validity checks. There are 3 checks in core,
	 *	- ensure the password meets the minimal length
	 *	- ensure the password is not the same as the username
	 *	- ensure the username/password combo isn't forbidden
	 * @covers User::checkPasswordValidity()
	 * @covers User::isValidPassword()
	 */
	public function testCheckPasswordValidity() {
		$this->overrideConfigValue(
			MainConfigNames::PasswordPolicy,
			[
				'policies' => [
					'sysop' => [
						'MinimalPasswordLength' => 8,
						'MinimumPasswordLengthToLogin' => 1,
						'PasswordCannotBeSubstringInUsername' => 1,
					],
					'default' => [
						'MinimalPasswordLength' => 6,
						'PasswordCannotBeSubstringInUsername' => true,
						'PasswordCannotMatchDefaults' => true,
						'MaximalPasswordLength' => 40,
					],
				],
				'checks' => [
					'MinimalPasswordLength' => 'PasswordPolicyChecks::checkMinimalPasswordLength',
					'MinimumPasswordLengthToLogin' => 'PasswordPolicyChecks::checkMinimumPasswordLengthToLogin',
					'PasswordCannotBeSubstringInUsername' =>
						'PasswordPolicyChecks::checkPasswordCannotBeSubstringInUsername',
					'PasswordCannotMatchDefaults' => 'PasswordPolicyChecks::checkPasswordCannotMatchDefaults',
					'MaximalPasswordLength' => 'PasswordPolicyChecks::checkMaximalPasswordLength',
				],
			]
		);

		$this->assertTrue( $this->user->isValidPassword( 'Password1234' ) );

		// Minimum length
		$this->assertFalse( $this->user->isValidPassword( 'a' ) );
		$this->assertFalse( $this->user->checkPasswordValidity( 'a' )->isGood() );
		$this->assertTrue( $this->user->checkPasswordValidity( 'a' )->isOK() );

		// Maximum length
		$longPass = str_repeat( 'a', 41 );
		$this->assertFalse( $this->user->isValidPassword( $longPass ) );
		$this->assertFalse( $this->user->checkPasswordValidity( $longPass )->isGood() );
		$this->assertFalse( $this->user->checkPasswordValidity( $longPass )->isOK() );

		// Matches username
		$this->assertFalse( $this->user->checkPasswordValidity( $this->user->getName() )->isGood() );
		$this->assertTrue( $this->user->checkPasswordValidity( $this->user->getName() )->isOK() );

		$this->setTemporaryHook( 'isValidPassword', static function ( $password, &$result, $user ) {
			$result = 'isValidPassword returned false';
			return false;
		} );
		$status = $this->user->checkPasswordValidity( 'Password1234' );
		$this->assertStatusOK( $status );
		$this->assertStatusNotGood( $status );
		$this->assertSame( 'isValidPassword returned false', $status->getErrors()[0]['message'] );

		$this->removeTemporaryHook( 'isValidPassword' );

		$this->setTemporaryHook( 'isValidPassword', static function ( $password, &$result, $user ) {
			$result = true;
			return true;
		} );
		$status = $this->user->checkPasswordValidity( 'Password1234' );
		$this->assertStatusGood( $status );

		$this->removeTemporaryHook( 'isValidPassword' );

		$this->setTemporaryHook( 'isValidPassword', static function ( $password, &$result, $user ) {
			$result = 'isValidPassword returned true';
			return true;
		} );
		$status = $this->user->checkPasswordValidity( 'Password1234' );
		$this->assertStatusOK( $status );
		$this->assertStatusNotGood( $status );
		$this->assertSame( 'isValidPassword returned true', $status->getErrors()[0]['message'] );

		$this->removeTemporaryHook( 'isValidPassword' );

		// On the forbidden list
		$user = User::newFromName( 'Useruser' );
		$this->assertFalse( $user->checkPasswordValidity( 'Passpass' )->isGood() );
	}

	/**
	 * @covers User::equals
	 */
	public function testEquals() {
		$first = $this->getMutableTestUser()->getUser();
		$second = User::newFromName( $first->getName() );

		$this->assertTrue( $first->equals( $first ) );
		$this->assertTrue( $first->equals( $second ) );
		$this->assertTrue( $second->equals( $first ) );

		$third = $this->getMutableTestUser()->getUser();
		$fourth = $this->getMutableTestUser()->getUser();

		$this->assertFalse( $third->equals( $fourth ) );
		$this->assertFalse( $fourth->equals( $third ) );

		// Test users loaded from db with id
		$user = $this->getMutableTestUser()->getUser();
		$fifth = User::newFromId( $user->getId() );
		$sixth = User::newFromName( $user->getName() );
		$this->assertTrue( $fifth->equals( $sixth ) );
	}

	/**
	 * @covers User::getId
	 * @covers User::setId
	 */
	public function testUserId() {
		$this->assertGreaterThan( 0, $this->user->getId() );

		$user = User::newFromName( 'UserWithNoId' );
		$this->assertSame( 0, $user->getId() );

		$user->setId( 7 );
		$this->assertSame(
			7,
			$user->getId(),
			'Manually setting a user id via ::setId is reflected in ::getId'
		);

		$user = new User;
		$user->setName( '1.2.3.4' );
		$this->assertSame(
			0,
			$user->getId(),
			'IPs have an id of 0'
		);
	}

	/**
	 * @covers User::isRegistered
	 * @covers User::isAnon
	 * @covers User::logOut
	 */
	public function testIsRegistered() {
		$user = $this->getMutableTestUser()->getUser();
		$this->assertTrue( $user->isRegistered() );
		$this->assertFalse( $user->isAnon() );

		$this->setTemporaryHook( 'UserLogout', static function ( &$user ) {
			return false;
		} );
		$user->logout();
		$this->assertTrue( $user->isRegistered() );

		$this->removeTemporaryHook( 'UserLogout' );
		$user->logout();
		$this->assertFalse( $user->isRegistered() );

		// Non-existent users are perceived as anonymous
		$user = User::newFromName( 'UTNonexistent' );
		$this->assertFalse( $user->isRegistered() );
		$this->assertTrue( $user->isAnon() );

		$user = new User;
		$this->assertFalse( $user->isRegistered() );
		$this->assertTrue( $user->isAnon() );
	}

	/**
	 * @covers User::setRealName
	 * @covers User::getRealName
	 */
	public function testRealName() {
		$user = $this->getMutableTestUser()->getUser();
		$realName = 'John Doe';

		$user->setRealName( $realName );
		$this->assertSame(
			$realName,
			$user->getRealName(),
			'Real name retrieved from cache'
		);

		$id = $user->getId();
		$user->saveSettings();

		$otherUser = User::newFromId( $id );
		$this->assertSame(
			$realName,
			$otherUser->getRealName(),
			'Real name retrieved from database'
		);
	}

	/**
	 * @covers User::checkAndSetTouched
	 * @covers User::getDBTouched()
	 */
	public function testCheckAndSetTouched() {
		$user = $this->getMutableTestUser()->getUser();
		$user = TestingAccessWrapper::newFromObject( $user );
		$this->assertTrue( $user->isRegistered() );

		$touched = $user->getDBTouched();
		$this->assertTrue(
			$user->checkAndSetTouched(), "checkAndSetTouched() succedeed" );
		$this->assertGreaterThan(
			$touched, $user->getDBTouched(), "user_touched increased with casOnTouched()" );

		$touched = $user->getDBTouched();
		$this->assertTrue(
			$user->checkAndSetTouched(), "checkAndSetTouched() succedeed #2" );
		$this->assertGreaterThan(
			$touched, $user->getDBTouched(), "user_touched increased with casOnTouched() #2" );
	}

	/**
	 * @covers User::validateCache
	 * @covers User::getTouched
	 */
	public function testValidateCache() {
		$user = $this->getTestUser()->getUser();

		$initialTouchMW = $user->getTouched();
		$initialTouchUnix = ( new MWTimestamp( $initialTouchMW ) )->getTimestamp();

		$earlierUnix = $initialTouchUnix - 1000;
		$earlierMW = ( new MWTimestamp( $earlierUnix ) )->getTimestamp( TS_MW );
		$this->assertFalse(
			$user->validateCache( $earlierMW ),
			'Caches from before the value of getTouched() are not valid'
		);

		$laterUnix = $initialTouchUnix + 1000;
		$laterMW = ( new MWTimestamp( $laterUnix ) )->getTimestamp( TS_MW );
		$this->assertTrue(
			$user->validateCache( $laterMW ),
			'Caches from after the value of getTouched() are valid'
		);
	}

	/**
	 * @covers User::findUsersByGroup
	 */
	public function testFindUsersByGroup() {
		$users = User::findUsersByGroup( [] );
		$this->assertSame( 0, iterator_count( $users ) );

		$users = User::findUsersByGroup( 'foo', 1, 1 );
		$this->assertSame( 0, iterator_count( $users ) );

		$user = $this->getMutableTestUser( [ 'foo' ] )->getUser();
		$users = User::findUsersByGroup( 'foo' );
		$this->assertSame( 1, iterator_count( $users ) );
		$users->rewind();
		$this->assertTrue( $user->equals( $users->current() ) );

		// arguments have OR relationship
		$user2 = $this->getMutableTestUser( [ 'bar' ] )->getUser();
		$users = User::findUsersByGroup( [ 'foo', 'bar' ] );
		$this->assertSame( 2, iterator_count( $users ) );
		$users->rewind();
		$this->assertTrue( $user->equals( $users->current() ) );
		$users->next();
		$this->assertTrue( $user2->equals( $users->current() ) );

		// users are not duplicated
		$user = $this->getMutableTestUser( [ 'baz', 'boom' ] )->getUser();
		$users = User::findUsersByGroup( [ 'baz', 'boom' ] );
		$this->assertSame( 1, iterator_count( $users ) );
		$users->rewind();
		$this->assertTrue( $user->equals( $users->current() ) );
	}

	/**
	 * @covers User::getBlockedStatus
	 */
	public function testSoftBlockRanges() {
		$this->overrideConfigValue( MainConfigNames::SoftBlockRanges, [ '10.0.0.0/8' ] );

		// IP isn't in $wgSoftBlockRanges
		$user = new User();
		$request = new FauxRequest();
		$request->setIP( '192.168.0.1' );
		$this->setSessionUser( $user, $request );
		$this->assertNull( $user->getBlock() );

		// IP is in $wgSoftBlockRanges
		$user = new User();
		$request = new FauxRequest();
		$request->setIP( '10.20.30.40' );
		$this->setSessionUser( $user, $request );
		$block = $user->getBlock();
		$this->assertInstanceOf( SystemBlock::class, $block );
		$this->assertSame( 'wgSoftBlockRanges', $block->getSystemBlockType() );

		// Make sure the block is really soft
		$request = new FauxRequest();
		$request->setIP( '10.20.30.40' );
		$this->setSessionUser( $this->user, $request );
		$this->assertFalse( $this->user->isAnon() );
		$this->assertNull( $this->user->getBlock() );
	}

	public function provideIsPingLimitable() {
		yield 'Not ip excluded' => [ [], null, true ];
		yield 'Ip excluded' => [ [ '1.2.3.4' ], null, false ];
		yield 'Ip subnet excluded' => [ [ '1.2.3.0/8' ], null, false ];
		yield 'noratelimit right' => [ [], 'noratelimit', false ];
	}

	/**
	 * @dataProvider provideIsPingLimitable
	 * @covers User::isPingLimitable
	 * @param array $rateLimitExcludeIps
	 * @param string|null $rightOverride
	 * @param bool $expected
	 */
	public function testIsPingLimitable(
		array $rateLimitExcludeIps,
		?string $rightOverride,
		bool $expected
	) {
		$request = new FauxRequest();
		$request->setIP( '1.2.3.4' );
		$user = User::newFromSession( $request );
		// We are trying to test for current user behaviour
		// since we are interested in request IP
		RequestContext::getMain()->setUser( $user );

		$this->overrideConfigValue( MainConfigNames::RateLimitsExcludedIPs, $rateLimitExcludeIps );
		if ( $rightOverride ) {
			$this->overrideUserPermissions( $user, $rightOverride );
		}
		$this->assertSame( $expected, $user->isPingLimitable() );
	}

	public function provideExperienceLevel() {
		return [
			[ 2, 2, 'newcomer' ],
			[ 12, 3, 'newcomer' ],
			[ 8, 5, 'newcomer' ],
			[ 15, 10, 'learner' ],
			[ 450, 20, 'learner' ],
			[ 460, 33, 'learner' ],
			[ 525, 28, 'learner' ],
			[ 538, 33, 'experienced' ],
			[ 9, null, 'newcomer' ],
			[ 10, null, 'learner' ],
			[ 501, null, 'experienced' ],
		];
	}

	/**
	 * @covers User::getExperienceLevel
	 * @dataProvider provideExperienceLevel
	 */
	public function testExperienceLevel( $editCount, $memberSince, $expLevel ) {
		$this->overrideConfigValues( [
			MainConfigNames::LearnerEdits => 10,
			MainConfigNames::LearnerMemberSince => 4,
			MainConfigNames::ExperiencedUserEdits => 500,
			MainConfigNames::ExperiencedUserMemberSince => 30,
		] );

		$db = wfGetDB( DB_PRIMARY );
		$userQuery = User::getQueryInfo();
		$row = $db->selectRow(
			$userQuery['tables'],
			$userQuery['fields'],
			[ 'user_id' => $this->user->getId() ],
			__METHOD__,
			[],
			$userQuery['joins']
		);
		$row->user_editcount = $editCount;
		if ( $memberSince !== null ) {
			$row->user_registration = $db->timestamp( time() - $memberSince * 86400 );
		} else {
			$row->user_registration = null;
		}
		$user = User::newFromRow( $row );

		$this->assertSame( $expLevel, $user->getExperienceLevel() );
	}

	/**
	 * @covers User::getExperienceLevel
	 */
	public function testExperienceLevelAnon() {
		$user = User::newFromName( '10.11.12.13', false );

		$this->assertFalse( $user->getExperienceLevel() );
	}

	public static function provideIsLocallyBlockedProxy() {
		return [
			[ '1.2.3.4', '1.2.3.4' ],
			[ '1.2.3.4', '1.2.3.0/16' ],
		];
	}

	/**
	 * @covers User::newFromId
	 */
	public function testNewFromId() {
		$userId = $this->user->getId();
		$this->assertGreaterThan(
			0,
			$userId,
			'user has a working id'
		);

		$otherUser = User::newFromId( $userId );
		$this->assertTrue(
			$this->user->equals( $otherUser ),
			'User created by id should match user with that id'
		);
	}

	/**
	 * @covers User::newFromActorId
	 */
	public function testActorId() {
		$this->filterDeprecated( '/Passing a parameter to getActorId\(\) is deprecated/', '1.36' );

		// Newly-created user has an actor ID
		$user = User::createNew( 'UserTestActorId1' );
		$id = $user->getId();
		$this->assertGreaterThan( 0, $user->getActorId(), 'User::createNew sets an actor ID' );

		$user = User::newFromName( 'UserTestActorId2' );
		$user->addToDatabase();
		$this->assertGreaterThan( 0, $user->getActorId(), 'User::addToDatabase sets an actor ID' );

		$user = User::newFromName( 'UserTestActorId1' );
		$this->assertGreaterThan( 0, $user->getActorId(),
			'Actor ID can be retrieved for user loaded by name' );

		$user = User::newFromId( $id );
		$this->assertGreaterThan( 0, $user->getActorId(),
			'Actor ID can be retrieved for user loaded by ID' );

		$user2 = User::newFromActorId( $user->getActorId() );
		$this->assertSame( $user->getId(), $user2->getId(),
			'User::newFromActorId works for an existing user' );

		$queryInfo = User::getQueryInfo();
		$row = $this->db->selectRow( $queryInfo['tables'],
			$queryInfo['fields'], [ 'user_id' => $id ], __METHOD__ );
		$user = User::newFromRow( $row );
		$this->assertGreaterThan( 0, $user->getActorId(),
			'Actor ID can be retrieved for user loaded with User::selectFields()' );

		$user = User::newFromId( $id );
		$user->setName( 'UserTestActorId4-renamed' );
		$user->saveSettings();
		$this->assertSame(
			$user->getName(),
			$this->db->selectField(
				'actor', 'actor_name', [ 'actor_id' => $user->getActorId() ], __METHOD__
			),
			'User::saveSettings updates actor table for name change'
		);

		$ip = '192.168.12.34';
		$this->db->delete( 'actor', [ 'actor_name' => $ip ], __METHOD__ );

		$user = User::newFromName( $ip, false );
		$this->assertSame( 0, $user->getActorId(), 'Anonymous user has no actor ID by default' );
		$this->filterDeprecated( '/Passing parameter of type IDatabase/' );
		$this->assertGreaterThan( 0, $user->getActorId( $this->db ),
			'Actor ID can be created for an anonymous user' );

		$user = User::newFromName( $ip, false );
		$this->assertGreaterThan( 0, $user->getActorId(),
			'Actor ID can be loaded for an anonymous user' );
		$user2 = User::newFromActorId( $user->getActorId() );
		$this->assertSame( $user->getName(), $user2->getName(),
			'User::newFromActorId works for an anonymous user' );
	}

	/**
	 * @covers User::getActorId
	 */
	public function testForeignGetActorId() {
		$this->filterDeprecated( '/Passing a parameter to getActorId\(\) is deprecated/', '1.36' );

		$user = User::newFromName( 'UserTestActorId1' );
		$this->expectException( PreconditionException::class );
		$user->getActorId( 'Foreign Wiki' );
	}

	/**
	 * @covers User::getWikiId
	 */
	public function testGetWiki() {
		$user = User::newFromName( 'UserTestActorId1' );
		$this->assertSame( User::LOCAL, $user->getWikiId() );
	}

	/**
	 * @covers User::assertWiki
	 */
	public function testAssertWiki() {
		$user = User::newFromName( 'UserTestActorId1' );

		$user->assertWiki( User::LOCAL );
		$this->assertTrue( true, 'User is for local wiki' );

		$this->expectException( PreconditionException::class );
		$user->assertWiki( 'Foreign Wiki' );
	}

	/**
	 * @covers User::newFromAnyId
	 */
	public function testNewFromAnyId() {
		// Registered user
		$user = $this->user;
		for ( $i = 1; $i <= 7; $i++ ) {
			$test = User::newFromAnyId(
				( $i & 1 ) ? $user->getId() : null,
				( $i & 2 ) ? $user->getName() : null,
				( $i & 4 ) ? $user->getActorId() : null
			);
			$this->assertSame( $user->getId(), $test->getId() );
			$this->assertSame( $user->getName(), $test->getName() );
			$this->assertSame( $user->getActorId(), $test->getActorId() );
		}

		// Anon user. Can't load by only user ID when that's 0.
		$user = User::newFromName( '192.168.12.34', false );
		// Make sure an actor ID exists
		$this->getServiceContainer()->getActorNormalization()->acquireActorId( $user, $this->db );

		$test = User::newFromAnyId( null, '192.168.12.34', null );
		$this->assertSame( $user->getId(), $test->getId() );
		$this->assertSame( $user->getName(), $test->getName() );
		$this->assertSame( $user->getActorId(), $test->getActorId() );
		$test = User::newFromAnyId( null, null, $user->getActorId() );
		$this->assertSame( $user->getId(), $test->getId() );
		$this->assertSame( $user->getName(), $test->getName() );
		$this->assertSame( $user->getActorId(), $test->getActorId() );

		// Bogus data should still "work" as long as nothing triggers a ->load(),
		// and accessing the specified data shouldn't do that.
		$test = User::newFromAnyId( 123456, 'Bogus', 654321 );
		$this->assertSame( 123456, $test->getId() );
		$this->assertSame( 'Bogus', $test->getName() );
		$this->assertSame( 654321, $test->getActorId() );

		// Loading remote user by name from remote wiki should succeed
		$test = User::newFromAnyId( null, 'Bogus', null, 'foo' );
		$this->assertSame( 0, $test->getId() );
		$this->assertSame( 'Bogus', $test->getName() );
		$this->assertSame( 0, $test->getActorId() );
		$test = User::newFromAnyId( 123456, 'Bogus', 654321, 'foo' );
		$this->assertSame( 0, $test->getId() );
		$this->assertSame( 0, $test->getActorId() );

		// Exceptional cases
		try {
			User::newFromAnyId( null, null, null );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
		}
		try {
			User::newFromAnyId( 0, null, 0 );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
		}

		// Loading remote user by id from remote wiki should fail
		try {
			User::newFromAnyId( 123456, null, 654321, 'foo' );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
		}
	}

	/**
	 * @covers User::newFromIdentity
	 */
	public function testNewFromIdentity() {
		// Registered user
		$user = $this->user;

		$this->assertSame( $user, User::newFromIdentity( $user ) );

		// ID only
		$identity = new UserIdentityValue( $user->getId(), '', 0 );
		$result = User::newFromIdentity( $identity );
		$this->assertInstanceOf( User::class, $result );
		$this->assertSame( $user->getId(), $result->getId(), 'ID' );
		$this->assertSame( $user->getName(), $result->getName(), 'Name' );
		$this->assertSame( $user->getActorId(), $result->getActorId(), 'Actor' );

		// Name only
		$identity = new UserIdentityValue( 0, $user->getName(), 0 );
		$result = User::newFromIdentity( $identity );
		$this->assertInstanceOf( User::class, $result );
		$this->assertSame( $user->getId(), $result->getId(), 'ID' );
		$this->assertSame( $user->getName(), $result->getName(), 'Name' );
		$this->assertSame( $user->getActorId(), $result->getActorId(), 'Actor' );
	}

	/**
	 * @covers User::newFromConfirmationCode
	 */
	public function testNewFromConfirmationCode() {
		$user = User::newFromConfirmationCode( 'NotARealConfirmationCode' );
		$this->assertNull(
			$user,
			'Invalid confirmation codes result in null users when reading from replicas'
		);

		$user = User::newFromConfirmationCode( 'OtherFakeCode', User::READ_LATEST );
		$this->assertNull(
			$user,
			'Invalid confirmation codes result in null users when reading from master'
		);
	}

	/**
	 * @covers User::newFromName
	 * @covers User::getName
	 * @covers User::getUserPage
	 * @covers User::getTalkPage
	 * @covers User::getTitleKey
	 * @covers User::whoIs
	 * @dataProvider provideNewFromName
	 */
	public function testNewFromName( $name, $titleKey ) {
		$user = User::newFromName( $name );
		$this->assertSame( $user->getName(), $name );
		$this->assertEquals( $user->getUserPage(), Title::makeTitle( NS_USER, $name ) );
		$this->assertEquals( $user->getTalkPage(), Title::makeTitle( NS_USER_TALK, $name ) );
		$this->assertSame( $user->getTitleKey(), $titleKey );

		$status = $user->addToDatabase();
		$this->assertStatusOK( $status, 'User can be added to the database' );
		$this->assertSame( $name, User::whoIs( $user->getId() ) );
	}

	public static function provideNewFromName() {
		return [
			[ 'Example1', 'Example1' ],
			[ 'Mediawiki easter egg', 'Mediawiki_easter_egg' ],
			[ 'See T22281 for more', 'See_T22281_for_more' ],
			[ 'DannyS712', 'DannyS712' ],
		];
	}

	/**
	 * @covers User::newFromName
	 */
	public function testNewFromName_extra() {
		$user = User::newFromName( '1.2.3.4' );
		$this->assertFalse( $user, 'IP addresses are not valid user names' );

		$user = User::newFromName( 'DannyS712', true );
		$otherUser = User::newFromName( 'DannyS712', 'valid' );
		$this->assertTrue(
			$user->equals( $otherUser ),
			'true maps to valid for backwards compatibility'
		);
	}

	/**
	 * @covers User::newFromSession
	 * @covers User::getRequest
	 */
	public function testSessionAndRequest() {
		$req1 = new WebRequest;
		$this->setRequest( $req1 );
		$user = User::newFromSession();
		$request = $user->getRequest();

		$this->assertSame(
			$req1,
			$request,
			'Creating a user without a request defaults to $wgRequest'
		);
		$req2 = new WebRequest;
		$this->assertNotSame(
			$req1,
			$req2,
			'passing a request that does not match $wgRequest'
		);
		$user = User::newFromSession( $req2 );
		$request = $user->getRequest();
		$this->assertSame(
			$req2,
			$request,
			'Creating a user by passing a WebRequest successfully sets the request, ' .
				'instead of using $wgRequest'
		);
	}

	/**
	 * @covers User::newFromRow
	 * @covers User::loadFromRow
	 */
	public function testNewFromRow() {
		// TODO: Create real tests here for loadFromRow
		$row = (object)[];
		$user = User::newFromRow( $row );
		$this->assertInstanceOf( User::class, $user, 'newFromRow returns a user object' );
	}

	/**
	 * @covers User::newFromRow
	 * @covers User::loadFromRow
	 */
	public function testNewFromRow_bad() {
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( '$row must be an object' );
		User::newFromRow( [] );
	}

	/**
	 * @covers User::getBlockedStatus
	 * @covers User::getBlockId
	 * @covers User::getBlock
	 * @covers User::blockedBy
	 * @covers User::blockedFor
	 * @covers User::isHidden
	 * @covers User::isBlockedFrom
	 */
	public function testBlockInstanceCache() {
		$this->hideDeprecated( 'User::blockedBy' );
		$this->hideDeprecated( 'User::blockedFor' );
		$this->hideDeprecated( 'User::getBlockId' );
		// First, check the user isn't blocked
		$user = $this->getMutableTestUser()->getUser();
		$ut = Title::makeTitle( NS_USER_TALK, $user->getName() );
		$this->assertNull( $user->getBlock( false ) );
		$this->assertSame( '', $user->blockedBy() );
		$this->assertSame( '', $user->blockedFor() );
		$this->assertFalse( $user->isHidden() );
		$this->assertFalse( $user->isBlockedFrom( $ut ) );

		// Block the user
		$blocker = $this->getTestSysop()->getUser();
		$block = new DatabaseBlock( [
			'hideName' => true,
			'allowUsertalk' => false,
			'reason' => 'Because',
		] );
		$block->setTarget( $user );
		$block->setBlocker( $blocker );
		$blockStore = $this->getServiceContainer()->getDatabaseBlockStore();
		$res = $blockStore->insertBlock( $block );
		$this->assertTrue( (bool)$res['id'], 'Failed to insert block' );

		// Clear cache and confirm it loaded the block properly
		$user->clearInstanceCache();
		$this->assertInstanceOf( DatabaseBlock::class, $user->getBlock( false ) );
		$this->assertSame( $blocker->getName(), $user->blockedBy() );
		$this->assertSame( 'Because', $user->blockedFor() );
		$this->assertTrue( $user->isHidden() );
		$this->assertTrue( $user->isBlockedFrom( $ut ) );
		$this->assertSame( $res['id'], $user->getBlockId() );

		// Unblock
		$blockStore->deleteBlock( $block );

		// Clear cache and confirm it loaded the not-blocked properly
		$user->clearInstanceCache();
		$this->assertNull( $user->getBlock( false ) );
		$this->assertSame( '', $user->blockedBy() );
		$this->assertSame( '', $user->blockedFor() );
		$this->assertFalse( $user->isHidden() );
		$this->assertFalse( $user->isBlockedFrom( $ut ) );
		$this->assertFalse( $user->getBlockId() );
	}

	/**
	 * @covers User::getBlockedStatus
	 */
	public function testCompositeBlocks() {
		$this->tablesUsed[] = 'ipblocks';

		$user = $this->getMutableTestUser()->getUser();
		$request = $user->getRequest();
		$this->setSessionUser( $user, $request );

		$blockStore = $this->getServiceContainer()->getDatabaseBlockStore();
		$ipBlock = new DatabaseBlock( [
			'address' => $user->getRequest()->getIP(),
			'by' => $this->getTestSysop()->getUser(),
			'createAccount' => true,
		] );
		$blockStore->insertBlock( $ipBlock );

		$userBlock = new DatabaseBlock( [
			'address' => $user,
			'by' => $this->getTestSysop()->getUser(),
			'createAccount' => false,
		] );
		$blockStore->insertBlock( $userBlock );

		$block = $user->getBlock();
		$this->assertInstanceOf( CompositeBlock::class, $block );
		$this->assertTrue( $block->isCreateAccountBlocked() );
		$this->assertTrue( $block->appliesToPasswordReset() );
		$this->assertTrue( $block->appliesToNamespace( NS_MAIN ) );
	}

	/**
	 * @covers User::getBlock
	 */
	public function testUserBlock() {
		$this->tablesUsed[] = 'ipblocks';

		$user = $this->getMutableTestUser()->getUser();
		$request = $user->getRequest();
		$this->setSessionUser( $user, $request );

		$blockStore = $this->getServiceContainer()->getDatabaseBlockStore();
		$ipBlock = new DatabaseBlock( [
			'address' => $user,
			'by' => $this->getTestSysop()->getUser(),
			'createAccount' => true,
		] );
		$blockStore->insertBlock( $ipBlock );

		$block = $user->getBlock();
		$this->assertNotNull( $block, 'getuserBlock' );
		$this->assertNotNull( $block->getTargetUserIdentity(), 'getTargetUserIdentity()' );
		$this->assertSame( $user->getName(), $block->getTargetUserIdentity()->getName() );
	}

	/**
	 * @covers User::isBlockedFrom
	 * @dataProvider provideIsBlockedFrom
	 * @param string|null $title Title to test.
	 * @param bool $expect Expected result from User::isBlockedFrom()
	 * @param array $options Additional test options:
	 *  - 'blockAllowsUTEdit': (bool, default true) Value for $wgBlockAllowsUTEdit
	 *  - 'allowUsertalk': (bool, default false) Passed to DatabaseBlock::__construct()
	 *  - 'pageRestrictions': (array|null) If non-empty, page restriction titles for the block.
	 */
	public function testIsBlockedFrom( $title, $expect, array $options = [] ) {
		$this->overrideConfigValue( MainConfigNames::BlockAllowsUTEdit, $options['blockAllowsUTEdit'] ?? true );

		$user = $this->getMutableTestUser()->getUser();

		if ( $title === self::USER_TALK_PAGE ) {
			$title = $user->getTalkPage();
		} else {
			$title = Title::newFromText( $title );
		}

		$restrictions = [];
		foreach ( $options['pageRestrictions'] ?? [] as $pagestr ) {
			$page = $this->getExistingTestPage(
				$pagestr === self::USER_TALK_PAGE ? $user->getTalkPage() : $pagestr
			);
			$restrictions[] = new PageRestriction( 0, $page->getId() );
		}
		foreach ( $options['namespaceRestrictions'] ?? [] as $ns ) {
			$restrictions[] = new NamespaceRestriction( 0, $ns );
		}

		$block = new DatabaseBlock( [
			'expiry' => wfTimestamp( TS_MW, wfTimestamp() + ( 40 * 60 * 60 ) ),
			'allowUsertalk' => $options['allowUsertalk'] ?? false,
			'sitewide' => !$restrictions,
		] );
		$block->setTarget( $user );
		$block->setBlocker( $this->getTestSysop()->getUser() );
		if ( $restrictions ) {
			$block->setRestrictions( $restrictions );
		}
		$blockStore = $this->getServiceContainer()->getDatabaseBlockStore();
		$blockStore->insertBlock( $block );

		try {
			$this->assertSame( $expect, $user->isBlockedFrom( $title ) );
		} finally {
			$blockStore->deleteBlock( $block );
		}
	}

	public static function provideIsBlockedFrom() {
		return [
			'Sitewide block, basic operation' => [ 'Test page', true ],
			'Sitewide block, not allowing user talk' => [
				self::USER_TALK_PAGE, true, [
					'allowUsertalk' => false,
				]
			],
			'Sitewide block, allowing user talk' => [
				self::USER_TALK_PAGE, false, [
					'allowUsertalk' => true,
				]
			],
			'Sitewide block, allowing user talk but $wgBlockAllowsUTEdit is false' => [
				self::USER_TALK_PAGE, true, [
					'allowUsertalk' => true,
					'blockAllowsUTEdit' => false,
				]
			],
			'Partial block, blocking the page' => [
				'Test page', true, [
					'pageRestrictions' => [ 'Test page' ],
				]
			],
			'Partial block, not blocking the page' => [
				'Test page 2', false, [
					'pageRestrictions' => [ 'Test page' ],
				]
			],
			'Partial block, not allowing user talk but user talk page is not blocked' => [
				self::USER_TALK_PAGE, false, [
					'allowUsertalk' => false,
					'pageRestrictions' => [ 'Test page' ],
				]
			],
			'Partial block, allowing user talk but user talk page is blocked' => [
				self::USER_TALK_PAGE, true, [
					'allowUsertalk' => true,
					'pageRestrictions' => [ self::USER_TALK_PAGE ],
				]
			],
			'Partial block, user talk page is not blocked but $wgBlockAllowsUTEdit is false' => [
				self::USER_TALK_PAGE, false, [
					'allowUsertalk' => false,
					'pageRestrictions' => [ 'Test page' ],
					'blockAllowsUTEdit' => false,
				]
			],
			'Partial block, user talk page is blocked and $wgBlockAllowsUTEdit is false' => [
				self::USER_TALK_PAGE, true, [
					'allowUsertalk' => true,
					'pageRestrictions' => [ self::USER_TALK_PAGE ],
					'blockAllowsUTEdit' => false,
				]
			],
			'Partial user talk namespace block, not allowing user talk' => [
				self::USER_TALK_PAGE, true, [
					'allowUsertalk' => false,
					'namespaceRestrictions' => [ NS_USER_TALK ],
				]
			],
			'Partial user talk namespace block, allowing user talk' => [
				self::USER_TALK_PAGE, false, [
					'allowUsertalk' => true,
					'namespaceRestrictions' => [ NS_USER_TALK ],
				]
			],
			'Partial user talk namespace block, where $wgBlockAllowsUTEdit is false' => [
				self::USER_TALK_PAGE, true, [
					'allowUsertalk' => true,
					'namespaceRestrictions' => [ NS_USER_TALK ],
					'blockAllowsUTEdit' => false,
				]
			],
		];
	}

	/**
	 * @covers User::isBlockedFromEmailuser
	 * @covers User::isAllowedToCreateAccount
	 * @dataProvider provideIsBlockedFromAction
	 * @param bool $blockFromEmail Whether to block email access.
	 * @param bool $blockFromAccountCreation Whether to block account creation.
	 */
	public function testIsBlockedFromAction( $blockFromEmail, $blockFromAccountCreation ) {
		$user = $this->getMutableTestUser( 'accountcreator' )->getUser();

		$block = new DatabaseBlock( [
			'expiry' => wfTimestamp( TS_MW, wfTimestamp() + ( 40 * 60 * 60 ) ),
			'sitewide' => true,
			'blockEmail' => $blockFromEmail,
			'createAccount' => $blockFromAccountCreation
		] );
		$block->setTarget( $user );
		$block->setBlocker( $this->getTestSysop()->getUser() );
		$blockStore = $this->getServiceContainer()->getDatabaseBlockStore();
		$blockStore->insertBlock( $block );

		try {
			$this->assertSame( $blockFromEmail, $user->isBlockedFromEmailuser() );
			$this->assertSame( !$blockFromAccountCreation, $user->isAllowedToCreateAccount() );
		} finally {
			$blockStore->deleteBlock( $block );
		}
	}

	public static function provideIsBlockedFromAction() {
		return [
			'Block email access and account creation' => [ true, true ],
			'Block only email access' => [ true, false ],
			'Block only account creation' => [ false, true ],
			'Allow email access and account creation' => [ false, false ],
		];
	}

	/**
	 * @covers User::isBlockedFromUpload
	 * @dataProvider provideIsBlockedFromUpload
	 * @param bool $sitewide Whether to block sitewide.
	 * @param bool $expected Whether the user is expected to be blocked from uploads.
	 */
	public function testIsBlockedFromUpload( $sitewide, $expected ) {
		$user = $this->getMutableTestUser()->getUser();

		$block = new DatabaseBlock( [
			'expiry' => wfTimestamp( TS_MW, wfTimestamp() + ( 40 * 60 * 60 ) ),
			'sitewide' => $sitewide,
		] );
		$block->setTarget( $user );
		$block->setBlocker( $this->getTestSysop()->getUser() );
		$blockStore = $this->getServiceContainer()->getDatabaseBlockStore();
		$blockStore->insertBlock( $block );

		try {
			$this->assertSame( $expected, $user->isBlockedFromUpload() );
		} finally {
			$blockStore->deleteBlock( $block );
		}
	}

	public static function provideIsBlockedFromUpload() {
		return [
			'sitewide blocks block uploads' => [ true, true ],
			'partial blocks allow uploads' => [ false, false ],
		];
	}

	/**
	 * @param User $user
	 * @param string $title
	 * @param string $content
	 * @param string $comment
	 * @return \MediaWiki\Revision\RevisionRecord|null
	 */
	private static function makeEdit( User $user, $title, $content, $comment ) {
		$page = WikiPage::factory( Title::newFromText( $title ) );
		$content = ContentHandler::makeContent( $content, $page->getTitle() );
		return $page->newPageUpdater( $user )
			->setContent( SlotRecord::MAIN, $content )
			->saveRevision( CommentStoreComment::newUnsavedComment( $comment ) );
	}

	/**
	 * @covers User::idFromName
	 */
	public function testExistingIdFromName() {
		$this->assertSame(
			$this->user->getId(), User::idFromName( $this->user->getName() ),
			'Id is correctly retrieved from the cache.'
		);
		$this->assertSame(
			$this->user->getId(), User::idFromName( $this->user->getName(), User::READ_LATEST ),
			'Id is correctly retrieved from the database.'
		);
	}

	/**
	 * @covers User::idFromName
	 */
	public function testNonExistingIdFromName() {
		$this->assertNull( User::idFromName( 'NotExisitngUser' ) );
	}

	/**
	 * @covers User::isSystemUser
	 */
	public function testIsSystemUser() {
		$this->assertFalse( $this->user->isSystemUser(), 'Normal users are not system users' );

		$user = User::newSystemUser( __METHOD__ );
		$this->assertTrue( $user->isSystemUser(), 'Users created with newSystemUser() are system users' );
	}

	/**
	 * @covers User::newSystemUser
	 * @dataProvider provideNewSystemUser
	 * @param string $exists How/whether to create the user before calling User::newSystemUser
	 *  - 'missing': Do not create the user
	 *  - 'actor': Create an anonymous actor
	 *  - 'user': Create a non-system user
	 *  - 'system': Create a system user
	 * @param string $options Options to User::newSystemUser
	 * @param array $testOpts Test options
	 * @param string $expect 'user', 'exception', or 'null'
	 */
	public function testNewSystemUser( $exists, $options, $testOpts, $expect ) {
		$this->filterDeprecated( '/User::newSystemUser options/' );
		$origUser = null;
		$actorId = null;

		switch ( $exists ) {
			case 'missing':
				$name = 'TestNewSystemUser ' . TestUserRegistry::getNextId();
				break;

			case 'actor':
				$name = 'TestNewSystemUser ' . TestUserRegistry::getNextId();
				$this->db->insert( 'actor', [ 'actor_name' => $name ] );
				$actorId = (int)$this->db->insertId();
				break;

			case 'user':
				$origUser = $this->getMutableTestUser()->getUser();
				$name = $origUser->getName();
				$actorId = $origUser->getActorId();
				break;

			case 'system':
				$name = 'TestNewSystemUser ' . TestUserRegistry::getNextId();
				$user = User::newSystemUser( $name ); // Heh.
				$actorId = $user->getActorId();
				// Use this hook as a proxy for detecting when a "steal" happens.
				$this->setTemporaryHook( 'InvalidateEmailComplete', function () {
					$this->fail( 'InvalidateEmailComplete hook should not have been called' );
				} );
				break;
		}

		$globals = $testOpts['globals'] ?? [];
		if ( !empty( $testOpts['reserved'] ) ) {
			$globals[MainConfigNames::ReservedUsernames] = [ $name ];
		}
		$this->overrideConfigValues( $globals );
		$userNameUtils = $this->getServiceContainer()->getUserNameUtils();
		$this->assertSame( empty( $testOpts['reserved'] ), $userNameUtils->isUsable( $name ) );
		$this->assertTrue( $userNameUtils->isValid( $name ) );

		if ( $expect === 'exception' ) {
			// T248195: Duplicate entry errors will log the exception, don't fail because of that.
			$this->setNullLogger( 'DBQuery' );
			$this->expectException( Exception::class );
		}
		$user = User::newSystemUser( $name, $options );
		if ( $expect === 'null' ) {
			$this->assertNull( $user );
			if ( $origUser ) {
				$this->assertNotSame(
					User::INVALID_TOKEN, TestingAccessWrapper::newFromObject( $origUser )->mToken
				);
				$this->assertNotSame( '', $origUser->getEmail() );
				$this->assertFalse( $origUser->isSystemUser(), 'Normal users should not be system users' );
			}
		} else {
			$this->assertInstanceOf( User::class, $user );
			$this->assertSame( $name, $user->getName() );
			if ( $actorId !== null ) {
				$this->assertSame( $actorId, $user->getActorId() );
			}
			$this->assertSame( User::INVALID_TOKEN, TestingAccessWrapper::newFromObject( $user )->mToken );
			$this->assertSame( '', $user->getEmail() );
			$this->assertTrue( $user->isSystemUser(), 'Newly created system users should be system users' );
		}
	}

	public static function provideNewSystemUser() {
		return [
			'Basic creation' => [ 'missing', [], [], 'user' ],
			'No creation' => [ 'missing', [ 'create' => false ], [], 'null' ],
			'Validation fail' => [
				'missing',
				[ 'validate' => 'usable' ],
				[ 'reserved' => true ],
				'null'
			],
			'No stealing' => [ 'user', [], [], 'null' ],
			'Stealing allowed' => [ 'user', [ 'steal' => true ], [], 'user' ],
			'Stealing an already-system user' => [ 'system', [ 'steal' => true ], [], 'user' ],
			'Anonymous actor (T236444)' => [ 'actor', [], [ 'reserved' => true ], 'user' ],
			'System user (T236444), reserved' => [ 'system', [], [ 'reserved' => true ], 'user' ],
			'Reserved but no anonymous actor' => [ 'missing', [], [ 'reserved' => true ], 'user' ],
			'Anonymous actor but no creation' => [ 'actor', [ 'create' => false ], [], 'null' ],
			'Anonymous actor but not reserved' => [ 'actor', [], [], 'exception' ],
		];
	}

	/**
	 * @covers User::getGroups
	 */
	public function testGetGroups() {
		$user = $this->getTestUser( [ 'a', 'b' ] )->getUser();
		$this->assertArrayEquals( [ 'a', 'b' ], $user->getGroups() );
	}

	/**
	 * @covers User::addGroup
	 */
	public function testAddGroup() {
		$user = $this->getTestUser()->getUser();
		$this->assertSame( [], $user->getGroups() );

		$this->assertTrue( $user->addGroup( 'test' ) );
		$this->assertArrayEquals( [ 'test' ], $user->getGroups() );

		$this->assertTrue( $user->addGroup( 'test2' ) );
		$this->assertArrayEquals( [ 'test', 'test2' ], $user->getGroups() );

		$this->setTemporaryHook( 'UserAddGroup', static function ( $user, &$group, &$expiry ) {
			return false;
		} );
		$this->assertFalse( $user->addGroup( 'test3' ) );
		$this->assertArrayEquals(
			[ 'test', 'test2' ],
			$user->getGroups(),
			'Hooks can stop addition of a group'
		);
	}

	/**
	 * @covers User::removeGroup
	 */
	public function testRemoveGroup() {
		$user = $this->getTestUser( [ 'test', 'test3' ] )->getUser();

		$this->assertTrue( $user->removeGroup( 'test' ) );
		$this->assertSame( [ 'test3' ], $user->getGroups() );

		$this->assertFalse(
			$user->removeGroup( 'test2' ),
			'A group membership that does not exist cannot be removed'
		);

		$this->setTemporaryHook( 'UserRemoveGroup', static function ( $user, &$group ) {
			return false;
		} );

		$this->assertFalse( $user->removeGroup( 'test3' ) );
		$this->assertSame( [ 'test3' ], $user->getGroups(), 'Hooks can stop removal of a group' );
	}

	/**
	 * @covers User::getName
	 * @covers User::setName
	 */
	public function testUserName() {
		$user = User::newFromName( 'DannyS712' );
		$this->assertSame(
			'DannyS712',
			$user->getName(),
			'Santiy check: Users created using ::newFromName should return the name used'
		);

		$user->setName( 'FooBarBaz' );
		$this->assertSame(
			'FooBarBaz',
			$user->getName(),
			'Changing a username via ::setName should be reflected in ::getName'
		);
	}

	/**
	 * @covers User::getEmail
	 * @covers User::setEmail
	 * @covers User::invalidateEmail
	 */
	public function testUserEmail() {
		$user = $this->user;

		$user->setEmail( 'TestEmail@mediawiki.org' );
		$this->assertSame(
			'TestEmail@mediawiki.org',
			$user->getEmail(),
			'Setting an email via ::setEmail should be reflected in ::getEmail'
		);

		$this->setTemporaryHook( 'UserSetEmail', function ( $user, &$email ) {
			$this->fail(
				'UserSetEmail hook should not be called when the new email ' .
				'is the same as the old email.'
			);
		} );
		$user->setEmail( 'TestEmail@mediawiki.org' );

		$this->removeTemporaryHook( 'UserSetEmail' );

		$this->setTemporaryHook( 'UserSetEmail', static function ( $user, &$email ) {
			$email = 'SettingIntercepted@mediawiki.org';
		} );
		$user->setEmail( 'NewEmail@mediawiki.org' );
		$this->assertSame(
			'SettingIntercepted@mediawiki.org',
			$user->getEmail(),
			'Hooks can override setting email addresses'
		);

		$this->setTemporaryHook( 'UserGetEmail', static function ( $user, &$email ) {
			$email = 'GettingIntercepted@mediawiki.org';
		} );
		$this->assertSame(
			'GettingIntercepted@mediawiki.org',
			$user->getEmail(),
			'Hooks can override getting email address'
		);

		$this->removeTemporaryHook( 'UserGetEmail' );
		$this->removeTemporaryHook( 'UserSetEmail' );

		$user->invalidateEmail();
		$this->assertSame(
			'',
			$user->getEmail(),
			'After invalidation, a user email should be an empty string'
		);
	}

	/**
	 * @covers User::setEmailWithConfirmation
	 */
	public function testSetEmailWithConfirmation_basic() {
		$user = $this->getTestUser()->getUser();
		$startingEmail = 'startingemail@mediawiki.org';
		$user->setEmail( $startingEmail );

		$this->overrideConfigValues( [
			MainConfigNames::EnableEmail => false,
			MainConfigNames::EmailAuthentication => false
		] );
		$status = $user->setEmailWithConfirmation( 'test1@mediawiki.org' );
		$this->assertSame(
			'emaildisabled',
			$status->getErrors()[0]['message'],
			'Cannot set email when email is disabled'
		);
		$this->assertSame(
			$user->getEmail(),
			$startingEmail,
			'Email has not changed'
		);

		$this->overrideConfigValue( MainConfigNames::EnableEmail, true );
		$status = $user->setEmailWithConfirmation( $startingEmail );
		$this->assertTrue(
			$status->getValue(),
			'Returns true if the email specified is the current email'
		);
		$this->assertSame(
			$user->getEmail(),
			$startingEmail,
			'Email has not changed'
		);
	}

	/**
	 * @covers User::isItemLoaded
	 * @covers User::setItemLoaded
	 */
	public function testItemLoaded() {
		$user = User::newFromName( 'DannyS712' );
		$this->assertTrue(
			$user->isItemLoaded( 'name', 'only' ),
			'Users created by name have user names loaded'
		);
		$this->assertFalse(
			$user->isItemLoaded( 'all', 'all' ),
			'Not everything is loaded yet'
		);
		$user->load();
		$this->assertTrue(
			$user->isItemLoaded( 'FooBar', 'all' ),
			'All items now loaded'
		);
	}

	/**
	 * @covers User::requiresHTTPS
	 * @dataProvider provideRequiresHTTPS
	 */
	public function testRequiresHTTPS( $preference, bool $expected ) {
		$this->overrideConfigValues( [
			MainConfigNames::SecureLogin => true,
			MainConfigNames::ForceHTTPS => false,
		] );

		$user = User::newFromName( 'UserWhoMayRequireHTTPS' );
		$user->addToDatabase();
		$this->getServiceContainer()->getUserOptionsManager()->setOption(
			$user,
			'prefershttps',
			$preference
		);
		$user->saveSettings();

		$this->assertTrue( $user->isRegistered() );
		$this->assertSame( $expected, $user->requiresHTTPS() );
	}

	public static function provideRequiresHTTPS() {
		return [
			'Wants, requires' => [ true, true ],
			'Does not want, not required' => [ false, false ],
		];
	}

	/**
	 * @covers User::requiresHTTPS
	 */
	public function testRequiresHTTPS_disabled() {
		$this->overrideConfigValues( [
			MainConfigNames::SecureLogin => false,
			MainConfigNames::ForceHTTPS => false,
		] );

		$user = User::newFromName( 'UserWhoMayRequireHTTP' );
		$user->addToDatabase();
		$this->getServiceContainer()->getUserOptionsManager()->setOption(
			$user,
			'prefershttps',
			true
		);
		$user->saveSettings();

		$this->assertTrue( $user->isRegistered() );
		$this->assertFalse(
			$user->requiresHTTPS(),
			'User preference ignored if wgSecureLogin  is false'
		);
	}

	/**
	 * @covers User::requiresHTTPS
	 */
	public function testRequiresHTTPS_forced() {
		$this->overrideConfigValues( [
			MainConfigNames::SecureLogin => true,
			MainConfigNames::ForceHTTPS => true,
		] );

		$user = User::newFromName( 'UserWhoMayRequireHTTP' );
		$user->addToDatabase();
		$this->getServiceContainer()->getUserOptionsManager()->setOption(
			$user,
			'prefershttps',
			false
		);
		$user->saveSettings();

		$this->assertTrue( $user->isRegistered() );
		$this->assertTrue(
			$user->requiresHTTPS(),
			'User preference ignored if wgForceHTTPS is true'
		);
	}

	/**
	 * @covers User::addToDatabase
	 */
	public function testAddToDatabase_bad() {
		$user = new User();
		$this->expectException( RuntimeException::class );
		$this->expectExceptionMessage(
			'User name field is not set.'
		);
		$user->addToDatabase();
	}

	/**
	 * @covers User::pingLimiter
	 */
	public function testPingLimiter() {
		$user = $this->getTestUser()->getUser();

		$limiter = $this->createNoOpMock( RateLimiter::class, [ 'limit' ] );
		$limiter->method( 'limit' )->willReturnCallback(
			function ( RateLimitSubject $subject, $action ) use ( $user ) {
				$this->assertSame( $user, $subject->getUser() );
				return $action === 'limited';
			}
		);

		$this->setService( 'RateLimiter', $limiter );

		$this->assertTrue( $user->pingLimiter( 'limited' ) );
		$this->assertFalse( $user->pingLimiter( 'unlimited' ) );
	}

	/**
	 * @covers User::loadFromDatabase
	 * @covers User::loadDefaults
	 */
	public function testBadUserID() {
		$user = User::newFromId( 999999999 );
		$this->assertSame( 'Unknown user', $user->getName() );
	}

	/**
	 * @covers User::probablyCan
	 * @covers User::definitelyCan
	 * @covers User::authorizeRead
	 * @covers User::authorizeWrite
	 */
	public function testAuthorityMethods() {
		$user = $this->getTestUser()->getUser();
		$page = Title::makeTitle( NS_MAIN, 'Test' );
		$this->assertFalse( $user->probablyCan( 'create', $page ) );
		$this->assertFalse( $user->definitelyCan( 'create', $page ) );
		$this->assertFalse( $user->authorizeRead( 'create', $page ) );
		$this->assertFalse( $user->authorizeWrite( 'create', $page ) );

		$this->overrideUserPermissions( $user, 'createpage' );
		$this->assertTrue( $user->probablyCan( 'create', $page ) );
		$this->assertTrue( $user->definitelyCan( 'create', $page ) );
		$this->assertTrue( $user->authorizeRead( 'create', $page ) );
		$this->assertTrue( $user->authorizeWrite( 'create', $page ) );
	}

	/**
	 * @covers User::isAllowed
	 * @covers User::__sleep
	 */
	public function testSerializationRoudTripWithAuthority() {
		$user = $this->getTestUser()->getUser();
		$isAllowed = $user->isAllowed( 'read' ); // Memoize the Authority
		$unserializedUser = unserialize( serialize( $user ) );
		$this->assertSame( $user->getId(), $unserializedUser->getId() );
		$this->assertSame( $isAllowed, $unserializedUser->isAllowed( 'read' ) );
	}

	private function enableAutoCreateTempUser() {
		$this->overrideConfigValue(
			MainConfigNames::AutoCreateTempUser,
			[
				'enabled' => true,
				'actions' => [ 'edit' ],
				'genPattern' => '*Unregistered $1',
				'matchPattern' => '*$1',
				'serialProvider' => [ 'type' => 'local' ],
				'serialMapping' => [ 'type' => 'plain-numeric' ],
			]
		);
	}

	public static function provideIsTemp() {
		return [
			[ '*Unregistered 1', true ],
			[ 'Some user', false ]
		];
	}

	/**
	 * @covers User::isTemp
	 * @dataProvider provideIsTemp
	 */
	public function testIsTemp( $name, $expected ) {
		$this->enableAutoCreateTempUser();
		$user = new User;
		$user->setName( $name );
		$this->assertSame( $expected, $user->isTemp() );
	}

	/**
	 * @covers User::isNamed
	 */
	public function testIsNamed() {
		$this->enableAutoCreateTempUser();

		// Temp user is not named
		$user = new User;
		$user->setName( '*Unregistered 1' );
		$this->assertFalse( $user->isNamed() );

		// Registered user is named
		$user = $this->getMutableTestUser()->getUser();
		$this->assertTrue( $user->isNamed() );

		// Anon is not named
		$user = new User;
		$this->assertFalse( $user->isNamed() );
	}
}
