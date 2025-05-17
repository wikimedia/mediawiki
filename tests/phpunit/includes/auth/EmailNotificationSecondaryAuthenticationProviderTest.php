<?php

namespace MediaWiki\Tests\Auth;

use MediaWiki\Auth\AuthenticationRequest;
use MediaWiki\Auth\AuthenticationResponse;
use MediaWiki\Auth\AuthManager;
use MediaWiki\Auth\EmailNotificationSecondaryAuthenticationProvider;
use MediaWiki\Config\HashConfig;
use MediaWiki\MainConfigNames;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Status\Status;
use MediaWiki\Tests\Unit\Auth\AuthenticationProviderTestTrait;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use MediaWiki\User\User;
use MediaWiki\User\UserNameUtils;
use MediaWikiIntegrationTestCase;
use Psr\Log\LoggerInterface;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Auth\EmailNotificationSecondaryAuthenticationProvider
 * @group Database
 */
class EmailNotificationSecondaryAuthenticationProviderTest extends MediaWikiIntegrationTestCase {
	use AuthenticationProviderTestTrait;
	use DummyServicesTrait;

	/**
	 * @param array $options
	 * @return EmailNotificationSecondaryAuthenticationProvider
	 */
	private function getProvider( array $options = [] ): EmailNotificationSecondaryAuthenticationProvider {
		$services = $this->getServiceContainer();
		$provider = new EmailNotificationSecondaryAuthenticationProvider(
			$options['dbProvider'] ?? $services->getConnectionProvider(),
			$options // make things easier for tests by using the same options
		);
		$this->initProvider(
			$provider,
			$options['config'] ?? null,
			$options['logger'] ?? null,
			$options['authManager'] ?? null,
			$options['hookContainer'] ?? null,
			$options['userNameUtils'] ?? null
		);
		return $provider;
	}

	public function testConstructor() {
		$config = new HashConfig( [
			MainConfigNames::EnableEmail => true,
			MainConfigNames::EmailAuthentication => true,
		] );

		$provider = $this->getProvider( [
			'config' => $config,
		] );
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );
		$this->assertTrue( $providerPriv->sendConfirmationEmail );

		$provider = $this->getProvider( [
			'config' => $config,
			'sendConfirmationEmail' => false,
		] );
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );
		$this->assertFalse( $providerPriv->sendConfirmationEmail );
	}

	/**
	 * @dataProvider provideGetAuthenticationRequests
	 * @param string $action
	 * @param AuthenticationRequest[] $expected
	 */
	public function testGetAuthenticationRequests( $action, $expected ) {
		$provider = $this->getProvider( [
			'sendConfirmationEmail' => true,
		] );
		$this->assertSame( $expected, $provider->getAuthenticationRequests( $action, [] ) );
	}

	public static function provideGetAuthenticationRequests() {
		return [
			[ AuthManager::ACTION_LOGIN, [] ],
			[ AuthManager::ACTION_CREATE, [] ],
			[ AuthManager::ACTION_LINK, [] ],
			[ AuthManager::ACTION_CHANGE, [] ],
			[ AuthManager::ACTION_REMOVE, [] ],
		];
	}

	public function testBeginSecondaryAuthentication() {
		$provider = $this->getProvider( [
			'sendConfirmationEmail' => true,
		] );
		$this->assertEquals( AuthenticationResponse::newAbstain(),
			$provider->beginSecondaryAuthentication( User::newFromName( 'Foo' ), [] ) );
	}

	public function testBeginSecondaryAccountCreation() {
		$mwServices = $this->getServiceContainer();
		$hookContainer = $this->createHookContainer();
		$userNameUtils = $this->createNoOpMock( UserNameUtils::class );
		$authManager = new AuthManager(
			new FauxRequest(),
			new HashConfig(),
			$this->getDummyObjectFactory(),
			$hookContainer,
			$mwServices->getReadOnlyMode(),
			$userNameUtils,
			$mwServices->getBlockManager(),
			$mwServices->getWatchlistManager(),
			$mwServices->getDBLoadBalancer(),
			$mwServices->getContentLanguage(),
			$mwServices->getLanguageConverterFactory(),
			$mwServices->getBotPasswordStore(),
			$mwServices->getUserFactory(),
			$mwServices->getUserIdentityLookup(),
			$mwServices->getUserOptionsManager(),
			$mwServices->getNotificationService(),
		);

		$creator = $this->createMock( User::class );
		$userWithoutEmail = $this->createMock( User::class );
		$userWithoutEmail->method( 'getEmail' )->willReturn( '' );
		$userWithoutEmail->method( 'getInstanceForUpdate' )->willReturnSelf();
		$userWithoutEmail->expects( $this->never() )->method( 'sendConfirmationMail' );
		$userWithEmailError = $this->createMock( User::class );
		$userWithEmailError->method( 'getEmail' )->willReturn( 'foo@bar.baz' );
		$userWithEmailError->method( 'getInstanceForUpdate' )->willReturnSelf();
		$userWithEmailError->method( 'sendConfirmationMail' )
			->willReturn( Status::newFatal( 'fail' ) );
		$userExpectsConfirmation = $this->createMock( User::class );
		$userExpectsConfirmation->method( 'getEmail' )
			->willReturn( 'foo@bar.baz' );
		$userExpectsConfirmation->method( 'getInstanceForUpdate' )
			->willReturnSelf();
		$userExpectsConfirmation->expects( $this->once() )->method( 'sendConfirmationMail' )
			->willReturn( Status::newGood() );
		$userNotExpectsConfirmation = $this->createMock( User::class );
		$userNotExpectsConfirmation->method( 'getEmail' )
			->willReturn( 'foo@bar.baz' );
		$userNotExpectsConfirmation->method( 'getInstanceForUpdate' )
			->willReturnSelf();
		$userNotExpectsConfirmation->expects( $this->never() )->method( 'sendConfirmationMail' );

		$provider = $this->getProvider( [
			'sendConfirmationEmail' => false,
			'authManager' => $authManager,
			'hookContainer' => $hookContainer,
			'userNameUtils' => $userNameUtils
		] );
		$provider->beginSecondaryAccountCreation( $userNotExpectsConfirmation, $creator, [] );

		$provider = $this->getProvider( [
			'sendConfirmationEmail' => true,
			'authManager' => $authManager,
			'userNameUtils' => $userNameUtils
		] );
		$provider->beginSecondaryAccountCreation( $userWithoutEmail, $creator, [] );
		$provider->beginSecondaryAccountCreation( $userExpectsConfirmation, $creator, [] );

		// test logging of email errors
		$logger = $this->getMockForAbstractClass( LoggerInterface::class );
		$logger->expects( $this->once() )->method( 'warning' );
		$this->initProvider( $provider, null, $logger, $authManager );
		$provider->beginSecondaryAccountCreation( $userWithEmailError, $creator, [] );

		// test disable flag used by other providers
		$authManager->setAuthenticationSessionData( 'no-email', true );
		$this->initProvider( $provider, null, null, $authManager );
		$provider->beginSecondaryAccountCreation( $userNotExpectsConfirmation, $creator, [] );
	}
}
