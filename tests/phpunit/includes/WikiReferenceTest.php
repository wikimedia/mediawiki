<?php

/**
 * @covers WikiReference
 */

class WikiReferenceTest extends PHPUnit_Framework_TestCase {

	public function provideGetDisplayName() {
		return [
			'http' => [ 'foo.bar', 'http://foo.bar' ],
			'https' => [ 'foo.bar', 'http://foo.bar' ],

			// apparently, this is the expected behavior
			'invalid' => [ 'purple kittens', 'purple kittens' ],
		];
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
		return [
			'no fragment' => [
				'https://acme.com/wiki/Foo',
				'https://acme.com',
				'//acme.com',
				'/wiki/$1',
				'Foo',
				null
			],
			'empty fragment' => [
				'https://acme.com/wiki/Foo',
				'https://acme.com',
				'//acme.com',
				'/wiki/$1',
				'Foo',
				''
			],
			'fragment' => [
				'https://acme.com/wiki/Foo#Bar',
				'https://acme.com',
				'//acme.com',
				'/wiki/$1',
				'Foo',
				'Bar'
			],
			'double fragment' => [
				'https://acme.com/wiki/Foo#Bar%23Xus',
				'https://acme.com',
				'//acme.com',
				'/wiki/$1',
				'Foo',
				'Bar#Xus'
			],
			'escaped fragment' => [
				'https://acme.com/wiki/Foo%23Bar',
				'https://acme.com',
				'//acme.com',
				'/wiki/$1',
				'Foo#Bar',
				null
			],
			'empty path' => [
				'https://acme.com/Foo',
				'https://acme.com',
				'//acme.com',
				'/$1',
				'Foo',
				null
			],
		];
	}

	/**
	 * @dataProvider provideGetCanonicalUrl
	 */
	public function testGetCanonicalUrl(
		$expected, $canonicalServer, $server, $path, $page, $fragmentId
	) {
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
		return [
			'no fragment' => [
				'//acme.com/wiki/Foo',
				'https://acme.com',
				'//acme.com',
				'/wiki/$1',
				'Foo',
				null
			],
			'empty fragment' => [
				'//acme.com/wiki/Foo',
				'https://acme.com',
				'//acme.com',
				'/wiki/$1',
				'Foo',
				''
			],
			'fragment' => [
				'//acme.com/wiki/Foo#Bar',
				'https://acme.com',
				'//acme.com',
				'/wiki/$1',
				'Foo',
				'Bar'
			],
			'double fragment' => [
				'//acme.com/wiki/Foo#Bar%23Xus',
				'https://acme.com',
				'//acme.com',
				'/wiki/$1',
				'Foo',
				'Bar#Xus'
			],
			'escaped fragment' => [
				'//acme.com/wiki/Foo%23Bar',
				'https://acme.com',
				'//acme.com',
				'/wiki/$1',
				'Foo#Bar',
				null
			],
			'empty path' => [
				'//acme.com/Foo',
				'https://acme.com',
				'//acme.com',
				'/$1',
				'Foo',
				null
			],
		];
	}

	/**
	 * @dataProvider provideGetFullUrl
	 */
	public function testGetFullUrl( $expected, $canonicalServer, $server, $path, $page, $fragmentId ) {
		$reference = new WikiReference( $canonicalServer, $path, $server );
		$this->assertEquals( $expected, $reference->getFullUrl( $page, $fragmentId ) );
	}

}
