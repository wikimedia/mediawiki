<?php

// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingDocumentationPublic -- Test traits are not excluded

use MediaWiki\FileBackend\FileBackendGroup;
use MediaWiki\FileBackend\LockManager\LockManagerGroupFactory;
use MediaWiki\FileRepo\LocalRepo;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\Output\StreamFile;
use MediaWiki\Status\Status;
use Wikimedia\FileBackend\FSFile\TempFSFileFactory;
use Wikimedia\FileBackend\FSFileBackend;
use Wikimedia\Mime\MimeAnalyzer;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\ObjectCache\HashBagOStuff;
use Wikimedia\TestingAccessWrapper;

/**
 * Code shared by the FileBackendGroup integration and unit tests. They need merely provide a
 * suitable newObj() method and everything else works magically.
 */
trait FileBackendGroupTestTrait {
	/**
	 * @param array $options Dictionary to use as a source for ServiceOptions before defaults, plus
	 *   the following options are available to override other arguments:
	 *     * 'readOnlyMode'
	 *     * 'lmgFactory'
	 *     * 'mimeAnalyzer'
	 *     * 'tmpFileFactory'
	 * @return FileBackendGroup
	 */
	abstract protected function newObj( array $options = [] ): FileBackendGroup;

	/**
	 * @param string $domain Expected argument that LockManagerGroupFactory::getLockManagerGroup
	 *   will receive
	 * @return LockManagerGroupFactory
	 */
	abstract protected function getLockManagerGroupFactory( $domain ): LockManagerGroupFactory;

	/**
	 * @return string As from WikiMap::getCurrentWikiId()
	 */
	abstract protected static function getWikiID();

	/**
	 * null indicates that we don't have the actual object reference, but it should be an empty
	 * HashBagOStuff.
	 *
	 * @var BagOStuff|null
	 */
	private $srvCache;

	/** @var LockManagerGroupFactory */
	private $lmgFactory;

	/** @var TempFSFileFactory */
	private $tmpFileFactory;

	private static function getDefaultLocalFileRepo(): array {
		return [
			'class' => LocalRepo::class,
			'name' => 'local',
			'directory' => 'upload-dir',
			'thumbDir' => 'thumb/',
			'transcodedDir' => 'transcoded/',
			'fileMode' => 0664,
			'scriptDirUrl' => 'script-path/',
			'url' => 'upload-path/',
			'hashLevels' => 2,
			'thumbScriptUrl' => false,
			'transformVia404' => false,
			'deletedDir' => 'deleted/',
			'deletedHashLevels' => 3,
			'backend' => 'local-backend',
		];
	}

	private static function getDefaultOptions(): array {
		return [
			MainConfigNames::DirectoryMode => 0775,
			MainConfigNames::FileBackends => [],
			MainConfigNames::ForeignFileRepos => [],
			MainConfigNames::LocalFileRepo => self::getDefaultLocalFileRepo(),
			'fallbackWikiId' => self::getWikiID(),
		];
	}

	/**
	 * @covers ::__construct
	 */
	public function testConstructor_overrideImplicitBackend() {
		$obj = $this->newObj( [ MainConfigNames::FileBackends =>
			[ [ 'name' => 'local-backend', 'class' => '', 'lockManager' => 'fsLockManager' ] ]
		] );
		$this->assertSame( '', $obj->config( 'local-backend' )['class'] );
	}

	/**
	 * @covers ::__construct
	 */
	public function testConstructor_backendObject() {
		// 'backend' being an object makes that repo from configuration ignored
		// XXX This is not documented in MainConfigSchema, does it do anything useful?
		$obj = $this->newObj( [ MainConfigNames::ForeignFileRepos => [ [ 'backend' => (object)[] ] ] ] );
		$this->assertSame( FSFileBackend::class, $obj->config( 'local-backend' )['class'] );
	}

	/**
	 * @dataProvider provideRegister_domainId
	 * @param string $key Key to check in return value of config()
	 * @param string|callable $expected Expected value of config()[$key], or callable returning it
	 * @param array $extraBackendsOptions To add to the FileBackends entry passed to newObj()
	 * @param array $otherExtraOptions To add to the array passed to newObj() (e.g., services)
	 * @covers ::register
	 */
	public function testRegister(
		$key, $expected, array $extraBackendsOptions = [], array $otherExtraOptions = []
	) {
		if ( $expected instanceof Closure ) {
			// Lame hack to get around providers being called too early
			$expected = $expected();
		}
		if ( $key === 'domainId' ) {
			// This will change the expected LMG name too
			$otherExtraOptions['lmgFactory'] = $this->getLockManagerGroupFactory( $expected );
		}
		$obj = $this->newObj( $otherExtraOptions + [
			MainConfigNames::FileBackends => [
				$extraBackendsOptions + [
					'name' => 'myname', 'class' => '', 'lockManager' => 'fsLockManager'
				]
			],
		] );
		$this->assertSame( $expected, $obj->config( 'myname' )[$key] );
	}

