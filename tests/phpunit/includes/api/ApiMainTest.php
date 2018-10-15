<?php

use Wikimedia\TestingAccessWrapper;

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers ApiMain
 */
class ApiMainTest extends ApiTestCase {

	/**
	 * Test that the API will accept a FauxRequest and execute.
	 */
	public function testApi() {
		$api = new ApiMain(
			new FauxRequest( [ 'action' => 'query', 'meta' => 'siteinfo' ] )
		);
		$api->execute();
		$data = $api->getResult()->getResultData();
		$this->assertInternalType( 'array', $data );
		$this->assertArrayHasKey( 'query', $data );
	}

	public function testApiNoParam() {
		$api = new ApiMain();
		$api->execute();
		$data = $api->getResult()->getResultData();
		$this->assertInternalType( 'array', $data );
	}

	/**
	 * ApiMain behaves differently if passed a FauxRequest (mInternalMode set
	 * to true) or a proper WebRequest (mInternalMode false).  For most tests
	 * we can just set mInternalMode to false using TestingAccessWrapper, but
	 * this doesn't work for the constructor.  This method returns an ApiMain
	 * that's been set up in non-internal mode.
	 *
	 * Note that calling execute() will print to the console.  Wrap it in
	 * ob_start()/ob_end_clean() to prevent this.
	 *
	 * @param array $requestData Query parameters for the WebRequest
	 * @param array $headers Headers for the WebRequest
	 */
	private function getNonInternalApiMain( array $requestData, array $headers = [] ) {
		$req = $this->getMockBuilder( WebRequest::class )
			->setMethods( [ 'response', 'getRawIP' ] )
			->getMock();
		$response = new FauxResponse();
		$req->method( 'response' )->willReturn( $response );
		$req->method( 'getRawIP' )->willReturn( '127.0.0.1' );

		$wrapper = TestingAccessWrapper::newFromObject( $req );
		$wrapper->data = $requestData;
		if ( $headers ) {
			$wrapper->headers = $headers;
		}

		return new ApiMain( $req );
	}

	public function testUselang() {
		global $wgLang;

		$api = $this->getNonInternalApiMain( [
			'action' => 'query',
			'meta' => 'siteinfo',
			'uselang' => 'fr',
		] );

		ob_start();
		$api->execute();
		ob_end_clean();

		$this->assertSame( 'fr', $wgLang->getCode() );
	}

	public function testNonWhitelistedCorsWithCookies() {
		$logFile = $this->getNewTempFile();

		$this->mergeMwGlobalArrayValue( '_COOKIE', [ 'forceHTTPS' => '1' ] );
		$logger = new TestLogger( true );
		$this->setLogger( 'cors', $logger );

		$api = $this->getNonInternalApiMain( [
			'action' => 'query',
			'meta' => 'siteinfo',
		// For some reason multiple origins (which are not allowed in the
		// WHATWG Fetch spec that supersedes the RFC) are always considered to
		// be problematic.
		], [ 'ORIGIN' => 'https://www.example.com https://www.com.example' ] );

		$this->assertSame(
			[ [ Psr\Log\LogLevel::WARNING, 'Non-whitelisted CORS request with session cookies' ] ],
			$logger->getBuffer()
		);
	}

	public function testSuppressedLogin() {
		global $wgUser;
		$origUser = $wgUser;

		$api = $this->getNonInternalApiMain( [
			'action' => 'query',
			'meta' => 'siteinfo',
			'origin' => '*',
		] );

		ob_start();
		$api->execute();
		ob_end_clean();

		$this->assertNotSame( $origUser, $wgUser );
		$this->assertSame( 'true', $api->getContext()->getRequest()->response()
			->getHeader( 'MediaWiki-Login-Suppressed' ) );
	}

	public function testSetContinuationManager() {
		$api = new ApiMain();
		$manager = $this->createMock( ApiContinuationManager::class );
		$api->setContinuationManager( $manager );
		$this->assertTrue( true, 'No exception' );
		return [ $api, $manager ];
	}

	/**
	 * @depends testSetContinuationManager
	 */
	public function testSetContinuationManagerTwice( $args ) {
		$this->setExpectedException( UnexpectedValueException::class,
			'ApiMain::setContinuationManager: tried to set manager from  ' .
			'when a manager is already set from ' );

		list( $api, $manager ) = $args;
		$api->setContinuationManager( $manager );
	}

	public function testSetCacheModeUnrecognized() {
		$api = new ApiMain();
		$api->setCacheMode( 'unrecognized' );
		$this->assertSame(
			'private',
			TestingAccessWrapper::newFromObject( $api )->mCacheMode,
			'Unrecognized params must be silently ignored'
		);
	}

	public function testSetCacheModePrivateWiki() {
		$this->setGroupPermissions( '*', 'read', false );

		$wrappedApi = TestingAccessWrapper::newFromObject( new ApiMain() );
		$wrappedApi->setCacheMode( 'public' );
		$this->assertSame( 'private', $wrappedApi->mCacheMode );
		$wrappedApi->setCacheMode( 'anon-public-user-private' );
		$this->assertSame( 'private', $wrappedApi->mCacheMode );
	}

