<?php

namespace MediaWiki\Auth;

/**
 * @group AuthManager
 * @covers MediaWiki\Auth\AbstractAuthenticationProvider
 */
class AbstractAuthenticationProviderTest extends \MediaWikiTestCase {
	public function testAbstractAuthenticationProvider() {
		$provider = $this->getMockForAbstractClass( 'MediaWiki\\Auth\\AbstractAuthenticationProvider' );
		$providerPriv = \TestingAccessWrapper::newFromObject( $provider );

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

	public function testGetRequestByClass() {
		$provider = $this->getMockForAbstractClass( 'MediaWiki\\Auth\\AbstractAuthenticationProvider' );
		$providerPriv = \TestingAccessWrapper::newFromObject( $provider );

		$mock = $this->getMockForAbstractClass( 'MediaWiki\\Auth\\AuthenticationRequest', array(),
			'AbstractAuthenticationProviderRequest1' );
		$this->getMockClass( 'MediaWiki\\Auth\\AuthenticationRequest', array(), array(),
			'AbstractAuthenticationProviderRequest2' );
		$requests = array(
			$mock,
			new \AbstractAuthenticationProviderRequest2(),
			new \AbstractAuthenticationProviderRequest2(),
		);

		$this->assertEquals( null, $providerPriv->getRequestByClass( $requests,
			'AbstractAuthenticationProviderRequest0' ) );
		$this->assertEquals( $mock, $providerPriv->getRequestByClass( $requests,
			'AbstractAuthenticationProviderRequest1' ) );
		$this->assertEquals( null, $providerPriv->getRequestByClass( $requests,
			'AbstractAuthenticationProviderRequest2' ) );
	}
}
