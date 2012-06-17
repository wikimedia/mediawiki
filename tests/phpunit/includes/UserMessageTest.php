<?php

/**
 * @group Database
 */
class UserMessageTest extends MediaWikiTestCase {
	
	/**
	 * @var User
	 */
	protected $user;

	public function setUp() {
		global $wgGroupPermissions, $wgRevokePermissions;
		parent::setUp();

		$this->savedGroupPermissions = $wgGroupPermissions;
		$this->savedRevokedPermissions = $wgRevokePermissions;

		$this->setUpPermissionGlobals();
		$this->setUpUser();

		UserMessage::clearMessages( $this->user );
	}
	
	public function testDelivery() {
		$msg = new UserMessage( $this->user, UserMessage::MSG_TALK );
		$this->assertTrue( $msg->update( UserMessage::SET ) );
		$this->assertEquals( $msg->getStatus(), UserMessage::SET );
		
		$msg = new UserMessage( $this->user, UserMessage::MSG_TALK );
		$msg->load();
		$this->assertEquals( $msg->getStatus(), UserMessage::SET );
		
		$msgs = UserMessage::getUserMessages( $this->user );
		$this->assertGreaterThanOrEqual( count( $msgs ), 1 );
		$this->assertEquals( $msgs[0]->getStatus(), UserMessage::SET );
		$this->assertEquals( $msgs[0]->getType(), UserMessage::MSG_TALK );
	}
	
	/**
	 * @depends testDelivery
	 */
	public function testClear() {
		$msg = new UserMessage( $this->user, UserMessage::MSG_TALK );
		$msg->update( UserMessage::SET );
		$msg->load();
		$this->assertEquals( $msg->getStatus(), UserMessage::SET );
		
		$this->assertTrue( $msg->update( UserMessage::NOTSET ) );
		$this->assertEquals( $msg->getStatus(), UserMessage::NOTSET );
		
		$msg = new UserMessage( $this->user, UserMessage::MSG_TALK );
		$msg->load();
		$this->assertEquals( $msg->getStatus(), UserMessage::NOTSET );
		
		$msgs = UserMessage::getUserMessages( $this->user );
		$this->assertEmpty( $msgs );
	}
	
	private function setUpUser() {
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
	}
}