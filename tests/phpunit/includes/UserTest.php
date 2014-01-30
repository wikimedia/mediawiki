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
		);
	}

	/**
	 * Test, if for all rights a right- message exist,
	 * which is used on Special:ListGroupRights as help text
	 * Extensions and core
	 */
	public function testAllRightsWithMessage() {
		//Getting all user rights, for core: User::$mCoreRights, for extensions: $wgAvailableRights
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
		$user->loadDefaults();
		$user->addToDatabase();

		// let the user have a few (3) edits
		$page = WikiPage::factory( Title::newFromText( 'Help:UserTest_EditCount' ) );
		for ( $i = 0; $i < 3; $i++ ) {
			$page->doEdit( (string)$i, 'test', 0, false, $user );
		}

		$user->clearInstanceCache();
		$this->assertEquals( 3, $user->getEditCount(), 'After three edits, the user edit count should be 3' );

		// increase the edit count and clear the cache
		$user->incEditCount();

		$user->clearInstanceCache();
		$this->assertEquals( 4, $user->getEditCount(), 'After increasing the edit count manually, the user edit count should be 4' );
	}

	/**
	 * Test changing user options.
	 * @covers User::setOption
	 * @covers User::getOption
	 */
	public function testOptions() {
		$user = User::newFromName( 'UnitTestUser' );
		$user->addToDatabase();

		$user->setOption( 'someoption', 'test' );
		$user->setOption( 'cols', 200 );
		$user->saveSettings();

		$user = User::newFromName( 'UnitTestUser' );
		$this->assertEquals( 'test', $user->getOption( 'someoption' ) );
		$this->assertEquals( 200, $user->getOption( 'cols' ) );
	}

	/**
	 * Bug 37963
	 * Make sure defaults are loaded when setOption is called.
	 * @covers User::loadOptions
	 */
	public function testAnonOptions() {
		global $wgDefaultUserOptions;
		$this->user->setOption( 'someoption', 'test' );
		$this->assertEquals( $wgDefaultUserOptions['cols'], $this->user->getOption( 'cols' ) );
		$this->assertEquals( 'test', $this->user->getOption( 'someoption' ) );
	}
}
