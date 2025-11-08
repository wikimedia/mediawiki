<?php

use MediaWiki\Logger\LoggerFactory;
use Wikimedia\FileBackend\FileBackend;
use Wikimedia\FileBackend\FileBackendError;
use Wikimedia\FileBackend\SwiftFileBackend;
use Wikimedia\TestingAccessWrapper;

/**
 * @group FileRepo
 * @group FileBackend
 * @group medium
 *
 * @covers \Wikimedia\FileBackend\SwiftFileBackend
 * @covers \Wikimedia\FileBackend\FileIteration\SwiftFileBackendDirList
 * @covers \Wikimedia\FileBackend\FileIteration\SwiftFileBackendFileList
 * @covers \Wikimedia\FileBackend\FileIteration\SwiftFileBackendList
 */
class SwiftFileBackendTest extends MediaWikiIntegrationTestCase {
	/** @var TestingAccessWrapper|SwiftFileBackend */
	private $backend;

	protected function setUp(): void {
		parent::setUp();

		$this->backend = TestingAccessWrapper::newFromObject(
			new SwiftFileBackend( [
				'name'             => 'local-swift-testing',
				'class'            => SwiftFileBackend::class,
				'wikiId'           => 'unit-testing',
				'lockManager'      => $this->getServiceContainer()->getLockManagerGroupFactory()
							->getLockManagerGroup()->get( 'fsLockManager' ),
				'swiftAuthUrl'     => 'http://127.0.0.1:8080/auth', // unused
				'swiftUser'        => 'test:tester',
				'swiftKey'         => 'testing',
				'swiftTempUrlKey'  => 'b3968d0207b54ece87cccc06515a89d4', // unused
				'logger'           => LoggerFactory::getInstance( 'FileOperation' )
			] )
		);
	}

	/**
	 * @dataProvider provider_testExtractPostableContentHeaders
	 */
	public function testExtractPostableContentHeaders( $raw, $sanitized ) {
		$hdrs = $this->backend->extractMutableContentHeaders( $raw );

		$this->assertEquals( $sanitized, $hdrs, 'Correct extractPostableContentHeaders() result' );
	}

	public static function provider_testExtractPostableContentHeaders() {
		return [
			'empty' => [
				[],
				[]
			],
			[
				[
					'content-length' => 345,
					'content-type' => 'image+bitmap/jpeg',
					'content-disposition' => 'inline',
					'content-duration' => 35.6363,
					'content-Custom' => 'hello',
					'x-content-custom' => 'hello'
				],
				[
					'content-type' => 'image+bitmap/jpeg',
					'content-disposition' => 'inline',
					'content-duration' => 35.6363,
					'content-custom' => 'hello',
					'x-content-custom' => 'hello'
				]
			],
			[
				[
					'content-length' => 345,
					'content-type' => 'image+bitmap/jpeg',
					'content-Disposition' => 'inline; filename=xxx; ' . str_repeat( 'o', 1024 ),
					'content-duration' => 35.6363,
					'content-custom' => 'hello',
					'x-content-custom' => 'hello'
				],
				[
					'content-type' => 'image+bitmap/jpeg',
					'content-disposition' => 'inline; filename=xxx',
					'content-duration' => 35.6363,
					'content-custom' => 'hello',
					'x-content-custom' => 'hello'
				]
			],
			[
				[
					'content-length' => 345,
					'content-type' => 'image+bitmap/jpeg',
					'content-disposition' => 'filename=' . str_repeat( 'o', 1024 ) . ';inline',
					'content-duration' => 35.6363,
					'content-custom' => 'hello',
					'x-content-custom' => 'hello'
				],
				[
					'content-type' => 'image+bitmap/jpeg',
					'content-disposition' => '',
					'content-duration' => 35.6363,
					'content-custom' => 'hello',
					'x-content-custom' => 'hello'
				]
			],
			[
				[
					'x-delete-at' => 'non numeric',
					'x-delete-after' => 'non numeric',
					'x-content-custom' => 'hello'
				],
				[
					'x-content-custom' => 'hello'
				]
			],
			[
				[
					'x-delete-at' => '12345',
					'x-delete-after' => '12345'
				],
				[
					'x-delete-at' => '12345',
					'x-delete-after' => '12345'
				]
			],
			[
				[
					'x-delete-at' => 12345,
					'x-delete-after' => 12345
				],
				[
					'x-delete-at' => 12345,
					'x-delete-after' => 12345
				]
			]
		];
	}

