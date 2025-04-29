<?php

use MediaWiki\Context\RequestContext;
use MediaWiki\FileRepo\File\UnregisteredLocalFile;
use MediaWiki\FileRepo\ThumbnailEntryPoint;
use MediaWiki\MainConfigNames;
use MediaWiki\Permissions\SimpleAuthority;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Tests\FileRepo\TestRepoTrait;
use MediaWiki\Tests\MockEnvironment;
use MediaWiki\Title\Title;
use MediaWiki\User\UserIdentityValue;

/**
 * @covers \MediaWiki\FileRepo\ThumbnailEntryPoint
 * @group Database
 */
class ThumbnailEntryPointTest extends MediaWikiIntegrationTestCase {

	use TestRepoTrait;
	use MockHttpTrait;

	private const PNG_MAGIC = "\x89\x50\x4e\x47";
	private const JPEG_MAGIC = "\xff\xd8\xff\xe0";

	private const IMAGES_DIR = __DIR__ . '/../../data/media';

	/** @var int Counter for getting unique width values */
	private static $uniqueWidth = 20;

	private ?MockEnvironment $environment = null;

	/**
	 * will be called only once per test class
	 */
	public function addDBDataOnce() {
		// Set a named user account for the request context as the default,
		// so that these tests do not fail with temp accounts enabled
		RequestContext::getMain()->setUser( $this->getTestUser()->getUser() );
		// Create mock repo with test files
		$this->initTestRepoGroup();

		$this->importFileToTestRepo( self::IMAGES_DIR . '/greyscale-png.png', 'Test.png' );
		$this->importFileToTestRepo( self::IMAGES_DIR . '/test.jpg', 'Icon.jpg' );

		// Create a second version of Test.png and Icon.jpg
		$this->importFileToTestRepo( self::IMAGES_DIR . '/greyscale-dot-na-png.png', 'Test.png' );
		$this->importFileToTestRepo( self::IMAGES_DIR . '/portrait-rotated.jpg', 'Icon.jpg' );

		// Create a redirect
		$title = Title::makeTitle( NS_FILE, 'Redirect_to_Test.png' );
		$this->editPage( $title, '#REDIRECT [[File:Test.png]]' );

		// Suppress the old version of Icon
		$file = $this->getTestRepo()->newFile( 'Icon.jpg' );
		$history = $file->getHistory();
		$oldFile = $history[0];

		$this->getDb()->newUpdateQueryBuilder()
			->table( 'oldimage' )
			->set( [ 'oi_deleted' => 1 ] )
			->where( [ 'oi_archive_name' => $oldFile->getArchiveName() ] )
			->caller( __METHOD__ )
			->execute();
	}

	public static function tearDownAfterClass(): void {
		self::destroyTestRepo();
		parent::tearDownAfterClass();
	}

	public function setUp(): void {
		parent::setUp();

		$this->overrideConfigValue( MainConfigNames::ThumbLimits, [ 16, 24 ] );
		$this->installTestRepoGroup();
	}

	private function recordHeader( string $header ) {
		$this->environment->getFauxResponse()->header( $header );
	}

	/**
	 * @param FauxRequest|string|array|null $request
	 *
	 * @return MockEnvironment
	 */
	private function makeEnvironment( $request ): MockEnvironment {
		if ( !$request ) {
			$request = new FauxRequest();
		}

		if ( is_string( $request ) ) {
			$request = [ 'f' => $request, 'width' => self::$uniqueWidth++ ];
		}

		if ( is_array( $request ) ) {
			$request = new FauxRequest( $request );
			$request->setRequestURL( '/w/img.php' );
		}

		$this->environment = new MockEnvironment( $request );
		return $this->environment;
	}

	/**
	 * @param MockEnvironment|null $environment
	 * @param FauxRequest|RequestContext|string|array|null $request
	 *
	 * @return ThumbnailEntryPoint
	 */
	private function getEntryPoint(
		?MockEnvironment $environment = null,
		$request = null
	) {
		if ( !$request && $environment ) {
			$request = $environment->getFauxRequest();
		}

		if ( $request instanceof RequestContext ) {
			$context = $request;
			$request = $context->getRequest();
		} else {
			$context = new RequestContext();
			$context->setRequest( $request );
			$context->setUser( $this->getTestUser()->getUser() );
		}

		if ( !$environment ) {
			$environment = $this->makeEnvironment( $request );
		}

		$context->setLanguage( 'qqx' );

		$entryPoint = new ThumbnailEntryPoint(
			$context,
			$environment,
			$this->getServiceContainer()
		);

		$entryPoint->enableOutputCapture();
		return $entryPoint;
	}

