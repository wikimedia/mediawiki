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

		$this->setMwGlobals( array(
			'wgGroupPermissions' => array(),
			'wgRevokePermissions' => array(),
		) );

		$this->setUpPermissionGlobals();

		$this->user = new User;
		$this->user->addGroup( 'unittesters' );
	}

	private function setUpPermissionGlobals() {
		global $wgGroupPermissions, $wgRevokePermissions;

		# Data for regular $wgGroupPermissions test
		$wgGroupPermissions['unittesters'] = array(
			'test' => true,
			'runtest' => true,
			'writetest' => false,
			'nukeworld' => false,
		);
		$wgGroupPermissions['testwriters'] = array(
			'test' => true,
			'writetest' => true,
			'modifytest' => true,
		);

		# Data for regular $wgRevokePermissions test
		$wgRevokePermissions['formertesters'] = array(
			'runtest' => true,
		);

		# For the options test
		$wgGroupPermissions['*'] = array(
			'editmyoptions' => true,
		);
	}

	/**
	 * @covers User::getGroupPermissions
	 */
	public function testGroupPermissions() {
		$rights = User::getGroupPermissions( array( 'unittesters' ) );
		$this->assertContains( 'runtest', $rights );
		$this->assertNotContains( 'writetest', $rights );
		$this->assertNotContains( 'modifytest', $rights );
		$this->assertNotContains( 'nukeworld', $rights );

		$rights = User::getGroupPermissions( array( 'unittesters', 'testwriters' ) );
		$this->assertContains( 'runtest', $rights );
		$this->assertContains( 'writetest', $rights );
		$this->assertContains( 'modifytest', $rights );
		$this->assertNotContains( 'nukeworld', $rights );
	}

	/**
	 * @covers User::getGroupPermissions
	 */
	public function testRevokePermissions() {
		$rights = User::getGroupPermissions( array( 'unittesters', 'formertesters' ) );
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
		return array(
			array(
				array( 'unittesters', 'testwriters' ),
				'test'
			),
			array(
				array( 'unittesters' ),
				'runtest'
			),
			array(
				array( 'testwriters' ),
				'writetest'
			),
			array(
				array( 'testwriters' ),
				'modifytest'
			),
		);
	}

	/**
	 * @dataProvider provideIPs
	 * @covers User::isIP
	 */
	public function testIsIP( $value, $result, $message ) {
		$this->assertEquals( $this->user->isIP( $value ), $result, $message );
	}

	public static function provideIPs() {
		return array(
			array( '', false, 'Empty string' ),
			array( ' ', false, 'Blank space' ),
			array( '10.0.0.0', true, 'IPv4 private 10/8' ),
			array( '10.255.255.255', true, 'IPv4 private 10/8' ),
			array( '192.168.1.1', true, 'IPv4 private 192.168/16' ),
			array( '203.0.113.0', true, 'IPv4 example' ),
			array( '2002:ffff:ffff:ffff:ffff:ffff:ffff:ffff', true, 'IPv6 example' ),
			// Not valid IPs but classified as such by MediaWiki for negated asserting
			// of whether this might be the identifier of a logged-out user or whether
			// to allow usernames like it.
			array( '300.300.300.300', true, 'Looks too much like an IPv4 address' ),
			array( '203.0.113.xxx', true, 'Assigned by UseMod to cloaked logged-out users' ),
		);
	}

	/**
	 * @dataProvider provideUserNames
	 * @covers User::isValidUserName
	 */
	public function testIsValidUserName( $username, $result, $message ) {
		$this->assertEquals( $this->user->isValidUserName( $username ), $result, $message );
	}

	public static function provideUserNames() {
		return array(
			array( '', false, 'Empty string' ),
			array( ' ', false, 'Blank space' ),
			array( 'abcd', false, 'Starts with small letter' ),
			array( 'Ab/cd', false, 'Contains slash' ),
			array( 'Ab cd', true, 'Whitespace' ),
			array( '192.168.1.1', false, 'IP' ),
			array( 'User:Abcd', false, 'Reserved Namespace' ),
			array( '12abcd232', true, 'Starts with Numbers' ),
			array( '?abcd', true, 'Start with ? mark' ),
			array( '#abcd', false, 'Start with #' ),
			array( 'Abcdകഖഗഘ', true, ' Mixed scripts' ),
			array( 'ജോസ്‌തോമസ്', false, 'ZWNJ- Format control character' ),
			array( 'Ab　cd', false, ' Ideographic space' ),
			array( '300.300.300.300', false, 'Looks too much like an IPv4 address' ),
			array( '302.113.311.900', false, 'Looks too much like an IPv4 address' ),
			array( '203.0.113.xxx', false, 'Reserved for usage by UseMod for cloaked logged-out users' ),
		);
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

		$rightsWithMessage = array();
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
	public function testEditCount() {
		$user = User::newFromName( 'UnitTestUser' );

		if ( !$user->getId() ) {
			$user->addToDatabase();
		}

		// let the user have a few (3) edits
		$page = WikiPage::factory( Title::newFromText( 'Help:UserTest_EditCount' ) );
		for ( $i = 0; $i < 3; $i++ ) {
			$page->doEdit( (string)$i, 'test', 0, false, $user );
		}

		$user->clearInstanceCache();
		$this->assertEquals(
			3,
			$user->getEditCount(),
			'After three edits, the user edit count should be 3'
		);

		// increase the edit count and clear the cache
		$user->incEditCount();

		$user->clearInstanceCache();
		$this->assertEquals(
			4,
			$user->getEditCount(),
			'After increasing the edit count manually, the user edit count should be 4'
		);
	}

	/**
	 * Test changing user options.
	 * @covers User::setOption
	 * @covers User::getOption
	 */
	public function testOptions() {
		$user = User::newFromName( 'UnitTestUser' );

		if ( !$user->getId() ) {
			$user->addToDatabase();
		}

		$user->setOption( 'userjs-someoption', 'test' );
		$user->setOption( 'cols', 200 );
		$user->saveSettings();

		$user = User::newFromName( 'UnitTestUser' );
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
	 * Test password expiration.
	 * @covers User::getPasswordExpired()
	 */
	public function testPasswordExpire() {
		$this->setMwGlobals( 'wgPasswordExpireGrace', 3600 * 24 * 7 ); // 7 days

		$user = User::newFromName( 'UnitTestUser' );
		$user->loadDefaults( 'UnitTestUser' );
		$this->assertEquals( false, $user->getPasswordExpired() );

		$ts = time() - ( 3600 * 24 * 1 ); // 1 day ago
		$user->expirePassword( $ts );
		$this->assertEquals( 'soft', $user->getPasswordExpired() );

		$ts = time() - ( 3600 * 24 * 10 ); // 10 days ago
		$user->expirePassword( $ts );
		$this->assertEquals( 'hard', $user->getPasswordExpired() );
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
		$this->setMwGlobals( array(
			'wgPasswordPolicy' => array(
				'policies' => array(
					'sysop' => array(
						'MinimalPasswordLength' => 8,
						'MinimumPasswordLengthToLogin' => 1,
						'PasswordCannotMatchUsername' => 1,
					),
					'default' => array(
						'MinimalPasswordLength' => 6,
						'PasswordCannotMatchUsername' => true,
						'PasswordCannotMatchBlacklist' => true,
						'MaximalPasswordLength' => 30,
					),
				),
				'checks' => array(
					'MinimalPasswordLength' => 'PasswordPolicyChecks::checkMinimalPasswordLength',
					'MinimumPasswordLengthToLogin' => 'PasswordPolicyChecks::checkMinimumPasswordLengthToLogin',
					'PasswordCannotMatchUsername' => 'PasswordPolicyChecks::checkPasswordCannotMatchUsername',
					'PasswordCannotMatchBlacklist' => 'PasswordPolicyChecks::checkPasswordCannotMatchBlacklist',
					'MaximalPasswordLength' => 'PasswordPolicyChecks::checkMaximalPasswordLength',
				),
			),
		) );

		$user = User::newFromName( 'Useruser' );
		// Sanity
		$this->assertTrue( $user->isValidPassword( 'Password1234' ) );

		// Minimum length
		$this->assertFalse( $user->isValidPassword( 'a' ) );
		$this->assertFalse( $user->checkPasswordValidity( 'a' )->isGood() );
		$this->assertTrue( $user->checkPasswordValidity( 'a' )->isOK() );
		$this->assertEquals( 'passwordtooshort', $user->getPasswordValidity( 'a' ) );

		// Maximum length
		$longPass = str_repeat( 'a', 31 );
		$this->assertFalse( $user->isValidPassword( $longPass ) );
		$this->assertFalse( $user->checkPasswordValidity( $longPass )->isGood() );
		$this->assertFalse( $user->checkPasswordValidity( $longPass )->isOK() );
		$this->assertEquals( 'passwordtoolong', $user->getPasswordValidity( $longPass ) );

		// Matches username
		$this->assertFalse( $user->checkPasswordValidity( 'Useruser' )->isGood() );
		$this->assertTrue( $user->checkPasswordValidity( 'Useruser' )->isOK() );
		$this->assertEquals( 'password-name-match', $user->getPasswordValidity( 'Useruser' ) );

		// On the forbidden list
		$this->assertFalse( $user->checkPasswordValidity( 'Passpass' )->isGood() );
		$this->assertEquals( 'password-login-forbidden', $user->getPasswordValidity( 'Passpass' ) );
	}

	/**
	 * @covers User::getCanonicalName()
	 * @dataProvider provideGetCanonicalName
	 */
	public function testGetCanonicalName( $name, $expectedArray, $msg ) {
		foreach ( $expectedArray as $validate => $expected ) {
			$this->assertEquals(
				$expected,
				User::getCanonicalName( $name, $validate === 'false' ? false : $validate ),
				$msg . ' (' . $validate . ')'
			);
		}
	}

	public static function provideGetCanonicalName() {
		return array(
			array( ' Trailing space ', array( 'creatable' => 'Trailing space' ), 'Trailing spaces' ),
			// @todo FIXME: Maybe the creatable name should be 'Talk:Username' or false to reject?
			array( 'Talk:Username', array( 'creatable' => 'Username', 'usable' => 'Username',
				'valid' => 'Username', 'false' => 'Talk:Username' ), 'Namespace prefix' ),
			array( ' name with # hash', array( 'creatable' => false, 'usable' => false ), 'With hash' ),
			array( 'Multi  spaces', array( 'creatable' => 'Multi spaces',
				'usable' => 'Multi spaces' ), 'Multi spaces' ),
			array( 'lowercase', array( 'creatable' => 'Lowercase' ), 'Lowercase' ),
			array( 'in[]valid', array( 'creatable' => false, 'usable' => false, 'valid' => false,
				'false' => 'In[]valid' ), 'Invalid' ),
			array( 'with / slash', array( 'creatable' => false, 'usable' => false, 'valid' => false,
				'false' => 'With / slash' ), 'With slash' ),
		);
	}

	/**
	 * @covers User::equals
	 */
	public function testEquals() {
		$first = User::newFromName( 'EqualUser' );
		$second = User::newFromName( 'EqualUser' );

		$this->assertTrue( $first->equals( $first ) );
		$this->assertTrue( $first->equals( $second ) );
		$this->assertTrue( $second->equals( $first ) );

		$third = User::newFromName( '0' );
		$fourth = User::newFromName( '000' );

		$this->assertFalse( $third->equals( $fourth ) );
		$this->assertFalse( $fourth->equals( $third ) );

		// Test users loaded from db with id
		$user = User::newFromName( 'EqualUnitTestUser' );
		if ( !$user->getId() ) {
			$user->addToDatabase();
		}

		$id = $user->getId();

		$fifth = User::newFromId( $id );
		$sixth = User::newFromName( 'EqualUnitTestUser' );
		$this->assertTrue( $fifth->equals( $sixth ) );
	}

	/**
	 * @covers User::getId
	 */
	public function testGetId() {
		$user = User::newFromName( 'UTSysop' );
		$this->assertTrue( $user->getId() > 0 );

	}

	/**
	 * @covers User::isLoggedIn
	 * @covers User::isAnon
	 */
	public function testLoggedIn() {
		$user = User::newFromName( 'UTSysop' );
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
		$user = TestingAccessWrapper::newFromObject( User::newFromName( 'UTSysop' ) );
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

	public static function setExtendedLoginCookieDataProvider() {
		$data = array();
		$now = time();

		$secondsInDay = 86400;

		// Arbitrary durations, in units of days, to ensure it chooses the
		// right one.  There is a 5-minute grace period (see testSetExtendedLoginCookie)
		// to work around slow tests, since we're not currently mocking time() for PHP.

		$durationOne = $secondsInDay * 5;
		$durationTwo = $secondsInDay * 29;
		$durationThree = $secondsInDay * 17;

		// If $wgExtendedLoginCookieExpiration is null, then the expiry passed to
		// set cookie is time() + $wgCookieExpiration
		$data[] = array(
			null,
			$durationOne,
			$now + $durationOne,
		);

		// If $wgExtendedLoginCookieExpiration isn't null, then the expiry passed to
		// set cookie is $now + $wgExtendedLoginCookieExpiration
		$data[] = array(
			$durationTwo,
			$durationThree,
			$now + $durationTwo,
		);

		return $data;
	}

	/**
	 * @dataProvider setExtendedLoginCookieDataProvider
	 * @covers User::getRequest
	 * @covers User::setCookie
	 * @backupGlobals enabled
	 */
	public function testSetExtendedLoginCookie(
		$extendedLoginCookieExpiration,
		$cookieExpiration,
		$expectedExpiry
	) {
		$this->setMwGlobals( array(
			'wgExtendedLoginCookieExpiration' => $extendedLoginCookieExpiration,
			'wgCookieExpiration' => $cookieExpiration,
		) );

		$response = $this->getMock( 'WebResponse' );
		$setcookieSpy = $this->any();
		$response->expects( $setcookieSpy )
			->method( 'setcookie' );

		$request = new MockWebRequest( $response );
		$user = new UserProxy( User::newFromSession( $request ) );
		$user->setExtendedLoginCookie( 'name', 'value', true );

		$setcookieInvocations = $setcookieSpy->getInvocations();
		$setcookieInvocation = end( $setcookieInvocations );
		$actualExpiry = $setcookieInvocation->parameters[ 2 ];

		// TODO: ± 300 seconds compensates for
		// slow-running tests. However, the dependency on the time
		// function should be removed.  This requires some way
		// to mock/isolate User->setExtendedLoginCookie's call to time()
		$this->assertEquals( $expectedExpiry, $actualExpiry, '', 300 );
	}
}

class UserProxy extends User {

	/**
	 * @var User
	 */
	protected $user;

	public function __construct( User $user ) {
		$this->user = $user;
	}

	public function setExtendedLoginCookie( $name, $value, $secure ) {
		$this->user->setExtendedLoginCookie( $name, $value, $secure );
	}
}
