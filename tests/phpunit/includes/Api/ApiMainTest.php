<?php

namespace MediaWiki\Tests\Api;

use Generator;
use InvalidArgumentException;
use LogicException;
use MediaWiki\Api\ApiBase;
use MediaWiki\Api\ApiContinuationManager;
use MediaWiki\Api\ApiErrorFormatter;
use MediaWiki\Api\ApiErrorFormatter_BackCompat;
use MediaWiki\Api\ApiMain;
use MediaWiki\Api\ApiRawMessage;
use MediaWiki\Api\ApiUsageException;
use MediaWiki\Config\Config;
use MediaWiki\Config\HashConfig;
use MediaWiki\Config\MultiConfig;
use MediaWiki\Context\RequestContext;
use MediaWiki\Exception\MWExceptionHandler;
use MediaWiki\Exception\ShellDisabledError;
use MediaWiki\Json\FormatJson;
use MediaWiki\Language\RawMessage;
use MediaWiki\Logger\LogCapturingSpi;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Logger\NullSpi;
use MediaWiki\MainConfigNames;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Request\FauxResponse;
use MediaWiki\Request\WebRequest;
use MediaWiki\StubObject\StubGlobalUser;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\User\User;
use RuntimeException;
use StatusValue;
use UnexpectedValueException;
use Wikimedia\Rdbms\DBQueryError;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\ScopedCallback;
use Wikimedia\Stats\UnitTestingHelper;
use Wikimedia\TestingAccessWrapper;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers \MediaWiki\Api\ApiMain
 */
class ApiMainTest extends ApiTestCase {
	use MockAuthorityTrait;

	protected function setUp(): void {
		parent::setUp();
		$this->setGroupPermissions( [
			'*' => [
				'read' => true,
				'edit' => true,
				'apihighlimits' => false,
			],
		] );
	}

	/**
	 * Test that the API will accept a MediaWiki\Request\FauxRequest and execute.
	 */
	public function testApi() {
		$fauxRequest = new FauxRequest( [ 'action' => 'query', 'meta' => 'siteinfo' ] );
		$fauxRequest->setRequestURL( 'https://' );
		$api = new ApiMain(
			$fauxRequest
		);
		$api->execute();
		$data = $api->getResult()->getResultData();
		$this->assertIsArray( $data );
		$this->assertArrayHasKey( 'query', $data );
	}

	public function testApiNoParam() {
		$api = new ApiMain();
		$api->execute();
		$data = $api->getResult()->getResultData();
		$this->assertIsArray( $data );
	}

