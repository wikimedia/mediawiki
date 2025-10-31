<?php
declare( strict_types = 1 );

namespace MediaWiki\Tests\OutputTransform\Stages;

use Generator;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\OutputTransform\OutputTransformStage;
use MediaWiki\OutputTransform\Stages\ExpandRelativeAttrs;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\Parsoid\PageBundleParserOutputConverter;
use MediaWiki\Parser\Parsoid\ParsoidParser;
use MediaWiki\Tests\OutputTransform\OutputTransformStageTestBase;
use Psr\Log\NullLogger;
use Wikimedia\Parsoid\Core\HtmlPageBundle;
use Wikimedia\Parsoid\Mocks\MockSiteConfig;

/**
 * @covers \MediaWiki\OutputTransform\Stages\ExpandRelativeAttrs
 */
class ExpandRelativeAttrsTest extends OutputTransformStageTestBase {

	public function createStage(): OutputTransformStage {
		return new ExpandRelativeAttrs(
			new ServiceOptions( [] ),
			new NullLogger(),
			$this->getServiceContainer()->getUrlUtils(),
			new MockSiteConfig( [] ),
			null
		);
	}

	public static function provideShouldRun(): array {
		return [
			[ PageBundleParserOutputConverter::parserOutputFromPageBundle( new HtmlPageBundle( '' ) ), null, [] ],
		];
	}

	public static function provideShouldNotRun(): array {
		return [
			[ new ParserOutput(), null, [] ],
			[ new ParserOutput(), null, [] ],
		];
	}

	public static function provideTransform(): Generator {
		$testData = [
			[
				"title" => "Foo",
				"body" => "<p><a href=\"./Foo#cite1\">hello</a><a href=\"./Foo/Bar#cite1\">boo</a></p>",
				"result" => "<p><a href=\"#cite1\">hello</a><a href=\"//my.wiki.example/wikix/Foo/Bar#cite1\">boo</a></p>"
			],
			// hrefs have db-key normalization of titles - test that output isn't tripped by that
			[
				"title" => "Foo_Bar Baz",
				"body" => "<p><a href=\"./Foo_Bar_Baz#cite1\">hello</a></p>",
				"result" => "<p><a href=\"#cite1\">hello</a></p>"
			],
			[
				"title" => "Foo+Bar",
				"body" => "<p><a href=\"./Foo+Bar#cite1\">hello</a></p>",
				"result" => "<p><a href=\"#cite1\">hello</a></p>"
			],
			[
				"title" => "Foo/Bar",
				"body" => "<p><a href=\"./Foo/Bar#cite1\">hello</a></p>",
				"result" => "<p><a href=\"#cite1\">hello</a></p>"
			],
			[
				"title" => "Foo/Bar'Baz",
				"body" => "<p><a href=\"./Foo/Bar'Baz#cite1\">hello</a></p>",
				"result" => "<p><a href=\"#cite1\">hello</a></p>"
			],
			[
				"title" => 'Foo/Bar"Baz',
				"body" => "<p><a href='./Foo/Bar\"Baz#cite1'>hello</a></p>",
				"result" => "<p><a href=\"#cite1\">hello</a></p>"
			],
			[
				"title" => "Foo/Bar",
				"body" => '<figure typeof="mw:File mw:Extension/imagemap"><span><img resource="./File:Foobar.jpg" src="http://example.com/images/3/3a/Foobar.jpg" usemap="#ImageMap_02c94d3ca4bfc187"></span><map name="ImageMap_02c94d3ca4bfc187"><area href="./Main_Page" shape="poly" coords="10,11,10,30,-30,15"></map><figcaption></figcaption></figure>',
				"result" => "<figure typeof=\"mw:File mw:Extension/imagemap\"><span><img resource=\"//my.wiki.example/wikix/File:Foobar.jpg\" src=\"http://example.com/images/3/3a/Foobar.jpg\" usemap=\"#ImageMap_02c94d3ca4bfc187\"/></span><map name=\"ImageMap_02c94d3ca4bfc187\"><area href=\"//my.wiki.example/wikix/Main_Page\" shape=\"poly\" coords=\"10,11,10,30,-30,15\"/></map><figcaption></figcaption></figure>",
			],
		];
		// Set test title in parser output extension data
		foreach ( $testData as $label => $t ) {
			$titleDBKey = strtr( $t['title'], ' ', '_' );
			$docHtml = $t['doc'] ?? $t['body'];
			$poInput = new ParserOutput( $docHtml );
			$poOutput = new ParserOutput( $t['result'] );
			$poInput->setExtensionData( ParsoidParser::PARSOID_TITLE_KEY, $titleDBKey );
			$poOutput->setExtensionData( ParsoidParser::PARSOID_TITLE_KEY, $titleDBKey );
			yield $label => [ $poInput, null, [], $poOutput ];
		}
	}
}
