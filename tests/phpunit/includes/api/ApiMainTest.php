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
			new FauxRequest( array( 'action' => 'query', 'meta' => 'siteinfo' ) )
		);
		$api->execute();
		$data = $api->getResult()->getResultData();
		$this->assertInternalType( 'array', $data );
		$this->assertArrayHasKey( 'query', $data );
	}

	public static function provideAssert() {
		return array(
			array( false, array(), 'user', 'assertuserfailed' ),
			array( true, array(), 'user', false ),
			array( true, array(), 'bot', 'assertbotfailed' ),
			array( true, array( 'bot' ), 'user', false ),
			array( true, array( 'bot' ), 'bot', false ),
		);
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
			$this->doApiRequest( array(
				'action' => 'query',
				'assert' => $assert,
			), null, null, $user );
			$this->assertFalse( $error ); // That no error was expected
		} catch ( UsageException $e ) {
			$this->assertEquals( $e->getCodeString(), $error );
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
			new FauxRequest( array( 'action' => 'query', 'meta' => 'siteinfo' ) )
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
	public function testCheckConditionalRequestHeaders( $headers, $conditions, $status, $post = false ) {
		$request = new FauxRequest( array( 'action' => 'query', 'meta' => 'siteinfo' ), $post );
		$request->setHeaders( $headers );
		$request->response()->statusHeader( 200 ); // Why doesn't it default?

		$api = new ApiMain( $request );
		$priv = TestingAccessWrapper::newFromObject( $api );
		$priv->mInternalMode = false;

		$module = $this->getMockBuilder( 'ApiBase' )
			->setConstructorArgs( array( $api, 'mock' ) )
			->setMethods( array( 'getConditionalRequestData' ) )
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

		return array(
			// Non-existing from module is ignored
			array( array( 'If-None-Match' => '"foo", "bar"' ), array(), 200 ),
			array( array( 'If-Modified-Since' => 'Tue, 18 Aug 2015 00:00:00 GMT' ), array(), 200 ),

			// No headers
			array(
				array(),
				array(
					'etag' => '""',
					'last-modified' => '20150815000000',
				),
				200
			),

			// Basic If-None-Match
			array( array( 'If-None-Match' => '"foo", "bar"' ), array( 'etag' => '"bar"' ), 304 ),
			array( array( 'If-None-Match' => '"foo", "bar"' ), array( 'etag' => '"baz"' ), 200 ),
			array( array( 'If-None-Match' => '"foo"' ), array( 'etag' => 'W/"foo"' ), 304 ),
			array( array( 'If-None-Match' => 'W/"foo"' ), array( 'etag' => '"foo"' ), 304 ),
			array( array( 'If-None-Match' => 'W/"foo"' ), array( 'etag' => 'W/"foo"' ), 304 ),

			// Pointless, but supported
			array( array( 'If-None-Match' => '*' ), array(), 304 ),

			// Basic If-Modified-Since
			array( array( 'If-Modified-Since' => wfTimestamp( TS_RFC2822, $now ) ),
				array( 'last-modified' => wfTimestamp( TS_MW, $now - 1 ) ), 304 ),
			array( array( 'If-Modified-Since' => wfTimestamp( TS_RFC2822, $now ) ),
				array( 'last-modified' => wfTimestamp( TS_MW, $now ) ), 304 ),
			array( array( 'If-Modified-Since' => wfTimestamp( TS_RFC2822, $now ) ),
				array( 'last-modified' => wfTimestamp( TS_MW, $now + 1 ) ), 200 ),

			// If-Modified-Since ignored when If-None-Match is given too
			array( array( 'If-None-Match' => '""', 'If-Modified-Since' => wfTimestamp( TS_RFC2822, $now ) ),
				array( 'etag' => '"x"', 'last-modified' => wfTimestamp( TS_MW, $now - 1 ) ), 200 ),
			array( array( 'If-None-Match' => '""', 'If-Modified-Since' => wfTimestamp( TS_RFC2822, $now ) ),
				array( 'last-modified' => wfTimestamp( TS_MW, $now - 1 ) ), 304 ),

			// Ignored for POST
			array( array( 'If-None-Match' => '"foo", "bar"' ), array( 'etag' => '"bar"' ), 200, true ),
			array( array( 'If-Modified-Since' => wfTimestamp( TS_RFC2822, $now ) ),
				array( 'last-modified' => wfTimestamp( TS_MW, $now - 1 ) ), 200, true ),

			// Other date formats allowed by the RFC
			array( array( 'If-Modified-Since' => gmdate( 'l, d-M-y H:i:s', $now ) . ' GMT' ),
				array( 'last-modified' => wfTimestamp( TS_MW, $now - 1 ) ), 304 ),
			array( array( 'If-Modified-Since' => gmdate( 'D M j H:i:s Y', $now ) ),
				array( 'last-modified' => wfTimestamp( TS_MW, $now - 1 ) ), 304 ),

			// Old browser extension to HTTP/1.0
			array( array( 'If-Modified-Since' => wfTimestamp( TS_RFC2822, $now ) . '; length=123' ),
				array( 'last-modified' => wfTimestamp( TS_MW, $now - 1 ) ), 304 ),

			// Invalid date formats should be ignored
			array( array( 'If-Modified-Since' => gmdate( 'Y-m-d H:i:s', $now ) . ' GMT' ),
				array( 'last-modified' => wfTimestamp( TS_MW, $now - 1 ) ), 200 ),
		);
	}

	/**
	 * Test conditional headers output
	 * @dataProvider provideConditionalRequestHeadersOutput
	 * @param array $conditions Return data for ApiBase::getConditionalRequestData
	 * @param array $headers Expected output headers
	 * @param bool $isError $isError flag
	 * @param bool $post Request is a POST
	 */
	public function testConditionalRequestHeadersOutput( $conditions, $headers, $isError = false, $post = false ) {
		$request = new FauxRequest( array( 'action' => 'query', 'meta' => 'siteinfo' ), $post );
		$response = $request->response();

		$api = new ApiMain( $request );
		$priv = TestingAccessWrapper::newFromObject( $api );
		$priv->mInternalMode = false;

		$module = $this->getMockBuilder( 'ApiBase' )
			->setConstructorArgs( array( $api, 'mock' ) )
			->setMethods( array( 'getConditionalRequestData' ) )
			->getMockForAbstractClass();
		$module->expects( $this->any() )
			->method( 'getConditionalRequestData' )
			->will( $this->returnCallback( function ( $condition ) use ( $conditions ) {
				return isset( $conditions[$condition] ) ? $conditions[$condition] : null;
			} ) );
		$priv->mModule = $module;

		$priv->sendCacheHeaders( $isError );

		foreach ( array( 'Last-Modified', 'ETag' ) as $header ) {
			$this->assertEquals(
				isset( $headers[$header] ) ? $headers[$header] : null,
				$response->getHeader( $header ),
				$header
			);
		}
	}

	public static function provideConditionalRequestHeadersOutput() {
		return array(
			array(
				array(),
				array()
			),
			array(
				array( 'etag' => '"foo"' ),
				array( 'ETag' => '"foo"' )
			),
			array(
				array( 'last-modified' => '20150818000102' ),
				array( 'Last-Modified' => 'Tue, 18 Aug 2015 00:01:02 GMT' )
			),
			array(
				array( 'etag' => '"foo"', 'last-modified' => '20150818000102' ),
				array( 'ETag' => '"foo"', 'Last-Modified' => 'Tue, 18 Aug 2015 00:01:02 GMT' )
			),
			array(
				array( 'etag' => '"foo"', 'last-modified' => '20150818000102' ),
				array(),
				true,
			),
			array(
				array( 'etag' => '"foo"', 'last-modified' => '20150818000102' ),
				array(),
				false,
				true,
			),
		);
	}

}
