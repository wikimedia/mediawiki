<?php

use MediaWiki\FileBackend\FSFile\TempFSFileFactory;
use Wikimedia\TestingAccessWrapper;

/**
 * @coversDefaultClass FileBackend
 */
class FileBackendTest extends MediaWikiUnitTestCase {
	/**
	 * createMock() stubs out all methods, which isn't desirable for testing an abstract base class,
	 * since we often want to test that the base class calls certain methods that the derived class
	 * is meant to override. getMockBuilder() can be set to override only certain methods, but then
	 * you have to manually specify all abstract methods or else it doesn't work.
	 * getMockForAbstractClass() automatically fills in stubs for the abstract methods, but by
	 * default doesn't allow overriding any other methods. So we have to write our own.
	 *
	 * @param string|array ...$args Zero or more of the following:
	 *   - A nonempty associative array, interpreted as $config to be passed to the constructor. The
	 *     'name' and 'domainId' will be given default values if not present.
	 *   - A nonempty indexed array or a string, interpreted as a list of methods to override.
	 *   - An empty array, which is ignored.
	 * @return FileBackend A mock with no methods overridden except those specified in
	 *   $methodsToMock, and all abstract methods.
	 */
	private function newMockFileBackend( ...$args ) : FileBackend {
		$methodsToMock = [];
		$config = [];
		foreach ( $args as $arg ) {
			if ( is_string( $arg ) ) {
				$methodsToMock = [ $arg ];
			} elseif ( is_array( $arg ) ) {
				if ( isset( $arg[0] ) ) {
					$methodsToMock = $arg;
				} elseif ( $arg ) {
					$config = $arg;
				}
			} else {
				throw new InvalidArgumentException(
					'Arguments must be strings or nonempty arrays' );
			}
		}

		$config += [ 'name' => 'test_name' ];
		if ( !array_key_exists( 'wikiId', $config ) ) {
			$config += [ 'domainId' => '' ];
		}

		// getMockForAbstractClass has a lot of undocumented parameters that we need to set
		// https://github.com/sebastianbergmann/phpunit-mock-objects/blob/5.0.10/src/Generator.php#L268
		// TODO Would be better to use getMockBuilder and replace the un-overridden abstract methods
		// with something that throws.
		return $this->getMockForAbstractClass( FileBackend::class,
			/* $arguments */ [ $config ],
			/* $mockClassName */ '',
			/* $callOriginalConstructor */ true,
			/* $callOriginalClone */ false,
			/* $callAutoload */ true,
			/* $mockedMethods */ $methodsToMock,
			/* $cloneArguments */ false
		);
	}

	/**
	 * @covers ::__construct
	 * @dataProvider provideConstruct_validName
	 * @param mixed $name
	 */
	public function testConstruct_validName( $name ) : void {
		$this->newMockFileBackend( [ 'name' => $name ] );

		// No exception
		$this->assertTrue( true );
	}

	public static function provideConstruct_validName() : array {
		return [
			'True' => [ true ],
			'Positive integer' => [ 7 ],
			'Zero integer' => [ 0 ],
			'Zero float' => [ 0.0 ],
			'Negative integer' => [ -7 ],
			'Negative float' => [ -7.0 ],
			'255 chars is allowed' => [ str_repeat( 'a', 255 ) ],
		];
	}

	/**
	 * @covers ::__construct
	 * @dataProvider provideConstruct_invalidName
	 * @param mixed $name
	 */
	public function testConstruct_invalidName( $name ) : void {
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( "Backend name '$name' is invalid." );

		$this->newMockFileBackend( [ 'name' => $name, 'domainId' => false ] );
	}

	public static function provideConstruct_invalidName() : array {
		return [
			'Empty string' => [ '' ],
			'256 chars is too long' => [ str_repeat( 'a', 256 ) ],
			'!' => [ '!' ],
			'With space' => [ 'a b' ],
			'False' => [ false ],
			'Null' => [ null ],
			'Positive float' => [ 13.402 ],
			'Negative float' => [ -13.402 ],
		];
	}

	/**
	 * @covers ::__construct
	 */
	public function testConstruct_noName() : void {
		$this->expectException( PHPUnit\Framework\Error\Notice::class );
		$this->expectExceptionMessage( 'Undefined index: name' );

		$this->getMockBuilder( FileBackend::class )
			->setConstructorArgs( [ [] ] )
			->getMock();
	}

