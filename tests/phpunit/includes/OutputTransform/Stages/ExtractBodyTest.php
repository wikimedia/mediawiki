<?php
declare( strict_types = 1 );

namespace MediaWiki\Tests\OutputTransform\Stages;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\OutputTransform\OutputTransformStage;
use MediaWiki\OutputTransform\Stages\ExtractBody;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\Parsoid\ParsoidParser;
use MediaWiki\Tests\OutputTransform\OutputTransformStageTestBase;
use Psr\Log\NullLogger;

/**
 * @covers \MediaWiki\OutputTransform\Stages\ExtractBody
 */
class ExtractBodyTest extends OutputTransformStageTestBase {

	public function createStage(): OutputTransformStage {
		$urlUtils = $this->getServiceContainer()->getUrlUtils();
		return new ExtractBody( new ServiceOptions( [] ), new NullLogger(), $urlUtils, null );
	}

	public static function provideShouldRun(): array {
		return [
			[ new ParserOutput(), null, [ 'isParsoidContent' => true ] ],
		];
	}

	public static function provideShouldNotRun(): array {
		return [
			[ new ParserOutput(), null, [ 'isParsoidContent' => false ] ],
			[ new ParserOutput(), null, [] ],
		];
	}

	public static function provideTransform(): array {
		$pageTemplate = <<<EOF
<!DOCTYPE html>
<html><head><base href="https://www.example.com/w/"/></head><body>__BODY__
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
			// hrefs have db-key normalization of titles - test that output isn't tripped by that
			[
				"title" => "Foo_Bar Baz",
				"body" => "<p><a href=\"./Foo_Bar_Baz#cite1\">hello</a></p>",
				"result" => "<p><a href=\"#cite1\">hello</a></p>\n"
			],
			[
				"title" => "Foo+Bar",
				"body" => "<p><a href=\"./Foo+Bar#cite1\">hello</a></p>",
				"result" => "<p><a href=\"#cite1\">hello</a></p>\n"
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
				"title" => 'Foo/Bar"Baz',
				"body" => "<p><a href='./Foo/Bar\"Baz#cite1'>hello</a></p>",
				"result" => "<p><a href=\"#cite1\">hello</a></p>\n"
			]
		];
		$opts = [];
		$tests = [];
		// Set test title in parser output extension data
		foreach ( $testData as $t ) {
			$titleDBKey = strtr( $t['title'], ' ', '_' );
			$text = str_replace( "__BODY__", $t['body'], $pageTemplate );
			$poInput = new ParserOutput( $text );
			$poOutput = new ParserOutput( $t['result'] );
			$poInput->setExtensionData( ParsoidParser::PARSOID_TITLE_KEY, $titleDBKey );
			$poOutput->setExtensionData( ParsoidParser::PARSOID_TITLE_KEY, $titleDBKey );
			$tests[] = [ $poInput, null, $opts, $poOutput ];
		}
		return $tests;
	}
}
