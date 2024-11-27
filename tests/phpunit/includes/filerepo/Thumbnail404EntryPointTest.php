<?php

use MediaWiki\Context\RequestContext;
use MediaWiki\FileRepo\Thumbnail404EntryPoint;
use MediaWiki\MainConfigNames;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Tests\FileRepo\TestRepoTrait;
use MediaWiki\Tests\MockEnvironment;
use MediaWiki\Title\Title;

/**
 * @covers \MediaWiki\FileRepo\Thumbnail404EntryPoint
 * @group Database
 */
class Thumbnail404EntryPointTest extends MediaWikiIntegrationTestCase {

	use TestRepoTrait;
	use MockHttpTrait;

	private const PNG_MAGIC = "\x89\x50\x4e\x47";
	private const JPEG_MAGIC = "\xff\xd8\xff\xe0";

	private const IMAGES_DIR = __DIR__ . '/../../data/media';

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
		$this->importFileToTestRepo( self::IMAGES_DIR . '/Animated_PNG_example_bouncing_beach_ball.png' );
		$this->importFileToTestRepo( self::IMAGES_DIR . '/test.jpg', 'Icon.jpg' );

		// Create a second version of Test.png
		$this->importFileToTestRepo( self::IMAGES_DIR . '/greyscale-dot-na-png.png', 'Test.png' );

