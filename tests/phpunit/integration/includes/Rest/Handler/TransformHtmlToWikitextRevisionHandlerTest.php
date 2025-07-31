<?php

namespace MediaWiki\Tests\Rest\Handler;

use MediaWiki\MainConfigNames;
use MediaWiki\Rest\Handler\Helper\ParsoidFormatHelper;
use MediaWiki\Rest\Handler\TransformHtmlToWikitextRevisionHandler;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\RequestInterface;
use MediaWikiIntegrationTestCase;
use Wikimedia\Message\MessageValue;

/**
 * @group Database
 */
class TransformHtmlToWikitextRevisionHandlerTest extends MediaWikiIntegrationTestCase {
	use HandlerTestTrait;

	public static function provideRequest() {
		$defaultParams = [
			'method' => 'POST',
			'headers' => [
				'content-type' => 'application/json',
			],
		];

		$request = new RequestData( [
			'pathParams' => [
				'revision' => '123',
			],
			'bodyContents' => json_encode( [
				'html' => '<pre>hi ho</pre>',
			] )
		] + $defaultParams );
		yield 'should require title parameter' => [
			$request,
			new LocalizedHttpException(
				new MessageValue( 'paramvalidator-missingparam' ),
				400,
				[]
			),
			[ 'from' => ParsoidFormatHelper::FORMAT_HTML, 'format' => ParsoidFormatHelper::FORMAT_WIKITEXT ]
		];

		$request = new RequestData( [
				'pathParams' => [
					'title' => 'Foo',
				],
				'bodyContents' => json_encode( [
					'html' => '<pre>hi ho</pre>',
				] )
			] + $defaultParams );
		yield 'should require revision parameter' => [
			$request,
			new LocalizedHttpException(
				new MessageValue( 'paramvalidator-missingparam' ),
				400,
				[]
			),
			[ 'from' => ParsoidFormatHelper::FORMAT_HTML, 'format' => ParsoidFormatHelper::FORMAT_WIKITEXT ]
		];

		$request = new RequestData( [
			'pathParams' => [
				'title' => 'Foo',
				'revision' => '123',
			],
			'bodyContents' => json_encode( [
				'html' => '<pre>hi ho</pre>',
			] )
		] + $defaultParams );
		yield 'should require only title and revision parameters' => [
			$request,
			new LocalizedHttpException(
				new MessageValue( 'rest-specified-revision-unavailable' ),
				404,
				[]
			),
			[ 'from' => ParsoidFormatHelper::FORMAT_HTML, 'format' => ParsoidFormatHelper::FORMAT_WIKITEXT ]
		];
	}

	/**
	 * @dataProvider provideRequest
	 * @covers \MediaWiki\Rest\Handler\TransformHtmlToWikitextRevisionHandler::execute
	 */
	public function testRequest(
		RequestInterface $request,
		$expectedException,
		$config = []
	) {
		$this->overrideConfigValue( MainConfigNames::UsePigLatinVariant, true );

		$revisionLookup = $this->getServiceContainer()->getRevisionLookup();
		$dataAccess = $this->getServiceContainer()->getParsoidDataAccess();
		$siteConfig = $this->getServiceContainer()->getParsoidSiteConfig();
		$pageConfigFactory = $this->getServiceContainer()->getParsoidPageConfigFactory();

		$handler = new TransformHtmlToWikitextRevisionHandler(
			$revisionLookup,
			$siteConfig,
			$pageConfigFactory,
			$dataAccess
		);

		// Always expect an exception, because we aren't setting up test content.
		// Use expectExceptionObject to make sure it is the expected one.
		$this->expectExceptionObject( $expectedException );
		$this->executeHandler( $handler, $request, $config );
	}
}