	public function testNotFound() {
		$env = $this->makeEnvironment( 'Missing_puppy.jpeg' );
		$entryPoint = $this->getEntryPoint( $env );

		$entryPoint->run();

		$env->assertStatusCode( 404 );
		$env->assertHeaderValue(
			'text/html; charset=utf-8',
			'Content-Type'
		);

		$output = $entryPoint->getCapturedOutput();
		$this->assertStringContainsString(
			'<title>Error generating thumbnail</title>',
			$output
		);
	}

	public function testGenerateAndStreamThumbnail() {
		$env = $this->makeEnvironment(
			[
				'f' => 'Test.png',
				'width' => 12 // Must match the width in testStreamExistingThumbnail
			]
		);

		$entryPoint = $this->getEntryPoint( $env );

		$entryPoint->run();
		$output = $entryPoint->getCapturedOutput();

		$response = $env->getFauxResponse();
		$this->assertSame( 'image/png', $response->getHeader( 'Content-Type' ) );
		$this->assertGreaterThan( 400, (int)$response->getHeader( 'Content-Length' ) );

		$env->assertStatusCode( 200, $output );

		$this->assertThumbnail(
			[ 'magic' => self::PNG_MAGIC, 'width' => 12, ],
			$output
		);

		return [ 'data' => $output, 'width' => 12 ];
	}

	/**
	 * @depends testGenerateAndStreamThumbnail
	 */
	public function testStreamExistingThumbnail() {
		// Sabotage transformations, so this test will fail if we do not
		// use the existing thumbnail generated by testGenerateAndStreamThumbnail.
		$handler = $this->getMockBuilder( BitmapHandler::class )
			->onlyMethods( [ 'doTransform' ] )
			->getMock();

		$handler->expects( $this->never() )->method( 'doTransform' );

		$factory = $this->createNoOpMock( MediaHandlerFactory::class, [ 'getHandler' ] );
		$factory->method( 'getHandler' )->willReturn( $handler );
		$this->setService( 'MediaHandlerFactory', $factory );

		$env = $this->makeEnvironment(
			[
				'f' => 'Test.png',
				'width' => 12 // Must match the width in testGenerateAndStreamThumbnail
			]
		);
		$entryPoint = $this->getEntryPoint( $env );

		$entryPoint->run();
		$output = $entryPoint->getCapturedOutput();

		$env->assertStatusCode( 200 );

		$this->assertThumbnail(
			[ 'magic' => self::PNG_MAGIC, 'width' => 12, ],
			$output
		);
	}

	public function testNoThumbName() {
		// Make sure no handler is set, so that File::generateThumbName() returns null
		$factory = $this->createNoOpMock( MediaHandlerFactory::class, [ 'getHandler' ] );
		$factory->method( 'getHandler' )->willReturn( false );
		$this->setService( 'MediaHandlerFactory', $factory );

		$env = $this->makeEnvironment(
			[
				'f' => 'Test.png',
				'width' => self::$uniqueWidth++
			]
		);
		$entryPoint = $this->getEntryPoint( $env );

		$entryPoint->run();
		$output = $entryPoint->getCapturedOutput();

		$env->assertStatusCode( 400, $output );
	}

	/** Verify that the exception from ImageHandler:makeParamString is handled */
	public function testNoWidth() {
		$env = $this->makeEnvironment(
			[
				'f' => 'Test.png',
				// no width
			]
		);
		$entryPoint = $this->getEntryPoint( $env );

		$entryPoint->run();
		$output = $entryPoint->getCapturedOutput();

		$env->assertStatusCode( 400, $output );
	}

	/** Verify that the exception from ImageHandler:makeParamString is handled in redirect case - T387684 */
	public function testNoWidthRedirect() {
		$env = $this->makeEnvironment(
			[
				'f' => 'Redirect_to_Test.png',
				// no width
			]
		);
		$entryPoint = $this->getEntryPoint( $env );

		$entryPoint->run();
		$output = $entryPoint->getCapturedOutput();

		$env->assertStatusCode( 400, $output );
	}

