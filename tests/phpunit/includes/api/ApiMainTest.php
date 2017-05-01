<?php

/**
 * @group API
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
		} catch ( UsageException $e ) {
			$this->assertEquals( $e->getCodeString(), $error );
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
		} catch ( UsageException $e ) {
			$this->assertEquals( $e->getCodeString(), 'assertnameduserfailed' );
		}
	}

	/**
	 * Test if all classes in the main module manager exists
	 */
	public function testClassNamesInModuleManager() {
		global $wgAutoloadLocalClasses, $wgAutoloadClasses;

		// wgAutoloadLocalClasses has precedence, just like in includes/AutoLoader.php
		$classes = $wgAutoloadLocalClasses + $wgAutoloadClasses;

		$api = new ApiMain(
			new FauxRequest( [ 'action' => 'query', 'meta' => 'siteinfo' ] )
		);
		$modules = $api->getModuleManager()->getNamesWithClasses();
		foreach ( $modules as $name => $class ) {
			$this->assertArrayHasKey(
				$class,
				$classes,
				'Class ' . $class . ' for api module ' . $name . ' not in autoloader (with exact case)'
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
}
