<?php

use MediaWiki\MainConfigNames;
use Wikimedia\TestingAccessWrapper;

class ImagePageTest extends MediaWikiMediaTestCase {

	protected function setUp(): void {
		$this->overrideConfigValue(
			MainConfigNames::ImageLimits,
			[
				[ 320, 240 ],
				[ 640, 480 ],
				[ 800, 600 ],
				[ 1024, 768 ],
				[ 1280, 1024 ]
			]
		);
		parent::setUp();
	}

	public function getImagePage( $filename ) {
		$title = Title::makeTitleSafe( NS_FILE, $filename );
		$file = $this->dataFile( $filename );
		$iPage = new ImagePage( $title );
		$iPage->setFile( $file );
		return $iPage;
	}

	/**
	 * @covers ImagePage::getThumbSizes
	 * @dataProvider providerGetThumbSizes
	 * @param string $filename
	 * @param int $expectedNumberThumbs How many thumbnails to show
	 */
	public function testGetThumbSizes( $filename, $expectedNumberThumbs ) {
		$iPage = $this->getImagePage( $filename );
		$reflection = new ReflectionClass( $iPage );
		$reflMethod = $reflection->getMethod( 'getThumbSizes' );
		$reflMethod->setAccessible( true );

		$actual = $reflMethod->invoke( $iPage, 545, 700 );
		$this->assertCount( $expectedNumberThumbs, $actual );
	}

	public function providerGetThumbSizes() {
		return [
			[ 'animated.gif', 2 ],
			[ 'Toll_Texas_1.svg', 1 ],
			[ '80x60-Greyscale.xcf', 1 ],
			[ 'jpeg-comment-binary.jpg', 2 ],
		];
	}

	/**
	 * @covers ImagePage::getLanguageForRendering()
	 * @dataProvider provideGetLanguageForRendering
	 *
	 * @param string|null $expected Expected IETF language code
	 * @param string $wikiLangCode Wiki language code (zh)
	 * @param string|null $wikiLangVariant Wiki language code variant (zh-cn)
	 * @param string|null $lang lang=... URL parameter
	 */
	public function testGetLanguageForRendering( $expected, $wikiLangCode, $wikiLangVariant = null, $lang = null ) {
		$params = [];
		if ( $lang !== null ) {
			$params['lang'] = $lang;
		}
		$request = new FauxRequest( $params );
		$this->overrideConfigValues( [
			MainConfigNames::LanguageCode => $wikiLangCode,
			MainConfigNames::DefaultLanguageVariant => $wikiLangVariant
		] );

		$page = $this->getImagePage( 'translated.svg' );
		$page = TestingAccessWrapper::newFromObject( $page );

		/** @var ImagePage $page */
		$result = $page->getLanguageForRendering( $request, $page->getDisplayedFile() );
		$this->assertEquals( $expected, $result );
	}

	public function provideGetLanguageForRendering() {
		return [
			[ 'ru', 'ru' ],
			[ 'ru', 'ru', null, 'ru' ],
			[ null, 'en' ],
			[ null, 'fr' ],
			[ null, 'en', null, 'en' ],
			[ null, 'fr', null, 'fr' ],
			[ null, 'ru', null, 'en' ],
			[ 'de', 'ru', null, 'de' ],
			[ 'gsw', 'als' ], /* als MW lang code (which is not a valid IETF lang code) */
			[ 'als', 'en', null, 'als' ], /* als IETF lang code */
			[ 'zh-hans-cn', 'zh', 'zh-cn' ],
			[ 'zh-hant-tw', 'zh', 'zh-tw' ],
			[ 'zh-hans-cn', 'zh-Hans-cn' ], /* Should not happen, not a MW lang code */
			[ 'zh-hans-cn', 'de', null, 'zh-Hans' ],
			[ null, 'de', null, 'zh-cn' ], /* MW language code via param */
			[ 'zh-hans-cn', 'zh', 'zh-cn', 'zh' ],
			[ 'zh-hans-cn', 'zh', 'zh-cn', 'zh-Hans' ],
			[ 'zh-hans-cn', 'zh', 'zh-cn', 'zh-Hans-CN' ],
			[ 'zh-hant-tw', 'zh', 'zh-tw', 'zh-Hant-TW' ],
			[ 'zh-hant-tw', 'zh', 'zh-cn', 'zh-Hant-TW' ],
		];
	}
}
