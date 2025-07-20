<?php

namespace MediaWiki\Tests\Parser;

use MediaWiki\Parser\ContentHolder;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\Parsoid\PageBundleParserOutputConverter;
use MediaWikiIntegrationTestCase;
use Wikimedia\Assert\InvariantException;
use Wikimedia\Parsoid\Core\DomPageBundle;
use Wikimedia\Parsoid\Core\HtmlPageBundle;
use Wikimedia\Parsoid\DOM\Document;
use Wikimedia\Parsoid\DOM\DocumentFragment;
use Wikimedia\Parsoid\Mocks\MockSiteConfig;
use Wikimedia\Parsoid\Utils\ContentUtils;
use Wikimedia\Parsoid\Utils\DOMCompat;
use Wikimedia\Parsoid\Utils\DOMUtils;

/**
 * @coversDefaultClass \MediaWiki\Parser\ContentHolder
 */
class ContentHolderTest extends MediaWikiIntegrationTestCase {

	private function legacyHtmlProvider() {
		yield "Basic legacy test case" => [
			 <<<EOD
<h2 data-mw-anchor="Test">Test<mw:editsection page="test" section="1">Test</mw:editsection></h2>
<p>some basic wikitext
</p>
EOD
		];
	}

	private function parsoidContentProvider() {
		$body = <<<EOD
<section data-mw-section-id="0" id="mwAQ" data-parsoid="{}"></section><section data-mw-section-id="1" id="mwAg" data-parsoid="{}"><h2 id="Test" data-parsoid='{"dsr":[0,10,2,2,1,1]}'>Test</h2>
<p id="mwAw" data-parsoid='{"dsr":[11,30,0,0]}'>some basic wikitext</p></section>
EOD;
		$bodyFiltered = <<<EOD
<section data-mw-section-id="0" id="mwAQ"></section><section data-mw-section-id="1" id="mwAg"><h2 id="Test">Test</h2>
<p id="mwAw">some basic wikitext</p></section>
EOD;
		$header = <<<EOD
<!DOCTYPE html>
<html prefix="dc: http://purl.org/dc/terms/ mw: http://mediawiki.org/rdf/"><head prefix="mwr: http://localhost/wiki/Special:Redirect/"><meta charset="utf-8"/><meta property="mw:pageId" content="0"/><meta property="mw:pageNamespace" content="0"/><meta property="mw:revisionSHA1" content="ee48b4b956b53763fdb7d294e857daf90e624d17"/><meta property="mw:htmlVersion" content="2.8.0"/><meta property="mw:html:version" content="2.8.0"/><link rel="dc:isVersionOf" href="http://localhost/wiki/test"/><base href="http://localhost/wiki/"/><title>test</title><link rel="stylesheet" href="/load.php?lang=en&amp;modules=mediawiki.skinning.content.parsoid%7Cmediawiki.skinning.interface%7Csite.styles&amp;only=styles&amp;skin=vector"/><meta http-equiv="content-language" content="en"/><meta http-equiv="vary" content="Accept, Accept-Language"/></head><body lang="en" class="mw-content-ltr sitedir-ltr ltr mw-body-content parsoid-body mediawiki mw-parser-output" dir="ltr" data-mw-parsoid-version="0.22.0.0-alpha5" data-mw-html-version="2.8.0" id="mwAA" data-parsoid='{"dsr":[0,30,0,0]}'>
EOD;
		$html = $header . $body . '</body></html>';
		$htmlFiltered = preg_replace( "/ data-parsoid=\\\\?'[^']*\\\\?'/u", '', $html );
		$htmlFiltered = preg_replace( '/ data-parsoid=\\\\?"[^\"]*\\\\?"/u', '', $htmlFiltered );
		yield "Basic Parsoid test case" => [
				[
				'body' => $body,
				'bodyFiltered' => $bodyFiltered,
				'header' => $header,
				'html' => $html,
				'htmlFiltered' => $htmlFiltered,
				'dom' => ContentUtils::createAndLoadDocument( $html ),
				]
		];
	}

	/**
	 * @dataProvider legacyHtmlProvider
	 * @covers ::getAsHtmlString
	 * @covers ::createFromLegacyString
	 */
	public function testShouldHandleTextOnlyOperationsLegacy( string $legacyHtml ): void {
		$ch = ContentHolder::createFromLegacyString( $legacyHtml );
		self::assertEquals( $ch->getAsHtmlString( ContentHolder::BODY_FRAGMENT ), $legacyHtml );
	}

	/**
	 * @dataProvider legacyHtmlProvider
	 * @covers ::setAsHtmlString
	 */
	public function testShouldSetHtmlStringLegacy( string $legacyHtml ): void {
		$ch = ContentHolder::createEmpty();
		$ch->setAsHtmlString( ContentHolder::BODY_FRAGMENT, $legacyHtml );
		self::assertEquals( $ch->getAsHtmlString( ContentHolder::BODY_FRAGMENT ), $legacyHtml );
	}

