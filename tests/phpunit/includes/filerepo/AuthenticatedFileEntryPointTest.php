<?php

use MediaWiki\Context\RequestContext;
use MediaWiki\FileRepo\AuthenticatedFileEntryPoint;
use MediaWiki\MainConfigNames;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Tests\FileRepo\TestRepoTrait;
use MediaWiki\Tests\MockEnvironment;
use MediaWiki\Title\Title;

/**
 * @covers \MediaWiki\FileRepo\AuthenticatedFileEntryPoint
 * @group Database
 */
class AuthenticatedFileEntryPointTest extends MediaWikiIntegrationTestCase {
	use TestRepoTrait;

	private const PNG_MAGIC = "\x89\x50\x4e\x47";
	private const JPEG_MAGIC = "\xff\xd8\xff\xe0";

	private const IMAGES_DIR = __DIR__ . '/../../data/media';

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
		$this->importFileToTestRepo( self::IMAGES_DIR . '/greyscale-na-png.png', 'Test.png' );
		$this->importFileToTestRepo( self::IMAGES_DIR . '/portrait-rotated.jpg', 'Icon.jpg' );

		// Create a thumbnail
		$this->copyFileToTestBackend(
			self::IMAGES_DIR . '/greyscale-na-png.png',
			'/thumb/Test.png'
		);

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

	protected function setUp(): void {
		parent::setUp();
		$this->overrideConfigValue(
			MainConfigNames::ImgAuthDetails,
			true
		);
		$this->overrideConfigValue(
			MainConfigNames::ForeignFileRepos,
			[]
		);
		$this->overrideConfigValue(
			MainConfigNames::UseInstantCommons,
			false
		);
		$this->overrideConfigValue(
			MainConfigNames::ImgAuthUrlPathMap,
			[ '/testing' => 'mwstore://test/test-thumb/' ]
		);
		$this->overrideConfigValue(
			MainConfigNames::ImgAuthPath,
			'/img_auth/'
		);
		$this->installTestRepoGroup();
	}

	private function recordHeader( string $header ) {
		$this->environment->getFauxResponse()->header( $header );
	}

	private function getFileUrlPath( string $name, string $prefix = '' ): string {
		if ( $prefix !== '' && !str_ends_with( $prefix, '/' ) ) {
			$prefix = $prefix . '/';
		}

		if ( !str_starts_with( $prefix, '/' ) ) {
			// Unauthenticated path
			$prefix = '/w/images/' . $prefix;
		}

		$file = $this->getTestRepo()->newFile( $name );
		if ( $file ) {
			$name = $file->getRel();
		}

		return $prefix . $name;
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
			$url = $request;
			$request = new FauxRequest();
			$request->setRequestURL( $url );
		}

		if ( is_array( $request ) ) {
			$request = new FauxRequest( $request );
		}

