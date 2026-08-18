<?php

namespace MediaWiki\Tests\Specials;

use MediaWiki\Context\RequestContext;
use MediaWiki\Exception\HttpError;
use MediaWiki\Exception\PermissionsError;
use MediaWiki\FileRepo\LocalRepo;
use MediaWiki\FileRepo\RepoGroup;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Media\MediaHandlerFactory;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Specials\SpecialUploadStash;
use MediaWiki\Upload\Exception\UploadStashNoSuchKeyException;
use MediaWiki\Upload\UploadStash;
use MediaWiki\User\UserIdentity;
use MediaWiki\WikiMap\WikiMap;
use Wikimedia\FileBackend\FSFileBackend;

/**
 * @covers \MediaWiki\Specials\SpecialUploadStash
 * @group Database
 */
class SpecialUploadStashTest extends SpecialPageTestBase {
	use \MockHttpTrait;

	private ?UploadStash $stash = null;
	private array $stashKeys = [];
	private array $headers = [];
	private ?int $maxServeBytes = null;

	private const JPEG_HEADER = 'ffd8ff';

	public function tearDown(): void {
		foreach ( $this->stashKeys as $stashKey ) {
			try {
				$this->stash?->removeFile( $stashKey );
			} catch ( UploadStashNoSuchKeyException ) {
			}
		}
	}

	protected function newSpecialPage(): SpecialUploadStash {
		/** @var SpecialUploadStash $page */
		$page = $this->getServiceContainer()->getSpecialPageFactory()
			->getPage( 'UploadStash' );
		if ( $this->maxServeBytes !== null ) {
			$page->setMaxServeBytes( $this->maxServeBytes );
		}
		return $page;
	}

	public function testGetRestriction() {
		$this->setGroupPermissions( [ 'user' => [ 'upload' => false ] ] );
		$this->expectException( PermissionsError::class );
		$this->executeSpecialPage();
	}

	public function testStreamLocallyScaledThumb() {
		$testUser = $this->getTestUser();
		$key = $this->setupStash( $testUser->getUserIdentity() );
		$request = new FauxRequest;
		[ $body ] = $this->executeSpecialPage(
			"thumb/$key/120px-$key",
			$request,
			performer: $testUser->getAuthority()
		);
		$this->assertArrayHasKey( 'last-modified', $this->headers );
		$this->assertArrayHasKey( 'content-security-policy', $this->headers );
		$this->assertArrayHasKey( 'content-length', $this->headers );
		$this->assertStringContainsString( 'private', $this->headers['cache-control'][0] );
		$this->assertSame( 'image/jpeg', $this->headers['content-type'][0] );
		$this->assertStringStartsWith( self::JPEG_HEADER, bin2hex( $body ) );
	}

	public static function provideStreamRemoteScaledThumb() {
		return [
			'ok' => [ 200, [ 'Content-Type' => 'image/jpeg' ], null ],
			'error' => [ 500, [], 'uploadstash-file-not-found-no-remote-thumb' ],
			'No content type' => [ 200, [], 'uploadstash-file-not-found-missing-content-type' ],
			'Too long' => [
				200,
				[ 'Content-Type' => 'image/jpeg' ],
				'uploadstash-file-too-large',
				100
			],
		];
	}