	public static function provideTransformError() {
		yield 'MediaTransformError' => [
			new MediaTransformError( 'testing', 200, 100 ),
			500
		];

		yield 'thumbnail_image-failure-limit' => [
			new MediaTransformError( 'thumbnail_image-failure-limit', 200, 100 ),
			429
		];

		yield 'no thumb' => [
			false,
			500
		];

		yield 'no file path' => [
			new ThumbnailImage(
				new UnregisteredLocalFile( false, false, 'dummy' ),
				'',
				false,
				[ 'width' => 1, 'height' => 1 ]
			),
			500
		];
	}

	/**
	 * @dataProvider provideTransformError
	 */
	public function testTransformError( $transformOutput, $expectedCode ) {
		// Mock transformations to return an error
		$handler = $this->getMockBuilder( BitmapHandler::class )
			->onlyMethods( [ 'doTransform' ] )
			->getMock();

		$handler->method( 'doTransform' )->willReturn( $transformOutput );

		$factory = $this->createNoOpMock( MediaHandlerFactory::class, [ 'getHandler' ] );
		$factory->method( 'getHandler' )->willReturn( $handler );
		$this->setService( 'MediaHandlerFactory', $factory );

		$env = $this->makeEnvironment(
			[
				'f' => 'Test.png',
				'width' => self::$uniqueWidth++
			]
		);
		$entryPoint = $this->getEntryPoint( $env );

		$entryPoint->run();
		$output = $entryPoint->getCapturedOutput();

		$env->assertStatusCode( $expectedCode, $output );
	}

	public function testContentDisposition() {
		$env = $this->makeEnvironment(
			[
				'f' => 'Test.png',
				'width' => 12,
				'download' => 1
			]
		);

		$entryPoint = $this->getEntryPoint( $env );
		$entryPoint->run();
		$output = $entryPoint->getCapturedOutput();

		$env->assertStatusCode( 200 );
		$this->assertThumbnail( [ 'magic' => self::PNG_MAGIC, ], $output );

		$env->assertHeaderValue(
			'attachment;filename*=UTF-8\'\'Test.png',
			'Content-Disposition'
		);
	}

	public static function provideThumbNameParam() {
		yield [ '12px-Test.png' ];
		yield [ 'page123456-12px-xyz' ];
		yield [ '12px-xyz' ];
		yield [ 'xyzzy', 400 ];
	}

	/**
	 * @dataProvider provideThumbNameParam
	 */
	public function testThumbNameParam( $thumbName, $expected = 200 ) {
		$env = $this->makeEnvironment(
			[
				'f' => 'Test.png',
				'thumbName' => $thumbName,
			]
		);

		$entryPoint = $this->getEntryPoint( $env );

		$entryPoint->run();
		$output = $entryPoint->getCapturedOutput();

		$env->assertStatusCode( $expected, $output );

		if ( $expected < 300 ) {
			$expectedProps = [ 'magic' => self::PNG_MAGIC ];

			// get expected width
			if ( preg_match( '/\b(\d+)px/', $thumbName, $matches ) ) {
				$expectedProps['width'] = (int)$matches[1];
			}

			$this->assertThumbnail(
				$expectedProps,
				$output
			);
		}
	}

	public function testAccessDenied() {
		// Make the wiki non-public
		$this->setGroupPermissions( '*', 'read', false );

		// Make the user have no rights
		$authority = new SimpleAuthority(
			new UserIdentityValue( 7, 'Heather' ),
			[]
		);

		$env = $this->makeEnvironment( 'Test.png' );

		$context = $env->makeFauxContext();
		$context->setAuthority( $authority );

		$entryPoint = $this->getEntryPoint(
			$env,
			$context
		);

		$entryPoint->run();

		$env->assertStatusCode( 403 );
		$env->assertHeaderValue(
			'text/html; charset=utf-8',
			'Content-Type'
		);

		$output = $entryPoint->getCapturedOutput();
		$this->assertStringContainsString(
			'<title>Error generating thumbnail</title>',
			$output
		);
	}

