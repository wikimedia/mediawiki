<?php

use MediaWiki\Content\ContentHandlerFactory;
use MediaWiki\HookContainer\HookContainer;
use Psr\Log\LogLevel;
use Wikimedia\ObjectFactory\ObjectFactory;

/**
 * @group ContentHandlerFactory
 */
class ContentHandlerFactoryTest extends MediaWikiUnitTestCase {

	/** @var TestLogger */
	private $logger;

	protected function setUp(): void {
		parent::setUp();

		$this->logger = new TestLogger( false, static function ( $message, $level ) {
			return $level === LogLevel::INFO ? null : $message;
		} );
	}

	public function provideHandlerSpecs() {
		return [
			'typical list' => [
				[
					'ExistClassName' => DummyContentHandlerForTesting::class,
					'ExistCallbackWithExistClassName' => static function ( $modelID ) {
						return new DummyContentHandlerForTesting( $modelID );
					},
				],
				DummyContentHandlerForTesting::class,
			],
		];
	}

	/**
	 * @covers \MediaWiki\Content\ContentHandlerFactory::getContentHandler
	 * @covers \MediaWiki\Content\ContentHandlerFactory::createForModelID
	 * @covers \MediaWiki\Content\ContentHandlerFactory::createContentHandlerFromHandlerSpec
	 * @covers \MediaWiki\Content\ContentHandlerFactory::validateContentHandler
	 * @covers \MediaWiki\Content\ContentHandlerFactory::__construct
	 *
	 * @dataProvider provideHandlerSpecs $handlerSpecs
	 */
	public function testGetContentHandler_callWithProvider_same(
		array $handlerSpecs, string $contentHandlerClass
	): void {
		$contentHandlerExpected = new $contentHandlerClass( 'dummy' );
		$objectFactory = $this->createMock( ObjectFactory::class );
		$hookContainer = $this->createMock( HookContainer::class );

		$factory = new ContentHandlerFactory(
			$handlerSpecs,
			$objectFactory,
			$hookContainer,
			$this->logger
		);
		$i = 0;
		foreach ( $handlerSpecs as $modelID => $handlerSpec ) {
			$objectFactory
				->expects( $this->at( $i++ ) )
				->method( 'createObject' )
				->with( $handlerSpec,
					[
						'assertClass' => ContentHandler::class,
						'allowCallable' => true,
						'allowClassName' => true,
						'extraArgs' => [ $modelID ],
					] )
				->willReturn( $contentHandlerExpected );
		}

		foreach ( $handlerSpecs as $modelID => $handlerSpec ) {
			$this->assertSame( $contentHandlerExpected, $factory->getContentHandler( $modelID ) );
		}
	}

	/**
	 * @covers \MediaWiki\Content\ContentHandlerFactory::getContentHandler
	 * @covers \MediaWiki\Content\ContentHandlerFactory::createForModelID
	 * @covers \MediaWiki\Content\ContentHandlerFactory::createContentHandlerFromHook
	 * @covers \MediaWiki\Content\ContentHandlerFactory::validateContentHandler
	 *
	 * @dataProvider provideHandlerSpecs $handlerSpecs
	 */
	public function testGetContentHandler_hookWithProvider_same(
		array $handlerSpecs,
		string $contentHandlerClass
	): void {
		$contentHandlerExpected = new $contentHandlerClass( 'dummy' );
		$hookContainer = $this->createHookContainer();
		$factory = new ContentHandlerFactory(
			[],
			$this->createMock( ObjectFactory::class ),
			$hookContainer,
			$this->logger
		);

		foreach ( $handlerSpecs as $modelID => $handlerSpec ) {
			$this->assertFalse( $factory->isDefinedModel( $modelID ) );
			$contentHandler = null;
			$hookContainer->register( 'ContentHandlerForModelID',
				static function ( $handlerSpec, &$contentHandler ) use (
					$contentHandlerExpected
				) {
					$contentHandler = $contentHandlerExpected;

					return true;
				} );

			$contentHandler = $factory->getContentHandler( $modelID );
			$this->assertSame( $contentHandlerExpected, $contentHandler, $modelID );
			$this->assertTrue( $factory->isDefinedModel( $modelID ), $modelID );
		}
	}

	public function provideHandlerSpecsWithMWException(): array {
		return [
			'MWException expected' => [
				[
					'ExistCallbackWithWrongType' => static function () {
						return true;
					},
					'ExistCallbackWithNull' => static function () {
						return null;
					},
					'ExistCallbackWithEmptyString' => static function () {
						return '';
					},
					'WrongClassName' => self::class,
					'WrongType' => true,
					'NullType' => null,

				],
				MWException::class,
			],
			'Error expected' => [
				[
					'WrongClassNameNotExist' => 'ClassNameNotExist',
					'ExistCallbackWithNotExistClassName' => static function () {
						return ClassNameNotExist();
					},
					'EmptyString' => '',
				],
				Error::class,
			],
		];
	}

