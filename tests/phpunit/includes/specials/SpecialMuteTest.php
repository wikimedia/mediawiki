<?php

use MediaWiki\MainConfigNames;
use MediaWiki\User\UserOptionsManager;

/**
 * @group SpecialPage
 * @covers SpecialMute
 */
class SpecialMuteTest extends SpecialPageTestBase {

	/** @var UserOptionsManager */
	private $userOptionsManager;

	protected function setUp(): void {
		parent::setUp();

		$this->userOptionsManager = $this->getServiceContainer()->getUserOptionsManager();
		$this->overrideConfigValue( MainConfigNames::EnableUserEmailMuteList, true );
	}

	/**
	 * @inheritDoc
	 */
	protected function newSpecialPage() {
		return new SpecialMute(
			$this->getServiceContainer()->getCentralIdLookupFactory()->getLookup( 'local' ),
			$this->userOptionsManager,
			$this->getServiceContainer()->getUserIdentityLookup()
		);
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

		$this->overrideConfigValue( MainConfigNames::EnableUserEmailMuteList, false );

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
		$targetUser = $this->getTestUser()->getUser();

		$loggedInUser = $this->getMutableTestUser()->getUser();
		$this->userOptionsManager->setOption( $loggedInUser, 'email-blacklist', "999" );
		$loggedInUser->confirmEmail();
		$loggedInUser->saveSettings();

		$fauxRequest = new FauxRequest( [ 'wpemail-blacklist' => true ], true );
		list( $html, ) = $this->executeSpecialPage(
			$targetUser->getName(), $fauxRequest, 'qqx', $loggedInUser
		);

		$this->assertStringContainsString( 'specialmute-success', $html );
		$this->assertEquals(
			"999\n" . $targetUser->getId(),
			$this->userOptionsManager->getOption( $loggedInUser, 'email-blacklist' )
		);
	}

	/**
	 * @covers SpecialMute::execute
	 */
	public function testUnmuteRemovesUserFromEmailBlacklist() {
		$targetUser = $this->getTestUser()->getUser();

		$loggedInUser = $this->getMutableTestUser()->getUser();
		$this->userOptionsManager->setOption( $loggedInUser, 'email-blacklist', "999\n" . $targetUser->getId() );
		$loggedInUser->confirmEmail();
		$loggedInUser->saveSettings();

		$fauxRequest = new FauxRequest( [ 'wpemail-blacklist' => false ], true );
		list( $html, ) = $this->executeSpecialPage(
			$targetUser->getName(), $fauxRequest, 'qqx', $loggedInUser
		);

		$this->assertStringContainsString( 'specialmute-success', $html );
		$this->assertSame( "999", $this->userOptionsManager->getOption( $loggedInUser, 'email-blacklist' ) );
	}
}
