<?php

use MediaWiki\Exception\MWException;
use MediaWiki\MainConfigNames;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Request\WebRequest;
use MediaWiki\Session\SessionManager;

class FauxRequestTest extends MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		parent::setUp();
		$this->overrideConfigValue( MainConfigNames::Server, '//wiki.test' );
	}

	/**
	 * @covers \MediaWiki\Request\FauxRequest::__construct
	 */
	public function testConstructInvalidSession() {
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( 'bogus session' );
		new FauxRequest( [], false, 'x' );
	}

	/**
	 * @covers \MediaWiki\Request\FauxRequest::__construct
	 */
	public function testConstructWithSession() {
		$session = SessionManager::singleton()->getEmptySession( new FauxRequest( [] ) );
		$this->assertInstanceOf(
			FauxRequest::class,
			new FauxRequest( [], false, $session )
		);
	}

	/**
	 * @covers \MediaWiki\Request\FauxRequest::getText
	 */
	public function testGetText() {
		$req = new FauxRequest( [ 'x' => 'Value' ] );
		$this->assertSame( 'Value', $req->getText( 'x' ) );
		$this->assertSame( '', $req->getText( 'z' ) );
	}

	/**
	 * Integration test for parent method
	 * @covers \MediaWiki\Request\FauxRequest::getVal
	 */
	public function testGetVal() {
		$req = new FauxRequest( [ 'crlf' => "A\r\nb" ] );
		$this->assertSame( "A\r\nb", $req->getVal( 'crlf' ), 'CRLF' );
	}

	/**
	 * Integration test for parent method
	 * @covers \MediaWiki\Request\FauxRequest::getRawVal
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
	 * @covers \MediaWiki\Request\FauxRequest::getValues
	 */
	public function testGetValues() {
		$values = [ 'x' => 'Value', 'y' => '' ];
		$req = new FauxRequest( $values );
		$this->assertSame( $values, $req->getValues() );
	}

	/**
	 * @covers \MediaWiki\Request\FauxRequest::getQueryValues
	 */
	public function testGetQueryValues() {
		$values = [ 'x' => 'Value', 'y' => '' ];

		$req = new FauxRequest( $values );
		$this->assertSame( $values, $req->getQueryValues() );
		$req = new FauxRequest( $values, /*wasPosted*/ true );
		$this->assertSame( [], $req->getQueryValues() );
	}

	/**
	 * @covers \MediaWiki\Request\FauxRequest::getMethod
	 */
	public function testGetMethod() {
		$req = new FauxRequest( [] );
		$this->assertSame( 'GET', $req->getMethod() );
		$req = new FauxRequest( [], /*wasPosted*/ true );
		$this->assertSame( 'POST', $req->getMethod() );
	}

	/**
	 * @covers \MediaWiki\Request\FauxRequest::wasPosted
	 */
	public function testWasPosted() {
		$req = new FauxRequest( [] );
		$this->assertFalse( $req->wasPosted() );
		$req = new FauxRequest( [], /*wasPosted*/ true );
		$this->assertTrue( $req->wasPosted() );
	}

	/**
	 * @covers \MediaWiki\Request\FauxRequest::getCookie
	 * @covers \MediaWiki\Request\FauxRequest::setCookie
	 * @covers \MediaWiki\Request\FauxRequest::setCookies
	 */
	public function testCookies() {
		$req = new FauxRequest();
		$this->assertSame( null, $req->getCookie( 'z', '' ) );

		$req->setCookie( 'x', 'Value', '' );
		$this->assertSame( 'Value', $req->getCookie( 'x', '' ) );

		$req->setCookies( [ 'x' => 'One', 'y' => 'Two' ], '' );
		$this->assertSame( 'One', $req->getCookie( 'x', '' ) );
		$this->assertSame( 'Two', $req->getCookie( 'y', '' ) );
	}

	/**
	 * @covers \MediaWiki\Request\FauxRequest::getCookie
	 * @covers \MediaWiki\Request\FauxRequest::setCookie
	 * @covers \MediaWiki\Request\FauxRequest::setCookies
	 */
	public function testCookiesDefaultPrefix() {
		global $wgCookiePrefix;
		$oldPrefix = $wgCookiePrefix;
		$wgCookiePrefix = '_';

		$req = new FauxRequest();
		$this->assertSame( null, $req->getCookie( 'z' ) );

		$req->setCookie( 'x', 'Value' );
		$this->assertSame( 'Value', $req->getCookie( 'x' ) );

		$wgCookiePrefix = $oldPrefix;
	}

	/**
	 * @covers \MediaWiki\Request\FauxRequest::getRequestURL
	 */
	public function testGetRequestURL_disallowed() {
		$req = new FauxRequest();
		$this->expectException( MWException::class );
		$req->getRequestURL();
	}

	/**
	 * @covers \MediaWiki\Request\FauxRequest::setRequestURL
	 * @covers \MediaWiki\Request\FauxRequest::getRequestURL
	 */
	public function testSetRequestURL() {
		$req = new FauxRequest();
		$req->setRequestURL( 'https://example.org' );
		$this->assertSame( 'https://example.org', $req->getRequestURL() );
	}

	/**
	 * @covers \MediaWiki\Request\FauxRequest::getFullRequestURL
	 */
	public function testGetFullRequestURL_disallowed() {
		$req = new FauxRequest();

		$this->expectException( MWException::class );
		$req->getFullRequestURL();
	}

	/**
	 * @covers \MediaWiki\Request\FauxRequest::getFullRequestURL
	 */
	public function testGetFullRequestURL_http() {
		$req = new FauxRequest();
		$req->setRequestURL( '/path' );

		$this->assertSame(
			'http://wiki.test/path',
			$req->getFullRequestURL()
		);
	}

	/**
	 * @covers \MediaWiki\Request\FauxRequest::getFullRequestURL
	 */
	public function testGetFullRequestURL_https() {
		$req = new FauxRequest( [], false, null, 'https' );
		$req->setRequestURL( '/path' );

		$this->assertSame(
			'https://wiki.test/path',
			$req->getFullRequestURL()
		);
	}

	/**
	 * @covers \MediaWiki\Request\FauxRequest::__construct
	 * @covers \MediaWiki\Request\FauxRequest::getProtocol
	 */
	public function testProtocol() {
		$req = new FauxRequest();
		$this->assertSame( 'http', $req->getProtocol() );
		$req = new FauxRequest( [], false, null, 'http' );
		$this->assertSame( 'http', $req->getProtocol() );
		$req = new FauxRequest( [], false, null, 'https' );
		$this->assertSame( 'https', $req->getProtocol() );
	}

	/**
	 * @covers \MediaWiki\Request\FauxRequest::setHeader
	 * @covers \MediaWiki\Request\FauxRequest::setHeaders
	 * @covers \MediaWiki\Request\FauxRequest::getHeader
	 */
	public function testGetSetHeader() {
		$value = 'text/plain, text/html';

		$request = new FauxRequest();
		$request->setHeader( 'Accept', $value );

		$this->assertSame( false, $request->getHeader( 'Nonexistent' ) );
		$this->assertSame( $value, $request->getHeader( 'Accept' ) );
		$this->assertSame( $value, $request->getHeader( 'ACCEPT' ) );
		$this->assertSame( $value, $request->getHeader( 'accept' ) );
		$this->assertSame(
			[ 'text/plain', 'text/html' ],
			$request->getHeader( 'Accept', WebRequest::GETHEADER_LIST )
		);
	}

	/**
	 * @covers \MediaWiki\Request\FauxRequest::initHeaders
	 */
	public function testGetAllHeaders() {
		$_SERVER['HTTP_TEST'] = 'Example';

		$request = new FauxRequest();

		$this->assertSame( [], $request->getAllHeaders() );
		$this->assertSame( false, $request->getHeader( 'test' ) );
	}

	/**
	 * @covers \MediaWiki\Request\FauxRequest::__construct
	 * @covers \MediaWiki\Request\FauxRequest::getSessionArray
	 */
	public function testSessionData() {
		$values = [ 'x' => 'Value', 'y' => '' ];

		$req = new FauxRequest( [], false, /*session*/ $values );
		$this->assertSame( $values, $req->getSessionArray() );

		$req = new FauxRequest();
		$this->assertSame( null, $req->getSessionArray() );
	}

	/**
	 * @covers \MediaWiki\Request\FauxRequest::getPostValues
	 */
	public function testGetPostValues() {
		$values = [ 'x' => 'Value', 'y' => '' ];

		$req = new FauxRequest( $values, true );
		$this->assertSame( $values, $req->getPostValues() );

		$req = new FauxRequest( $values );
		$this->assertSame( [], $req->getPostValues() );
	}

	/**
	 * @covers \MediaWiki\Request\FauxRequest::getRawQueryString
	 * @covers \MediaWiki\Request\FauxRequest::getRawPostString
	 * @covers \MediaWiki\Request\FauxRequest::getRawInput
	 */
	public function testDummies() {
		$req = new FauxRequest();
		$this->assertSame( '', $req->getRawQueryString() );
		$this->assertSame( '', $req->getRawPostString() );
		$this->assertSame( '', $req->getRawInput() );
	}
}
