<?php

use MediaWiki\Block\BlockUser;
use MediaWiki\Block\CompositeBlock;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\SystemBlock;
use MediaWiki\Context\RequestContext;
use MediaWiki\MainConfigNames;
use MediaWiki\Permissions\RateLimiter;
use MediaWiki\Permissions\RateLimitSubject;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Request\WebRequest;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWiki\User\UserIdentityValue;
use MediaWiki\Utils\MWTimestamp;
use Wikimedia\Assert\PreconditionException;
use Wikimedia\Rdbms\IDBAccessObject;
use Wikimedia\TestingAccessWrapper;

/**
 * @coversDefaultClass \MediaWiki\User\User
 * @group Database
 */
class UserTest extends MediaWikiIntegrationTestCase {
	use DummyServicesTrait;
	use TempUserTestTrait;

	/** Constant for self::testIsBlockedFrom */
	private const USER_TALK_PAGE = '<user talk page>';

	protected User $user;

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
	 * @covers \MediaWiki\User\User::isAllowedAny
	 * @covers \MediaWiki\User\User::isAllowedAll
	 * @covers \MediaWiki\User\User::isAllowed
	 * @covers \MediaWiki\User\User::isNewbie
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
	 * @covers \MediaWiki\User\User::useRCPatrol
	 * @covers \MediaWiki\User\User::useNPPatrol
	 * @covers \MediaWiki\User\User::useFilePatrol
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
	 * @covers \MediaWiki\User\User::isBot
	 */
	public function testBot() {
		$user = $this->getTestUser( 'bot' )->getUser();

		$userGroupManager = $this->getServiceContainer()->getUserGroupManager();
		$this->assertSame( [ 'bot' ], $userGroupManager->getUserGroups( $user ) );
		$this->assertArrayHasKey( 'bot', $userGroupManager->getUserGroupMemberships( $user ) );
		$this->assertTrue( $user->isBot() );

		$this->assertArrayNotHasKey( 'bot', $userGroupManager->getUserGroupMemberships( $this->user ) );
		$this->assertFalse( $this->user->isBot() );
	}

