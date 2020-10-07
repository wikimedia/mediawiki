<?php

use MediaWiki\MediaWikiServices;

/**
 * @group ContentHandlerFactory
 */
class ObjectFactoryMakeContentHandlerWithSpecsToTest extends MediaWikiIntegrationTestCase {

	private $createObjectOptions = [
		'assertClass' => ContentHandler::class,
		'allowCallable' => true,
		'allowClassName' => true,
	];

	protected function setUp(): void {
		parent::setUp();

		$this->setMwGlobals( [
			'wgContentHandlers' => [],
		] );
	}

	/**
	 * @covers       \Wikimedia\ObjectFactory::createObject
	 *
	 * @param array $handlerSpecs
	 *
	 * @dataProvider provideHandlerSpecs
	 */
	public function testObjectFactoryCreateObject_callWithProvider_same(
		array $handlerSpecs
	): void {
		$objectFactory = MediaWikiServices::getInstance()->getObjectFactory();
		foreach ( $handlerSpecs as $modelID => $handlerSpec ) {
			$this->assertInstanceOf( DummyContentHandlerForTesting::class,
				$objectFactory->createObject( $handlerSpec,
					$this->createObjectOptions + [ 'extraArgs' => [ $modelID ] ] ) );
		}
	}

	public function provideHandlerSpecs() {
		return [
			'typical list' => [
				[
					'ExistClassName' => DummyContentHandlerForTesting::class,
					'ExistCallbackWithExistClassName' => function ( $modelID ) {
						return new DummyContentHandlerForTesting( $modelID );
					},
				],
				DummyContentHandlerForTesting::class,
			],
		];
	}

	/**
	 * @covers       \Wikimedia\ObjectFactory::createObject
	 *
	 * @dataProvider provideHandlerSpecsWithMWException
	 *
	 * @param array $handlerSpecs
	 * @param string $exceptionName
	 */
	public function testCreateContentHandlerForModelID_callWithProvider_throwsException(
		array $handlerSpecs,
		string $exceptionName
	) {
		$objectFactory = MediaWikiServices::getInstance()->getObjectFactory();

		foreach ( $handlerSpecs as $modelID => $handlerSpec ) {
			try {
				$objectFactory->createObject( $handlerSpec,
					$this->createObjectOptions + [ 'extraArgs' => [ $modelID ] ] );
				$this->assertTrue( false );
			}
			catch ( \Throwable $exception ) {
				$this->assertInstanceOf( $exceptionName,
					$exception,
					"For test with: '$modelID'" );
			}
		}
	}

	public function provideHandlerSpecsWithMWException() {
		return [
			'UnexpectedValueException with wrong specs result' => [
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
				],
				UnexpectedValueException::class,
			],
			'ObjectFactory with wrong specs' => [
				[

					'WrongType' => true,
					'NullType' => null,
					'WrongClassInstanceName' => $this,
					'WrongClassNameNotExist' => 'ClassNameNotExist',
					'EmptyString' => '',
				],
				InvalidArgumentException::class,
			],
			'Error expected' => [
				[
					'ExistCallbackWithNotExistClassName' => function () {
						return \ClassNameNotExist();
					},
				],
				Error::class,
			],
		];
	}
}
