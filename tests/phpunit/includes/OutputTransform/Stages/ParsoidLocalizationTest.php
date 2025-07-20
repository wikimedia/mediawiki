<?php
declare( strict_types = 1 );

namespace MediaWiki\OutputTransform\Stages;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\Parser\Parsoid\PageBundleParserOutputConverter;
use MediaWiki\Parser\Parsoid\ParsoidParser;
use MediaWiki\Title\TitleFactory;
use MediaWikiIntegrationTestCase;
use Psr\Log\NullLogger;
use Wikimedia\Bcp47Code\Bcp47CodeValue;
use Wikimedia\Message\MessageValue;
use Wikimedia\Message\ParamType;
use Wikimedia\Message\ScalarParam;
use Wikimedia\Parsoid\Core\HtmlPageBundle;
use Wikimedia\Parsoid\ParserTests\TestUtils;
use Wikimedia\Parsoid\Utils\ContentUtils;
use Wikimedia\Parsoid\Utils\DOMCompat;
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
			new NullLogger(),
			new TitleFactory()
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
		$po = PageBundleParserOutputConverter::parserOutputFromPageBundle( new HtmlPageBundle( $input ) );
		$po->setLanguage( new Bcp47CodeValue( $pagelang ) );
		$po->setExtensionData( ParsoidParser::PARSOID_TITLE_KEY, 'Test_page' );
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
		$doc = ContentUtils::createAndLoadDocument( '<p>' );
		$p = DOMCompat::querySelector( $doc, 'p' );
		$p->appendChild( WTUtils::createInterfaceI18nFragment( $doc, $key, $params ) );
		$po = PageBundleParserOutputConverter::parserOutputFromPageBundle(
			new HtmlPageBundle( ContentUtils::ppToXML( $doc ) ) );
		$po->setLanguage( new Bcp47CodeValue( 'en' ) );
		$po->setExtensionData( ParsoidParser::PARSOID_TITLE_KEY, 'Test_page' );
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
		$doc = ContentUtils::createAndLoadDocument( '<a>' );
		$a = DOMCompat::querySelector( $doc, 'a' );
		WTUtils::addInterfaceI18nAttribute( $a, 'title', $key, $params );

		$po = PageBundleParserOutputConverter::parserOutputFromPageBundle(
			new HtmlPageBundle( ContentUtils::ppToXML( $doc ) ) );
		$po->setLanguage( new Bcp47CodeValue( 'fr' ) );
		$po->setExtensionData( ParsoidParser::PARSOID_TITLE_KEY, 'Test_page' );
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
				'testparam',
				[ new MessageValue( 'testparam', [ new ScalarParam( ParamType::TEXT, new MessageValue( 'testparam', [ new ScalarParam( ParamType::TEXT, 'hello' ) ] ) ) ] ) ],
				'<p><span typeof="mw:I18n" data-mw-i18n=\'{"/":{"lang":"x-user","key":"testparam","params":{"0":{"key":"testparam","params":{"0":{"text":{"key":"testparam","params":{"0":{"text":"hello","_type_":"Wikimedia\\\\Message\\\\ScalarParam"},"_type_":"array"},"_type_":"Wikimedia\\\\Message\\\\MessageValue"},"_type_":"Wikimedia\\\\Message\\\\ScalarParam"},"_type_":"array"},"_type_":"Wikimedia\\\\Message\\\\MessageValue"},"_type_":"array"}}}\'>english english english hello</span></p>',
				'Span with nested message'
			],
			[
				'testblock',
				[],
				// Observe that we're not generating HTML conforming to content types in this specific case
				'<p><span typeof="mw:I18n" data-mw-i18n=\'{"/":{"lang":"x-user","key":"testblock","params":[]}}\'><p>english </p><div>stuff</div></span></p>',
				'Message with block content in a span'
			],
			[
				'testparam',
				[ new MessageValue( 'testlink', [] ) ],
				'<p><span typeof="mw:I18n" data-mw-i18n=\'{"/":{"lang":"x-user","key":"testparam","params":{"0":{"key":"testlink","params":[],"_type_":"Wikimedia\\\\Message\\\\MessageValue"},"_type_":"array"}}}\'>english english <a href="/index.php?title=Link&amp;action=edit&amp;redlink=1" class="new" title="Link (page does not exist)">link</a></span></p>',
				'span with link in the parameter'
			],
			[
				'testpagename',
				[],
				'<p><span typeof="mw:I18n" data-mw-i18n=\'{"/":{"lang":"x-user","key":"testpagename","params":[]}}\'>Test page</span></p>',
				'{{PAGENAME}} should resolve'
			],
			[
				'testblank',
				[],
				'<p><span typeof="mw:I18n" data-mw-i18n=\'{"/":{"lang":"x-user","key":"testblank","params":[]}}\'></span></p>',
				'blank message should resolve'
			],
			[
				'testdisabled',
				[],
				'<p><span typeof="mw:I18n" data-mw-i18n=\'{"/":{"lang":"x-user","key":"testdisabled","params":[]}}\'></span></p>',
				'disabled message should resolve to empty'
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
			],
			[
				'testparam',
				[ new MessageValue( 'testparam', [ new ScalarParam( ParamType::TEXT, new MessageValue( 'testparam', [ new ScalarParam( ParamType::TEXT, 'hello' ) ] ) ) ] ) ],
				'<a typeof="mw:LocalizedAttrs" title="english english english hello" data-mw-i18n=\'{"title":{"lang":"x-user","key":"testparam","params":{"0":{"key":"testparam","params":{"0":{"text":{"key":"testparam","params":{"0":{"text":"hello","_type_":"Wikimedia\\\\Message\\\\ScalarParam"},"_type_":"array"},"_type_":"Wikimedia\\\\Message\\\\MessageValue"},"_type_":"Wikimedia\\\\Message\\\\ScalarParam"},"_type_":"array"},"_type_":"Wikimedia\\\\Message\\\\MessageValue"},"_type_":"array"}}}\'></a>',
				'Attr with nested message'
			],
			[
				'testblank',
				[],
				'<a typeof="mw:LocalizedAttrs" title="" data-mw-i18n=\'{"title":{"lang":"x-user","key":"testblank","params":[]}}\'></a>',
				'blank attribute should resolve'
			],
			[
				'testdisabled',
				[],
				'<a typeof="mw:LocalizedAttrs" title="" data-mw-i18n=\'{"title":{"lang":"x-user","key":"testdisabled","params":[]}}\'></a>',
				'disabled attribute should resolve to empty'
			]
		];
	}
}
