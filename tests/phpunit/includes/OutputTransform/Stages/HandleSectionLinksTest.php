<?php

namespace Mediawiki\OutputTransform\Stages;

use Mediawiki\OutputTransform\OutputTransformStage;
use Mediawiki\OutputTransform\OutputTransformStageTest;
use Mediawiki\OutputTransform\TestUtils;
use ParserOutput;
use Psr\Log\NullLogger;

/** @covers \Mediawiki\OutputTransform\Stages\HandleSectionLinks */
class HandleSectionLinksTest extends OutputTransformStageTest {

	public function createStage(): OutputTransformStage {
		return new HandleSectionLinks( new NullLogger(), $this->getServiceContainer()->getTitleFactory() );
	}

	public function provideShouldRun(): array {
		return [ [ new ParserOutput(), null, [] ] ];
	}

	public function provideShouldNotRun(): array {
		return [];
	}

	public function provideTransform(): array {
		return [
			[
				new ParserOutput( TestUtils::TEST_DOC ),
				null, [],
				new ParserOutput( TestUtils::TEST_DOC_WITH_LINKS )
			],
			[
				new ParserOutput( TestUtils::TEST_DOC ),
				null, [ 'enableSectionEditLinks' => false ],
				new ParserOutput( TestUtils::TEST_DOC_WITHOUT_LINKS )
			],
			[
				new ParserOutput( TestUtils::TEST_DOC ),
				null, [ 'enableSectionEditLinks' => true ],
				new ParserOutput( TestUtils::TEST_DOC_WITH_LINKS )
			],
		];
	}
}
