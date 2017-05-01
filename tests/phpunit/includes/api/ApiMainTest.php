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
	 * @covers ApiMain::checkAsserts
	 * @dataProvider provideAssert
	 * @param bool $registered
	 * @param array $rights
	 * @param string $assert
	 * @param string|bool $error False if no error expected
	 */
	public function testAssert( $registered, $rights, $assert, $error ) {
		$user = new User();
		if ( $registered ) {
			$user->setId( 1 );
		}
		$user->mRights = $rights;
		try {
			$this->doApiRequest( [
				'action' => 'query',
				'assert' => $assert,
			], null, null, $user );
			$this->assertFalse( $error ); // That no error was expected
		} catch ( ApiUsageException $e ) {
			$this->assertTrue( self::apiExceptionHasCode( $e, $error ) );
		}
	}

	/**
	 * Tests the assertuser= functionality
	 *
	 * @covers ApiMain::checkAsserts
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
	 * @covers ApiMain::checkConditionalRequestHeaders
	 * @dataProvider provideCheckConditionalRequestHeaders
	 * @param array $headers HTTP headers
	 * @param array $conditions Return data for ApiBase::getConditionalRequestData
	 * @param int $status Expected response status
	 * @param bool $post Request is a POST
	 */
	public function testCheckConditionalRequestHeaders(
		$headers, $conditions, $status, $post = false
	) {
		$request = new FauxRequest( [ 'action' => 'query', 'meta' => 'siteinfo' ], $post );
		$request->setHeaders( $headers );
		$request->response()->statusHeader( 200 ); // Why doesn't it default?

		$context = $this->apiContext->newTestContext( $request, null );
		$api = new ApiMain( $context );
		$priv = TestingAccessWrapper::newFromObject( $api );
		$priv->mInternalMode = false;

		$module = $this->getMockBuilder( 'ApiBase' )
			->setConstructorArgs( [ $api, 'mock' ] )
			->setMethods( [ 'getConditionalRequestData' ] )
			->getMockForAbstractClass();
		$module->expects( $this->any() )
			->method( 'getConditionalRequestData' )
			->will( $this->returnCallback( function ( $condition ) use ( $conditions ) {
				return isset( $conditions[$condition] ) ? $conditions[$condition] : null;
			} ) );

		$ret = $priv->checkConditionalRequestHeaders( $module );

		$this->assertSame( $status, $request->response()->getStatusCode() );
		$this->assertSame( $status === 200, $ret );
	}

	public static function provideCheckConditionalRequestHeaders() {
		$now = time();

		return [
			// Non-existing from module is ignored
			[ [ 'If-None-Match' => '"foo", "bar"' ], [], 200 ],
			[ [ 'If-Modified-Since' => 'Tue, 18 Aug 2015 00:00:00 GMT' ], [], 200 ],

			// No headers
			[
				[],
				[
					'etag' => '""',
					'last-modified' => '20150815000000',
				],
				200
			],

			// Basic If-None-Match
			[ [ 'If-None-Match' => '"foo", "bar"' ], [ 'etag' => '"bar"' ], 304 ],
			[ [ 'If-None-Match' => '"foo", "bar"' ], [ 'etag' => '"baz"' ], 200 ],
			[ [ 'If-None-Match' => '"foo"' ], [ 'etag' => 'W/"foo"' ], 304 ],
			[ [ 'If-None-Match' => 'W/"foo"' ], [ 'etag' => '"foo"' ], 304 ],
			[ [ 'If-None-Match' => 'W/"foo"' ], [ 'etag' => 'W/"foo"' ], 304 ],

			// Pointless, but supported
			[ [ 'If-None-Match' => '*' ], [], 304 ],

			// Basic If-Modified-Since
			[ [ 'If-Modified-Since' => wfTimestamp( TS_RFC2822, $now ) ],
				[ 'last-modified' => wfTimestamp( TS_MW, $now - 1 ) ], 304 ],
			[ [ 'If-Modified-Since' => wfTimestamp( TS_RFC2822, $now ) ],
				[ 'last-modified' => wfTimestamp( TS_MW, $now ) ], 304 ],
			[ [ 'If-Modified-Since' => wfTimestamp( TS_RFC2822, $now ) ],
				[ 'last-modified' => wfTimestamp( TS_MW, $now + 1 ) ], 200 ],

			// If-Modified-Since ignored when If-None-Match is given too
			[ [ 'If-None-Match' => '""', 'If-Modified-Since' => wfTimestamp( TS_RFC2822, $now ) ],
				[ 'etag' => '"x"', 'last-modified' => wfTimestamp( TS_MW, $now - 1 ) ], 200 ],
			[ [ 'If-None-Match' => '""', 'If-Modified-Since' => wfTimestamp( TS_RFC2822, $now ) ],
				[ 'last-modified' => wfTimestamp( TS_MW, $now - 1 ) ], 304 ],

			// Ignored for POST
			[ [ 'If-None-Match' => '"foo", "bar"' ], [ 'etag' => '"bar"' ], 200, true ],
			[ [ 'If-Modified-Since' => wfTimestamp( TS_RFC2822, $now ) ],
				[ 'last-modified' => wfTimestamp( TS_MW, $now - 1 ) ], 200, true ],

			// Other date formats allowed by the RFC
			[ [ 'If-Modified-Since' => gmdate( 'l, d-M-y H:i:s', $now ) . ' GMT' ],
				[ 'last-modified' => wfTimestamp( TS_MW, $now - 1 ) ], 304 ],
			[ [ 'If-Modified-Since' => gmdate( 'D M j H:i:s Y', $now ) ],
				[ 'last-modified' => wfTimestamp( TS_MW, $now - 1 ) ], 304 ],

			// Old browser extension to HTTP/1.0
			[ [ 'If-Modified-Since' => wfTimestamp( TS_RFC2822, $now ) . '; length=123' ],
				[ 'last-modified' => wfTimestamp( TS_MW, $now - 1 ) ], 304 ],

			// Invalid date formats should be ignored
			[ [ 'If-Modified-Since' => gmdate( 'Y-m-d H:i:s', $now ) . ' GMT' ],
				[ 'last-modified' => wfTimestamp( TS_MW, $now - 1 ) ], 200 ],
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

		$module = $this->getMockBuilder( 'ApiBase' )
			->setConstructorArgs( [ $api, 'mock' ] )
			->setMethods( [ 'getConditionalRequestData' ] )
			->getMockForAbstractClass();
		$module->expects( $this->any() )
			->method( 'getConditionalRequestData' )
			->will( $this->returnCallback( function ( $condition ) use ( $conditions ) {
				return isset( $conditions[$condition] ) ? $conditions[$condition] : null;
			} ) );
		$priv->mModule = $module;

		$priv->sendCacheHeaders( $isError );

		foreach ( [ 'Last-Modified', 'ETag' ] as $header ) {
			$this->assertEquals(
				isset( $headers[$header] ) ? $headers[$header] : null,
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

	/**
	 * @covers ApiMain::lacksSameOriginSecurity
	 */
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
	 * @covers ApiMain::__construct
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
	 * @covers ApiMain::errorMessagesFromException
	 * @covers ApiMain::substituteResultWithError
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
				'ShowHostnames' => true, 'ShowSQLErrors' => false,
				'ShowExceptionDetails' => true, 'ShowDBErrorBacktrace' => true,
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
			$this->createMock( 'IDatabase' ),
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
							'text' => "[$reqId] Database query error.",
						]
					],
					'trace' => $dbtrace,
					'servedby' => wfHostname(),
				]
			],
			[
				new UsageException( 'Usage exception!', 'ue', 0, [ 'foo' => 'bar' ] ),
				[ 'existing-error', 'ue' ],
				[
					'warnings' => [
						[ 'code' => 'existing-warning', 'text' => 'existing warning', 'module' => 'main' ],
					],
					'errors' => [
						[ 'code' => 'existing-error', 'text' => 'existing error', 'module' => 'main' ],
						[ 'code' => 'ue', 'text' => "Usage exception!", 'data' => [ 'foo' => 'bar' ] ]
					],
					'docref' => "See $doclink for API usage. Subscribe to the mediawiki-api-announce mailing " .
						"list at &lt;https://lists.wikimedia.org/mailman/listinfo/mediawiki-api-announce&gt; " .
						"for notice of API deprecations and breaking changes.",
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
}
