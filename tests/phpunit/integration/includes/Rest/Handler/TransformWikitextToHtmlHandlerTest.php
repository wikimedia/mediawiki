<?php

namespace MediaWiki\Tests\Rest\Handler;

use MediaWiki\MainConfigNames;
use MediaWiki\Rest\Handler\Helper\ParsoidFormatHelper;
use MediaWiki\Rest\Handler\TransformWikitextToHtmlHandler;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\RequestInterface;
use MediaWikiIntegrationTestCase;
use Wikimedia\Message\MessageValue;

/**
 * @group Database
 * @covers \MediaWiki\Rest\Handler\TransformWikitextToHtmlHandler
 */
class TransformWikitextToHtmlHandlerTest extends MediaWikiIntegrationTestCase {
	use HandlerTestTrait;

	private function newHandler(): TransformWikitextToHtmlHandler {
		$revisionLookup = $this->getServiceContainer()->getRevisionLookup();
		$dataAccess = $this->getServiceContainer()->getParsoidDataAccess();
		$siteConfig = $this->getServiceContainer()->getParsoidSiteConfig();
		$pageConfigFactory = $this->getServiceContainer()->getParsoidPageConfigFactory();

		return new TransformWikitextToHtmlHandler(
			$revisionLookup,
			$siteConfig,
			$pageConfigFactory,
			$dataAccess
		);
	}

	public static function provideRequest() {
		$defaultParams = [
			'method' => 'POST',
			'headers' => [
				'content-type' => 'application/json',
			],
		];

		$request = new RequestData( [
				'bodyContents' => json_encode( [
					'wikitext' => '== h2 ==',
				] )
			] + $defaultParams );

		yield 'should not require any parameters' => [
			$request,
			[ 'from' => ParsoidFormatHelper::FORMAT_WIKITEXT, 'format' => ParsoidFormatHelper::FORMAT_HTML ]
		];
	}

	/**
	 * @dataProvider provideRequest
	 */
	public function testRequest(
		RequestInterface $request,
		$config = []
	) {
		$this->overrideConfigValue( MainConfigNames::UsePigLatinVariant, true );

		$handler = $this->newHandler();

		$this->executeHandler( $handler, $request, $config );
		$this->assertTrue( true );
	}

	public function testGetRequestBodyDescription() {
		$handler = $this->newHandler();

		$this->assertEquals(
			new MessageValue( 'rest-requestbody-desc-transform-wikitext' ),
			$handler->getRequestBodyDescription()
		);
	}

	public function testGetOpenApiSpecRequestBodySchema() {
		$handler = $this->newHandler();
		$config = [ 'from' => ParsoidFormatHelper::FORMAT_WIKITEXT, 'format' => ParsoidFormatHelper::FORMAT_HTML ];
		$this->initHandler( $handler, new RequestData( [ 'method' => 'POST' ] ), $config );

		$spec = $handler->getOpenApiSpec( 'POST' );
		$this->assertSame(
			'<message key="rest-requestbody-desc-transform-wikitext"></message>',
			$spec['requestBody']['description']
		);

		$schema = $spec['requestBody']['content']['application/json']['schema'];

		$stringForm = $schema['oneOf'][0]['properties']['wikitext'];
		$this->assertSame(
			'<message key="rest-property-desc-transform-wikitext"></message>',
			$stringForm['description']
		);
		$this->assertSame( '== Hello world ==', $stringForm['example'] );

		$objectForm = $schema['oneOf'][1]['properties']['wikitext'];
		$this->assertSame(
			'<message key="rest-property-desc-transform-wikitext-with-headers"></message>',
			$objectForm['description']
		);
		$this->assertSame( [ 'body' => '== Hello world ==' ], $objectForm['example'] );

		$headers = $objectForm['properties']['headers'];
		$this->assertSame(
			'<message key="rest-property-desc-transform-wikitext-headers"></message>',
			$headers['description']
		);
		$this->assertSame( [ 'content-language' => 'en' ], $headers['example'] );

		$body = $objectForm['properties']['body'];
		$this->assertSame(
			'<message key="rest-property-desc-transform-wikitext-body"></message>',
			$body['description']
		);
		$this->assertSame( '== Hello world ==', $body['example'] );
	}
}
