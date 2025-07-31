<?php

namespace MediaWiki\Tests\Rest\Handler;

use MediaWiki\MainConfigNames;
use MediaWiki\Rest\Handler\Helper\ParsoidFormatHelper;
use MediaWiki\Rest\Handler\TransformHtmlToWikitextHandler;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\RequestInterface;
use MediaWikiIntegrationTestCase;

/**
 * @group Database
 */
class TransformHtmlToWikitextHandlerTest extends MediaWikiIntegrationTestCase {
	use HandlerTestTrait;

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
	 * @covers \MediaWiki\Rest\Handler\TransformHtmlToWikitextHandler
	 */
	public function testRequest(
		RequestInterface $request,
		$config = []
	) {
		$this->overrideConfigValue( MainConfigNames::UsePigLatinVariant, true );

		$revisionLookup = $this->getServiceContainer()->getRevisionLookup();
		$dataAccess = $this->getServiceContainer()->getParsoidDataAccess();
		$siteConfig = $this->getServiceContainer()->getParsoidSiteConfig();
		$pageConfigFactory = $this->getServiceContainer()->getParsoidPageConfigFactory();

		$handler = new TransformHtmlToWikitextHandler(
			$revisionLookup,
			$siteConfig,
			$pageConfigFactory,
			$dataAccess
		);

		$this->executeHandler( $handler, $request, $config );
		$this->assertTrue( true );
	}
}
