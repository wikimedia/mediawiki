<?php

use MediaWiki\Content\ContentHandlerFactory;

class ContentHandlerFactoryTest extends MediaWikiUnitTestCase {

	public function provideHandlerSpecs() {
		return [
			'typical ' => [
				[
					'ExistClassName' => DummyContentHandlerForTesting::class,
					'ExistCallbackWithExistClassName' => function ( $modelID ) {
						return new DummyContentHandlerForTesting( $modelID );
					},
				],
			],
		];
	}

	/**
	 * @covers       \MediaWiki\Content\ContentHandlerFactory::getContentHandler
	 * @covers       \MediaWiki\Content\ContentHandlerFactory::createForModelID
	 * @covers       \MediaWiki\Content\ContentHandlerFactory::createContentHandlerFromHandlerSpec
	 * @covers       \MediaWiki\Content\ContentHandlerFactory::validateContentHandler
	 *
	 * @param array $handlerSpecs
	 *
	 * @todo test ContentHandlerFactory::createContentHandlerFromHook
	 *
	 * @dataProvider provideHandlerSpecs $handlerSpecs
	 */
	public function testGetContentHandler_callWithProvider_same( array $handlerSpecs ) {
		$registry = new ContentHandlerFactory( $handlerSpecs );
		foreach ( $handlerSpecs as $modelID => $handlerSpec ) {
			$contentHandler = $registry->getContentHandler( $modelID );
			$this->assertInstanceOf( DummyContentHandlerForTesting::class, $contentHandler );
			$this->assertSame( $modelID, $contentHandler->getModelID() );
		}
	}

	public function provideHandlerSpecsWithMWException() {
		return [
			'MWException expected' => [
				[
					'ExistCallbackWithWrongType' => function () {
						return true;
					},
					'ExistCallbackWithNull' => function () {
						return null;
					},
					'ExistCallbackWithEmptyString' => function () {
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
					'ExistCallbackWithNotExistClassName' => function () {
						return ClassNameNotExist();
					},
					'EmptyString' => '',
				],
				Error::class,
			],
		];
	}

	/**
	 * @covers       \MediaWiki\Content\ContentHandlerFactory::getContentHandler
	 * @covers       \MediaWiki\Content\ContentHandlerFactory::createForModelID
	 * @covers       \MediaWiki\Content\ContentHandlerFactory::createContentHandlerFromHandlerSpec
	 * @covers       \MediaWiki\Content\ContentHandlerFactory::validateContentHandler
	 *
	 * @dataProvider provideHandlerSpecsWithMWException
	 *
	 * @param array $handlerSpecs
	 * @param string $exceptionName
	 */
	public function testCreateContentHandlerForModelID_callWithProvider_throwsException(
		array $handlerSpecs, string $exceptionName
	) {
		$registry = new ContentHandlerFactory( $handlerSpecs );

		foreach ( $handlerSpecs as $modelID => $handlerSpec ) {
			try {
				$registry->getContentHandler( $modelID );
				$this->assertTrue( false );
			}
			catch ( \Throwable $exception ) {
				$this->assertInstanceOf( $exceptionName, $exception,
					"$modelID get: " . get_class( $exception ) . " but expect: $exceptionName" );
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

		( new ContentHandlerFactory( [] ) )->getContentHandler( 'ModelNameNotExist' );
	}

	/**
	 * @covers       \MediaWiki\Content\ContentHandlerFactory::getContentHandler
	 * @covers       \MediaWiki\Content\ContentHandlerFactory::createForModelID
	 * @covers       \MediaWiki\Content\ContentHandlerFactory::createContentHandlerFromHandlerSpec
	 * @covers       \MediaWiki\Content\ContentHandlerFactory::validateContentHandler
	 * @covers       \MediaWiki\Content\ContentHandlerFactory::isDefinedModel
	 */
	public function testDefineContentHandler_flow_throwsException() {
		$registry = new ContentHandlerFactory( [] );
		$this->assertFalse( $registry->isDefinedModel( 'define test' ) );

		$registry->defineContentHandler( 'define test', DummyContentHandlerForTesting::class );
		$this->assertTrue( $registry->isDefinedModel( 'define test' ) );
		$this->assertInstanceOf(
			DummyContentHandlerForTesting::class,
			$registry->getContentHandler( 'define test' )
		);
	}

	/**
	 * @covers       \MediaWiki\Content\ContentHandlerFactory::getContentModels
	 */
	public function testGetContentModels_flow_same() {
		$registry = new ContentHandlerFactory( [
			'mock name 1' => DummyContentHandlerForTesting::class,
			'mock name 0' => DummyContentHandlerForTesting::class,
		] );

		$this->assertArrayEquals( [
			'mock name 1',
			'mock name 0',
		], $registry->getContentModels() );

		$registry->defineContentHandler( 'some new name', function () {
		} );

		$this->assertArrayEquals( [
			'mock name 1',
			'mock name 0',
			'some new name',
		], $registry->getContentModels() );
	}

	/**
	 * @covers       \MediaWiki\Content\ContentHandlerFactory::getContentModels
	 */
	public function testGetContentModels_empty_empty() {
		$registry = new ContentHandlerFactory( [] );

		$this->assertArrayEquals( [], $registry->getContentModels() );
	}

	/**
	 * @covers       \MediaWiki\Content\ContentHandlerFactory::getAllContentFormats
	 * @covers       \MediaWiki\Content\ContentHandlerFactory::defineContentHandler
	 */
	public function testGetAllContentFormats_flow_same() {
		$registry = new ContentHandlerFactory( [
			'mock name 1' => function () {
				return new DummyContentHandlerForTesting( 'mock 1', [ 'format 1' ] );
			},
			'mock name 2' => function () {
				return new DummyContentHandlerForTesting( 'mock 0', [ 'format 0' ] );
			},
		] );

		$this->assertArrayEquals( [
			'format 1',
			'format 0',
		], $registry->getAllContentFormats() );

		$registry->defineContentHandler( 'some new name', function () {
			return new DummyContentHandlerForTesting( 'mock defined', [ 'format defined' ] );
		} );

		$this->assertArrayEquals( [
			'format 1',
			'format 0',
			'format defined',
		], $registry->getAllContentFormats() );
	}
}