	/**
	 * ApiMain behaves differently if passed a MediaWiki\Request\FauxRequest (mInternalMode set
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
	 * @return ApiMain
	 */
	private function getNonInternalApiMain( array $requestData, array $headers = [] ) {
		$req = $this->getMockBuilder( WebRequest::class )
			->onlyMethods( [ 'response', 'getRawIP' ] )
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

	public function testSuppressedLogin() {
		// Testing some logic that changes the global $wgUser
		// ApiMain will be setting it to a MediaWiki\StubObject\StubGlobalUser object, it should already
		// be one but in case its a full User object we will wrap the comparisons
		// in MediaWiki\StubObject\StubGlobalUser::getRealUser() which will return the inner User object
		// for a MediaWiki\StubObject\StubGlobalUser, or the actual User object if given a user.

		// phpcs:ignore MediaWiki.Usage.DeprecatedGlobalVariables.Deprecated$wgUser
		global $wgUser;
		$origUser = StubGlobalUser::getRealUser( $wgUser );

		$api = $this->getNonInternalApiMain( [
			'action' => 'query',
			'meta' => 'siteinfo',
			'origin' => '*',
		] );

		ob_start();
		$api->execute();
		ob_end_clean();

		$this->assertNotSame( $origUser, StubGlobalUser::getRealUser( $wgUser ) );
		$this->assertSame( 'true', $api->getContext()->getRequest()->response()
			->getHeader( 'MediaWiki-Login-Suppressed' ) );
	}

	public function testSetContinuationManager() {
		$api = new ApiMain();
		$manager = $this->createMock( ApiContinuationManager::class );
		$api->setContinuationManager( $manager );
		$this->assertTrue( true, 'No exception' );
	}

	public function testSetContinuationManagerTwice() {
		$this->expectException( UnexpectedValueException::class );
		$this->expectExceptionMessage(
			'ApiMain::setContinuationManager: tried to set manager from  ' .
				'when a manager is already set from '
		);

		$api = new ApiMain();
		$manager = $this->createMock( ApiContinuationManager::class );
		$api->setContinuationManager( $manager );
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
		// Fake timestamp for better testability, CI can sometimes take
		// unreasonably long to run the simple test request here.
		ConvertibleTimestamp::setFakeTime( '20190102030405' );

		$req = new FauxRequest( [
			'action' => 'query',
			'meta' => 'siteinfo',
			'curtimestamp' => '',
		] );
		$api = new ApiMain( $req );
		$api->execute();
		$timestamp = $api->getResult()->getResultData()['curtimestamp'];
		$this->assertSame( '2019-01-02T03:04:05Z', $timestamp );
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
		$this->expectApiErrorCodeFromCallback( 'badvalue', static function () {
			$req = new FauxRequest( [ 'action' => 'unknownaction' ] );
			$api = new ApiMain( $req );
			$api->execute();
		} );
	}

	public function testSetupModuleNoTokenProvided() {
		$this->expectApiErrorCodeFromCallback( 'missingparam', static function () {
			$req = new FauxRequest( [
				'action' => 'edit',
				'title' => 'New page',
				'text' => 'Some text',
			] );
			$api = new ApiMain( $req );
			$api->execute();
		} );
	}

	public function testSetupModuleInvalidTokenProvided() {
		$this->expectApiErrorCodeFromCallback( 'badtoken', static function () {
			$req = new FauxRequest( [
				'action' => 'edit',
				'title' => 'New page',
				'text' => 'Some text',
				'token' => "This isn't a real token!",
			] );
			$api = new ApiMain( $req );
			$api->execute();
		} );
	}

	private function newApiMain(
		string $moduleName = 'testmodule',
		array $return = [],
		$asInternal = true
	) {
		$req = new FauxRequest( [ 'action' => $moduleName, 'format' => 'json' ] );
		$req->setRequestURL( 'https://dummy.test/api.php' );

		$api = new ApiMain( $req, true, $asInternal );

		$return += [
			'getModuleName' => $moduleName,
			'execute' => null,
		];

		$mock = $this->createMock( ApiBase::class );

		foreach ( $return as $mth => $ret ) {
			if ( is_callable( $ret ) ) {
				$mock->method( $mth )->willReturnCallback( $ret );
			} else {
				$mock->method( $mth )->willReturn( $ret );
			}
		}

		$api->getModuleManager()->addModule( $moduleName, 'action', [
			'class' => get_class( $mock ),
			'factory' => static function () use ( $mock ) {
				return $mock;
			}
		] );

		return $api;
	}

	public function testSetupModuleNeedsTokenTrue() {
		$this->expectException( LogicException::class );
		$this->expectExceptionMessage(
			"Module 'testmodule' must be updated for the new token handling. " .
				"See documentation for ApiBase::needsToken for details."
		);

		$api = $this->newApiMain( 'testmodule', [ 'needsToken' => true ] );
		$api->execute();
	}

	public function testSetupModuleNeedsTokenNeedntBePosted() {
		$this->expectException( LogicException::class );
		$this->expectExceptionMessage( "Module 'testmodule' must require POST to use tokens." );

		$mock = $this->createMock( ApiBase::class );
		$mock->method( 'getModuleName' )->willReturn( 'testmodule' );
		$mock->method( 'needsToken' )->willReturn( 'csrf' );
		$mock->method( 'mustBePosted' )->willReturn( false );

		$api = new ApiMain( new FauxRequest( [ 'action' => 'testmodule' ] ) );
		$api->getModuleManager()->addModule( 'testmodule', 'action', [
			'class' => get_class( $mock ),
			'factory' => static function () use ( $mock ) {
				return $mock;
			}
		] );
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
			->onlyMethods( [ 'checkMaxLag' ] )
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

		$this->overrideConfigValue( MainConfigNames::CacheEpoch, '20030516000000' );

		$mock = $this->createMock( ApiBase::class );
		$mock->method( 'getModuleName' )->willReturn( 'testmodule' );
		$mock->method( 'getConditionalRequestData' )
			->willReturn( wfTimestamp( TS_MW, $now - 3600 ) );
		$mock->expects( $this->never() )->method( 'execute' );

		$req = new FauxRequest( [
			'action' => 'testmodule',
		] );
		$req->setHeader( 'If-Modified-Since', wfTimestamp( TS_RFC2822, $now - 3600 ) );
		$req->setRequestURL( "http://localhost" );

		$api = new ApiMain( $req );
		$api->getModuleManager()->addModule( 'testmodule', 'action', [
			'class' => get_class( $mock ),
			'factory' => static function () use ( $mock ) {
				return $mock;
			}
		] );

		$wrapper = TestingAccessWrapper::newFromObject( $api );
		$wrapper->mInternalMode = false;

		ob_start();
		$api->execute();
		ob_end_clean();
	}

	private function doTestCheckMaxLag( $lag ) {
		$mockLB = $this->createMock( ILoadBalancer::class );
		$mockLB->method( 'getMaxLag' )->willReturn( [ 'somehost', $lag ] );
		$mockLB->method( 'getConnection' )->willReturn( $this->createMock( IDatabase::class ) );
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
		$this->overrideConfigValue( MainConfigNames::ShowHostnames, false );

		$this->expectApiErrorCodeFromCallback( 'maxlag', function () {
			$this->doTestCheckMaxLag( 4 );
		} );
	}

	public function testCheckMaxLagExceededWithHostNames() {
		$this->overrideConfigValue( MainConfigNames::ShowHostnames, true );

		$this->expectApiErrorCodeFromCallback( 'maxlag', function () {
			$this->doTestCheckMaxLag( 4 );
		} );
	}

	public static function provideAssert() {
		return [
			[ 'anon', 'user', 'assertuserfailed' ],
			[ 'registered', 'user', false ],
			[ 'anon', 'anon', false ],
			[ 'registered', 'anon', 'assertanonfailed' ],
			[ 'registered', 'bot', 'assertbotfailed' ],
			[ [ 'bot' ], 'user', false ],
			[ [ 'bot' ], 'bot', false ],
		];
	}

	/**
	 * Tests the assert={user|bot} functionality
	 *
	 * @dataProvider provideAssert
	 */
	public function testAssert( $performerSpec, $assert, $error ) {
		if ( is_array( $performerSpec ) ) {
			$performer = $this->mockRegisteredAuthorityWithPermissions( $performerSpec );
		} else {
			$performer = $performerSpec === 'registered'
				? $this->mockRegisteredNullAuthority()
				: $this->mockAnonNullAuthority();
		}
		try {
			$this->doApiRequest( [
				'action' => 'query',
				'assert' => $assert,
			], null, null, $performer );
			$this->assertFalse( $error ); // That no error was expected
		} catch ( ApiUsageException $e ) {
			$this->assertApiErrorCode( $error, $e,
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
			$this->assertApiErrorCode( 'assertnameduserfailed', $e );
		}
	}

	/**
	 * Test that 'assert' is processed before module errors
	 */
	public function testAssertBeforeModule() {
		// Check that the query without assert throws too-many-titles
		try {
			$this->doApiRequest( [
				'action' => 'query',
				'titles' => implode( '|', range( 1, ApiBase::LIMIT_SML1 + 1 ) ),
			], null, null, new User );
			$this->fail( 'Expected exception not thrown' );
		} catch ( ApiUsageException $e ) {
			$this->assertApiErrorCode( 'toomanyvalues', $e );
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
			$this->assertApiErrorCode( 'assertuserfailed', $e,
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
	 *   cdn => true CDN is enabled ($wgUseCdn)
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
			$this->overrideConfigValue( MainConfigNames::UseCdn, true );
		}

		// Can't do this in TestSetup.php because Setup.php will override it
		$this->overrideConfigValue( MainConfigNames::CacheEpoch, '20030516000000' );

		$module = $this->getMockBuilder( ApiBase::class )
			->setConstructorArgs( [ $api, 'mock' ] )
			->onlyMethods( [ 'getConditionalRequestData' ] )
			->getMockForAbstractClass();
		$module->method( 'getConditionalRequestData' )
			->willReturnCallback( static function ( $condition ) use ( $conditions ) {
				return $conditions[$condition] ?? null;
			} );

		$ret = $priv->checkConditionalRequestHeaders( $module );

		$this->assertSame( $status, $request->response()->getStatusCode() );
		$this->assertSame( $status === 200, $ret );
	}

	public static function provideCheckConditionalRequestHeaders() {
		global $wgCdnMaxAge;
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

			// Anything before $wgCdnMaxAge seconds ago should be considered
			// expired.
			'If-Modified-Since with CDN post-expiry' =>
				[ [ 'If-Modified-Since' => wfTimestamp( TS_RFC2822, $now - $wgCdnMaxAge * 2 ) ],
					[ 'last-modified' => wfTimestamp( TS_MW, $now - $wgCdnMaxAge * 3 ) ],
					200, [ 'cdn' => true ] ],
			'If-Modified-Since with CDN pre-expiry' =>
				[ [ 'If-Modified-Since' => wfTimestamp( TS_RFC2822, $now - $wgCdnMaxAge / 2 ) ],
					[ 'last-modified' => wfTimestamp( TS_MW, $now - $wgCdnMaxAge * 3 ) ],
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
			->onlyMethods( [ 'getConditionalRequestData' ] )
			->getMockForAbstractClass();
		$module->method( 'getConditionalRequestData' )
			->willReturnCallback( static function ( $condition ) use ( $conditions ) {
				return $conditions[$condition] ?? null;
			} );
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
		$this->setGroupPermissions( '*', 'read', false );

		$this->expectApiErrorCodeFromCallback( 'readapidenied', static function () {
			$main = new ApiMain( new FauxRequest( [ 'action' => 'query', 'meta' => 'siteinfo' ] ) );
			$main->execute();
		} );
	}

	public function testCheckExecutePermissionWriteDisabled() {
		$this->expectApiErrorCodeFromCallback( 'noapiwrite', static function () {
			$main = new ApiMain( new FauxRequest( [
				'action' => 'edit',
				'title' => 'Some page',
				'text' => 'Some text',
				'token' => '+\\',
			] ) );
			$main->execute();
		} );
	}

	public function testCheckExecutePermissionPromiseNonWrite() {
		$this->expectApiErrorCodeFromCallback( 'promised-nonwrite-api', static function () {
			$req = new FauxRequest( [
				'action' => 'edit',
				'title' => 'Some page',
				'text' => 'Some text',
				'token' => '+\\',
			] );
			$req->setHeaders( [ 'Promise-Non-Write-API-Action' => '1' ] );
			$main = new ApiMain( $req, /* enableWrite = */ true );
			$main->execute();
		} );
	}

	public function testCheckExecutePermissionHookAbort() {
		$this->setTemporaryHook( 'ApiCheckCanExecute', static function ( $unused1, $unused2, &$message ) {
			$message = 'mainpage';
			return false;
		} );

		$this->expectApiErrorCodeFromCallback( 'mainpage', static function () {
			$main = new ApiMain( new FauxRequest( [
				'action' => 'edit',
				'title' => 'Some page',
				'text' => 'Some text',
				'token' => '+\\',
			] ), /* enableWrite = */ true );
			$main->execute();
		} );
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
			'RequestHasSameOriginSecurity' => [ static function () {
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
	 */
	public function testExceptionErrors( $errorCallback, $expectReturn, $expectResult ) {
		$this->overrideConfigValues( [
			MainConfigNames::Server => 'https://local.example',
			MainConfigNames::ScriptPath => '/w',
		] );

		$error = $errorCallback( $this );
		if ( isset( $expectResult['trace'] ) ) {
			$expectResult['trace'] = $expectResult['trace']( $error );
		}

		$context = new RequestContext();
		$context->setRequest( new FauxRequest( [ 'errorformat' => 'plaintext' ] ) );
		$context->setLanguage( 'en' );
		$context->setConfig( new MultiConfig( [
			new HashConfig( [
				MainConfigNames::ShowHostnames => true, MainConfigNames::ShowExceptionDetails => true,
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

	public static function provideExceptionErrors() {
		$reqId = WebRequest::getRequestId();
		$doclink = 'https://local.example/w/api.php';

		// The specific exception doesn't matter, as long as it's namespaced.
		$nsex = new ShellDisabledError();

		return [
			[
				static function () {
					return new InvalidArgumentException( 'Random exception' );
				},
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
							'data' => [
								'errorclass' => InvalidArgumentException::class,
							],
						]
					],
					'trace' => static function ( $ex ) {
						return wfMessage( 'api-exception-trace',
							get_class( $ex ),
							$ex->getFile(),
							$ex->getLine(),
							MWExceptionHandler::getRedactedTraceAsString( $ex )
						)->inLanguage( 'en' )->useDatabase( false )->text();
					},
					'servedby' => wfHostname(),
				]
			],
			[
				static function ( $testCase ) {
					return new DBQueryError(
						$testCase->createMock( IDatabase::class ),
						'error', 1234, 'SELECT 1', 'provideExceptionErrors'
					);
				},
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
							'data' => [
								'errorclass' => DBQueryError::class,
							],
						]
					],
					'trace' => static function ( $dbex ) {
						return wfMessage( 'api-exception-trace',
							get_class( $dbex ),
							$dbex->getFile(),
							$dbex->getLine(),
							MWExceptionHandler::getRedactedTraceAsString( $dbex )
						)->inLanguage( 'en' )->useDatabase( false )->text();
					},
					'servedby' => wfHostname(),
				]
			],
			[
				static function () use ( $nsex ) {
					return $nsex;
				},
				[ 'existing-error', 'internal_api_error_MediaWiki\Exception\ShellDisabledError' ],
				[
					'warnings' => [
						[ 'code' => 'existing-warning', 'text' => 'existing warning', 'module' => 'main' ],
					],
					'errors' => [
						[ 'code' => 'existing-error', 'text' => 'existing error', 'module' => 'main' ],
						[
							'code' => 'internal_api_error_MediaWiki\Exception\ShellDisabledError',
							'text' => "[$reqId] Exception caught: " . $nsex->getMessage(),
							'data' => [
								'errorclass' => ShellDisabledError::class,
							],
						]
					],
					'trace' => static function ( $nsex ) {
						return wfMessage( 'api-exception-trace',
							get_class( $nsex ),
							$nsex->getFile(),
							$nsex->getLine(),
							MWExceptionHandler::getRedactedTraceAsString( $nsex )
						)->inLanguage( 'en' )->useDatabase( false )->text();
					},
					'servedby' => wfHostname(),
				]
			],
			[
				static function () {
					$apiEx1 = new ApiUsageException( null,
						StatusValue::newFatal( new ApiRawMessage( 'An error', 'sv-error1' ) ) );
					TestingAccessWrapper::newFromObject( $apiEx1 )->modulePath = 'foo+bar';
					$apiEx1->getStatusValue()->warning( new ApiRawMessage( 'A warning', 'sv-warn1' ) );
					$apiEx1->getStatusValue()->warning( new ApiRawMessage( 'Another warning', 'sv-warn2' ) );
					$apiEx1->getStatusValue()->fatal( new ApiRawMessage( 'Another error', 'sv-error2' ) );
					return $apiEx1;
				},
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
						"list at &lt;https://lists.wikimedia.org/postorius/lists/mediawiki-api-announce.lists.wikimedia.org/&gt; " .
						"for notice of API deprecations and breaking changes.",
					'servedby' => wfHostname(),
				]
			],
			[
				static function ( $testCase ) {
					$badMsg = $testCase->getMockBuilder( ApiRawMessage::class )
						->setConstructorArgs( [ 'An error', 'ignored' ] )
						->onlyMethods( [ 'getApiCode' ] )
						->getMock();
					$badMsg->method( 'getApiCode' )->willReturn( "bad\nvalue" );
					$apiEx2 = new ApiUsageException( null, StatusValue::newFatal( $badMsg ) );
					return $apiEx2;
				},
				[ 'existing-error', '<invalid-code>' ],
				[
					'warnings' => [
						[ 'code' => 'existing-warning', 'text' => 'existing warning', 'module' => 'main' ],
					],
					'errors' => [
						[ 'code' => 'existing-error', 'text' => 'existing error', 'module' => 'main' ],
						[ 'code' => "bad\nvalue", 'text' => 'An error' ],
					],
					'docref' => "See $doclink for API usage. Subscribe to the mediawiki-api-announce mailing " .
						"list at &lt;https://lists.wikimedia.org/postorius/lists/mediawiki-api-announce.lists.wikimedia.org/&gt; " .
						"for notice of API deprecations and breaking changes.",
					'servedby' => wfHostname(),
				]
			]
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
		$this->assertIsArray( $data );
		$this->assertArrayHasKey( 'error', $data );
		$this->assertArrayHasKey( 'code', $data['error'] );
		$this->assertSame( 'badvalue', $data['error']['code'] );
	}

	public function testMatchRequestedHeaders() {
		$api = TestingAccessWrapper::newFromClass( ApiMain::class );
		$allowedHeaders = [ 'Accept', 'Origin', 'User-Agent' ];

		$this->assertTrue( $api->matchRequestedHeaders( 'Accept', $allowedHeaders ) );
		$this->assertTrue( $api->matchRequestedHeaders( 'Accept,Origin', $allowedHeaders ) );
		$this->assertTrue( $api->matchRequestedHeaders( 'accEpt, oRIGIN', $allowedHeaders ) );
		$this->assertFalse( $api->matchRequestedHeaders( 'Accept,Foo', $allowedHeaders ) );
		$this->assertFalse( $api->matchRequestedHeaders( 'Accept, fOO', $allowedHeaders ) );
	}

	/**
	 * Common test code for tests that cover ApiMain::sendCacheHeaders.
	 *
	 * @param TestingAccessWrapper $api An ApiMain instance, wrapped in a TestingAccessWrapper.
	 * @param FauxRequest $req
	 * @param bool $isError
	 * @param string $cacheMode
	 * @param string|null $expectedVary
	 * @param string $expectedCacheControl
	 */
	private function commonTestCacheHeaders(
		TestingAccessWrapper $api,
		FauxRequest $req,
		bool $isError,
		string $cacheMode,
		?string $expectedVary,
		string $expectedCacheControl
	) {
		$api->setCacheMode( $cacheMode );
		$this->assertSame( $cacheMode, $api->mCacheMode, 'Cache mode precondition' );
		$api->sendCacheHeaders( $isError );

		$this->assertSame( $expectedVary, $req->response()->getHeader( 'Vary' ), 'Vary' );
		$this->assertSame( $expectedCacheControl, $req->response()->getHeader( 'Cache-Control' ), 'Cache-Control' );
	}

	/**
	 * @param string $cacheMode
	 * @param string|null $expectedVary
	 * @param string $expectedCacheControl
	 * @param array $requestData
	 * @param Config|null $config
	 * @dataProvider provideCacheHeaders
	 */
	public function testCacheHeaders(
		string $cacheMode,
		?string $expectedVary,
		string $expectedCacheControl,
		array $requestData = [],
		?Config $config = null
	) {
		$req = new FauxRequest( $requestData );
		$ctx = new RequestContext();
		$ctx->setRequest( $req );
		if ( $config ) {
			$ctx->setConfig( $config );
		}
		/** @var ApiMain|TestingAccessWrapper $api */
		$api = TestingAccessWrapper::newFromObject( new ApiMain( $ctx ) );

		$this->commonTestCacheHeaders( $api, $req, false, $cacheMode, $expectedVary, $expectedCacheControl );
	}

	public static function provideCacheHeaders(): Generator {
		yield 'Private' => [ 'private', null, 'private, must-revalidate, max-age=0' ];
		yield 'Public' => [
			'public',
			'Accept-Encoding, Treat-as-Untrusted, Cookie',
			'private, must-revalidate, max-age=0',
			[ 'uselang' => 'en' ]
		];
		yield 'Anon public, user private' => [
			'anon-public-user-private',
			'Accept-Encoding, Treat-as-Untrusted, Cookie',
			'private, must-revalidate, max-age=0'
		];
	}

	/** @dataProvider provideCacheHeaders */
	public function testCacheHeadersOnIsErrorAsTrue(
		string $cacheMode,
		?string $expectedVary,
		string $expectedCacheControl,
		array $requestData = []
	) {
		$req = new FauxRequest( $requestData );
		$ctx = new RequestContext();
		$ctx->setRequest( $req );
		/** @var ApiMain|TestingAccessWrapper $api */
		$api = TestingAccessWrapper::newFromObject( new ApiMain( $ctx ) );

		// Create a mock ApiBase object that throws an ApiUsageException from ::isWriteMode. This will be used as the
		// mModule property of $api, and will test that ::isWriteMode is either never called or properly wrapped in
		// a try block in the method we are testing (T363133).
		$module = $this->getMockBuilder( ApiBase::class )
			->setConstructorArgs( [ $api->object, 'mock' ] )
			->onlyMethods( [ 'isWriteMode' ] )
			->getMockForAbstractClass();
		$module->method( 'isWriteMode' )
			->willThrowException( new ApiUsageException( $module, StatusValue::newFatal( 'test' ) ) );
		$api->mModule = $module;

		// This will test that ::isWriteMode will not be called if $isError is true.
		$this->commonTestCacheHeaders( $api, $req, true, $cacheMode, $expectedVary, $expectedCacheControl );
	}

	public function testTimingStats() {
		$helper = new UnitTestingHelper();
		$this->setService( 'StatsFactory', $helper->getStatsFactory() );

		$api = $this->newApiMain( 'test', [], false );

		// Since we are calling execute() with internal mode turned off,
		// we need to capture and discard the HTML that will be written to
		// the output buffer.
		ob_start();
		$scope = new ScopedCallback( 'ob_end_clean' );

		$api->execute();

		$stats = $helper->consumeAllFormatted();
		$this->assertArrayContainsSubstring( 'api_executeTiming_seconds', $stats );
		$this->assertArrayContainsSubstring( 'module:test', $stats );
	}

	public function provideErrorReporting() {
		yield 'RuntimeException: server_error' => [
			static function ( ApiBase $base ) {
				throw new RuntimeException();
			},
			[
				'error' => [ 'code' => 'internal_api_error_RuntimeException' ]
			],
			[
				'mediawiki.api_errors',
				'exception_cause:server_error',
				'error_code:internal_api_error_RuntimeException',
				'module:test',
			],
			[
				'RuntimeException'
			]
		];
		yield 'ApiUsageException: client_error' => [
			static function ( ApiBase $base ) {
				$ex = new ApiUsageException(
					$base,
					StatusValue::newFatal( new ApiRawMessage( 'An error', 'error1' ) )
				);

				$ex->getStatusValue()->fatal( new ApiRawMessage( 'Another error', 'error2' ) );
				throw $ex;
			},
			[
				'error' => [ 'code' => 'error1' ]
			],
			[
				'mediawiki.api_errors',
				'exception_cause:client_error',
				'error_code:error1_error2',
				'module:test',
			],
			[]
		];
	}

	/**
	 * @dataProvider provideErrorReporting
	 */
	public function testErrorReporting(
		callable $execute,
		array $expectedResponse,
		array $expectedStats,
		array $expectedLogs
	) {
		$helper = new UnitTestingHelper();
		$this->setService( 'StatsFactory', $helper->getStatsFactory() );

		// inject the ApiMain instance into the callback
		$api = null;
		$curriedExec = static function () use ( $execute, &$api ) {
			$execute( $api );
		};

		$api = $this->newApiMain( 'test', [ 'execute' => $curriedExec ], false );

		// Since we are calling execute() with internal mode turned off,
		// we need to capture and discard the HTML that will be written to
		// the output buffer. We also need to disable error logging
		// and the restore it for later.
		$oldLoggerSpi = LoggerFactory::getProvider();
		$scope = new ScopedCallback( static function () use ( $oldLoggerSpi ) {
			ob_end_clean();
			LoggerFactory::registerProvider( $oldLoggerSpi );
		} );

		$logCapture = new LogCapturingSpi( new NullSpi() );
		LoggerFactory::registerProvider( $logCapture );

		// Since we turned off "internal" mode, we have to capture stdout.
		ob_start();

		// Now execute the API module that will fail
		$api->execute();

		$errorJson = json_decode( ob_get_clean(), true );
		$this->assertNotFalse( $errorJson, 'Response should be valid JSON' );

		foreach ( $expectedResponse as $key => $expected ) {
			$this->assertArrayHasKey( $key, $errorJson, 'Response should contain key' );
			$actual = $errorJson[$key];
			$actual = array_intersect_key( $actual, $expected );

			$this->assertSame(
				$expected,
				$actual,
				"Response key: $key"
			);
		}

		$stats = $helper->consumeAllFormatted();

		foreach ( $expectedStats as $substring ) {
			$this->assertArrayContainsSubstring( $substring, $stats );
		}

		$logs = array_map(
			static fn ( $logEntry ) => $logEntry['message'],
			array_filter(
				$logCapture->getLogs(),
				static fn ( $logEntry ) => $logEntry['level'] === 'error'
			)
		);

		foreach ( $expectedLogs as $substring ) {
			$this->assertArrayContainsSubstring( $substring, $logs );
		}
	}

	private function assertArrayContainsSubstring( string $substring, array $array, $message = '' ): void {
		if ( $message ) {
			$message = "$message\n";
		}

		$this->assertTrue(
			self::arrayContainsSubstring( $substring, $array ),
			"{$message}Array should contain $substring:\n\t" . implode( "\n\t", $array )
		);
	}

	private static function arrayContainsSubstring( string $substring, array $array ): bool {
		foreach ( $array as $value ) {
			if ( strpos( $value, $substring ) !== false ) {
				return true;
			}
		}

		return false;
	}

}