	/**
	 * @dataProvider parsoidContentProvider
	 * @covers ::setAsHtmlString
	 */
	public function testShouldSetHtmlStringParsoid( array $parsoidData ): void {
		$ch = ContentHolder::createFromParsoidPageBundle( new HtmlPageBundle( '' ) );
		$ch->setAsHtmlString( ContentHolder::BODY_FRAGMENT, $parsoidData['html'] );
		self::assertEquals( $parsoidData['html'], $ch->getAsHtmlString( ContentHolder::BODY_FRAGMENT ) );
	}

	/**
	 * @dataProvider legacyHtmlProvider
	 * @covers ::setAsDom
	 */
	public function testShouldThrowUnadoptedFragment( string $legacyHtml ): void {
		$ch = ContentHolder::createEmpty();
		$doc = DOMCompat::newDocument( true );
		$frag = $doc->createDocumentFragment();
		DOMUtils::setFragmentInnerHTML( $frag, $legacyHtml );
		$this->expectException( InvariantException::class );
		$ch->setAsDom( ContentHolder::BODY_FRAGMENT, $frag );
	}

	/**
	 * @dataProvider legacyHtmlProvider
	 * @covers ::setAsDom
	 */
	public function testShouldSetDomLegacy( string $legacyHtml ): void {
		$ch = ContentHolder::createEmpty();
		$frag = $ch->createFragment( $legacyHtml );
		$ch->setAsDom( ContentHolder::BODY_FRAGMENT, $frag );
		self::assertEquals( $legacyHtml, $ch->getAsHtmlString( ContentHolder::BODY_FRAGMENT ) );
	}

	/**
	 * @dataProvider parsoidContentProvider
	 * @covers ::setAsDom
	 */
	public function testShouldSetDomParsoid( array $parsoidData ): void {
		$dpb = DomPageBundle::fromLoadedDocument( $parsoidData['dom'], [
			'siteConfig' => new MockSiteConfig( [] ),
		] );
		$pb = HtmlPageBundle::fromDomPageBundle( $dpb );
		$ch = ContentHolder::createFromParsoidPageBundle( $pb );

		$frag = $ch->createFragment( $parsoidData['body'] );

		$ch->setAsDom( ContentHolder::BODY_FRAGMENT, $frag );
		self::assertEquals( $parsoidData['bodyFiltered'], $ch->getAsHtmlString( ContentHolder::BODY_FRAGMENT ) );
		$this->checkBundle( $ch, $parsoidData['bodyFiltered'] );

		$frag = $ch->createFragment( $parsoidData['body'] );
		$ch->setAsDom( ContentHolder::BODY_FRAGMENT, $frag );
		self::assertEquals( $parsoidData['bodyFiltered'], $ch->getAsHtmlString( ContentHolder::BODY_FRAGMENT ) );
		$this->checkBundle( $ch, $parsoidData['bodyFiltered'] );
	}

	private function checkBundle( ContentHolder $ch, string $bodyFiltered ) {
		$po = new ParserOutput( $bodyFiltered );
		$ch->finalize( $po );
		$this->assertTrue( PageBundleParserOutputConverter::hasPageBundle( $po ) );
		$pb = PageBundleParserOutputConverter::pageBundleFromParserOutput( $po );
		self::assertEquals( 10, $pb->parsoid['ids']['Test']['dsr'][1] );
	}

	/**
	 * @dataProvider parsoidContentProvider
	 * @covers ::getAsHtmlString
	 * @covers ::createFromParsoidPageBundle
	 */
	public function testShouldHandleTextOnlyOperationsParsoidFullDocBundle( array $parsoidData ): void {
		$dpb = DomPageBundle::fromLoadedDocument( $parsoidData['dom'], [
			'siteConfig' => new MockSiteConfig( [] ),
		] );
		$pb = HtmlPageBundle::fromDomPageBundle( $dpb );
		$ch = ContentHolder::createFromParsoidPageBundle( $pb );
		self::assertEquals( $parsoidData['htmlFiltered'], $ch->getAsHtmlString( ContentHolder::BODY_FRAGMENT ) );
	}

	/**
	 * @dataProvider parsoidContentProvider
	 * @covers ::getAsHtmlString
	 * @covers ::createFromParsoidPageBundle
	 */
	public function testShouldHandleTextOnlyOperationsParsoidBodyBundle( array $parsoidData ): void {
		$dpb = DomPageBundle::fromLoadedDocument( $parsoidData['dom'], [
			'siteConfig' => new MockSiteConfig( [] ),
		] );
		$pb = HtmlPageBundle::fromDomPageBundle( $dpb, [ 'body_only' => true ] );
		$ch = ContentHolder::createFromParsoidPageBundle( $pb );
		self::assertEquals( $parsoidData['bodyFiltered'], $ch->getAsHtmlString( ContentHolder::BODY_FRAGMENT ) );
	}

	private function legacyDomProvider() {
		foreach ( $this->legacyHtmlProvider() as $k => $legacyHtml ) {
			$expected = DOMUtils::parseHTMLToFragment( DOMCompat::newDocument( true ), $legacyHtml[0] );
			yield $k => [ $legacyHtml[0], $expected ];
		}
	}

