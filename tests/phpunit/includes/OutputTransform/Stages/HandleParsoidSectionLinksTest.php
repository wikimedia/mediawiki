<?php

namespace MediaWiki\Tests\OutputTransform\Stages;

use Language;
use MediaWiki\OutputTransform\OutputTransformStage;
use MediaWiki\OutputTransform\Stages\HandleParsoidSectionLinks;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\Parsoid\PageBundleParserOutputConverter;
use MediaWiki\Tests\OutputTransform\OutputTransformStageTestBase;
use Psr\Log\NullLogger;
use Skin;
use Wikimedia\Parsoid\Core\PageBundle;
use Wikimedia\Parsoid\Core\TOCData;

/** @covers \MediaWiki\OutputTransform\Stages\HandleParsoidSectionLinks */
class HandleParsoidSectionLinksTest extends OutputTransformStageTestBase {

	public function createStage(): OutputTransformStage {
		return new HandleParsoidSectionLinks(
			new NullLogger(),
			$this->getServiceContainer()->getTitleFactory()
		);
	}

	public function provideShouldRun(): iterable {
		yield [ new ParserOutput(), null, [ 'isParsoidContent' => true ] ];
	}

	public function provideShouldNotRun(): iterable {
		yield [ new ParserOutput(), null, [ 'isParsoidContent' => false ] ];
		yield [ new ParserOutput(), null, [ 'enableSectionEditLinks' => false ] ];
		yield [ new ParserOutput(), null, [ 'isParsoidContent' => true, 'enableSectionEditLinks' => false ] ];
	}

	private static function pb( string $html, TOCData $toc = null ) {
		$po = PageBundleParserOutputConverter::parserOutputFromPageBundle(
			new PageBundle( $html )
		);
		if ( $toc !== null ) {
			$po->setTOCData( $toc );
		}
		return $po;
	}

	public function provideTransform(): iterable {
		$skin = $this->createNoOpMock(
			Skin::class, [ 'getLanguage', 'doEditSectionLink' ]
		);
		$skin->method( 'getLanguage' )->willReturn(
			$this->createNoOpMock( Language::class )
		);
		$skin->method( 'doEditSectionLink' )->willReturn(
			'!<a id="c">edit</a>!'
		);
		$options = [
			'isParsoidContent' => true,
			'enableSectionEditLinks' => true,
			'skin' => $skin,
		];
		$toc = TOCData::fromLegacy( [ [
			'toclevel' => 1,
			'fromtitle' => 'TestTitle',
			'anchor' => 'foo',
		] ] );
		$input = '<section id="a"><h2 id="foo">Foo</h2>Bar</section>';
		$expected = '<section id="a"><div class="mw-heading mw-heading-1" id="mwAQ"><h2 id="foo">Foo</h2>!<a id="c">edit</a>!</div>Bar</section>';

		yield [ self::pb( $input, $toc ), null, $options, self::pb( $expected, $toc ) ];

		// Test that an existing heading <div> wrapper is reused (T357826)
		$input = '<section id="a"><div class="mw-heading mw-heading2" id="b">prefix<h2 id="foo">Foo</h2>suffix</div>Bar</section>';
		$expected = '<section id="a"><div class="mw-heading mw-heading2" id="b">prefix<h2 id="foo">Foo</h2>!<a id="c">edit</a>!suffix</div>Bar</section>';
		yield [ self::pb( $input, $toc ), null, $options, self::pb( $expected, $toc ) ];
	}
}