	public static function provideRegister_domainId() {
		return [
			'domainId with neither wikiId nor domainId set' => [
				'domainId',
				static function () {
					return self::getWikiID();
				},
			],
			'domainId with wikiId set but no domainId' =>
				[ 'domainId', 'id0', [ 'wikiId' => 'id0' ] ],
			'domainId with wikiId and domainId set' =>
				[ 'domainId', 'dom1', [ 'wikiId' => 'id0', 'domainId' => 'dom1' ] ],
			'readOnly without readOnly set' => [ 'readOnly', false ],
			'readOnly with readOnly set to string' =>
				[ 'readOnly', 'cuz', [ 'readOnly' => 'cuz' ] ],
			'readOnly without readOnly set but with string in passed object' => [
				'readOnly',
				'cuz',
				[],
				[ 'readOnlyMode' => 'cuz' ],
			],
			'readOnly with readOnly set to false but string in passed object' => [
				'readOnly',
				false,
				[ 'readOnly' => false ],
				[ 'readOnlyMode' => 'cuz' ],
			],
		];
	}

	/**
	 * @dataProvider provideRegister_exception
	 * @param array $fileBackends Value of FileBackends to pass to constructor
	 * @param string $class Expected exception class
	 * @param string $msg Expected exception message
	 * @covers ::__construct
	 * @covers ::register
	 */
	public function testRegister_exception( $fileBackends, $class, $msg ) {
		$this->expectException( $class );
		$this->expectExceptionMessage( $msg );
		$this->newObj( [ MainConfigNames::FileBackends => $fileBackends ] );
	}

	public static function provideRegister_exception() {
		return [
			'Nameless' => [
				[ [] ], InvalidArgumentException::class, "Cannot register a backend with no name."
			],
			'Duplicate' => [
				[ [ 'name' => 'dupe', 'class' => '' ], [ 'name' => 'dupe' ] ],
				LogicException::class,
				"Backend with name 'dupe' already registered.",
			],
			'Classless' => [
				[ [ 'name' => 'classless' ] ],
				InvalidArgumentException::class,
				"Backend with name 'classless' has no class.",
			],
		];
	}

	/**
	 * @covers ::__construct
	 * @covers ::config
	 * @covers ::get
	 */
	public function testGet() {
		$backend = $this->newObj()->get( 'local-backend' );
		$this->assertTrue( $backend instanceof FSFileBackend );
	}

	/**
	 * @covers ::get
	 */
	public function testGetUnrecognized() {
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( "No backend defined with the name 'unrecognized'." );
		$this->newObj()->get( 'unrecognized' );
	}

	/**
	 * @covers ::__construct
	 * @covers ::config
	 */
	public function testConfig() {
		$obj = $this->newObj();
		$config = $obj->config( 'local-backend' );

		// XXX How to actually test that a profiler is loaded?
		$this->assertNull( $config['profiler']( 'x' ) );
		// Equality comparison doesn't work for closures, so just set to null
		$config['profiler'] = null;

		$this->assertEquals( [
			'mimeCallback' => $obj->guessMimeInternal( ... ),
			'obResetFunc' => 'wfResetOutputBuffers',
			'streamMimeFunc' => StreamFile::contentTypeFromPath( ... ),
			'tmpFileFactory' => $this->tmpFileFactory,
			'statusWrapper' => Status::wrap( ... ),
			// Ignore actual value, normal service is fine, because it wraps
			// $wgMainCacheType (HashBagOStuff) which we already clear between tests.
			'wanCache' => $config['wanCache'],
			// If $this->srvCache is null, we don't know what it should be, so just fill in the
			// actual value. Equality to a new HashBagOStuff doesn't work because of the token.
			'srvCache' => $this->srvCache ?? $config['srvCache'],
			'logger' => LoggerFactory::getInstance( 'FileOperation' ),
			// This was set to null above in $config, it's not really null
			'profiler' => null,
			'name' => 'local-backend',
			'containerPaths' => [
				'local-public' => 'upload-dir',
				'local-thumb' => 'thumb/',
				'local-transcoded' => 'transcoded/',
				'local-deleted' => 'deleted/',
				'local-temp' => 'upload-dir/temp',
			],
			'fileMode' => 0664,
			'directoryMode' => 0775,
			'domainId' => self::getWikiID(),
			'readOnly' => false,
			'class' => FSFileBackend::class,
			'lockManager' =>
				$this->lmgFactory->getLockManagerGroup( self::getWikiID() )->get( 'fsLockManager' ),
			'asyncHandler' => DeferredUpdates::addCallableUpdate( ... )
		], $config );

		// Compare closures with ==
		// https://github.com/sebastianbergmann/comparator/issues/127
		$this->assertTrue( $obj->guessMimeInternal( ... ) == $config['mimeCallback'] );

		// For config values that are objects, check object identity.
		$this->assertSame( $this->tmpFileFactory, $config['tmpFileFactory'] );
		if ( $this->srvCache === null ) {
			$this->assertInstanceOf( HashBagOStuff::class, $config['srvCache'] );
			$this->assertSame(
				[], TestingAccessWrapper::newFromObject( $config['srvCache'] )->bag );
		} else {
			$this->assertSame( $this->srvCache, $config['srvCache'] );
		}
	}

