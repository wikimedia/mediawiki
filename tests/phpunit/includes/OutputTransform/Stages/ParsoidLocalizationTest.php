<?php

namespace MediaWiki\OutputTransform\Stages;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\Parser\Parsoid\PageBundleParserOutputConverter;
use MediaWikiIntegrationTestCase;
use Psr\Log\NullLogger;
use Wikimedia\Bcp47Code\Bcp47CodeValue;
use Wikimedia\Parsoid\Core\PageBundle;
use Wikimedia\Parsoid\ParserTests\TestUtils;
use Wikimedia\Parsoid\Utils\ContentUtils;
use Wikimedia\Parsoid\Utils\WTUtils;

/**
 * @covers \MediaWiki\OutputTransform\Stages\ParsoidLocalization
 * @group Database
 */
class ParsoidLocalizationTest extends MediaWikiIntegrationTestCase {

	public function setUp(): void {
		global $IP;
		$msgDirs = [];
		$msgDirs[] = "$IP/tests/phpunit/data/OutputTransform/i18n";
		$this->overrideConfigValue( MainConfigNames::MessagesDirs, $msgDirs );
	}

	public function createStage(): ParsoidLocalization {
		return new ParsoidLocalization(
			new ServiceOptions( [] ),
			new NullLogger()
		);
	}

	/**
	 * @dataProvider provideDocsToLocalize
	 */
	public function testApplyTransformation(
		string $input, string $expected, string $pagelang, string $userlang,
		string $message
	) {
		$this->setUserLang( $userlang );
		$loc = $this->createStage();
		$po = PageBundleParserOutputConverter::parserOutputFromPageBundle( new PageBundle( $input ) );
		$po->setLanguage( new Bcp47CodeValue( $pagelang ) );
		$opts = [ 'isParsoidContent' => true ];
		$transf = $loc->transform( $po, null, $opts );
		$res = $transf->getContentHolderText();
		self::assertEquals( $expected, TestUtils::stripParsoidIds( $res ), $message );
	}

	/**
	 * @dataProvider provideSpans
	 */
	public function testTransformGeneratedSpans( string $key, array $params, string $expected, string $message ) {
		// one of the messages we use resolves a link
		$this->overrideConfigValue( MainConfigNames::ArticlePath, '/wiki/$1' );
		$loc = $this->createStage();
		$doc = ContentUtils::createDocument();
		$p = $doc->createElement( 'p' );
		$doc->body->appendChild( $p );
		$p->appendChild( WTUtils::createInterfaceI18nFragment( $doc, $key, $params ) );
		$po = PageBundleParserOutputConverter::parserOutputFromPageBundle(
			new PageBundle( ContentUtils::ppToXML( $doc ) ) );
		$po->setLanguage( new Bcp47CodeValue( 'en' ) );
		$opts = [ 'isParsoidContent' => true ];
		$transf = $loc->transform( $po, null, $opts );
		$res = $transf->getContentHolderText();
		$this->assertEquals( $expected, TestUtils::stripParsoidIds( $res ), $message );
	}

	/**
	 * @dataProvider provideAttrs
	 */
	public function testTransformGeneratedAttrs( string $key, array $params, string $expected, string $message ) {
		$loc = $this->createStage();
		$doc = ContentUtils::createDocument();
		$a = $doc->createElement( 'a' );
		$doc->body->appendChild( $a );
		WTUtils::addInterfaceI18nAttribute( $a, 'title', $key, $params );

		$po = PageBundleParserOutputConverter::parserOutputFromPageBundle(
			new PageBundle( ContentUtils::ppToXML( $doc ) ) );
		$po->setLanguage( new Bcp47CodeValue( 'fr' ) );
		$opts = [ 'isParsoidContent' => true ];
		$transf = $loc->transform( $po, null, $opts );
		$res = $transf->getContentHolderText();
		$this->assertEquals( $expected, TestUtils::stripParsoidIds( $res ), $message );
	}

