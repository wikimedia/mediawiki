<?php

namespace MediaWiki\Tests\Rest\Handler;

use MediaWiki\MainConfigNames;
use MediaWiki\MainConfigSchema;
use MediaWiki\Rest\Handler\ParsoidFormatHelper;
use MediaWiki\Rest\Handler\TransformHandler;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\RequestInterface;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\StringStream;
use MediaWikiIntegrationTestCase;
use Wikimedia\Parsoid\Parsoid;

/**
 * @group Database
 */
class TransformHandlerTest extends MediaWikiIntegrationTestCase {
	use HandlerTestTrait;

	public function provideRequest() {
		$profileVersion = Parsoid::AVAILABLE_VERSIONS[0];
		$htmlProfileUri = 'https://www.mediawiki.org/wiki/Specs/HTML/' . $profileVersion;
		$wikitextProfileUri = 'https://www.mediawiki.org/wiki/Specs/wikitext/1.0.0';

		$defaultParams = [
			'method' => 'POST',
			'headers' => [
				'content-type' => 'application/json',
			],
		];

		// Convert wikitext to HTML ////////////////////////////////////////////////////////////////
		$request = new RequestData( $defaultParams + [
			'pathParams' => [
				'from' => ParsoidFormatHelper::FORMAT_WIKITEXT,
				'format' => ParsoidFormatHelper::FORMAT_HTML,
			],
			'bodyContents' => json_encode( [
				'wikitext' => '== h2 ==',
			] )
		] );

		$response = new Response();
		$response->setBody( new StringStream( '<h2> h2 </h2>' ) );

		yield 'should transform wikitext to HTML' => [
			$request,
			'>h2</h2>',
			200,
			[ 'content-type' => "text/html; charset=utf-8; profile=\"$htmlProfileUri\"" ],
		];

		// Convert HTML to wikitext ////////////////////////////////////////////////////////////////
		$request = new RequestData( $defaultParams + [
				'pathParams' => [
					'from' => ParsoidFormatHelper::FORMAT_HTML,
					'format' => ParsoidFormatHelper::FORMAT_WIKITEXT,
				],
				'bodyContents' => json_encode( [
					'html' => '<pre>hi ho</pre>',
				] )
			] );

		$response = new Response();
		$response->setBody( new StringStream( '<h2> h2 </h2>' ) );

		yield 'should transform HTML to wikitext' => [
			$request,
			'hi ho',
			200,
			[ 'content-type' => "text/plain; charset=utf-8; profile=\"$wikitextProfileUri\"" ],
		];
	}

	/**
	 * @dataProvider provideRequest
	 * @covers \MediaWiki\Rest\Handler\TransformHandler::execute
	 */
	public function testRequest(
		RequestInterface $request,
		$expectedText,
		$expectedStatus = 200,
		$expectedHeaders = []
	) {
		$parsoidSettings = MainConfigSchema::getDefaultValue( MainConfigNames::ParsoidSettings );

		$dataAccess = $this->getServiceContainer()->getParsoidDataAccess();
		$siteConfig = $this->getServiceContainer()->getParsoidSiteConfig();
		$pageConfigFactory = $this->getServiceContainer()->getParsoidPageConfigFactory();

		$handler = new TransformHandler(
			$parsoidSettings,
			$siteConfig,
			$pageConfigFactory,
			$dataAccess
		);
		$response = $this->executeHandler( $handler, $request );
		$response->getBody()->rewind();

		$this->assertSame( $expectedStatus, $response->getStatusCode(), 'Status' );
		$this->assertStringContainsString( $expectedText, $response->getBody()->getContents() );

		foreach ( $expectedHeaders as $key => $expectedHeader ) {
			$this->assertSame( $expectedHeader, $response->getHeaderLine( $key ), $key );
		}
	}

}
