<?php

use MediaWiki\MainConfigNames;

/**
 * @group ContentHandlerFactory
 */
class RegistrationContentHandlerFactoryToMediaWikiServicesTest extends MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValue(
			MainConfigNames::ContentHandlers,
			[
				CONTENT_MODEL_WIKITEXT => WikitextContentHandler::class,
				CONTENT_MODEL_JAVASCRIPT => JavaScriptContentHandler::class,
				CONTENT_MODEL_JSON => JsonContentHandler::class,
				CONTENT_MODEL_CSS => CssContentHandler::class,
				CONTENT_MODEL_TEXT => TextContentHandler::class,
				'testing' => DummyContentHandlerForTesting::class,
				'testing-callbacks' => static function ( $modelId ) {
					return new DummyContentHandlerForTesting( $modelId );
				},
			]
		);
	}

	/**
	 * @covers \MediaWiki\MediaWikiServices::getContentHandlerFactory
	 */
	public function testCallFromService_get_ok(): void {
		$this->assertInstanceOf(
			\MediaWiki\Content\IContentHandlerFactory::class,
			$this->getServiceContainer()->getContentHandlerFactory()
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
			$this->getServiceContainer()->getContentHandlerFactory()->getContentModels()
		);
	}

	/**
	 * @covers \MediaWiki\MediaWikiServices::getContentHandlerFactory
	 */
	public function testCallFromService_second_same(): void {
		$this->assertSame(
			$this->getServiceContainer()->getContentHandlerFactory(),
			$this->getServiceContainer()->getContentHandlerFactory()
		);
	}

	/**
	 * @covers \MediaWiki\MediaWikiServices::getContentHandlerFactory
	 */
	public function testCallFromService_afterCustomDefine_same(): void {
		$factory = $this->getServiceContainer()->getContentHandlerFactory();
		$factory->defineContentHandler(
			'model name',
			DummyContentHandlerForTesting::class
		);
		$this->assertTrue(
			$this->getServiceContainer()
				->getContentHandlerFactory()
				->isDefinedModel( 'model name' )
		);
		$this->assertSame(
			$factory,
			$this->getServiceContainer()->getContentHandlerFactory()
		);
	}
}
