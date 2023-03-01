<?php

namespace MediaWiki\Tests\Unit\Auth;

use HashConfig;
use MediaWiki\Auth\AbstractAuthenticationProvider;
use MediaWiki\Auth\AuthManager;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\User\UserNameUtils;
use Psr\Log\LoggerInterface;
use Wikimedia\TestingAccessWrapper;

/**
 * @group AuthManager
 * @covers \MediaWiki\Auth\AbstractAuthenticationProvider
 */
class AbstractAuthenticationProviderTest extends \MediaWikiUnitTestCase {
	use AuthenticationProviderTestTrait;

	public function testAbstractAuthenticationProvider() {
		$this->hideDeprecated( 'MediaWiki\Auth\AbstractAuthenticationProvider::setConfig' );
		$this->hideDeprecated( 'MediaWiki\Auth\AbstractAuthenticationProvider::setLogger' );
		$this->hideDeprecated( 'MediaWiki\Auth\AbstractAuthenticationProvider::setManager' );
		$this->hideDeprecated( 'MediaWiki\Auth\AbstractAuthenticationProvider::setHookContainer' );

		$provider = $this->getMockForAbstractClass( AbstractAuthenticationProvider::class );
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );

		// test AbstractAuthenticationProvider::init
		$logger = $this->getMockForAbstractClass( LoggerInterface::class );
		$authManager = $this->createMock( AuthManager::class );
		$hookContainer = $this->createMock( HookContainer::class );
		$config = new HashConfig();
		$userNameUtils = $this->createNoOpMock( UserNameUtils::class );
		$this->initProvider( $provider, $config, $logger, $authManager, $hookContainer, $userNameUtils );
		$this->assertSame( $logger, $providerPriv->logger );
		$this->assertSame( $authManager, $providerPriv->manager );
		$this->assertSame( $hookContainer, $providerPriv->hookContainer );
		$this->assertSame( $config, $providerPriv->config );
		$this->assertSame( $userNameUtils, $providerPriv->userNameUtils );

		// test AbstractAuthenticationProvider::setLogger
		$obj = $this->getMockForAbstractClass( LoggerInterface::class );
		$provider->setLogger( $obj );
		$this->assertSame( $obj, $providerPriv->logger, 'setLogger' );

		// test AbstractAuthenticationProvider::setManager
		$obj = $this->createMock( AuthManager::class );
		$provider->setManager( $obj );
		$this->assertSame( $obj, $providerPriv->manager, 'setManager' );

		// test AbstractAuthenticationProvider::setConfig
		$obj = new HashConfig();
		$provider->setConfig( $obj );
		$this->assertSame( $obj, $providerPriv->config, 'setConfig' );

		// test AbstractAuthenticationProvider::setHookContainer
		$obj = $this->createHookContainer();
		$provider->setHookContainer( $obj );
		$this->assertSame( $obj, $providerPriv->hookContainer, 'setHookContainer' );

		// test AbstractAuthenticationProvider::getUniqueId
		$this->assertIsString( $provider->getUniqueId(), 'getUniqueId' );
	}
}
