<?php

class UriTest extends MediaWikiTestCase {

	function setUp() {
		AutoLoader::loadClass( 'Uri' );
	}

	function dataUris() {
		return array(
			array(
				'http://example.com/',
				'http', '://', null, null, 'example.com', null, '/', null, null,
			),
			array(
				'//mediawiki.org/wiki/Main_Page',
				'', '//', null, null, 'mediawiki.org', null, '/wiki/Main_Page', null, null,
			),
			array(
				'http://user:pass@example.com/',
				'http', '://', 'user', 'pass', 'example.com', null, '/', null, null,
			),
			array(
				'/?asdf=asdf',
				null, null, null, null, null, null, '/',  'asdf=asdf', null,
			),
			array(
				'?asdf=asdf#asdf',
				null, null, null, null, null, null, null, 'asdf=asdf', 'asdf',
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
	function testComponents( $uri, $scheme, $delimiter, $user, $pass, $host, $port, $path, $query, $fragment ) {
		$uri = new Uri( $uri );

		$this->assertSame( $scheme, $uri->getProtocol(), 'Correct scheme' );
		$this->assertSame( $delimiter, $uri->getDelimiter(), 'Correct delimiter' );
		$this->assertSame( $user, $uri->getUser(), 'Correct user' );
		$this->assertSame( $pass, $uri->getPassword(), 'Correct pass' );
		$this->assertSame( $host, $uri->getHost(), 'Correct host' );
		$this->assertSame( $port, $uri->getPort(), 'Correct port' );
		$this->assertSame( $path, $uri->getPath(), 'Correct path' );
		$this->assertSame( $query, $uri->getQueryString(), 'Correct query' );
		$this->assertSame( $fragment, $uri->getFragment(), 'Correct fragment' );
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
		$uri->extend( 'a=c&foo=bar' );
		$this->assertSame( 'a=c&hello=world&foo=bar', $uri->getQueryString() );
	}
}