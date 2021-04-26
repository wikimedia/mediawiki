<?php

namespace MediaWiki\Auth;

use Config;
use MediaWiki\MediaWikiServices;
use MediaWiki\User\UserNameUtils;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Auth\EmailNotificationSecondaryAuthenticationProvider
 */
class EmailNotificationSecondaryAuthenticationProviderTest extends \MediaWikiIntegrationTestCase {
	public function testConstructor() {
		$config = new \HashConfig( [
			'EnableEmail' => true,
			'EmailAuthentication' => true,
		] );

		$provider = new EmailNotificationSecondaryAuthenticationProvider();
		$provider->init(
			$this->createNoOpMock( NullLogger::class ),
			$this->createNoOpMock( AuthManager::class ),
			$this->createHookContainer(),
			$config,
			$this->createNoOpMock( UserNameUtils::class )
		);
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );
		$this->assertTrue( $providerPriv->sendConfirmationEmail );

		$provider = new EmailNotificationSecondaryAuthenticationProvider( [
			'sendConfirmationEmail' => false,
		] );
		$provider->init(
			$this->createNoOpMock( NullLogger::class ),
			$this->createNoOpMock( AuthManager::class ),
			$this->createHookContainer(),
			$config,
			$this->createNoOpMock( UserNameUtils::class )
		);
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
		$mwServices = MediaWikiServices::getInstance();
		$services = $this->createNoOpAbstractMock( ContainerInterface::class );
		$objectFactory = new \Wikimedia\ObjectFactory( $services );
		$hookContainer = $this->createHookContainer();
		$userNameUtils = $this->createNoOpMock( UserNameUtils::class );
		$authManager = new AuthManager(
			new \FauxRequest(),
			new \HashConfig(),
			$objectFactory,
			$hookContainer,
			$mwServices->getReadOnlyMode(),
			$userNameUtils,
			$mwServices->getBlockManager(),
			$mwServices->getBlockErrorFormatter(),
			$mwServices->getWatchlistManager()
		);

		$creator = $this->getMockBuilder( \User::class )->getMock();
		$userWithoutEmail = $this->getMockBuilder( \User::class )->getMock();
		$userWithoutEmail->method( 'getEmail' )->willReturn( '' );
		$userWithoutEmail->method( 'getInstanceForUpdate' )->willReturnSelf();
		$userWithoutEmail->expects( $this->never() )->method( 'sendConfirmationMail' );
		$userWithEmailError = $this->getMockBuilder( \User::class )->getMock();
		$userWithEmailError->method( 'getEmail' )->willReturn( 'foo@bar.baz' );
		$userWithEmailError->method( 'getInstanceForUpdate' )->willReturnSelf();
		$userWithEmailError->method( 'sendConfirmationMail' )
			->willReturn( \Status::newFatal( 'fail' ) );
		$userExpectsConfirmation = $this->getMockBuilder( \User::class )->getMock();
		$userExpectsConfirmation->method( 'getEmail' )
			->willReturn( 'foo@bar.baz' );
		$userExpectsConfirmation->method( 'getInstanceForUpdate' )
			->willReturnSelf();
		$userExpectsConfirmation->expects( $this->once() )->method( 'sendConfirmationMail' )
			->willReturn( \Status::newGood() );
		$userNotExpectsConfirmation = $this->getMockBuilder( \User::class )->getMock();
		$userNotExpectsConfirmation->method( 'getEmail' )
			->willReturn( 'foo@bar.baz' );
		$userNotExpectsConfirmation->method( 'getInstanceForUpdate' )
			->willReturnSelf();
		$userNotExpectsConfirmation->expects( $this->never() )->method( 'sendConfirmationMail' );

		$provider = new EmailNotificationSecondaryAuthenticationProvider( [
			'sendConfirmationEmail' => false,
		] );
		$provider->init(
			$this->createNoOpMock( NullLogger::class ),
			$authManager,
			$hookContainer,
			$this->createNoOpAbstractMock( Config::class ),
			$userNameUtils
		);
		$provider->beginSecondaryAccountCreation( $userNotExpectsConfirmation, $creator, [] );

		$provider = new EmailNotificationSecondaryAuthenticationProvider( [
			'sendConfirmationEmail' => true,
		] );
		$provider->init(
			$this->createNoOpMock( NullLogger::class ),
			$authManager,
			$this->createHookContainer(),
			$this->createNoOpAbstractMock( Config::class ),
			$userNameUtils
		);
		$provider->beginSecondaryAccountCreation( $userWithoutEmail, $creator, [] );
		$provider->beginSecondaryAccountCreation( $userExpectsConfirmation, $creator, [] );

		// test logging of email errors
		$logger = $this->getMockForAbstractClass( LoggerInterface::class );
		$logger->expects( $this->once() )->method( 'warning' );
		$provider->init(
			$logger,
			$authManager,
			$this->createHookContainer(),
			$this->createNoOpAbstractMock( Config::class ),
			$userNameUtils
		);
		$provider->beginSecondaryAccountCreation( $userWithEmailError, $creator, [] );

		// test disable flag used by other providers
		$authManager->setAuthenticationSessionData( 'no-email', true );
		$provider->init(
			$this->createNoOpMock( NullLogger::class ),
			$authManager,
			$this->createHookContainer(),
			$this->createNoOpAbstractMock( Config::class ),
			$userNameUtils
		);
		$provider->beginSecondaryAccountCreation( $userNotExpectsConfirmation, $creator, [] );
	}
}