	public function testAddRequestedFieldsRequestId() {
		$req = new FauxRequest( [
			'action' => 'query',
			'meta' => 'siteinfo',
			'requestid' => '123456',
		] );
		$api = new ApiMain( $req );
		$api->execute();
		$this->assertSame( '123456', $api->getResult()->getResultData()['requestid'] );
	}

	public function testAddRequestedFieldsCurTimestamp() {
		$req = new FauxRequest( [
			'action' => 'query',
			'meta' => 'siteinfo',
			'curtimestamp' => '',
		] );
		$api = new ApiMain( $req );
		$api->execute();
		$timestamp = $api->getResult()->getResultData()['curtimestamp'];
		$this->assertLessThanOrEqual( 1, abs( strtotime( $timestamp ) - time() ) );
	}

	public function testAddRequestedFieldsResponseLangInfo() {
		$req = new FauxRequest( [
			'action' => 'query',
			'meta' => 'siteinfo',
			// errorlang is ignored if errorformat is not specified
			'errorformat' => 'plaintext',
			'uselang' => 'FR',
			'errorlang' => 'ja',
			'responselanginfo' => '',
		] );
		$api = new ApiMain( $req );
		$api->execute();
		$data = $api->getResult()->getResultData();
		$this->assertSame( 'fr', $data['uselang'] );
		$this->assertSame( 'ja', $data['errorlang'] );
	}

	public function testSetupModuleUnknown() {
		$this->setExpectedException( ApiUsageException::class,
			'Unrecognized value for parameter "action": unknownaction.' );

		$req = new FauxRequest( [ 'action' => 'unknownaction' ] );
		$api = new ApiMain( $req );
		$api->execute();
	}

	public function testSetupModuleNoTokenProvided() {
		$this->setExpectedException( ApiUsageException::class,
			'The "token" parameter must be set.' );

		$req = new FauxRequest( [
			'action' => 'edit',
			'title' => 'New page',
			'text' => 'Some text',
		] );
		$api = new ApiMain( $req );
		$api->execute();
	}

	public function testSetupModuleInvalidTokenProvided() {
		$this->setExpectedException( ApiUsageException::class, 'Invalid CSRF token.' );

		$req = new FauxRequest( [
			'action' => 'edit',
			'title' => 'New page',
			'text' => 'Some text',
			'token' => "This isn't a real token!",
		] );
		$api = new ApiMain( $req );
		$api->execute();
	}

	public function testSetupModuleNeedsTokenTrue() {
		$this->setExpectedException( MWException::class,
			"Module 'testmodule' must be updated for the new token handling. " .
			"See documentation for ApiBase::needsToken for details." );

		$mock = $this->createMock( ApiBase::class );
		$mock->method( 'getModuleName' )->willReturn( 'testmodule' );
		$mock->method( 'needsToken' )->willReturn( true );

		$api = new ApiMain( new FauxRequest( [ 'action' => 'testmodule' ] ) );
		$api->getModuleManager()->addModule( 'testmodule', 'action', get_class( $mock ),
			function () use ( $mock ) {
				return $mock;
			}
		);
		$api->execute();
	}

	public function testSetupModuleNeedsTokenNeedntBePosted() {
		$this->setExpectedException( MWException::class,
			"Module 'testmodule' must require POST to use tokens." );

		$mock = $this->createMock( ApiBase::class );
		$mock->method( 'getModuleName' )->willReturn( 'testmodule' );
		$mock->method( 'needsToken' )->willReturn( 'csrf' );
		$mock->method( 'mustBePosted' )->willReturn( false );

		$api = new ApiMain( new FauxRequest( [ 'action' => 'testmodule' ] ) );
		$api->getModuleManager()->addModule( 'testmodule', 'action', get_class( $mock ),
			function () use ( $mock ) {
				return $mock;
			}
		);
		$api->execute();
	}

	public function testCheckMaxLagFailed() {
		// It's hard to mock the LoadBalancer properly, so instead we'll mock
		// checkMaxLag (which is tested directly in other tests below).
		$req = new FauxRequest( [
			'action' => 'query',
			'meta' => 'siteinfo',
		] );

		$mock = $this->getMockBuilder( ApiMain::class )
			->setConstructorArgs( [ $req ] )
			->setMethods( [ 'checkMaxLag' ] )
			->getMock();
		$mock->method( 'checkMaxLag' )->willReturn( false );

		$mock->execute();

		$this->assertArrayNotHasKey( 'query', $mock->getResult()->getResultData() );
	}

	public function testCheckConditionalRequestHeadersFailed() {
		// The detailed checking of all cases of checkConditionalRequestHeaders
		// is below in testCheckConditionalRequestHeaders(), which calls the
		// method directly.  Here we just check that it will stop execution if
		// it does fail.
		$now = time();

		$this->setMwGlobals( 'wgCacheEpoch', '20030516000000' );

		$mock = $this->createMock( ApiBase::class );
		$mock->method( 'getModuleName' )->willReturn( 'testmodule' );
		$mock->method( 'getConditionalRequestData' )
			->willReturn( wfTimestamp( TS_MW, $now - 3600 ) );
		$mock->expects( $this->exactly( 0 ) )->method( 'execute' );

		$req = new FauxRequest( [
			'action' => 'testmodule',
		] );
		$req->setHeader( 'If-Modified-Since', wfTimestamp( TS_RFC2822, $now - 3600 ) );
		$req->setRequestURL( "http://localhost" );

		$api = new ApiMain( $req );
		$api->getModuleManager()->addModule( 'testmodule', 'action', get_class( $mock ),
			function () use ( $mock ) {
				return $mock;
			}
		);

		$wrapper = TestingAccessWrapper::newFromObject( $api );
		$wrapper->mInternalMode = false;

		ob_start();
		$api->execute();
		ob_end_clean();
	}

