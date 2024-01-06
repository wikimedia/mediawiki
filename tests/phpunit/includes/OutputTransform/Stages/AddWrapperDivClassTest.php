<?php

namespace MediaWiki\OutputTransform\Stages;

use MediaWiki\OutputTransform\OutputTransformStage;
use MediaWiki\OutputTransform\OutputTransformStageTest;
use MediaWiki\OutputTransform\TestUtils;
use MediaWiki\Parser\ParserOutput;

/**
 * @covers \MediaWiki\OutputTransform\Stages\AddWrapperDivClass
 */
class AddWrapperDivClassTest extends OutputTransformStageTest {
	public function createStage(): OutputTransformStage {
		return new AddWrapperDivClass( $this->getServiceContainer()->getLanguageFactory(),
			$this->getServiceContainer()->getContentLanguage() );
	}

	public function provideShouldRun(): array {
		return( [
			[ new ParserOutput(), null, [ 'wrapperDivClass' => 'some string' ] ]
		] );
	}

	public function provideShouldNotRun(): array {
		return( [
			[ new ParserOutput(), null, [ 'wrapperDivClass' => '' ] ],
			[ new ParserOutput(), null, [] ]
		] );
	}

	public function provideTransform(): array {
		$opts = [ 'wrapperDivClass' => 'mw-parser-output' ];
		$po = new ParserOutput( TestUtils::TEST_DOC );
		$wrappedText = <<<EOF
<div class="mw-content-ltr mw-parser-output" lang="en" dir="ltr"><p>Test document.
</p>
<meta property="mw:PageProp/toc" />
<h2><span class="mw-headline" id="Section_1">Section 1</span><mw:editsection page="Test Page" section="1">Section 1</mw:editsection></h2>
<p>One
</p>
<h2><span class="mw-headline" id="Section_2">Section 2</span><mw:editsection page="Test Page" section="2">Section 2</mw:editsection></h2>
<p>Two
</p>
<h3><span class="mw-headline" id="Section_2.1">Section 2.1</span><mw:editsection page="Talk:User:Bug_T261347" section="3">Section 2.1</mw:editsection></h3>
<p>Two point one
</p>
<h2><span class="mw-headline" id="Section_3">Section 3</span><mw:editsection page="Test Page" section="4">Section 3</mw:editsection></h2>
<p>Three
</p></div>
EOF;
		$expected = new ParserOutput( $wrappedText );
		return [
			[ $po, null, $opts, $expected ]
		];
	}
}