	/**
	 * @covers ::__construct
	 * @dataProvider provideConstruct_validDomainId
	 * @param string $domainId
	 */
	public function testConstruct_validDomainId( string $domainId ) : void {
		$this->newMockFileBackend( [ 'domainId' => $domainId ] );

		// No exception
		$this->assertTrue( true );
	}

	/**
	 * @covers ::__construct
	 * @dataProvider provideConstruct_validDomainId
	 * @param string $wikiId
	 */
	public function testConstruct_validWikiId( string $wikiId ) : void {
		$this->newMockFileBackend( [ 'wikiId' => $wikiId ] );

		// No exception
		$this->assertTrue( true );
	}

	public static function provideConstruct_validDomainId() : array {
		return [
			'Empty string' => [ '' ],
			'1000 chars' => [ str_repeat( 'a', 1000 ) ],
			'Null character' => [ "\0" ],
			'Invalid UTF-8' => [ "\xff" ],
		];
	}

	/**
	 * @covers ::__construct
	 * @dataProvider provideConstruct_invalidDomainId
	 * @param mixed $domainId
	 */
	public function testConstruct_invalidDomainId( $domainId ) : void {
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( "Backend domain ID not provided for 'test_name'." );

		$this->newMockFileBackend( [ 'domainId' => $domainId ] );
	}

	public static function provideConstruct_invalidDomainId() : array {
		return [
			// We don't include null because that will fall back to wikiId
			'False' => [ false ],
			'True' => [ true ],
			'Integer' => [ 7 ],
			'Function' => [ function () {
			} ],
			'Float' => [ -13.402 ],
			'Object' => [ new stdclass ],
			'Array' => [ [] ],
		];
	}

	/**
	 * @covers ::__construct
	 * @dataProvider provideConstruct_invalidWikiId
	 * @param mixed $wikiId
	 */
	public function testConstruct_invalidWikiId( $wikiId ) : void {
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( "Backend domain ID not provided for 'test_name'." );

		$this->newMockFileBackend( [ 'wikiId' => $wikiId ] );
	}

	public static function provideConstruct_invalidWikiId() : array {
		return [
			'Null' => [ null ],
		] + self::provideConstruct_invalidDomainId();
	}

	/**
	 * @covers ::__construct
	 */
	public function testConstruct_noDomainId() : void {
		$this->expectException( PHPUnit\Framework\Error\Notice::class );
		$this->expectExceptionMessage( 'Undefined index: wikiId' );

		$this->getMockBuilder( FileBackend::class )
			->setConstructorArgs( [ [ 'name' => 'test_name' ] ] )
			->getMock();
	}

	/**
	 * @covers ::__construct
	 * @dataProvider provideConstruct_properties
	 * @param string $property
	 * @param mixed $expected
	 * @param array $config Can also include the key 'inexact' to tell us to not check equality
	 *   strictly.
	 */
	public function testConstruct_properties(
		string $property, $expected, array $config = []
	) : void {
		$backend = $this->newMockFileBackend( $config );

		if ( $expected instanceof Closure ) {
			$expected = $expected( $backend );
		}

		$assertMethod = isset( $config['inexact'] ) ? 'assertEquals' : 'assertSame';
		unset( $config['inexact'] );

		// We need to test this for the sake of subclasses that actually use the property. There
		// doesn't seem to be any better way to do it. It shouldn't be tested in the subclasses,
		// because we're testing the behavior of this class' constructor. We could make our own
		// subclass, but we'd have to stub 26 abstract methods.
		$this->$assertMethod( $expected,
			TestingAccessWrapper::newFromObject( $backend )->$property );
	}

