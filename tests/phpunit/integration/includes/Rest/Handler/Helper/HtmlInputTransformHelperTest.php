<?php

namespace MediaWiki\Tests\Rest\Handler\Helper;

use Exception;
use LogicException;
use MediaWiki\Content\TextContent;
use MediaWiki\Content\WikitextContent;
use MediaWiki\Edit\ParsoidRenderID;
use MediaWiki\Edit\SelserContext;
use MediaWiki\MainConfigNames;
use MediaWiki\MainConfigSchema;
use MediaWiki\Message\TextFormatter;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\Parsoid\HtmlToContentTransform;
use MediaWiki\Parser\Parsoid\HtmlTransformFactory;
use MediaWiki\Parser\Parsoid\PageBundleParserOutputConverter;
use MediaWiki\Rest\Handler\Helper\HtmlInputTransformHelper;
use MediaWiki\Rest\Handler\Helper\ParsoidFormatHelper;
use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\ResponseFactory;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWikiIntegrationTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Wikimedia\Bcp47Code\Bcp47Code;
use Wikimedia\Message\MessageValue;
use Wikimedia\Parsoid\Core\ClientError;
use Wikimedia\Parsoid\Core\PageBundle;
use Wikimedia\Parsoid\Core\ResourceLimitExceededException;
use Wikimedia\Parsoid\Parsoid;
use Wikimedia\Parsoid\Utils\ContentUtils;
use Wikimedia\Stats\StatsFactory;

/**
 * @covers \MediaWiki\Rest\Handler\Helper\HtmlInputTransformHelper
 * @group Database
 */
