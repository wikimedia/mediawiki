<?php

/**
 * @group Templates
 * @coversDefaultClass TemplateParser
 */
class TemplateParserIntegrationTest extends MediaWikiIntegrationTestCase {

	private const NAME = 'foobar';
	private const RESULT = "hello world!\n";
	private const DIR = __DIR__ . '/../../data/templates';
	private const SECRET_KEY = 'foo';

	protected function setUp() : void {
		parent::setUp();

		$this->setMwGlobals( [
			'wgSecretKey' => self::SECRET_KEY,
		] );
	}

	/**
	 * @covers ::getTemplate
	 */
	public function testGetTemplateNeverCacheWithoutSecretKey() {
		$this->setMwGlobals( 'wgSecretKey', false );

		// Expect no cache interaction
		$cache = $this->createMock( BagOStuff::class );
		$cache->expects( $this->never() )->method( 'get' );
		$cache->expects( $this->never() )->method( 'set' );

		$tp = new TemplateParser( self::DIR, $cache );
		$this->assertEquals( self::RESULT, $tp->processTemplate( self::NAME, [] ) );
	}

	/**
	 * @covers ::getTemplate
	 */
	public function testGetTemplateCachesCompilationResult() {
		$store = null;

		// 1. Expect a cache miss, compile, and cache set
		$cache1 = $this->createMock( BagOStuff::class );
		$cache1->expects( $this->once() )->method( 'get' )->willReturn( false );
		$cache1->expects( $this->once() )->method( 'set' )
			->will( $this->returnCallback( function ( $key, $val ) use ( &$store ) {
				$store = [ 'key' => $key, 'val' => $val ];
			} ) );

		$tp1 = new TemplateParser( self::DIR, $cache1 );
		$this->assertEquals( self::RESULT, $tp1->processTemplate( self::NAME, [] ) );

		// Inspect cache
		$this->assertEquals(
			[
				'phpCode',
				'files',
				'filesHash',
				'integrityHash',
			],
			array_keys( $store['val'] ),
			'keys of the cached array'
		);
		$this->assertEquals(
			FileContentsHasher::getFileContentsHash( [
				self::DIR . '/' . self::NAME . '.mustache'
			] ),
			$store['val']['filesHash'],
			'content hash for the compiled template'
		);
		$this->assertEquals(
			hash_hmac( 'sha256', $store['val']['phpCode'], self::SECRET_KEY ),
			$store['val']['integrityHash'],
			'integrity hash for the compiled template'
		);

		// 2. Expect a cache hit that passes validation checks, and no compilation
		$cache2 = $this->createMock( BagOStuff::class );
		$cache2->expects( $this->once() )->method( 'get' )
			->will( $this->returnCallback( function ( $key ) use ( &$store ) {
				return $key === $store['key'] ? $store['val'] : false;
			} ) );
		$cache2->expects( $this->never() )->method( 'set' );

		$tp2 = $this->getMockBuilder( TemplateParser::class )
			->setConstructorArgs( [ self::DIR, $cache2 ] )
			->setMethods( [ 'compile' ] )
			->getMock();
		$tp2->expects( $this->never() )->method( 'compile' );

		$this->assertEquals( self::RESULT, $tp2->processTemplate( self::NAME, [] ) );
	}

	/**
	 * @covers ::getTemplate
	 */
	public function testGetTemplateInvalidatesCacheWhenFilesHashIsInvalid() {
		$store = null;

		// 1. Expect a cache miss, compile, and cache set
		$cache1 = $this->createMock( BagOStuff::class );
		$cache1->expects( $this->once() )->method( 'get' )->willReturn( false );
		$cache1->expects( $this->once() )->method( 'set' )
			->will( $this->returnCallback( function ( $key, $val ) use ( &$store ) {
				$store = [ 'key' => $key, 'val' => $val ];
			} ) );

		$tp1 = new TemplateParser( self::DIR, $cache1 );
		$this->assertEquals( self::RESULT, $tp1->processTemplate( self::NAME, [] ) );

		// Invalidate file hash
		$store['val']['filesHash'] = 'baz';

		// 2. Expect a cache hit that fails validation, and a re-compilation
		$cache2 = $this->createMock( BagOStuff::class );
		$cache2->expects( $this->once() )->method( 'get' )
			->will( $this->returnCallback( function ( $key ) use ( &$store ) {
				return $key === $store['key'] ? $store['val'] : false;
			} ) );
		$cache2->expects( $this->once() )->method( 'set' );

		$tp2 = $this->getMockBuilder( TemplateParser::class )
			->setConstructorArgs( [ self::DIR, $cache2 ] )
			->setMethods( [ 'compile' ] )
			->getMock();
		$tp2->expects( $this->once() )->method( 'compile' )
			->willReturn( $store['val'] );

		$this->assertEquals( self::RESULT, $tp2->processTemplate( self::NAME, [] ) );
	}

