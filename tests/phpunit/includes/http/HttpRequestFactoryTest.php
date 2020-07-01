<?php

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Http\HttpRequestFactory;
use Psr\Log\NullLogger;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers MediaWiki\Http\HttpRequestFactory
 */
class HttpRequestFactoryTest extends MediaWikiIntegrationTestCase {

	/**
	 * @return HttpRequestFactory
	 */
	private function newFactory( $options = null ) {
		if ( !$options ) {
			$options = [
				'HTTPTimeout' => 1,
				'HTTPConnectTimeout' => 1,
				'HTTPMaxTimeout' => INF,
				'HTTPMaxConnectTimeout' => INF
			];
		}
		return new HttpRequestFactory(
			new ServiceOptions( HttpRequestFactory::CONSTRUCTOR_OPTIONS, $options ),
			new NullLogger
		);
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
			->disableOriginalConstructor()
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

	public static function provideCreateTimeouts() {
		return [
			'normal config defaults' => [
				[
					'HTTPTimeout' => 10,
					'HTTPConnectTimeout' => 20,
					'HTTPMaxTimeout' => INF,
					'HTTPMaxConnectTimeout' => INF
				],
				[],
				[
					'timeout' => 10,
					'connectTimeout' => 20
				]
			],
			'config defaults overridden by max' => [
				[
					'HTTPTimeout' => 10,
					'HTTPConnectTimeout' => 20,
					'HTTPMaxTimeout' => 9,
					'HTTPMaxConnectTimeout' => 11
				],
				[],
				[
					'timeout' => 9,
					'connectTimeout' => 11
				]
			],
			'create option overridden by max config' => [
				[
					'HTTPTimeout' => 1,
					'HTTPConnectTimeout' => 2,
					'HTTPMaxTimeout' => 9,
					'HTTPMaxConnectTimeout' => 11
				],
				[
					'timeout' => 100,
					'connectTimeout' => 200
				],
				[
					'timeout' => 9,
					'connectTimeout' => 11
				]
			],
			'create option below max config' => [
				[
					'HTTPTimeout' => 1,
					'HTTPConnectTimeout' => 2,
					'HTTPMaxTimeout' => 9,
					'HTTPMaxConnectTimeout' => 11
				],
				[
					'timeout' => 7,
					'connectTimeout' => 8
				],
				[
					'timeout' => 7,
					'connectTimeout' => 8
				]
			],
			'max config overridden by max create option ' => [
				[
					'HTTPTimeout' => 1,
					'HTTPConnectTimeout' => 2,
					'HTTPMaxTimeout' => 9,
					'HTTPMaxConnectTimeout' => 11
				],
				[
					'timeout' => 100,
					'connectTimeout' => 200,
					'maxTimeout' => 100,
					'maxConnectTimeout' => 200
				],
				[
					'timeout' => 100,
					'connectTimeout' => 200
				]
			],
		];
	}

	/** @dataProvider provideCreateTimeouts */
	public function testCreateTimeouts( $config, $createOptions, $expected ) {
		$factory = $this->newFactory( $config );
		$request = $factory->create( 'https://example.test', $createOptions );
		$request = TestingAccessWrapper::newFromObject( $request );
		foreach ( $expected as $key => $expectedValue ) {
			$this->assertEquals( $expectedValue, $request->$key, "key $key" );
		}
	}

	/** @dataProvider provideCreateTimeouts */
	public function testCreateMultiTimeouts( $config, $createOptions, $expected ) {
		$factory = $this->newFactory( $config );
		$multi = $factory->createMultiClient( $createOptions );
		$multi = TestingAccessWrapper::newFromObject( $multi );
		$this->assertEquals( $expected['connectTimeout'], $multi->connTimeout );
		$this->assertEquals( $expected['timeout'], $multi->reqTimeout );
	}

}