	/**
	 * @dataProvider provider_testGetMetadataHeaders
	 */
	public function testGetMetadataHeaders( $raw, $sanitized ) {
		$hdrs = $this->backend->extractMetadataHeaders( $raw );

		$this->assertEquals( $sanitized, $hdrs, 'getMetadataHeaders() has unexpected result' );
	}

	public static function provider_testGetMetadataHeaders() {
		return [
			[
				[
					'content-length' => 345,
					'content-custom' => 'hello',
					'x-content-custom' => 'hello',
					'x-object-meta-custom' => 5,
					'x-object-meta-sha1Base36' => 'a3deadfg...',
				],
				[
					'x-object-meta-custom' => 5,
					'x-object-meta-sha1base36' => 'a3deadfg...',
				]
			]
		];
	}

	/**
	 * @dataProvider provider_testGetMetadata
	 */
	public function testGetMetadata( $raw, $sanitized ) {
		$hdrs = $this->backend->getMetadataFromHeaders( $raw );

		$this->assertEquals( $sanitized, $hdrs, 'getMetadata() has unexpected result' );
	}

	public static function provider_testGetMetadata() {
		return [
			[
				[
					'content-length' => 345,
					'content-custom' => 'hello',
					'x-content-custom' => 'hello',
					'x-object-meta-custom' => 5,
					'x-object-meta-sha1Base36' => 'a3deadfg...',
				],
				[
					'custom' => 5,
					'sha1base36' => 'a3deadfg...',
				]
			]
		];
	}

	private function setupAuthFailure() {
		$this->backend->authErrorTimestamp = time();
		$this->backend->http = null;
	}

	public function testGetFileStatAuthFail() {
		$this->setupAuthFailure();
		$result = $this->backend->getFileStat( [
			'src' => 'mwstore://local-swift-testing/c/test.txt'
		] );
		$this->assertSame( FileBackend::STAT_ERROR, $result );
	}

	public function testGetFileContentsAuthFail() {
		$this->setupAuthFailure();
		$result = $this->backend->getFileContents( [
			'src' => 'mwstore://local-swift-testing/c/test.txt'
		] );
		$this->assertFalse( $result );
	}

	public function testGetLocalCopyAuthFail() {
		$this->setupAuthFailure();
		$result = $this->backend->getLocalCopy( [
			'src' => 'mwstore://local-swift-testing/c/test.txt'
		] );
		$this->assertNull( $result );
	}

	public function testCreateAuthFail() {
		$this->setupAuthFailure();
		$status = $this->backend->create( [
			'dst' => 'mwstore://local-swift-testing/c/test.txt',
			'content' => '',
		] );
		// Ideally it would fail with backend-fail-connect, but preloadFileStat()
		// fails without any way to propagate error details.
		$this->assertStatusError( 'backend-fail-internal', $status );
	}

	public function testSecureAuthFail() {
		$this->setupAuthFailure();
		$status = $this->backend->secure( [
			'dir' => 'mwstore://local-swift-testing/c',
			'noAccess' => true,
		] );
		$this->assertStatusError( 'backend-fail-internal', $status );
	}

	public function testPrepareAuthFail() {
		$this->setupAuthFailure();
		$status = $this->backend->prepare( [
			'dir' => 'mwstore://local-swift-testing/c',
			'noAccess' => true,
		] );
		$this->assertStatusError( 'backend-fail-internal', $status );
	}

	public function testCleanAuthFail() {
		$this->setupAuthFailure();
		$status = $this->backend->clean( [
			'dir' => 'mwstore://local-swift-testing/c',
		] );
		$this->assertStatusError( 'backend-fail-internal', $status );
	}

	public function testGetFileListAuthFail() {
		$this->setupAuthFailure();
		$result = $this->backend->getFileList( [
			'dir' => 'mwstore://local-swift-testing/c',
		] );
		$this->expectException( FileBackendError::class );
		iterator_to_array( $result );
	}
}
