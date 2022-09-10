<?php

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Http\HttpRequestFactory;
use MediaWiki\MainConfigNames;
use Psr\Log\NullLogger;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers MediaWiki\Http\HttpRequestFactory
 */
class HttpRequestFactoryTest extends MediaWikiUnitTestCase {

	/**
	 * @param array|null $options
	 * @return HttpRequestFactory
	 */
	private function newFactory( $options = null ) {
		if ( !$options ) {
			$options = [
				MainConfigNames::HTTPTimeout => 1,
				MainConfigNames::HTTPConnectTimeout => 1,
				MainConfigNames::HTTPMaxTimeout => INF,
				MainConfigNames::HTTPMaxConnectTimeout => INF
			];
		}
		$options += [
			MainConfigNames::LocalVirtualHosts => [],
			MainConfigNames::LocalHTTPProxy => false,
		];
		return new HttpRequestFactory(
			new ServiceOptions( HttpRequestFactory::CONSTRUCTOR_OPTIONS, $options ),
			new NullLogger
		);
	}

	/**
	 * @param MWHttpRequest $req
	 * @param string $expectedUrl
	 * @param array $expectedOptions
	 * @return HttpRequestFactory
	 */
	private function newFactoryWithFakeRequest(
		MWHttpRequest $req,
		$expectedUrl,
		$expectedOptions = []
	) {
		$factory = $this->getMockBuilder( HttpRequestFactory::class )
			->onlyMethods( [ 'create' ] )
			->disableOriginalConstructor()
			->getMock();

		$factory->method( 'create' )
			->willReturnCallback(
				function ( $url, array $options = [], $caller = __METHOD__ )
					use ( $req, $expectedUrl, $expectedOptions )
				{
					$this->assertSame( $expectedUrl, $url );

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
	 * @param Status|string $result
	 * @return MWHttpRequest
	 */
	private function newFakeRequest( $result ) {
		if ( !( $result instanceof Status ) ) {
			$result = Status::newGood( $result );
		}

		$req = $this->getMockBuilder( MWHttpRequest::class )
			->disableOriginalConstructor()
			->onlyMethods( [ 'getContent', 'execute' ] )
			->getMock();

		$req->method( 'getContent' )
			->willReturn( $result->getValue() );
		$req->method( 'execute' )
			->willReturn( $result );

		return $req;
	}

	public function testCreate() {
		$factory = $this->newFactory();
		$this->assertInstanceOf( MWHttpRequest::class, $factory->create( 'http://example.test' ) );
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
		$status = new class extends Status {
			public function getWikiText( $shortContext = false, $longContext = false, $lang = null ) {
				// Status::getWikiText doesn't work in unit tests
				return '';
			}
		};
		$status->fatal( 'testing' );

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
					MainConfigNames::HTTPTimeout => 10,
					MainConfigNames::HTTPConnectTimeout => 20,
					MainConfigNames::HTTPMaxTimeout => INF,
					MainConfigNames::HTTPMaxConnectTimeout => INF
				],
				[],
				[
					'timeout' => 10,
					'connectTimeout' => 20
				]
			],
			'config defaults overridden by max' => [
				[
					MainConfigNames::HTTPTimeout => 10,
					MainConfigNames::HTTPConnectTimeout => 20,
					MainConfigNames::HTTPMaxTimeout => 9,
					MainConfigNames::HTTPMaxConnectTimeout => 11
				],
				[],
				[
					'timeout' => 9,
					'connectTimeout' => 11
				]
			],
			'create option overridden by max config' => [
				[
					MainConfigNames::HTTPTimeout => 1,
					MainConfigNames::HTTPConnectTimeout => 2,
					MainConfigNames::HTTPMaxTimeout => 9,
					MainConfigNames::HTTPMaxConnectTimeout => 11
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
					MainConfigNames::HTTPTimeout => 1,
					MainConfigNames::HTTPConnectTimeout => 2,
					MainConfigNames::HTTPMaxTimeout => 9,
					MainConfigNames::HTTPMaxConnectTimeout => 11
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
					MainConfigNames::HTTPTimeout => 1,
					MainConfigNames::HTTPConnectTimeout => 2,
					MainConfigNames::HTTPMaxTimeout => 9,
					MainConfigNames::HTTPMaxConnectTimeout => 11
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

	/** @dataProvider provideCreateTimeouts */
	public function testCreateGuzzleClient( $config, $createOptions, $expected ) {
		$factory = $this->newFactory( $config );
		$client = $factory->createGuzzleClient(
			[
				'timeout' => $createOptions['timeout'] ?? null,
				'connect_timeout' => $createOptions['connectTimeout'] ?? null,
				'maxTimeout' => $createOptions['maxTimeout'] ?? null,
				'maxConnectTimeout' => $createOptions['maxConnectTimeout'] ?? null
			]
		);
		$this->assertEquals(
			$expected['connectTimeout'],
			$client->getConfig( 'connect_timeout' )
		);
		$this->assertEquals(
			$expected['timeout'],
			$client->getConfig( 'timeout' )
		);
	}
}