	private function doTestCheckMaxLag( $lag ) {
		$mockLB = $this->getMockBuilder( LoadBalancer::class )
			->disableOriginalConstructor()
			->setMethods( [ 'getMaxLag', '__destruct' ] )
			->getMock();
		$mockLB->method( 'getMaxLag' )->willReturn( [ 'somehost', $lag ] );
		$this->setService( 'DBLoadBalancer', $mockLB );

		$req = new FauxRequest();

		$api = new ApiMain( $req );
		$wrapper = TestingAccessWrapper::newFromObject( $api );

		$mockModule = $this->createMock( ApiBase::class );
		$mockModule->method( 'shouldCheckMaxLag' )->willReturn( true );

		try {
			$wrapper->checkMaxLag( $mockModule, [ 'maxlag' => 3 ] );
		} finally {
			if ( $lag > 3 ) {
				$this->assertSame( '5', $req->response()->getHeader( 'Retry-After' ) );
				$this->assertSame( (string)$lag, $req->response()->getHeader( 'X-Database-Lag' ) );
			}
		}
	}

	public function testCheckMaxLagOkay() {
		$this->doTestCheckMaxLag( 3 );

		// No exception, we're happy
		$this->assertTrue( true );
	}

	public function testCheckMaxLagExceeded() {
		$this->setExpectedException( ApiUsageException::class,
			'Waiting for a database server: 4 seconds lagged.' );

		$this->setMwGlobals( 'wgShowHostnames', false );

		$this->doTestCheckMaxLag( 4 );
	}

	public function testCheckMaxLagExceededWithHostNames() {
		$this->setExpectedException( ApiUsageException::class,
			'Waiting for somehost: 4 seconds lagged.' );

		$this->setMwGlobals( 'wgShowHostnames', true );

		$this->doTestCheckMaxLag( 4 );
	}

	public static function provideAssert() {
		return [
			[ false, [], 'user', 'assertuserfailed' ],
			[ true, [], 'user', false ],
			[ true, [], 'bot', 'assertbotfailed' ],
			[ true, [ 'bot' ], 'user', false ],
			[ true, [ 'bot' ], 'bot', false ],
		];
	}

	/**
	 * Tests the assert={user|bot} functionality
	 *
	 * @dataProvider provideAssert
	 * @param bool $registered
	 * @param array $rights
	 * @param string $assert
	 * @param string|bool $error False if no error expected
	 */
	public function testAssert( $registered, $rights, $assert, $error ) {
		if ( $registered ) {
			$user = $this->getMutableTestUser()->getUser();
			$user->load(); // load before setting mRights
		} else {
			$user = new User();
		}
		$user->mRights = $rights;
		try {
			$this->doApiRequest( [
				'action' => 'query',
				'assert' => $assert,
			], null, null, $user );
			$this->assertFalse( $error ); // That no error was expected
		} catch ( ApiUsageException $e ) {
			$this->assertTrue( self::apiExceptionHasCode( $e, $error ),
				"Error '{$e->getMessage()}' matched expected '$error'" );
		}
	}

	/**
	 * Tests the assertuser= functionality
	 */
	public function testAssertUser() {
		$user = $this->getTestUser()->getUser();
		$this->doApiRequest( [
			'action' => 'query',
			'assertuser' => $user->getName(),
		], null, null, $user );

		try {
			$this->doApiRequest( [
				'action' => 'query',
				'assertuser' => $user->getName() . 'X',
			], null, null, $user );
			$this->fail( 'Expected exception not thrown' );
		} catch ( ApiUsageException $e ) {
			$this->assertTrue( self::apiExceptionHasCode( $e, 'assertnameduserfailed' ) );
		}
	}

	/**
	 * Test that 'assert' is processed before module errors
	 */
	public function testAssertBeforeModule() {
		// Sanity check that the query without assert throws too-many-titles
		try {
			$this->doApiRequest( [
				'action' => 'query',
				'titles' => implode( '|', range( 1, ApiBase::LIMIT_SML1 + 1 ) ),
			], null, null, new User );
			$this->fail( 'Expected exception not thrown' );
		} catch ( ApiUsageException $e ) {
			$this->assertTrue( self::apiExceptionHasCode( $e, 'too-many-titles' ), 'sanity check' );
		}

		// Now test that the assert happens first
		try {
			$this->doApiRequest( [
				'action' => 'query',
				'titles' => implode( '|', range( 1, ApiBase::LIMIT_SML1 + 1 ) ),
				'assert' => 'user',
			], null, null, new User );
			$this->fail( 'Expected exception not thrown' );
		} catch ( ApiUsageException $e ) {
			$this->assertTrue( self::apiExceptionHasCode( $e, 'assertuserfailed' ),
				"Error '{$e->getMessage()}' matched expected 'assertuserfailed'" );
		}
	}