	public function testAccessOnPrivateWiki() {
		// Make the wiki non-public, so we don't use the short-circuit code
		$this->setGroupPermissions( '*', 'read', false );

		// Make a user who is allowed to read
		$authority = new SimpleAuthority(
			new UserIdentityValue( 7, 'Heather' ),
			[ 'read', 'renderfile', 'renderfile-nonstandard' ]
		);

		$env = $this->makeEnvironment( 'Test.png' );

		$context = $env->makeFauxContext();
		$context->setAuthority( $authority );

		$entryPoint = $this->getEntryPoint(
			$env,
			$context
		);

		$entryPoint->run();
		$output = $entryPoint->getCapturedOutput();

		$env->assertStatusCode( 200 );
		$this->assertThumbnail( [ 'magic' => self::PNG_MAGIC, ], $output );
	}

	public static function provideRateLimit() {
		// NOTE: The 12px thumbnail will have been generated at this point.
		//       We force 16 and 24 to be standard sizes during setup.
		//       Once the thumbnail is generated, the rate limit is no longer
		//       triggered.
		yield [ '16', '24', 'renderfile' ];
		yield [ self::$uniqueWidth++, self::$uniqueWidth++, 'renderfile-nonstandard' ];
	}

	/**
	 * @dataProvider provideRateLimit
	 */
	public function testRateLimited( $width1, $width2, $limit ) {
		// Set up rate limit config
		$rateLimits = $this->getConfVar( MainConfigNames::RateLimits );
		$rateLimits[$limit] = [
			'ip' => [ 1, 60 ],
			'newbie' => [ 1, 60 ],
			'user' => [ 1, 60 ],
		];

		$this->overrideConfigValue( MainConfigNames::RateLimits, $rateLimits );

		// First run should pass
		$env = $this->makeEnvironment( [ 'f' => 'Test.png', 'width' => $width1 ] );
		$entryPoint = $this->getEntryPoint( $env );

		$entryPoint->run();
		$entryPoint->getCapturedOutput();
		$env->assertStatusCode( 200 );

		// Second run should fail
		$env = $this->makeEnvironment( [ 'f' => 'Test.png', 'width' => $width2 ] );
		$entryPoint = $this->getEntryPoint( $env );

		$entryPoint->run();
		$entryPoint->getCapturedOutput();
		$env->assertStatusCode( 429 );

		$env->assertHeaderValue(
			'text/html; charset=utf-8',
			'Content-Type'
		);
	}

	/**
	 * @depends testGenerateAndStreamThumbnail
	 */
	public function testStreamOldFile( array $latestThumbnailInfo ) {
		$file = $this->getTestRepo()->newFile( 'Test.png' );
		$history = $file->getHistory();
		$oldFile = $history[0];

		$env = $this->makeEnvironment(
			[
				'f' => $oldFile->getArchiveName(),
				'width' => '12px', // use "px" suffix, just so we also cover that code path
				'archived' => 1,
			]
		);
		$entryPoint = $this->getEntryPoint( $env );

		$entryPoint->run();
		$output = $entryPoint->getCapturedOutput();
		$env->assertStatusCode( 200 );

		$this->assertNotSame(
			$latestThumbnailInfo['data'],
			$output,
			'Thumbnail for the old version should not be the same as the ' .
				'thumbnail for the latest version'
		);

		$this->assertThumbnail(
			[ 'magic' => self::PNG_MAGIC, 'width' => 12, ],
			$output
		);
	}

	public function testOldDeletedFile() {
		// Note that we manually set oi_deleted for this revision
		// in addDBDataOnce().
		$file = $this->getTestRepo()->newFile( 'Icon.jpg' );
		$history = $file->getHistory();
		$oldFile = $history[0];

		$env = $this->makeEnvironment(
			[
				'f' => $oldFile->getArchiveName(),
				'width' => '12px', // use "px" suffix, just so we also cover that code path
				'archived' => 1,
			]
		);
		$entryPoint = $this->getEntryPoint( $env );

		$entryPoint->run();
		$output = $entryPoint->getCapturedOutput();
		$env->assertStatusCode( 404, $output );
	}

