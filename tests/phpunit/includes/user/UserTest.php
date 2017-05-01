<?php

define( 'NS_UNITTEST', 5600 );
define( 'NS_UNITTEST_TALK', 5601 );

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
		$user->setOption( 'cols', 200 );
		$user->saveSettings();

		$user = User::newFromName( $user->getName() );
		$this->assertEquals( 'test', $user->getOption( 'userjs-someoption' ) );
		$this->assertEquals( 200, $user->getOption( 'cols' ) );
	}

	/**
	 * Bug 37963
	 * Make sure defaults are loaded when setOption is called.
	 * @covers User::loadOptions
	 */
	public function testAnonOptions() {
		global $wgDefaultUserOptions;
		$this->user->setOption( 'userjs-someoption', 'test' );
		$this->assertEquals( $wgDefaultUserOptions['cols'], $this->user->getOption( 'cols' ) );
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
}
