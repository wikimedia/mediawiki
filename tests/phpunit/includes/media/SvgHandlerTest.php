<?php

use MediaWiki\MainConfigNames;
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
		$this->overrideConfigValue( MainConfigNames::ShowEXIF, true );

		$file = $this->dataFile( $filename, 'image/svg+xml' );
		$handler = new SvgHandler();
		$res = $handler->getCommonMetaArray( $file );

		self::assertEquals( $expected, $res );
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
			'deprecated MW code als' => [
				'userPreferredLanguage' => 'als',
				'svgLanguages' => [ 'en', 'als', 'gsw' ],
				'expectedMatch' => 'als',
			],
			'deprecated language code i-klingon' => [
				'userPreferredLanguage' => 'i-klingon',
				'svgLanguages' => [ 'i-klingon' ],
				'expectedMatch' => 'i-klingon',
			],
			'complex IETF language code' => [
				'userPreferredLanguage' => 'he',
				'svgLanguages' => [ 'he-IL-u-ca-hebrew-tz-jeruslm' ],
				'expectedMatch' => 'he-IL-u-ca-hebrew-tz-jeruslm',
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
				"lang should override targetlang even if it's in English"
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
				[ 'width' => 123, 'lang' => 'zh-Hans' ],
				'langzh-hans-123px',
				'Lowercase language codes',
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
	 */
	public function testNormaliseParamsInternal( $message,
		$width,
		$height,
		array $params,
		array $paramsExpected = null
	) {
		$this->overrideConfigValue( MainConfigNames::SVGMaxSize, 1000 );

		/** @var SvgHandler $handler */
		$handler = TestingAccessWrapper::newFromObject( new SvgHandler() );

		$file = $this->getMockBuilder( File::class )
			->disableOriginalConstructor()
			->onlyMethods( [ 'getWidth', 'getHeight', 'getMetadataArray', 'getHandler' ] )
			->getMock();

		$file->method( 'getWidth' )
			->willReturn( $width );
		$file->method( 'getHeight' )
			->willReturn( $height );
		$file->method( 'getMetadataArray' )
			->willReturn( [
				'version' => SvgHandler::SVG_METADATA_VERSION,
				'translations' => [
					'en' => SVGReader::LANG_FULL_MATCH,
					'ru' => SVGReader::LANG_FULL_MATCH,
				],
			] );
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
		$this->overrideConfigValue( MainConfigNames::SVGConverter, $converter );

		$handler = new SvgHandler();
		self::assertEquals( $expected, $handler->isEnabled() );
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
	 */
	public function testGetAvailableLanguages( array $metadata, array $expected ) {
		$metadata['version'] = SvgHandler::SVG_METADATA_VERSION;
		$file = $this->getMockBuilder( File::class )
			->disableOriginalConstructor()
			->onlyMethods( [ 'getMetadataArray' ] )
			->getMock();
		$file->method( 'getMetadataArray' )
			->willReturn( $metadata );

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
						'zh-Hans' => SVGReader::LANG_FULL_MATCH,
						'zh-Hans-TW' => SVGReader::LANG_FULL_MATCH,
						'he-IL-u-ca-hebrew-tz-jeruslm' => SVGReader::LANG_FULL_MATCH,
					],
				],
				[ 'en', 'ru', 'zh-hans', 'zh-hans-tw', 'he-il-u-ca-hebrew-tz-jeruslm' ],
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

	/**
	 * @covers SvgHandler::parseParamString()
	 * @dataProvider provideParseParamString
	 *
	 * @param string $paramString
	 * @param array $expected
	 * @param string $message
	 * @return void
	 */
	public function testParseParamString( string $paramString, $expected, $message ) {
		/** @var SvgHandler $handler */
		$handler = TestingAccessWrapper::newFromObject( new SvgHandler() );
		$params = $handler->parseParamString( $paramString );
		self::assertSame( $expected, $params, $message );
		if ( $params === false ) {
			return;
		}
		foreach ( $expected as $key => $value ) {
			self::assertArrayHasKey( $key, $params, $message );
			self::assertEquals( $value, $params[$key], $message );
		}
	}

	public function provideParseParamString() {
		return [
			[ '100px', [ 'width' => '100', 'lang' => 'en' ], 'Only width' ],
			[ 'langde-100px', [ 'width' => '100', 'lang' => 'de' ], 'German language and width' ],
			[ 'langzh-hans-100px', [ 'width' => '100', 'lang' => 'zh-hans' ], 'Chinese language and width' ],
			[ 'langzh-Hans-100px', false, 'Capitalized language code' ],
			[ 'langzh-TW-100px', false, 'Deprecated MW language code' ],
			[ 'langund-100px', [ 'width' => '100', 'lang' => 'und' ], 'Undetermined language code' ],
			[ 'langzh-%25-100px', false, 'Invalid IETF language code' ],
			[ 'langhe-il-u-ca-hebrew-tz-jeruslm-100px',
				[ 'width' => '100', 'lang' => 'he-il-u-ca-hebrew-tz-jeruslm' ],
				'Very complex IETF language code'
			],
		];
	}
}
