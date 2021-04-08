<?php

namespace MediaWiki\Tests\Unit\Auth;

use MediaWiki\Auth\AbstractAuthenticationProvider;
use MediaWiki\Auth\AuthManager;
use Wikimedia\TestingAccessWrapper;

/**
 * @group AuthManager
 * @covers \MediaWiki\Auth\AbstractAuthenticationProvider
 */
class AbstractAuthenticationProviderTest extends \MediaWikiUnitTestCase {
	public function testAbstractAuthenticationProvider() {
		$provider = $this->getMockForAbstractClass( AbstractAuthenticationProvider::class );
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );

		$obj = $this->getMockForAbstractClass( \Psr\Log\LoggerInterface::class );
		$provider->setLogger( $obj );
		$this->assertSame( $obj, $providerPriv->logger, 'setLogger' );

		$obj = $this->createMock( AuthManager::class );
		$provider->setManager( $obj );
		$this->assertSame( $obj, $providerPriv->manager, 'setManager' );

		$obj = $this->getMockForAbstractClass( \Config::class );
		$provider->setConfig( $obj );
		$this->assertSame( $obj, $providerPriv->config, 'setConfig' );

		$this->assertIsString( $provider->getUniqueId(), 'getUniqueId' );
	}
}