		$this->environment = new MockEnvironment( $request );
		return $this->environment;
	}

	/**
	 * @param MockEnvironment|null $environment
	 * @param FauxRequest|RequestContext|string|array|null $request
	 *
	 * @return AuthenticatedFileEntryPoint
	 */
	private function getEntryPoint( ?MockEnvironment $environment = null, $request = null ) {
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

		$entryPoint = new AuthenticatedFileEntryPoint(
			$context,
			$environment,
			$this->getServiceContainer()
		);

		$entryPoint->enableOutputCapture();
		return $entryPoint;
	}

	public static function provideGetRequestPathSuffix() {
		yield [ '/upload', '/upload/file', 'file' ];
		yield [ '/upload', '/upload/file?q=x', 'file' ];
		yield [ '/upload', '/upload/x%25y', 'x%y' ];
		yield [ '/foo', '/upload/file', false ];
	}

	/**
	 * @dataProvider provideGetRequestPathSuffix
	 *
	 * @param string $basePath
	 * @param string $requestURL
	 * @param string|false $expected
	 *
	 * @covers \MediaWiki\MediaWikiEntryPoint::getRequestPathSuffix
	 */
	public function testGetRequestPathSuffix( string $basePath, string $requestURL, $expected ) {
		$entryPoint = $this->getEntryPoint( $this->makeEnvironment( $requestURL ) );
		$this->assertSame( $expected, $entryPoint->getRequestPathSuffix( $basePath ) );
	}

	public static function provideStreamFile() {
		yield 'public wiki' => [
			'',
		];

		yield 'private wiki' => [
			'',
			[
				'*' => [],
				'user' => [ 'read' => true ],
			],
			[],
			[],
			[
				'cache-control' => 'private',
				'vary' => 'Cookie',
			]
		];

		yield 'range' => [
			'',
			[],
			[],
			[ 'HTTP_RANGE' => 'bytes=0-99' ],
			[ 'content-range' => 'bytes 0-99/365', 'content-length' => '100' ],
			206
		];

		yield 'download' => [
			'',
			[],
			[ 'download' => 1 ],
			[],
			[ 'content-disposition' => 'attachment' ]
		];

		yield 'thumb zone' => [
			// Path under /w/images/
			'thumb',
		];

		yield 'mapped prefix' => [
			// Path under /w/images/
			'testing', // per ImgAuthUrlPathMap
		];

		yield 'use ImgAuthPath' => [
			// If the prefix starts with a "/" it's the full path.
			'/img_auth/', // per ImgAuthPath
		];
	}

	/**
	 * @dataProvider provideStreamFile
	 *
	 * @param string $prefix
	 * @param array $permissions
	 * @param array $requestData
	 * @param array $serverInfo
	 * @param array $expectedHeaders
	 * @param int $expectedCode
	 *
	 * @throws Exception
	 */
	public function testStreamFile(
		string $prefix,
		array $permissions = [],
		array $requestData = [],
		array $serverInfo = [],
		array $expectedHeaders = [],
		int $expectedCode = 200
	) {
		if ( !isset( $permissions['*'] ) ) {
			// public wiki
			$permissions['*'] = [ 'read' => true ];
		}

		$this->overrideConfigValue( MainConfigNames::GroupPermissions, $permissions );

		$name = 'Test.png';
		$url = $this->getFileUrlPath( $name, $prefix );
		$request = new FauxRequest( $requestData );
		$request->setRequestURL( $url );
		$env = $this->makeEnvironment( $request );

		foreach ( $serverInfo as $key => $value ) {
			$env->setServerInfo( $key, $value );
		}

		$entryPoint = $this->getEntryPoint( $env );
		$entryPoint->run();
		$data = $entryPoint->getCapturedOutput();

		$env->assertStatusCode( $expectedCode, $data );

		$this->assertStringStartsWith(
			self::PNG_MAGIC,
			$data
		);

		$env->assertHeaderValue( 'image/png', 'Content-Type' );
		foreach ( $expectedHeaders as $name => $exp ) {
			$env->assertHeaderValue( $exp, $name );
		}
	}

	public function testStreamFile_archive() {
		$this->overrideConfigValue(
			MainConfigNames::GroupPermissions,
			[ '*' => [ 'read' => true ] ]
		);

		$name = 'Test.png';
		$file = $this->getTestRepo()->newFile( $name );
		$history = $file->getHistory();
		$oldFile = $history[0];
		$url = '/img_auth/' . $oldFile->getArchiveRel() . '/' . $oldFile->getArchiveName();

		$env = $this->makeEnvironment( $url );

		$entryPoint = $this->getEntryPoint( $env );
		$entryPoint->run();
		$data = $entryPoint->getCapturedOutput();

		$env->assertStatusCode( 200, $data );
	}

	public function testNotModified() {
		$this->overrideConfigValue(
			MainConfigNames::GroupPermissions,
			[ '*' => [ 'read' => true ] ]
		);

		$url = $this->getFileUrlPath( 'Test.png' );
		$env = $this->makeEnvironment( $url );
		$env->setServerInfo( 'HTTP_IF_MODIFIED_SINCE', '25250101001122' );

		$entryPoint = $this->getEntryPoint( $env );
		$entryPoint->run();

		// Not modified
		$env->assertStatusCode( 304 );
	}

	public function testAccessDenied_deleted() {
		$this->overrideConfigValue(
			MainConfigNames::GroupPermissions,
			[ '*' => [ 'read' => true ] ]
		);

		$name = 'Icon.jpg';
		$file = $this->getTestRepo()->newFile( $name );
		$history = $file->getHistory();

		// This old revision is marked as deleted (supressed) in the database
		$oldFile = $history[0];
		$url = '/img_auth/' . $oldFile->getArchiveRel() . '/' . $oldFile->getArchiveName();
		$env = $this->makeEnvironment( $url );

		$entryPoint = $this->getEntryPoint( $env );
		$entryPoint->run();
		$data = $entryPoint->getCapturedOutput();

		$env->assertStatusCode( 403, $data );
	}

	public static function provideAccessDenied() {
		yield 'no prefix' => [ '' ];
		yield 'thumb zone' => [ 'thumb' ];
		yield 'mapped prefix' => [ 'testing' ];
	}

	/**
	 * @dataProvider provideAccessDenied
	 */
	public function testAccessDenied(
		string $prefix,
		string $expected = 'User does not have access to read'
	) {
		$this->overrideConfigValue(
			MainConfigNames::GroupPermissions,
			[ '*' => [], 'user' => [], ]
		);

		$env = $this->makeEnvironment( $this->getFileUrlPath( 'Test.png', $prefix ) );

		$entryPoint = $this->getEntryPoint( $env );
		$entryPoint->run();
		$output = $entryPoint->getCapturedOutput();

		$env->assertStatusCode( 403 );
		$env->assertHeaderValue( 'no-cache', 'cache-control' );
		$this->assertStringContainsString( '<h1>Access denied</h1>', $output );
		$this->assertStringContainsString( $expected, $output );
	}

	public function testAccessDenied_hook() {
		$this->setUserLang( 'qqx' );

		$this->overrideConfigValue(
			MainConfigNames::GroupPermissions,
			[ '*' => [], 'user' => [ 'read' => true ], ]
		);

		$this->setTemporaryHook(
			'ImgAuthBeforeStream',
			static function ( $title, $path, $name, ?array &$result ) {
				$result = [ 'test-title', 'test-detail' ];
				return false;
			}
		);

		$env = $this->makeEnvironment( $this->getFileUrlPath( 'Test.png' ) );

		$entryPoint = $this->getEntryPoint( $env );
		$entryPoint->run();
		$output = $entryPoint->getCapturedOutput();

		$env->assertStatusCode( 403 );
		$env->assertHeaderValue( 'no-cache', 'cache-control' );
		$this->assertStringContainsString( '<h1>⧼test-title⧽</h1>', $output );
		$this->assertStringContainsString( '<p>⧼test-detail⧽</p>', $output );
	}

	public static function provideNotFOund() {
		yield 'no prefix, missing file' =>
			[ 'No-such-file.png', '' ];

		yield 'no prefix, bad title' =>
			[ '_<>_', '' ];

		yield 'thumb zone' =>
			[ 'No-such-file.png', 'thumb' ];

		yield 'mapped prefix' =>
			[ 'No-such-file.png', 'testing' ];

		yield 'unrecognized base path' =>
			[
				'No-such-file.png',
				'/bad/base/path',
				'Requested path is not in the configured',
			];
	}

	/**
	 * @dataProvider provideNotFOund
	 */
	public function testNotFound( string $name, string $prefix, $expected = 'does not exist' ) {
		$this->overrideConfigValue(
			MainConfigNames::GroupPermissions,
			[
				'*' => [ 'read' => 'true' ],
			]
		);

		$env = $this->makeEnvironment( $this->getFileUrlPath( $name, $prefix ) );

		$entryPoint = $this->getEntryPoint( $env );
		$entryPoint->run();
		$output = $entryPoint->getCapturedOutput();

		// Missing files are also "forbidden"
		$env->assertStatusCode( 403 );
		$env->assertHeaderValue( 'no-cache', 'cache-control' );
		$this->assertStringContainsString(
			'<h1>Access denied</h1>',
			$output
		);
		$this->assertStringContainsString(
			$expected,
			$output
		);
	}

}
