<?php

namespace MediaWiki\Auth;

use MediaWiki\MediaWikiServices;
use Wikimedia\TestingAccessWrapper;

/**
 * @group AuthManager
 * @covers \MediaWiki\Auth\AbstractAuthenticationProvider
 */
class AbstractAuthenticationProviderTest extends \MediaWikiIntegrationTestCase {
	public function testAbstractAuthenticationProvider() {
		$provider = $this->getMockForAbstractClass( AbstractAuthenticationProvider::class );
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );

		$obj = $this->getMockForAbstractClass( \Psr\Log\LoggerInterface::class );
		$provider->setLogger( $obj );
		$this->assertSame( $obj, $providerPriv->logger, 'setLogger' );

		$obj = MediaWikiServices::getInstance()->getAuthManager();
		$provider->setManager( $obj );
		$this->assertSame( $obj, $providerPriv->manager, 'setManager' );

		$obj = $this->getMockForAbstractClass( \Config::class );
		$provider->setConfig( $obj );
		$this->assertSame( $obj, $providerPriv->config, 'setConfig' );

		$this->assertIsString( $provider->getUniqueId(), 'getUniqueId' );
	}
}
