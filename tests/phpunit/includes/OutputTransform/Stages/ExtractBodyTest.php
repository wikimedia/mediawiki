<?php

namespace MediaWiki\Tests\OutputTransform\Stages;

use MediaWiki\OutputTransform\OutputTransformStage;
use MediaWiki\OutputTransform\Stages\ExtractBody;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Tests\OutputTransform\OutputTransformStageTestBase;

/**
 * @covers \MediaWiki\OutputTransform\Stages\ExtractBody
 */
class ExtractBodyTest extends OutputTransformStageTestBase {

	public function createStage(): OutputTransformStage {
		return new ExtractBody();
	}

	public function provideShouldRun(): array {
		return [
			[ new ParserOutput(), null, [ 'isParsoidContent' => true ] ],
		];
	}

	public function provideShouldNotRun(): array {
		return [
			[ new ParserOutput(), null, [ 'isParsoidContent' => false ] ],
			[ new ParserOutput(), null, [] ],
		];
	}

	public function provideTransform(): array {
		$text = <<<EOF
<!DOCTYPE html>
<html><head><title>Main Page</title><base href="https://www.example.com/w/"/></head><body data-parsoid="{'dsr':[0,6,0,0]} "lang="en"><p data-parsoid="{'dsr':[0,5,0,0]}"><a href="./Hello">hello</a></p>
</body></html>
EOF;
		$body = "<p data-parsoid=\"{'dsr':[0,5,0,0]}\"><a href=\"https://www.example.com/w/Hello\">hello</a></p>\n";
		$opts = [];
		return [
			[ new ParserOutput( $text ), null, $opts, new ParserOutput( $body ) ]
		];
	}
}
