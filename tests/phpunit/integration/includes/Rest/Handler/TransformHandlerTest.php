<?php

namespace MediaWiki\Tests\Rest\Handler;

use MediaWiki\MainConfigNames;
use MediaWiki\Rest\Handler\Helper\ParsoidFormatHelper;
use MediaWiki\Rest\Handler\TransformHandler;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\RequestInterface;
use MediaWikiIntegrationTestCase;
use Wikimedia\Parsoid\Parsoid;

/**
 * @group Database
 */
class TransformHandlerTest extends MediaWikiIntegrationTestCase {
	use HandlerTestTrait;

	public static function provideRequest() {
		$profileVersion = Parsoid::AVAILABLE_VERSIONS[0];
		$htmlProfileUri = 'https://www.mediawiki.org/wiki/Specs/HTML/' . $profileVersion;
		$htmlContentType = "text/html; charset=utf-8; profile=\"$htmlProfileUri\"";
		$pbProfileUri = 'https://www.mediawiki.org/wiki/Specs/pagebundle/' . $profileVersion;
		$pbContentType = "application/json; charset=utf-8; profile=\"$pbProfileUri\"";

		$wikitextProfileUri = 'https://www.mediawiki.org/wiki/Specs/wikitext/1.0.0';

		$defaultParams = [
			'method' => 'POST',
			'headers' => [
				'content-type' => 'application/json',
			],
		];

		// Convert wikitext to HTML ////////////////////////////////////////////////////////////////
		$request = new RequestData( [
			'pathParams' => [
				'from' => ParsoidFormatHelper::FORMAT_WIKITEXT,
				'format' => ParsoidFormatHelper::FORMAT_HTML,
			],
			'bodyContents' => json_encode( [
				'wikitext' => '== h2 ==',
			] )
		] + $defaultParams );

		yield 'should transform wikitext to HTML' => [
			$request,
			'>h2</h2>',
			200,
			[ 'content-type' => $htmlContentType ],
		];

		// Convert HTML to wikitext ////////////////////////////////////////////////////////////////
		$request = new RequestData( [
				'pathParams' => [
					'from' => ParsoidFormatHelper::FORMAT_HTML,
					'format' => ParsoidFormatHelper::FORMAT_WIKITEXT,
				],
				'bodyContents' => json_encode( [
					'html' => '<pre>hi ho</pre>',
				] )
			] + $defaultParams );

		yield 'should transform HTML to wikitext' => [
			$request,
			'hi ho',
			200,
			[ 'content-type' => "text/plain; charset=utf-8; profile=\"$wikitextProfileUri\"" ],
		];

		// Perform language variant conversion //////////////////////////////////////////////////////
		$request = new RequestData( [
				'pathParams' => [
					'from' => ParsoidFormatHelper::FORMAT_PAGEBUNDLE,
					'format' => ParsoidFormatHelper::FORMAT_PAGEBUNDLE,
				],
				'bodyContents' => json_encode( [
					// NOTE: input for pb2pb is expected in the 'original' structure for some reason
					'original' => [
						'html' => [
							'headers' => [
								'content-type' => $htmlContentType,
							],
							'body' => '<p>test language conversion</p>',
						],
					],
					'updates' => [
						'variant' => [
							'source' => 'en',
							'target' => 'en-x-piglatin'
						]
					]
				] ),
				'headers' => [
					'content-type' => 'application/json',
					'content-language' => 'en',
					'accept-language' => 'en-x-piglatin',
				]
			] + $defaultParams );

		yield 'should apply language variant conversion' => [
			$request,
			[
				// pig latin!
				'>esttay anguagelay onversioncay<',
				// NOTE: quotes are escaped because this is embedded in JSON
				'<meta http-equiv=\"content-language\" content=\"en-x-piglatin\"/>'
			],
			200,
			// NOTE: Parsoid returns a content-language header in the page bundle,
			// but that header is not applied to the HTTP response, which is JSON.
			[ 'content-type' => $pbContentType ],
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
		$this->overrideConfigValue( MainConfigNames::UsePigLatinVariant, true );

		$revisionLookup = $this->getServiceContainer()->getRevisionLookup();
		$dataAccess = $this->getServiceContainer()->getParsoidDataAccess();
		$siteConfig = $this->getServiceContainer()->getParsoidSiteConfig();
		$pageConfigFactory = $this->getServiceContainer()->getParsoidPageConfigFactory();

		$handler = new TransformHandler(
			$revisionLookup,
			$siteConfig,
			$pageConfigFactory,
			$dataAccess
		);
		$response = $this->executeHandler( $handler, $request );
		$response->getBody()->rewind();
		$data = $response->getBody()->getContents();

		$this->assertSame( $expectedStatus, $response->getStatusCode(), 'Status' );

		foreach ( (array)$expectedText as $txt ) {
			$this->assertStringContainsString( $txt, $data );
		}

		foreach ( $expectedHeaders as $key => $expectedHeader ) {
			$this->assertSame( $expectedHeader, $response->getHeaderLine( $key ), $key );
		}
	}

}
