<?php

namespace MediaWiki\Tests\Unit\Content;

use DummyContentHandlerForTesting;
use Error;
use InvalidArgumentException;
use MediaWiki\Content\ContentHandler;
use MediaWiki\Content\ContentHandlerFactory;
use MediaWiki\Exception\MWUnknownContentModelException;
use MediaWiki\HookContainer\HookContainer;
use MediaWikiUnitTestCase;
use Psr\Log\LogLevel;
use TestLogger;
use UnexpectedValueException;
use Wikimedia\ObjectFactory\ObjectFactory;

/**
 * @group ContentHandlerFactory
 * @covers \MediaWiki\Content\ContentHandlerFactory
 */
class ContentHandlerFactoryTest extends MediaWikiUnitTestCase {

	private TestLogger $logger;

	protected function setUp(): void {
		parent::setUp();

		$this->logger = new TestLogger( false, static function ( $message, $level ) {
			return $level === LogLevel::INFO ? null : $message;
		} );
	}

	public static function provideHandlerSpecs() {
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
	 * @dataProvider provideHandlerSpecs
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

		$returnMap = [];
		foreach ( $handlerSpecs as $modelID => $handlerSpec ) {
			$returnMap[] = [
				$handlerSpec,
				[
					'assertClass' => ContentHandler::class,
					'allowCallable' => true,
					'allowClassName' => true,
					'extraArgs' => [ $modelID ],
				],
				$contentHandlerExpected
			];
		}
		$objectFactory
			->method( 'createObject' )
			->willReturnMap( $returnMap );

		foreach ( $handlerSpecs as $modelID => $handlerSpec ) {
			$this->assertSame( $contentHandlerExpected, $factory->getContentHandler( $modelID ) );
		}
	}

	/**
	 * @dataProvider provideHandlerSpecs
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

	public static function provideHandlerSpecsWithException(): array {
		return [
			'Callback exists but returns the wrong type' => [
				[
					'ExistCallbackWithWrongType' => static function () {
						return true;
					}
				],
				UnexpectedValueException::class
			],
			'Callback exists but returns null' => [
				[
					'ExistCallbackWithNull' => static function () {
						return null;
					}
				],
				UnexpectedValueException::class
			],
			'Callback exists but returns the empty string' => [
				[
					'ExistCallbackWithEmptyString' => static function () {
						return '';
					}
				],
				UnexpectedValueException::class
			],
			'Wrong class name' => [
				[
					'WrongClassName' => self::class
				],
				UnexpectedValueException::class
			],
			'Wrong type' => [
				[
					'WrongType' => true
				],
				InvalidArgumentException::class
			],
			'Is null' => [
				[
					'NullType' => null
				],
				MWUnknownContentModelException::class
			],
			'Class does not exist' => [
				[
					'WrongClassNameNotExist' => 'ClassNameNotExist'
				],
				InvalidArgumentException::class
			],
			'Callback with non-existing class name' => [
				[
					'ExistCallbackWithNotExistClassName' => static function () {
						return ClassNameNotExist();
					},
				],
				Error::class
			],
			'Empty string' => [
				[
					'EmptyString' => '',
				],
				InvalidArgumentException::class
			],
		];
	}

	/**
	 * @dataProvider provideHandlerSpecsWithException
	 */
	public function testCreateContentHandlerForModelID_callWithProvider_throwsException(
		array $handlerSpecs,
		string $exceptionClassName
	): void {
		if ( count( $handlerSpecs ) !== 1 ) {
			$this->fail( 'Dataprovider provided wrong amount of specs' );
		}

		$factory = new ContentHandlerFactory(
			$handlerSpecs,
			$this->createSimpleObjectFactory(),
			$this->createMock( HookContainer::class ),
			$this->logger
		);

		$modelID = key( $handlerSpecs );
		$this->expectException( $exceptionClassName );
		$factory->getContentHandler( $modelID );
	}

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

	public static function provideValidDummySpecList() {
		return [
			'1-0-3' => [
				'mock name 1',
				'mock name 0',
				'mock name 3',
				'mock name 4',
			],
		];
	}

	public function testGetContentModels_empty_empty() {
		$factory = new ContentHandlerFactory(
			[],
			$this->createMock( ObjectFactory::class ),
			$this->createMock( HookContainer::class ),
			$this->logger
		);

		$this->assertArrayEquals( [], $factory->getContentModels() );
	}

	public function testGetAllContentFormats_flow_same() {
		$contentHandler1 = $this->createMock( DummyContentHandlerForTesting::class );
		$contentHandler1->method( 'getSupportedFormats' )->willReturn( [ 'format 1' ] );

		$contentHandler2 = $this->createMock( DummyContentHandlerForTesting::class );
		$contentHandler2->method( 'getSupportedFormats' )->willReturn( [ 'format 0' ] );

		$contentHandler3 = $this->createMock( DummyContentHandlerForTesting::class );
		$contentHandler3->method( 'getSupportedFormats' )->willReturn( [ 'format 3' ] );

		$objectFactory = $this->createMock( ObjectFactory::class );
		$objectFactory
			->method( 'createObject' )
			->willReturnOnConsecutiveCalls( $contentHandler1, $contentHandler2, $contentHandler3 );

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
