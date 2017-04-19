<?php

define( 'NS_UNITTEST', 5600 );
define( 'NS_UNITTEST_TALK', 5601 );

use MediaWiki\MediaWikiServices;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Database
 */
class UserTest extends MediaWikiTestCase {
	/**
	 * @var User
	 */
	protected $user;

	protected function setUp() {
		parent::setUp();

		$this->setMwGlobals( [
			'wgGroupPermissions' => [],
			'wgRevokePermissions' => [],
		] );

		$this->setUpPermissionGlobals();

		$this->user = new User;
		$this->user->addToDatabase();
		$this->user->addGroup( 'unittesters' );
	}

	private function setUpPermissionGlobals() {
		global $wgGroupPermissions, $wgRevokePermissions;

		# Data for regular $wgGroupPermissions test
		$wgGroupPermissions['unittesters'] = [
			'test' => true,
			'runtest' => true,
			'writetest' => false,
			'nukeworld' => false,
		];
		$wgGroupPermissions['testwriters'] = [
			'test' => true,
			'writetest' => true,
			'modifytest' => true,
		];

		# Data for regular $wgRevokePermissions test
		$wgRevokePermissions['formertesters'] = [
			'runtest' => true,
		];

		# For the options test
		$wgGroupPermissions['*'] = [
			'editmyoptions' => true,
		];
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
	 * @covers User::getRights
	 */
	public function testUserGetRightsHooks() {
		$user = new User;
		$user->addToDatabase();
		$user->addGroup( 'unittesters' );
		$user->addGroup( 'testwriters' );
		$userWrapper = TestingAccessWrapper::newFromObject( $user );

		$rights = $user->getRights();
		$this->assertContains( 'test', $rights, 'sanity check' );
		$this->assertContains( 'runtest', $rights, 'sanity check' );
		$this->assertContains( 'writetest', $rights, 'sanity check' );
		$this->assertNotContains( 'nukeworld', $rights, 'sanity check' );

		// Add a hook manipluating the rights
		$this->mergeMwGlobalArrayValue( 'wgHooks', [ 'UserGetRights' => [ function ( $user, &$rights ) {
			$rights[] = 'nukeworld';
			$rights = array_diff( $rights, [ 'writetest' ] );
		} ] ] );

		$userWrapper->mRights = null;
		$rights = $user->getRights();
		$this->assertContains( 'test', $rights );
		$this->assertContains( 'runtest', $rights );
		$this->assertNotContains( 'writetest', $rights );
		$this->assertContains( 'nukeworld', $rights );

		// Add a Session that limits rights
		$mock = $this->getMockBuilder( stdclass::class )
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

		$userWrapper->mRights = null;
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
	public function testGetGroupsWithPermission( $expected, $right ) {
		$result = User::getGroupsWithPermission( $right );
		sort( $result );
		sort( $expected );

		$this->assertEquals( $expected, $result, "Groups with permission $right" );
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
	 * @dataProvider provideIPs
	 * @covers User::isIP
	 */
	public function testIsIP( $value, $result, $message ) {
		$this->assertEquals( $this->user->isIP( $value ), $result, $message );
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
		$this->assertEquals( $this->user->isValidUserName( $username ), $result, $message );
	}

	public static function provideUserNames() {
		return [
			[ '', false, 'Empty string' ],
			[ ' ', false, 'Blank space' ],
			[ 'abcd', false, 'Starts with small letter' ],
			[ 'Ab/cd', false, 'Contains slash' ],
			[ 'Ab cd', true, 'Whitespace' ],
			[ '192.168.1.1', false, 'IP' ],
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
	 * Test, if for all rights a right- message exist,
	 * which is used on Special:ListGroupRights as help text
	 * Extensions and core
	 */
	public function testAllRightsWithMessage() {
		// Getting all user rights, for core: User::$mCoreRights, for extensions: $wgAvailableRights
		$allRights = User::getAllRights();
		$allMessageKeys = Language::getMessageKeysFor( 'en' );

		$rightsWithMessage = [];
		foreach ( $allMessageKeys as $message ) {
			// === 0: must be at beginning of string (position 0)
			if ( strpos( $message, 'right-' ) === 0 ) {
				$rightsWithMessage[] = substr( $message, strlen( 'right-' ) );
			}
		}

		sort( $allRights );
		sort( $rightsWithMessage );

		$this->assertEquals(
			$allRights,
			$rightsWithMessage,
			'Each user rights (core/extensions) has a corresponding right- message.'
		);
	}

	/**
	 * Test User::editCount
	 * @group medium
	 * @covers User::getEditCount
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

		$this->assertEquals(
			3,
			$user->getEditCount(),
			'After three edits, the user edit count should be 3'
		);

		// increase the edit count
		$user->incEditCount();

		$this->assertEquals(
			4,
			$user->getEditCount(),
			'After increasing the edit count manually, the user edit count should be 4'
		);
	}

	/**
	 * Test User::editCount
	 * @group medium
	 * @covers User::getEditCount
	 */
	public function testGetEditCountForAnons() {
		$user = User::newFromName( 'Anonymous' );

		$this->assertNull(
			$user->getEditCount(),
			'Edit count starts null for anonymous users.'
		);

		$user->incEditCount();

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

		$this->assertEquals(
			2,
			$reloadedUser->getEditCount(),
			'Increasing the edit count after a fresh load leaves the object up to date.'
		);
	}

	/**
	 * Test changing user options.
	 * @covers User::setOption
	 * @covers User::getOption
	 */
	public function testOptions() {
		$user = $this->getMutableTestUser()->getUser();

		$user->setOption( 'userjs-someoption', 'test' );
		$user->setOption( 'rclimit', 200 );
		$user->saveSettings();

		$user = User::newFromName( $user->getName() );
		$user->load( User::READ_LATEST );
		$this->assertEquals( 'test', $user->getOption( 'userjs-someoption' ) );
		$this->assertEquals( 200, $user->getOption( 'rclimit' ) );

		$user = User::newFromName( $user->getName() );
		MediaWikiServices::getInstance()->getMainWANObjectCache()->clearProcessCache();
		$this->assertEquals( 'test', $user->getOption( 'userjs-someoption' ) );
		$this->assertEquals( 200, $user->getOption( 'rclimit' ) );
	}

	/**
	 * T39963
	 * Make sure defaults are loaded when setOption is called.
	 * @covers User::loadOptions
	 */
	public function testAnonOptions() {
		global $wgDefaultUserOptions;
		$this->user->setOption( 'userjs-someoption', 'test' );
		$this->assertEquals( $wgDefaultUserOptions['rclimit'], $this->user->getOption( 'rclimit' ) );
		$this->assertEquals( 'test', $this->user->getOption( 'userjs-someoption' ) );
	}

	/**
	 * Test password validity checks. There are 3 checks in core,
	 *	- ensure the password meets the minimal length
	 *	- ensure the password is not the same as the username
	 *	- ensure the username/password combo isn't forbidden
	 * @covers User::checkPasswordValidity()
	 * @covers User::getPasswordValidity()
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
					],
					'default' => [
						'MinimalPasswordLength' => 6,
						'PasswordCannotMatchUsername' => true,
						'PasswordCannotMatchBlacklist' => true,
						'MaximalPasswordLength' => 40,
					],
				],
				'checks' => [
					'MinimalPasswordLength' => 'PasswordPolicyChecks::checkMinimalPasswordLength',
					'MinimumPasswordLengthToLogin' => 'PasswordPolicyChecks::checkMinimumPasswordLengthToLogin',
					'PasswordCannotMatchUsername' => 'PasswordPolicyChecks::checkPasswordCannotMatchUsername',
					'PasswordCannotMatchBlacklist' => 'PasswordPolicyChecks::checkPasswordCannotMatchBlacklist',
					'MaximalPasswordLength' => 'PasswordPolicyChecks::checkMaximalPasswordLength',
				],
			],
		] );

		$user = static::getTestUser()->getUser();

		// Sanity
		$this->assertTrue( $user->isValidPassword( 'Password1234' ) );

		// Minimum length
		$this->assertFalse( $user->isValidPassword( 'a' ) );
		$this->assertFalse( $user->checkPasswordValidity( 'a' )->isGood() );
		$this->assertTrue( $user->checkPasswordValidity( 'a' )->isOK() );
		$this->assertEquals( 'passwordtooshort', $user->getPasswordValidity( 'a' ) );

		// Maximum length
		$longPass = str_repeat( 'a', 41 );
		$this->assertFalse( $user->isValidPassword( $longPass ) );
		$this->assertFalse( $user->checkPasswordValidity( $longPass )->isGood() );
		$this->assertFalse( $user->checkPasswordValidity( $longPass )->isOK() );
		$this->assertEquals( 'passwordtoolong', $user->getPasswordValidity( $longPass ) );

		// Matches username
		$this->assertFalse( $user->checkPasswordValidity( $user->getName() )->isGood() );
		$this->assertTrue( $user->checkPasswordValidity( $user->getName() )->isOK() );
		$this->assertEquals( 'password-name-match', $user->getPasswordValidity( $user->getName() ) );

		// On the forbidden list
		$user = User::newFromName( 'Useruser' );
		$this->assertFalse( $user->checkPasswordValidity( 'Passpass' )->isGood() );
		$this->assertEquals( 'password-login-forbidden', $user->getPasswordValidity( 'Passpass' ) );
	}

	/**
	 * @covers User::getCanonicalName()
	 * @dataProvider provideGetCanonicalName
	 */
	public function testGetCanonicalName( $name, $expectedArray ) {
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
			$this->assertEquals(
				$expected,
				User::getCanonicalName( $name, $validate === 'false' ? false : $validate ), $validate );
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
	 */
	public function testGetId() {
		$user = static::getTestUser()->getUser();
		$this->assertTrue( $user->getId() > 0 );
	}

	/**
	 * @covers User::isLoggedIn
	 * @covers User::isAnon
	 */
	public function testLoggedIn() {
		$user = $this->getMutableTestUser()->getUser();
		$this->assertTrue( $user->isLoggedIn() );
		$this->assertFalse( $user->isAnon() );

		// Non-existent users are perceived as anonymous
		$user = User::newFromName( 'UTNonexistent' );
		$this->assertFalse( $user->isLoggedIn() );
		$this->assertTrue( $user->isAnon() );

		$user = new User;
		$this->assertFalse( $user->isLoggedIn() );
		$this->assertTrue( $user->isAnon() );
	}

	/**
	 * @covers User::checkAndSetTouched
	 */
	public function testCheckAndSetTouched() {
		$user = $this->getMutableTestUser()->getUser();
		$user = TestingAccessWrapper::newFromObject( $user );
		$this->assertTrue( $user->isLoggedIn() );

		$touched = $user->getDBTouched();
		$this->assertTrue(
			$user->checkAndSetTouched(), "checkAndSetTouched() succeded" );
		$this->assertGreaterThan(
			$touched, $user->getDBTouched(), "user_touched increased with casOnTouched()" );

		$touched = $user->getDBTouched();
		$this->assertTrue(
			$user->checkAndSetTouched(), "checkAndSetTouched() succeded #2" );
		$this->assertGreaterThan(
			$touched, $user->getDBTouched(), "user_touched increased with casOnTouched() #2" );
	}

	/**
	 * @covers User::findUsersByGroup
	 */
	public function testFindUsersByGroup() {
		$users = User::findUsersByGroup( [] );
		$this->assertEquals( 0, iterator_count( $users ) );

		$users = User::findUsersByGroup( 'foo' );
		$this->assertEquals( 0, iterator_count( $users ) );

		$user = $this->getMutableTestUser( [ 'foo' ] )->getUser();
		$users = User::findUsersByGroup( 'foo' );
		$this->assertEquals( 1, iterator_count( $users ) );
		$users->rewind();
		$this->assertTrue( $user->equals( $users->current() ) );

		// arguments have OR relationship
		$user2 = $this->getMutableTestUser( [ 'bar' ] )->getUser();
		$users = User::findUsersByGroup( [ 'foo', 'bar' ] );
		$this->assertEquals( 2, iterator_count( $users ) );
		$users->rewind();
		$this->assertTrue( $user->equals( $users->current() ) );
		$users->next();
		$this->assertTrue( $user2->equals( $users->current() ) );

		// users are not duplicated
		$user = $this->getMutableTestUser( [ 'baz', 'boom' ] )->getUser();
		$users = User::findUsersByGroup( [ 'baz', 'boom' ] );
		$this->assertEquals( 1, iterator_count( $users ) );
		$users->rewind();
		$this->assertTrue( $user->equals( $users->current() ) );
	}

	/**
	 * When a user is autoblocked a cookie is set with which to track them
	 * in case they log out and change IP addresses.
	 * @link https://phabricator.wikimedia.org/T5233
	 */
	public function testAutoblockCookies() {
		// Set up the bits of global configuration that we use.
		$this->setMwGlobals( [
			'wgCookieSetOnAutoblock' => true,
			'wgCookiePrefix' => 'wmsitetitle',
			'wgSecretKey' => MWCryptRand::generateHex( 64, true ),
		] );

		// 1. Log in a test user, and block them.
		$user1tmp = $this->getTestUser()->getUser();
		$request1 = new FauxRequest();
		$request1->getSession()->setUser( $user1tmp );
		$expiryFiveHours = wfTimestamp() + ( 5 * 60 * 60 );
		$block = new Block( [
			'enableAutoblock' => true,
			'expiry' => wfTimestamp( TS_MW, $expiryFiveHours ),
		] );
		$block->setTarget( $user1tmp );
		$block->insert();
		$user1 = User::newFromSession( $request1 );
		$user1->mBlock = $block;
		$user1->load();

		// Confirm that the block has been applied as required.
		$this->assertTrue( $user1->isLoggedIn() );
		$this->assertTrue( $user1->isBlocked() );
		$this->assertEquals( Block::TYPE_USER, $block->getType() );
		$this->assertTrue( $block->isAutoblocking() );
		$this->assertGreaterThanOrEqual( 1, $block->getId() );

		// Test for the desired cookie name, value, and expiry.
		$cookies = $request1->response()->getCookies();
		$this->assertArrayHasKey( 'wmsitetitleBlockID', $cookies );
		$this->assertEquals( $expiryFiveHours, $cookies['wmsitetitleBlockID']['expire'] );
		$cookieValue = Block::getIdFromCookieValue( $cookies['wmsitetitleBlockID']['value'] );
		$this->assertEquals( $block->getId(), $cookieValue );

		// 2. Create a new request, set the cookies, and see if the (anon) user is blocked.
		$request2 = new FauxRequest();
		$request2->setCookie( 'BlockID', $block->getCookieValue() );
		$user2 = User::newFromSession( $request2 );
		$user2->load();
		$this->assertNotEquals( $user1->getId(), $user2->getId() );
		$this->assertNotEquals( $user1->getToken(), $user2->getToken() );
		$this->assertTrue( $user2->isAnon() );
		$this->assertFalse( $user2->isLoggedIn() );
		$this->assertTrue( $user2->isBlocked() );
		$this->assertEquals( true, $user2->getBlock()->isAutoblocking() ); // Non-strict type-check.
		// Can't directly compare the objects becuase of member type differences.
		// One day this will work: $this->assertEquals( $block, $user2->getBlock() );
		$this->assertEquals( $block->getId(), $user2->getBlock()->getId() );
		$this->assertEquals( $block->getExpiry(), $user2->getBlock()->getExpiry() );

		// 3. Finally, set up a request as a new user, and the block should still be applied.
		$user3tmp = $this->getTestUser()->getUser();
		$request3 = new FauxRequest();
		$request3->getSession()->setUser( $user3tmp );
		$request3->setCookie( 'BlockID', $block->getId() );
		$user3 = User::newFromSession( $request3 );
		$user3->load();
		$this->assertTrue( $user3->isLoggedIn() );
		$this->assertTrue( $user3->isBlocked() );
		$this->assertEquals( true, $user3->getBlock()->isAutoblocking() ); // Non-strict type-check.

		// Clean up.
		$block->delete();
	}

	/**
	 * Make sure that no cookie is set to track autoblocked users
	 * when $wgCookieSetOnAutoblock is false.
	 */
	public function testAutoblockCookiesDisabled() {
		// Set up the bits of global configuration that we use.
		$this->setMwGlobals( [
			'wgCookieSetOnAutoblock' => false,
			'wgCookiePrefix' => 'wm_no_cookies',
			'wgSecretKey' => MWCryptRand::generateHex( 64, true ),
		] );

		// 1. Log in a test user, and block them.
		$testUser = $this->getTestUser()->getUser();
		$request1 = new FauxRequest();
		$request1->getSession()->setUser( $testUser );
		$block = new Block( [ 'enableAutoblock' => true ] );
		$block->setTarget( $testUser );
		$block->insert();
		$user = User::newFromSession( $request1 );
		$user->mBlock = $block;
		$user->load();

		// 2. Test that the cookie IS NOT present.
		$this->assertTrue( $user->isLoggedIn() );
		$this->assertTrue( $user->isBlocked() );
		$this->assertEquals( Block::TYPE_USER, $block->getType() );
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
	 */
	public function testAutoblockCookieInfiniteExpiry() {
		$this->setMwGlobals( [
			'wgCookieSetOnAutoblock' => true,
			'wgCookiePrefix' => 'wm_infinite_block',
			'wgSecretKey' => MWCryptRand::generateHex( 64, true ),
		] );
		// 1. Log in a test user, and block them indefinitely.
		$user1Tmp = $this->getTestUser()->getUser();
		$request1 = new FauxRequest();
		$request1->getSession()->setUser( $user1Tmp );
		$block = new Block( [ 'enableAutoblock' => true, 'expiry' => 'infinity' ] );
		$block->setTarget( $user1Tmp );
		$block->insert();
		$user1 = User::newFromSession( $request1 );
		$user1->mBlock = $block;
		$user1->load();

		// 2. Test the cookie's expiry timestamp.
		$this->assertTrue( $user1->isLoggedIn() );
		$this->assertTrue( $user1->isBlocked() );
		$this->assertEquals( Block::TYPE_USER, $block->getType() );
		$this->assertTrue( $block->isAutoblocking() );
		$this->assertGreaterThanOrEqual( 1, $user1->getBlockId() );
		$cookies = $request1->response()->getCookies();
		// Test the cookie's expiry to the nearest minute.
		$this->assertArrayHasKey( 'wm_infinite_blockBlockID', $cookies );
		$expOneDay = wfTimestamp() + ( 24 * 60 * 60 );
		// Check for expiry dates in a 10-second window, to account for slow testing.
		$this->assertEquals(
			$expOneDay,
			$cookies['wm_infinite_blockBlockID']['expire'],
			'Expiry date',
			5.0
		);

		// 3. Change the block's expiry (to 2 hours), and the cookie's should be changed also.
		$newExpiry = wfTimestamp() + 2 * 60 * 60;
		$block->mExpiry = wfTimestamp( TS_MW, $newExpiry );
		$block->update();
		$user2tmp = $this->getTestUser()->getUser();
		$request2 = new FauxRequest();
		$request2->getSession()->setUser( $user2tmp );
		$user2 = User::newFromSession( $request2 );
		$user2->mBlock = $block;
		$user2->load();
		$cookies = $request2->response()->getCookies();
		$this->assertEquals( wfTimestamp( TS_MW, $newExpiry ), $block->getExpiry() );
		$this->assertEquals( $newExpiry, $cookies['wm_infinite_blockBlockID']['expire'] );

		// Clean up.
		$block->delete();
	}

	public function testSoftBlockRanges() {
		global $wgUser;

		$this->setMwGlobals( [
			'wgSoftBlockRanges' => [ '10.0.0.0/8' ],
			'wgUser' => null,
		] );

		// IP isn't in $wgSoftBlockRanges
		$request = new FauxRequest();
		$request->setIP( '192.168.0.1' );
		$wgUser = User::newFromSession( $request );
		$this->assertNull( $wgUser->getBlock() );

		// IP is in $wgSoftBlockRanges
		$request = new FauxRequest();
		$request->setIP( '10.20.30.40' );
		$wgUser = User::newFromSession( $request );
		$block = $wgUser->getBlock();
		$this->assertInstanceOf( Block::class, $block );
		$this->assertSame( 'wgSoftBlockRanges', $block->getSystemBlockType() );

		// Make sure the block is really soft
		$request->getSession()->setUser( $this->getTestUser()->getUser() );
		$wgUser = User::newFromSession( $request );
		$this->assertFalse( $wgUser->isAnon(), 'sanity check' );
		$this->assertNull( $wgUser->getBlock() );
	}

	/**
	 * Test that a modified BlockID cookie doesn't actually load the relevant block (T152951).
	 */
	public function testAutoblockCookieInauthentic() {
		// Set up the bits of global configuration that we use.
		$this->setMwGlobals( [
			'wgCookieSetOnAutoblock' => true,
			'wgCookiePrefix' => 'wmsitetitle',
			'wgSecretKey' => MWCryptRand::generateHex( 64, true ),
		] );

		// 1. Log in a blocked test user.
		$user1tmp = $this->getTestUser()->getUser();
		$request1 = new FauxRequest();
		$request1->getSession()->setUser( $user1tmp );
		$block = new Block( [ 'enableAutoblock' => true ] );
		$block->setTarget( $user1tmp );
		$block->insert();
		$user1 = User::newFromSession( $request1 );
		$user1->mBlock = $block;
		$user1->load();

		// 2. Create a new request, set the cookie to an invalid value, and make sure the (anon)
		// user not blocked.
		$request2 = new FauxRequest();
		$request2->setCookie( 'BlockID', $block->getId() . '!zzzzzzz' );
		$user2 = User::newFromSession( $request2 );
		$user2->load();
		$this->assertTrue( $user2->isAnon() );
		$this->assertFalse( $user2->isLoggedIn() );
		$this->assertFalse( $user2->isBlocked() );

		// Clean up.
		$block->delete();
	}

	/**
	 * The BlockID cookie is normally verified with a HMAC, but not if wgSecretKey is not set.
	 * This checks that a non-authenticated cookie still works.
	 */
	public function testAutoblockCookieNoSecretKey() {
		// Set up the bits of global configuration that we use.
		$this->setMwGlobals( [
			'wgCookieSetOnAutoblock' => true,
			'wgCookiePrefix' => 'wmsitetitle',
			'wgSecretKey' => null,
		] );

		// 1. Log in a blocked test user.
		$user1tmp = $this->getTestUser()->getUser();
		$request1 = new FauxRequest();
		$request1->getSession()->setUser( $user1tmp );
		$block = new Block( [ 'enableAutoblock' => true ] );
		$block->setTarget( $user1tmp );
		$block->insert();
		$user1 = User::newFromSession( $request1 );
		$user1->mBlock = $block;
		$user1->load();
		$this->assertTrue( $user1->isBlocked() );

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
		$this->assertTrue( $user2->isBlocked() );
		$this->assertEquals( true, $user2->getBlock()->isAutoblocking() ); // Non-strict type-check.

		// Clean up.
		$block->delete();
	}

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
		$noRateLimitUser = $this->getMockBuilder( User::class )->disableOriginalConstructor()
			->setMethods( [ 'getIP', 'getRights' ] )->getMock();
		$noRateLimitUser->expects( $this->any() )->method( 'getIP' )->willReturn( '1.2.3.4' );
		$noRateLimitUser->expects( $this->any() )->method( 'getRights' )->willReturn( [ 'noratelimit' ] );
		$this->assertFalse( $noRateLimitUser->isPingLimitable() );
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

		$data = new stdClass();
		$data->user_id = 1;
		$data->user_name = 'name';
		$data->user_real_name = 'Real Name';
		$data->user_touched = 1;
		$data->user_token = 'token';
		$data->user_email = 'a@a.a';
		$data->user_email_authenticated = null;
		$data->user_email_token = 'token';
		$data->user_email_token_expires = null;
		$data->user_editcount = $editCount;
		$data->user_registration = $db->timestamp( time() - $memberSince * 86400 );
		$user = User::newFromRow( $data );

		$this->assertEquals( $expLevel, $user->getExperienceLevel() );
	}

	public function testExperienceLevelAnon() {
		$user = User::newFromName( '10.11.12.13', false );

		$this->assertFalse( $user->getExperienceLevel() );
	}
}