	/**
	 * Test User::editCount
	 * @group medium
	 * @covers \MediaWiki\User\User::getEditCount
	 */
	public function testGetEditCount() {
		$user = $this->getMutableTestUser()->getUser();

		// let the user have a few (3) edits
		$title = Title::makeTitle( NS_HELP, 'UserTest_EditCount' );
		for ( $i = 0; $i < 3; $i++ ) {
			$this->editPage(
				$title,
				(string)$i,
				'test',
				NS_MAIN,
				$user
			);
		}

		$this->assertSame(
			3,
			$user->getEditCount(),
			'After three edits, the user edit count should be 3'
		);

		// increase the edit count
		$this->getServiceContainer()->getUserEditTracker()->incrementUserEditCount( $user );
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
	 * @covers \MediaWiki\User\User::getEditCount
	 */
	public function testGetEditCountForAnons() {
		$user = User::newFromName( 'Anonymous' );

		$this->assertNull(
			$user->getEditCount(),
			'Edit count starts null for anonymous users.'
		);

		$this->assertNull(
			$this->getServiceContainer()->getUserEditTracker()->incrementUserEditCount( $user ),
			'Edit count cannot be increased for anonymous users'
		);

		$this->assertNull(
			$user->getEditCount(),
			'Edit count remains null for anonymous users despite calls to increase it.'
		);
	}

	/**
	 * @covers \MediaWiki\User\User::getRightDescription
	 */
	public function testGetRightDescription() {
		$key = 'deletechangetags';
		$parsedDescription = User::getRightDescription( $key );
		$this->assertMatchesRegularExpression( '/[|]/', $parsedDescription );
	}

	/**
	 * @covers \MediaWiki\User\User::getRightDescriptionHtml
	 */
	public function testGetParsedRightDescription() {
		$key = 'deletechangetags';
		$parsedDescription = User::getRightDescriptionHtml( $key );
		$this->assertMatchesRegularExpression( '/<.*>/', $parsedDescription );
	}

	/**
	 * Test password validity checks. There are 3 checks in core:
	 *	- ensure the password meets the minimal length
	 *	- ensure the password is not the same as the username
	 *	- ensure the username/password combo isn't forbidden
	 * @covers \MediaWiki\User\User::checkPasswordValidity()
	 * @covers \MediaWiki\User\User::isValidPassword()
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
					'MinimalPasswordLength' => 'MediaWiki\Password\PasswordPolicyChecks::checkMinimalPasswordLength',
					'MinimumPasswordLengthToLogin' => 'MediaWiki\Password\PasswordPolicyChecks::checkMinimumPasswordLengthToLogin',
					'PasswordCannotBeSubstringInUsername' =>
						'MediaWiki\Password\PasswordPolicyChecks::checkPasswordCannotBeSubstringInUsername',
					'PasswordCannotMatchDefaults' => 'MediaWiki\Password\PasswordPolicyChecks::checkPasswordCannotMatchDefaults',
					'MaximalPasswordLength' => 'MediaWiki\Password\PasswordPolicyChecks::checkMaximalPasswordLength',
				],
			]
		);

		$this->assertTrue( $this->user->isValidPassword( 'Password1234' ) );

		// Minimum length
		$this->assertFalse( $this->user->isValidPassword( 'a' ) );
		$status = $this->user->checkPasswordValidity( 'a' );
		$this->assertStatusWarning( 'passwordtooshort', $status );

		// Maximum length
		$longPass = str_repeat( 'a', 41 );
		$this->assertFalse( $this->user->isValidPassword( $longPass ) );
		$status = $this->user->checkPasswordValidity( $longPass );
		$this->assertStatusError( 'passwordtoolong', $status );

		// Matches username
		$status = $this->user->checkPasswordValidity( $this->user->getName() );
		$this->assertStatusWarning( 'password-substring-username-match', $status );

		$this->setTemporaryHook( 'isValidPassword', static function ( $password, &$result, $user ) {
			$result = 'isValidPassword returned false';
			return false;
		} );
		$status = $this->user->checkPasswordValidity( 'Password1234' );
		$this->assertStatusWarning( 'isValidPassword returned false', $status );

		$this->clearHook( 'isValidPassword' );

		$this->setTemporaryHook( 'isValidPassword', static function ( $password, &$result, $user ) {
			$result = true;
			return true;
		} );
		$status = $this->user->checkPasswordValidity( 'Password1234' );
		$this->assertStatusGood( $status );

		$this->clearHook( 'isValidPassword' );

		$this->setTemporaryHook( 'isValidPassword', static function ( $password, &$result, $user ) {
			$result = 'isValidPassword returned true';
			return true;
		} );
		$status = $this->user->checkPasswordValidity( 'Password1234' );
		$this->assertStatusWarning( 'isValidPassword returned true', $status );

		$this->clearHook( 'isValidPassword' );

		// On the forbidden list
		$user = User::newFromName( 'Useruser' );
		$status = $user->checkPasswordValidity( 'Passpass' );
		$this->assertStatusWarning( 'password-login-forbidden', $status );
	}

	/**
	 * @covers \MediaWiki\User\User::checkPasswordValidity
	 */
	public function testCheckPasswordValidityForTemporaryAccount() {
		$this->enableAutoCreateTempUser();
		$user = $this->getServiceContainer()->getTempUserCreator()
			->create( null, new FauxRequest() )->getUser();
		$this->assertStatusError(
			'error-temporary-accounts-cannot-have-passwords', $user->checkPasswordValidity( 'abc' )
		);
	}

	/**
	 * @covers \MediaWiki\User\User::equals
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
	 * @covers \MediaWiki\User\User::getId
	 * @covers \MediaWiki\User\User::setId
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
	 * @covers \MediaWiki\User\User::isRegistered
	 * @covers \MediaWiki\User\User::isAnon
	 * @covers \MediaWiki\User\User::logOut
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

		$this->clearHook( 'UserLogout' );
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
	 * @covers \MediaWiki\User\User::setRealName
	 * @covers \MediaWiki\User\User::getRealName
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
	 * @covers \MediaWiki\User\User::checkAndSetTouched
	 * @covers \MediaWiki\User\User::getDBTouched()
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
	 * @covers \MediaWiki\User\User::validateCache
	 * @covers \MediaWiki\User\User::getTouched
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
	 * @covers \MediaWiki\User\User::findUsersByGroup
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
	 * @covers \MediaWiki\User\User::getBlock
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

		// IP is in $wgSoftBlockRanges and user is temporary
		$this->enableAutoCreateTempUser();
		$user = ( new TestUser( '~1' ) )->getUser();
		$request = new FauxRequest();
		$request->setIP( '10.20.30.40' );
		$this->setSessionUser( $user, $request );
		$block = $user->getBlock();
		$this->assertTrue( $user->isTemp() );
		$this->assertInstanceOf( SystemBlock::class, $block );
		$this->assertSame( 'wgSoftBlockRanges', $block->getSystemBlockType() );

		// Make sure the block is really soft
		$request = new FauxRequest();
		$request->setIP( '10.20.30.40' );
		$this->setSessionUser( $this->user, $request );
		$this->assertFalse( $this->user->isAnon() );
		$this->assertNull( $this->user->getBlock() );
	}

	public static function provideIsPingLimitable() {
		yield 'Not ip excluded' => [ [], null, true ];
		yield 'Ip excluded' => [ [ '1.2.3.4' ], null, false ];
		yield 'Ip subnet excluded' => [ [ '1.2.3.0/8' ], null, false ];
		yield 'noratelimit right' => [ [], 'noratelimit', false ];
	}

	/**
	 * @dataProvider provideIsPingLimitable
	 * @covers \MediaWiki\User\User::isPingLimitable
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

	public static function provideExperienceLevel() {
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
	 * @covers \MediaWiki\User\User::getExperienceLevel
	 * @dataProvider provideExperienceLevel
	 */
	public function testExperienceLevel( $editCount, $memberSince, $expLevel ) {
		$this->overrideConfigValues( [
			MainConfigNames::LearnerEdits => 10,
			MainConfigNames::LearnerMemberSince => 4,
			MainConfigNames::ExperiencedUserEdits => 500,
			MainConfigNames::ExperiencedUserMemberSince => 30,
		] );

		$db = $this->getDb();
		$row = User::newQueryBuilder( $db )
			->where( [ 'user_id' => $this->user->getId() ] )
			->caller( __METHOD__ )
			->fetchRow();
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
	 * @covers \MediaWiki\User\User::getExperienceLevel
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
	 * @covers \MediaWiki\User\User::newFromId
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
	 * @covers \MediaWiki\User\User::newFromActorId
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

		$row = User::newQueryBuilder( $this->getDb() )
			->where( [ 'user_id' => $id ] )
			->caller( __METHOD__ )
			->fetchRow();
		$user = User::newFromRow( $row );
		$this->assertGreaterThan( 0, $user->getActorId(),
			'Actor ID can be retrieved for user loaded with User::selectFields()' );

		$user = User::newFromId( $id );
		$user->setName( 'UserTestActorId4-renamed' );
		$user->saveSettings();
		$this->assertSame(
			$user->getName(),
			$this->getDb()->newSelectQueryBuilder()
				->select( 'actor_name' )
				->from( 'actor' )
				->where( [ 'actor_id' => $user->getActorId() ] )
				->caller( __METHOD__ )->fetchField(),
			'User::saveSettings updates actor table for name change'
		);

		$ip = '192.168.12.34';
		$this->getDb()->newDeleteQueryBuilder()
			->deleteFrom( 'actor' )
			->where( [ 'actor_name' => $ip ] )
			->caller( __METHOD__ )
			->execute();

		// Next tests require disabling temp user feature.
		$this->disableAutoCreateTempUser();
		$user = User::newFromName( $ip, false );
		$this->assertSame( 0, $user->getActorId(), 'Anonymous user has no actor ID by default' );
		$this->filterDeprecated( '/Passing parameter of type IDatabase/' );
		$this->assertGreaterThan( 0, $user->getActorId( $this->getDb() ),
			'Actor ID can be created for an anonymous user' );

		$user = User::newFromName( $ip, false );
		$this->assertGreaterThan( 0, $user->getActorId(),
			'Actor ID can be loaded for an anonymous user' );
		$user2 = User::newFromActorId( $user->getActorId() );
		$this->assertSame( $user->getName(), $user2->getName(),
			'User::newFromActorId works for an anonymous user' );
	}

	/**
	 * @covers \MediaWiki\User\User::getActorId
	 */
	public function testForeignGetActorId() {
		$this->filterDeprecated( '/Passing a parameter to getActorId\(\) is deprecated/', '1.36' );

		$user = User::newFromName( 'UserTestActorId1' );
		$this->expectException( PreconditionException::class );
		$user->getActorId( 'Foreign Wiki' );
	}

	/**
	 * @covers \MediaWiki\User\User::getWikiId
	 */
	public function testGetWiki() {
		$user = User::newFromName( 'UserTestActorId1' );
		$this->assertSame( User::LOCAL, $user->getWikiId() );
	}

	/**
	 * @covers \MediaWiki\User\User::assertWiki
	 */
	public function testAssertWiki() {
		$user = User::newFromName( 'UserTestActorId1' );

		$user->assertWiki( User::LOCAL );
		$this->assertTrue( true, 'User is for local wiki' );

		$this->expectException( PreconditionException::class );
		$user->assertWiki( 'Foreign Wiki' );
	}

	/**
	 * @covers \MediaWiki\User\User::newFromAnyId
	 */
	public function testNewFromAnyId() {
		$this->disableAutoCreateTempUser();
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
		$this->getServiceContainer()->getActorNormalization()->acquireActorId( $user, $this->getDb() );

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
	 * @covers \MediaWiki\User\User::newFromIdentity
	 */
	public function testNewFromIdentity() {
		// Registered user
		$user = $this->user;

		$this->assertSame( $user, User::newFromIdentity( $user ) );

		// ID only
		$identity = new UserIdentityValue( $user->getId(), '' );
		$result = User::newFromIdentity( $identity );
		$this->assertInstanceOf( User::class, $result );
		$this->assertSame( $user->getId(), $result->getId(), 'ID' );
		$this->assertSame( $user->getName(), $result->getName(), 'Name' );
		$this->assertSame( $user->getActorId(), $result->getActorId(), 'Actor' );

		// Name only
		$identity = new UserIdentityValue( 0, $user->getName() );
		$result = User::newFromIdentity( $identity );
		$this->assertInstanceOf( User::class, $result );
		$this->assertSame( $user->getId(), $result->getId(), 'ID' );
		$this->assertSame( $user->getName(), $result->getName(), 'Name' );
		$this->assertSame( $user->getActorId(), $result->getActorId(), 'Actor' );
	}

	/**
	 * @covers \MediaWiki\User\User::newFromConfirmationCode
	 */
	public function testNewFromConfirmationCode() {
		$user = User::newFromConfirmationCode( 'NotARealConfirmationCode' );
		$this->assertNull(
			$user,
			'Invalid confirmation codes result in null users when reading from replicas'
		);

		$user = User::newFromConfirmationCode( 'OtherFakeCode', IDBAccessObject::READ_LATEST );
		$this->assertNull(
			$user,
			'Invalid confirmation codes result in null users when reading from master'
		);
	}

	/**
	 * @covers \MediaWiki\User\User::newFromName
	 * @covers \MediaWiki\User\User::getName
	 * @covers \MediaWiki\User\User::getUserPage
	 * @covers \MediaWiki\User\User::getTalkPage
	 * @covers \MediaWiki\User\User::getTitleKey
	 * @covers \MediaWiki\User\User::whoIs
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
		$this->hideDeprecated( User::class . '::whoIs' );
		$this->assertSame( $name, User::whoIs( $user->getId() ) );
	}

	public static function provideNewFromName() {
		return [
			[ 'Example1', 'Example1' ],
			[ 'MediaWiki easter egg', 'MediaWiki_easter_egg' ],
			[ 'See T22281 for more', 'See_T22281_for_more' ],
			[ 'DannyS712', 'DannyS712' ],
		];
	}

	/**
	 * @covers \MediaWiki\User\User::newFromName
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
	 * @covers \MediaWiki\User\User::newFromSession
	 * @covers \MediaWiki\User\User::getRequest
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
	 * @covers \MediaWiki\User\User::newFromRow
	 * @covers \MediaWiki\User\User::loadFromRow
	 */
	public function testNewFromRow() {
		// TODO: Create real tests here for loadFromRow
		$row = (object)[];
		$user = User::newFromRow( $row );
		$this->assertInstanceOf( User::class, $user, 'newFromRow returns a user object' );
	}

	/**
	 * @covers \MediaWiki\User\User::newFromRow
	 * @covers \MediaWiki\User\User::loadFromRow
	 */
	public function testNewFromRow_bad() {
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( '$row must be an object' );
		User::newFromRow( [] );
	}

	/**
	 * @covers \MediaWiki\User\User::getBlock
	 * @covers \MediaWiki\User\User::isHidden
	 */
	public function testBlockInstanceCache() {
		$this->hideDeprecated( User::class . '::isBlockedFrom' );
		// First, check the user isn't blocked
		$user = $this->getMutableTestUser()->getUser();
		$ut = Title::makeTitle( NS_USER_TALK, $user->getName() );
		$this->assertNull( $user->getBlock( false ) );
		$this->assertFalse( $user->isHidden() );

		// Block the user
		$blocker = $this->getTestSysop()->getUser();
		$blockStore = $this->getServiceContainer()->getDatabaseBlockStore();
		$block = $blockStore->insertBlockWithParams( [
			'targetUser' => $user,
			'hideName' => true,
			'allowUsertalk' => false,
			'reason' => 'Because',
			'by' => $blocker,
		] );
		$this->assertNotNull( $block, 'Failed to insert block' );

		// Clear cache and confirm it loaded the block properly
		$user->clearInstanceCache();
		$this->assertInstanceOf( DatabaseBlock::class, $user->getBlock( false ) );
		$this->assertTrue( $user->isHidden() );

		// Unblock
		$blockStore->deleteBlock( $block );

		// Clear cache and confirm it loaded the not-blocked properly
		$user->clearInstanceCache();
		$this->assertNull( $user->getBlock( false ) );
		$this->assertFalse( $user->isHidden() );
	}

	/**
	 * @covers \MediaWiki\User\User::getBlock
	 */
	public function testCompositeBlocks() {
		$user = $this->getMutableTestUser()->getUser();
		$request = $user->getRequest();
		$this->setSessionUser( $user, $request );

		$blockStore = $this->getServiceContainer()->getDatabaseBlockStore();
		$blockStore->insertBlockWithParams( [
			'address' => $user->getRequest()->getIP(),
			'by' => $this->getTestSysop()->getUser(),
			'createAccount' => true,
		] );

		$userBlock = $blockStore->insertBlockWithParams( [
			'targetUser' => $user,
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
	 * @covers \MediaWiki\User\User::getBlock
	 */
	public function testUserBlock() {
		$user = $this->getMutableTestUser()->getUser();
		$request = $user->getRequest();
		$this->setSessionUser( $user, $request );

		$this->getServiceContainer()->getDatabaseBlockStore()
			->insertBlockWithParams( [
				'targetUser' => $user,
				'by' => $this->getTestSysop()->getUser(),
				'createAccount' => true,
			] );

		$block = $user->getBlock();
		$this->assertNotNull( $block, 'getuserBlock' );
		$this->assertNotNull( $block->getTargetUserIdentity(), 'getTargetUserIdentity()' );
		$this->assertSame( $user->getName(), $block->getTargetUserIdentity()->getName() );
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
	 * @covers \MediaWiki\User\User::isBlockedFromEmailuser
	 * @covers \MediaWiki\User\User::isAllowedToCreateAccount
	 * @dataProvider provideIsBlockedFromAction
	 * @param bool $blockFromEmail Whether to block email access.
	 * @param bool $blockFromAccountCreation Whether to block account creation.
	 */
	public function testIsBlockedFromAction( $blockFromEmail, $blockFromAccountCreation ) {
		$this->hideDeprecated( User::class . '::isBlockedFromEmailuser' );
		$user = $this->getMutableTestUser( 'accountcreator' )->getUser();

		$this->getServiceContainer()->getDatabaseBlockStore()
			->insertBlockWithParams( [
				'targetUser' => $user,
				'expiry' => wfTimestamp( TS_MW, wfTimestamp() + ( 40 * 60 * 60 ) ),
				'sitewide' => true,
				'blockEmail' => $blockFromEmail,
				'createAccount' => $blockFromAccountCreation,
				'by' => $this->getTestSysop()->getUser()
			] );

		$this->assertSame( $blockFromEmail, $user->isBlockedFromEmailuser() );
		$this->assertSame( !$blockFromAccountCreation, $user->isAllowedToCreateAccount() );
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
	 * @covers \MediaWiki\User\User::isBlockedFromUpload
	 * @dataProvider provideIsBlockedFromUpload
	 * @param bool $sitewide Whether to block sitewide.
	 * @param bool $expected Whether the user is expected to be blocked from uploads.
	 */
	public function testIsBlockedFromUpload( $sitewide, $expected ) {
		$user = $this->getMutableTestUser()->getUser();

		$this->getServiceContainer()->getDatabaseBlockStore()
			->insertBlockWithParams( [
				'targetUser' => $user,
				'expiry' => wfTimestamp( TS_MW, wfTimestamp() + ( 40 * 60 * 60 ) ),
				'sitewide' => $sitewide,
				'by' => $this->getTestSysop()->getUser(),
			] );

		$this->assertSame( $expected, $user->isBlockedFromUpload() );
	}

	public static function provideIsBlockedFromUpload() {
		return [
			'sitewide blocks block uploads' => [ true, true ],
			'partial blocks allow uploads' => [ false, false ],
		];
	}

	/**
	 * @covers \MediaWiki\User\User::isSystemUser
	 */
	public function testIsSystemUser() {
		$this->assertFalse( $this->user->isSystemUser(), 'Normal users are not system users' );

		$user = User::newSystemUser( __METHOD__ );
		$this->assertTrue( $user->isSystemUser(), 'Users created with newSystemUser() are system users' );
	}

	/**
	 * @covers \MediaWiki\User\User::newSystemUser
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
				$this->getDb()->newInsertQueryBuilder()
					->insertInto( 'actor' )
					->row( [ 'actor_name' => $name ] )
					->caller( __METHOD__ )
					->execute();
				$actorId = (int)$this->getDb()->insertId();
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
			$this->setNullLogger( 'rdbms' );
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
	 * @covers \MediaWiki\User\User::getName
	 * @covers \MediaWiki\User\User::setName
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
	 * @covers \MediaWiki\User\User::getEmail
	 * @covers \MediaWiki\User\User::setEmail
	 * @covers \MediaWiki\User\User::invalidateEmail
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

		$this->clearHook( 'UserSetEmail' );

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

		$this->clearHook( 'UserGetEmail' );
		$this->clearHook( 'UserSetEmail' );

		$user->invalidateEmail();
		$this->assertSame(
			'',
			$user->getEmail(),
			'After invalidation, a user email should be an empty string'
		);
	}

	/**
	 * @covers \MediaWiki\User\User::setEmailWithConfirmation
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
		$this->assertStatusError( 'emaildisabled', $status,
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
	 * @covers \MediaWiki\User\User::isItemLoaded
	 * @covers \MediaWiki\User\User::setItemLoaded
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
	 * @covers \MediaWiki\User\User::requiresHTTPS
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
	 * @covers \MediaWiki\User\User::requiresHTTPS
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
	 * @covers \MediaWiki\User\User::requiresHTTPS
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
	 * @covers \MediaWiki\User\User::addToDatabase
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
	 * @covers \MediaWiki\User\User::pingLimiter
	 */
	public function testPingLimiter() {
		$user = $this->getTestUser()->getUser();

		$limiter = $this->createNoOpMock( RateLimiter::class, [ 'limit', 'isLimitable' ] );
		$limiter->method( 'isLimitable' )->willReturn( true );
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
	 * @covers \MediaWiki\User\User::loadFromDatabase
	 * @covers \MediaWiki\User\User::loadDefaults
	 */
	public function testBadUserID() {
		$user = User::newFromId( 999999999 );
		$this->assertSame( 'Unknown user', $user->getName() );
	}

	/**
	 * @covers \MediaWiki\User\User::probablyCan
	 * @covers \MediaWiki\User\User::definitelyCan
	 * @covers \MediaWiki\User\User::authorizeRead
	 * @covers \MediaWiki\User\User::authorizeWrite
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
	 * @covers \MediaWiki\User\User::isAllowed
	 * @covers \MediaWiki\User\User::__sleep
	 */
	public function testSerializationRoudTripWithAuthority() {
		$user = $this->getTestUser()->getUser();
		$isAllowed = $user->isAllowed( 'read' ); // Memoize the Authority
		$unserializedUser = unserialize( serialize( $user ) );
		$this->assertSame( $user->getId(), $unserializedUser->getId() );
		$this->assertSame( $isAllowed, $unserializedUser->isAllowed( 'read' ) );
	}

	public static function provideIsTemp() {
		return [
			[ '~2024-1', true ],
			[ '~1', true ],
			[ 'Some user', false ],
		];
	}

	/**
	 * @covers \MediaWiki\User\User::isTemp
	 * @dataProvider provideIsTemp
	 */
	public function testIsTemp( $name, $expected ) {
		$this->enableAutoCreateTempUser();
		$user = new User;
		$user->setName( $name );
		$this->assertSame( $expected, $user->isTemp() );
	}

	/**
	 * @covers \MediaWiki\User\User::isTemp
	 */
	public function testSetIsTempInLoadDefaults() {
		$this->enableAutoCreateTempUser();
		$user = new User();
		$user->loadDefaults();
		$this->assertSame( false, $user->isTemp() );
		$user->loadDefaults( '~2024-1' );
		$this->assertSame( true, $user->isTemp() );
	}

	/**
	 * @covers \MediaWiki\User\User::isNamed
	 */
	public function testIsNamed() {
		$this->enableAutoCreateTempUser();

		// Temp user is not named
		$user = new User;
		$user->setName( '~1' );
		$this->assertFalse( $user->isNamed() );

		// Registered user is named
		$user = $this->getMutableTestUser()->getUser();
		$this->assertTrue( $user->isNamed() );

		// Anon is not named
		$user = new User;
		$this->assertFalse( $user->isNamed() );
	}

	public static function provideAddToDatabase_temp() {
		return [
			[ '~1', '1' ],
			[ 'Some user', '0' ]
		];
	}

	/**
	 * @covers \MediaWiki\User\User::addToDatabase
	 * @dataProvider provideAddToDatabase_temp
	 */
	public function testAddToDatabase_temp( $name, $expected ) {
		$this->enableAutoCreateTempUser();

		$user = User::newFromName( $name );
		$user->addToDatabase();
		$field = $this->getDb()->newSelectQueryBuilder()
			->select( 'user_is_temp' )
			->from( 'user' )
			->where( [ 'user_name' => $name ] )
			->caller( __METHOD__ )
			->fetchField();

		$this->assertSame( $expected, $field );
	}

	/**
	 * @covers \MediaWiki\User\User::spreadAnyEditBlock
	 * @covers \MediaWiki\User\User::spreadBlock
	 */
	public function testSpreadAnyEditBlockForAnonUser() {
		$hookCalled = false;
		$this->setTemporaryHook( 'SpreadAnyEditBlock', static function () use ( &$hookCalled ){
			$hookCalled = true;
		} );
		$user = new User;
		$user->setName( '1.2.3.4' );
		$user->spreadAnyEditBlock();
		$this->assertFalse( $hookCalled );
	}

	/**
	 * @covers \MediaWiki\User\User::spreadAnyEditBlock
	 * @covers \MediaWiki\User\User::spreadBlock
	 * @dataProvider provideBlockWasSpreadValues
	 */
	public function testSpreadAnyEditBlockForUnblockedUser( $mockBlockWasSpreadHookValue ) {
		// Assert that the SpreadAnyEditBlock hook gets called with the right arguments when
		// ::spreadAnyEditBlock is called for a registered user.
		$hookCalled = false;
		$this->setTemporaryHook(
			'SpreadAnyEditBlock',
			function ( $user, &$blockWasSpread ) use ( &$hookCalled, $mockBlockWasSpreadHookValue ) {
				$hookCalled = true;
				$blockWasSpread = $mockBlockWasSpreadHookValue;
				$this->assertSame( $this->user, $user );
			}
		);
		$this->assertSame( $mockBlockWasSpreadHookValue, $this->user->spreadAnyEditBlock() );
		$this->assertTrue( $hookCalled );
	}

	public static function provideBlockWasSpreadValues() {
		return [
			'SpreadAnyEditBlock hook handler sets $blockWasSpread to true' => [ true ],
			'No SpreadAnyEditBlock hook handler spread a block' => [ false ],
		];
	}

	/**
	 * @covers \MediaWiki\User\User::spreadAnyEditBlock
	 * @covers \MediaWiki\User\User::spreadBlock
	 */
	public function testSpreadAnyEditBlockForBlockedUser() {
		$this->getServiceContainer()->getBlockUserFactory()->newBlockUser(
			$this->user, $this->getTestSysop()->getAuthority(), 'indefinite', '', [ 'isAutoblocking' => true ]
		)->placeBlockUnsafe();
		RequestContext::getMain()->getRequest()->setIP( '1.2.3.4' );
		$this->assertTrue( $this->user->spreadAnyEditBlock() );
		$this->assertNotNull( $this->getServiceContainer()->getBlockManager()->getIpBlock( '1.2.3.4', true ) );
	}

	/**
	 * @covers \MediaWiki\User\User::spreadAnyEditBlock
	 * @covers \MediaWiki\User\User::spreadBlock
	 */
	public function testSpreadAnyEditBlockWhenMultiblocked() {
		$this->overrideConfigValue( MainConfigNames::EnableMultiBlocks, true );
		$this->getServiceContainer()->getBlockUserFactory()->newBlockUser(
			$this->user, $this->getTestSysop()->getAuthority(), '1 day', '', [ 'isAutoblocking' => false ]
		)->placeBlockUnsafe();
		$this->getServiceContainer()->getBlockUserFactory()->newBlockUser(
			$this->user, $this->getTestSysop()->getAuthority(), 'indefinite', '', [ 'isAutoblocking' => true ]
		)->placeBlockUnsafe( BlockUser::CONFLICT_NEW );

		RequestContext::getMain()->getRequest()->setIP( '1.2.3.4' );
		$this->assertTrue( $this->user->spreadAnyEditBlock() );

		$autoblocks = $this->getServiceContainer()->getBlockManager()->getIpBlock( '1.2.3.4', true );
		$this->assertCount( 1, $autoblocks->toArray() );
	}
}
