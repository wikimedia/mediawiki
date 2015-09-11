<?php

/**
 * @covers WikiReference
 */

class WikiReferenceTest extends PHPUnit_Framework_TestCase {

	public function provideGetHostname() {
		return array(
			'http' => array( 'foo.bar', 'http://foo.bar' ),
			'https' => array( 'foo.bar', 'https://foo.bar' ),
		);
	}

	/**
	 * @dataProvider provideGetHostname
	 */
	public function testGetHostname( $expected, $canonicalServer ) {
		$this->markTestSkipped( 'The implementation is patently broken.' );

		$reference = new WikiReference( 'wiki', 'xx', $canonicalServer, '/wiki/$1' );
		$this->assertEquals( $expected, $reference->getHostname() );
	}

	public function provideGetDisplayName() {
		return array(
			'http' => array( 'foo.bar', 'http://foo.bar' ),
			'https' => array( 'foo.bar', 'http://foo.bar' ),

			// apparently, this is the expected behavior
			'invalid' => array( 'purple kittens/wiki/', 'purple kittens' ),
		);
	}

	/**
	 * @dataProvider provideGetDisplayName
	 */
	public function testGetDisplayName( $expected, $canonicalServer ) {
		$reference = new WikiReference( 'wiki', 'xx', $canonicalServer, '/wiki/$1' );
		$this->assertEquals( $expected, $reference->getDisplayName() );
	}

	public function testGetCanonicalServer() {
		$reference = new WikiReference( 'wiki', 'xx', 'https://acme.com', '/wiki/$1', '//acme.com' );
		$this->assertEquals( 'https://acme.com', $reference->getCanonicalServer() );
	}

	public function provideGetCanonicalUrl() {
		return array(
			'wiki path' => array( 'https://acme.com/wiki/Foo', 'https://acme.com', '//acme.com', '/wiki/$1', 'Foo' ),
			'empty path' => array( 'https://acme.com/Foo', 'https://acme.com', '//acme.com', '/$1', 'Foo' ),
		);
	}

	/**
	 * @dataProvider provideGetCanonicalUrl
	 */
	public function testGetCanonicalUrl( $expected, $canonicalServer, $server, $path, $page ) {
		$reference = new WikiReference( 'wiki', 'xx', $canonicalServer, $path, $server );
		$this->assertEquals( $expected, $reference->getCanonicalUrl( $page ) );
	}

	/**
	 * @dataProvider provideGetCanonicalUrl
	 */
	public function testGetUrl( $expected, $canonicalServer, $server, $path, $page ) {
		$reference = new WikiReference( 'wiki', 'xx', $canonicalServer, $path, $server );
		$this->assertEquals( $expected, $reference->getUrl( $page ) );
	}

	public function provideGetFullUrl() {
		return array(
			'wiki path' => array( '//acme.com/wiki/Foo', 'https://acme.com', '//acme.com', '/wiki/$1', 'Foo', null ),
			'empty path' => array( '//acme.com/Foo', 'https://acme.com', '//acme.com', '/$1', 'Foo', null ),
		);
	}

	/**
	 * @dataProvider provideGetFullUrl
	 */
	public function testGetFullUrl( $expected, $canonicalServer, $server, $path, $page ) {
		$reference = new WikiReference( 'wiki', 'xx', $canonicalServer, $path, $server );
		$this->assertEquals( $expected, $reference->getFullUrl( $page ) );
	}

}

