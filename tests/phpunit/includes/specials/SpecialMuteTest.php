<?php

/**
 * @group SpecialPage
 * @covers SpecialMute
 */
class SpecialMuteTest extends SpecialPageTestBase {

	protected function setUp() {
		parent::setUp();

		$this->setMwGlobals( [
			'wgEnableUserEmailBlacklist' => true
		] );
	}

	/**
	 * @inheritDoc
	 */
	protected function newSpecialPage() {
		return new SpecialMute();
	}

	/**
	 * @covers SpecialMute::execute
	 * @expectedExceptionMessage username requested could not be found
	 * @expectedException ErrorPageError
	 */
	public function testInvalidTarget() {
		$user = $this->getTestUser()->getUser();
		$this->executeSpecialPage(
			'InvalidUser', null, 'qqx', $user
		);
	}

	/**
	 * @covers SpecialMute::execute
	 * @expectedExceptionMessage Mute features are unavailable
	 * @expectedException ErrorPageError
	 */
	public function testEmailBlacklistNotEnabled() {
		$this->setTemporaryHook(
			'SpecialMuteModifyFormFields',
			null
		);

		$this->setMwGlobals( [
			'wgEnableUserEmailBlacklist' => false
		] );

		$user = $this->getTestUser()->getUser();
		$this->executeSpecialPage(
			$user->getName(), null, 'qqx', $user
		);
	}

	/**
	 * @covers SpecialMute::execute
	 * @expectedException UserNotLoggedIn
	 */
	public function testUserNotLoggedIn() {
		$this->executeSpecialPage( 'TestUser' );
	}

	/**
	 * @covers SpecialMute::execute
	 */
	public function testMuteAddsUserToEmailBlacklist() {
		$this->setMwGlobals( [
			'wgCentralIdLookupProvider' => 'local',
		] );

		$targetUser = $this->getTestUser()->getUser();

		$loggedInUser = $this->getMutableTestUser()->getUser();
		$loggedInUser->setOption( 'email-blacklist', "999" );
		$loggedInUser->confirmEmail();
		$loggedInUser->saveSettings();

		$fauxRequest = new FauxRequest( [ 'wpemail-blacklist' => true ], true );
		list( $html, ) = $this->executeSpecialPage(
			$targetUser->getName(), $fauxRequest, 'qqx', $loggedInUser
		);

		$this->assertContains( 'specialmute-success', $html );
		$this->assertEquals(
			"999\n" . $targetUser->getId(),
			$loggedInUser->getOption( 'email-blacklist' )
		);
	}

	/**
	 * @covers SpecialMute::execute
	 */
	public function testUnmuteRemovesUserFromEmailBlacklist() {
		$this->setMwGlobals( [
			'wgCentralIdLookupProvider' => 'local',
		] );

		$targetUser = $this->getTestUser()->getUser();

		$loggedInUser = $this->getMutableTestUser()->getUser();
		$loggedInUser->setOption( 'email-blacklist', "999\n" . $targetUser->getId() );
		$loggedInUser->confirmEmail();
		$loggedInUser->saveSettings();

		$fauxRequest = new FauxRequest( [ 'wpemail-blacklist' => false ], true );
		list( $html, ) = $this->executeSpecialPage(
			$targetUser->getName(), $fauxRequest, 'qqx', $loggedInUser
		);

		$this->assertContains( 'specialmute-success', $html );
		$this->assertEquals( "999", $loggedInUser->getOption( 'email-blacklist' ) );
	}
}