	public static function provideConstruct_properties() : array {
		$tmpFileFactory = new TempFSFileFactory( 'some_unique_path' );

		return [
			'parallelize default value' => [ 'parallelize', 'off' ],
			'parallelize null' => [ 'parallelize', 'off', [ 'parallelize' => null ] ],
			'parallelize cast to string' => [ 'parallelize', '1', [ 'parallelize' => true ] ],
			'parallelize case-preserving' =>
				[ 'parallelize', 'iMpLiCiT', [ 'parallelize' => 'iMpLiCiT' ] ],

			'concurrency default value' => [ 'concurrency', 50 ],
			'concurrency null' => [ 'concurrency', 50, [ 'concurrency' => null ] ],
			'concurrency cast to int' => [ 'concurrency', 51, [ 'concurrency' => '51x' ] ],

			'obResetFunc default value' => [ 'obResetFunc',
				// I'd've thought the return type should be 'callable', but apparently protected
				// methods aren't callable.
				function ( FileBackend $backend ) : array {
					return [ $backend, 'resetOutputBuffer' ];
				} ],
			'obResetFunc null' => [ 'obResetFunc',
				function ( FileBackend $backend ) : array {
					return [ $backend, 'resetOutputBuffer' ];
				} ],
			'obResetFunc set' => [ 'obResetFunc', 'wfSomeImaginaryFunction',
				[ 'obResetFunc' => 'wfSomeImaginaryFunction' ] ],

			'streamMimeFunc default value' => [ 'streamMimeFunc', null ],
			'streamMimeFunc set' => [ 'streamMimeFunc', 'smf', [ 'streamMimeFunc' => 'smf' ] ],

			'profiler default value' => [ 'profiler', null ],
			'profiler callable' => [ 'profiler', 'strtr', [ 'profiler' => 'strtr' ] ],
			'profiler not callable' => [ 'profiler', null, [ 'profiler' => '!' ] ],

			'logger default value' => [ 'logger', new Psr\Log\NullLogger, [ 'inexact' => true ] ],
			'logger set' => [ 'logger', 'abcd', [ 'logger' => 'abcd' ] ],

			'statusWrapper default value' => [ 'statusWrapper', null ],
			'statusWrapper set' => [ 'statusWrapper', 'stat', [ 'statusWrapper' => 'stat' ] ],

			'tmpFileFactory default value' =>
				[ 'tmpFileFactory', new TempFSFileFactory, [ 'inexact' => true ] ],
			'tmpDirectory null' => [ 'tmpFileFactory', new TempFSFileFactory,
				[ 'tmpDirectory' => null, 'inexact' => true ] ],
			'tmpDirectory set' => [ 'tmpFileFactory', new TempFSFileFactory( 'dir' ),
				[ 'tmpDirectory' => 'dir', 'inexact' => true ] ],
			'tmpFileFactory null' => [ 'tmpFileFactory', new TempFSFileFactory,
				[ 'tmpFileFactory' => null, 'inexact' => true ] ],
			'tmpFileFactory set' => [ 'tmpFileFactory', $tmpFileFactory,
				[ 'tmpFileFactory' => $tmpFileFactory ] ],
			'tmpDirectory and tmpFileFactory set' => [
				'tmpFileFactory',
				new TempFSFileFactory( 'dir' ),
				[ 'tmpDirectory' => 'dir', 'tmpFileFactory' => $tmpFileFactory, 'inexact' => true ],
			],
			'tmpDirectory null and tmpFileFactory set' => [ 'tmpFileFactory', $tmpFileFactory,
				[ 'tmpDirectory' => null, 'tmpFileFactory' => $tmpFileFactory ] ],
		];
	}

	/**
	 * @covers ::__construct
	 * @covers ::getName
	 */
	public function testGetName() : void {
		$backend = $this->newMockFileBackend();
		$this->assertSame( 'test_name', $backend->getName() );
	}

	/**
	 * @covers ::__construct
	 * @covers ::getDomainId
	 * @dataProvider provideGetDomainId
	 * @param array $config
	 */
	public function testGetDomainId( array $config ) : void {
		$backend = $this->newMockFileBackend( $config );
		$this->assertSame( 'test_domain', $backend->getDomainId() );
	}

	/**
	 * @covers ::__construct
	 * @covers ::getWikiId
	 * @dataProvider provideGetDomainId
	 * @param array $config
	 */
	public function testGetWikiId( array $config ) : void {
		$backend = $this->newMockFileBackend( $config );
		$this->assertSame( 'test_domain', $backend->getWikiId() );
	}

	public static function provideGetDomainId() : array {
		return [
			'Only domainId' => [ [ 'domainId' => 'test_domain' ] ],
			'Only wikiId' => [ [ 'wikiId' => 'test_domain' ] ],
			'null domainId' => [ [ 'domainId' => null, 'wikiId' => 'test_domain' ] ],
			'wikiId is ignored if domainId is present' =>
				[ [ 'domainId' => 'test_domain', 'wikiId' => 'other_domain' ] ],
		];
	}

