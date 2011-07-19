<?php

class UserTest extends MediaWikiTestCase {
	protected $savedGroupPermissions, $savedRevokedPermissions;
	
	public function setUp() {
		parent::setUp();
		
		$this->savedGroupPermissions = $GLOBALS['wgGroupPermissions'];
		$this->savedRevokedPermissions = $GLOBALS['wgRevokePermissions'];
		
		$this->setUpPermissionGlobals();
	}
	private function setUpPermissionGlobals() {
		global $wgGroupPermissions, $wgRevokePermissions;
		
		$wgGroupPermissions['unittesters'] = array(
			'runtest' => true,
			'writetest' => false,
			'nukeworld' => false,
		);
		$wgGroupPermissions['testwriters'] = array(
			'writetest' => true,
			'modifytest' => true,
		);
		
		$wgRevokePermissions['formertesters'] = array(
			'runtest' => true,
		);
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
}