<?php

/**
 * @group AuthManager
 * @covers AbstractAuthnSessionProvider
 */
class AbstractAuthnSessionProviderTest extends MediaWikiTestCase {
	/**
	 * @uses AuthManager
	 */
	public function testAbstractAuthnSessionProvider() {
		$provider = $this->getMockForAbstractClass( 'AbstractAuthnSessionProvider' );
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );

		$obj = $this->getMockForAbstractClass( 'Psr\Log\LoggerInterface' );
		$provider->setLogger( $obj );
		$this->assertSame( $obj, $providerPriv->logger, 'setLogger' );

		$obj = $this->getMockForAbstractClass( 'Config' );
		$provider->setConfig( $obj );
		$this->assertSame( $obj, $providerPriv->config, 'setConfig' );

		$obj = $this->getMockForAbstractClass( 'BagOStuff' );
		$provider->setStore( $obj );
		$this->assertSame( $obj, $providerPriv->store, 'setStore' );

		$this->assertSame( array(), $provider->getVaryHeaders(), 'getVaryHeaders' );
		$this->assertSame( array(), $provider->getVaryCookies(), 'getVaryCookies' );
	}
}