	/**
	 * @covers ::__construct
	 * @covers ::isReadOnly
	 * @covers ::getReadOnlyReason
	 */
	public function testIsReadOnly_default() : void {
		$backend = $this->newMockFileBackend();
		$this->assertFalse( $backend->isReadOnly() );
		$this->assertFalse( $backend->getReadOnlyReason() );
	}

	/**
	 * @covers ::__construct
	 * @covers ::isReadOnly
	 * @covers ::getReadOnlyReason
	 */
	public function testIsReadOnly() : void {
		$backend = $this->newMockFileBackend( [ 'readOnly' => '.' ] );
		$this->assertTrue( $backend->isReadOnly() );
		$this->assertSame( '.', $backend->getReadOnlyReason() );
	}

	/**
	 * @covers ::getFeatures
	 */
	public function testGetFeatures() : void {
		$backend = $this->newMockFileBackend();
		$this->assertSame( FileBackend::ATTR_UNICODE_PATHS, $backend->getFeatures() );
	}

	/**
	 * @covers ::hasFeatures
	 * @dataProvider provideHasFeatures
	 * @param bool $expected
	 * @param int $testedFeatures
	 * @param int $actualFeatures
	 */
	public function testHasFeatures(
		bool $expected, int $actualFeatures, int $testedFeatures
	) : void {
		$backend = $this->createMock( FileBackend::class );
		$backend->method( 'getFeatures' )->willReturn( $actualFeatures );

		$this->assertSame( $expected, $backend->hasFeatures( $testedFeatures ) );
	}

	public static function provideHasFeatures() : array {
		return [
			'Nothing has nothing' => [ true, 0, 0 ],
			"Nothing doesn't have something" => [ false, 0, 1 ],
			'Something has nothing' => [ true, 1, 0 ],
			'Something has itself' => [ true, 1, 1 ],
			"Something doesn't have something else" => [ false, 0b01, 0b10 ],
			"Something doesn't have itself and something else" => [ false, 0b01, 0b11 ],
			'Two things have the first one' => [ true, 0b11, 0b01 ],
			'Two things have the second one' => [ true, 0b11, 0b10 ],
			'Two things have both' => [ true, 0b11, 0b11 ],
			"Two things don't have a third" => [ false, 0b11, 0b100 ],
		];
	}

	/**
	 * @covers ::doOperations
	 * @covers ::doOperation
	 * @covers ::doQuickOperations
	 * @covers ::doQuickOperation
	 * @covers ::prepare
	 * @covers ::secure
	 * @covers ::publish
	 * @covers ::clean
	 * @dataProvider provideReadOnly
	 * @param string $method
	 */
	public function testReadOnly( string $method ) : void {
		$backend = $this->newMockFileBackend( [ 'readOnly' => '.' ] );
		$status = $backend->$method( [] );
		$this->assertSame( [ [
			'type' => 'error',
			'message' => 'backend-fail-readonly',
			'params' => [ 'test_name', '.' ],
		] ], $status->getErrors() );
		$this->assertFalse( $status->isOK() );
	}

	public static function provideReadOnly() : array {
		return [
			'doOperations' => [ 'doOperations', 'doOperationsInternal', [ [ [] ] ] ],
			'doOperation' => [ 'doOperation', 'doOperationsInternal', [ [ 'op' => '' ] ] ],
			'doQuickOperations' => [ 'doQuickOperations', 'doQuickOperationsInternal', [ [ [] ] ] ],
			'doQuickOperation' => [
				'doQuickOperation',
				'doQuickOperationsInternal',
				[ [ 'op' => '' ] ]
			],
			'prepare' => [ 'prepare', 'doPrepare' ],
			'secure' => [ 'secure', 'doSecure' ],
			'publish' => [ 'publish', 'doPublish' ],
			'clean' => [ 'clean', 'doClean' ],
		];
	}

