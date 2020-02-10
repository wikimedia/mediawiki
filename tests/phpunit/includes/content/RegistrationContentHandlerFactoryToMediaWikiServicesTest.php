<?php

use MediaWiki\MediaWikiServices;

/**
 * @group ContentHandlerFactory
 */
class RegistrationContentHandlerFactoryToMediaWikiServicesTest extends MediaWikiTestCase {

	protected function setUp(): void {
		parent::setUp();

		$this->setMwGlobals( [
			'wgExtraNamespaces' => [
				12312 => 'Dummy',
				12313 => 'Dummy_talk',
			],
			// The below tests assume that namespaces not mentioned here (Help, User, MediaWiki, ..)
			// default to CONTENT_MODEL_WIKITEXT.
			'wgNamespaceContentModels' => [
				12312 => 'testing',
			],
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

		// Reset LinkCache
		MediaWikiServices::getInstance()->resetServiceForTesting( 'LinkCache' );
	}

	protected function tearDown(): void {
		// Reset LinkCache
		MediaWikiServices::getInstance()->resetServiceForTesting( 'LinkCache' );

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
