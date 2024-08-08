<?php

namespace MediaWiki\Tests\OutputTransform\Stages;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Language\Language;
use MediaWiki\OutputTransform\OutputTransformStage;
use MediaWiki\OutputTransform\Stages\HandleParsoidSectionLinks;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\Parsoid\PageBundleParserOutputConverter;
use MediaWiki\Tests\OutputTransform\OutputTransformStageTestBase;
use ParserOptions;
use Psr\Log\NullLogger;
use Skin;
use Wikimedia\Parsoid\Core\PageBundle;
use Wikimedia\Parsoid\Core\TOCData;

/** @covers \MediaWiki\OutputTransform\Stages\HandleParsoidSectionLinks */
class HandleParsoidSectionLinksTest extends OutputTransformStageTestBase {

	public function createStage(): OutputTransformStage {
		return new HandleParsoidSectionLinks(
			new ServiceOptions( [] ),
			new NullLogger(),
			$this->getServiceContainer()->getTitleFactory()
		);
	}

	public function provideShouldRun(): iterable {
		yield [ new ParserOutput(), null, [ 'isParsoidContent' => true ] ];
	}

	public function provideShouldNotRun(): iterable {
		yield [ new ParserOutput(), null, [ 'isParsoidContent' => false ] ];
	}

	private static function newParserOutput(
		?string $rawText = null,
		?ParserOptions $parserOptions = null,
		?TOCData $toc = null,
		string ...$flags
	) {
		$po = new ParserOutput();
		if ( $rawText !== null ) {
			$po = PageBundleParserOutputConverter::parserOutputFromPageBundle(
				new PageBundle( $rawText )
			);
		}
		if ( $parserOptions !== null ) {
			$po->setFromParserOptions( $parserOptions );
		}
		if ( $toc !== null ) {
			$po->setTOCData( $toc );
		}
		foreach ( $flags as $f ) {
			$po->setOutputFlag( $f );
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

		$expected = '<section id="a"><div class="mw-heading mw-heading-1" id="mwAQ"><h2 id="foo">Foo</h2></div>Bar</section>';
		yield 'Standard Parsoid output: no links' => [
			self::newParserOutput( $input, null, $toc ),
			null, [ 'enableSectionEditLinks' => false ] + $options,
			self::newParserOutput( $expected, null, $toc )
		];

		$expected = '<section id="a"><div class="mw-heading mw-heading-1" id="mwAQ"><h2 id="foo">Foo</h2>!<a id="c">edit</a>!</div>Bar</section>';
		yield 'Standard Parsoid output: with links' => [
			self::newParserOutput( $input, null, $toc ),
			null, $options,
			self::newParserOutput( $expected, null, $toc )
		];

		// Test collapsible section wrapper (T359001)
		$pOpts = ParserOptions::newFromAnon();
		$pOpts->setCollapsibleSections();
		$expected = '<section id="a"><div class="mw-heading mw-heading-1" id="mwAQ"><h2 id="foo">Foo</h2>!<a id="c">edit</a>!</div><div id="mwAg">Bar</div></section>';
		yield 'Standard Parsoid output: collapsible with links' => [
			self::newParserOutput( $input, $pOpts, $toc ),
			$pOpts, $options,
			self::newParserOutput( $expected, $pOpts, $toc )
		];

		// Test that an existing heading <div> wrapper is reused (T357826)
		$input = '<section id="a"><div class="mw-heading mw-heading2" id="b">prefix<h2 id="foo">Foo</h2>suffix</div>Bar</section>';
		$expected = '<section id="a"><div class="mw-heading mw-heading2" id="b">prefix<h2 id="foo">Foo</h2>!<a id="c">edit</a>!suffix</div>Bar</section>';
		yield 'Output with existing div: with links' => [
			self::newParserOutput( $input, null, $toc ),
			null, $options,
			self::newParserOutput( $expected, null, $toc )
		];

		// Reused <div> plus collapsible sections
		$expected = '<section id="a"><div class="mw-heading mw-heading2" id="b">prefix<h2 id="foo">Foo</h2>!<a id="c">edit</a>!suffix</div><div id="mwAQ">Bar</div></section>';
		yield 'Output with existing div: collapsible with links' => [
			self::newParserOutput( $input, $pOpts, $toc ),
			$pOpts, $options,
			self::newParserOutput( $expected, $pOpts, $toc )
		];
	}
}
