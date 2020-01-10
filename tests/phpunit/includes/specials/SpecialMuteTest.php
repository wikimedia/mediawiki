<?php

/**
 * @group SpecialPage
 * @covers SpecialMute
 */
class SpecialMuteTest extends SpecialPageTestBase {

	protected function setUp() : void {
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
	 */
	public function testInvalidTarget() {
		$user = $this->getTestUser()->getUser();
		$this->expectException( ErrorPageError::class );
		$this->expectExceptionMessage( "username requested could not be found" );
		$this->executeSpecialPage(
			'InvalidUser', null, 'qqx', $user
		);
	}

	/**
	 * @covers SpecialMute::execute
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
		$this->expectException( ErrorPageError::class );
		$this->expectExceptionMessage( "Mute features are unavailable" );
		$this->executeSpecialPage(
			$user->getName(), null, 'qqx', $user
		);
	}

	/**
	 * @covers SpecialMute::execute
	 */
	public function testUserNotLoggedIn() {
		$this->expectException( UserNotLoggedIn::class );
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

		$this->assertStringContainsString( 'specialmute-success', $html );
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

		$this->assertStringContainsString( 'specialmute-success', $html );
		$this->assertSame( "999", $loggedInUser->getOption( 'email-blacklist' ) );
	}
}