	/**
	 * @dataProvider provideConfig_default
	 * @param string $expected Expected default value
	 * @param string $inputName Name to set to null in LocalFileRepo setting
	 * @param string|array $key Key to check in array returned by config(), or array [ 'key1',
	 *   'key2' ] for nested key
	 * @covers ::__construct
	 * @covers ::config
	 */
	public function testConfig_defaultNull( $expected, $inputName, $key ) {
		$config = self::getDefaultLocalFileRepo();
		$config[$inputName] = null;

		$result = $this->newObj( [ MainConfigNames::LocalFileRepo => $config ] )->config( 'local-backend' );

		$actual = is_string( $key ) ? $result[$key] : $result[$key[0]][$key[1]];

		$this->assertSame( $expected, $actual );
	}

	/**
	 * @dataProvider provideConfig_default
	 * @param string $expected Expected default value
	 * @param string $inputName Name to unset in LocalFileRepo setting
	 * @param string|array $key Key to check in array returned by config(), or array [ 'key1',
	 *   'key2' ] for nested key
	 * @covers ::__construct
	 * @covers ::config
	 */
	public function testConfig_defaultUnset( $expected, $inputName, $key ) {
		$config = self::getDefaultLocalFileRepo();
		unset( $config[$inputName] );

		$result = $this->newObj( [ MainConfigNames::LocalFileRepo => $config ] )->config( 'local-backend' );

		$actual = is_string( $key ) ? $result[$key] : $result[$key[0]][$key[1]];

		$this->assertSame( $expected, $actual );
	}

	public static function provideConfig_default() {
		return [
			'deletedDir' => [ false, 'deletedDir', [ 'containerPaths', 'local-deleted' ] ],
			'thumbDir' => [ 'upload-dir/thumb', 'thumbDir', [ 'containerPaths', 'local-thumb' ] ],
			'transcodedDir' => [
				'upload-dir/transcoded', 'transcodedDir', [ 'containerPaths', 'local-transcoded' ]
			],
			'fileMode' => [ 0644, 'fileMode', 'fileMode' ],
		];
	}

	/**
	 * @covers ::config
	 */
	public function testConfigUnrecognized() {
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( "No backend defined with the name 'unrecognized'." );
		$this->newObj()->config( 'unrecognized' );
	}

	/**
	 * @dataProvider provideBackendFromPath
	 * @covers ::backendFromPath
	 * @param string|null $expected Name of backend that will be returned from 'get', or null
	 * @param string $storagePath
	 */
	public function testBackendFromPath( $expected, $storagePath ) {
		$obj = $this->newObj( [ MainConfigNames::FileBackends => [
			[ 'name' => '', 'class' => stdClass::class, 'lockManager' => 'fsLockManager' ],
			[ 'name' => 'a', 'class' => stdClass::class, 'lockManager' => 'fsLockManager' ],
			[ 'name' => 'b', 'class' => stdClass::class, 'lockManager' => 'fsLockManager' ],
		] ] );
		$this->assertSame(
			$expected === null ? null : $obj->get( $expected ),
			$obj->backendFromPath( $storagePath )
		);
	}

	public static function provideBackendFromPath() {
		return [
			'Empty string' => [ null, '' ],
			'mwstore://' => [ null, 'mwstore://' ],
			'mwstore://a' => [ null, 'mwstore://a' ],
			'mwstore:///' => [ null, 'mwstore:///' ],
			'mwstore://a/' => [ null, 'mwstore://a/' ],
			'mwstore://a//' => [ null, 'mwstore://a//' ],
			'mwstore://a/b' => [ 'a', 'mwstore://a/b' ],
			'mwstore://a/b/' => [ 'a', 'mwstore://a/b/' ],
			'mwstore://a/b////' => [ 'a', 'mwstore://a/b////' ],
			'mwstore://a/b/c' => [ 'a', 'mwstore://a/b/c' ],
			'mwstore://a/b/c/d' => [ 'a', 'mwstore://a/b/c/d' ],
			'mwstore://b/b' => [ 'b', 'mwstore://b/b' ],
			'mwstore://c/b' => [ null, 'mwstore://c/b' ],
		];
	}