	/**
	 * @covers \MediaWiki\Content\ContentHandlerFactory::getContentHandler
	 * @covers \MediaWiki\Content\ContentHandlerFactory::createForModelID
	 * @covers \MediaWiki\Content\ContentHandlerFactory::createContentHandlerFromHandlerSpec
	 * @covers \MediaWiki\Content\ContentHandlerFactory::validateContentHandler
	 *
	 * @dataProvider provideHandlerSpecsWithMWException
	 */
	public function testCreateContentHandlerForModelID_callWithProvider_throwsException(
		array $handlerSpecs,
		string $exceptionClassName
	): void {
		/**
		 * @var Exception $exceptionExpected
		 */
		$objectFactory = $this->createMock( ObjectFactory::class );
		$objectFactory->method( 'createObject' )
			->willThrowException( $this->createMock( $exceptionClassName ) );
		$factory = new ContentHandlerFactory(
			$handlerSpecs,
			$objectFactory,
			$this->createMock( HookContainer::class ),
			$this->logger
		);

		foreach ( $handlerSpecs as $modelID => $handlerSpec ) {
			try {
				$factory->getContentHandler( $modelID );
				$this->fail();
			} catch ( \Throwable $exception ) {
				$this->assertInstanceOf( $exceptionClassName, $exception );
			}
		}
	}

	/**
	 * @covers \MediaWiki\Content\ContentHandlerFactory::createForModelID
	 * @covers \MediaWiki\Content\ContentHandlerFactory::createContentHandlerFromHandlerSpec
	 * @covers \MediaWiki\Content\ContentHandlerFactory::validateContentHandler
	 */
	public function testCreateContentHandlerForModelID_callNotExist_throwMWUCMException() {
		$this->expectException( MWUnknownContentModelException::class );
		$factory = new ContentHandlerFactory(
			[],
			$this->createMock( ObjectFactory::class ),
			$this->createMock( HookContainer::class ),
			$this->logger
		);
		$factory->getContentHandler( 'ModelNameNotExist' );
	}

	/**
	 * @covers \MediaWiki\Content\ContentHandlerFactory::defineContentHandler
	 * @covers \MediaWiki\Content\ContentHandlerFactory::getContentHandler
	 * @covers \MediaWiki\Content\ContentHandlerFactory::createForModelID
	 * @covers \MediaWiki\Content\ContentHandlerFactory::createContentHandlerFromHandlerSpec
	 * @covers \MediaWiki\Content\ContentHandlerFactory::validateContentHandler
	 * @covers \MediaWiki\Content\ContentHandlerFactory::isDefinedModel
	 */
	public function testDefineContentHandler_flow_throwsException() {
		$objectFactory = $this->createMock( ObjectFactory::class );
		$objectFactory
			->method( 'createObject' )
			->willReturn( $this->createMock( DummyContentHandlerForTesting::class ) );
		$factory = new ContentHandlerFactory(
			[],
			$objectFactory,
			$this->createMock( HookContainer::class ),
			$this->logger
		);
		$this->assertFalse( $factory->isDefinedModel( 'define test' ) );

		$factory->defineContentHandler( 'define test', DummyContentHandlerForTesting::class );
		$this->assertTrue( $factory->isDefinedModel( 'define test' ) );
		$this->assertInstanceOf(
			DummyContentHandlerForTesting::class,
			$factory->getContentHandler( 'define test' )
		);
	}

	/**
	 * @covers \MediaWiki\Content\ContentHandlerFactory::getContentModels
	 *
	 * @dataProvider provideValidDummySpecList
	 */
	public function testGetContentModels_flow_same(
		string $name1, string $name2, string $name3, string $name4
	): void {
		$hookContainer = $this->createHookContainer();
		$factory = new ContentHandlerFactory(
			[
				$name1 => DummyContentHandlerForTesting::class,
				$name2 => DummyContentHandlerForTesting::class,
			],
			$this->createMock( ObjectFactory::class ),
			$hookContainer,
			$this->logger
		);
		$this->assertArrayEquals(
			[ $name1, $name2, ],
			$factory->getContentModels() );

		$factory->defineContentHandler(
			$name3,
			static function () {
			}
		);

		$this->assertArrayEquals(
			[ $name1, $name2, $name3, ],
			$factory->getContentModels()
		);

		$hookContainer->register( 'GetContentModels',
			static function ( &$models ) use ( $name4 ) {
				$models[] = $name4;
			} );
		$this->assertArrayEquals(
			[ $name1, $name2, $name3, $name4, ],
			$factory->getContentModels()
		);
	}