	/**
	 * Test if all classes in the main module manager exists
	 */
	public function testClassNamesInModuleManager() {
		$api = new ApiMain(
			new FauxRequest( [ 'action' => 'query', 'meta' => 'siteinfo' ] )
		);
		$modules = $api->getModuleManager()->getNamesWithClasses();

		foreach ( $modules as $name => $class ) {
			$this->assertTrue(
				class_exists( $class ),
				'Class ' . $class . ' for api module ' . $name . ' does not exist (with exact case)'
			);
		}
	}

	/**
	 * Test HTTP precondition headers
	 *
	 * @dataProvider provideCheckConditionalRequestHeaders
	 * @param array $headers HTTP headers
	 * @param array $conditions Return data for ApiBase::getConditionalRequestData
	 * @param int $status Expected response status
	 * @param array $options Array of options:
	 *   post => true Request is a POST
	 *   cdn => true CDN is enabled ($wgUseSquid)
	 */
	public function testCheckConditionalRequestHeaders(
		$headers, $conditions, $status, $options = []
	) {
		$request = new FauxRequest(
			[ 'action' => 'query', 'meta' => 'siteinfo' ],
			!empty( $options['post'] )
		);
		$request->setHeaders( $headers );
		$request->response()->statusHeader( 200 ); // Why doesn't it default?

		$context = $this->apiContext->newTestContext( $request, null );
		$api = new ApiMain( $context );
		$priv = TestingAccessWrapper::newFromObject( $api );
		$priv->mInternalMode = false;

		if ( !empty( $options['cdn'] ) ) {
			$this->setMwGlobals( 'wgUseSquid', true );
		}

		// Can't do this in TestSetup.php because Setup.php will override it
		$this->setMwGlobals( 'wgCacheEpoch', '20030516000000' );

		$module = $this->getMockBuilder( ApiBase::class )
			->setConstructorArgs( [ $api, 'mock' ] )
			->setMethods( [ 'getConditionalRequestData' ] )
			->getMockForAbstractClass();
		$module->expects( $this->any() )
			->method( 'getConditionalRequestData' )
			->will( $this->returnCallback( function ( $condition ) use ( $conditions ) {
				return $conditions[$condition] ?? null;
			} ) );

		$ret = $priv->checkConditionalRequestHeaders( $module );

		$this->assertSame( $status, $request->response()->getStatusCode() );
		$this->assertSame( $status === 200, $ret );
	}

