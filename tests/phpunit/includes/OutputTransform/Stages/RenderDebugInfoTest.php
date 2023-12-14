<?php

namespace Mediawiki\OutputTransform\Stages;

use Mediawiki\OutputTransform\OutputTransformStage;
use Mediawiki\OutputTransform\OutputTransformStageTest;
use MediaWiki\Parser\ParserOutput;

/**
 * @covers \Mediawiki\OutputTransform\Stages\RenderDebugInfo
 */
class RenderDebugInfoTest extends OutputTransformStageTest {

	public function createStage(): OutputTransformStage {
		return new RenderDebugInfo( $this->getServiceContainer()->getHookContainer() );
	}

	public function provideShouldRun(): array {
		return [
			[ new ParserOutput(), null, [ 'includeDebugInfo' => true ] ],
		];
	}

	public function provideShouldNotRun(): array {
		return [
			[ new ParserOutput(), null, [] ],
			[ new ParserOutput(), null, [ 'includeDebugInfo' => false ] ],
		];
	}

	/**
	 * TODO this only covers the addition of the report, not the content of the report itself. Expanding this
	 * test may be a good idea.
	 */
	public function provideTransform(): array {
		$text = <<<EOF
<!DOCTYPE html>
<html><head><title>Main Page</title></head><body data-parsoid='{"dsr":[0,6,0,0]}' lang="en"><p data-parsoid='{"dsr":[0,5,0,0]}'>hello</p>
</body></html>
EOF;
		$expectedText = $text . "\n<!-- \nNewPP limit report\nComplications: []\n-->\n";
		$po = new ParserOutput( $text );
		$po->setLimitReportData( 'test', 'limit' );
		$expected = new ParserOutput( $expectedText );
		$expected->setLimitReportData( 'test', 'limit' );
		return [
			[ $po, null, [], $expected ],
		];
	}
}