	/**
	 * @dataProvider provideGuessMimeInternal
	 * @covers ::guessMimeInternal
	 * @param string $storagePath
	 * @param string|null $content
	 * @param string|null $fsPath
	 * @param string|null $expectedExtensionType Expected return of
	 *   MimeAnalyzer::getMimeTypeFromExtensionOrNull
	 * @param string|null $expectedGuessedMimeType Expected return value of
	 *   MimeAnalyzer::guessMimeType (null if expected not to be called)
	 */
	public function testGuessMimeInternal(
		$storagePath,
		$content,
		$fsPath,
		$expectedExtensionType,
		$expectedGuessedMimeType
	) {
		$mimeAnalyzer = $this->createNoOpMock( MimeAnalyzer::class,
			[ 'getMimeTypeFromExtensionOrNull', 'guessMimeType' ] );
		$mimeAnalyzer->expects( $this->once() )->method( 'getMimeTypeFromExtensionOrNull' )
			->willReturn( $expectedExtensionType );
		$tmpFileFactory = $this->createNoOpMock( TempFSFileFactory::class, [ 'newTempFSFile' ] );

		if ( !$expectedExtensionType && $fsPath ) {
			$tmpFileFactory->expects( $this->never() )->method( 'newTempFSFile' );
			$mimeAnalyzer->expects( $this->once() )->method( 'guessMimeType' )
				->with( $fsPath, false )->willReturn( $expectedGuessedMimeType );
		} elseif ( !$expectedExtensionType && $content !== null && $content !== '' ) {
			// XXX What should we do about the file creation here? Really we should mock
			// file_put_contents() somehow. It's not very nice to ignore the value of
			// $wgTmpDirectory.
			$tmpFile = ( new TempFSFileFactory() )->newTempFSFile( 'mime_', '' );

			$tmpFileFactory->expects( $this->once() )->method( 'newTempFSFile' )
				->with( 'mime_', '' )->willReturn( $tmpFile );
			$mimeAnalyzer->expects( $this->once() )->method( 'guessMimeType' )
				->with( $tmpFile->getPath(), false )->willReturn( $expectedGuessedMimeType );
		} else {
			$tmpFileFactory->expects( $this->never() )->method( 'newTempFSFile' );
			$mimeAnalyzer->expects( $this->never() )->method( 'guessMimeType' );
		}

		$obj = $this->newObj( [
			'mimeAnalyzer' => $mimeAnalyzer,
			'tmpFileFactory' => $tmpFileFactory,
		] );

		$this->assertSame( $expectedExtensionType ?? $expectedGuessedMimeType ?? 'unknown/unknown',
			$obj->guessMimeInternal( $storagePath, $content, $fsPath ) );
	}

	public static function provideGuessMimeInternal() {
		return [
			'With extension' =>
				[ 'foo.txt', null, null, 'text/plain', null ],
			'No extension' =>
				[ 'foo', null, null, null, null ],
			'Empty content, with extension' =>
				[ 'foo.txt', '', null, 'text/plain', null ],
			'Empty content, no extension' =>
				[ 'foo', '', null, null, null ],
			'Non-empty content, with extension' =>
				[ 'foo.txt', '<b>foo</b>', null, 'text/plain', null ],
			'Non-empty content, no extension' =>
				[ 'foo', '<b>foo</b>', null, null, 'text/html' ],
			'Empty path, with extension' =>
				[ 'foo.txt', null, '', 'text/plain', null ],
			'Empty path, no extension' =>
				[ 'foo', null, '', null, null ],
			'Non-empty path, with extension' =>
				[ 'foo.txt', null, '/bogus/path', 'text/plain', null ],
			'Non-empty path, no extension' =>
				[ 'foo', null, '/bogus/path', null, 'text/html' ],
			'Empty path and content, with extension' =>
				[ 'foo.txt', '', '', 'text/plain', null ],
			'Empty path and content, no extension' =>
				[ 'foo', '', '', null, null ],
			'Non-empty path and content, with extension' =>
				[ 'foo.txt', '<b>foo</b>', '/bogus/path', 'text/plain', null ],
			'Non-empty path and content, no extension' =>
				[ 'foo', '<b>foo</b>', '/bogus/path', null, 'image/jpeg' ],
		];
	}
}
