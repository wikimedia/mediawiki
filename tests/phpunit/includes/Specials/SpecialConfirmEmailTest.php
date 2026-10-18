<?php
namespace MediaWiki\Tests\Specials;

use MediaWiki\Specials\SpecialConfirmEmail;
use MediaWiki\User\UserFactory;

/**
 * @covers \MediaWiki\Specials\SpecialConfirmEmail
 * @group Database
 */
class SpecialConfirmEmailTest extends SpecialPageTestBase {
	protected function newSpecialPage() {
		return new SpecialConfirmEmail(
			$this->createMock( UserFactory::class )
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
	}

	public function testAttemptConfirmWhenAlreadyConfirmed() {
		$testUser = $this->getMutableTestUser();
		$user = $testUser->getUser();
		$user->confirmEmail();
		$user->saveSettings();

		$token = str_repeat( 'a', 32 );

		[ $html ] = $this->executeSpecialPage(
			$token,
			null,
			null,
			$testUser->getAuthority()
		);

		$this->assertStringContainsString( '(confirmemail_loggedin)', $html );
		$this->assertStringNotContainsString( '(confirmemail_invalid)', $html );
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