	/**
	 * @depends testGenerateAndStreamThumbnail
	 */
	public function testStreamOldFileRedirect( array $latestThumbnailInfo ) {
		$file = $this->getTestRepo()->newFile( 'Test.png' );
		$history = $file->getHistory();
		$oldFile = $history[0];

		// Try accessing the old revision using a redirected title
		$archiveName = str_replace(
			'Test.png',
			'Redirect_to_Test.png',
			$oldFile->getArchiveName()
		);

		$env = $this->makeEnvironment(
			[
				'f' => $archiveName,
				'width' => 12,
				'archived' => 1,
			]
		);
		$entryPoint = $this->getEntryPoint( $env );

		$entryPoint->run();
		$output = $entryPoint->getCapturedOutput();

		$response = $env->getFauxResponse();

		$this->assertSame( 302, $response->getStatusCode() );

		$expected = '/' . urlencode( $oldFile->getArchiveName() ) . '/12px-Test.png';
		$this->assertStringEndsWith(
			$expected,
			$response->getHeader( 'Location' )
		);

		$this->assertSame( '', $output );
	}

	public function testStreamTempFile() {
		$user = $this->getTestUser()->getUser();
		$stash = new UploadStash( $this->getTestRepo(), $user );
		$file = $stash->stashFile( self::IMAGES_DIR . '/adobergb.jpg' );

		$env = $this->makeEnvironment(
			[
				'f' => $file->getName(),
				'width' => 12,
				'temp' => 'yes',
			]
		);
		$entryPoint = $this->getEntryPoint( $env );

		$entryPoint->run();
		$output = $entryPoint->getCapturedOutput();

		$env->assertStatusCode( 200 );
		$this->assertThumbnail(
			[ 'magic' => self::JPEG_MAGIC, 'width' => 12, ],
			$output
		);
	}

	public function testRedirect() {
		$this->overrideConfigValue( MainConfigNames::VaryOnXFP, true );

		$env = $this->makeEnvironment(
			[
				'f' => 'Redirect_to_Test.png',
				'w' => 12
			]
		);
		$entryPoint = $this->getEntryPoint( $env );

		$entryPoint->run();
		$output = $entryPoint->getCapturedOutput();

		$response = $env->getFauxResponse();

		$this->assertSame( 302, $response->getStatusCode() );
		$this->assertStringEndsWith(
			'/Test.png/12px-Test.png',
			$response->getHeader( 'Location' )
		);

		$this->assertSame( '', $output );
		$env->assertHeaderValue( 'X-Forwarded-Proto', 'Vary' );
	}

	public function testBadTitle() {
		$env = $this->makeEnvironment( '_/_' );
		$entryPoint = $this->getEntryPoint( $env );

		$entryPoint->run();
		$output = $entryPoint->getCapturedOutput();

		$env->assertStatusCode( 404 );
		$env->assertHeaderValue(
			'text/html; charset=utf-8',
			'Content-Type'
		);

		$this->assertStringContainsString(
			'(badtitletext)',
			$output
		);
	}

	public static function provideOldFileWithBadTitle() {
		yield 'invalid title' => [ '_/_' ];
		yield 'valid title without timestamp' => [ 'Test.png' ];
		yield 'invalid title with timestamp' => [ '20200101002233!_/_' ];
	}

	/**
	 * @dataProvider provideOldFileWithBadTitle
	 */
	public function testOldFileWithBadTitle( $badTitle ) {
		$env = $this->makeEnvironment( [
			'f' => $badTitle,
			'width' => 12,
			'archived' => 1
		] );
		$entryPoint = $this->getEntryPoint( $env );

		$entryPoint->run();
		$output = $entryPoint->getCapturedOutput();

		$env->assertStatusCode( 404 );
		$env->assertHeaderValue(
			'text/html; charset=utf-8',
			'Content-Type'
		);

		$this->assertStringContainsString(
			'(badtitletext)',
			$output
		);
	}

	public function testTooMuchWidth() {
		// Set the width larger than the size of the image
		$env = $this->makeEnvironment( [ 'f' => 'Test.png', 'width' => 1200 ] );

		$entryPoint = $this->getEntryPoint( $env );

		$entryPoint->run();
		$output = $entryPoint->getCapturedOutput();

		$env->assertStatusCode( 400 );
		$env->assertHeaderValue(
			'text/html; charset=utf-8',
			'Content-Type'
		);

		$this->assertStringContainsString(
			'(thumbnail_error: ',
			$output
		);
		$this->assertStringContainsString(
			'bigger than the source',
			$output
		);
	}

