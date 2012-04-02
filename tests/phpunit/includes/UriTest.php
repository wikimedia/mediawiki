<?php

class UriTest extends MediaWikiTestCase {

	function setUp() {
		AutoLoader::loadClass( 'Uri' );
	}

	function dataUris() {
		return array(
			array(
				'http://example.com/',
				array(
					'scheme'    => 'http',
					'delimiter' => '://',
					'user'      => null,
					'pass'      => null,
					'host'      => 'example.com',
					'port'      => null,
					'path'      => '/',
					'query'     => null,
					'fragment'  => null,
				),
			),
			array(
				'//mediawiki.org/wiki/Main_Page',
				array(
					'scheme'    => null,
					'delimiter' => '//',
					'user'      => null,
					'pass'      => null,
					'host'      => 'mediawiki.org',
					'port'      => null,
					'path'      => '/wiki/Main_Page',
					'query'     => null,
					'fragment'  => null,
				),
			),
			array(
				'http://user:pass@example.com/',
				array(
					'scheme'    => 'http',
					'delimiter' => '://',
					'user'      => 'user',
					'pass'      => 'pass',
					'host'      => 'example.com',
					'port'      => null,
					'path'      => '/',
					'query'     => null,
					'fragment'  => null,
				),
			),
			array(
				'/?asdf=asdf',
				array(
					'scheme'    => null,
					'delimiter' => null,
					'user'      => null,
					'pass'      => null,
					'host'      => null,
					'port'      => null,
					'path'      => '/',
					'query'     => 'asdf=asdf',
					'fragment'  => null,
				),
			),
			array(
				'?asdf=asdf#asdf',
				array(
					'scheme'    => null,
					'delimiter' => null,
					'user'      => null,
					'pass'      => null,
					'host'      => null,
					'port'      => null,
					'path'      => null,
					'query'     => 'asdf=asdf',
					'fragment'  => 'asdf',
				),
			)
		);
	}

	/**
	 * Ensure that get* methods properly match the appropriate getComponent( key ) value
	 * @dataProvider dataUris
	 */
	function testGetters( $uri ) {
		$uri = new Uri( $uri );
		$getterMap = array(
			'getProtocol' => 'scheme',
			'getUser' => 'user',
			'getPassword' => 'pass',
			'getHost' => 'host',
			'getPort' => 'port',
			'getPath' => 'path',
			'getQueryString' => 'query',
			'getFragment' => 'fragment',
		);
		foreach ( $getterMap as $fn => $c ) {
			$this->assertSame( $uri->{$fn}(), $uri->getComponent( $c ), "\$uri->{$fn}(); matches \$uri->getComponent( '$c' );" );
		}
	}

	/**
	 * Ensure that Uri has the proper components for our example uris
	 * @dataProvider dataUris
	 */
	function testComponents( $uri, $components ) {
		$uri = new Uri( $uri );

		$this->assertSame( $components['scheme'], $uri->getProtocol(), 'Correct scheme' );
		$this->assertSame( $components['delimiter'], $uri->getDelimiter(), 'Correct delimiter' );
		$this->assertSame( $components['user'], $uri->getUser(), 'Correct user' );
		$this->assertSame( $components['pass'], $uri->getPassword(), 'Correct pass' );
		$this->assertSame( $components['host'], $uri->getHost(), 'Correct host' );
		$this->assertSame( $components['port'], $uri->getPort(), 'Correct port' );
		$this->assertSame( $components['path'], $uri->getPath(), 'Correct path' );
		$this->assertSame( $components['query'], $uri->getQueryString(), 'Correct query' );
		$this->assertSame( $components['fragment'], $uri->getFragment(), 'Correct fragment' );
	}

	/**
	 * Ensure that Uri's helper methods return the correct data
	 */
	function testHelpers() {
		$uri = new Uri( 'http://a:b@example.com:8080/path?query=value' );

		$this->assertSame( 'a:b', $uri->getUserInfo(), 'Correct getUserInfo' );
		$this->assertSame( 'example.com:8080', $uri->getHostPort(), 'Correct getHostPort' );
		$this->assertSame( 'a:b@example.com:8080', $uri->getAuthority(), 'Correct getAuthority' );
		$this->assertSame( '/path?query=value', $uri->getRelativePath(), 'Correct getRelativePath' );
		$this->assertSame( 'http://a:b@example.com:8080/path?query=value', $uri->toString(), 'Correct toString' );
	}

	/**
	 * Ensure that Uri's extend method properly overrides keys
	 */
	function testExtend() {
		$uri = new Uri( 'http://example.org/?a=b&hello=world' );
		$uri->extendQuery( 'a=c&foo=bar' );
		$this->assertSame( 'a=c&hello=world&foo=bar', $uri->getQueryString() );
	}
}
