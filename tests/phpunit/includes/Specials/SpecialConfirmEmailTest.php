<?php
namespace MediaWiki\Tests\Specials;

use MediaWiki\Specials\SpecialConfirmEmail;
use MediaWiki\Utils\MWTimestamp;

/**
 * @covers \MediaWiki\Specials\SpecialConfirmEmail
 * @group Database
 */
class SpecialConfirmEmailTest extends SpecialPageTestBase {
	protected function newSpecialPage() {
		return new SpecialConfirmEmail(
			$this->getServiceContainer()->getUserFactory()
		);
	}

	public function testExecute() {
		[ $html ] = $this->executeSpecialPage(
			'',
			null,
			null,
			$this->getTestUser()->getAuthority()
		);

		$this->assertStringContainsString( '(confirmemail_text)', $html );
		$this->assertStringNotContainsString( '(emailauthenticated:', $html );
	}

	public function testExecuteConfirmed() {
		$testUser = $this->getMutableTestUser();
		$user = $testUser->getUser();
		$user->confirmEmail();
		$user->saveSettings();

		[ $html ] = $this->executeSpecialPage(
			'',
			null,
			null,
			$testUser->getAuthority()
		);

		$this->assertStringNotContainsString( '(confirmemail_text)', $html );
		$this->assertStringContainsString( '(emailauthenticated:', $html );
	}

	public function testAttemptConfirmNew() {
		$testUser = $this->getMutableTestUser();
		$user = $testUser->getUser();
		$token = $user->getConfirmationToken( $unused );
		$user->saveSettings();

		[ $html ] = $this->executeSpecialPage(
			$token,
			null,
			null,
			$testUser->getAuthority()
		);

		$this->assertStringContainsString( '(confirmemail_loggedin)', $html );
		$this->assertStringNotContainsString( '(confirmemail_alreadydone)', $html );
		$this->assertStringNotContainsString( '(confirmemail_invalid)', $html );
	}

	public function testAttemptConfirmNewOther() {
		$testUser = $this->getMutableTestUser();
		$user = $testUser->getUser();
		$token = $user->getConfirmationToken( $unused );
		$user->saveSettings();

		[ $html ] = $this->executeSpecialPage(
			$token,
			null,
			null,
			$this->getTestSysop()->getAuthority()
		);

		$this->assertStringContainsString( '(confirmemail_other:', $html );
		$this->assertStringNotContainsString( '(confirmemail_alreadydone)', $html );
		$this->assertStringNotContainsString( '(confirmemail_invalid)', $html );
	}

	public function testAttemptConfirmWhenAlreadyConfirmed() {
		$testUser = $this->getMutableTestUser();
		$user = $testUser->getUser();
		$token = $user->getConfirmationToken( $unused );
		$user->confirmEmail();
		$user->saveSettings();

		[ $html ] = $this->executeSpecialPage(
			$token,
			null,
			null,
			$testUser->getAuthority()
		);

		$this->assertStringContainsString( '(confirmemail_alreadydone)', $html );
		$this->assertStringContainsString( '(emailauthenticated:', $html );
		$this->assertStringNotContainsString( '(confirmemail_invalid)', $html );
	}

	public function testAttemptConfirmOtherWhenAlreadyConfirmed() {
		$testUser = $this->getMutableTestUser();
		$user = $testUser->getUser();
		$token = $user->getConfirmationToken( $unused );
		$user->confirmEmail();
		$user->saveSettings();

		[ $html ] = $this->executeSpecialPage(
			$token,
			null,
			null,
			$this->getTestSysop()->getAuthority()
		);

		$this->assertStringContainsString( '(confirmemail_alreadydone_other:', $html );
		$this->assertStringNotContainsString( '(emailauthenticated:', $html );
		$this->assertStringNotContainsString( '(confirmemail_invalid)', $html );
	}

	public function testAttemptConfirmExpiredToken() {
		$testUser = $this->getMutableTestUser();
		$user = $testUser->getUser();
		$token = $user->getConfirmationToken( $expiration );
		$user->saveSettings();

		MWTimestamp::setFakeTime( $expiration );

		[ $html ] = $this->executeSpecialPage(
			$token,
			null,
			null,
			$testUser->getAuthority()
		);

		$this->assertStringNotContainsString( '(confirmemail_loggedin)', $html );
		$this->assertStringNotContainsString( '(confirmemail_alreadydone)', $html );
		$this->assertStringContainsString( '(confirmemail_invalid)', $html );
	}

	public function testAttemptConfirmInvalidToken() {
		$testUser = $this->getMutableTestUser();
		$token = str_repeat( 'a', 32 );

		[ $html ] = $this->executeSpecialPage(
			$token,
			null,
			null,
			$testUser->getAuthority()
		);

		$this->assertStringContainsString( '(confirmemail_invalid)', $html );
	}

	public function testAttemptConfirmInvalidFormat() {
		$testUser = $this->getMutableTestUser();
		$user = $testUser->getUser();
		$user->confirmEmail();
		$user->saveSettings();

		[ $html ] = $this->executeSpecialPage(
			'invalid-format',
			null,
			null,
			$testUser->getAuthority()
		);

		$this->assertStringContainsString( '(confirmemail_invalid_format)', $html );
	}
}