	/**
	 * @covers ::doOperations
	 * @covers ::doOperation
	 * @covers ::doQuickOperations
	 * @covers ::doQuickOperation
	 * @covers ::prepare
	 * @covers ::secure
	 * @covers ::publish
	 * @covers ::clean
	 * @dataProvider provideReadOnly
	 * @param string $method Method to call
	 * @param string $internalMethod Internal method the call will be forwarded to
	 * @param array $args To be passed to $method before a final argument of
	 *   [ 'bypassReadOnly' => true ]
	 */
	public function testDoOperations_bypassReadOnly(
		string $method, string $internalMethod, array $args = []
	) : void {
		$backend = $this->newMockFileBackend( [ 'readOnly' => '.' ], $internalMethod );
		$backend->expects( $this->once() )->method( $internalMethod )
			->willReturn( StatusValue::newGood( 'myvalue' ) );

		$status = $backend->$method( ...array_merge( $args, [ [ 'bypassReadOnly' => true ] ] ) );

		$this->assertTrue( $status->isOK() );
		$this->assertEmpty( $status->getErrors() );
		$this->assertSame( 'myvalue', $status->getValue() );
	}

	/**
	 * @covers ::doOperations
	 * @covers ::doQuickOperations
	 * @dataProvider provideDoMultipleOperations
	 * @param string $method
	 */
	public function testDoOperations_noOp( string $method ) : void {
		$backend = $this->newMockFileBackend(
			[ 'doOperationsInternal', 'doQuickOperationsInternal' ] );
		$backend->expects( $this->never() )->method( 'doOperationsInternal' );
		$backend->expects( $this->never() )->method( 'doQuickOperationsInternal' );

		$status = $backend->$method( [] );
		$this->assertTrue( $status->isOK() );
		$this->assertEmpty( $status->getErrors() );
	}

	public static function provideDoMultipleOperations() : array {
		return [
			'doOperations' => [ 'doOperations' ],
			'doQuickOperations' => [ 'doQuickOperations' ],
		];
	}

	/**
	 * @covers ::doOperations
	 * @covers ::doOperation
	 * @dataProvider provideDoOperations
	 * @param string $method 'doOperation' or 'doOperations'
	 */
	public function testDoOperations_nonLockingNoForce( string $method ) : void {
		$backend = $this->newMockFileBackend( [ 'doOperationsInternal' ] );
		$backend->expects( $this->once() )->method( 'doOperationsInternal' )
			->with( [ [] ], [] );
		$backend->$method( $method === 'doOperation' ? [] : [ [] ], [ 'nonLocking' => true ] );
	}

	public static function provideDoOperations() : array {
		return [
			'doOperations' => [ 'doOperations' ],
			'doOperation' => [ 'doOperation' ],
		];
	}

	/**
	 * @covers ::doOperations
	 * @covers ::doOperation
	 * @dataProvider provideDoOperations
	 * @param string $method 'doOperation' or 'doOperations'
	 */
	public function testDoOperations_nonLockingForce( string $method ) : void {
		$backend = $this->newMockFileBackend( [ 'doOperationsInternal' ] );
		$backend->expects( $this->once() )->method( 'doOperationsInternal' )
			->with( [ [] ], [ 'nonLocking' => true, 'force' => true ] );
		$backend->$method(
			$method === 'doOperation' ? [] : [ [] ],
			[ 'nonLocking' => true, 'force' => true ]
		);
	}

	// XXX Can't test newScopedIgnoreUserAbort() because it's a no-op in CLI

	/**
	 * @covers ::create
	 * @covers ::store
	 * @covers ::copy
	 * @covers ::move
	 * @covers ::delete
	 * @covers ::describe
	 * @covers ::quickCreate
	 * @covers ::quickStore
	 * @covers ::quickCopy
	 * @covers ::quickMove
	 * @covers ::quickDelete
	 * @covers ::quickDescribe
	 * @dataProvider provideAction
	 * @param string $prefix '' or 'quick'
	 * @param string $action
	 */
	public function testAction( string $prefix, string $action ) : void {
		$backend = $this->newMockFileBackend( 'do' . ucfirst( $prefix ) . 'OperationsInternal' );
		$expectedOp = [ 'op' => $action, 'foo' => 'bar' ];
		if ( $prefix === 'quick' ) {
			$expectedOp['overwrite'] = true;
		}
		$backend->expects( $this->once() )
			->method( 'do' . ucfirst( $prefix ) . 'OperationsInternal' )
			->with( [ $expectedOp ], [ 'baz' => 'quuz' ] )
			->willReturn( StatusValue::newGood( 'myvalue' ) );

		$method = $prefix ? $prefix . ucfirst( $action ) : $action;
		$status = $backend->$method( [ 'op' => 'ignored', 'foo' => 'bar' ], [ 'baz' => 'quuz' ] );

		$this->assertTrue( $status->isOK() );
		$this->assertSame( 'myvalue', $status->getValue() );
	}

