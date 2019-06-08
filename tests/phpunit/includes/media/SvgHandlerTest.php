<?php

use Wikimedia\TestingAccessWrapper;

/**
 * @group Media
 */
class SvgHandlerTest extends MediaWikiMediaTestCase {

	/**
	 * @covers \SvgHandler::getCommonMetaArray()
	 * @dataProvider provideGetIndependentMetaArray
	 *
	 * @param string $filename
	 * @param array $expected The expected independent metadata
	 */
	public function testGetIndependentMetaArray( $filename, $expected ) {
		$this->filePath = __DIR__ . '/../../data/media/';
		$this->setMwGlobals( 'wgShowEXIF', true );

		$file = $this->dataFile( $filename, 'image/svg+xml' );
		$handler = new SvgHandler();
		$res = $handler->getCommonMetaArray( $file );

		self::assertEquals( $res, $expected );
	}

	public static function provideGetIndependentMetaArray() {
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
	 * @covers SvgHandler::getMatchedLanguage()
	 * @dataProvider provideGetMatchedLanguage
	 *
	 * @param string $userPreferredLanguage
	 * @param array $svgLanguages
	 * @param string $expectedMatch
	 */
	public function testGetMatchedLanguage( $userPreferredLanguage, $svgLanguages, $expectedMatch ) {
		$handler = new SvgHandler();
		$match = $handler->getMatchedLanguage( $userPreferredLanguage, $svgLanguages );
		self::assertEquals( $expectedMatch, $match );
	}

	public function provideGetMatchedLanguage() {
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

	/**
	 * @covers \SvgHandler::makeParamString()
	 * @dataProvider provideMakeParamString
	 *
	 * @param array $params
	 * @param string $expected
	 * @param string $message
	 */
	public function testMakeParamString( array $params, $expected, $message = '' ) {
		$handler = new SvgHandler();
		self::assertEquals( $expected, $handler->makeParamString( $params ), $message );
	}

	public function provideMakeParamString() {
		return [
			[
				[],
				false,
				"Don't thumbnail without knowing width"
			],
			[
				[ 'lang' => 'ru' ],
				false,
				"Don't thumbnail without knowing width, even with lang"
			],
			[
				[ 'width' => 123, ],
				'123px',
				'Width in thumb'
			],
			[
				[ 'width' => 123, 'lang' => 'en' ],
				'123px',
				'Ignore lang=en'
			],
			[
				[ 'width' => 123, 'targetlang' => 'en' ],
				'123px',
				'Ignore targetlang=en'
			],
			[
				[ 'width' => 123, 'lang' => 'en', 'targetlang' => 'ru' ],
				'123px',
				"lang should override targetlang even of it's in English"
			],
			[
				[ 'width' => 123, 'targetlang' => 'en' ],
				'123px',
				'Ignore targetlang=en'
			],
			[
				[ 'width' => 123, 'lang' => 'en', 'targetlang' => 'en' ],
				'123px',
				'Ignore lang=targetlang=en'
			],
			[
				[ 'width' => 123, 'lang' => 'ru' ],
				'langru-123px',
				'Include lang in thumb'
			],
			[
				[ 'width' => 123, 'targetlang' => 'ru' ],
				'langru-123px',
				'Include targetlang in thumb'
			],
			[
				[ 'width' => 123, 'lang' => 'fr', 'targetlang' => 'sq' ],
				'langfr-123px',
				'lang should override targetlang'
			],
		];
	}

	/**
	 * @covers SvgHandler::normaliseParamsInternal()
	 * @dataProvider provideNormaliseParamsInternal
	 *
	 * @param string $message
	 * @param int $width
	 * @param int $height
	 * @param array $params
	 * @param array $paramsExpected
	 */
	public function testNormaliseParamsInternal( $message,
		$width,
		$height,
		array $params,
		array $paramsExpected = null
	) {
		$this->setMwGlobals( 'wgSVGMaxSize', 1000 );

		/** @var SvgHandler $handler */
		$handler = TestingAccessWrapper::newFromObject( new SvgHandler() );

		$file = $this->getMockBuilder( File::class )
			->disableOriginalConstructor()
			->setMethods( [ 'getWidth', 'getHeight', 'getMetadata', 'getHandler' ] )
			->getMock();

		$file->method( 'getWidth' )
			->willReturn( $width );
		$file->method( 'getHeight' )
			->willReturn( $height );
		$file->method( 'getMetadata' )
			->willReturn( serialize( [
				'version' => SvgHandler::SVG_METADATA_VERSION,
				'translations' => [
					'en' => SVGReader::LANG_FULL_MATCH,
					'ru' => SVGReader::LANG_FULL_MATCH,
				],
			] ) );
		$file->method( 'getHandler' )
			->willReturn( $handler );

		/** @var File $file */
		$params = $handler->normaliseParamsInternal( $file, $params );
		self::assertEquals( $paramsExpected, $params, $message );
	}

	public function provideNormaliseParamsInternal() {
		return [
			[
				'No need to change anything',
				400, 500,
				[ 'physicalWidth' => 400, 'physicalHeight' => 500 ],
				[ 'physicalWidth' => 400, 'physicalHeight' => 500 ],
			],
			[
				'Resize horizontal image',
				2000, 1600,
				[ 'physicalWidth' => 2000, 'physicalHeight' => 1600, 'page' => 0 ],
				[ 'physicalWidth' => 1250, 'physicalHeight' => 1000, 'page' => 0 ],
			],
			[
				'Resize vertical image',
				1600, 2000,
				[ 'physicalWidth' => 1600, 'physicalHeight' => 2000, 'page' => 0 ],
				[ 'physicalWidth' => 1000, 'physicalHeight' => 1250, 'page' => 0 ],
			],
			[
				'Preserve targetlang present in the image',
				400, 500,
				[ 'physicalWidth' => 400, 'physicalHeight' => 500, 'targetlang' => 'en' ],
				[ 'physicalWidth' => 400, 'physicalHeight' => 500, 'targetlang' => 'en' ],
			],
			[
				'Preserve targetlang present in the image 2',
				400, 500,
				[ 'physicalWidth' => 400, 'physicalHeight' => 500, 'targetlang' => 'en' ],
				[ 'physicalWidth' => 400, 'physicalHeight' => 500, 'targetlang' => 'en' ],
			],
			[
				'Remove targetlang not present in the image',
				400, 500,
				[ 'physicalWidth' => 400, 'physicalHeight' => 500, 'targetlang' => 'de' ],
				[ 'physicalWidth' => 400, 'physicalHeight' => 500 ],
			],
			[
				'Remove targetlang not present in the image 2',
				400, 500,
				[ 'physicalWidth' => 400, 'physicalHeight' => 500, 'targetlang' => 'ru-UA' ],
				[ 'physicalWidth' => 400, 'physicalHeight' => 500 ],
			],
		];
	}

	/**
	 * @covers \SvgHandler::isEnabled()
	 * @dataProvider provideIsEnabled
	 *
	 * @param string $converter
	 * @param bool $expected
	 */
	public function testIsEnabled( $converter, $expected ) {
		$this->setMwGlobals( 'wgSVGConverter', $converter );

		$handler = new SvgHandler();
		self::assertEquals( $handler->isEnabled(), $expected );
	}

	public function provideIsEnabled() {
		return [
			[ 'ImageMagick', true ],
			[ 'sodipodi', true ],
			[ 'invalid', false ],
		];
	}

	/**
	 * @covers \SvgHandler::getAvailableLanguages()
	 * @dataProvider provideAvailableLanguages
	 *
	 * @param array $metadata
	 * @param array $expected
	 */
	public function testGetAvailableLanguages( array $metadata, array $expected ) {
		$metadata['version'] = SvgHandler::SVG_METADATA_VERSION;
		$file = $this->getMockBuilder( File::class )
			->disableOriginalConstructor()
			->setMethods( [ 'getMetadata' ] )
			->getMock();
		$file->method( 'getMetadata' )
			->willReturn( serialize( $metadata ) );

		$handler = new SvgHandler();
		/** @var File $file */
		self::assertEquals( $expected, $handler->getAvailableLanguages( $file ) );
	}

	public function provideAvailableLanguages() {
		return [
			[ [], [] ],
			[ [ 'translations' => [] ], [] ],
			[
				[
					'translations' => [
						'ru-RU' => SVGReader::LANG_PREFIX_MATCH
					]
				],
				[],
			],
			[
				[
					'translations' => [
						'en' => SVGReader::LANG_FULL_MATCH,
						'ru-RU' => SVGReader::LANG_PREFIX_MATCH,
						'ru' => SVGReader::LANG_FULL_MATCH,
						'fr-CA' => SVGReader::LANG_PREFIX_MATCH,
					],
				],
				[ 'en', 'ru' ],
			],
		];
	}

	/**
	 * @covers SvgHandler::getLanguageFromParams()
	 * @dataProvider provideGetLanguageFromParams
	 *
	 * @param array $params
	 * @param string $expected
	 * @param string $message
	 */
	public function testGetLanguageFromParams( array $params, $expected, $message ) {
		/** @var SvgHandler $handler */
		$handler = TestingAccessWrapper::newFromObject( new SvgHandler() );
		self::assertEquals( $expected, $handler->getLanguageFromParams( $params ), $message );
	}

	public function provideGetLanguageFromParams() {
		return [
			[ [], 'en', 'Default no language to en' ],
			[ [ 'preserve' => 'this' ], 'en', 'Default no language to en 2' ],
			[ [ 'preserve' => 'this', 'lang' => 'ru' ], 'ru', 'Language from lang' ],
			[ [ 'lang' => 'ru' ], 'ru', 'Language from lang 2' ],
			[ [ 'targetlang' => 'fr' ], 'fr', 'Language from targetlang' ],
			[ [ 'lang' => 'fr', 'targetlang' => 'de' ], 'fr', 'lang overrides targetlang' ],
		];
	}
}
