<?php

declare( strict_types = 1 );

use MediaWiki\Http\MwHttpRequestToResponseInterfaceAdapter;
use Psr\Http\Message\StreamInterface;

/**
 * @covers \MediaWiki\Http\MwHttpRequestToResponseInterfaceAdapter
 *
 * @group Http
 * @group small
 *
 * @license GPL-2.0-or-later
 */
class MwHttpRequestToResponseInterfaceAdapterTest extends MediaWikiUnitTestCase {

	public function testGivenNotExecutedRequest_constructorThrows() {
		$req = $this->newMockMWHttpRequestWithHeaders( [] );

		$this->expectException( \LogicException::class );
		new MwHttpRequestToResponseInterfaceAdapter( $req );
	}

	public function testGetHeaders() {
		$headers = [
			'foo' => [ 'bar' ],
			'some' => [ 'such', 'other' ]
		];
		$mwHttpReq = $this->newMockMWHttpRequestWithHeaders( $headers );

		$response = new MwHttpRequestToResponseInterfaceAdapter( $mwHttpReq );
		$this->assertEquals( $headers, $response->getHeaders() );
	}

	public function testHasHeader() {
		$mwHttpReq = $this->newMockMWHttpRequestWithHeaders( [
			'foo' => [ 'bar' ],
		] );
		$response = new MwHttpRequestToResponseInterfaceAdapter( $mwHttpReq );
		$this->assertTrue( $response->hasHeader( 'foo' ) );
	}

	public function testGivenExistingHeader_getHeaderReturnsValuesArray() {
		$headerValues = [ 'bar', 'baz' ];
		$mwHttpReq = $this->newMockMWHttpRequestWithHeaders( [
			'foo' => $headerValues,
		] );
		$response = new MwHttpRequestToResponseInterfaceAdapter( $mwHttpReq );
		$this->assertEquals( $headerValues, $response->getHeader( 'foo' ) );
	}

	public function testGivenNonExistingHeader_getHeaderReturnsEmptyArray() {
		$mwHttpReq = $this->newMockMWHttpRequestWithHeaders( [
			'some-header' => [ 'some', 'such' ],
		] );
		$response = new MwHttpRequestToResponseInterfaceAdapter( $mwHttpReq );
		$this->assertEquals( [], $response->getHeader( 'foo' ) );
	}

	public function testGetBody() {
		$req = $this->newMockMWHttpRequestWithHeaders( [ 'Some-header' => [ 'abc' ] ] );
		$body = '{ "some": "body" }';
		$req->expects( $this->once() )
			->method( 'getContent' )
			->willReturn( $body );

		$response = new MwHttpRequestToResponseInterfaceAdapter( $req );

		$this->assertEquals( $body, $response->getBody() );
	}

	public function testGetStatusCode() {
		$req = $this->newMockMWHttpRequestWithHeaders( [ 'Some-header' => [ 'abc' ] ] );
		$status = 200;
		$req->expects( $this->once() )
			->method( 'getStatus' )
			->willReturn( $status );

		$response = new MwHttpRequestToResponseInterfaceAdapter( $req );

		$this->assertEquals( $status, $response->getStatusCode() );
	}

	public function testGivenExistingHeader_getHeaderLineReturnsCommaSeparatedValues() {
		$mwHttpReq = $this->newMockMWHttpRequestWithHeaders( [
			'foo' => [ 'bar', 'baz' ],
		] );
		$response = new MwHttpRequestToResponseInterfaceAdapter( $mwHttpReq );
		$this->assertEquals( 'bar,baz', $response->getHeaderLine( 'foo' ) );
	}

	public function testGivenNonExistingHeader_getHeaderLineReturnsEmptyString() {
		$mwHttpReq = $this->newMockMWHttpRequestWithHeaders( [
			'some-header' => [ 'some', 'such' ],
		] );
		$response = new MwHttpRequestToResponseInterfaceAdapter( $mwHttpReq );
		$this->assertSame( '', $response->getHeaderLine( 'foo' ) );
	}

	public function testGetProtocolVersionIsNotImplemented() {
		$this->expectException( \LogicException::class );
		( new MwHttpRequestToResponseInterfaceAdapter( $this->createMock( MWHttpRequest::class ) ) )
			->getProtocolVersion();
	}

	/**
	 * @dataProvider unsupportedMethodsProvider
	 */
	public function testBuilderMethodsThrowLogicException( string $method, $args ) {
		if ( $args === 'streaminterface' ) {
			$args = [ $this->createMock( StreamInterface::class ) ];
		}
		$this->expectException( \LogicException::class );
		( new MwHttpRequestToResponseInterfaceAdapter( $this->createMock( MWHttpRequest::class ) ) )
			->{$method}( ...$args );
	}

	public static function unsupportedMethodsProvider() {
		return [
			[ 'withAddedHeader', [ 'foo', 'bar' ] ],
			[ 'withBody', 'streaminterface' ],
			[ 'withHeader', [ 'foo', 'bar' ] ],
			[ 'withoutHeader', [ 'foo', 'bar' ] ],
			[ 'withProtocolVersion', [ '1.1' ] ],
			[ 'withStatus', [ 200 ] ],
		];
	}

	private function newMockMWHttpRequestWithHeaders( array $headers ) {
		$req = $this->createMock( MWHttpRequest::class );
		$req->method( 'getResponseHeaders' )
			->willReturn( $headers );

		return $req;
	}

}
