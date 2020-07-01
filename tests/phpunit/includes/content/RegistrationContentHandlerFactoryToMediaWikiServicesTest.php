<?php

use MediaWiki\MediaWikiServices;

/**
 * @group ContentHandlerFactory
 */
class RegistrationContentHandlerFactoryToMediaWikiServicesTest extends MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		parent::setUp();

		$this->setMwGlobals( [
			'wgContentHandlers' => [
				CONTENT_MODEL_WIKITEXT => WikitextContentHandler::class,
				CONTENT_MODEL_JAVASCRIPT => JavaScriptContentHandler::class,
				CONTENT_MODEL_JSON => JsonContentHandler::class,
				CONTENT_MODEL_CSS => CssContentHandler::class,
				CONTENT_MODEL_TEXT => TextContentHandler::class,
				'testing' => DummyContentHandlerForTesting::class,
				'testing-callbacks' => function ( $modelId ) {
					return new DummyContentHandlerForTesting( $modelId );
				},
			],
		] );

		MediaWikiServices::getInstance()->resetServiceForTesting( 'ContentHandlerFactory' );
	}

	protected function tearDown(): void {
		MediaWikiServices::getInstance()->resetServiceForTesting( 'ContentHandlerFactory' );

		parent::tearDown();
	}

	/**
	 * @covers \MediaWiki\MediaWikiServices::getContentHandlerFactory
	 */
	public function testCallFromService_get_ok(): void {
		$this->assertInstanceOf(
			\MediaWiki\Content\IContentHandlerFactory::class,
			MediaWikiServices::getInstance()->getContentHandlerFactory()
		);

		$this->assertSame(
			[
				'wikitext',
				'javascript',
				'json',
				'css',
				'text',
				'testing',
				'testing-callbacks',
			],
			MediaWikiServices::getInstance()->getContentHandlerFactory()->getContentModels()
		);
	}

	/**
	 * @covers \MediaWiki\MediaWikiServices::getContentHandlerFactory
	 */
	public function testCallFromService_second_same(): void {
		$this->assertSame(
			MediaWikiServices::getInstance()->getContentHandlerFactory(),
			MediaWikiServices::getInstance()->getContentHandlerFactory()
		);
	}

	/**
	 * @covers \MediaWiki\MediaWikiServices::getContentHandlerFactory
	 */
	public function testCallFromService_afterCustomDefine_same(): void {
		$factory = MediaWikiServices::getInstance()->getContentHandlerFactory();
		$factory->defineContentHandler(
			'model name',
			DummyContentHandlerForTesting::class
		);
		$this->assertTrue(
			MediaWikiServices::getInstance()
				->getContentHandlerFactory()
				->isDefinedModel( 'model name' )
		);
		$this->assertSame(
			$factory,
			MediaWikiServices::getInstance()->getContentHandlerFactory()
		);
	}
}
