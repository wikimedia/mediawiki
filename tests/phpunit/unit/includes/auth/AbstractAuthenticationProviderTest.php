<?php

namespace MediaWiki\Tests\Unit\Auth;

use MediaWiki\Auth\AbstractAuthenticationProvider;
use MediaWiki\Auth\AuthManager;
use MediaWiki\Config\HashConfig;
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

		// test AbstractAuthenticationProvider::getUniqueId
		$this->assertIsString( $provider->getUniqueId(), 'getUniqueId' );
	}
}
