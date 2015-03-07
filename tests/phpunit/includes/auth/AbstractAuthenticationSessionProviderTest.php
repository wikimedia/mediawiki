<?php

/**
 * @group AuthManager
 * @covers AbstractAuthenticationSessionProvider
 */
class AbstractAuthenticationSessionProviderTest extends MediaWikiTestCase {
	/**
	 * @uses AuthManager
	 */
	public function testAbstractAuthenticationSessionProvider() {
		$provider = $this->getMockForAbstractClass( 'AbstractAuthenticationSessionProvider' );
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

		$this->assertFalse(
			$provider->immutableSessionCouldExistForUser( 'UTSysop' ), 'immutableSessionCouldExistForUser'
		);

		$provider->preventImmutableSessionsForUser( 'UTSysop' );

		$this->assertSame( array(), $provider->getVaryHeaders(), 'getVaryHeaders' );
		$this->assertSame( array(), $provider->getVaryCookies(), 'getVaryCookies' );
	}
}
