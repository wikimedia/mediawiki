<?php
declare( strict_types = 1 );

namespace MediaWiki\Tests\OutputTransform\Stages;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\OutputTransform\OutputTransformStage;
use MediaWiki\OutputTransform\Stages\RenderDebugInfo;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Tests\OutputTransform\OutputTransformStageTestBase;
use Psr\Log\NullLogger;

/**
 * @covers \MediaWiki\OutputTransform\Stages\RenderDebugInfo
 */
class RenderDebugInfoTest extends OutputTransformStageTestBase {

	public function createStage(): OutputTransformStage {
		return new RenderDebugInfo(
			new ServiceOptions( [] ),
			new NullLogger(),
			$this->getServiceContainer()->getHookContainer()
		);
	}

	public static function provideShouldRun(): array {
		return [
			[ new ParserOutput(), null, [ 'includeDebugInfo' => true ] ],
		];
	}

	public static function provideShouldNotRun(): array {
		return [
			[ new ParserOutput(), null, [] ],
			[ new ParserOutput(), null, [ 'includeDebugInfo' => false ] ],
		];
	}

	/**
	 * TODO this only covers the addition of the report, not the content of the report itself. Expanding this
	 * test may be a good idea.
	 */
	public static function provideTransform(): array {
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
