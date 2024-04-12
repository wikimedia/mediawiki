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
		$pageTemplate = <<<EOF
<!DOCTYPE html>
<html><head><title>__TITLE__</title><link rel="dc:isVersionOf" href="https://www.example.com/w/__TITLE__"/><base href="https://www.example.com/w/"/></head><body>__BODY__
</body></html>
EOF;
		$testData = [
			[
				"title" => "Foo",
				"body" => "<p><a href=\"./Hello\">hello</a></p>",
				"result" => "<p><a href=\"https://www.example.com/w/Hello\">hello</a></p>\n"
			],
			// Rest of these tests ensure that self-page cite fragments are converted to fragment urls
			// Tests different title scenarios to exercise edge cases
			[
				"title" => "Foo",
				"body" => "<p><a href=\"./Foo#cite1\">hello</a><a href=\"./Foo/Bar#cite1\">boo</a></p>",
				"result" => "<p><a href=\"#cite1\">hello</a><a href=\"https://www.example.com/w/Foo/Bar#cite1\">boo</a></p>\n"
			],
			[
				"title" => "Foo/Bar",
				"body" => "<p><a href=\"./Foo/Bar#cite1\">hello</a></p>",
				"result" => "<p><a href=\"#cite1\">hello</a></p>\n"
			],
			[
				"title" => "Foo/Bar'Baz",
				"body" => "<p><a href=\"./Foo/Bar'Baz#cite1\">hello</a></p>",
				"result" => "<p><a href=\"#cite1\">hello</a></p>\n"
			],
			[
				"title" => "Foo/Bar%22Baz",
				"body" => "<p><a href='./Foo/Bar\"Baz#cite1'>hello</a></p>",
				"result" => "<p><a href=\"#cite1\">hello</a></p>\n"
			]
		];
		$opts = [];
		$tests = [];
		foreach ( $testData as $t ) {
			// Strictly speaking, __TITLE__ in the <title> tag will have entites decoded
			// but since we aren't testing that or using that tag, we are ignoring that detail.
			$text = str_replace( "__TITLE__", $t['title'], $pageTemplate );
			$text = str_replace( "__BODY__", $t['body'], $text );
			$tests[] = [ new ParserOutput( $text ), null, $opts, new ParserOutput( $t['result'] ) ];
		}
		return $tests;
	}
}
