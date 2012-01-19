<?php

define( 'NS_UNITTEST', 5600 );
define( 'NS_UNITTEST_TALK', 5601 );

/**
 * @group Database
 */
class UserTest extends MediaWikiTestCase {
	protected $savedGroupPermissions, $savedRevokedPermissions;

	/**
	 * @var User
	 */
	protected $user;

	public function setUp() {
		parent::setUp();

		$this->savedGroupPermissions = $GLOBALS['wgGroupPermissions'];
		$this->savedRevokedPermissions = $GLOBALS['wgRevokePermissions'];

		$this->setUpPermissionGlobals();
		$this->setUpUser();
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
	}
	private function setUpUser() {
		$this->user = new User;
		$this->user->addGroup( 'unittesters' );
	}

	public function tearDown() {
		parent::tearDown();

		$GLOBALS['wgGroupPermissions'] = $this->savedGroupPermissions;
		$GLOBALS['wgRevokePermissions'] = $this->savedRevokedPermissions;
	}

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
	public function testRevokePermissions() {
		$rights = User::getGroupPermissions( array( 'unittesters', 'formertesters' ) );
		$this->assertNotContains( 'runtest', $rights );
		$this->assertNotContains( 'writetest', $rights );
		$this->assertNotContains( 'modifytest', $rights );
		$this->assertNotContains( 'nukeworld', $rights );
	}

	public function testUserPermissions() {
		$rights = $this->user->getRights();
		$this->assertContains( 'runtest', $rights );
		$this->assertNotContains( 'writetest', $rights );
		$this->assertNotContains( 'modifytest', $rights );
		$this->assertNotContains( 'nukeworld', $rights );
	}

	/**
	 * @dataProvider provideGetGroupsWithPermission
	 */
	public function testGetGroupsWithPermission( $expected, $right ) {
		$result = User::getGroupsWithPermission( $right );
		sort( $result );
		sort( $expected );

		$this->assertEquals( $expected, $result, "Groups with permission $right" );
	}

	public function provideGetGroupsWithPermission() {
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
	 */
	public function testIsValidUserName( $username, $result, $message ) {
		$this->assertEquals( $this->user->isValidUserName( $username ), $result, $message );
	}

	public function provideUserNames() {
		return array(
			array( '', false, 'Empty string' ),
			array( ' ', false, 'Blank space' ),
			array( 'abcd', false, 'Starts with small letter' ),
			array( 'Ab/cd', false,  'Contains slash' ),
			array( 'Ab cd' , true, 'Whitespace' ),
			array( '192.168.1.1', false,  'IP' ),
			array( 'User:Abcd', false, 'Reserved Namespace' ),
			array( '12abcd232' , true  , 'Starts with Numbers' ),
			array( '?abcd' , true,  'Start with ? mark' ),
			array( '#abcd', false, 'Start with #' ),
			array( 'Abcdകഖഗഘ', true,  ' Mixed scripts' ),
			array( 'ജോസ്‌തോമസ്',  false, 'ZWNJ- Format control character' ),
			array( 'Ab　cd', false, ' Ideographic space' ),
		);
	}
}
