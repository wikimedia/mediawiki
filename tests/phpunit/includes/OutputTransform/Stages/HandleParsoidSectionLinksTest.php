<?php
declare( strict_types = 1 );

namespace MediaWiki\Tests\OutputTransform\Stages;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Language\Language;
use MediaWiki\OutputTransform\OutputTransformStage;
use MediaWiki\OutputTransform\Stages\HandleParsoidSectionLinks;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\Parsoid\PageBundleParserOutputConverter;
use MediaWiki\Skin\Skin;
use MediaWiki\Tests\OutputTransform\OutputTransformStageTestBase;
use Psr\Log\NullLogger;
use Wikimedia\Parsoid\Core\HtmlPageBundle;
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

	public static function provideShouldRun(): iterable {
		yield [ PageBundleParserOutputConverter::parserOutputFromPageBundle( new HtmlPageBundle( '' ) ), ParserOptions::newFromAnon(), [] ];
	}

	public static function provideShouldNotRun(): iterable {
		yield [ new ParserOutput(), ParserOptions::newFromAnon(), [] ];
	}

	private static function newParserOutput(
		?string $rawText = null,
		?ParserOptions $parserOptions = null,
		?TOCData $toc = null,
		string ...$flags
	) {
		$po = PageBundleParserOutputConverter::parserOutputFromPageBundle(
			new HtmlPageBundle( $rawText ?? '' )
		);
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

	/** @dataProvider provideTransform */
	public function testTransform( ParserOutput $parserOutput, ?ParserOptions $parserOptions, array $options,
		ParserOutput $expected, string $message = ''
	): void {
		if ( array_key_exists( 'skin', $options ) ) {
			$skin = $this->createNoOpMock(
				Skin::class, [ 'getLanguage', 'doEditSectionLink' ]
			);
			$skin->method( 'getLanguage' )->willReturn(
				$this->createNoOpMock( Language::class )
			);
			$skin->method( 'doEditSectionLink' )->willReturn(
				'!<a id="c">edit</a>!'
			);
			$options['skin'] = $skin;
		}
		parent::testTransform( $parserOutput, $parserOptions, $options, $expected, $message = '' );
	}

	public static function provideTransform(): iterable {
		$options = [
			'enableSectionEditLinks' => true,
			'skin' => null,
		];
		$toc = TOCData::fromLegacy( [
			[
				'toclevel' => 1,
				'fromtitle' => 'TestTitle',
				'anchor' => 'foo',
			],
			[
				'toclevel' => 1,
				'fromtitle' => 'TestTitle',
				'anchor' => 'bar',
			],
			[
				'toclevel' => 1,
				'fromtitle' => 'TestTitle',
				'anchor' => '',
			],
		] );
		$input = '<section id="a"><h2 id="foo">Foo</h2>Bar</section>';

		$expected = '<section id="a" aria-labelledby="foo"><div class="mw-heading mw-heading-1"><h2 id="foo">Foo</h2></div>Bar</section>';
		$pOpts = ParserOptions::newFromAnon();
		yield 'Standard Parsoid output: no links' => [
			self::newParserOutput( $input, $pOpts, $toc ),
			$pOpts, [ 'enableSectionEditLinks' => false ] + $options,
			self::newParserOutput( $expected, $pOpts, $toc )
		];

		$expected = '<section id="a" aria-labelledby="foo"><div class="mw-heading mw-heading-1"><h2 id="foo">Foo</h2>!<a id="c">edit</a>!</div>Bar</section>';
		yield 'Standard Parsoid output: with links' => [
			self::newParserOutput( $input, $pOpts, $toc ),
			$pOpts, $options,
			self::newParserOutput( $expected, $pOpts, $toc )
		];

		// Test collapsible section wrapper (T359001)
		$pOpts = ParserOptions::newFromAnon();
		$pOpts->setCollapsibleSections();
		$expected = '<section id="a" aria-labelledby="foo"><div class="mw-heading mw-heading-1"><h2 id="foo">Foo</h2>!<a id="c">edit</a>!</div><div>Bar</div></section>';
		yield 'Standard Parsoid output: collapsible with links' => [
			self::newParserOutput( $input, $pOpts, $toc ),
			$pOpts, $options,
			self::newParserOutput( $expected, $pOpts, $toc )
		];

		// Test that an existing heading <div> wrapper is reused (T357826)
		$input = '<section id="a"><div class="mw-heading mw-heading2" id="b">prefix<h2 id="foo">Foo</h2>suffix</div>Bar</section>';
		$expected = '<section id="a" aria-labelledby="foo"><div class="mw-heading mw-heading2" id="b">prefix<h2 id="foo">Foo</h2>!<a id="c">edit</a>!suffix</div>Bar</section>';
		$pOpts = ParserOptions::newFromAnon();
		yield 'Output with existing div: with links' => [
			self::newParserOutput( $input, $pOpts, $toc ),
			$pOpts, $options,
			self::newParserOutput( $expected, $pOpts, $toc )
		];

		// Reused <div> plus collapsible sections
		$expected = '<section id="a" aria-labelledby="foo"><div class="mw-heading mw-heading2" id="b">prefix<h2 id="foo">Foo</h2>!<a id="c">edit</a>!suffix</div><div>Bar</div></section>';
		$pOpts = ParserOptions::newFromAnon();
		$pOpts->setCollapsibleSections();
		yield 'Output with existing div: collapsible with links' => [
			self::newParserOutput( $input, $pOpts, $toc ),
			$pOpts, $options,
			self::newParserOutput( $expected, $pOpts, $toc )
		];

		// Empty string isn't a valid id
		$input = '<section id="a"><h2 id="">Foo</h2>Bar</section>';
		$expected = '<section id="a"><h2 id="">Foo</h2>Bar</section>';
		yield 'Heading with empty id is skipped' => [
			self::newParserOutput( $input, $pOpts, $toc ),
			$pOpts, $options,
			self::newParserOutput( $expected, $pOpts, $toc )
		];

		// T353489: Wrappers aren't added to headings with attributes
		$input = '<section id="a"><h2 id="foo">F</h2>Oo<h2 id="bar" class="b">B</h2>Ar</section>';
		$expected = '<section id="a" aria-labelledby="foo"><div class="mw-heading mw-heading-1"><h2 id="foo">F</h2>!<a id="c">edit</a>!</div><div>Oo<h2 id="bar" class="b mw-html-heading">B</h2>Ar</div></section>';
		yield 'Heading with attributes is skipped' => [
			self::newParserOutput( $input, $pOpts, $toc ),
			$pOpts, $options,
			self::newParserOutput( $expected, $pOpts, $toc )
		];
	}
}
