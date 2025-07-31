<?php

namespace MediaWiki\Tests\Rest\Handler;

use MediaWiki\MainConfigNames;
use MediaWiki\Rest\Handler\Helper\ParsoidFormatHelper;
use MediaWiki\Rest\Handler\TransformWikitextToLintHandler;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\RequestInterface;
use MediaWikiIntegrationTestCase;

/**
 * @group Database
 */
class TransformWikitextToLintHandlerTest extends MediaWikiIntegrationTestCase {
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
					'wikitext' => '== h2 ==',
				] )
			] + $defaultParams );

		yield 'should not require any parameters' => [
			$request,
			[ 'from' => ParsoidFormatHelper::FORMAT_WIKITEXT, 'format' => ParsoidFormatHelper::FORMAT_LINT ]
		];
	}

	/**
	 * @dataProvider provideRequest
	 * @covers \MediaWiki\Rest\Handler\TransformWikitextToLintHandler
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

		$handler = new TransformWikitextToLintHandler(
			$revisionLookup,
			$siteConfig,
			$pageConfigFactory,
			$dataAccess
		);

		$this->executeHandler( $handler, $request, $config );
		$this->assertTrue( true );
	}
}
