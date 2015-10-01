<?php

/**
 * @covers WikiReference
 */

class WikiReferenceTest extends PHPUnit_Framework_TestCase {

	public function provideGetDisplayName() {
		return array(
			'http' => array( 'foo.bar', 'http://foo.bar' ),
			'https' => array( 'foo.bar', 'http://foo.bar' ),

			// apparently, this is the expected behavior
			'invalid' => array( 'purple kittens', 'purple kittens' ),
		);
	}

	/**
	 * @dataProvider provideGetDisplayName
	 */
	public function testGetDisplayName( $expected, $canonicalServer ) {
		$reference = new WikiReference( $canonicalServer, '/wiki/$1' );
		$this->assertEquals( $expected, $reference->getDisplayName() );
	}

	public function testGetCanonicalServer() {
		$reference = new WikiReference( 'https://acme.com', '/wiki/$1', '//acme.com' );
		$this->assertEquals( 'https://acme.com', $reference->getCanonicalServer() );
	}

	public function provideGetCanonicalUrl() {
		return array(
			'no fragment' => array(
				'https://acme.com/wiki/Foo',
				'https://acme.com',
				'//acme.com',
				'/wiki/$1',
				'Foo',
				null
			),
			'empty fragment' => array(
				'https://acme.com/wiki/Foo',
				'https://acme.com',
				'//acme.com',
				'/wiki/$1',
				'Foo',
				''
			),
			'fragment' => array(
				'https://acme.com/wiki/Foo#Bar',
				'https://acme.com',
				'//acme.com',
				'/wiki/$1',
				'Foo',
				'Bar'
			),
			'double fragment' => array(
				'https://acme.com/wiki/Foo#Bar%23Xus',
				'https://acme.com',
				'//acme.com',
				'/wiki/$1',
				'Foo',
				'Bar#Xus'
			),
			'escaped fragment' => array(
				'https://acme.com/wiki/Foo%23Bar',
				'https://acme.com',
				'//acme.com',
				'/wiki/$1',
				'Foo#Bar',
				null
			),
			'empty path' => array(
				'https://acme.com/Foo',
				'https://acme.com',
				'//acme.com',
				'/$1',
				'Foo',
				null
			),
		);
	}

	/**
	 * @dataProvider provideGetCanonicalUrl
	 */
	public function testGetCanonicalUrl( $expected, $canonicalServer, $server, $path, $page, $fragmentId ) {
		$reference = new WikiReference( $canonicalServer, $path, $server );
		$this->assertEquals( $expected, $reference->getCanonicalUrl( $page, $fragmentId ) );
	}

	/**
	 * @dataProvider provideGetCanonicalUrl
	 * @note getUrl is an alias for getCanonicalUrl
	 */
	public function testGetUrl( $expected, $canonicalServer, $server, $path, $page, $fragmentId ) {
		$reference = new WikiReference( $canonicalServer, $path, $server );
		$this->assertEquals( $expected, $reference->getUrl( $page, $fragmentId ) );
	}

	public function provideGetFullUrl() {
		return array(
			'no fragment' => array(
				'//acme.com/wiki/Foo',
				'https://acme.com',
				'//acme.com',
				'/wiki/$1',
				'Foo',
				null
			),
			'empty fragment' => array(
				'//acme.com/wiki/Foo',
				'https://acme.com',
				'//acme.com',
				'/wiki/$1',
				'Foo',
				''
			),
			'fragment' => array(
				'//acme.com/wiki/Foo#Bar',
				'https://acme.com',
				'//acme.com',
				'/wiki/$1',
				'Foo',
				'Bar'
			),
			'double fragment' => array(
				'//acme.com/wiki/Foo#Bar%23Xus',
				'https://acme.com',
				'//acme.com',
				'/wiki/$1',
				'Foo',
				'Bar#Xus'
			),
			'escaped fragment' => array(
				'//acme.com/wiki/Foo%23Bar',
				'https://acme.com',
				'//acme.com',
				'/wiki/$1',
				'Foo#Bar',
				null
			),
			'empty path' => array(
				'//acme.com/Foo',
				'https://acme.com',
				'//acme.com',
				'/$1',
				'Foo',
				null
			),
		);
	}

	/**
	 * @dataProvider provideGetFullUrl
	 */
	public function testGetFullUrl( $expected, $canonicalServer, $server, $path, $page, $fragmentId ) {
		$reference = new WikiReference( $canonicalServer, $path, $server );
		$this->assertEquals( $expected, $reference->getFullUrl( $page, $fragmentId ) );
	}

}