class HtmlInputTransformHelperTest extends MediaWikiIntegrationTestCase {
	private const CACHE_EPOCH = '20001111010101';

	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValue( MainConfigNames::CacheEpoch, self::CACHE_EPOCH );
	}

	/**
	 * @param array $methodOverrides
	 *
	 * @return MockObject|HtmlTransformFactory
	 */
	public function newMockHtmlTransformFactory( $methodOverrides = [] ): HtmlTransformFactory {
		$factory = $this->createNoOpMock( HtmlTransformFactory::class, [ 'getHtmlToContentTransform' ] );

		$factory->method( 'getHtmlToContentTransform' )->willReturnCallback(
			function ( $html ) use ( $methodOverrides ) {
				return $this->newHtmlToContentTransform( $html, $methodOverrides );
			}
		);

		return $factory;
	}

	/**
	 * @param array $transformMethodOverrides
	 * @param StatsFactory|null $stats
	 * @param ?PageIdentity $page
	 * @param array|string $body Body structure, or an HTML string
	 * @param array $parameters
	 * @param RevisionRecord|null $originalRevision
	 * @param Bcp47Code|null $pageLanguage
	 *
	 * @return HtmlInputTransformHelper
	 * @throws Exception
	 */
	private function newHelper(
		array $transformMethodOverrides = [],
		?StatsFactory $stats = null,
		?PageIdentity $page = null,
		$body = '',
		array $parameters = [],
		?RevisionRecord $originalRevision = null,
		?Bcp47Code $pageLanguage = null
	): HtmlInputTransformHelper {
		// TODO: $cache = $cache ?: new EmptyBagOStuff();
		// TODO: $stash = new SimpleParsoidOutputStash( $cache, 1 );

		$stats = $stats ?? StatsFactory::newNull();
		return new HtmlInputTransformHelper(
			$stats,
			$this->newMockHtmlTransformFactory( $transformMethodOverrides ),
			$this->getServiceContainer()->getParsoidOutputStash(),
			$this->getServiceContainer()->getParserOutputAccess(),
			$this->getServiceContainer()->getPageStore(),
			$this->getServiceContainer()->getRevisionLookup(),
			[], /* envOptions */
			$page,
			$body,
			$parameters,
			$originalRevision,
			$pageLanguage
		);
	}

	private function getTextFromFile( string $name ): string {
		return trim( file_get_contents( __DIR__ . "/../data/Transform/$name" ) );
	}

	private function getJsonFromFile( string $name ): array {
		$text = $this->getTextFromFile( $name );
		return json_decode( $text, JSON_OBJECT_AS_ARRAY );
	}

	public function provideRequests() {
		$profileVersion = '2.4.0';
		$wikitextProfileUri = 'https://www.mediawiki.org/wiki/Specs/wikitext/1.0.0';
		$htmlProfileUri = 'https://www.mediawiki.org/wiki/Specs/HTML/' . $profileVersion;
		$dataParsoidProfileUri = 'https://www.mediawiki.org/wiki/Specs/data-parsoid/' . $profileVersion;

		$wikiTextContentType = "text/plain; charset=utf-8; profile=\"$wikitextProfileUri\"";
		$htmlContentType = "text/html;profile=\"$htmlProfileUri\"";
		$dataParsoidContentType = "application/json;profile=\"$dataParsoidProfileUri\"";

		$htmlHeaders = [
			'content-type' => $htmlContentType,
		];

		// NOTE: profile version 999 is a placeholder for a future feature, see T78676
		$htmlContentType999 = 'text/html;profile="https://www.mediawiki.org/wiki/Specs/HTML/999.0.0"';
		$htmlHeaders999 = [
			'content-type' => $htmlContentType999,
		];

		// should convert html to wikitext ///////////////////////////////////
		$html = $this->getTextFromFile( 'MainPage-data-parsoid.html' );
		$expectedText = [
			'MediaWiki has been successfully installed',
			'== Getting started ==',
		];

		$params = [];
		$body = [ 'html' => $html ];
		yield 'should convert html to wikitext' => [
			$body,
			$params,
			$expectedText,
		];

		// should load original wikitext by revision id ////////////////////
		$params = [
			'oldid' => 1, // will be replaced by the actual revid
		];
		$body = [ 'html' => $html ];
		yield 'should load original wikitext by revision id' => [
			$body,
			$params,
			$expectedText,
		];

		// should accept original wikitext in body ////////////////////
		$originalWikitext = $this->getTextFromFile( 'OriginalMainPage.wikitext' );
		$params = [];
		$body = [
			'html' => $html,
			'original' => [
				'wikitext' => [
					'headers' => [
						'content-type' => $wikiTextContentType,
					],
					'body' => $originalWikitext,
				]
			]
		];
		yield 'should accept original wikitext in body' => [
			$body,
			$params,
			$expectedText, // TODO: ensure it's actually used!
		];

		// should use original html for selser (default) //////////////////////
		$originalDataParsoid = $this->getJsonFromFile( 'MainPage-original.data-parsoid' );
		$params = [
			'from' => ParsoidFormatHelper::FORMAT_PAGEBUNDLE,
		];
		$body = [
			'html' => $html,
			'original' => [
				'html' => [
					'headers' => $htmlHeaders,
					'body' => $this->getTextFromFile( 'MainPage-original.html' ),
				],
				'data-parsoid' => [
					'headers' => [
						'content-type' => $dataParsoidContentType,
					],
					'body' => $originalDataParsoid
				]
			]
		];
		yield 'should use original html for selser (default)' => [
			$body,
			$params,
			$expectedText,
		];

		// should use original html for selser (1.1.1, meta) ///////////////////
		$params = [];
		$body = [
			'html' => $html,
			'original' => [
				'html' => [
					'headers' => [
						// XXX: If this is required anyway, how do we know we are using the
						//      version given in the HTML?
						'content-type' => 'text/html; profile="mediawiki.org/specs/html/1.1.1"',
					],
					'body' => $this->getTextFromFile( 'MainPage-data-parsoid-1.1.1.html' ),
				],
				'data-parsoid' => [
					'headers' => [
						'content-type' => $dataParsoidContentType,
					],
					'body' => $originalDataParsoid
				]
			]
		];
		yield 'should use original html for selser (1.1.1, meta)' => [
			$body,
			$params,
			$expectedText,
		];

		// should accept original html for selser (1.1.1, headers) ////////////
		$params = [
			'from' => ParsoidFormatHelper::FORMAT_PAGEBUNDLE,
		];
		$body = [
			'html' => $html,
			'original' => [
				'html' => [
					'headers' => [
						// Set the schema version to 1.1.1!
						'content-type' => 'text/html; profile="mediawiki.org/specs/html/1.1.1"',
					],
					// No schema version in HTML
					'body' => $this->getTextFromFile( 'MainPage-original.html' ),
				],
				'data-parsoid' => [
					'headers' => [
						'content-type' => $dataParsoidContentType,
					],
					'body' => $originalDataParsoid
				]
			]
		];
		yield 'should use original html for selser (1.1.1, headers)' => [
			$body,
			$params,
			$expectedText,
		];

		// Return original wikitext when HTML doesn't change ////////////////////////////
		// New and old html are identical, which should produce no diffs
		// and reuse the original wikitext.
		$html = '<html><body id="mwAA"><div id="mwBB">Selser test</div></body></html>';
		$dataParsoid = [
			'ids' => [
				'mwAA' => [],
				'mwBB' => [ 'autoInsertedEnd' => true, 'stx' => 'html' ]
			]
		];

		$params = [
			'oldid' => 1, // Will be replaced by the revision ID of the default test page
		];
		$body = [
			'html' => $html,
			'original' => [
				'html' => [
					'headers' => $htmlHeaders,
					// original HTML is the same as the new HTML
					'body' => $html
				],
				'data-parsoid' => [
					'body' => $dataParsoid,
				]
			]
		];
		yield 'should use selser, return original wikitext because the HTML didn\'t change' => [
			$body,
			$params,
			null, // Returns original wikitext, because HTML didn't change.
		];

		// Should fall back to non-selective serialization. //////////////////
		// Without the original wikitext, use non-selective serialization.
		$params = [
			// No wikitext, no revid/oldid
			'from' => ParsoidFormatHelper::FORMAT_PAGEBUNDLE,
		];
		$body = [
			'html' => $html,
			'original' => [
				'html' => [
					'headers' => $htmlHeaders,
					// original HTML is the same as the new HTML
					'body' => $html
				],
				'data-parsoid' => [
					'body' => $dataParsoid,
				]
			]
		];
		yield 'Should fallback to non-selective serialization' => [
			$body,
			$params,
			[ '<div>Selser test' ],
		];

		// should apply data-parsoid to duplicated ids /////////////////////////
		$html = '<html><body id="mwAA"><div id="mwBB">data-parsoid test</div>' .
			'<div id="mwBB">data-parsoid test</div></body></html>';
		$originalHtml = '<html><body id="mwAA"><div id="mwBB">data-parsoid test</div></body></html>';

		$params = [];
		$body = [
			'html' => $html,
			'original' => [
				'html' => [
					'headers' => $htmlHeaders,
					'body' => $originalHtml
				],
				'data-parsoid' => [
					'body' => $dataParsoid,
				]
			]
		];
		yield 'should apply data-parsoid to duplicated ids' => [
			$body,
			$params,
			[ '<div>data-parsoid test<div>data-parsoid test' ],
		];

		// should ignore data-parsoid if the input format is given but not pagebundle //////////////
		$html = '<html><body id="mwAA"><div id="mwBB">data-parsoid test</div>' .
			'<div id="mwBB">data-parsoid test</div></body></html>';
		$originalHtml = '<html><body id="mwAA"><div id="mwBB">data-parsoid test</div></body></html>';

		$params = [
			'from' => ParsoidFormatHelper::FORMAT_HTML,
		];
		$body = [
			'html' => $html,
			'original' => [
				'html' => [
					'headers' => $htmlHeaders,
					'body' => $originalHtml
				],
				'data-parsoid' => [
					// This has 'autoInsertedEnd' => true, which would cause
					// closing </div> tags to be omitted.
					'body' => $dataParsoid,
				]
			]
		];
		yield 'should ignore data-parsoid if the input format is not pagebundle' => [
			$body,
			$params,
			[ '<div>data-parsoid test</div><div>data-parsoid test</div>' ],
		];

		// should apply original data-mw ///////////////////////////////////////
		$html = '<p about="#mwt1" typeof="mw:Transclusion" id="mwAQ">hi</p>';
		$originalHtml = '<p about="#mwt1" typeof="mw:Transclusion" id="mwAQ">ho</p>';
		$dataParsoid = [ 'ids' => [ 'mwAQ' => [ 'pi' => [ [ [ 'k' => '1' ] ] ] ] ] ];
		$dataMediaWiki = [
			'ids' => [
				'mwAQ' => [
					'parts' => [ [
						'template' => [
							'target' => [ 'wt' => '1x', 'href' => './Template:1x' ],
							'params' => [ '1' => [ 'wt' => 'hi' ] ],
							'i' => 0
						]
					] ]
				]
			]
		];
		$params = [];
		$body = [
			'html' => $html,
			'original' => [
				'html' => [
					'headers' => $htmlHeaders,
					'body' => $originalHtml,
				],
				'data-parsoid' => [
					'body' => $dataParsoid,
				],
				'data-mw' => [
					'body' => $dataMediaWiki,
				],
			],
		];
		yield 'should apply original data-mw' => [
			$body,
			$params,
			[ '{{1x|hi}}' ],
		];

		// should give precedence to inline data-mw over original ////////
		$html = '<p about="#mwt1" typeof="mw:Transclusion" data-mw=\'{"parts":[{"template":{"target":{"wt":"1x","href":"./Template:1x"},"params":{"1":{"wt":"hi"}},"i":0}}]}\' id="mwAQ">hi</p>';
		$originalHtml = '<p about="#mwt1" typeof="mw:Transclusion" id="mwAQ">ho</p>';
		$dataParsoid = [ 'ids' => [ 'mwAQ' => [ 'pi' => [ [ [ 'k' => '1' ] ] ] ] ] ];
		$dataMediaWiki = [ 'ids' => [ 'mwAQ' => [] ] ]; // Missing data-mw.parts!
		$params = [];
		$body = [
			'html' => $html,
			'original' => [
				'html' => [
					'headers' => $htmlHeaders,
					'body' => $originalHtml
				],
				'data-parsoid' => [
					'body' => $dataParsoid,
				],
				'data-mw' => [
					'body' => $dataMediaWiki,
				],
			]
		];
		yield 'should give precedence to inline data-mw over original' => [
			$body,
			$params,
			[ '{{1x|hi}}' ],
		];

		// should not apply original data-mw if modified is supplied ///////////
		$html = '<p about="#mwt1" typeof="mw:Transclusion" id="mwAQ">hi</p>';
		$originalHtml = '<p about="#mwt1" typeof="mw:Transclusion" id="mwAQ">ho</p>';
		$dataParsoid = [ 'ids' => [ 'mwAQ' => [ 'pi' => [ [ [ 'k' => '1' ] ] ] ] ] ];
		$dataMediaWiki = [ 'ids' => [ 'mwAQ' => [] ] ]; // Missing data-mw.parts!
		$dataMediaWikiModified = [
			'ids' => [
				'mwAQ' => [
					'parts' => [ [
						'template' => [
							'target' => [ 'wt' => '1x', 'href' => './Template:1x' ],
							'params' => [ '1' => [ 'wt' => 'hi' ] ],
							'i' => 0
						]
					] ]
				]
			]
		];
		$params = [];
		$body = [
			'html' => $html,
			'data-mw' => [ // modified data
				'body' => $dataMediaWikiModified,
			],
			'original' => [
				'html' => [
					'headers' => $htmlHeaders999,
					'body' => $originalHtml
				],
				'data-parsoid' => [
					'body' => $dataParsoid,
				],
				'data-mw' => [ // original data
					'body' => $dataMediaWiki,
				],
			]
		];
		yield 'should not apply original data-mw if modified is supplied' => [
			$body,
			$params,
			[ '{{1x|hi}}' ],
		];

		// should apply original data-mw when modified is absent (captions 1) ///////////
		$html = $this->getTextFromFile( 'Image.html' );
		$dataParsoid = [ 'ids' => [
			'mwAg' => [ 'optList' => [ [ 'ck' => 'caption', 'ak' => 'Testing 123' ] ] ],
			'mwAw' => [ 'a' => [ 'href' => './File:Foobar.jpg' ], 'sa' => [] ],
			'mwBA' => [
				'a' => [ 'resource' => './File:Foobar.jpg', 'height' => '28', 'width' => '240' ],
				'sa' => [ 'resource' => 'File:Foobar.jpg' ]
			]
		] ];
		$dataMediaWiki = [ 'ids' => [ 'mwAg' => [ 'caption' => 'Testing 123' ] ] ];

		$params = [];
		$body = [
			'html' => $html,
			'original' => [
				'data-parsoid' => [
					'body' => $dataParsoid,
				],
				'data-mw' => [ // original data
					'body' => $dataMediaWiki,
				],
				'html' => [
					'headers' => $htmlHeaders999,
					'body' => $html
				],
			]
		];
		yield 'should apply original data-mw when modified is absent (captions 1)' => [
			$body,
			$params,
			[ '[[File:Foobar.jpg|Testing 123]]' ],
		];

		// should give precedence to inline data-mw over modified (captions 2) /////////////
		$htmlModified = $this->getTextFromFile( 'Image-data-mw.html' );
		$dataMediaWikiModified = [
			'ids' => [
				'mwAg' => [ 'caption' => 'Testing 123' ]
			]
		];

		$params = [];
		$body = [
			'html' => $htmlModified, // modified HTML
			'data-mw' => [
				'body' => $dataMediaWikiModified,
			],
			'original' => [
				'data-parsoid' => [
					'body' => $dataParsoid,
				],
				'data-mw' => [ // original data
					'body' => $dataMediaWiki,
				],
				'html' => [
					'headers' => $htmlHeaders999,
					'body' => $html
				],
			]
		];
		yield 'should give precedence to inline data-mw over modified (captions 2)' => [
			$body,
			$params,
			[ '[[File:Foobar.jpg]]' ],
		];

		// should give precedence to modified data-mw over original (captions 3) /////////////
		$dataMediaWikiModified = [
			'ids' => [
				'mwAg' => []
			]
		];

		$params = [];
		$body = [
			'html' => $html,
			'data-mw' => [
				'body' => $dataMediaWikiModified,
			],
			'original' => [
				'data-parsoid' => [
					'body' => $dataParsoid,
				],
				'data-mw' => [ // original data
					'body' => $dataMediaWiki,
				],
				'html' => [
					'headers' => $htmlHeaders999,
					'body' => $html
				],
			]
		];
		yield 'should give precedence to modified data-mw over original (captions 3)' => [
			$body,
			$params,
			[ '[[File:Foobar.jpg]]' ],
		];

		// should apply extra normalizations ///////////////////
		$htmlModified = 'Foo<h2></h2>Bar';
		$params = [
			'opts' => [
				'original' => []
			],
		];
		$body = [ 'html' => $htmlModified ]; // modified HTML
		yield 'should apply extra normalizations' => [
			$body,
			$params,
			[ 'FooBar' ], // empty tag was stripped
		];

		// should apply version downgrade ///////////
		$htmlOfMinimal = $this->getTextFromFile( 'Minimal.html' ); // Uses profile version 2.4.0
		$params = [
			'from' => ParsoidFormatHelper::FORMAT_PAGEBUNDLE,
		];
		$body = [
			'html' => $htmlOfMinimal,
			'original' => [
				'html' => [
					'headers' => [
						// Specify newer profile version for original HTML
						'content-type' => 'text/html;profile="https://www.mediawiki.org/wiki/Specs/HTML/999.0.0"'
					],
					// The profile version given inline in the original HTML doesn't matter, it's ignored
					'body' => $htmlOfMinimal,
				],
				'data-parsoid' => [ 'body' => [ 'ids' => [] ] ],
				'data-mw' => [ 'body' => [ 'ids' => [] ] ], // required by version 999.0.0
			]
		];
		yield 'should apply version downgrade' => [
			$body,
			$params,
			[ '123' ]
		];

		// should not apply version downgrade if versions are the same ///////////
		$htmlOfMinimal = $this->getTextFromFile( 'Minimal.html' ); // Uses profile version 2.4.0
		$params = [];
		$body = [
			'html' => $htmlOfMinimal,
			'original' => [
				'html' => [
					'headers' => [
						// Specify the exact same version specified inline in Minimal.html 2.4.0
						'content-type' => 'text/html;profile="https://www.mediawiki.org/wiki/Specs/HTML/2.4.0"'
					],
					// The profile version given inline in the original HTML doesn't matter, it's ignored
					'body' => $htmlOfMinimal,
				],
				'data-parsoid' => [ 'body' => [ 'ids' => [] ] ],
			]
		];
		yield 'should not apply version downgrade if versions are the same' => [
			$body,
			$params,
			[ '123' ]
		];

		// should convert html to json ///////////////////////////////////
		$html = $this->getTextFromFile( 'JsonConfig.html' );
		$expectedText = [
			'{"a":4,"b":3}',
		];

		$params = [
			'contentmodel' => CONTENT_MODEL_JSON,
		];
		$body = [ 'html' => $html ];
		yield 'should convert html to json' => [
			$body,
			$params,
			$expectedText,
			[ 'content-type' => 'application/json' ],
		];

		// page bundle input should work with no original data present  ///////////
		$htmlOfMinimal = $this->getTextFromFile( 'Minimal.html' ); // Uses profile version 2.4.0
		$params = [];
		$body = [
			'html' => $htmlOfMinimal,
			'original' => [],
		];
		yield 'page bundle input should work with no original data present' => [
			$body,
			$params,
			[ '123' ]
		];
	}

	private function createResponse() {
		$responseFactory = new ResponseFactory( [ new TextFormatter( 'qqx' ) ] );
		$response = $responseFactory->create();
		return $response;
	}

	/**
	 * @param array $body
	 * @param array $params
	 * @param string|string[]|null $expectedText Null means use the original content.
	 * @param array $expectedHeaders
	 * @dataProvider provideRequests
	 * @covers \MediaWiki\Rest\Handler\Helper\HtmlInputTransformHelper
	 * @covers \MediaWiki\Parser\Parsoid\HtmlToContentTransform
	 */
	public function testResponse( $body, $params, $expectedText, array $expectedHeaders = [] ) {
		if ( !empty( $params['oldid'] ) ) {
			// If an oldid is set, run the test with an actual existing revision ID
			$originalContent = __METHOD__ . ' original content';
			$page = $this->getNonexistingTestPage();
			$this->editPage( $page, new WikitextContent( $originalContent ) );
			$page = $page->getTitle();
			$params['oldid'] = $page->getLatestRevID();
		} else {
			$page = PageIdentityValue::localIdentity( 7, NS_MAIN, $body['pageName'] ?? 'HtmlInputTransformHelperTest' );
			$originalContent = '';
		}

		$statsHelper = StatsFactory::newUnitTestingHelper();
		$statsFactory = $statsHelper->getStatsFactory();

		// TODO: find a way to test $pageLanguage
		$helper = $this->newHelper( [], $statsFactory, $page, $body, $params );

		$response = $this->createResponse();
		$helper->putContent( $response );

		foreach ( $expectedHeaders as $name => $value ) {
			$this->assertSame( $value, $response->getHeaderLine( $name ) );
		}

		$body = $response->getBody();
		$body->rewind();
		$text = $body->getContents();

		$expectedText ??= $originalContent;
		foreach ( (array)$expectedText as $exp ) {
			$this->assertStringContainsString( $exp, $text );
		}

		// Ensure that exactly one key with the given prefix is set.
		// This ensures that the number of keys set always adds up to 100%,
		// for any set of keys under this prefix.
		$this->assertMetricsCount( 1, $statsHelper, 'html_input_transform_total' );
	}

	private function assertMetricsCount( $expected, $statsHelper, string $selector ) {
		$this->assertSame(
			(float)$expected,
			$statsHelper->sum( $selector ),
			"\nMetrics buffer:\n" . implode( "\n", $statsHelper->getAllFormatted() ) . "\n"
		);
	}

	public function provideOriginal() {
		$unchangedPB = new PageBundle(
			$this->getTextFromFile( 'MainPage-original.html' ),
			$this->getJsonFromFile( 'MainPage-original.data-parsoid' ),
			null,
			Parsoid::defaultHTMLVersion()
		);

		$originalContent = new WikitextContent( 'Goats are great!' );
		$selserContext = new SelserContext( $unchangedPB, 0, $originalContent );

		$unchangedPO = PageBundleParserOutputConverter::parserOutputFromPageBundle( $unchangedPB );

		$renderID = new ParsoidRenderID( 0, 'testing' );

		yield 'no original data' => [
			$selserContext,
			null,
			null,
			[
				'MediaWiki has been successfully installed',
				'== Getting started ==',
			]
		];

		yield 'should load original wikitext by revision id' => [
			$selserContext,
			1, // will be replaced by the actual revid
			$unchangedPB, // Expect selser, since HTML didn't change.
			null, // Selser should preserve the original content.
		];

		$page = PageIdentityValue::localIdentity( 7, NS_MAIN, 'HtmlInputTransformHelperTest' );
		$rev = new MutableRevisionRecord( $page );
		$rev->setContent( SlotRecord::MAIN, new WikitextContent( 'Goats are great!' ) );
		yield 'should use wikitext from fake revision' => [
			$selserContext,
			$rev,
			$unchangedPO, // Expect selser, since HTML didn't change.
			'Goats are great!', // Text from the fake revision. Selser should preserve it.
		];

		yield 'should get original HTML from stash' => [
			$selserContext,
			$rev,
			$renderID, // Expect selser, since HTML didn't change.
			'Goats are great!', // Text from the fake revision. Selser should preserve it.
		];
	}

	/**
	 * @dataProvider provideOriginal
	 *
	 * @param SelserContext|null $stashed
	 * @param RevisionRecord|int|null $rev
	 * @param ParsoidRenderID|PageBundle|ParserOutput|null $originalRendering
	 * @param string|string[]|null $expectedText Null means use the original content
	 *
	 * @covers \MediaWiki\Rest\Handler\Helper\HtmlInputTransformHelper::setOriginal
	 */
	public function testSetOriginal( ?SelserContext $stashed, $rev, $originalRendering, $expectedText ) {
		if ( is_int( $rev ) && $rev > 0 ) {
			// If a revision ID is given, run the test with an actual existing revision ID
			$originalContent = __METHOD__ . ' original content';
			$page = $this->getNonexistingTestPage();
			$this->editPage( $page, new WikitextContent( $originalContent ) );
			$page = $page->getTitle();
			$revId = $page->getLatestRevID() ?: 0;
			$rev = $revId;
		} elseif ( $rev instanceof RevisionRecord ) {
			$originalContentObj = $rev->getContent( SlotRecord::MAIN );
			if ( !$originalContentObj instanceof TextContent ) {
				throw new LogicException( 'Not implemented' );
			}
			$originalContent = $originalContentObj->getText();
			$page = $rev->getPage();
			$revId = $rev->getId() ?: 0;
		} else {
			$originalContent = '';
			$page = PageIdentityValue::localIdentity( 7, NS_MAIN, 'HtmlInputTransformHelperTest' );
			$revId = 0;
		}

		if ( $stashed ) {
			$renderID = new ParsoidRenderID( $revId, 'testing' );
			$stash = $this->getServiceContainer()->getParsoidOutputStash();
			$stash->set( $renderID, $stashed );
		}

		$html = $this->getTextFromFile( 'MainPage-original.html' );

		$params = [];
		$body = [
			'html' => $html
		];

		$statsHelper = StatsFactory::newUnitTestingHelper();
		$statsFactory = $statsHelper->getStatsFactory();

		$helper = $this->newHelper( [], $statsFactory, $page, $body, $params );
		$helper->setOriginal( $rev, $originalRendering );

		$response = $this->createResponse();
		$helper->putContent( $response );

		$body = $response->getBody();
		$body->rewind();
		$text = $body->getContents();

		$expectedText ??= $originalContent;
		foreach ( (array)$expectedText as $exp ) {
			$this->assertStringContainsString( $exp, $text );
		}

		// Ensure that exactly one key with the given prefix is set.
		// This ensures that the number of keys set always adds up to 100%,
		// for any set of keys under this prefix.
		if ( $originalRendering instanceof ParsoidRenderID ) {
			// NOTE: This increments both
			// - first, original_html_given=false
			// - then, original_html_given=as_renderid
			$this->assertMetricsCount( 1, $statsHelper, 'html_input_transform_total{original_html_given=as_renderid}' );
		} elseif ( $rev || $originalRendering ) {
			// NOTE: This increments both
			// - first, original_html_given=false
			// - then, original_html_given=true
			$this->assertMetricsCount( 1, $statsHelper, 'html_input_transform_total{original_html_given=true}' );
		} else {
			$this->assertMetricsCount( 1, $statsHelper, 'html_input_transform_total{original_html_given=false}' );
		}
	}

	/**
	 * @covers \MediaWiki\Rest\Handler\Helper\HtmlInputTransformHelper::getTransform
	 */
	public function testGetTransform() {
		$page = PageIdentityValue::localIdentity( 7, NS_MAIN, 'HtmlInputTransformHelperTest' );
		$html = '<p>kittens are cute</p>';

		$params = [];
		$body = [
			'html' => $html
		];

		$helper = $this->newHelper( [], StatsFactory::newNull(), $page, $body, $params );

		$transform = $helper->getTransform();

		$this->assertStringContainsString( 'kittens', ContentUtils::toXML( $transform->getModifiedDocument() ) );
	}

	/**
	 * @covers \MediaWiki\Rest\Handler\Helper\HtmlInputTransformHelper
	 * @covers \MediaWiki\Parser\Parsoid\HtmlToContentTransform
	 */
	public function testResponseForFakeRevision() {
		$wikitext = 'Unsaved Revision Content';

		$html = $this->getTextFromFile( 'Minimal.html' );
		$page = PageIdentityValue::localIdentity( 7, NS_MAIN, $body['pageName'] ?? 'HtmlInputTransformHelperTest' );

		// Create a fake revision. Since the HTML didn't change, we expect to get back the content
		// we defined for this revision.
		$revision = new MutableRevisionRecord( $page );
		$revision->setContent( SlotRecord::MAIN, new WikitextContent( $wikitext ) );

		$params = [];
		$body = [
			'html' => $html,
			'original' => [
				'html' => [
					'headers' => [ 'content-type' => 'text/html;profile="https://www.mediawiki.org/wiki/Specs/HTML/2.4.0"' ],
					// original HTML is the same as the new HTML
					'body' => $html
				],
			]
		];

		$page = PageIdentityValue::localIdentity( 7, NS_MAIN, $body['pageName'] ?? 'HtmlInputTransformHelperTest' );

		$helper = $this->newHelper( [], StatsFactory::newNull(), $page, $body, $params, $revision );

		$response = $this->createResponse();
		$helper->putContent( $response );

		$body = $response->getBody();
		$body->rewind();

		// Since the HTML didn't change, we expect to get back the content of the fake revision.
		$this->assertSame( $wikitext, $body->getContents() );
	}

	public function testResponseWithRenderIdForExistingRevision() {
		$profileVersion = '2.4.0';
		$htmlProfileUri = 'https://www.mediawiki.org/wiki/Specs/HTML/' . $profileVersion;
		$htmlContentType = "text/html;profile=\"$htmlProfileUri\"";

		$htmlHeaders = [
			'content-type' => $htmlContentType,
		];

		$page = $this->getExistingTestPage();
		$oldWikitext = $page->getContent()->serialize();

		$html = $this->getTextFromFile( 'MainPage-original.html' );
		$dataParsoid = $this->getJsonFromFile( 'MainPage-original.data-parsoid' );

		$pb = new PageBundle(
			$html,
			$dataParsoid,
			[],
			$profileVersion,
			$htmlHeaders,
			CONTENT_MODEL_WIKITEXT
		);

		$eTag = '"' . $page->getLatest() . '/just-a-test/edit"';

		// Load the original data based on the ETag
		$body = [ 'html' => $html, 'original' => [ 'renderid' => $eTag ] ];
		$params = [];

		$stash = $this->getServiceContainer()->getParsoidOutputStash();
		$stash->set(
			ParsoidRenderID::newFromETag( $eTag ),
			new SelserContext( $pb, $page->getLatest() ),
		);

		$helper = $this->newHelper( [], StatsFactory::newNull(), $page, $body, $params );

		$content = $helper->getContent();

		// Assert that we get back the old wikitext, not wikitext derived from the HTML.
		// Since the supplied HTML is the same as the HTML in the stash, selser should
		// decide that there is nothing to do and return the wikitext unchanged.
		$this->assertSame( $oldWikitext, $content->serialize() );
	}

	public function testResponseWithRenderIdForUnsavedWikitext() {
		$profileVersion = '2.4.0';
		$htmlProfileUri = 'https://www.mediawiki.org/wiki/Specs/HTML/' . $profileVersion;
		$htmlContentType = "text/html;profile=\"$htmlProfileUri\"";

		$htmlHeaders = [
			'content-type' => $htmlContentType,
		];

		$page = $this->getNonexistingTestPage();

		$html = $this->getTextFromFile( 'MainPage-original.html' );
		$dataParsoid = $this->getJsonFromFile( 'MainPage-original.data-parsoid' );
		$oldWikitext = 'Fake old wikitext';

		$content = new WikitextContent( $oldWikitext );
		$pb = new PageBundle(
			$html,
			$dataParsoid,
			[],
			$profileVersion,
			$htmlHeaders,
			CONTENT_MODEL_WIKITEXT
		);

		// NOTE: Using 0 as the prefix in the ETag indicates that the content does
		// not correspond to a saved revision. Since we don't have a revision
		// ID that we could use to load the wikitext from the database,
		// the wikitext should be taken from the stash.
		// That is the behavior asserted by this test case.
		$eTag = '"0/just-a-test/edit"';

		// Load the original data based on the ETag
		$body = [ 'html' => $html, 'original' => [ 'renderid' => $eTag ] ];
		$params = [];

		$stash = $this->getServiceContainer()->getParsoidOutputStash();
		$stash->set(
			ParsoidRenderID::newFromETag( $eTag ),
			new SelserContext( $pb, 0, $content )
		);

		$helper = $this->newHelper( [], StatsFactory::newNull(), $page, $body, $params );

		$content = $helper->getContent();

		// Assert that we get back the old wikitext, not wikitext derived from the HTML.
		// Since the supplied HTML is the same as the HTML in the stash, selser should
		// decide that there is nothing to do and return the wikitext unchanged.
		$this->assertSame( $oldWikitext, $content->serialize() );
	}

	public function testETagWithBadUUIDFails() {
		$page = $this->getExistingTestPage();
		$html = 'whatever';

		// Call getParserOutput() to make sure a rendering is in the ParserCache.
		// Even though we find a rendering, it should be discarded because it doesn't match
		// the ETag.
		$access = $this->getServiceContainer()->getParserOutputAccess();
		$pageLookup = $this->getServiceContainer()->getPageStore();
		$popt = ParserOptions::newFromAnon();
		$popt->setUseParsoid();
		$access->getParserOutput( $pageLookup->getPageByReference( $page ), $popt )->getValue();

		$revid = $page->getLatest();
		$eTag = "\"$revid/nope-nope-nope\"";

		$body = [ 'html' => $html, 'original' => [ 'renderid' => $eTag ] ];
		$params = [];

		$this->expectException( HttpException::class );
		$this->expectExceptionCode( 412 );
		$helper = $this->newHelper( [], StatsFactory::newNull(), $page, $body, $params );
		$helper->getContent();
	}

	public function testETagWithBadRevIDFails() {
		$page = $this->getExistingTestPage();
		$html = 'whatever';

		// Non-Existing revision
		$eTag = "\"1111111/nope-nope-nope\"";

		$body = [ 'html' => $html, 'original' => [ 'renderid' => $eTag ] ];
		$params = [];

		$this->expectException( HttpException::class );
		$this->expectExceptionCode( 412 );
		$helper = $this->newHelper( [], StatsFactory::newNull(), $page, $body, $params );
		$helper->getContent();
	}

	public function testResponseWithRenderIDFallbackToParserCache() {
		// use wikitext that would be normalized without selser.
		$oldWikitext = '<p >testing</P>';
		$rev = $this->editPage( __METHOD__, $oldWikitext )->value['revision-record'];
		$page = $rev->getPage();

		$access = $this->getServiceContainer()->getParserOutputAccess();
		$pageLookup = $this->getServiceContainer()->getPageStore();

		$popt = ParserOptions::newFromAnon();
		$popt->setUseParsoid();
		$pout = $access->getParserOutput( $pageLookup->getPageByReference( $page ), $popt )->getValue();

		$key = ParsoidRenderID::newFromParserOutput( $pout )->getKey();
		$html = $pout->getRawText();

		// Load the original data based on the ETag
		$body = [ 'html' => $html, 'original' => [ 'renderid' => $key ] ];
		$params = [];

		// We are asking for a stash key that is not in the stash.
		// However, a rendering with the corresponding key is in the ParserCache.
		// Because of this, the code below will not throw to trigger a 412 response.
		$helper = $this->newHelper( [], StatsFactory::newNull(), $page, $body, $params );
		$content = $helper->getContent();

		// The wikitext should not have been normalized by re-serialization
		$this->assertSame( $oldWikitext, $content->serialize() );
	}

	public function testResponseWithRevisionIDFallbackToRendering() {
		// use wikitext that would be normalized without selser.
		$oldWikitext = '<p >testing</P>';
		$rev = $this->editPage( __METHOD__, $oldWikitext )->value['revision-record'];
		$page = $rev->getPage();

		$access = $this->getServiceContainer()->getParserOutputAccess();
		$pageLookup = $this->getServiceContainer()->getPageStore();

		$popt = ParserOptions::newFromAnon();
		$popt->setUseParsoid();
		$pout = $access->getParserOutput( $pageLookup->getPageByReference( $page ), $popt )->getValue();
		$html = $pout->getRawText();

		// Load the original data based on the ETag
		$body = [ 'html' => $html, 'original' => [ 'revid' => $rev->getId() ] ];
		$params = [];

		// We are asking for a stash key that is not in the stash.
		// However, a rendering with the corresponding key is in the ParserCache.
		// Because of this, the code below will not trigger a 412 response.
		$helper = $this->newHelper( [], StatsFactory::newNull(), $page, $body, $params );
		$content = $helper->getContent();

		// The wikitext should not have been normalized by re-serialization
		$this->assertSame( $oldWikitext, $content->serialize() );
	}

	public static function provideHandlesParsoidError() {
		yield 'ClientError' => [
			new ClientError( 'TEST_TEST' ),
			new LocalizedHttpException(
				new MessageValue( 'rest-html-backend-error' ),
				400,
				[
					'reason' => 'TEST_TEST'
				]
			)
		];
		yield 'ResourceLimitExceededException' => [
			new ResourceLimitExceededException( 'TEST_TEST' ),
			new LocalizedHttpException(
				new MessageValue( 'rest-resource-limit-exceeded' ),
				413,
				[
					'reason' => 'TEST_TEST'
				]
			)
		];
	}

	/**
	 * @dataProvider provideHandlesParsoidError
	 */
	public function testHandlesParsoidError(
		Exception $parsoidException,
		Exception $expectedException
	) {
		$page = $this->getExistingTestPage( __METHOD__ );

		$body = [ 'html' => 'hi', ];
		$params = [];

		$helper = $this->newHelper( [
			'htmlToContent' => static function () use ( $parsoidException ) {
				throw $parsoidException;
			}
		], StatsFactory::newNull(), $page, $body, $params );

		$this->expectExceptionObject( $expectedException );
		$helper->getContent();
	}

	public function testHandlesInvalidRenderID(): void {
		$page = $this->getExistingTestPage( __METHOD__ );

		$body = [ 'html' => 'hi', 'original' => [ 'renderid' => 'foo' ] ];
		$params = [];

		$this->expectExceptionObject( new LocalizedHttpException(
			new MessageValue( 'rest-parsoid-bad-render-id', [ 'foo' ] ),
			400
		) );

		$this->newHelper( [], StatsFactory::newNull(), $page, $body, $params );
	}

	private function newHtmlToContentTransform( $html, $methodOverrides = [] ): HtmlToContentTransform {
		$transform = $this->getMockBuilder( HtmlToContentTransform::class )
			->onlyMethods( array_keys( $methodOverrides ) )
			->setConstructorArgs( [
				$html,
				$this->getExistingTestPage(),
				new Parsoid(
					$this->getServiceContainer()->getParsoidSiteConfig(),
					$this->getServiceContainer()->getParsoidDataAccess()
				),
				MainConfigSchema::getDefaultValue( MainConfigNames::ParsoidSettings ),
				$this->getServiceContainer()->getParsoidPageConfigFactory(),
				$this->getServiceContainer()->getContentHandlerFactory()
			] )
			->getMock();

		foreach ( $methodOverrides as $method => $callback ) {
			$transform->method( $method )->willReturnCallback( $callback );
		}

		return $transform;
	}

}
