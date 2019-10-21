<?php

use MediaWiki\Session\SessionManager;

class FauxRequestTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;
	use PHPUnit4And6Compat;

	public function setUp() {
		parent::setUp();
		$this->orgWgServer = $GLOBALS['wgServer'];
	}

	public function tearDown() {
		$GLOBALS['wgServer'] = $this->orgWgServer;
		parent::tearDown();
	}

	/**
	 * @covers FauxRequest::__construct
	 */
	public function testConstructInvalidData() {
		$this->setExpectedException( MWException::class, 'bogus data' );
		$req = new FauxRequest( 'x' );
	}

	/**
	 * @covers FauxRequest::__construct
	 */
	public function testConstructInvalidSession() {
		$this->setExpectedException( MWException::class, 'bogus session' );
		$req = new FauxRequest( [], false, 'x' );
	}

	/**
	 * @covers FauxRequest::__construct
	 */
	public function testConstructWithSession() {
		$session = SessionManager::singleton()->getEmptySession( new FauxRequest( [] ) );
		$this->assertInstanceOf(
			FauxRequest::class,
			new FauxRequest( [], false, $session )
		);
	}

	/**
	 * @covers FauxRequest::getText
	 */
	public function testGetText() {
		$req = new FauxRequest( [ 'x' => 'Value' ] );
		$this->assertEquals( 'Value', $req->getText( 'x' ) );
		$this->assertSame( '', $req->getText( 'z' ) );
	}

	/**
	 * Integration test for parent method
	 * @covers FauxRequest::getVal
	 */
	public function testGetVal() {
		$req = new FauxRequest( [ 'crlf' => "A\r\nb" ] );
		$this->assertSame( "A\r\nb", $req->getVal( 'crlf' ), 'CRLF' );
	}

	/**
	 * Integration test for parent method
	 * @covers FauxRequest::getRawVal
	 */
	public function testGetRawVal() {
		$req = new FauxRequest( [
			'x' => 'Value',
			'y' => [ 'a' ],
			'crlf' => "A\r\nb"
		] );
		$this->assertSame( 'Value', $req->getRawVal( 'x' ) );
		$this->assertSame( null, $req->getRawVal( 'z' ), 'Not found' );
		$this->assertSame( null, $req->getRawVal( 'y' ), 'Array is ignored' );
		$this->assertSame( "A\r\nb", $req->getRawVal( 'crlf' ), 'CRLF' );
	}

	/**
	 * @covers FauxRequest::getValues
	 */
	public function testGetValues() {
		$values = [ 'x' => 'Value', 'y' => '' ];
		$req = new FauxRequest( $values );
		$this->assertEquals( $values, $req->getValues() );
	}

	/**
	 * @covers FauxRequest::getQueryValues
	 */
	public function testGetQueryValues() {
		$values = [ 'x' => 'Value', 'y' => '' ];

		$req = new FauxRequest( $values );
		$this->assertEquals( $values, $req->getQueryValues() );
		$req = new FauxRequest( $values, /*wasPosted*/ true );
		$this->assertEquals( [], $req->getQueryValues() );
	}

	/**
	 * @covers FauxRequest::getMethod
	 */
	public function testGetMethod() {
		$req = new FauxRequest( [] );
		$this->assertEquals( 'GET', $req->getMethod() );
		$req = new FauxRequest( [], /*wasPosted*/ true );
		$this->assertEquals( 'POST', $req->getMethod() );
	}

	/**
	 * @covers FauxRequest::wasPosted
	 */
	public function testWasPosted() {
		$req = new FauxRequest( [] );
		$this->assertFalse( $req->wasPosted() );
		$req = new FauxRequest( [], /*wasPosted*/ true );
		$this->assertTrue( $req->wasPosted() );
	}

	/**
	 * @covers FauxRequest::getCookie
	 * @covers FauxRequest::setCookie
	 * @covers FauxRequest::setCookies
	 */
	public function testCookies() {
		$req = new FauxRequest();
		$this->assertSame( null, $req->getCookie( 'z', '' ) );

		$req->setCookie( 'x', 'Value', '' );
		$this->assertEquals( 'Value', $req->getCookie( 'x', '' ) );

		$req->setCookies( [ 'x' => 'One', 'y' => 'Two' ], '' );
		$this->assertEquals( 'One', $req->getCookie( 'x', '' ) );
		$this->assertEquals( 'Two', $req->getCookie( 'y', '' ) );
	}

	/**
	 * @covers FauxRequest::getCookie
	 * @covers FauxRequest::setCookie
	 * @covers FauxRequest::setCookies
	 */
	public function testCookiesDefaultPrefix() {
		global $wgCookiePrefix;
		$oldPrefix = $wgCookiePrefix;
		$wgCookiePrefix = '_';

		$req = new FauxRequest();
		$this->assertSame( null, $req->getCookie( 'z' ) );

		$req->setCookie( 'x', 'Value' );
		$this->assertEquals( 'Value', $req->getCookie( 'x' ) );

		$wgCookiePrefix = $oldPrefix;
	}

	/**
	 * @covers FauxRequest::getRequestURL
	 */
	public function testGetRequestURL_disallowed() {
		$req = new FauxRequest();
		$this->setExpectedException( MWException::class );
		$req->getRequestURL();
	}

	/**
	 * @covers FauxRequest::setRequestURL
	 * @covers FauxRequest::getRequestURL
	 */
	public function testSetRequestURL() {
		$req = new FauxRequest();
		$req->setRequestURL( 'https://example.org' );
		$this->assertEquals( 'https://example.org', $req->getRequestURL() );
	}

	/**
	 * @covers FauxRequest::getFullRequestURL
	 */
	public function testGetFullRequestURL_disallowed() {
		$GLOBALS['wgServer'] = '//wiki.test';
		$req = new FauxRequest();

		$this->setExpectedException( MWException::class );
		$req->getFullRequestURL();
	}

	/**
	 * @covers FauxRequest::getFullRequestURL
	 */
	public function testGetFullRequestURL_http() {
		$GLOBALS['wgServer'] = '//wiki.test';
		$req = new FauxRequest();
		$req->setRequestURL( '/path' );

		$this->assertSame(
			'http://wiki.test/path',
			$req->getFullRequestURL()
		);
	}

	/**
	 * @covers FauxRequest::getFullRequestURL
	 */
	public function testGetFullRequestURL_https() {
		$GLOBALS['wgServer'] = '//wiki.test';
		$req = new FauxRequest( [], false, null, 'https' );
		$req->setRequestURL( '/path' );

		$this->assertSame(
			'https://wiki.test/path',
			$req->getFullRequestURL()
		);
	}

	/**
	 * @covers FauxRequest::__construct
	 * @covers FauxRequest::getProtocol
	 */
	public function testProtocol() {
		$req = new FauxRequest();
		$this->assertEquals( 'http', $req->getProtocol() );
		$req = new FauxRequest( [], false, null, 'http' );
		$this->assertEquals( 'http', $req->getProtocol() );
		$req = new FauxRequest( [], false, null, 'https' );
		$this->assertEquals( 'https', $req->getProtocol() );
	}

	/**
	 * @covers FauxRequest::setHeader
	 * @covers FauxRequest::setHeaders
	 * @covers FauxRequest::getHeader
	 */
	public function testGetSetHeader() {
		$value = 'text/plain, text/html';

		$request = new FauxRequest();
		$request->setHeader( 'Accept', $value );

		$this->assertEquals( $request->getHeader( 'Nonexistent' ), false );
		$this->assertEquals( $request->getHeader( 'Accept' ), $value );
		$this->assertEquals( $request->getHeader( 'ACCEPT' ), $value );
		$this->assertEquals( $request->getHeader( 'accept' ), $value );
		$this->assertEquals(
			$request->getHeader( 'Accept', WebRequest::GETHEADER_LIST ),
			[ 'text/plain', 'text/html' ]
		);
	}

	/**
	 * @covers FauxRequest::initHeaders
	 */
	public function testGetAllHeaders() {
		$_SERVER['HTTP_TEST'] = 'Example';

		$request = new FauxRequest();

		$this->assertEquals(
			[],
			$request->getAllHeaders()
		);

		$this->assertEquals(
			false,
			$request->getHeader( 'test' )
		);
	}

	/**
	 * @covers FauxRequest::__construct
	 * @covers FauxRequest::getSessionArray
	 */
	public function testSessionData() {
		$values = [ 'x' => 'Value', 'y' => '' ];

		$req = new FauxRequest( [], false, /*session*/ $values );
		$this->assertEquals( $values, $req->getSessionArray() );

		$req = new FauxRequest();
		$this->assertSame( null, $req->getSessionArray() );
	}

	/**
	 * @covers FauxRequest::getRawQueryString
	 * @covers FauxRequest::getRawPostString
	 * @covers FauxRequest::getRawInput
	 */
	public function testDummies() {
		$req = new FauxRequest();
		$this->assertSame( '', $req->getRawQueryString() );
		$this->assertSame( '', $req->getRawPostString() );
		$this->assertSame( '', $req->getRawInput() );
	}
}
