<?php

namespace MediaWiki\Tests\Specials;

use MediaWiki\Exception\ErrorPageError;
use MediaWiki\Exception\UserNotLoggedIn;
use MediaWiki\MainConfigNames;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use SpecialPageTestBase;

/**
 * @covers \MediaWiki\Specials\SpecialEmailUser
 * @group Database
 */
class SpecialEmailUserTest extends SpecialPageTestBase {
	use MockAuthorityTrait;

	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValues( [
			MainConfigNames::EnableEmail => true,
			MainConfigNames::EnableUserEmail => true,
		] );
	}

	/** @dataProvider provideExecuteWhenEmailDisabled */
	public function testExecuteWhenEmailDisabled( $config ) {
		$this->overrideConfigValues( $config );

		// Executing the special page should cause an error about emailing not being enabled and should ignore that
		// the user isn't logged in (as the email not being enabled error has higher priority).
		$this->expectExceptionMessageKey( 'usermaildisabledtext' );
		$this->expectException( ErrorPageError::class );
		$this->executeSpecialPage( '', null, null, $this->mockAnonNullAuthority() );
	}

	public static function provideExecuteWhenEmailDisabled() {
		return [
			'$wgEnableEmail is false' => [ [ MainConfigNames::EnableEmail => false ] ],
			'$wgEnableUserEmail is false' => [ [ MainConfigNames::EnableUserEmail => false ] ],
		];
	}

	public function testExecuteWhenLoggedOut() {
		$this->expectExceptionMessageKey( 'mailnologintext' );
		$this->expectException( UserNotLoggedIn::class );
		$this->executeSpecialPage( '', null, null, $this->mockAnonNullAuthority() );
	}

	public function testExecuteWhenUsingTemporaryAccount() {
		$this->expectExceptionMessageKey( 'mailnologintext' );
		$this->expectException( UserNotLoggedIn::class );
		$this->executeSpecialPage( '', null, null, $this->mockTempNullAuthority() );
	}

	public function testExecuteWhenUsingNamedAccountWithoutConfirmed() {
		// Mock that a test users' email is not confirmed.
		$testUser = $this->getTestUser()->getUser();
		$this->setTemporaryHook( 'EmailConfirmed', function ( $actualUser, &$confirmed ) use ( $testUser ) {
			$confirmed = false;
			$this->assertTrue( $testUser->equals( $actualUser ) );
			return false;
		} );

		$this->expectExceptionMessageKey( 'mailnologintext' );
		$this->expectException( ErrorPageError::class );
		$this->executeSpecialPage( '', null, null, $testUser );
	}

	/**
	 * Expects that an exception is thrown where the exception message equals the English translation for the given
	 * message key.
	 */
	private function expectExceptionMessageKey( string $messageKey ): void {
		$this->expectExceptionMessage( wfMessage( $messageKey )->inLanguage( 'en' )->text() );
	}

	/** @inheritDoc */
	protected function newSpecialPage() {
		return $this->getServiceContainer()->getSpecialPageFactory()->getPage( 'Emailuser' );
	}
}