	public static function provideCheckConditionalRequestHeaders() {
		global $wgSquidMaxage;
		$now = time();

		return [
			// Non-existing from module is ignored
			'If-None-Match' => [ [ 'If-None-Match' => '"foo", "bar"' ], [], 200 ],
			'If-Modified-Since' =>
				[ [ 'If-Modified-Since' => 'Tue, 18 Aug 2015 00:00:00 GMT' ], [], 200 ],

			// No headers
			'No headers' => [ [], [ 'etag' => '""', 'last-modified' => '20150815000000', ], 200 ],

			// Basic If-None-Match
			'If-None-Match with matching etag' =>
				[ [ 'If-None-Match' => '"foo", "bar"' ], [ 'etag' => '"bar"' ], 304 ],
			'If-None-Match with non-matching etag' =>
				[ [ 'If-None-Match' => '"foo", "bar"' ], [ 'etag' => '"baz"' ], 200 ],
			'Strong If-None-Match with weak matching etag' =>
				[ [ 'If-None-Match' => '"foo"' ], [ 'etag' => 'W/"foo"' ], 304 ],
			'Weak If-None-Match with strong matching etag' =>
				[ [ 'If-None-Match' => 'W/"foo"' ], [ 'etag' => '"foo"' ], 304 ],
			'Weak If-None-Match with weak matching etag' =>
				[ [ 'If-None-Match' => 'W/"foo"' ], [ 'etag' => 'W/"foo"' ], 304 ],

			// Pointless for GET, but supported
			'If-None-Match: *' => [ [ 'If-None-Match' => '*' ], [], 304 ],

			// Basic If-Modified-Since
			'If-Modified-Since, modified one second earlier' =>
				[ [ 'If-Modified-Since' => wfTimestamp( TS_RFC2822, $now ) ],
					[ 'last-modified' => wfTimestamp( TS_MW, $now - 1 ) ], 304 ],
			'If-Modified-Since, modified now' =>
				[ [ 'If-Modified-Since' => wfTimestamp( TS_RFC2822, $now ) ],
					[ 'last-modified' => wfTimestamp( TS_MW, $now ) ], 304 ],
			'If-Modified-Since, modified one second later' =>
				[ [ 'If-Modified-Since' => wfTimestamp( TS_RFC2822, $now ) ],
					[ 'last-modified' => wfTimestamp( TS_MW, $now + 1 ) ], 200 ],

			// If-Modified-Since ignored when If-None-Match is given too
			'Non-matching If-None-Match and matching If-Modified-Since' =>
				[ [ 'If-None-Match' => '""',
					'If-Modified-Since' => wfTimestamp( TS_RFC2822, $now ) ],
					[ 'etag' => '"x"', 'last-modified' => wfTimestamp( TS_MW, $now - 1 ) ], 200 ],
			'Non-matching If-None-Match and matching If-Modified-Since with no ETag' =>
				[
					[
						'If-None-Match' => '""',
						'If-Modified-Since' => wfTimestamp( TS_RFC2822, $now )
					],
					[ 'last-modified' => wfTimestamp( TS_MW, $now - 1 ) ],
					304
				],

			// Ignored for POST
			'Matching If-None-Match with POST' =>
				[ [ 'If-None-Match' => '"foo", "bar"' ], [ 'etag' => '"bar"' ], 200,
					[ 'post' => true ] ],
			'Matching If-Modified-Since with POST' =>
				[ [ 'If-Modified-Since' => wfTimestamp( TS_RFC2822, $now ) ],
					[ 'last-modified' => wfTimestamp( TS_MW, $now - 1 ) ], 200,
					[ 'post' => true ] ],

			// Other date formats allowed by the RFC
			'If-Modified-Since with alternate date format 1' =>
				[ [ 'If-Modified-Since' => gmdate( 'l, d-M-y H:i:s', $now ) . ' GMT' ],
					[ 'last-modified' => wfTimestamp( TS_MW, $now - 1 ) ], 304 ],
			'If-Modified-Since with alternate date format 2' =>
				[ [ 'If-Modified-Since' => gmdate( 'D M j H:i:s Y', $now ) ],
					[ 'last-modified' => wfTimestamp( TS_MW, $now - 1 ) ], 304 ],

			// Old browser extension to HTTP/1.0
			'If-Modified-Since with length' =>
				[ [ 'If-Modified-Since' => wfTimestamp( TS_RFC2822, $now ) . '; length=123' ],
					[ 'last-modified' => wfTimestamp( TS_MW, $now - 1 ) ], 304 ],

			// Invalid date formats should be ignored
			'If-Modified-Since with invalid date format' =>
				[ [ 'If-Modified-Since' => gmdate( 'Y-m-d H:i:s', $now ) . ' GMT' ],
					[ 'last-modified' => wfTimestamp( TS_MW, $now - 1 ) ], 200 ],
			'If-Modified-Since with entirely unparseable date' =>
				[ [ 'If-Modified-Since' => 'a potato' ],
					[ 'last-modified' => wfTimestamp( TS_MW, $now - 1 ) ], 200 ],

			// Anything before $wgSquidMaxage seconds ago should be considered
			// expired.
			'If-Modified-Since with CDN post-expiry' =>
				[ [ 'If-Modified-Since' => wfTimestamp( TS_RFC2822, $now - $wgSquidMaxage * 2 ) ],
					[ 'last-modified' => wfTimestamp( TS_MW, $now - $wgSquidMaxage * 3 ) ],
					200, [ 'cdn' => true ] ],
			'If-Modified-Since with CDN pre-expiry' =>
				[ [ 'If-Modified-Since' => wfTimestamp( TS_RFC2822, $now - $wgSquidMaxage / 2 ) ],
					[ 'last-modified' => wfTimestamp( TS_MW, $now - $wgSquidMaxage * 3 ) ],
					304, [ 'cdn' => true ] ],
		];
	}

	/**
	 * Test conditional headers output
	 * @dataProvider provideConditionalRequestHeadersOutput
	 * @param array $conditions Return data for ApiBase::getConditionalRequestData
	 * @param array $headers Expected output headers
	 * @param bool $isError $isError flag
	 * @param bool $post Request is a POST
	 */
	public function testConditionalRequestHeadersOutput(
		$conditions, $headers, $isError = false, $post = false
	) {
		$request = new FauxRequest( [ 'action' => 'query', 'meta' => 'siteinfo' ], $post );
		$response = $request->response();

		$api = new ApiMain( $request );
		$priv = TestingAccessWrapper::newFromObject( $api );
		$priv->mInternalMode = false;

		$module = $this->getMockBuilder( ApiBase::class )
			->setConstructorArgs( [ $api, 'mock' ] )
			->setMethods( [ 'getConditionalRequestData' ] )
			->getMockForAbstractClass();
		$module->expects( $this->any() )
			->method( 'getConditionalRequestData' )
			->will( $this->returnCallback( function ( $condition ) use ( $conditions ) {
				return $conditions[$condition] ?? null;
			} ) );
		$priv->mModule = $module;

		$priv->sendCacheHeaders( $isError );

		foreach ( [ 'Last-Modified', 'ETag' ] as $header ) {
			$this->assertEquals(
				$headers[$header] ?? null,
				$response->getHeader( $header ),
				$header
			);
		}
	}

	public static function provideConditionalRequestHeadersOutput() {
		return [
			[
				[],
				[]
			],
			[
				[ 'etag' => '"foo"' ],
				[ 'ETag' => '"foo"' ]
			],
			[
				[ 'last-modified' => '20150818000102' ],
				[ 'Last-Modified' => 'Tue, 18 Aug 2015 00:01:02 GMT' ]
			],
			[
				[ 'etag' => '"foo"', 'last-modified' => '20150818000102' ],
				[ 'ETag' => '"foo"', 'Last-Modified' => 'Tue, 18 Aug 2015 00:01:02 GMT' ]
			],
			[
				[ 'etag' => '"foo"', 'last-modified' => '20150818000102' ],
				[],
				true,
			],
			[
				[ 'etag' => '"foo"', 'last-modified' => '20150818000102' ],
				[],
				false,
				true,
			],
		];
	}