	/**
	 * @covers \MediaWiki\Content\ContentHandlerFactory::isDefinedModel
	 * @covers \MediaWiki\Content\ContentHandlerFactory::createContentHandlerFromHook
	 * @dataProvider provideValidDummySpecList
	 */
	public function testIsDefinedModel_flow_same(
		string $name1, string $name2, string $name3, string $name4
	): void {
		$hookContainer = $this->createHookContainer();
		$factory = new ContentHandlerFactory(
			[
				$name1 => DummyContentHandlerForTesting::class,
				$name2 => DummyContentHandlerForTesting::class,
			],
			$this->createMock( ObjectFactory::class ),
			$hookContainer,
			$this->logger
		);

		$this->assertTrue( $factory->isDefinedModel( $name1 ) );
		$this->assertTrue( $factory->isDefinedModel( $name2 ) );
		$this->assertFalse( $factory->isDefinedModel( $name3 ) );
		$this->assertFalse( $factory->isDefinedModel( $name4 ) );
		$this->assertFalse( $factory->isDefinedModel( 'not exist name' ) );

		$factory->defineContentHandler(
			$name3,
			static function () {
			}
		);

		$this->assertTrue( $factory->isDefinedModel( $name1 ) );
		$this->assertTrue( $factory->isDefinedModel( $name2 ) );
		$this->assertTrue( $factory->isDefinedModel( $name3 ) );
		$this->assertFalse( $factory->isDefinedModel( $name4 ) );
		$this->assertFalse( $factory->isDefinedModel( 'not exist name' ) );

		$hookContainer->register(
			'GetContentModels',
			static function ( &$models ) use ( $name4 ) {
				$models[] = $name4;
			} );

		$this->assertTrue( $factory->isDefinedModel( $name1 ) );
		$this->assertTrue( $factory->isDefinedModel( $name2 ) );
		$this->assertTrue( $factory->isDefinedModel( $name3 ) );
		$this->assertTrue( $factory->isDefinedModel( $name4 ) );
		$this->assertFalse( $factory->isDefinedModel( 'not exist name' ) );
	}

	public function provideValidDummySpecList() {
		return [
			'1-0-3' => [
				'mock name 1',
				'mock name 0',
				'mock name 3',
				'mock name 4',
			],
		];
	}

	/**
	 * @covers \MediaWiki\Content\ContentHandlerFactory::getContentModels
	 */
	public function testGetContentModels_empty_empty() {
		$factory = new ContentHandlerFactory(
			[],
			$this->createMock( ObjectFactory::class ),
			$this->createMock( HookContainer::class ),
			$this->logger
		);

		$this->assertArrayEquals( [], $factory->getContentModels() );
	}

	/**
	 * @covers \MediaWiki\Content\ContentHandlerFactory::getAllContentFormats
	 * @covers \MediaWiki\Content\ContentHandlerFactory::defineContentHandler
	 */
	public function testGetAllContentFormats_flow_same() {
		$contentHandler1 = $this->createMock( DummyContentHandlerForTesting::class );
		$contentHandler1->method( 'getSupportedFormats' )->willReturn( [ 'format 1' ] );

		$contentHandler2 = $this->createMock( DummyContentHandlerForTesting::class );
		$contentHandler2->method( 'getSupportedFormats' )->willReturn( [ 'format 0' ] );

		$contentHandler3 = $this->createMock( DummyContentHandlerForTesting::class );
		$contentHandler3->method( 'getSupportedFormats' )->willReturn( [ 'format 3' ] );

		$objectFactory = $this->createMock( ObjectFactory::class );
		$objectFactory->expects( $this->at( 0 ) )
			->method( 'createObject' )
			->willReturn( $contentHandler1 );
		$objectFactory->expects( $this->at( 1 ) )
			->method( 'createObject' )
			->willReturn( $contentHandler2 );
		$objectFactory->expects( $this->at( 2 ) )
			->method( 'createObject' )
			->willReturn( $contentHandler3 );

		$factory = new ContentHandlerFactory(
			[
				'mock name 1' => static function () {
					// return new DummyContentHandlerForTesting( 'mock 1', [ 'format 1' ] );
				},
				'mock name 2' => static function () {
					// return new DummyContentHandlerForTesting( 'mock 0', [ 'format 0' ] );
				},
			],
			$objectFactory,
			$this->createMock( HookContainer::class ),
			$this->logger
		);

		$this->assertArrayEquals( [
				'format 1',
				'format 0',
			],
			$factory->getAllContentFormats() );

		$factory->defineContentHandler( 'some new name',
			static function () {
				// return new DummyContentHandlerForTesting( 'mock defined', [ 'format defined' ] );
			} );

		$this->assertArrayEquals( [
				'format 1',
				'format 0',
				'format 3',
			],
			$factory->getAllContentFormats() );
	}

}
