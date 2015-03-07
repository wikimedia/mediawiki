<?php

/**
 * @group AuthManager
 * @covers AbstractAuthenticationProvider
 */
class AbstractAuthenticationProviderTest extends MediaWikiTestCase {
	/**
	 * @uses AuthManager
	 */
	public function testAbstractAuthenticationProvider() {
		$provider = $this->getMockForAbstractClass( 'AbstractAuthenticationProvider' );
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );

		$obj = $this->getMockForAbstractClass( 'Psr\Log\LoggerInterface' );
		$provider->setLogger( $obj );
		$this->assertSame( $obj, $providerPriv->logger, 'setLogger' );

		$obj = AuthManager::singleton();
		$provider->setManager( $obj );
		$this->assertSame( $obj, $providerPriv->manager, 'setManager' );

		$obj = $this->getMockForAbstractClass( 'Config' );
		$provider->setConfig( $obj );
		$this->assertSame( $obj, $providerPriv->config, 'setConfig' );

		$this->assertType( 'string', $provider->getUniqueId(), 'getUniqueId' );
	}
}