	public static function provideAction() : array {
		$ret = [];
		foreach ( [ '', 'quick' ] as $prefix ) {
			foreach ( [ 'create', 'store', 'copy', 'move', 'delete', 'describe' ] as $action ) {
				$key = $prefix ? $prefix . ucfirst( $action ) : $action;
				$ret[$key] = [ $prefix, $action ];
			}
		}
		return $ret;
	}

	/**
	 * @covers ::prepare
	 * @covers ::secure
	 * @covers ::publish
	 * @covers ::clean
	 * @dataProvider provideForwardToDo
	 * @param string $method
	 */
	public function testForwardToDo( string $method ) : void {
		$backend = $this->newMockFileBackend( 'do' . ucfirst( $method ) );
		$backend->expects( $this->once() )->method( 'do' . ucfirst( $method ) )
			->with( [ 'foo' => 'bar' ] )
			->willReturn( StatusValue::newGood( 'myvalue' ) );

		$status = $backend->$method( [ 'foo' => 'bar' ] );

		$this->assertTrue( $status->isOK() );
		$this->assertEmpty( $status->getErrors() );
		$this->assertSame( 'myvalue', $status->getValue() );
	}

	public static function provideForwardToDo() : array {
		return [
			'prepare' => [ 'prepare' ],
			'secure' => [ 'secure' ],
			'publish' => [ 'publish' ],
			'clean' => [ 'clean' ],
		];
	}

	/**
	 * @covers ::getFileContents
	 * @covers ::getLocalReference
	 * @covers ::getLocalCopy
	 * @dataProvider provideForwardToMulti
	 * @param string $method
	 */
	public function testForwardToMulti( string $method ) : void {
		$backend = $this->newMockFileBackend( "{$method}Multi" );
		$backend->expects( $this->once() )->method( "{$method}Multi" )
			->with( [ 'srcs' => [ 'mysrc' ], 'foo' => 'bar', 'src' => 'mysrc' ] )
			->willReturn( [ 'mysrc' => 'mycontents' ] );

		$result = $backend->$method( [ 'srcs' => 'ignored', 'foo' => 'bar', 'src' => 'mysrc' ] );

		$this->assertSame( 'mycontents', $result );
	}

	public static function provideForwardToMulti() : array {
		return [
			'getFileContents' => [ 'getFileContents' ],
			'getLocalReference' => [ 'getLocalReference' ],
			'getLocalCopy' => [ 'getLocalCopy' ],
		];
	}

	/**
	 * @covers ::getTopDirectoryList
	 * @covers ::getTopFileList
	 * @dataProvider provideForwardFromTop
	 * @param string $methodSuffix
	 */
	public function testForwardFromTop( string $methodSuffix ) : void {
		$backend = $this->newMockFileBackend( "get$methodSuffix" );
		$backend->expects( $this->once() )->method( "get$methodSuffix" )
			->with( [ 'topOnly' => true, 'foo' => 'bar' ] )
			->willReturn( [ 'something' ] );

		$method = "getTop$methodSuffix";
		$result = $backend->$method( [ 'topOnly' => 'ignored', 'foo' => 'bar' ] );

		$this->assertSame( [ 'something' ], $result );
	}

	public static function provideForwardFromTop() : array {
		return [
			'getTopDirectoryList' => [ 'DirectoryList' ],
			'getTopFileList' => [ 'FileList' ],
		];
	}

