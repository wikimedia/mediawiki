<?php

namespace MediaWiki\Tests\Rest\Handler;

use MediaWiki\MainConfigNames;
use MediaWiki\Rest\Handler\Helper\ParsoidFormatHelper;
use MediaWiki\Rest\Handler\TransformHtmlToWikitextHandler;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\RequestInterface;
use MediaWikiIntegrationTestCase;
use Wikimedia\Message\MessageValue;

/**
 * @group Database
 * @covers \MediaWiki\Rest\Handler\TransformHtmlToWikitextHandler
 */
class TransformHtmlToWikitextHandlerTest extends MediaWikiIntegrationTestCase {
	use HandlerTestTrait;

	private function newHandler(): TransformHtmlToWikitextHandler {
		$revisionLookup = $this->getServiceContainer()->getRevisionLookup();
		$dataAccess = $this->getServiceContainer()->getParsoidDataAccess();
		$siteConfig = $this->getServiceContainer()->getParsoidSiteConfig();
		$pageConfigFactory = $this->getServiceContainer()->getParsoidPageConfigFactory();

		return new TransformHtmlToWikitextHandler(
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
				'html' => '<pre>hi ho</pre>',
			] )
		] + $defaultParams );

		yield 'should not require any parameters' => [
			$request,
			[ 'from' => ParsoidFormatHelper::FORMAT_HTML, 'format' => ParsoidFormatHelper::FORMAT_WIKITEXT ]
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
			new MessageValue( 'rest-requestbody-desc-transform-html' ),
			$handler->getRequestBodyDescription()
		);
	}

	public function testGetOpenApiSpecRequestBodySchema() {
		$handler = $this->newHandler();
		$config = [ 'from' => ParsoidFormatHelper::FORMAT_HTML, 'format' => ParsoidFormatHelper::FORMAT_WIKITEXT ];
		$this->initHandler( $handler, new RequestData( [ 'method' => 'POST' ] ), $config );

		$spec = $handler->getOpenApiSpec( 'POST' );
		$this->assertSame(
			'<message key="rest-requestbody-desc-transform-html"></message>',
			$spec['requestBody']['description']
		);

		$schema = $spec['requestBody']['content']['application/json']['schema'];

		$stringForm = $schema['oneOf'][0]['properties']['html'];
		$this->assertSame(
			'<message key="rest-property-desc-transform-html"></message>',
			$stringForm['description']
		);
		$this->assertSame( '<h2>Hello world</h2>', $stringForm['example'] );

		$objectForm = $schema['oneOf'][1]['properties']['html'];
		$this->assertSame(
			'<message key="rest-property-desc-transform-html-with-headers"></message>',
			$objectForm['description']
		);
		$this->assertSame( [ 'body' => '<h2>Hello world</h2>' ], $objectForm['example'] );

		$headers = $objectForm['properties']['headers'];
		$this->assertSame(
			'<message key="rest-property-desc-transform-html-headers"></message>',
			$headers['description']
		);
		$this->assertSame( [ 'content-language' => 'en' ], $headers['example'] );

		$body = $objectForm['properties']['body'];
		$this->assertSame(
			'<message key="rest-property-desc-transform-html-body"></message>',
			$body['description']
		);
		$this->assertSame( '<h2>Hello world</h2>', $body['example'] );
	}
}