	/**
	 * @dataProvider legacyDomProvider
	 * @covers ::convertHtmlToDom
	 */
	public function testShouldConvertHtmlStringToDomLegacy( string $legacyHtml, DocumentFragment $expected ): void {
		$ch = ContentHolder::createFromLegacyString( $legacyHtml );
		$res = $ch->getAsDom( ContentHolder::BODY_FRAGMENT );
		$this->assertEquals( ContentUtils::dumpDOM( $expected ), ContentUtils::dumpDOM( $res ) );
	}

	private function parsoidDomProvider() {
		foreach ( $this->parsoidContentProvider() as $k => $parsoidData ) {
			$input = $parsoidData[0][ 'dom' ];
			$doc = ContentUtils::createAndLoadDocument( $parsoidData[0]['html'] );
			$expected = $doc->createDocumentFragment();
			DOMUtils::migrateChildren( DOMCompat::getBody( $doc ), $expected );
			yield $k => [ $input, ContentUtils::dumpDOM( $expected ) ];
		}
	}

	/**
	 * @dataProvider parsoidDomProvider
	 * @covers ::convertHtmlToDom
	 */
	public function testShouldConvertHtmlStringToDomParsoid( Document $input, string $expected ): void {
		$dpb = DomPageBundle::fromLoadedDocument( $input, [
			'siteConfig' => new MockSiteConfig( [] ),
		] );
		$pb = HtmlPageBundle::fromDomPageBundle( $dpb );
		$ch = ContentHolder::createFromParsoidPageBundle( $pb );

		$res = $ch->getAsDom( ContentHolder::BODY_FRAGMENT );
		$this->assertEquals( $expected, ContentUtils::dumpDOM( $res ) );
	}

	/**
	 * @dataProvider parsoidContentProvider
	 * @covers ::convertDomToHtml
	 * @covers ::convertHtmlToDom
	 */
	public function testShouldConvertHtmlDocToDomToHtmlBodyParsoid( array $parsoidData ): void {
		$dpb = DomPageBundle::fromLoadedDocument( $parsoidData[ 'dom' ], [
			'siteConfig' => new MockSiteConfig( [] ),
		] );
		$pb = HtmlPageBundle::fromDomPageBundle( $dpb );
		$ch = ContentHolder::createFromParsoidPageBundle( $pb );
		$ch->getAsDom( ContentHolder::BODY_FRAGMENT );
		$res = $ch->getAsHtmlString( ContentHolder::BODY_FRAGMENT );
		$this->assertEquals( $parsoidData['bodyFiltered'], $res );
		$this->checkBundle( $ch, $parsoidData['bodyFiltered'] );
	}

	/**
	 * @dataProvider parsoidContentProvider
	 * @covers ::createFromParsoidPageBundle
	 * @covers ::convertDomToHtml
	 * @covers ::convertHtmlToDom
	 */
	public function testShouldConvertHtmlBodyToDomToHtmlBodyParsoid( array $parsoidData ): void {
		$dpb = DomPageBundle::fromLoadedDocument( $parsoidData['dom'], [
			'siteConfig' => new MockSiteConfig( [] ),
		] );
		$pb = HtmlPageBundle::fromDomPageBundle( $dpb, [ 'body_only', true ] );
		$ch = ContentHolder::createFromParsoidPageBundle( $pb );
		$ch->getAsDom( ContentHolder::BODY_FRAGMENT );
		$res = $ch->getAsHtmlString( ContentHolder::BODY_FRAGMENT );
		$this->assertEquals( $parsoidData['bodyFiltered'], $res );
		$this->checkBundle( $ch, $parsoidData['bodyFiltered'] );
	}

	/**
	 * @dataProvider legacyHtmlProvider
	 * @covers ::convertDomToHtml
	 * @covers ::convertHtmlToDom
	 */
	public function testShouldConvertHtmlBodyToDomToHtmlBodyLegacy( string $legacyHtml ): void {
		$ch = ContentHolder::createFromLegacyString( $legacyHtml );
		$ch->getAsDom( ContentHolder::BODY_FRAGMENT );
		$res = $ch->getAsHtmlString( ContentHolder::BODY_FRAGMENT );
		$this->assertEquals( $legacyHtml, $res );
	}

	/**
	 * @dataProvider parsoidContentProvider
	 * @covers ::finalize
	 */
	public function testFinalize( array $parsoidData ): void {
		$po = new ParserOutput( $parsoidData['html'] );
		$dpb = DomPageBundle::fromLoadedDocument( $parsoidData['dom'], [
			'siteConfig' => new MockSiteConfig( [] ),
		] );
		$pb = HtmlPageBundle::fromDomPageBundle( $dpb );
		$ch = ContentHolder::createFromParsoidPageBundle( $pb );
		$this->checkBundle( $ch, $parsoidData['bodyFiltered'] );
	}
}