	public static function provideDocsToLocalize(): array {
		return [
			[
				'<p><a rel="mw:WikiLink" href="./Zigzagzogzagzig?action=edit&amp;redlink=1" title="Zigzagzogzagzig" class="new" typeof="mw:LocalizedAttrs" data-mw-i18n=\'{"title":{"lang":"x-page","key":"testparam","params":["Zigzagzogzagzig"]}}\'>Zigzagzogzagzig</a></p>',
				'<p><a rel="mw:WikiLink" href="./Zigzagzogzagzig?action=edit&amp;redlink=1" title="français Zigzagzogzagzig" class="new" typeof="mw:LocalizedAttrs" data-mw-i18n=\'{"title":{"lang":"x-page","key":"testparam","params":["Zigzagzogzagzig"]}}\'>Zigzagzogzagzig</a></p>',
				'fr',
				'de',
				'Red link resolution, content language'
			],
			[
				'<p><a rel="mw:WikiLink" href="./Zigzagzogzagzig?action=edit&amp;redlink=1" title="Zigzagzogzagzig" class="new" typeof="mw:LocalizedAttrs" data-mw-i18n=\'{"title":{"lang":"x-user","key":"testparam","params":["Zigzagzogzagzig"]}}\'>Zigzagzogzagzig</a></p>',
				'<p><a rel="mw:WikiLink" href="./Zigzagzogzagzig?action=edit&amp;redlink=1" title="deutsch Zigzagzogzagzig" class="new" typeof="mw:LocalizedAttrs" data-mw-i18n=\'{"title":{"lang":"x-user","key":"testparam","params":["Zigzagzogzagzig"]}}\'>Zigzagzogzagzig</a></p>',
				'fr',
				'de',
				'Red link resolution, user language'
			],
			[
				'<p><a rel="mw:WikiLink" href="./Zigzagzogzagzig?action=edit&amp;redlink=1" title="Zigzagzogzagzig" class="new" typeof="mw:LocalizedAttrs" data-mw-i18n=\'{"title":{"lang":"pt-br","key":"testparam","params":["Zigzagzogzagzig"]}}\'>Zigzagzogzagzig</a></p>',
				'<p><a rel="mw:WikiLink" href="./Zigzagzogzagzig?action=edit&amp;redlink=1" title="brazilian Zigzagzogzagzig" class="new" typeof="mw:LocalizedAttrs" data-mw-i18n=\'{"title":{"lang":"pt-br","key":"testparam","params":["Zigzagzogzagzig"]}}\'>Zigzagzogzagzig</a></p>',
				'fr',
				'de',
				'Red link resolution, arbitrary language'
			],
		];
	}

	public static function provideSpans(): array {
		return [
			[
				'testparam',
				[ '&<' ],
				'<p><span typeof="mw:I18n" data-mw-i18n=\'{"/":{"lang":"x-user","key":"testparam","params":["&amp;&lt;"]}}\'>english &amp;&lt;</span></p>',
				'Span with &<',
			],
			[
				'testparam',
				[ "<script>console()</script>" ],
				'<p><span typeof="mw:I18n" data-mw-i18n=\'{"/":{"lang":"x-user","key":"testparam","params":["&lt;script>console()&lt;/script>"]}}\'>english &lt;script>console()&lt;/script></span></p>',
				'Span with <script> (gets escaped)'
			],
			[
				'testparam',
				[ "<b>bold move</b>" ],
				'<p><span typeof="mw:I18n" data-mw-i18n=\'{"/":{"lang":"x-user","key":"testparam","params":["&lt;b>bold move&lt;/b>"]}}\'>english <b>bold move</b></span></p>',
				'Span with <b> (doesn\'t get escaped)'
			],
			[
				'testblock',
				[],
				// Observe that we're not generating HTML conforming to content types in this specific case
				'<p><span typeof="mw:I18n" data-mw-i18n=\'{"/":{"lang":"x-user","key":"testblock","params":[]}}\'><p>english </p><div>stuff</div></span></p>',
				'Message with block content in a span'
			]
		];
	}

	public static function provideAttrs(): array {
		return [
			[
				'testparam',
				[ '&<' ],
				'<a typeof="mw:LocalizedAttrs" title="english &amp;&lt;" data-mw-i18n=\'{"title":{"lang":"x-user","key":"testparam","params":["&amp;&lt;"]}}\'></a>',
				'Attr with &<'
			],
			[
				'testparam',
				[ "<script>console()</script>" ],
				'<a typeof="mw:LocalizedAttrs" title="english &lt;script>console()&lt;/script>" data-mw-i18n=\'{"title":{"lang":"x-user","key":"testparam","params":["&lt;script>console()&lt;/script>"]}}\'></a>',
				'Attr with <script> (gets escaped)'
			],
			[
				'testparam',
				[ "<b>bold move</b>" ],
				'<a typeof="mw:LocalizedAttrs" title="english bold move" data-mw-i18n=\'{"title":{"lang":"x-user","key":"testparam","params":["&lt;b>bold move&lt;/b>"]}}\'></a>',
				'Attr with <b> (gets dropped)'
			],
			[
				'testblock',
				[],
				'<a typeof="mw:LocalizedAttrs" title="english stuff" data-mw-i18n=\'{"title":{"lang":"x-user","key":"testblock","params":[]}}\'></a>',
				'Attr with block content'
			]
		];
	}
}
