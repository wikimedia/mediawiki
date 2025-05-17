<?php

namespace MediaWiki\Tests\OutputTransform\Stages;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\OutputTransform\OutputTransformStage;
use MediaWiki\OutputTransform\Stages\AddWrapperDivClass;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Tests\OutputTransform\OutputTransformStageTestBase;
use MediaWiki\Tests\OutputTransform\TestUtils;
use Psr\Log\NullLogger;

/**
 * @covers \MediaWiki\OutputTransform\Stages\AddWrapperDivClass
 */
class AddWrapperDivClassTest extends OutputTransformStageTestBase {
	public function createStage(): OutputTransformStage {
		return new AddWrapperDivClass(
			new ServiceOptions( [] ),
			new NullLogger(),
			$this->getServiceContainer()->getLanguageFactory(),
			$this->getServiceContainer()->getContentLanguage()
		);
	}

	public static function provideShouldRun(): array {
		return( [
			[ new ParserOutput(), null, [ 'wrapperDivClass' => 'some string' ] ]
		] );
	}

	public static function provideShouldNotRun(): array {
		return( [
			[ new ParserOutput(), null, [ 'wrapperDivClass' => '' ] ],
			[ new ParserOutput(), null, [] ]
		] );
	}

	public static function provideTransform(): array {
		$opts = [ 'wrapperDivClass' => 'mw-parser-output' ];
		$po = new ParserOutput( TestUtils::TEST_DOC );
		$wrappedText = <<<EOF
<div class="mw-content-ltr mw-parser-output" lang="en" dir="ltr"><p>Test document.
</p>
<meta property="mw:PageProp/toc" />
<h2 data-mw-anchor="Section_1">Section 1<mw:editsection page="Test Page" section="1">Section 1</mw:editsection></h2>
<p>One
</p>
<h2 data-mw-anchor="Section_2">Section 2<mw:editsection page="Test Page" section="2">Section 2</mw:editsection></h2>
<p>Two
</p>
<h3 data-mw-anchor="Section_2.1">Section 2.1</h3>
<p>Two point one
</p>
<h2 data-mw-anchor="Section_3">Section 3<mw:editsection page="Test Page" section="4">Section 3</mw:editsection></h2>
<p>Three
</p></div>
EOF;
		$expected = new ParserOutput( $wrappedText );
		return [
			[ $po, null, $opts, $expected ]
		];
	}
}
