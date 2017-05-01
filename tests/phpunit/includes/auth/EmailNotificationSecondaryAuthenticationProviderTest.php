<?php

namespace MediaWiki\Auth;

use Psr\Log\LoggerInterface;
use Wikimedia\TestingAccessWrapper;

class EmailNotificationSecondaryAuthenticationProviderTest extends \PHPUnit_Framework_TestCase {
	public function testConstructor() {
		$config = new \HashConfig( [
			'EnableEmail' => true,
			'EmailAuthentication' => true,
		] );

		$provider = new EmailNotificationSecondaryAuthenticationProvider();
		$provider->setConfig( $config );
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );
		$this->assertTrue( $providerPriv->sendConfirmationEmail );

		$provider = new EmailNotificationSecondaryAuthenticationProvider( [
			'sendConfirmationEmail' => false,
		] );
		$provider->setConfig( $config );
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );
		$this->assertFalse( $providerPriv->sendConfirmationEmail );
	}

	/**
	 * @dataProvider provideGetAuthenticationRequests
	 * @param string $action
	 * @param AuthenticationRequest[] $expected
	 */
	public function testGetAuthenticationRequests( $action, $expected ) {
		$provider = new EmailNotificationSecondaryAuthenticationProvider( [
			'sendConfirmationEmail' => true,
		] );
		$this->assertSame( $expected, $provider->getAuthenticationRequests( $action, [] ) );
	}

	public function provideGetAuthenticationRequests() {
		return [
			[ AuthManager::ACTION_LOGIN, [] ],
			[ AuthManager::ACTION_CREATE, [] ],
			[ AuthManager::ACTION_LINK, [] ],
			[ AuthManager::ACTION_CHANGE, [] ],
			[ AuthManager::ACTION_REMOVE, [] ],
		];
	}

	public function testBeginSecondaryAuthentication() {
		$provider = new EmailNotificationSecondaryAuthenticationProvider( [
			'sendConfirmationEmail' => true,
		] );
		$this->assertEquals( AuthenticationResponse::newAbstain(),
			$provider->beginSecondaryAuthentication( \User::newFromName( 'Foo' ), [] ) );
	}

	public function testBeginSecondaryAccountCreation() {
		$authManager = new AuthManager( new \FauxRequest(), new \HashConfig() );

		$creator = $this->getMockBuilder( 'User' )->getMock();
		$userWithoutEmail = $this->getMockBuilder( 'User' )->getMock();
		$userWithoutEmail->expects( $this->any() )->method( 'getEmail' )->willReturn( '' );
		$userWithoutEmail->expects( $this->any() )->method( 'getInstanceForUpdate' )->willReturnSelf();
		$userWithoutEmail->expects( $this->never() )->method( 'sendConfirmationMail' );
		$userWithEmailError = $this->getMockBuilder( 'User' )->getMock();
		$userWithEmailError->expects( $this->any() )->method( 'getEmail' )->willReturn( 'foo@bar.baz' );
		$userWithEmailError->expects( $this->any() )->method( 'getInstanceForUpdate' )->willReturnSelf();
		$userWithEmailError->expects( $this->any() )->method( 'sendConfirmationMail' )
			->willReturn( \Status::newFatal( 'fail' ) );
		$userExpectsConfirmation = $this->getMockBuilder( 'User' )->getMock();
		$userExpectsConfirmation->expects( $this->any() )->method( 'getEmail' )
			->willReturn( 'foo@bar.baz' );
		$userExpectsConfirmation->expects( $this->any() )->method( 'getInstanceForUpdate' )
			->willReturnSelf();
		$userExpectsConfirmation->expects( $this->once() )->method( 'sendConfirmationMail' )
			->willReturn( \Status::newGood() );
		$userNotExpectsConfirmation = $this->getMockBuilder( 'User' )->getMock();
		$userNotExpectsConfirmation->expects( $this->any() )->method( 'getEmail' )
			->willReturn( 'foo@bar.baz' );
		$userNotExpectsConfirmation->expects( $this->any() )->method( 'getInstanceForUpdate' )
			->willReturnSelf();
		$userNotExpectsConfirmation->expects( $this->never() )->method( 'sendConfirmationMail' );

		$provider = new EmailNotificationSecondaryAuthenticationProvider( [
			'sendConfirmationEmail' => false,
		] );
		$provider->setManager( $authManager );
		$provider->beginSecondaryAccountCreation( $userNotExpectsConfirmation, $creator, [] );

		$provider = new EmailNotificationSecondaryAuthenticationProvider( [
			'sendConfirmationEmail' => true,
		] );
		$provider->setManager( $authManager );
		$provider->beginSecondaryAccountCreation( $userWithoutEmail, $creator, [] );
		$provider->beginSecondaryAccountCreation( $userExpectsConfirmation, $creator, [] );

		// test logging of email errors
		$logger = $this->getMockForAbstractClass( LoggerInterface::class );
		$logger->expects( $this->once() )->method( 'warning' );
		$provider->setLogger( $logger );
		$provider->beginSecondaryAccountCreation( $userWithEmailError, $creator, [] );

		// test disable flag used by other providers
		$authManager->setAuthenticationSessionData( 'no-email', true );
		$provider->setManager( $authManager );
		$provider->beginSecondaryAccountCreation( $userNotExpectsConfirmation, $creator, [] );
	}
}