	/**
	 * @dataProvider provideStreamRemoteScaledThumb
	 */
	public function testStreamRemoteScaledThumb(
		int $responseCode,
		array $responseHeaders,
		?string $expectedError,
		?int $dataLength = null
	) {
		$testUser = $this->getTestUser();
		$key = $this->setupStash( $testUser->getUserIdentity(), [
			'thumbProxyUrl' => 'https://localhost/scaler/',
			// TODO: get request headers out of MockHttpTrait and assert this value
			'thumbProxySecret' => 'secret',
		] );

		$name = 'landscape-plain.jpg';

		if ( $dataLength === null ) {
			$mockBody = 'thumbnail output';
		} else {
			$mockBody = str_repeat( 'x', $dataLength );
			$this->maxServeBytes = 10;
		}

		$httpRequest = $this->makeFakeHttpRequest(
			$mockBody,
			$responseCode,
			$responseHeaders
		);

		$this->installMockHttp( function ( $url ) use ( $httpRequest, $name ) {
			$this->assertStringMatchesFormat(
				"https://localhost/scaler/temp/%x/%x/%d%%21$name/120px-%d%%21$name",
				$url
			);
			return $httpRequest;
		} );

		$request = new FauxRequest;

		if ( $expectedError ) {
			$this->expectException( HttpError::class );
			$this->expectExceptionMessageMatches( '/' . preg_quote( $expectedError, '/' ) . '/' );
		}

		[ $body, $response ] = $this->executeSpecialPage(
			"thumb/$key/120px-$key",
			$request,
			performer: $testUser->getAuthority()
		);

		$headers = $response->getHeaders();
		$this->assertArrayHasKey( 'CONTENT-SECURITY-POLICY', $headers );
		$this->assertArrayHasKey( 'EXPIRES', $headers );
		$this->assertStringContainsString( 'private', $headers['CACHE-CONTROL'] );
		$this->assertSame( 'image/jpeg', $headers['CONTENT-TYPE'] );
		$this->assertSame( $mockBody, $body );
	}

	public static function provideShowUploadOrThrowErrors() {
		return [
			'unknown-type' => [
				'unknown',
				'uploadstash-bad-path-unknown-type'
			],
			'trailing garbage' => [
				'thumb/$key/120px-$key-trail',
				'uploadstash-bad-path-unrecognized-thumb-name'
			],
			'invalid params' => [
				'thumb/$key/---$key',
				'uploadstash-bad-path-unrecognized-thumb-name'
			],
			'file not found' => [
				'thumb/nonexistent.jpg/120px-nonexistent.jpg',
				'uploadstash-file-not-found'
			],
			'thumb larger than original' => [
				'thumb/$key/500px-$key',
				// Error message could be improved
				'uploadstash-file-not-found-no-local-path'
			],
			'no handler' => [
				'thumb/$key/120px-$key',
				'uploadstash-bad-path-no-handler',
				[ 'handlers' => [ 'image/jpeg' => false ] ]
			],
			'thumb too large' => [
				'thumb/$key/120px-$key',
				'uploadstash-file-too-large',
				[ 'maxServeBytes' => 100 ],
			],
			'original too large' => [
				'file/$key',
				'uploadstash-file-too-large',
				[ 'maxServeBytes' => 100 ],
			],
		];
	}

	/**
	 * @dataProvider provideShowUploadOrThrowErrors
	 */
	public function testShowUploadOrThrowErrors( string $subPage, string $expectedMessage,
		array $stashParams = []
	) {
		$testUser = $this->getTestUser();
		$key = $this->setupStash( $testUser->getUserIdentity(), $stashParams );
		$subPage = str_replace( '$key', $key, $subPage );
		$request = new FauxRequest;
		$this->expectException( HttpError::class );
		$this->expectExceptionMessageMatches( '/' . preg_quote( $expectedMessage, '/' ) . '/' );
		$this->executeSpecialPage(
			$subPage,
			$request,
			performer: $testUser->getAuthority()
		);
	}

	public function testStreamOriginal() {
		$testUser = $this->getTestUser();
		$contents = file_get_contents( $this->getTestFileSourcePath( 'test.jpg' ) );
		$key = $this->setupStash( $testUser->getUserIdentity(), [ 'file' => 'test.jpg' ] );
		$request = new FauxRequest;
		[ $body ] = $this->executeSpecialPage(
			"file/$key",
			$request,
			performer: $testUser->getAuthority()
		);
		$this->assertArrayHasKey( 'last-modified', $this->headers );
		$this->assertArrayHasKey( 'content-security-policy', $this->headers );
		$this->assertSame( $this->headers['content-length'][0], (string)strlen( $contents ) );
		$this->assertStringContainsString( 'private', $this->headers['cache-control'][0] );
		$this->assertSame( bin2hex( $contents ), bin2hex( $body ) );
	}