		// Create a redirect
		$title = Title::makeTitle( NS_FILE, 'Redirect_to_Test.png' );
		$this->editPage( $title, '#REDIRECT [[File:Test.png]]' );
	}

	public static function tearDownAfterClass(): void {
		self::destroyTestRepo();
		parent::tearDownAfterClass();
	}

	public function setUp(): void {
		parent::setUp();

		$this->installTestRepoGroup();
	}

	/**
	 * @param FauxRequest|string|null $request
	 *
	 * @return MockEnvironment
	 */
	private function makeEnvironment( $request ): MockEnvironment {
		if ( !$request ) {
			$request = new FauxRequest();
		}

		if ( is_string( $request ) ) {
			$req = new FauxRequest( [] );
			$req->setRequestURL( $request );
			$request = $req;
		}

		return new MockEnvironment( $request );
	}

	/**
	 * @param MockEnvironment|null $environment
	 * @param FauxRequest|RequestContext|string|array|null $request
	 *
	 * @return Thumbnail404EntryPoint
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

		$entryPoint = new Thumbnail404EntryPoint(
			$context,
			$environment,
			$this->getServiceContainer()
		);

		$entryPoint->enableOutputCapture();
		return $entryPoint;
	}

	public static function provideNotFound() {
		yield 'non-existing image' => [
			'/w/images/thumb/a/aa/Xyzzy.png/13px-Xyzzy.png',
			404
		];
		yield 'malformed name' => [
			'/w/images/thumb/x/xx/XyzzyXyzzy',
			400
		];
	}

	/**
	 * @dataProvider provideNotFound
	 */
	public function testNotFound( $req, $expectedStatus ) {
		$env = $this->makeEnvironment( $req );
		$entryPoint = $this->getEntryPoint( $env );

		$entryPoint->run();
		$output = $entryPoint->getCapturedOutput();

		$env->assertStatusCode( $expectedStatus );
		$env->assertHeaderValue(
			'text/html; charset=utf-8',
			'Content-Type'
		);

		$this->assertStringContainsString(
			'<title>Error generating thumbnail</title>',
			$output
		);
	}

	public function testStreamFile() {
		$file = $this->getTestRepo()->newFile( 'Test.png' );
		$rel = $file->getRel();
		$name = $file->getName();

		$env = $this->makeEnvironment( "/w/images/thumb/$rel/13px-$name" );
		$entryPoint = $this->getEntryPoint( $env );

		$entryPoint->run();
		$output = $entryPoint->getCapturedOutput();

		$env->assertStatusCode( 200 );

		$this->assertThumbnail(
			[ 'magic' => self::PNG_MAGIC, 'width' => 13, ],
			$output
		);

		return [ 'data' => $output, 'width' => 13 ];
	}

	public function testStreamFileWithThumbPath() {
		$this->overrideConfigValue( MainConfigNames::ThumbPath, '/thumbnails/' );

		$file = $this->getTestRepo()->newFile( 'Test.png' );
		$rel = $file->getRel();

		$env = $this->makeEnvironment( "/thumbnails/$rel/13px-Test.png" );
		$entryPoint = $this->getEntryPoint( $env );

		$entryPoint->run();
		$output = $entryPoint->getCapturedOutput();

		$env->assertStatusCode( 200 );

		$this->assertThumbnail(
			[ 'magic' => self::PNG_MAGIC, 'width' => 13, ],
			$output
		);
	}

	public function testStreamFileWithLongName() {
		$this->overrideConfigValue( MainConfigNames::VaryOnXFP, true );

		// Note that abbrvThreshold is 16 per MockRepTrait
		$file = $this->getTestRepo()->newFile( 'Animated_PNG_example_bouncing_beach_ball.png' );
		$rel = $file->getRel();
		$name = $file->getName();

		// use abbreviated name
		$env = $this->makeEnvironment( "/w/images/thumb/$rel/13px-thumbnail.png" );
		$entryPoint = $this->getEntryPoint( $env );

		$entryPoint->run();
		$output = $entryPoint->getCapturedOutput();

		$env->assertStatusCode( 200, $output );
		$env->assertHeaderValue( null, 'Vary' );

		// use long name
		$env = $this->makeEnvironment( "/w/images/thumb/$rel/13px-$name" );
		$entryPoint = $this->getEntryPoint( $env );

		$entryPoint->run();
		$output = $entryPoint->getCapturedOutput();

		$env->assertStatusCode( 301, $output );
		$env->assertHeaderValue( 'X-Forwarded-Proto', 'Vary' );

		$this->assertStringEndsWith(
			"/w/images/thumb/$rel/13px-thumbnail.png",
			$env->getFauxResponse()->getHeader( 'Location' )
		);
	}

	/**
	 * @depends testStreamFile
	 */
	public function testStreamOldFile( array $latestThumbnailInfo ) {
		$file = $this->getTestRepo()->newFile( 'Test.png' );
		$history = $file->getHistory();
		$oldFile = $history[0];

		$this->assertNotSame(
			$file->getSize(),
			$oldFile->getSize(),
			'Old and latest file version should not have the same size'
		);

		$curThumbPath = $file->getThumbPath( '13px-Test.png' );
		$oldThumbPath = $oldFile->getThumbPath( '13px-Test.png' );
		$this->assertFalse(
			$file->getRepo()->getBackend()->fileExists( [ 'src' => $oldThumbPath ] ),
			'Thumbnail for old file version does not exist'
		);

		$uri = '/w/images/thumb/' . $oldFile->getArchiveRel()
			. '/' . $oldFile->getArchiveName() . '/13px-Test.png';

		$env = $this->makeEnvironment( $uri );
		$entryPoint = $this->getEntryPoint( $env );

		$entryPoint->run();
		$output = $entryPoint->getCapturedOutput();
		$env->assertStatusCode( 200 );

		$this->assertNotSame(
			$file->getRepo()->getBackend()->getFileSha1Base36( [ 'src' => $oldThumbPath ] ),
			$file->getRepo()->getBackend()->getFileSha1Base36( [ 'src' => $curThumbPath ] ),
			"Thumbnails at $oldThumbPath and $curThumbPath should have different hashes"
		);

		$this->assertNotSame(
			$latestThumbnailInfo['data'],
			$output,
			'Thumbnail for the old version should not be the same as the ' .
			'thumbnail for the latest version'
		);

		$this->assertThumbnail(
			[ 'magic' => self::PNG_MAGIC, 'width' => 13, ],
			$output
		);
	}

	public function testStreamTempFile() {
		$user = $this->getTestUser()->getUser();
		$stash = new UploadStash( $this->getTestRepo(), $user );
		$file = $stash->stashFile( self::IMAGES_DIR . '/adobergb.jpg' );

		$uri = '/w/images/thumb/temp/' . $file->getRel()
			. '/13px-' . $file->getName();

		$env = $this->makeEnvironment( $uri );
		$entryPoint = $this->getEntryPoint( $env );

		$entryPoint->run();
		$output = $entryPoint->getCapturedOutput();

		$env->assertStatusCode( 200 );
		$this->assertThumbnail(
			[ 'magic' => self::JPEG_MAGIC, 'width' => 13, ],
			$output
		);
	}

	public function testBadPath() {
		$file = $this->getTestRepo()->newFile( 'Test.png' );
		$rel = $file->getRel();

		$uri = "/w/images/thumb/$rel/148px-XYZZY";

		$env = $this->makeEnvironment( $uri );
		$entryPoint = $this->getEntryPoint( $env );

		$entryPoint->run();
		$entryPoint->getCapturedOutput();

		$env->assertStatusCode( 404 );
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
