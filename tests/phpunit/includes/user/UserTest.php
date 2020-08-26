<?php

define( 'NS_UNITTEST', 5600 );
define( 'NS_UNITTEST_TALK', 5601 );

use MediaWiki\Block\CompositeBlock;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\Restriction\NamespaceRestriction;
use MediaWiki\Block\Restriction\PageRestriction;
use MediaWiki\Block\SystemBlock;
use MediaWiki\MediaWikiServices;
use MediaWiki\User\UserIdentityValue;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Database
 */
class UserTest extends MediaWikiIntegrationTestCase {

	/** Constant for self::testIsBlockedFrom */
	private const USER_TALK_PAGE = '<user talk page>';

	/**
	 * @var User
	 */
	protected $user;

	protected function setUp() : void {
		parent::setUp();

		$this->setMwGlobals( [
			'wgGroupPermissions' => [],
			'wgRevokePermissions' => [],
			'wgUseRCPatrol' => true,
			'wgWatchlistExpiry' => true,
		] );

		$this->setUpPermissionGlobals();

		$this->user = $this->getTestUser( 'unittesters' )->getUser();
	}

	private function setUpPermissionGlobals() {
		global $wgGroupPermissions, $wgRevokePermissions;

		# Data for regular $wgGroupPermissions test
		$wgGroupPermissions['unittesters'] = [
			'test' => true,
			'runtest' => true,
			'writetest' => false,
			'nukeworld' => false,
			'autoconfirmed' => false,
		];
		$wgGroupPermissions['testwriters'] = [
			'test' => true,
			'writetest' => true,
			'modifytest' => true,
			'autoconfirmed' => true,
		];

		# Data for regular $wgRevokePermissions test
		$wgRevokePermissions['formertesters'] = [
			'runtest' => true,
		];

		# For the options and watchlist tests
		$wgGroupPermissions['*'] = [
			'editmyoptions' => true,
			'editmywatchlist' => true,
			'viewmywatchlist' => true,
		];

		# For patrol tests
		$wgGroupPermissions['patroller'] = [
			'patrol' => true,
		];

		# For account creation when blocked test
		$wgGroupPermissions['accountcreator'] = [
			'createaccount' => true,
			'ipblock-exempt' => true
		];

		# For bot and ratelimit tests
		$wgGroupPermissions['bot'] = [
			'bot' => true,
			'noratelimit' => true,
		];
	}