	public function testCheckExecutePermissionsReadProhibited() {
		$this->setExpectedException( ApiUsageException::class,
			'You need read permission to use this module.' );

		$this->setGroupPermissions( '*', 'read', false );

		$main = new ApiMain( new FauxRequest( [ 'action' => 'query', 'meta' => 'siteinfo' ] ) );
		$main->execute();
	}

	public function testCheckExecutePermissionWriteDisabled() {
		$this->setExpectedException( ApiUsageException::class,
			'Editing of this wiki through the API is disabled. Make sure the ' .
			'"$wgEnableWriteAPI=true;" statement is included in the wiki\'s ' .
			'"LocalSettings.php" file.' );
		$main = new ApiMain( new FauxRequest( [
			'action' => 'edit',
			'title' => 'Some page',
			'text' => 'Some text',
			'token' => '+\\',
		] ) );
		$main->execute();
	}

	public function testCheckExecutePermissionWriteApiProhibited() {
		$this->setExpectedException( ApiUsageException::class,
			"You're not allowed to edit this wiki through the API." );
		$this->setGroupPermissions( '*', 'writeapi', false );

		$main = new ApiMain( new FauxRequest( [
			'action' => 'edit',
			'title' => 'Some page',
			'text' => 'Some text',
			'token' => '+\\',
		] ), /* enableWrite = */ true );
		$main->execute();
	}

	public function testCheckExecutePermissionPromiseNonWrite() {
		$this->setExpectedException( ApiUsageException::class,
			'The "Promise-Non-Write-API-Action" HTTP header cannot be sent ' .
			'to write-mode API modules.' );

		$req = new FauxRequest( [
			'action' => 'edit',
			'title' => 'Some page',
			'text' => 'Some text',
			'token' => '+\\',
		] );
		$req->setHeaders( [ 'Promise-Non-Write-API-Action' => '1' ] );
		$main = new ApiMain( $req, /* enableWrite = */ true );
		$main->execute();
	}

	public function testCheckExecutePermissionHookAbort() {
		$this->setExpectedException( ApiUsageException::class, 'Main Page' );

		$this->setTemporaryHook( 'ApiCheckCanExecute', function ( $unused1, $unused2, &$message ) {
			$message = 'mainpage';
			return false;
		} );

		$main = new ApiMain( new FauxRequest( [
			'action' => 'edit',
			'title' => 'Some page',
			'text' => 'Some text',
			'token' => '+\\',
		] ), /* enableWrite = */ true );
		$main->execute();
	}

	public function testGetValUnsupportedArray() {
		$main = new ApiMain( new FauxRequest( [
			'action' => 'query',
			'meta' => 'siteinfo',
			'siprop' => [ 'general', 'namespaces' ],
		] ) );
		$this->assertSame( 'myDefault', $main->getVal( 'siprop', 'myDefault' ) );
		$main->execute();
		$this->assertSame( 'Parameter "siprop" uses unsupported PHP array syntax.',
			$main->getResult()->getResultData()['warnings']['main']['warnings'] );
	}

	public function testReportUnusedParams() {
		$main = new ApiMain( new FauxRequest( [
			'action' => 'query',
			'meta' => 'siteinfo',
			'unusedparam' => 'unusedval',
			'anotherunusedparam' => 'anotherval',
		] ) );
		$main->execute();
		$this->assertSame( 'Unrecognized parameters: unusedparam, anotherunusedparam.',
			$main->getResult()->getResultData()['warnings']['main']['warnings'] );
	}

	public function testLacksSameOriginSecurity() {
		// Basic test
		$main = new ApiMain( new FauxRequest( [ 'action' => 'query', 'meta' => 'siteinfo' ] ) );
		$this->assertFalse( $main->lacksSameOriginSecurity(), 'Basic test, should have security' );

		// JSONp
		$main = new ApiMain(
			new FauxRequest( [ 'action' => 'query', 'format' => 'xml', 'callback' => 'foo' ] )
		);
		$this->assertTrue( $main->lacksSameOriginSecurity(), 'JSONp, should lack security' );

		// Header
		$request = new FauxRequest( [ 'action' => 'query', 'meta' => 'siteinfo' ] );
		$request->setHeader( 'TrEaT-As-UnTrUsTeD', '' ); // With falsey value!
		$main = new ApiMain( $request );
		$this->assertTrue( $main->lacksSameOriginSecurity(), 'Header supplied, should lack security' );

		// Hook
		$this->mergeMwGlobalArrayValue( 'wgHooks', [
			'RequestHasSameOriginSecurity' => [ function () {
				return false;
			} ]
		] );
		$main = new ApiMain( new FauxRequest( [ 'action' => 'query', 'meta' => 'siteinfo' ] ) );
		$this->assertTrue( $main->lacksSameOriginSecurity(), 'Hook, should lack security' );
	}