	/**
	 * @covers ::__construct
	 * @covers ::lockFiles
	 * @covers ::unlockFiles
	 * @dataProvider provideLockUnlockFiles
	 * @param string $method
	 * @param int $timeout Only relevant for lockFiles
	 */
	public function testLockUnlockFiles( string $method, ?int $timeout = null ) : void {
		// TODO Test that normalizeStoragePath is being called
		$args = [ [ 'mwstore://a/b', 'mwstore://c/d/e' ], LockManager::LOCK_SH ];

		$mockLm = $this->getMockBuilder( LockManager::class )
			->disableOriginalConstructor()
			->setMethods( [ 'do' . ucfirst( $method ) . 'ByType', 'doLock', 'doUnlock' ] )
			->getMock();
		// XXX PHPUnit can't override final methods (T231419)
		//$mockLm->expects( $this->once() )->method( $method )
		//	->with( ...array_merge( $args, [ $timeout ?? 0 ] ) )
		//	->willReturn( StatusValue::newGood( 'myvalue' ) );
		//$mockLm->expects( $this->never() )->method( $this->anythingBut( $method ) );
		$mockLm->expects( $this->once() )->method( 'do' . ucfirst( $method ) . 'ByType' )
			->with( [ LockManager::LOCK_SH => [ 'mwstore://a/b', 'mwstore://c/d/e' ] ] )
			->willReturn( StatusValue::newGood( 'myvalue' ) );

		$backend = $this->newMockFileBackend( [ 'lockManager' => $mockLm ] );
		$backendMethod = "{$method}Files";

		$status = $backend->$backendMethod( ...array_merge( $args, (array)$timeout ) );

		$this->assertTrue( $status->isOK() );
		$this->assertEmpty( $status->getErrors() );
		$this->assertSame( 'myvalue', $status->getValue() );
	}

	public static function provideLockUnlockFiles() : array {
		return [
			[ 'lock' ],
			[ 'lock', 731 ],
			[ 'unlock' ],
		];
	}

	/**
	 * @covers ::__construct
	 * @covers ::getRootStoragePath
	 * @dataProvider provideConstruct_validName
	 * @param mixed $name
	 */
	public function testGetRootStoragePath( $name ) : void {
		$backend = $this->newMockFileBackend( [ 'name' => $name ] );
		$this->assertSame( "mwstore://$name", $backend->getRootStoragePath() );
	}

	/**
	 * @covers ::__construct
	 * @covers ::getContainerStoragePath
	 * @dataProvider provideConstruct_validName
	 * @param mixed $name
	 */
	public function testGetContainerStoragePath( $name ) : void {
		$backend = $this->newMockFileBackend( [ 'name' => $name ] );
		$this->assertSame( "mwstore://$name/mycontainer",
			$backend->getContainerStoragePath( 'mycontainer' ) );
	}

	/**
	 * @covers ::__construct
	 * @covers ::getJournal
	 */
	public function testGetFileJournal_default() : void {
		$backend = $this->newMockFileBackend();
		$this->assertEquals( new NullFileJournal, $backend->getJournal() );
	}

	/**
	 * @covers ::__construct
	 * @covers ::getJournal
	 */
	public function testGetJournal() : void {
		$mockJournal = $this->createNoOpMock( FileJournal::class );
		$backend = $this->newMockFileBackend( [ 'fileJournal' => $mockJournal ] );
		$this->assertSame( $mockJournal, $backend->getJournal() );
	}

	/**
	 * @covers ::doOperations
	 * @covers ::doOperation
	 * @covers ::resolveFSFileObjects
	 * @dataProvider provideDoOperations
	 * @param string $method 'doOperation' or 'doOperations'
	 */
	public function testResolveFSFileObjects( string $method ) : void {
		$tmpFile = ( new TempFSFileFactory )->newTempFSFile( 'a' );

		$backend = $this->newMockFileBackend( 'doOperationsInternal' );
		$backend->expects( $this->once() )->method( 'doOperationsInternal' )
			->with( [ [ 'src' => $tmpFile->getPath(), 'srcRef' => $tmpFile ] ] )
			->willReturn( StatusValue::newGood() );

		$op = [ 'src' => $tmpFile ];
		if ( $method === 'doOperations' ) {
			$op = [ $op ];
		}
		$status = $backend->$method( $op );

		$this->assertTrue( $status->isOK() );
		$this->assertEmpty( $status->getErrors() );
	}

	/**
	 * @covers ::doOperations
	 * @covers ::doOperation
	 * @covers ::resolveFSFileObjects
	 * @dataProvider provideDoOperations
	 * @param string $method 'doOperation' or 'doOperations'
	 */
	public function testResolveFSFileObjects_preservesTempFiles( string $method ) : void {
		$tmpFile = ( new TempFSFileFactory )->newTempFSFile( 'a' );
		$path = $tmpFile->getPath();

		$backend = $this->newMockFileBackend();

		$op = [ 'src' => $tmpFile ];
		if ( $method === 'doOperations' ) {
			$op = [ $op ];
		}
		$status = $backend->$method( $op );

		$this->assertTrue( file_exists( $path ) );
	}
}