	/**
	 * @covers ::getTemplate
	 */
	public function testGetTemplateInvalidatesCacheWhenIntegrityHashIsInvalid() {
		$store = null;

		// 1. Cache miss, expect a compile and cache set
		$cache1 = $this->createMock( BagOStuff::class );
		$cache1->expects( $this->once() )->method( 'get' )->willReturn( false );
		$cache1->expects( $this->once() )->method( 'set' )
			->will( $this->returnCallback( function ( $key, $val ) use ( &$store ) {
				$store = [ 'key' => $key, 'val' => $val ];
			} ) );

		$tp1 = new TemplateParser( self::DIR, $cache1 );
		$this->assertEquals( self::RESULT, $tp1->processTemplate( self::NAME, [] ) );

		// Invalidate integrity hash
		$store['val']['integrityHash'] = 'foo';

		// 2. Expect a cache hit that fails validation, and a re-compilation
		$cache2 = $this->createMock( BagOStuff::class );
		$cache2->expects( $this->once() )->method( 'get' )
			->will( $this->returnCallback( function ( $key ) use ( &$store ) {
				return $key === $store['key'] ? $store['val'] : false;
			} ) );
		$cache2->expects( $this->once() )->method( 'set' );

		$tp2 = $this->getMockBuilder( TemplateParser::class )
			->setConstructorArgs( [ self::DIR, $cache2 ] )
			->setMethods( [ 'compile' ] )
			->getMock();
		$tp2->expects( $this->once() )->method( 'compile' )
			->willReturn( $store['val'] );

		$this->assertEquals( self::RESULT, $tp2->processTemplate( self::NAME, [] ) );
	}

	/**
	 * @dataProvider provideProcessTemplate
	 * @covers TemplateParser
	 */
	public function testProcessTemplate( $name, $args, $result, $exception = false ) {
		$tp = new TemplateParser( self::DIR, new EmptyBagOStuff );
		if ( $exception ) {
			$this->expectException( $exception );
		}
		$this->assertEquals( $result, $tp->processTemplate( $name, $args ) );
	}

	public static function provideProcessTemplate() {
		return [
			[
				'foobar',
				[],
				"hello world!\n"
			],
			[
				'foobar_args',
				[
					'planet' => 'world',
				],
				self::RESULT,
			],
			[
				'../foobar',
				[],
				false,
				UnexpectedValueException::class
			],
			[
				"\000../foobar",
				[],
				false,
				UnexpectedValueException::class
			],
			[
				'/',
				[],
				false,
				UnexpectedValueException::class
			],
			[
				// Allegedly this can strip ext in windows.
				'baz<',
				[],
				false,
				UnexpectedValueException::class
			],
			[
				'\\foo',
				[],
				false,
				UnexpectedValueException::class
			],
			[
				'C:\bar',
				[],
				false,
				UnexpectedValueException::class
			],
			[
				"foo\000bar",
				[],
				false,
				UnexpectedValueException::class
			],
			[
				'nonexistenttemplate',
				[],
				false,
				RuntimeException::class,
			],
			[
				'has_partial',
				[
					'planet' => 'world',
				],
				"Partial hello world!\n in here\n",
			],
			[
				'bad_partial',
				[],
				false,
				Exception::class,
			],
			[
				'invalid_syntax',
				[],
				false,
				Exception::class
			],
			[
				'parentvars',
				[
					'foo' => 'f',
					'bar' => [
						[ 'baz' => 'x' ],
						[ 'baz' => 'y' ]
					]
				],
				"f\n\tf x\n\tf y\n"
			]
		];
	}

	/**
	 * @covers ::enableRecursivePartials
	 */
	public function testEnableRecursivePartials() {
		$tp = new TemplateParser( self::DIR, new EmptyBagOStuff );
		$data = [ 'r' => [ 'r' => [ 'r' => [] ] ] ];

		$tp->enableRecursivePartials( true );
		$this->assertEquals( 'rrr', $tp->processTemplate( 'recurse', $data ) );

		$tp->enableRecursivePartials( false );
		$this->expectException( Exception::class );
		$tp->processTemplate( 'recurse', $data );
	}

	/**
	 * @covers TemplateParser::compile
	 */
	public function testCompileReturnsPHPCodeAndMetadata() {
		$store = null;

		// 1. Expect a compile and cache set
		$cache = $this->createMock( BagOStuff::class );
		$cache->expects( $this->once() )->method( 'get' )->willReturn( false );
		$cache->expects( $this->once() )->method( 'set' )
			->will( $this->returnCallback( function ( $key, $val ) use ( &$store ) {
				$store = [ 'key' => $key, 'val' => $val ];
			} ) );
		$tp = new TemplateParser( self::DIR, $cache );
		$tp->processTemplate( 'has_partial', [] );

		// 2. Inspect cache
		$expectedFiles = [
			self::DIR . '/has_partial.mustache',
			self::DIR . '/foobar_args.mustache',
		];
		$this->assertEquals(
			$expectedFiles,
			$store['val']['files'],
			'track all files read during the compilation'
		);
		$this->assertEquals(
			FileContentsHasher::getFileContentsHash( $expectedFiles ),
			$store['val'][ 'filesHash' ],
			'hash of all files read during the compilation'
		);
	}
}
