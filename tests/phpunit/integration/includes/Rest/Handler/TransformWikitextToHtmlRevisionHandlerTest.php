<?php

namespace MediaWiki\Tests\Rest\Handler;

use MediaWiki\MainConfigNames;
use MediaWiki\Rest\Handler\Helper\ParsoidFormatHelper;
use MediaWiki\Rest\Handler\TransformWikitextToHtmlRevisionHandler;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\RequestInterface;
use MediaWikiIntegrationTestCase;
use Wikimedia\Message\MessageValue;

/**
 * @group Database
 */
class TransformWikitextToHtmlRevisionHandlerTest extends MediaWikiIntegrationTestCase {
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
					'wikitext' => '== h2 ==',
				] )
			] + $defaultParams );
		yield 'should require title parameter' => [
			$request,
			new LocalizedHttpException(
				new MessageValue( 'paramvalidator-missingparam' ),
				400,
				[]
			),
			[ 'from' => ParsoidFormatHelper::FORMAT_WIKITEXT, 'format' => ParsoidFormatHelper::FORMAT_HTML ]
		];

		$request = new RequestData( [
				'pathParams' => [
					'title' => 'Foo',
				],
				'bodyContents' => json_encode( [
					'wikitext' => '== h2 ==',
				] )
			] + $defaultParams );
		yield 'should require revision parameter' => [
			$request,
			new LocalizedHttpException(
				new MessageValue( 'paramvalidator-missingparam' ),
				400,
				[]
			),
			[ 'from' => ParsoidFormatHelper::FORMAT_WIKITEXT, 'format' => ParsoidFormatHelper::FORMAT_HTML ]
		];

		$request = new RequestData( [
				'pathParams' => [
					'title' => 'Foo',
					'revision' => '123',
				],
				'bodyContents' => json_encode( [
					'wikitext' => '== h2 ==',
				] )
			] + $defaultParams );
		yield 'should require only title and revision parameters' => [
			$request,
			null,
			[ 'from' => ParsoidFormatHelper::FORMAT_WIKITEXT, 'format' => ParsoidFormatHelper::FORMAT_HTML ]
		];
	}

	/**
	 * @dataProvider provideRequest
	 * @covers \MediaWiki\Rest\Handler\TransformWikitextToHtmlRevisionHandler::execute
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

		$handler = new TransformWikitextToHtmlRevisionHandler(
			$revisionLookup,
			$siteConfig,
			$pageConfigFactory,
			$dataAccess
		);

		if ( $expectedException ) {
			$this->expectExceptionObject( $expectedException );
		}
		$this->executeHandler( $handler, $request, $config );
		$this->assertTrue( true );
	}
}
