<?php

namespace Mediawiki\OutputTransform\Stages;

use Mediawiki\OutputTransform\OutputTransformStage;
use Mediawiki\OutputTransform\OutputTransformStageTest;
use MediaWiki\Parser\ParserOutput;

/**
 * @covers \Mediawiki\OutputTransform\Stages\AddRedirectHeaderTest
 * @group Database
 *        ^ Title shenanigans seem to require this
 */
class AddRedirectHeaderTest extends OutputTransformStageTest {

	public function createStage(): OutputTransformStage {
		return new AddRedirectHeader();
	}

	public function provideShouldRun(): array {
		$po = new ParserOutput();
		$linkRenderer = $this->getServiceContainer()->getLinkRenderer();
		$titleFactory = $this->getServiceContainer()->getTitleFactory();
		$languageFactory = $this->getServiceContainer()->getLanguageFactory();
		$redirect = $linkRenderer->makeRedirectHeader(
			$languageFactory->getLanguage( 'en' ), $titleFactory->newFromText( 'Stuff' ), false
		);
		$po->setRedirectHeader( $redirect );
		return [ [ $po, null, [] ] ];
	}

	public function provideShouldNotRun(): array {
		return [ [ new ParserOutput(), null, [] ] ];
	}

	public function provideTransform(): array {
		$text = "<h1>header</h1>\n<p>hello world</p>";
		$expectedText = <<<EOF
<div class="redirectMsg"><p>Redirect to:</p><ul class="redirectText"><li><a href="/w/index.php?title=Stuff&amp;action=edit&amp;redlink=1" class="new" title="Stuff (page does not exist)">Stuff</a></li></ul></div><h1>header</h1>\n<p>hello world</p>
EOF;
		$po = new ParserOutput( $text );
		$linkRenderer = $this->getServiceContainer()->getLinkRenderer();
		$titleFactory = $this->getServiceContainer()->getTitleFactory();
		$languageFactory = $this->getServiceContainer()->getLanguageFactory();
		$redirect = $linkRenderer->makeRedirectHeader(
			$languageFactory->getLanguage( 'en' ), $titleFactory->newFromText( 'Stuff' ), false
		);
		$po->setRedirectHeader( $redirect );
		$expected = new ParserOutput( $expectedText );
		$expected->setRedirectHeader( $redirect );
		return [ [ $po, null, [], $expected ] ];
	}
}
