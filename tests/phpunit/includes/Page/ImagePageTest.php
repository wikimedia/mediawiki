<?php

use MediaWiki\FileRepo\File\UnregisteredLocalFile;
use MediaWiki\FileRepo\FileRepo;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\ImagePage;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Title\Title;
use Wikimedia\Parsoid\Core\SectionMetadata;
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

	public function getImagePage( $filename, array $extraRepoOptions = [] ) {
		$title = Title::makeTitleSafe( NS_FILE, $filename );
		$title->setContentModel( CONTENT_MODEL_WIKITEXT );
		if ( $extraRepoOptions ) {
			$repo = new FileRepo( $extraRepoOptions + $this->getRepoOptions() );
			$file = new UnregisteredLocalFile( false, $repo, "mwstore://localtesting/data/$filename" );
		} else {
			$file = $this->dataFile( $filename );
		}
		$iPage = new ImagePage( $title );
		$iPage->setFile( $file );
		return $iPage;
	}

	/**
	 * @covers \MediaWiki\Page\ImagePage::getThumbSizes
	 * @dataProvider providerGetThumbSizes
	 * @param string $filename
	 * @param int $expectedNumberThumbs How many thumbnails to show
	 */
	public function testGetThumbSizes( $filename, $expectedNumberThumbs ) {
		/** @var ImagePage $iPage */
		$iPage = TestingAccessWrapper::newFromObject( $this->getImagePage( $filename ) );
		$this->overrideConfigValue( MainConfigNames::SVGNativeRendering, false );

		$actual = $iPage->getThumbSizes( 545, 700 );
		$this->assertCount( $expectedNumberThumbs, $actual );
	}

	public static function providerGetThumbSizes() {
		return [
			[ 'animated.gif', 2 ],
			[ 'Toll_Texas_1.svg', 1 ],
			[ '80x60-Greyscale.xcf', 1 ],
			[ 'jpeg-comment-binary.jpg', 2 ],
		];
	}

	/**
	 * The label has to describe the thumbnail that the URL points at, whether
	 * the requested width is rounded up to a thumbnail step (T401668) or that
	 * step turns out to be beyond what the source can produce (T432193).
	 *
	 * @covers \MediaWiki\Page\ImagePage::makeSizeLink
	 * @dataProvider provideMakeSizeLink
	 * @param string $filename
	 * @param int[] $thumbnailSteps
	 * @param int $requestedWidth
	 * @param string $expectedLabel
	 * @param string $expectedThumbName Name of the file the link points at
	 */
	public function testMakeSizeLink(
		$filename, $thumbnailSteps, $requestedWidth, $expectedLabel, $expectedThumbName
	) {
		$this->overrideConfigValues( [
			MainConfigNames::ThumbnailSteps => $thumbnailSteps,
			// Pick a scaler that does not depend on which image extensions are
			// available. Nothing is executed, as the transform is deferred.
			MainConfigNames::UseImageMagick => true,
		] );

		// transformVia404 so that the thumbnail is described but not rendered.
		$iPage = TestingAccessWrapper::newFromObject(
			$this->getImagePage( $filename, [ 'transformVia404' => true ] )
		);

		// A tall bounding box, so that the width is the limiting dimension.
		$link = $iPage->makeSizeLink( [], $requestedWidth, 1000 );
		$this->assertMatchesRegularExpression(
			'/>\s*' . preg_quote( $expectedLabel, '/' ) . '\s*<\/a>/u', $link );
		$this->assertSame( 1, preg_match( '/href="([^"]+)"/', $link, $matches ) );
		$this->assertSame( $expectedThumbName, basename( $matches[1] ) );
	}

	public static function provideMakeSizeLink() {
		return [
			// adobergb.jpg is 120x78, and is web-safe, so it does not mustRender().
			'rounded up to a thumbnail step' => [
				'adobergb.jpg', [ 50, 100, 200 ], 60,
				'100 × 65 pixels', '100px-adobergb.jpg',
			],
			'step past the source width, web-safe: served as the original' => [
				'adobergb.jpg', [ 50, 100, 200 ], 110,
				'120 × 78 pixels', 'adobergb.jpg',
			],
			// 80x60-Greyscale.xcf is 80x60 and mustRender(), so a step past the
			// source width is rounded back down to the step below it instead.
			// This is the shape of the PDF in T432193, which was labelled with
			// the step above the source width while linking the one below it.
			'step past the source width, must render: served one step down' => [
				'80x60-Greyscale.xcf', [ 50, 100 ], 80,
				'50 × 38 pixels', '50px-80x60-Greyscale.xcf.png',
			],
		];
	}

	/**
	 * @covers \MediaWiki\Page\ImagePage::getLanguageForRendering()
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

	public static function provideGetLanguageForRendering() {
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

	/**
	 * @covers \MediaWiki\Page\ImagePage::getFileTOCSections
	 */
	public function testGetFileTOCSections() {
		/** @var ImagePage $page */
		$page = TestingAccessWrapper::newFromObject( $this->getImagePage( 'animated.gif' ) );

		$withMetadata = $page->getFileTOCSections( true );
		$this->assertSame(
			[ 'file', 'filehistory', 'filelinks', 'metadata' ],
			array_column( $withMetadata, 'anchor' )
		);

		$withoutMetadata = $page->getFileTOCSections( false );
		$this->assertSame(
			[ 'file', 'filehistory', 'filelinks' ],
			array_column( $withoutMetadata, 'anchor' )
		);
	}

	/**
	 * @covers \MediaWiki\Page\ImagePage::buildTOCData
	 */
	public function testBuildTOCData() {
		/** @var ImagePage $page */
		$page = TestingAccessWrapper::newFromObject( $this->getImagePage( 'animated.gif' ) );
		$sections = $page->getFileTOCSections( true );

		$tocData = $page->buildTOCData( $sections, [] );
		$entries = $tocData->getSections();
		$this->assertSame(
			[ 'file', 'filehistory', 'filelinks', 'metadata' ],
			array_map( static fn ( $s ) => $s->anchor, $entries )
		);
		// All structural entries are top-level and numbered consecutively.
		$this->assertSame( [ 1, 1, 1, 1 ], array_map( static fn ( $s ) => $s->tocLevel, $entries ) );
		$this->assertSame( [ '1', '2', '3', '4' ], array_map( static fn ( $s ) => $s->number, $entries ) );
	}

	/**
	 * @covers \MediaWiki\Page\ImagePage::buildTOCData
	 */
	public function testBuildTOCDataMergesDescriptionSections() {
		/** @var ImagePage $page */
		$page = TestingAccessWrapper::newFromObject( $this->getImagePage( 'animated.gif' ) );
		$sections = $page->getFileTOCSections( true );

		$descriptionSection = new SectionMetadata(
			1, 2, 'Description heading', '1', '1', null, null,
			'Description_heading', 'Description_heading'
		);

		$tocData = $page->buildTOCData( $sections, [ $descriptionSection ] );

		// The description heading is inserted right after the "File" entry.
		$this->assertSame(
			[ 'file', 'Description_heading', 'filehistory', 'filelinks', 'metadata' ],
			array_map( static fn ( $s ) => $s->anchor, $tocData->getSections() )
		);
	}
}
