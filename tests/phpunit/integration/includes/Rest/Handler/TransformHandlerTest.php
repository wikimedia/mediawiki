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

	public function setUp(): void {
		parent::setUp();
		$this->editPage(
			'wikitext_lint_page',
			"intro\n== h2 ==\ndetails== h2 ==\nmore\n<code>foo<code>\nend"
		);
	}

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

		// Convert wikitext to HTML //////////////////////////////////////////////////////
		$request = new RequestData( [
			'pathParams' => [
				'from' => ParsoidFormatHelper::FORMAT_WIKITEXT,
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
			[ 'format' => ParsoidFormatHelper::FORMAT_HTML ]
		];

		// Convert HTML to wikitext //////////////////////////////////////////////////////
		$request = new RequestData( [
				'pathParams' => [
					'from' => ParsoidFormatHelper::FORMAT_HTML,
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
			[ 'format' => ParsoidFormatHelper::FORMAT_WIKITEXT ]
		];

		// Convert wikitext to lint errors (POST) //////////////////////////////////////////////////
		$request = new RequestData( [
				'pathParams' => [
					'from' => ParsoidFormatHelper::FORMAT_WIKITEXT,
				],
				'bodyContents' => json_encode( [
					'wikitext' => "intro\n== h2 ==\ndetails== h2 ==\nmore\n<code>foo<code>\nend",
				] )
			] + $defaultParams );

		yield 'should transform wikitext to lint errors (POST)' => [
			$request,
			'[' .
			'{"type":"missing-end-tag","dsr":[36,55,6,0],"templateInfo":null,"params":{"name":"code","inTable":false}},' .
			'{"type":"multiple-unclosed-formatting-tags","dsr":[36,55,6,0],"templateInfo":null,"params":{"name":"code","inTable":false}},' .
			'{"type":"missing-end-tag","dsr":[45,55,6,0],"templateInfo":null,"params":{"name":"code","inTable":false}}' .
			']',
			200,
			[ 'content-type' => 'application/json' ],
			[ 'format' => ParsoidFormatHelper::FORMAT_LINT ]
		];

		// Convert wikitext to lint errors (GET) //////////////////////////////////////////////////
		$request = new RequestData( [
			'method' => 'GET',
			'pathParams' => [
				'from' => ParsoidFormatHelper::FORMAT_WIKITEXT,
				'title' => 'wikitext_lint_page'
			]
		] + $defaultParams );

		yield 'should transform wikitext to lint errors (GET)' => [
			$request,
			'[' .
			'{"type":"missing-end-tag","dsr":[36,55,6,0],"templateInfo":null,"params":{"name":"code","inTable":false}},' .
			'{"type":"multiple-unclosed-formatting-tags","dsr":[36,55,6,0],"templateInfo":null,"params":{"name":"code","inTable":false}},' .
			'{"type":"missing-end-tag","dsr":[45,55,6,0],"templateInfo":null,"params":{"name":"code","inTable":false}}' .
			']',
			200,
			[ 'content-type' => 'application/json' ],
			[ 'format' => ParsoidFormatHelper::FORMAT_LINT ]
		];

		// Perform language variant conversion //////////////////////////////////////////////////
		$request = new RequestData( [
				'pathParams' => [
					'from' => ParsoidFormatHelper::FORMAT_PAGEBUNDLE,
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
			[ 'format' => ParsoidFormatHelper::FORMAT_PAGEBUNDLE ]
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
		$expectedHeaders = [],
		$config = []
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
		$response = $this->executeHandler( $handler, $request, $config );
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