	/**
	 * Set up a stash with a file in it and return the stash key
	 *
	 * @param UserIdentity $user
	 * @param array $params Associative array:
	 *  - file: The file
	 *  - maxServeBytes: The maximum number of bytes allowed
	 *  - thumbProxyUrl: The remote scaler
	 *  - thumbProxySecret: The X-Swift-Secret header
	 * @return string
	 */
	private function setupStash( UserIdentity $user, array $params = [] ): string {
		$params += [
			// Some tests need a file bigger than 120px.
			'file' => 'landscape-plain.jpg'
		];

		// UploadStash uses wfMessage() -- use qqx for consistency with SpecialPageTestBase
		RequestContext::getMain()->setLanguage( 'qqx' );

		if ( isset( $params['maxServeBytes'] ) ) {
			$this->maxServeBytes = $params['maxServeBytes'];
		}

		if ( isset( $params['handlers'] ) ) {
			$this->setService(
				'MediaHandlerFactory',
				new MediaHandlerFactory(
					$this->getServiceContainer()->getLanguageFactory(),
					LoggerFactory::getInstance( 'MediaHandlerFactory' ),
					$params['handlers']
				)
			);
		}

		$dir = $this->getNewTempDirectory();
		$backend = new FSFileBackend( [
			'name' => 'test-backend',
			'wikiId' => WikiMap::getCurrentWikiId(),
			'basePath' => $dir,
			// phpcs:ignore Squiz.WhiteSpace.ScopeClosingBrace.ContentBefore
			'obResetFunc' => static function () {},
			'headerFunc' => function ( $header ) {
				$this->addHeaderLine( $header );
			}
		] );

		$repoParams = [
			'name' => 'test-repo',
			'backend' => $backend
		];
		if ( isset( $params[ 'thumbProxyUrl'] ) ) {
			$repoParams['thumbProxyUrl'] = $params['thumbProxyUrl'];
		}
		if ( isset( $params['thumbProxySecret'] ) ) {
			$repoParams['thumbProxySecret'] = $params['thumbProxySecret'];
		}

		$repo = new LocalRepo( $repoParams );
		$repoGroup = $this->createMock( RepoGroup::class );
		$repoGroup->method( 'getLocalRepo' )->willReturn( $repo );
		$this->setService( 'RepoGroup', $repoGroup );

		$this->stash = $repo->getUploadStash( $user );
		$stashFile = $this->stash->stashFile( $this->getTestFileSourcePath( $params['file'] ) );
		$this->stashKeys[] = $stashFile->getFileKey();
		return $stashFile->getFileKey();
	}

	private function getTestFileSourcePath( $name ) {
		return MW_INSTALL_PATH . "/tests/phpunit/data/media/$name";
	}

	private function addHeaderLine( $line ) {
		[ $name, $value ] = explode( ':', $line, 2 );
		$name = strtolower( trim( $name ) );
		$value = trim( $value );
		$this->headers[$name][] = $value;
	}

	public function testShowUploads() {
		$testUser = $this->getTestUser();
		$key = $this->setupStash( $testUser->getUserIdentity() );
		[ $body ] = $this->executeSpecialPage( performer: $testUser->getAuthority() );
		$this->assertStringContainsString( $key, $body );
		$this->assertStringContainsString( 'uploadstash-clear', $body );
	}

	public function testShowUploadsSubmit() {
		$testUser = $this->getTestUser();
		$this->setupStash( $testUser->getUserIdentity() );
		$request = new FauxRequest( [
			'clear' => '1',
			],
			true
		);
		[ $body ] = $this->executeSpecialPage(
			request: $request,
			performer: $testUser->getAuthority()
		);
		// It just shows the pager, no confirmation message
		$this->assertStringContainsString( 'uploadstash-nofiles', $body );
	}
}
