<?php

use MediaWiki\Http\HttpRequestFactory;

/**
 * @covers MediaWiki\Http\HttpRequestFactory
 */
class HttpRequestFactoryTest extends MediaWikiTestCase {

	/**
	 * @return HttpRequestFactory
	 */
	private function newFactory() {
		return new HttpRequestFactory();
	}

	/**
	 * @return HttpRequestFactory
	 */
	private function newFactoryWithFakeRequest(
		MWHttpRequest $req,
		$expectedUrl,
		$expectedOptions = []
	) {
		$factory = $this->getMockBuilder( HttpRequestFactory::class )
			->setMethods( [ 'create' ] )
			->getMock();

		$factory->method( 'create' )
			->willReturnCallback(
				function ( $url, array $options = [], $caller = __METHOD__ )
					use ( $req, $expectedUrl, $expectedOptions )
				{
					$this->assertSame( $url, $expectedUrl );

					foreach ( $expectedOptions as $opt => $exp ) {
						$this->assertArrayHasKey( $opt, $options );
						$this->assertSame( $exp, $options[$opt] );
					}

					return $req;
				}
			);

		return $factory;
	}

	/**
	 * @return MWHttpRequest
	 */
	private function newFakeRequest( $result ) {
		$req = $this->getMockBuilder( MWHttpRequest::class )
			->disableOriginalConstructor()
			->setMethods( [ 'getContent', 'execute' ] )
			->getMock();

		if ( $result instanceof Status ) {
			$req->method( 'getContent' )
				->willReturn( $result->getValue() );
			$req->method( 'execute' )
				->willReturn( $result );
		} else {
			$req->method( 'getContent' )
				->willReturn( $result );
			$req->method( 'execute' )
				->willReturn( Status::newGood( $result ) );
		}

		return $req;
	}

	public function testCreate() {
		$factory = $this->newFactory();
		$this->assertInstanceOf( 'MWHttpRequest', $factory->create( 'http://example.test' ) );
	}

	public function testGetUserAgent() {
		$factory = $this->newFactory();
		$this->assertStringStartsWith( 'MediaWiki/', $factory->getUserAgent() );
	}

	public function testGet() {
		$req = $this->newFakeRequest( __METHOD__ );
		$factory = $this->newFactoryWithFakeRequest(
			$req, 'https://example.test', [ 'method' => 'GET' ]
		);

		$this->assertSame( __METHOD__, $factory->get( 'https://example.test' ) );
	}

	public function testPost() {
		$req = $this->newFakeRequest( __METHOD__ );
		$factory = $this->newFactoryWithFakeRequest(
			$req, 'https://example.test', [ 'method' => 'POST' ]
		);

		$this->assertSame( __METHOD__, $factory->post( 'https://example.test' ) );
	}

	public function testRequest() {
		$req = $this->newFakeRequest( __METHOD__ );
		$factory = $this->newFactoryWithFakeRequest(
			$req, 'https://example.test', [ 'method' => 'GET' ]
		);

		$this->assertSame( __METHOD__, $factory->request( 'GET', 'https://example.test' ) );
	}

	public function testRequest_failed() {
		$status = Status::newFatal( 'testing' );
		$req = $this->newFakeRequest( $status );
		$factory = $this->newFactoryWithFakeRequest(
			$req, 'https://example.test', [ 'method' => 'POST' ]
		);

		$this->assertNull( $factory->request( 'POST', 'https://example.test' ) );
	}

}