	public function testDeletedFile() {
		// Delete Icon.jpg
		$icon = $this->getTestRepo()->newFile( 'Icon.jpg' );

		$this->assertTrue( $icon->exists() );// sanity
		$icon->deleteFile( 'testing', new UserIdentityValue( 0, 'Test' ) );

		$env = $this->makeEnvironment( 'Icon.jpg' );
		$entryPoint = $this->getEntryPoint( $env );

		$entryPoint->run();
		$output = $entryPoint->getCapturedOutput();

		$env->assertStatusCode( 404 );
		$env->assertHeaderValue(
			'text/html; charset=utf-8',
			'Content-Type'
		);

		$this->assertStringContainsString(
			'<title>Error generating thumbnail</title>',
			$output
		);
	}

	public function testNotModified() {
		$env = $this->makeEnvironment(
			[
				'f' => 'Test.png',
				'width' => 12
			]
		);

		$env->setServerInfo( 'HTTP_IF_MODIFIED_SINCE', '25250101001122' );

		$entryPoint = $this->getEntryPoint( $env );
		$entryPoint->run();
		$output = $entryPoint->getCapturedOutput();

		$response = $env->getFauxResponse();
		$this->assertSame( 304, $response->getStatusCode() );
		$this->assertSame( '', $output );
	}

	public function testProxy() {
		$this->installTestRepoGroup( [ 'thumbProxyUrl' => 'https://images.acme.test/thumbnails/' ] );
		$this->installMockHttp( 'PROXY RESPONSE' );

		$env = $this->makeEnvironment(
			[
				'f' => 'Test.png',
				'width' => self::$uniqueWidth++
			]
		);
		$entryPoint = $this->getEntryPoint( $env );

		$entryPoint->run();
		$output = $entryPoint->getCapturedOutput();

		$this->assertSame( 'PROXY RESPONSE', $output );
	}

	public static function provideRepoCouldNotStreamFile() {
		// TODO: figure out how to provoke an error in
		// MediaTransformOutput::streamFileWithStatus.
		// The below causes an error to be triggered too early.
		// Since MediaTransformOutput uses StreamFile directly, we have to also
		// sabotage transformations in the handler to return a ThumbnailImage
		// with no path. This is unfortunately brittle to implementation changes.

		// The width must match the one generated by testGenerateAndStreamThumbnail
		/*yield 'existing thumbnail' => [
			12,
			'Could not stream the file',
		];*/

		// The specific error message may change as the code evolves
		yield 'non-existing thumbnail' => [
			self::$uniqueWidth++,
			'No path supplied in thumbnail object',
		];
	}

	/**
	 * @dataProvider provideRepoCouldNotStreamFile
	 * @depends testGenerateAndStreamThumbnail
	 */
	public function testRepoCouldNotStreamFile( int $width, string $expectedError ) {
		$handler = $this->getMockBuilder( BitmapHandler::class )
			->onlyMethods( [ 'doTransform' ] )
			->getMock();

		$file = $this->getTestRepo()->newFile( 'Test.png' );
		$params = [ 'width' => $width, 'height' => $width ];
		$handler->method( 'doTransform' )->willReturn(
			new ThumbnailImage( $file, '', false, $params )
		);

		$factory = $this->createNoOpMock( MediaHandlerFactory::class, [ 'getHandler' ] );
		$factory->method( 'getHandler' )->willReturn( $handler );
		$this->setService( 'MediaHandlerFactory', $factory );

		$env = $this->makeEnvironment(
			[
				'f' => 'Test.png',
				'width' => $width
			]
		);
		$entryPoint = $this->getEntryPoint( $env );

		$entryPoint->run();
		$output = $entryPoint->getCapturedOutput();

		$env->assertStatusCode( 500, $output );
		$env->assertHeaderValue(
			'text/html; charset=utf-8',
			'Content-Type'
		);

		$this->assertStringContainsString( $expectedError, $output );
	}

	/**
	 * @param array $props
	 * @param string $output binary data
	 */
	private function assertThumbnail( array $props, string $output ): void {
		if ( isset( $props['magic'] ) ) {
			$this->assertStringStartsWith(
				$props['magic'],
				$output,
				'Magic number should match'
			);
		}

		if ( isset( $props['width'] ) && function_exists( 'getimagesizefromstring' ) ) {
			[ $width, ] = getimagesizefromstring( $output );
			$this->assertSame(
				$props['width'],
				$width
			);
		}
	}

}