	/**
	 * Test proper creation of the ApiErrorFormatter
	 *
	 * @dataProvider provideApiErrorFormatterCreation
	 * @param array $request Request parameters
	 * @param array $expect Expected data
	 *  - uselang: ApiMain language
	 *  - class: ApiErrorFormatter class
	 *  - lang: ApiErrorFormatter language
	 *  - format: ApiErrorFormatter format
	 *  - usedb: ApiErrorFormatter use-database flag
	 */
	public function testApiErrorFormatterCreation( array $request, array $expect ) {
		$context = new RequestContext();
		$context->setRequest( new FauxRequest( $request ) );
		$context->setLanguage( 'ru' );

		$main = new ApiMain( $context );
		$formatter = $main->getErrorFormatter();
		$wrappedFormatter = TestingAccessWrapper::newFromObject( $formatter );

		$this->assertSame( $expect['uselang'], $main->getLanguage()->getCode() );
		$this->assertInstanceOf( $expect['class'], $formatter );
		$this->assertSame( $expect['lang'], $formatter->getLanguage()->getCode() );
		$this->assertSame( $expect['format'], $wrappedFormatter->format );
		$this->assertSame( $expect['usedb'], $wrappedFormatter->useDB );
	}

	public static function provideApiErrorFormatterCreation() {
		return [
			'Default (BC)' => [ [], [
				'uselang' => 'ru',
				'class' => ApiErrorFormatter_BackCompat::class,
				'lang' => 'en',
				'format' => 'none',
				'usedb' => false,
			] ],
			'BC ignores fields' => [ [ 'errorlang' => 'de', 'errorsuselocal' => 1 ], [
				'uselang' => 'ru',
				'class' => ApiErrorFormatter_BackCompat::class,
				'lang' => 'en',
				'format' => 'none',
				'usedb' => false,
			] ],
			'Explicit BC' => [ [ 'errorformat' => 'bc' ], [
				'uselang' => 'ru',
				'class' => ApiErrorFormatter_BackCompat::class,
				'lang' => 'en',
				'format' => 'none',
				'usedb' => false,
			] ],
			'Basic' => [ [ 'errorformat' => 'wikitext' ], [
				'uselang' => 'ru',
				'class' => ApiErrorFormatter::class,
				'lang' => 'ru',
				'format' => 'wikitext',
				'usedb' => false,
			] ],
			'Follows uselang' => [ [ 'uselang' => 'fr', 'errorformat' => 'plaintext' ], [
				'uselang' => 'fr',
				'class' => ApiErrorFormatter::class,
				'lang' => 'fr',
				'format' => 'plaintext',
				'usedb' => false,
			] ],
			'Explicitly follows uselang' => [
				[ 'uselang' => 'fr', 'errorlang' => 'uselang', 'errorformat' => 'plaintext' ],
				[
					'uselang' => 'fr',
					'class' => ApiErrorFormatter::class,
					'lang' => 'fr',
					'format' => 'plaintext',
					'usedb' => false,
				]
			],
			'uselang=content' => [
				[ 'uselang' => 'content', 'errorformat' => 'plaintext' ],
				[
					'uselang' => 'en',
					'class' => ApiErrorFormatter::class,
					'lang' => 'en',
					'format' => 'plaintext',
					'usedb' => false,
				]
			],
			'errorlang=content' => [
				[ 'errorlang' => 'content', 'errorformat' => 'plaintext' ],
				[
					'uselang' => 'ru',
					'class' => ApiErrorFormatter::class,
					'lang' => 'en',
					'format' => 'plaintext',
					'usedb' => false,
				]
			],
			'Explicit parameters' => [
				[ 'errorlang' => 'de', 'errorformat' => 'html', 'errorsuselocal' => 1 ],
				[
					'uselang' => 'ru',
					'class' => ApiErrorFormatter::class,
					'lang' => 'de',
					'format' => 'html',
					'usedb' => true,
				]
			],
			'Explicit parameters override uselang' => [
				[ 'errorlang' => 'de', 'uselang' => 'fr', 'errorformat' => 'raw' ],
				[
					'uselang' => 'fr',
					'class' => ApiErrorFormatter::class,
					'lang' => 'de',
					'format' => 'raw',
					'usedb' => false,
				]
			],
			'Bogus language doesn\'t explode' => [
				[ 'errorlang' => '<bogus1>', 'uselang' => '<bogus2>', 'errorformat' => 'none' ],
				[
					'uselang' => 'en',
					'class' => ApiErrorFormatter::class,
					'lang' => 'en',
					'format' => 'none',
					'usedb' => false,
				]
			],
			'Bogus format doesn\'t explode' => [ [ 'errorformat' => 'bogus' ], [
				'uselang' => 'ru',
				'class' => ApiErrorFormatter_BackCompat::class,
				'lang' => 'en',
				'format' => 'none',
				'usedb' => false,
			] ],
		];
	}

	/**
	 * @dataProvider provideExceptionErrors
	 * @param Exception $exception
	 * @param array $expectReturn
	 * @param array $expectResult
	 */
	public function testExceptionErrors( $error, $expectReturn, $expectResult ) {
		$context = new RequestContext();
		$context->setRequest( new FauxRequest( [ 'errorformat' => 'plaintext' ] ) );
		$context->setLanguage( 'en' );
		$context->setConfig( new MultiConfig( [
			new HashConfig( [
				'ShowHostnames' => true, 'ShowExceptionDetails' => true,
			] ),
			$context->getConfig()
		] ) );

		$main = new ApiMain( $context );
		$main->addWarning( new RawMessage( 'existing warning' ), 'existing-warning' );
		$main->addError( new RawMessage( 'existing error' ), 'existing-error' );

		$ret = TestingAccessWrapper::newFromObject( $main )->substituteResultWithError( $error );
		$this->assertSame( $expectReturn, $ret );

		// PHPUnit sometimes adds some SplObjectStorage garbage to the arrays,
		// so let's try ->assertEquals().
		$this->assertEquals(
			$expectResult,
			$main->getResult()->getResultData( [], [ 'Strip' => 'all' ] )
		);
	}

