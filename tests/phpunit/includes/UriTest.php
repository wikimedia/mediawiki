<?php

class UriTest extends MediaWikiTestCase {

	public function setUp() {
		parent::setUp();
		AutoLoader::loadClass( 'Uri' );
	}

	public static function dataUris() {
		return array(
			array(
				'http://example.com/',
				array(
					'scheme'    => 'http',
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
					'user'      => null,
					'pass'      => null,
					'host'      => null,
					'port'      => null,
					'path'      => null,
					'query'     => 'asdf=asdf',
					'fragment'  => 'asdf',
				),
			),
			array(
				'http://test.com/path?query',
				array(
					'scheme'    => 'http',
					'user'      => null,
					'pass'      => null,
					'host'      => 'test.com',
					'port'      => null,
					'path'      => '/path',
					'query'     => 'query',
					'fragment'  => null
				)
			)
		);
	}

	/**
	 * Ensure that URIs that round-trip through an object are preserved.
	 * @dataProvider dataUris
	 */
	function testRoundtrip( $uri ) {
		$obj = new Uri( $uri );
		$this->assertSame( $uri, (string) $obj );
	}

	/**
	 * Ensure that get* methods properly match the appropriate getComponent( key ) value
	 * @dataProvider dataUris
	 */
	function testGetters( $uri ) {
		$uri = new Uri( $uri );
		$getterMap = array(
			'getScheme' => 'scheme',
			'getUser' => 'user',
			'getPassword' => 'pass',
			'getHost' => 'host',
			'getPort' => 'port',
			'getPath' => 'path',
			'getQuery' => 'query',
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

		$this->assertSame( $components['scheme'], $uri->getScheme(), 'Correct scheme' );
		$this->assertSame( $components['user'], $uri->getUser(), 'Correct user' );
		$this->assertSame( $components['pass'], $uri->getPassword(), 'Correct pass' );
		$this->assertSame( $components['host'], $uri->getHost(), 'Correct host' );
		$this->assertSame( $components['port'], $uri->getPort(), 'Correct port' );
		$this->assertSame( $components['path'], $uri->getPath(), 'Correct path' );
		$this->assertSame( $components['query'], $uri->getQuery(), 'Correct query' );
		$this->assertSame( $components['fragment'], $uri->getFragment(), 'Correct fragment' );
	}

	/**
	 * Ensure that the aliases work for various components.
	 */
	function testAliases() {
		$url = "//myuser@test.com";
		$uri = new Uri( $url );

		// Set the aliases.
		$uri->setComponent( 'protocol', 'https' );
		$uri->setComponent( 'password', 'mypass' );

		// Now try getting them.
		$this->assertSame( 'https', $uri->getComponent( 'protocol' ), 'Correct protocol (alias for scheme)' );
		$this->assertSame( 'mypass', $uri->getComponent( 'password' ), 'Correct password (alias for pass)' );

		// Finally check their actual names.
		$this->assertSame( 'https', $uri->getScheme(), 'Alias for scheme works' );
		$this->assertSame( 'mypass', $uri->getPassword(), 'Alias for pass works' );
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
	 * Make sure Uri's raw extendQuery() functionality behaves like wfAppendQuery.
	 */
	function testExtendQuery() {
		$uri = new Uri( 'http://a:b@example.com:8080/path?query=value' );
		$uri->extendQuery( 'a=b' );
		$this->assertSame( 'query=value&a=b', $uri->getQuery() );
		$uri->extendQuery( array( 'c' => 'd' ) );
		$this->assertSame( 'query=value&a=b&c=d', $uri->getQuery() );
	}

	/**
	 * Test the UriFormQuery class when used in the Uri class.
	 */
	function testFormQuery() {
		$uri = new Uri;
		$uri->setQuery( array( 'a' => 'b', 'c' => 'd' ) );
		$this->assertInstanceOf( 'UriFormQuery', $uri->getQuery() );
		$this->assertSame( 'a=b&c=d', $uri->getQuery()->getQueryString() );
	}

	/**
	 * Test extending a UriFormQuery.
	 */
	function testFormQueryExtend() {
		$uri = new Uri;
		$uri->setQuery( array( 'a' => 'b', 'c' => 'd' ) );
		$uri->extendQuery( array( 'a' => 'e', 'f' => 'g' ) );
		$this->assertSame( 'a=e&c=d&f=g', $uri->getQuery()->getQueryString() );
	}

	/**
	 * Test the UriRawQuery class when used in the Uri class.
	 */
	function testRawQuery() {
		$uri = new Uri;
		$uri->setQuery( new UriRawQuery( 'hello' ) );
		$this->assertInstanceOf( 'UriRawQuery', $uri->getQuery() );
		$this->assertSame( 'hello', $uri->getQuery()->getQueryString() );
	}

	/**
	 * Test extending a UriRawQuery.
	 */
	function testRawQueryExtend() {
                $uri = new Uri;
                $uri->setQuery( new UriRawQuery( 'hello' ) );
		$uri->extendQuery( 'world' );
		$this->assertSame( 'helloworld', $uri->getQuery()->getQueryString() );
	}

}