	private function setSessionUser( User $user, WebRequest $request ) {
		$this->setMwGlobals( 'wgUser', $user );
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
	 * TODO: Remove. This is the same as PermissionManagerTest::testGetUserPermissions
	 * @covers User::getRights
	 */
	public function testUserPermissions() {
		$rights = $this->user->getRights();
		$this->assertContains( 'runtest', $rights );
		$this->assertNotContains( 'writetest', $rights );
		$this->assertNotContains( 'modifytest', $rights );
		$this->assertNotContains( 'nukeworld', $rights );
	}

	/**
	 * TODO: Remove. This is the same as PermissionManagerTest::testGetUserPermissionsHooks
	 * @covers User::getRights
	 */
	public function testUserGetRightsHooks() {
		$user = $this->getTestUser( [ 'unittesters', 'testwriters' ] )->getUser();
		$userWrapper = TestingAccessWrapper::newFromObject( $user );

		$rights = $user->getRights();
		$this->assertContains( 'test', $rights, 'sanity check' );
		$this->assertContains( 'runtest', $rights, 'sanity check' );
		$this->assertContains( 'writetest', $rights, 'sanity check' );
		$this->assertNotContains( 'nukeworld', $rights, 'sanity check' );

		// Add a hook manipulating the rights
		$this->setTemporaryHook( 'UserGetRights', function ( $user, &$rights ) {
			$rights[] = 'nukeworld';
			$rights = array_diff( $rights, [ 'writetest' ] );
		} );

		MediaWikiServices::getInstance()->getPermissionManager()->invalidateUsersRightsCache( $user );
		$rights = $user->getRights();
		$this->assertContains( 'test', $rights );
		$this->assertContains( 'runtest', $rights );
		$this->assertNotContains( 'writetest', $rights );
		$this->assertContains( 'nukeworld', $rights );

		// Add a Session that limits rights
		$mock = $this->getMockBuilder( stdClass::class )
			->setMethods( [ 'getAllowedUserRights', 'deregisterSession', 'getSessionId' ] )
			->getMock();
		$mock->method( 'getAllowedUserRights' )->willReturn( [ 'test', 'writetest' ] );
		$mock->method( 'getSessionId' )->willReturn(
			new MediaWiki\Session\SessionId( str_repeat( 'X', 32 ) )
		);
		$session = MediaWiki\Session\TestUtils::getDummySession( $mock );
		$mockRequest = $this->getMockBuilder( FauxRequest::class )
			->setMethods( [ 'getSession' ] )
			->getMock();
		$mockRequest->method( 'getSession' )->willReturn( $session );
		$userWrapper->mRequest = $mockRequest;

		$this->resetServices();
		$rights = $user->getRights();
		$this->assertContains( 'test', $rights );
		$this->assertNotContains( 'runtest', $rights );
		$this->assertNotContains( 'writetest', $rights );
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
	 * @dataProvider provideIPs
	 * @covers User::isIP
	 */
	public function testIsIP( $value, $result, $message ) {
		$this->assertSame( $result, $this->user->isIP( $value ), $message );
	}

	public static function provideIPs() {
		return [
			[ '', false, 'Empty string' ],
			[ ' ', false, 'Blank space' ],
			[ '10.0.0.0', true, 'IPv4 private 10/8' ],
			[ '10.255.255.255', true, 'IPv4 private 10/8' ],
			[ '192.168.1.1', true, 'IPv4 private 192.168/16' ],
			[ '203.0.113.0', true, 'IPv4 example' ],
			[ '2002:ffff:ffff:ffff:ffff:ffff:ffff:ffff', true, 'IPv6 example' ],
			// Not valid IPs but classified as such by MediaWiki for negated asserting
			// of whether this might be the identifier of a logged-out user or whether
			// to allow usernames like it.
			[ '300.300.300.300', true, 'Looks too much like an IPv4 address' ],
			[ '203.0.113.xxx', true, 'Assigned by UseMod to cloaked logged-out users' ],
		];
	}

	/**
	 * @dataProvider provideUserNames
	 * @covers User::isValidUserName
	 */
	public function testIsValidUserName( $username, $result, $message ) {
		$this->assertSame( $result, $this->user->isValidUserName( $username ), $message );
	}

	public static function provideUserNames() {
		return [
			[ '', false, 'Empty string' ],
			[ ' ', false, 'Blank space' ],
			[ 'abcd', false, 'Starts with small letter' ],
			[ 'Ab/cd', false, 'Contains slash' ],
			[ 'Ab cd', true, 'Whitespace' ],
			[ '192.168.1.1', false, 'IP' ],
			[ '116.17.184.5/32', false, 'IP range' ],
			[ '::e:f:2001/96', false, 'IPv6 range' ],
			[ 'User:Abcd', false, 'Reserved Namespace' ],
			[ '12abcd232', true, 'Starts with Numbers' ],
			[ '?abcd', true, 'Start with ? mark' ],
			[ '#abcd', false, 'Start with #' ],
			[ 'Abcdകഖഗഘ', true, ' Mixed scripts' ],
			[ 'ജോസ്‌തോമസ്', false, 'ZWNJ- Format control character' ],
			[ 'Ab　cd', false, ' Ideographic space' ],
			[ '300.300.300.300', false, 'Looks too much like an IPv4 address' ],
			[ '302.113.311.900', false, 'Looks too much like an IPv4 address' ],
			[ '203.0.113.xxx', false, 'Reserved for usage by UseMod for cloaked logged-out users' ],
		];
	}

	/**
	 * Test User::editCount
	 * @group medium
	 * @covers User::getEditCount
	 * @covers User::setEditCountInternal
	 */
	public function testGetEditCount() {
		$user = $this->getMutableTestUser()->getUser();

		// let the user have a few (3) edits
		$page = WikiPage::factory( Title::newFromText( 'Help:UserTest_EditCount' ) );
		for ( $i = 0; $i < 3; $i++ ) {
			$page->doEditContent(
				ContentHandler::makeContent( (string)$i, $page->getTitle() ),
				'test',
				0,
				false,
				$user
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

		// Update the edit count
		$user->setEditCountInternal( 42 );
		$this->assertSame(
			42,
			$user->getEditCount(),
			'After setting the edit count manually, the user edit count should be 42'
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
	 * Test changing user options.
	 * @covers User::setOption
	 * @covers User::getOptions
	 * @covers User::getBoolOption
	 * @covers User::getIntOption
	 * @covers User::getStubThreshold
	 */
	public function testOptions() {
		$this->setMwGlobals( [
			'wgMaxArticleSize' => 2,
		] );
		$user = $this->getMutableTestUser()->getUser();

		$user->setOption( 'userjs-someoption', 'test' );
		$user->setOption( 'userjs-someintoption', '42' );
		$user->setOption( 'rclimit', 200 );
		$user->setOption( 'wpwatchlistdays', '0' );
		$user->setOption( 'stubthreshold', 1024 );
		$user->setOption( 'userjs-usedefaultoverride', '' );
		$user->saveSettings();

		MediaWikiServices::getInstance()->getUserOptionsManager()->clearUserOptionsCache( $user );
		$this->assertSame( 'test', $user->getOption( 'userjs-someoption' ) );
		$this->assertTrue( $user->getBoolOption( 'userjs-someoption' ) );
		$this->assertEquals( 200, $user->getOption( 'rclimit' ) );
		$this->assertSame( 42, $user->getIntOption( 'userjs-someintoption' ) );
		$this->assertSame(
			123,
			$user->getIntOption( 'userjs-usedefaultoverride', 123 ),
			'Int options that are empty string can have a default returned'
		);
		$this->assertSame(
			1024,
			$user->getStubThreshold(),
			'Valid stub threshold preferences are respected'
		);

		MediaWikiServices::getInstance()->getUserOptionsManager()->clearUserOptionsCache( $user );
		MediaWikiServices::getInstance()->getMainWANObjectCache()->clearProcessCache();
		$this->assertSame( 'test', $user->getOption( 'userjs-someoption' ) );
		$this->assertTrue( $user->getBoolOption( 'userjs-someoption' ) );
		$this->assertEquals( 200, $user->getOption( 'rclimit' ) );
		$this->assertSame( 42, $user->getIntOption( 'userjs-someintoption' ) );
		$this->assertSame(
			0,
			$user->getIntOption( 'userjs-usedefaultoverride' ),
			'Int options that are empty string and have no default specified default to 0'
		);
		$this->assertSame(
			1024,
			$user->getStubThreshold(),
			'Valid stub threshold preferences are respected after cache is cleared'
		);

		// Check that an option saved as a string '0' is returned as an integer.
		MediaWikiServices::getInstance()->getUserOptionsManager()->clearUserOptionsCache( $user );
		$this->assertSame( 0, $user->getOption( 'wpwatchlistdays' ) );
		$this->assertFalse( $user->getBoolOption( 'wpwatchlistdays' ) );

		// Check that getStubThreashold resorts to 0 if invalid
		$user->setOption( 'stubthreshold', 4096 );
		$user->saveSettings();
		$this->assertSame(
			0,
			$user->getStubThreshold(),
			'If a stub threashold is impossible, it defaults to 0'
		);
	}

	/**
	 * T39963
	 * Make sure defaults are loaded when setOption is called.
	 * @covers User::setOption
	 */
	public function testAnonOptions() {
		global $wgDefaultUserOptions;
		$this->user->setOption( 'userjs-someoption', 'test' );
		$this->assertSame( $wgDefaultUserOptions['rclimit'], $this->user->getOption( 'rclimit' ) );
		$this->assertSame( 'test', $this->user->getOption( 'userjs-someoption' ) );
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
		$this->setMwGlobals( [
			'wgPasswordPolicy' => [
				'policies' => [
					'sysop' => [
						'MinimalPasswordLength' => 8,
						'MinimumPasswordLengthToLogin' => 1,
						'PasswordCannotMatchUsername' => 1,
						'PasswordCannotBeSubstringInUsername' => 1,
					],
					'default' => [
						'MinimalPasswordLength' => 6,
						'PasswordCannotMatchUsername' => true,
						'PasswordCannotBeSubstringInUsername' => true,
						'PasswordCannotMatchDefaults' => true,
						'MaximalPasswordLength' => 40,
					],
				],
				'checks' => [
					'MinimalPasswordLength' => 'PasswordPolicyChecks::checkMinimalPasswordLength',
					'MinimumPasswordLengthToLogin' => 'PasswordPolicyChecks::checkMinimumPasswordLengthToLogin',
					'PasswordCannotMatchUsername' => 'PasswordPolicyChecks::checkPasswordCannotMatchUsername',
					'PasswordCannotBeSubstringInUsername' =>
						'PasswordPolicyChecks::checkPasswordCannotBeSubstringInUsername',
					'PasswordCannotMatchDefaults' => 'PasswordPolicyChecks::checkPasswordCannotMatchDefaults',
					'MaximalPasswordLength' => 'PasswordPolicyChecks::checkMaximalPasswordLength',
				],
			],
		] );

		// Sanity
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

		$this->setTemporaryHook( 'isValidPassword', function ( $password, &$result, $user ) {
			$result = 'isValidPassword returned false';
			return false;
		} );
		$status = $this->user->checkPasswordValidity( 'Password1234' );
		$this->assertTrue( $status->isOK() );
		$this->assertFalse( $status->isGood() );
		$this->assertSame( $status->getErrors()[0]['message'], 'isValidPassword returned false' );

		$this->removeTemporaryHook( 'isValidPassword' );

		$this->setTemporaryHook( 'isValidPassword', function ( $password, &$result, $user ) {
			$result = true;
			return true;
		} );
		$status = $this->user->checkPasswordValidity( 'Password1234' );
		$this->assertTrue( $status->isOK() );
		$this->assertTrue( $status->isGood() );
		$this->assertSame( [], $status->getErrors() );

		$this->removeTemporaryHook( 'isValidPassword' );

		$this->setTemporaryHook( 'isValidPassword', function ( $password, &$result, $user ) {
			$result = 'isValidPassword returned true';
			return true;
		} );
		$status = $this->user->checkPasswordValidity( 'Password1234' );
		$this->assertTrue( $status->isOK() );
		$this->assertFalse( $status->isGood() );
		$this->assertSame( $status->getErrors()[0]['message'], 'isValidPassword returned true' );

		$this->removeTemporaryHook( 'isValidPassword' );

		// On the forbidden list
		$user = User::newFromName( 'Useruser' );
		$this->assertFalse( $user->checkPasswordValidity( 'Passpass' )->isGood() );
	}

	/**
	 * @covers User::getCanonicalName()
	 * @dataProvider provideGetCanonicalName
	 */
	public function testGetCanonicalName( $name, array $expectedArray ) {
		// fake interwiki map for the 'Interwiki prefix' testcase
		$this->mergeMwGlobalArrayValue( 'wgHooks', [
			'InterwikiLoadPrefix' => [
				function ( $prefix, &$iwdata ) {
					if ( $prefix === 'interwiki' ) {
						$iwdata = [
							'iw_url' => 'http://example.com/',
							'iw_local' => 0,
							'iw_trans' => 0,
						];
						return false;
					}
				},
			],
		] );

		foreach ( $expectedArray as $validate => $expected ) {
			$this->assertSame(
				$expected,
				User::getCanonicalName( $name, $validate === 'false' ? false : $validate ),
				$validate
			);
		}
	}

	public static function provideGetCanonicalName() {
		return [
			'Leading space' => [ ' Leading space', [ 'creatable' => 'Leading space' ] ],
			'Trailing space ' => [ 'Trailing space ', [ 'creatable' => 'Trailing space' ] ],
			'Namespace prefix' => [ 'Talk:Username', [ 'creatable' => false, 'usable' => false,
				'valid' => false, 'false' => 'Talk:Username' ] ],
			'Interwiki prefix' => [ 'interwiki:Username', [ 'creatable' => false, 'usable' => false,
				'valid' => false, 'false' => 'Interwiki:Username' ] ],
			'With hash' => [ 'name with # hash', [ 'creatable' => false, 'usable' => false ] ],
			'Multi spaces' => [ 'Multi  spaces', [ 'creatable' => 'Multi spaces',
				'usable' => 'Multi spaces' ] ],
			'Lowercase' => [ 'lowercase', [ 'creatable' => 'Lowercase' ] ],
			'Invalid character' => [ 'in[]valid', [ 'creatable' => false, 'usable' => false,
				'valid' => false, 'false' => 'In[]valid' ] ],
			'With slash' => [ 'with / slash', [ 'creatable' => false, 'usable' => false, 'valid' => false,
				'false' => 'With / slash' ] ],
		];
	}

	/**
	 * @covers User::getCanonicalName()
	 */
	public function testGetCanonicalName_bad() {
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage(
			'Invalid parameter value for validation'
		);
		User::getCanonicalName( 'ValidName', 'InvalidValidationValue' );
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
	 * @covers User::isLoggedIn
	 * @covers User::isAnon
	 * @covers User::logOut
	 */
	public function testLoggedIn() {
		$user = $this->getMutableTestUser()->getUser();
		$this->assertTrue( $user->isRegistered() );
		$this->assertTrue( $user->isLoggedIn() );
		$this->assertFalse( $user->isAnon() );

		$this->setTemporaryHook( 'UserLogout', function ( &$user ) {
			return false;
		} );
		$user->logout();
		$this->assertTrue( $user->isLoggedIn() );

		$this->removeTemporaryHook( 'UserLogout' );
		$user->logout();
		$this->assertFalse( $user->isLoggedIn() );

		// Non-existent users are perceived as anonymous
		$user = User::newFromName( 'UTNonexistent' );
		$this->assertFalse( $user->isRegistered() );
		$this->assertFalse( $user->isLoggedIn() );
		$this->assertTrue( $user->isAnon() );

		$user = new User;
		$this->assertFalse( $user->isRegistered() );
		$this->assertFalse( $user->isLoggedIn() );
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
			$user->getRealName(),
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
		$this->assertTrue( $user->isLoggedIn() );

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
		// FIXME: fails under postgres
		$this->markTestSkippedIfDbType( 'postgres' );

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
	 * When a user is autoblocked a cookie is set with which to track them
	 * in case they log out and change IP addresses.
	 * @link https://phabricator.wikimedia.org/T5233
	 * @covers User::trackBlockWithCookie
	 */
	public function testAutoblockCookies() {
		// Set up the bits of global configuration that we use.
		$this->setMwGlobals( [
			'wgCookieSetOnAutoblock' => true,
			'wgCookiePrefix' => 'wmsitetitle',
			'wgSecretKey' => MWCryptRand::generateHex( 64 ),
		] );

		// Unregister the hooks for proper unit testing
		$this->mergeMwGlobalArrayValue( 'wgHooks', [
			'PerformRetroactiveAutoblock' => []
		] );

		$blockManager = MediaWikiServices::getInstance()->getBlockManager();

		// 1. Log in a test user, and block them.
		$request1 = new FauxRequest();
		$request1->getSession()->setUser( $this->user );
		$expiryFiveHours = wfTimestamp() + ( 5 * 60 * 60 );
		$block = new DatabaseBlock( [
			'enableAutoblock' => true,
			'expiry' => wfTimestamp( TS_MW, $expiryFiveHours ),
		] );
		$block->setBlocker( $this->getTestSysop()->getUser() );
		$block->setTarget( $this->user );
		$res = $block->insert();
		$this->assertTrue( (bool)$res['id'], 'Failed to insert block' );
		$user1 = User::newFromSession( $request1 );
		$user1->load();
		$blockManager->trackBlockWithCookie( $user1, $request1->response() );

		// Confirm that the block has been applied as required.
		$this->assertTrue( $user1->isLoggedIn() );
		$this->assertInstanceOf( DatabaseBlock::class, $user1->getBlock() );
		$this->assertSame( DatabaseBlock::TYPE_USER, $block->getType() );
		$this->assertTrue( $block->isAutoblocking() );
		$this->assertGreaterThanOrEqual( 1, $block->getId() );

		// Test for the desired cookie name, value, and expiry.
		$cookies = $request1->response()->getCookies();
		$this->assertArrayHasKey( 'wmsitetitleBlockID', $cookies );
		$this->assertSame( $expiryFiveHours, $cookies['wmsitetitleBlockID']['expire'] );
		$cookieId = $blockManager->getIdFromCookieValue(
			$cookies['wmsitetitleBlockID']['value']
		);
		$this->assertSame( $block->getId(), $cookieId );

		// 2. Create a new request, set the cookies, and see if the (anon) user is blocked.
		$request2 = new FauxRequest();
		$request2->setCookie( 'BlockID', $blockManager->getCookieValue( $block ) );
		$user2 = User::newFromSession( $request2 );
		$user2->load();
		$blockManager->trackBlockWithCookie( $user2, $request2->response() );
		$this->assertNotEquals( $user1->getId(), $user2->getId() );
		$this->assertNotEquals( $user1->getToken(), $user2->getToken() );
		$this->assertTrue( $user2->isAnon() );
		$this->assertFalse( $user2->isLoggedIn() );
		$this->assertInstanceOf( DatabaseBlock::class, $user2->getBlock() );
		$this->assertTrue( (bool)$user2->getBlock()->isAutoblocking(), 'Autoblock does not work' );
		// Can't directly compare the objects because of member type differences.
		// One day this will work: $this->assertEquals( $block, $user2->getBlock() );
		$this->assertSame( $block->getId(), $user2->getBlock()->getId() );
		$this->assertSame( $block->getExpiry(), $user2->getBlock()->getExpiry() );

		// 3. Finally, set up a request as a new user, and the block should still be applied.
		$request3 = new FauxRequest();
		$request3->getSession()->setUser( $this->user );
		$request3->setCookie( 'BlockID', $block->getId() );
		$user3 = User::newFromSession( $request3 );
		$user3->load();
		$blockManager->trackBlockWithCookie( $user3, $request3->response() );
		$this->assertTrue( $user3->isLoggedIn() );
		$this->assertInstanceOf( DatabaseBlock::class, $user3->getBlock() );
		$this->assertTrue( (bool)$user3->getBlock()->isAutoblocking() );

		// Clean up.
		$block->delete();
	}

	/**
	 * Make sure that no cookie is set to track autoblocked users
	 * when $wgCookieSetOnAutoblock is false.
	 * @covers User::trackBlockWithCookie
	 */
	public function testAutoblockCookiesDisabled() {
		// Set up the bits of global configuration that we use.
		$this->setMwGlobals( [
			'wgCookieSetOnAutoblock' => false,
			'wgCookiePrefix' => 'wm_no_cookies',
			'wgSecretKey' => MWCryptRand::generateHex( 64 ),
		] );

		// Unregister the hooks for proper unit testing
		$this->mergeMwGlobalArrayValue( 'wgHooks', [
			'PerformRetroactiveAutoblock' => []
		] );

		// 1. Log in a test user, and block them.
		$request1 = new FauxRequest();
		$request1->getSession()->setUser( $this->user );
		$block = new DatabaseBlock( [ 'enableAutoblock' => true ] );
		$block->setBlocker( $this->getTestSysop()->getUser() );
		$block->setTarget( $this->user );
		$res = $block->insert();
		$this->assertTrue( (bool)$res['id'], 'Failed to insert block' );
		$user = User::newFromSession( $request1 );
		$user->load();
		MediaWikiServices::getInstance()->getBlockManager()
			->trackBlockWithCookie( $user, $request1->response() );

		// 2. Test that the cookie IS NOT present.
		$this->assertTrue( $user->isLoggedIn() );
		$this->assertInstanceOf( DatabaseBlock::class, $user->getBlock() );
		$this->assertSame( DatabaseBlock::TYPE_USER, $block->getType() );
		$this->assertTrue( $block->isAutoblocking() );
		$this->assertGreaterThanOrEqual( 1, $user->getBlockId() );
		$this->assertGreaterThanOrEqual( $block->getId(), $user->getBlockId() );
		$cookies = $request1->response()->getCookies();
		$this->assertArrayNotHasKey( 'wm_no_cookiesBlockID', $cookies );

		// Clean up.
		$block->delete();
	}

	/**
	 * When a user is autoblocked and a cookie is set to track them, the expiry time of the cookie
	 * should match the block's expiry, to a maximum of 24 hours. If the expiry time is changed,
	 * the cookie's should change with it.
	 * @covers User::trackBlockWithCookie
	 */
	public function testAutoblockCookieInfiniteExpiry() {
		$this->setMwGlobals( [
			'wgCookieSetOnAutoblock' => true,
			'wgCookiePrefix' => 'wm_infinite_block',
			'wgSecretKey' => MWCryptRand::generateHex( 64 ),
		] );

		// Unregister the hooks for proper unit testing
		$this->mergeMwGlobalArrayValue( 'wgHooks', [
			'PerformRetroactiveAutoblock' => []
		] );

		// 1. Log in a test user, and block them indefinitely.
		$request1 = new FauxRequest();
		$request1->getSession()->setUser( $this->user );
		$block = new DatabaseBlock( [ 'enableAutoblock' => true, 'expiry' => 'infinity' ] );
		$block->setBlocker( $this->getTestSysop()->getUser() );
		$block->setTarget( $this->user );
		$res = $block->insert();
		$this->assertTrue( (bool)$res['id'], 'Failed to insert block' );
		$user1 = User::newFromSession( $request1 );
		$user1->load();
		MediaWikiServices::getInstance()->getBlockManager()
			->trackBlockWithCookie( $user1, $request1->response() );

		// 2. Test the cookie's expiry timestamp.
		$this->assertTrue( $user1->isLoggedIn() );
		$this->assertInstanceOf( DatabaseBlock::class, $user1->getBlock() );
		$this->assertSame( DatabaseBlock::TYPE_USER, $block->getType() );
		$this->assertTrue( $block->isAutoblocking() );
		$this->assertGreaterThanOrEqual( 1, $user1->getBlockId() );
		$cookies = $request1->response()->getCookies();
		// Test the cookie's expiry to the nearest minute.
		$this->assertArrayHasKey( 'wm_infinite_blockBlockID', $cookies );
		$expOneDay = wfTimestamp() + ( 24 * 60 * 60 );
		// Check for expiry dates in a 10-second window, to account for slow testing.
		$this->assertEqualsWithDelta(
			$expOneDay,
			$cookies['wm_infinite_blockBlockID']['expire'],
			5.0,
			'Expiry date'
		);

		// 3. Change the block's expiry (to 2 hours), and the cookie's should be changed also.
		$newExpiry = wfTimestamp() + 2 * 60 * 60;
		$block->setExpiry( wfTimestamp( TS_MW, $newExpiry ) );
		$block->update();
		$request2 = new FauxRequest();
		$request2->getSession()->setUser( $this->user );
		$user2 = User::newFromSession( $request2 );
		$user2->load();
		MediaWikiServices::getInstance()->getBlockManager()
			->trackBlockWithCookie( $user2, $request2->response() );
		$cookies = $request2->response()->getCookies();
		$this->assertSame( wfTimestamp( TS_MW, $newExpiry ), $block->getExpiry() );
		$this->assertSame( $newExpiry, $cookies['wm_infinite_blockBlockID']['expire'] );

		// Clean up.
		$block->delete();
	}

	/**
	 * @covers User::getBlockedStatus
	 */
	public function testSoftBlockRanges() {
		$this->setMwGlobals( 'wgSoftBlockRanges', [ '10.0.0.0/8' ] );

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
		$this->assertFalse( $this->user->isAnon(), 'sanity check' );
		$this->assertNull( $this->user->getBlock() );
	}

	/**
	 * Test that a modified BlockID cookie doesn't actually load the relevant block (T152951).
	 * @covers User::trackBlockWithCookie
	 */
	public function testAutoblockCookieInauthentic() {
		// Set up the bits of global configuration that we use.
		$this->setMwGlobals( [
			'wgCookieSetOnAutoblock' => true,
			'wgCookiePrefix' => 'wmsitetitle',
			'wgSecretKey' => MWCryptRand::generateHex( 64 ),
		] );

		// Unregister the hooks for proper unit testing
		$this->mergeMwGlobalArrayValue( 'wgHooks', [
			'PerformRetroactiveAutoblock' => []
		] );

		// 1. Log in a blocked test user.
		$request1 = new FauxRequest();
		$request1->getSession()->setUser( $this->user );
		$block = new DatabaseBlock( [ 'enableAutoblock' => true ] );
		$block->setBlocker( $this->getTestSysop()->getUser() );
		$block->setTarget( $this->user );
		$res = $block->insert();
		$this->assertTrue( (bool)$res['id'], 'Failed to insert block' );

		// 2. Create a new request, set the cookie to an invalid value, and make sure the (anon)
		// user not blocked.
		$request2 = new FauxRequest();
		$request2->setCookie( 'BlockID', $block->getId() . '!zzzzzzz' );
		$user2 = User::newFromSession( $request2 );
		$user2->load();
		$this->assertTrue( $user2->isAnon() );
		$this->assertFalse( $user2->isLoggedIn() );
		$this->assertNull( $user2->getBlock() );

		// Clean up.
		$block->delete();
	}

	/**
	 * The BlockID cookie is normally verified with a HMAC, but not if wgSecretKey is not set.
	 * This checks that a non-authenticated cookie still works.
	 * @covers User::trackBlockWithCookie
	 */
	public function testAutoblockCookieNoSecretKey() {
		// Set up the bits of global configuration that we use.
		$this->setMwGlobals( [
			'wgCookieSetOnAutoblock' => true,
			'wgCookiePrefix' => 'wmsitetitle',
			'wgSecretKey' => null,
		] );

		// Unregister the hooks for proper unit testing
		$this->mergeMwGlobalArrayValue( 'wgHooks', [
			'PerformRetroactiveAutoblock' => []
		] );

		// 1. Log in a blocked test user.
		$request1 = new FauxRequest();
		$request1->getSession()->setUser( $this->user );
		$block = new DatabaseBlock( [ 'enableAutoblock' => true ] );
		$block->setBlocker( $this->getTestSysop()->getUser() );
		$block->setTarget( $this->user );
		$res = $block->insert();
		$this->assertTrue( (bool)$res['id'], 'Failed to insert block' );
		$user1 = User::newFromSession( $request1 );
		$user1->load();
		$this->assertInstanceOf( DatabaseBlock::class, $user1->getBlock() );

		// 2. Create a new request, set the cookie to just the block ID, and the user should
		// still get blocked when they log in again.
		$request2 = new FauxRequest();
		$request2->setCookie( 'BlockID', $block->getId() );
		$user2 = User::newFromSession( $request2 );
		$user2->load();
		$this->assertNotEquals( $user1->getId(), $user2->getId() );
		$this->assertNotEquals( $user1->getToken(), $user2->getToken() );
		$this->assertTrue( $user2->isAnon() );
		$this->assertFalse( $user2->isLoggedIn() );
		$this->assertInstanceOf( DatabaseBlock::class, $user2->getBlock() );
		$this->assertTrue( (bool)$user2->getBlock()->isAutoblocking() );

		// Clean up.
		$block->delete();
	}

	/**
	 * @covers User::isPingLimitable
	 */
	public function testIsPingLimitable() {
		$request = new FauxRequest();
		$request->setIP( '1.2.3.4' );
		$user = User::newFromSession( $request );

		$this->setMwGlobals( 'wgRateLimitsExcludedIPs', [] );
		$this->assertTrue( $user->isPingLimitable() );

		$this->setMwGlobals( 'wgRateLimitsExcludedIPs', [ '1.2.3.4' ] );
		$this->assertFalse( $user->isPingLimitable() );

		$this->setMwGlobals( 'wgRateLimitsExcludedIPs', [ '1.2.3.0/8' ] );
		$this->assertFalse( $user->isPingLimitable() );

		$this->setMwGlobals( 'wgRateLimitsExcludedIPs', [] );
		$this->overrideUserPermissions( $user, 'noratelimit' );
		$this->assertFalse( $user->isPingLimitable() );
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
		];
	}

	/**
	 * @covers User::getExperienceLevel
	 * @dataProvider provideExperienceLevel
	 */
	public function testExperienceLevel( $editCount, $memberSince, $expLevel ) {
		$this->setMwGlobals( [
			'wgLearnerEdits' => 10,
			'wgLearnerMemberSince' => 4,
			'wgExperiencedUserEdits' => 500,
			'wgExperiencedUserMemberSince' => 30,
		] );

		$db = wfGetDB( DB_MASTER );
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
		$row->user_registration = $db->timestamp( time() - $memberSince * 86400 );
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
	 * @dataProvider provideIsLocallyBlockedProxy
	 * @covers User::isLocallyBlockedProxy
	 */
	public function testIsLocallyBlockedProxy( $ip, $blockListEntry ) {
		$this->hideDeprecated( 'User::isLocallyBlockedProxy' );

		$this->setMwGlobals(
			'wgProxyList', []
		);
		$this->assertFalse( User::isLocallyBlockedProxy( $ip ) );

		$this->setMwGlobals(
			'wgProxyList',
			[
				$blockListEntry
			]
		);
		$this->assertTrue( User::isLocallyBlockedProxy( $ip ) );

		$this->setMwGlobals(
			'wgProxyList',
			[
				'test' => $blockListEntry
			]
		);
		$this->assertTrue( User::isLocallyBlockedProxy( $ip ) );
	}

	/**
	 * @covers User::newFromId
	 */
	public function testNewFromId() {
		$userId = $this->user->getId();
		$this->assertGreaterThan(
			0,
			$userId,
			'Sanity check: user has a working id'
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

		// For sanity
		$ip = '192.168.12.34';
		$this->db->delete( 'actor', [ 'actor_name' => $ip ], __METHOD__ );

		$user = User::newFromName( $ip, false );
		$this->assertSame( 0, $user->getActorId(), 'Anonymous user has no actor ID by default' );
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
		$user->getActorId( $this->db ); // Make sure an actor ID exists

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

		// Actor only
		$identity = new UserIdentityValue( 0, '', $user->getActorId() );
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
		$this->assertTrue( $status->isOK(), 'User can be added to the database' );
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
		$this->setMwGlobals( [
			'wgRequest' => $req1,
		] );
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
			'Sanity check: passing a request that does not match $wgRequest'
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
		// First, check the user isn't blocked
		$user = $this->getMutableTestUser()->getUser();
		$ut = Title::makeTitle( NS_USER_TALK, $user->getName() );
		$this->assertNull( $user->getBlock( false ), 'sanity check' );
		$this->assertSame( '', $user->blockedBy(), 'sanity check' );
		$this->assertSame( '', $user->blockedFor(), 'sanity check' );
		$this->assertFalse( $user->isHidden(), 'sanity check' );
		$this->assertFalse( $user->isBlockedFrom( $ut ), 'sanity check' );

		// Block the user
		$blocker = $this->getTestSysop()->getUser();
		$block = new DatabaseBlock( [
			'hideName' => true,
			'allowUsertalk' => false,
			'reason' => 'Because',
		] );
		$block->setTarget( $user );
		$block->setBlocker( $blocker );
		$res = $block->insert();
		$this->assertTrue( (bool)$res['id'], 'sanity check: Failed to insert block' );

		// Clear cache and confirm it loaded the block properly
		$user->clearInstanceCache();
		$this->assertInstanceOf( DatabaseBlock::class, $user->getBlock( false ) );
		$this->assertSame( $blocker->getName(), $user->blockedBy() );
		$this->assertSame( 'Because', $user->blockedFor() );
		$this->assertTrue( $user->isHidden() );
		$this->assertTrue( $user->isBlockedFrom( $ut ) );
		$this->assertSame( $res['id'], $user->getBlockId() );

		// Unblock
		$block->delete();

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
		$user = $this->getMutableTestUser()->getUser();
		$request = $user->getRequest();
		$this->setSessionUser( $user, $request );

		$ipBlock = new Block( [
			'address' => $user->getRequest()->getIP(),
			'by' => $this->getTestSysop()->getUser()->getId(),
			'createAccount' => true,
		] );
		$ipBlock->insert();

		$userBlock = new Block( [
			'address' => $user,
			'by' => $this->getTestSysop()->getUser()->getId(),
			'createAccount' => false,
		] );
		$userBlock->insert();

		$block = $user->getBlock();
		$this->assertInstanceOf( CompositeBlock::class, $block );
		$this->assertTrue( $block->isCreateAccountBlocked() );
		$this->assertTrue( $block->appliesToPasswordReset() );
		$this->assertTrue( $block->appliesToNamespace( NS_MAIN ) );
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
		$this->setMwGlobals( [
			'wgBlockAllowsUTEdit' => $options['blockAllowsUTEdit'] ?? true,
		] );

		$user = $this->user;

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
		$block->insert();

		try {
			$this->assertSame( $expect, $user->isBlockedFrom( $title ) );
		} finally {
			$block->delete();
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
		$user = $this->getTestUser( 'accountcreator' )->getUser();

		$block = new DatabaseBlock( [
			'expiry' => wfTimestamp( TS_MW, wfTimestamp() + ( 40 * 60 * 60 ) ),
			'sitewide' => true,
			'blockEmail' => $blockFromEmail,
			'createAccount' => $blockFromAccountCreation
		] );
		$block->setTarget( $user );
		$block->setBlocker( $this->getTestSysop()->getUser() );
		$block->insert();

		try {
			$this->assertSame( $blockFromEmail, $user->isBlockedFromEmailuser() );
			$this->assertSame( !$blockFromAccountCreation, $user->isAllowedToCreateAccount() );
		} finally {
			$block->delete();
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
		$block = new DatabaseBlock( [
			'expiry' => wfTimestamp( TS_MW, wfTimestamp() + ( 40 * 60 * 60 ) ),
			'sitewide' => $sitewide,
		] );
		$block->setTarget( $this->user );
		$block->setBlocker( $this->getTestSysop()->getUser() );
		$block->insert();

		try {
			$this->assertSame( $expected, $this->user->isBlockedFromUpload() );
		} finally {
			$block->delete();
		}
	}

	public static function provideIsBlockedFromUpload() {
		return [
			'sitewide blocks block uploads' => [ true, true ],
			'partial blocks allow uploads' => [ false, false ],
		];
	}

	/**
	 * Block cookie should be set for IP Blocks if
	 * wgCookieSetOnIpBlock is set to true
	 * @covers User::trackBlockWithCookie
	 */
	public function testIpBlockCookieSet() {
		$this->setMwGlobals( [
			'wgCookieSetOnIpBlock' => true,
			'wgCookiePrefix' => 'wiki',
			'wgSecretKey' => MWCryptRand::generateHex( 64 ),
		] );

		// setup block
		$block = new DatabaseBlock( [
			'expiry' => wfTimestamp( TS_MW, wfTimestamp() + ( 5 * 60 * 60 ) ),
		] );
		$block->setTarget( '1.2.3.4' );
		$block->setBlocker( $this->getTestSysop()->getUser() );
		$block->insert();

		// setup request
		$request = new FauxRequest();
		$request->setIP( '1.2.3.4' );

		// get user
		$user = User::newFromSession( $request );
		MediaWikiServices::getInstance()->getBlockManager()
			->trackBlockWithCookie( $user, $request->response() );

		// test cookie was set
		$cookies = $request->response()->getCookies();
		$this->assertArrayHasKey( 'wikiBlockID', $cookies );

		// clean up
		$block->delete();
	}

	/**
	 * Block cookie should NOT be set when wgCookieSetOnIpBlock
	 * is disabled
	 * @covers User::trackBlockWithCookie
	 */
	public function testIpBlockCookieNotSet() {
		$this->setMwGlobals( [
			'wgCookieSetOnIpBlock' => false,
			'wgCookiePrefix' => 'wiki',
			'wgSecretKey' => MWCryptRand::generateHex( 64 ),
		] );

		// setup block
		$block = new DatabaseBlock( [
			'expiry' => wfTimestamp( TS_MW, wfTimestamp() + ( 5 * 60 * 60 ) ),
		] );
		$block->setTarget( '1.2.3.4' );
		$block->setBlocker( $this->getTestSysop()->getUser() );
		$block->insert();

		// setup request
		$request = new FauxRequest();
		$request->setIP( '1.2.3.4' );

		// get user
		$user = User::newFromSession( $request );
		MediaWikiServices::getInstance()->getBlockManager()
			->trackBlockWithCookie( $user, $request->response() );

		// test cookie was not set
		$cookies = $request->response()->getCookies();
		$this->assertArrayNotHasKey( 'wikiBlockID', $cookies );

		// clean up
		$block->delete();
	}

	/**
	 * When an ip user is blocked and then they log in, cookie block
	 * should be invalid and the cookie removed.
	 * @covers User::trackBlockWithCookie
	 */
	public function testIpBlockCookieIgnoredWhenUserLoggedIn() {
		$this->setMwGlobals( [
			'wgAutoblockExpiry' => 8000,
			'wgCookieSetOnIpBlock' => true,
			'wgCookiePrefix' => 'wiki',
			'wgSecretKey' => MWCryptRand::generateHex( 64 ),
		] );

		$blockManager = MediaWikiServices::getInstance()->getBlockManager();

		// setup block
		$block = new DatabaseBlock( [
			'expiry' => wfTimestamp( TS_MW, wfTimestamp() + ( 40 * 60 * 60 ) ),
		] );
		$block->setTarget( '1.2.3.4' );
		$block->setBlocker( $this->getTestSysop()->getUser() );
		$block->insert();

		// setup request
		$request = new FauxRequest();
		$request->setIP( '1.2.3.4' );
		$request->getSession()->setUser( $this->user );
		$request->setCookie( 'BlockID', $blockManager->getCookieValue( $block ) );

		// setup user
		$user = User::newFromSession( $request );

		// logged in users should be inmune to cookie block of type ip/range
		$this->assertNull( $user->getBlock() );

		// clean up
		$block->delete();
	}

	/**
	 * @covers User::getFirstEditTimestamp
	 * @covers User::getLatestEditTimestamp
	 */
	public function testGetFirstLatestEditTimestamp() {
		$clock = MWTimestamp::convert( TS_UNIX, '20100101000000' );
		MWTimestamp::setFakeTime( function () use ( &$clock ) {
			return $clock += 1000;
		} );
		try {
			$user = $this->user;
			$firstRevision = self::makeEdit( $user, 'Help:UserTest_GetEditTimestamp', 'one', 'test' );
			$secondRevision = self::makeEdit( $user, 'Help:UserTest_GetEditTimestamp', 'two', 'test' );
			// Sanity check: revisions timestamp are different
			$this->assertNotEquals( $firstRevision->getTimestamp(), $secondRevision->getTimestamp() );

			$this->assertSame( $firstRevision->getTimestamp(), $user->getFirstEditTimestamp() );
			$this->assertSame( $secondRevision->getTimestamp(), $user->getLatestEditTimestamp() );
		} finally {
			MWTimestamp::setFakeTime( false );
		}
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
		$updater = $page->newPageUpdater( $user );
		$updater->setContent( 'main', $content );
		return $updater->saveRevision( CommentStoreComment::newUnsavedComment( $comment ) );
	}

	/**
	 * @covers User::idFromName
	 */
	public function testExistingIdFromName() {
		$this->assertTrue(
			array_key_exists( $this->user->getName(), User::$idCacheByName ),
			'Test user should already be in the id cache.'
		);
		$this->assertSame(
			$this->user->getId(), User::idFromName( $this->user->getName() ),
			'Id is correctly retreived from the cache.'
		);
		$this->assertSame(
			$this->user->getId(), User::idFromName( $this->user->getName(), User::READ_LATEST ),
			'Id is correctly retreived from the database.'
		);
	}

	/**
	 * @covers User::idFromName
	 */
	public function testNonExistingIdFromName() {
		$this->assertFalse(
			array_key_exists( 'NotExisitngUser', User::$idCacheByName ),
			'Non exisitng user should not be in the id cache.'
		);
		$this->assertNull( User::idFromName( 'NotExisitngUser' ) );
		$this->assertTrue(
			array_key_exists( 'NotExisitngUser', User::$idCacheByName ),
			'Username will be cached when requested once.'
		);
		$this->assertNull( User::idFromName( 'NotExistingUser' ) );
		$this->assertNull( User::idFromName( 'Illegal|Name' ) );
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
			$globals['wgReservedUsernames'] = [ $name ];
		}
		$this->setMwGlobals( $globals );
		$this->assertTrue( User::isValidUserName( $name ) );
		$this->assertSame( empty( $testOpts['reserved'] ), User::isUsableName( $name ) );

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
			'Reserved but no anonymous actor' => [ 'missing', [], [ 'reserved' => true ], 'user' ],
			'Anonymous actor but no creation' => [ 'actor', [ 'create' => false ], [], 'null' ],
			'Anonymous actor but not reserved' => [ 'actor', [], [], 'exception' ],
		];
	}

	/**
	 * @covers User::getDefaultOption
	 * @covers User::getDefaultOptions
	 */
	public function testGetDefaultOptions() {
		$this->resetServices();

		$this->setTemporaryHook( 'UserGetDefaultOptions', function ( &$defaults ) {
			$defaults['extraoption'] = 42;
		} );

		$defaultOptions = User::getDefaultOptions();
		$this->assertArrayHasKey( 'search-match-redirect', $defaultOptions );
		$this->assertArrayHasKey( 'extraoption', $defaultOptions );

		$extraOption = User::getDefaultOption( 'extraoption' );
		$this->assertSame( 42, $extraOption );
	}

	/**
	 * @covers User::getAutomaticGroups
	 */
	public function testGetAutomaticGroups() {
		$this->assertArrayEquals( [
			'*',
			'user',
			'autoconfirmed'
		], $this->user->getAutomaticGroups( true ) );

		$user = $this->getTestUser( [ 'bureaucrat', 'test' ] )->getUser();
		$this->assertArrayEquals( [
			'*',
			'user',
			'autoconfirmed'
		], $user->getAutomaticGroups( true ) );
		$user->addGroup( 'something' );
		$this->assertArrayEquals( [
			'*',
			'user',
			'autoconfirmed'
		], $user->getAutomaticGroups( true ) );

		$user = User::newFromName( 'UTUser1' );
		$this->assertSame( [ '*' ], $user->getAutomaticGroups( true ) );
		$this->setMwGlobals( [
			'wgAutopromote' => [
				'dummy' => APCOND_EMAILCONFIRMED
			]
		] );

		$this->user->confirmEmail();
		$this->assertArrayEquals( [
			'*',
			'user',
			'dummy'
		], $this->user->getAutomaticGroups( true ) );

		$user = $this->getTestUser( [ 'dummy' ] )->getUser();
		$user->confirmEmail();
		$this->assertArrayEquals( [
			'*',
			'user',
			'dummy'
		], $user->getAutomaticGroups( true ) );
	}

	/**
	 * @covers User::getEffectiveGroups
	 */
	public function testGetEffectiveGroups() {
		$user = $this->getTestUser()->getUser();
		$this->assertArrayEquals( [
			'*',
			'user',
			'autoconfirmed'
		], $user->getEffectiveGroups( true ) );

		$user = $this->getTestUser( [ 'bureaucrat', 'test' ] )->getUser();
		$this->assertArrayEquals( [
			'*',
			'user',
			'autoconfirmed',
			'bureaucrat',
			'test'
		], $user->getEffectiveGroups( true ) );

		$user = $this->getTestUser( [ 'autoconfirmed', 'test' ] )->getUser();
		$this->assertArrayEquals( [
			'*',
			'user',
			'autoconfirmed',
			'test'
		], $user->getEffectiveGroups( true ) );
	}

	/**
	 * @covers User::getGroups
	 */
	public function testGetGroups() {
		$user = $this->getTestUser( [ 'a', 'b' ] )->getUser();
		$this->assertArrayEquals( [ 'a', 'b' ], $user->getGroups() );
	}

	/**
	 * @covers User::getFormerGroups
	 */
	public function testGetFormerGroups() {
		$user = $this->getTestUser( [ 'a', 'b', 'c' ] )->getUser();
		$this->assertArrayEquals( [], $user->getFormerGroups() );
		$user->addGroup( 'test' );
		$user->removeGroup( 'test' );
		$this->assertArrayEquals( [ 'test' ], $user->getFormerGroups() );
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

		$this->setTemporaryHook( 'UserAddGroup', function ( $user, &$group, &$expiry ) {
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

		$this->setTemporaryHook( 'UserRemoveGroup', function ( $user, &$group ) {
			return false;
		} );

		$this->assertFalse( $user->removeGroup( 'test3' ) );
		$this->assertSame( [ 'test3' ], $user->getGroups(), 'Hooks can stop removal of a group' );
	}

	/**
	 * @covers User::changeableGroups
	 */
	public function testChangeableGroups() {
		// todo: test changeableByGroup here as well
		$this->setMwGlobals( [
			'wgGroupPermissions' => [
				'doEverything' => [
					'userrights' => true,
				],
			],
			'wgAddGroups' => [
				'sysop' => [ 'rollback' ],
				'bureaucrat' => [ 'sysop', 'bureaucrat' ],
			],
			'wgRemoveGroups' => [
				'sysop' => [ 'rollback' ],
				'bureaucrat' => [ 'sysop' ],
			],
			'wgGroupsAddToSelf' => [
				'sysop' => [ 'flood' ],
			],
			'wgGroupsRemoveFromSelf' => [
				'flood' => [ 'flood' ],
			],
		] );

		$allGroups = User::getAllGroups();

		$user = $this->getTestUser( [ 'doEverything' ] )->getUser();
		$changeableGroups = $user->changeableGroups();
		$this->assertGroupsEquals(
			[
				'add' => $allGroups,
				'remove' => $allGroups,
				'add-self' => [],
				'remove-self' => [],
			],
			$changeableGroups
		);

		$user = $this->getTestUser( [ 'bureaucrat', 'sysop' ] )->getUser();
		$changeableGroups = $user->changeableGroups();
		$this->assertGroupsEquals(
			[
				'add' => [ 'bureaucrat', 'sysop', 'rollback' ],
				'remove' => [ 'sysop', 'rollback' ],
				'add-self' => [ 'flood' ],
				'remove-self' => [],
			],
			$changeableGroups
		);

		$user = $this->getTestUser( [ 'flood' ] )->getUser();
		$changeableGroups = $user->changeableGroups();
		$this->assertGroupsEquals(
			[
				'add' => [],
				'remove' => [],
				'add-self' => [],
				'remove-self' => [ 'flood' ],
			],
			$changeableGroups
		);
	}

	private function assertGroupsEquals( array $expected, array $actual ) {
		// assertArrayEquals can compare without requiring the same order,
		// but the elements of an array are still required to be in the same order,
		// so just compare each element
		$this->assertArrayEquals( $expected['add'], $actual['add'] );
		$this->assertArrayEquals( $expected['remove'], $actual['remove'] );
		$this->assertArrayEquals( $expected['add-self'], $actual['add-self'] );
		$this->assertArrayEquals( $expected['remove-self'], $actual['remove-self'] );
	}

	/**
	 * @covers User::isWatched
	 * @covers User::isTempWatched
	 * @covers User::addWatch
	 * @covers User::removeWatch
	 */
	public function testWatchlist() {
		$user = $this->user;
		$specialTitle = Title::newFromText( 'Special:Version' );
		$articleTitle = Title::newFromText( 'FooBar' );

		$this->assertFalse( $user->isWatched( $specialTitle ), 'Special pages cannot be watched' );
		$this->assertFalse( $user->isWatched( $articleTitle ), 'The article has not been watched yet' );

		$user->addWatch( $articleTitle );
		$this->assertTrue( $user->isWatched( $articleTitle ), 'The article has been watched' );
		$this->assertFalse(
			$user->isTempWatched( $articleTitle ),
			"The article hasn't been temporarily watched"
		);

		$user->removeWatch( $articleTitle );
		$this->assertFalse( $user->isWatched( $articleTitle ), 'The article has been unwatched' );
		$this->assertFalse(
			$user->isTempWatched( $articleTitle ),
			"The article hasn't been temporarily watched"
		);

		$user->addWatch( $articleTitle, true, '2 weeks' );
		$this->assertTrue(
			$user->isTempWatched( $articleTitle, 'The article has been tempoarily watched' )
		);
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

		$this->setTemporaryHook( 'UserSetEmail', function ( $user, &$email ) {
			$email = 'SettingIntercepted@mediawiki.org';
		} );
		$user->setEmail( 'NewEmail@mediawiki.org' );
		$this->assertSame(
			'SettingIntercepted@mediawiki.org',
			$user->getEmail(),
			'Hooks can override setting email addresses'
		);

		$this->setTemporaryHook( 'UserGetEmail', function ( $user, &$email ) {
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

		$this->setMwGlobals( [
			'wgEnableEmail' => false,
			'wgEmailAuthentication' => false
		] );
		$status = $user->setEmailWithConfirmation( 'test1@mediawiki.org' );
		$this->assertSame(
			$status->getErrors()[0]['message'],
			'emaildisabled',
			'Cannot set email when email is disabled'
		);
		$this->assertSame(
			$user->getEmail(),
			$startingEmail,
			'Email has not changed'
		);

		$this->setMwGlobals( [
			'wgEnableEmail' => true,
		] );
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
	public function testRequiresHTTPS( $preference, $hook1, $hook2, bool $expected ) {
		$this->setMwGlobals( [
			'wgSecureLogin' => true,
			'wgForceHTTPS' => false,
		] );

		$user = User::newFromName( 'UserWhoMayRequireHTTPS' );
		$user->setOption( 'prefershttps', $preference );
		$user->saveSettings();

		$this->filterDeprecated( '/UserRequiresHTTPS hook/' );
		$this->setTemporaryHook( 'UserRequiresHTTPS', function ( $user, &$https ) use ( $hook1 ) {
			$https = $hook1;
			return false;
		} );
		$this->filterDeprecated( '/CanIPUseHTTPS hook/' );
		$this->setTemporaryHook( 'CanIPUseHTTPS', function ( $ip, &$canDo ) use ( $hook2 ) {
			if ( $hook2 === 'notcalled' ) {
				$this->fail( 'CanIPUseHTTPS hook should not have been called' );
			}
			$canDo = $hook2;
			return false;
		} );

		$user = User::newFromName( $user->getName() );
		$this->assertSame( $user->requiresHTTPS(), $expected );
	}

	public static function provideRequiresHTTPS() {
		return [
			'Wants, hook requires, can' => [ true, true, true, true ],
			'Wants, hook requires, cannot' => [ true, true, false, false ],
			'Wants, hook prohibits, not called' => [ true, false, 'notcalled', false ],
			'Does not want, hook requires, can' => [ false, true, true, true ],
			'Does not want, hook requires, cannot' => [ false, true, false, false ],
			'Does not want, hook prohibits, not called' => [ false, false, 'notcalled', false ],
		];
	}

	/**
	 * @covers User::requiresHTTPS
	 */
	public function testRequiresHTTPS_disabled() {
		$this->setMwGlobals( [
			'wgSecureLogin' => false,
			'wgForceHTTPS' => false,
		] );

		$user = User::newFromName( 'UserWhoMayRequireHTTP' );
		$user->setOption( 'prefershttps', true );
		$user->saveSettings();

		$user = User::newFromName( $user->getName() );
		$this->assertFalse(
			$user->requiresHTTPS(),
			'User preference ignored if wgSecureLogin  is false'
		);
	}

	/**
	 * @covers User::requiresHTTPS
	 */
	public function testRequiresHTTPS_forced() {
		$this->setMwGlobals( [
			'wgSecureLogin' => true,
			'wgForceHTTPS' => true,
		] );

		$user = User::newFromName( 'UserWhoMayRequireHTTP' );
		$user->setOption( 'prefershttps', false );
		$user->saveSettings();

		$user = User::newFromName( $user->getName() );
		$this->assertTrue(
			$user->requiresHTTPS(),
			'User preference ignored if wgForceHTTPS is true'
		);
	}

	/**
	 * @covers User::isCreatableName
	 */
	public function testIsCreatableName() {
		$this->setMwGlobals( [
			'wgInvalidUsernameCharacters' => '@',
		] );

		$longUserName = str_repeat( 'x', 260 );

		$this->assertFalse(
			User::isCreatableName( $longUserName ),
			'longUserName is too long'
		);
		$this->assertFalse(
			User::isCreatableName( 'Foo@Bar' ),
			'User name contains invalid character'
		);
		$this->assertTrue(
			User::isCreatableName( 'FooBar' ),
			'User names with no issues can be created'
		);
	}

	/**
	 * @covers User::isUsableName
	 */
	public function testIsUsableName() {
		$this->setMwGlobals( [
			'wgReservedUsernames' => [
				'MediaWiki default',
				'msg:reserved-user'
			],
			'wgForceUIMsgAsContentMsg' => [
				'reserved-user'
			],
		] );

		$this->assertFalse(
			User::isUsableName( '' ),
			'Only valid user names are creatable'
		);
		$this->assertFalse(
			User::isUsableName( 'MediaWiki default' ),
			'Reserved names cannot be used'
		);
		$this->assertFalse(
			User::isUsableName( 'reserved-user' ),
			'Names can also be reserved via msg: '
		);
		$this->assertTrue(
			User::isUsableName( 'FooBar' ),
			'User names with no issues can be used'
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
	public function testPingLimiterHook() {
		$this->setMwGlobals( [
			'wgRateLimits' => [
				'edit' => [
					'user' => [ 3, 60 ],
				],
			],
		] );

		// Hook leaves $result false
		$this->setTemporaryHook(
			'PingLimiter',
			function ( &$user, $action, &$result, $incrBy ) {
				return false;
			}
		);
		$this->assertFalse(
			$this->user->pingLimiter(),
			'Hooks that just return false leave $result false'
		);
		$this->removeTemporaryHook( 'PingLimiter' );

		// Hook sets $result to true
		$this->setTemporaryHook(
			'PingLimiter',
			function ( &$user, $action, &$result, $incrBy ) {
				$result = true;
				return false;
			}
		);
		$this->assertTrue(
			$this->user->pingLimiter(),
			'Hooks can set $result to true'
		);
		$this->removeTemporaryHook( 'PingLimiter' );

		// Unknown action
		$this->assertFalse(
			$this->user->pingLimiter( 'FakeActionWithNoRateLimit' ),
			'Actions with no rate limit set do not trip the rate limiter'
		);
	}

	/**
	 * @covers User::pingLimiter
	 */
	public function testPingLimiterWithStaleCache() {
		global $wgMainCacheType;

		$this->setMwGlobals( [
			'wgRateLimits' => [
				'edit' => [
					'user' => [ 1, 60 ],
				],
			],
		] );

		$cacheTime = 1600000000.0;
		$appTime = 1600000000;
		$cache = new HashBagOStuff();

		// TODO: make the main object cache a service we can override, T243233
		ObjectCache::$instances[$wgMainCacheType] = $cache;

		$cache->setMockTime( $cacheTime ); // this is a reference!
		MWTimestamp::setFakeTime( function () use ( &$appTime ) {
			return (int)$appTime;
		} );

		$this->assertFalse( $this->user->pingLimiter(), 'limit not reached' );
		$this->assertTrue( $this->user->pingLimiter(), 'limit reached' );

		// Make it so that rate limits are expired according to MWTimestamp::time(),
		// but not according to $cache->getCurrentTime(), emulating the conditions
		// that trigger T246991.
		$cacheTime += 10;
		$appTime += 100;

		$this->assertFalse( $this->user->pingLimiter(), 'limit expired' );
		$this->assertTrue( $this->user->pingLimiter(), 'limit functional after expiry' );
	}

	/**
	 * @covers User::pingLimiter
	 */
	public function testPingLimiterRate() {
		global $wgMainCacheType;

		$this->setMwGlobals( [
			'wgRateLimits' => [
				'edit' => [
					'user' => [ 3, 60 ],
				],
			],
		] );

		$fakeTime = 1600000000;
		$cache = new HashBagOStuff();

		// TODO: make the main object cache a service we can override, T243233
		ObjectCache::$instances[$wgMainCacheType] = $cache;

		$cache->setMockTime( $fakeTime ); // this is a reference!
		MWTimestamp::setFakeTime( function () use ( &$fakeTime ) {
			return (int)$fakeTime;
		} );

		// The limit is 3 per 60 second. Do 5 edits at an emulated 50 second interval.
		// They should all pass. This tests that the counter doesn't just keeps increasing
		// but gets reset in an appropriate way.
		$this->assertFalse( $this->user->pingLimiter(), 'first ping should pass' );

		$fakeTime += 50;
		$this->assertFalse( $this->user->pingLimiter(), 'second ping should pass' );

		$fakeTime += 50;
		$this->assertFalse( $this->user->pingLimiter(), 'third ping should pass' );

		$fakeTime += 50;
		$this->assertFalse( $this->user->pingLimiter(), 'fourth ping should pass' );

		$fakeTime += 50;
		$this->assertFalse( $this->user->pingLimiter(), 'fifth ping should pass' );
	}

	private function newFakeUser( $name, $ip, $id ) {
		$req = new FauxRequest();
		$req->setIP( $ip );

		$user = User::newFromName( $name, false );

		$access = TestingAccessWrapper::newFromObject( $user );
		$access->mRequest = $req;
		$access->mId = $id;
		$access->setItemLoaded( 'id' );

		$this->overrideUserPermissions( $user, [
			'noratelimit' => false,
		] );

		return $user;
	}

	private function newFakeAnon( $ip ) {
		return $this->newFakeUser( $ip, $ip, 0 );
	}

	/**
	 * @covers User::pingLimiter
	 */
	public function testPingLimiterGlobal() {
		$this->setMwGlobals( [
			'wgRateLimits' => [
				'edit' => [
					'anon' => [ 1, 60 ],
				],
				'purge' => [
					'ip' => [ 1, 60 ],
					'subnet' => [ 1, 60 ],
				],
				'rollback' => [
					'user' => [ 1, 60 ],
				],
				'move' => [
					'user-global' => [ 1, 60 ],
				],
				'delete' => [
					'ip-all' => [ 1, 60 ],
					'subnet-all' => [ 1, 60 ],
				],
			],
		] );

		// Set up a fake cache for storing limits
		$cache = new HashBagOStuff( [ 'keyspace' => 'xwiki' ] );

		global $wgMainCacheType;
		ObjectCache::$instances[$wgMainCacheType] = $cache;

		$cacheAccess = TestingAccessWrapper::newFromObject( $cache );
		$cacheAccess->keyspace = 'xwiki';

		$this->installMockContralIdProvider();

		// Set up some fake users
		$anon1 = $this->newFakeAnon( '1.2.3.4' );
		$anon2 = $this->newFakeAnon( '1.2.3.8' );
		$anon3 = $this->newFakeAnon( '6.7.8.9' );
		$anon4 = $this->newFakeAnon( '6.7.8.1' );

		// The mock ContralIdProvider uses the local id MOD 10 as the global ID.
		// So Frank has global ID 11, and Jane has global ID 56.
		// Kara's global ID is 0, which means no global ID.
		$frankX1 = $this->newFakeUser( 'Frank', '1.2.3.4', 111 );
		$frankX2 = $this->newFakeUser( 'Frank', '1.2.3.8', 111 );
		$frankY1 = $this->newFakeUser( 'Frank', '1.2.3.4', 211 );
		$janeX1 = $this->newFakeUser( 'Jane', '1.2.3.4', 456 );
		$janeX3 = $this->newFakeUser( 'Jane', '6.7.8.9', 456 );
		$janeY1 = $this->newFakeUser( 'Jane', '1.2.3.4', 756 );
		$karaX1 = $this->newFakeUser( 'Kara', '5.5.5.5', 100 );
		$karaY1 = $this->newFakeUser( 'Kara', '5.5.5.5', 200 );

		// Test limits on wiki X
		$this->assertFalse( $anon1->pingLimiter( 'edit' ), 'First anon edit' );
		$this->assertTrue( $anon2->pingLimiter( 'edit' ), 'Second anon edit' );

		$this->assertFalse( $anon1->pingLimiter( 'purge' ), 'Anon purge' );
		$this->assertTrue( $anon1->pingLimiter( 'purge' ), 'Anon purge via same IP' );

		$this->assertFalse( $anon3->pingLimiter( 'purge' ), 'Anon purge via different subnet' );
		$this->assertTrue( $anon2->pingLimiter( 'purge' ), 'Anon purge via same subnet' );

		$this->assertFalse( $frankX1->pingLimiter( 'rollback' ), 'First rollback' );
		$this->assertTrue( $frankX2->pingLimiter( 'rollback' ), 'Second rollback via different IP' );
		$this->assertFalse( $janeX1->pingLimiter( 'rollback' ), 'Rlbk by different user, same IP' );

		$this->assertFalse( $frankX1->pingLimiter( 'move' ), 'First move' );
		$this->assertTrue( $frankX2->pingLimiter( 'move' ), 'Second move via different IP' );
		$this->assertFalse( $janeX1->pingLimiter( 'move' ), 'Move by different user, same IP' );
		$this->assertFalse( $karaX1->pingLimiter( 'move' ), 'Move by another user' );
		$this->assertTrue( $karaX1->pingLimiter( 'move' ), 'Second move by another user' );

		$this->assertFalse( $frankX1->pingLimiter( 'delete' ), 'First delete' );
		$this->assertTrue( $janeX1->pingLimiter( 'delete' ), 'Delete via same IP' );

		$this->assertTrue( $frankX2->pingLimiter( 'delete' ), 'Delete via same subnet' );
		$this->assertFalse( $janeX3->pingLimiter( 'delete' ), 'Delete via different subnet' );

		// Now test how limits carry over to wiki Y
		$cacheAccess->keyspace = 'ywiki';

		$this->assertFalse( $anon3->pingLimiter( 'edit' ), 'Anon edit on wiki Y' );
		$this->assertTrue( $anon4->pingLimiter( 'purge' ), 'Anon purge on wiki Y, same subnet' );
		$this->assertFalse( $frankY1->pingLimiter( 'rollback' ), 'Rollback on wiki Y, same name' );
		$this->assertTrue( $frankY1->pingLimiter( 'move' ), 'Move on wiki Y, same name' );
		$this->assertTrue( $janeY1->pingLimiter( 'move' ), 'Move on wiki Y, different user' );
		$this->assertTrue( $frankY1->pingLimiter( 'delete' ), 'Delete on wiki Y, same IP' );

		// For a user without a global ID, user-global acts as a local restriction
		$this->assertFalse( $karaY1->pingLimiter( 'move' ), 'Move by another user' );
		$this->assertTrue( $karaY1->pingLimiter( 'move' ), 'Second move by another user' );
	}

	private function doTestNewTalk( User $user ) {
		$this->hideDeprecated( 'User::getNewtalk' );
		$this->hideDeprecated( 'User::setNewtalk' );
		$this->assertFalse( $user->getNewtalk(), 'Should be false before updated' );
		$user->setNewtalk( true );
		$this->assertTrue( $user->getNewtalk(), 'Should be true after updated' );
		$user->clearInstanceCache();
		$this->assertTrue( $user->getNewtalk(), 'Should be true after cache cleared' );
		$user->setNewtalk( false );
		$this->assertFalse( $user->getNewtalk(), 'Should be false after updated' );
		$user->clearInstanceCache();
		$this->assertFalse( $user->getNewtalk(), 'Should be false after cache cleared' );
	}

	/**
	 * @covers User::getNewtalk
	 * @covers User::setNewtalk
	 */
	public function testNewtalkRegistered() {
		$this->doTestNewTalk( $this->getTestUser()->getUser() );
	}

	/**
	 * @covers User::getNewtalk
	 * @covers User::setNewtalk
	 */
	public function testNewtalkAnon() {
		$this->doTestNewTalk( User::newFromName( __METHOD__ ) );
	}

	/**
	 * @covers User::getNewMessageLinks
	 * @covers User::getNewMessageRevisionId
	 */
	public function testGetNewMessageLinks() {
		$this->hideDeprecated( 'User::getNewMessageLinks' );
		$this->hideDeprecated( 'User::getNewMessageRevisionId' );
		$this->hideDeprecated( 'User::setNewtalk' );
		$this->hideDeprecated( 'Revision::__construct' );
		$this->hideDeprecated( 'Revision::getId' );

		$user = $this->getTestUser()->getUser();
		$userTalk = $user->getTalkPage();

		// Make sure time progresses between revisions.
		// MediaWikiIntegrationTestCase automatically restores the real clock.
		$clock = MWTimestamp::time();
		MWTimestamp::setFakeTime( function () use ( &$clock ) {
			return ++$clock;
		} );

		$status = $this->editPage( $userTalk->getPrefixedText(), 'Message one' );
		$this->assertTrue( $status->isGood(), 'Sanity: create revision 1 of user talk' );
		/** @var RevisionRecord $firstRevRecord */
		$firstRevRecord = $status->getValue()['revision-record'];
		$status = $this->editPage( $userTalk->getPrefixedText(), 'Message two' );
		$this->assertTrue( $status->isGood(), 'Sanity: create revision 2 of user talk' );
		/** @var RevisionRecord $secondRevRecord */
		$secondRevRecord = $status->getValue()['revision-record'];

		$user->setNewtalk( true, $secondRevRecord );
		$links = $user->getNewMessageLinks();
		$this->assertTrue( count( $links ) > 0, 'Must have new message links' );
		$this->assertSame( $userTalk->getLocalURL(), $links[0]['link'] );
		$this->assertSame( $firstRevRecord->getId(), $links[0]['rev']->getId() );
		$this->assertSame( $firstRevRecord->getId(), $user->getNewMessageRevisionId() );
	}

	private function installMockContralIdProvider() {
		$mockCentralIdLookup = $this->createNoOpMock(
			CentralIdLookup::class,
			[ 'centralIdFromLocalUser', 'getProviderId' ]
		);

		$mockCentralIdLookup->method( 'centralIdFromLocalUser' )
			->willReturnCallback( function ( User $user ) {
				return $user->getId() % 100;
			} );

		$this->setMwGlobals( [
			'wgCentralIdLookupProvider' => 'test',
			'wgCentralIdLookupProviders' => [
				'test' => [
					'factory' => function () use ( $mockCentralIdLookup ) {
						return $mockCentralIdLookup;
					}
				]
			]
		] );
	}
}