	// Not static so $this can be used
	public function provideExceptionErrors() {
		$reqId = WebRequest::getRequestId();
		$doclink = wfExpandUrl( wfScript( 'api' ) );

		$ex = new InvalidArgumentException( 'Random exception' );
		$trace = wfMessage( 'api-exception-trace',
			get_class( $ex ),
			$ex->getFile(),
			$ex->getLine(),
			MWExceptionHandler::getRedactedTraceAsString( $ex )
		)->inLanguage( 'en' )->useDatabase( false )->text();

		$dbex = new DBQueryError(
			$this->createMock( \Wikimedia\Rdbms\IDatabase::class ),
			'error', 1234, 'SELECT 1', __METHOD__ );
		$dbtrace = wfMessage( 'api-exception-trace',
			get_class( $dbex ),
			$dbex->getFile(),
			$dbex->getLine(),
			MWExceptionHandler::getRedactedTraceAsString( $dbex )
		)->inLanguage( 'en' )->useDatabase( false )->text();

		$apiEx1 = new ApiUsageException( null,
			StatusValue::newFatal( new ApiRawMessage( 'An error', 'sv-error1' ) ) );
		TestingAccessWrapper::newFromObject( $apiEx1 )->modulePath = 'foo+bar';
		$apiEx1->getStatusValue()->warning( new ApiRawMessage( 'A warning', 'sv-warn1' ) );
		$apiEx1->getStatusValue()->warning( new ApiRawMessage( 'Another warning', 'sv-warn2' ) );
		$apiEx1->getStatusValue()->fatal( new ApiRawMessage( 'Another error', 'sv-error2' ) );

		return [
			[
				$ex,
				[ 'existing-error', 'internal_api_error_InvalidArgumentException' ],
				[
					'warnings' => [
						[ 'code' => 'existing-warning', 'text' => 'existing warning', 'module' => 'main' ],
					],
					'errors' => [
						[ 'code' => 'existing-error', 'text' => 'existing error', 'module' => 'main' ],
						[
							'code' => 'internal_api_error_InvalidArgumentException',
							'text' => "[$reqId] Exception caught: Random exception",
						]
					],
					'trace' => $trace,
					'servedby' => wfHostname(),
				]
			],
			[
				$dbex,
				[ 'existing-error', 'internal_api_error_DBQueryError' ],
				[
					'warnings' => [
						[ 'code' => 'existing-warning', 'text' => 'existing warning', 'module' => 'main' ],
					],
					'errors' => [
						[ 'code' => 'existing-error', 'text' => 'existing error', 'module' => 'main' ],
						[
							'code' => 'internal_api_error_DBQueryError',
							'text' => "[$reqId] Exception caught: A database query error has occurred. " .
								"This may indicate a bug in the software.",
						]
					],
					'trace' => $dbtrace,
					'servedby' => wfHostname(),
				]
			],
			[
				$apiEx1,
				[ 'existing-error', 'sv-error1', 'sv-error2' ],
				[
					'warnings' => [
						[ 'code' => 'existing-warning', 'text' => 'existing warning', 'module' => 'main' ],
						[ 'code' => 'sv-warn1', 'text' => 'A warning', 'module' => 'foo+bar' ],
						[ 'code' => 'sv-warn2', 'text' => 'Another warning', 'module' => 'foo+bar' ],
					],
					'errors' => [
						[ 'code' => 'existing-error', 'text' => 'existing error', 'module' => 'main' ],
						[ 'code' => 'sv-error1', 'text' => 'An error', 'module' => 'foo+bar' ],
						[ 'code' => 'sv-error2', 'text' => 'Another error', 'module' => 'foo+bar' ],
					],
					'docref' => "See $doclink for API usage. Subscribe to the mediawiki-api-announce mailing " .
						"list at &lt;https://lists.wikimedia.org/mailman/listinfo/mediawiki-api-announce&gt; " .
						"for notice of API deprecations and breaking changes.",
					'servedby' => wfHostname(),
				]
			],
		];
	}

	public function testPrinterParameterValidationError() {
		$api = $this->getNonInternalApiMain( [
			'action' => 'query', 'meta' => 'siteinfo', 'format' => 'json', 'formatversion' => 'bogus',
		] );

		ob_start();
		$api->execute();
		$txt = ob_get_clean();

		// Test that the actual output is valid JSON, not just the format of the ApiResult.
		$data = FormatJson::decode( $txt, true );
		$this->assertInternalType( 'array', $data );
		$this->assertArrayHasKey( 'error', $data );
		$this->assertArrayHasKey( 'code', $data['error'] );
		$this->assertSame( 'unknown_formatversion', $data['error']['code'] );
	}
}
