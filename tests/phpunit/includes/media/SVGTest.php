<?php

/**
 * @group Media
 */
class SVGTest extends MediaWikiMediaTestCase {

	/**
	 * @var SvgHandler
	 */
	private $handler;

	protected function setUp() {
		parent::setUp();

		$this->filePath = __DIR__ . '/../../data/media/';

		$this->setMwGlobals( 'wgShowEXIF', true );

		$this->handler = new SvgHandler;
	}

	/**
	 * @param string $filename
	 * @param array $expected The expected independent metadata
	 * @dataProvider providerGetIndependentMetaArray
	 * @covers SvgHandler::getCommonMetaArray
	 */
	public function testGetIndependentMetaArray( $filename, $expected ) {
		$file = $this->dataFile( $filename, 'image/svg+xml' );
		$res = $this->handler->getCommonMetaArray( $file );

		$this->assertEquals( $res, $expected );
	}

	public static function providerGetIndependentMetaArray() {
		return [
			[ 'Tux.svg', [
				'ObjectName' => 'Tux',
				'ImageDescription' =>
					'For more information see: http://commons.wikimedia.org/wiki/Image:Tux.svg',
			] ],
			[ 'Wikimedia-logo.svg', [] ]
		];
	}

	/**
	 * @param string $userPreferredLanguage
	 * @param array $svgLanguages
	 * @param string $expectedMatch
	 * @dataProvider providerGetMatchedLanguage
	 * @covers SvgHandler::getMatchedLanguage
	 */
	public function testGetMatchedLanguage( $userPreferredLanguage, $svgLanguages, $expectedMatch ) {
		$match = $this->handler->getMatchedLanguage( $userPreferredLanguage, $svgLanguages );
		$this->assertEquals( $expectedMatch, $match );
	}

	public function providerGetMatchedLanguage() {
		return [
			'no match' => [
				'userPreferredLanguage' => 'en',
				'svgLanguages' => [ 'de-DE', 'zh', 'ga', 'fr', 'sr-Latn-ME' ],
				'expectedMatch' => null,
			],
			'no subtags' => [
				'userPreferredLanguage' => 'en',
				'svgLanguages' => [ 'de', 'zh', 'en', 'fr' ],
				'expectedMatch' => 'en',
			],
			'user no subtags, svg 1 subtag' => [
				'userPreferredLanguage' => 'en',
				'svgLanguages' => [ 'de-DE', 'en-GB', 'en-US', 'fr' ],
				'expectedMatch' => 'en-GB',
			],
			'user no subtags, svg >1 subtag' => [
				'userPreferredLanguage' => 'sr',
				'svgLanguages' => [ 'de-DE', 'sr-Cyrl-BA', 'sr-Latn-ME', 'en-US', 'fr' ],
				'expectedMatch' => 'sr-Cyrl-BA',
			],
			'user 1 subtag, svg no subtags' => [
				'userPreferredLanguage' => 'en-US',
				'svgLanguages' => [ 'de', 'en', 'en', 'fr' ],
				'expectedMatch' => null,
			],
			'user 1 subtag, svg 1 subtag' => [
				'userPreferredLanguage' => 'en-US',
				'svgLanguages' => [ 'de-DE', 'en-GB', 'en-US', 'fr' ],
				'expectedMatch' => 'en-US',
			],
			'user 1 subtag, svg >1 subtag' => [
				'userPreferredLanguage' => 'sr-Latn',
				'svgLanguages' => [ 'de-DE', 'sr-Cyrl-BA', 'sr-Latn-ME', 'fr' ],
				'expectedMatch' => 'sr-Latn-ME',
			],
			'user >1 subtag, svg >1 subtag' => [
				'userPreferredLanguage' => 'sr-Latn-ME',
				'svgLanguages' => [ 'de-DE', 'sr-Cyrl-BA', 'sr-Latn-ME', 'en-US', 'fr' ],
				'expectedMatch' => 'sr-Latn-ME',
			],
			'user >1 subtag, svg <=1 subtag' => [
				'userPreferredLanguage' => 'sr-Latn-ME',
				'svgLanguages' => [ 'de-DE', 'sr-Cyrl', 'sr-Latn', 'en-US', 'fr' ],
				'expectedMatch' => null,
			],
			'ensure case-insensitive' => [
				'userPreferredLanguage' => 'sr-latn',
				'svgLanguages' => [ 'de-DE', 'sr-Cyrl', 'sr-Latn-ME', 'en-US', 'fr' ],
				'expectedMatch' => 'sr-Latn-ME',
			],
		];
	}
}
