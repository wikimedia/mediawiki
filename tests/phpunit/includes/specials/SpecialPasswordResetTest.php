<?php

use MediaWiki\MainConfigNames;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Specials\SpecialPasswordReset;
use MediaWiki\Tests\SpecialPage\FormSpecialPageTestCase;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;

/**
 * @covers \MediaWiki\Specials\SpecialPasswordReset
 * @group Database
 */
class SpecialPasswordResetTest extends FormSpecialPageTestCase {

	use TempUserTestTrait;

	protected function setUp(): void {
		parent::setUp();

		// Test the default primary and secondary authentication providers
		// irrespective of any potentially conflicting local configuration.
		$authConfig = $this->getServiceContainer()
			->getConfigSchema()
			->getDefaultFor( MainConfigNames::AuthManagerAutoConfig );

		$this->overrideConfigValues( [
			MainConfigNames::AuthManagerConfig => null,
			MainConfigNames::AuthManagerAutoConfig => $authConfig
		] );

		// Avoid failures if CentralAuth is loaded and configured to use SUL3 locally.
		$this->clearHook( 'AuthManagerFilterProviders' );
	}

	/**
	 * @inheritDoc
	 */
	protected function newSpecialPage() {
		return new SpecialPasswordReset(
			$this->getServiceContainer()->getPasswordReset()
		);
	}

	public function testView() {
		[ $html ] = $this->executeSpecialPage();
		// Check that the form fields are as expected
		$this->assertStringContainsString( '(passwordreset-username', $html );
		$this->assertStringContainsString( '(passwordreset-email', $html );
		$this->assertStringContainsString( '(passwordreset-text-many', $html );
		$this->assertStringContainsString( '(mailmypassword', $html );
	}

	public function testExecuteForTemporaryAccountUsername() {
		// Get an existing temporary account to test with
		$this->enableAutoCreateTempUser();
		$tempUser = $this->getServiceContainer()->getTempUserCreator()->create( null, new FauxRequest() )->getUser();
		// Make a fake POST request that tries to use a temporary account for the password
		$request = new FauxRequest(
			[ 'wpUsername' => $tempUser->getName() ],
			true
		);
		[ $html ] = $this->executeSpecialPage( '', $request );
		$this->assertStringContainsString( '(htmlform-user-not-valid', $html );
	}

	/**
	 * @dataProvider provideSuccessfulReset
	 */
	public function testSuccessfulReset( string $email ): void {
		$user = $this->getMutableTestUser()->getUser();
		$user->setEmail( 'test@example.com' );
		$user->saveSettings();

		$request = new FauxRequest( [
			'wpUsername' => $user->getName(),
			'wpEmail' => $email,
		], true );

		[ $html ] = $this->executeSpecialPage( '', $request );

		$this->assertStringContainsString( "(passwordreset-username) {$user->getName()}", $html );
		$this->assertStringContainsString( "(passwordreset-email) {$email}", $html );
	}

	public static function provideSuccessfulReset(): iterable {
		yield 'matching email' => [ 'test@example.com' ];
		yield 'different email' => [ 'other@example.com' ];
	}
}
