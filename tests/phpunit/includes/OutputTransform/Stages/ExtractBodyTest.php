<?php
declare( strict_types = 1 );

namespace MediaWiki\Tests\OutputTransform\Stages;

use Generator;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\OutputTransform\OutputTransformStage;
use MediaWiki\OutputTransform\Stages\ExtractBody;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\Parsoid\PageBundleParserOutputConverter;
use MediaWiki\Tests\OutputTransform\OutputTransformStageTestBase;
use MediaWiki\Title\Title;
use Psr\Log\NullLogger;
use Wikimedia\Parsoid\Core\HtmlPageBundle;

/**
 * @covers \MediaWiki\OutputTransform\Stages\ExtractBody
 * @covers \MediaWiki\Parser\Parser::extractBody
 */
class ExtractBodyTest extends OutputTransformStageTestBase {

	public function createStage(): OutputTransformStage {
		$urlUtils = $this->getServiceContainer()->getUrlUtils();
		return new ExtractBody( new ServiceOptions( [] ), new NullLogger(), true, $urlUtils, null );
	}

	public static function provideShouldRun(): array {
		return [
			[ PageBundleParserOutputConverter::parserOutputFromPageBundle( new HtmlPageBundle( '' ) ), ParserOptions::newFromAnon(), [] ],
		];
	}

	public static function provideShouldNotRun(): array {
		return [
			[ new ParserOutput(), ParserOptions::newFromAnon(), [] ],
			[ new ParserOutput(), ParserOptions::newFromAnon(), [] ],
		];
	}

	public static function provideTransform(): Generator {
		$pageTemplate = <<<HTML
<!DOCTYPE html>
<html><head><base href="https://www.example.com/w/"/></head><body>__BODY__
</body></html>
HTML;
		$testData = [
			'basic' => [
				"title" => "Foo",
				"body" => "<p><a href=\"./Hello\">hello</a></p>",
				"result" => "<p><a href=\"./Hello\">hello</a></p>\n"
			],
			'body with attributes' => [
				"title" => "Foo",
				"doc" => <<<HTML
					<!DOCTYPE html>
					<html><head><base href="https://www.example.com/w/"/></head>
					<body class="bar" data-x="XX"><p><a href="./Hello">hello</a></p>
					</body></html>
					HTML,
				"result" => "<p><a href=\"./Hello\">hello</a></p>\n"
			],
			'multiple close tags' => [
				"title" => "Foo",
				"doc" => <<<HTML
					<!DOCTYPE html>
					<html><head><base href="https://www.example.com/w/"/></head>
					<body class="bar" data-x="XX"><p><a href="./Hello">hello</a></p>
					</body>
					<p>world</p>
					</body></html>
					HTML,
				"result" => "<p><a href=\"./Hello\">hello</a></p>\n</body>\n<p>world</p>\n"
			],
			'truncated with missing close tag' => [
				"title" => "Foo",
				"doc" => <<<HTML
					<!DOCTYPE html>
					<html><head><base href="https://www.example.com/w/"/></head>
					<body class="bar" data-x="XX"><p><a href="./Hello">hello</a></p>
					HTML,
				"result" => "<p><a href=\"./Hello\">hello</a></p>"
			],
			'early false-positive close tag in comment' => [
				"title" => "Foo",
				"doc" => <<<HTML
					<!DOCTYPE html>
					<html><head><base href="https://www.example.com/w/"/></head>
					<!-- This is a red </body> herring. -->
					<body class="bar" data-x="XX"><p><a href="./Hello">hello</a></p>
					</body></html>
					HTML,
				"result" => "<p><a href=\"./Hello\">hello</a></p>\n"
			],
			'early false-positive close tag in open tag' => [
				"title" => "Foo",
				"doc" => <<<HTML
					<!DOCTYPE html>
					<html><head><base href="https://www.example.com/w/"/></head>
					<body class="bar" data-x="XX" data-y=</body><p><a href="./Hello">hello</a></p>
					</body></html>
					HTML,
				"result" => "<p><a href=\"./Hello\">hello</a></p>\n"
			],
			'early false-positive close tag in open tag and truncated close tags' => [
				"title" => "Foo",
				"doc" => <<<HTML
					<!DOCTYPE html>
					<html><head><base href="https://www.example.com/w/"/></head>
					<body class="bar" data-x="XX" data-y=</body><p><a href="./Hello">hello</a></p>
					HTML,
				"result" => "<p><a href=\"./Hello\">hello</a></p>"
			],
		];
		// Set test title in parser output extension data
		foreach ( $testData as $label => $t ) {
			$title = Title::newFromText( $t['title'] );
			$docHtml = $t['doc'] ?? str_replace( "__BODY__", $t['body'], $pageTemplate );
			$poInput = new ParserOutput( $docHtml );
			$poInput->getContentHolder()->setAsHtmlString( 'body fragment', $docHtml );
			$poOutput = new ParserOutput( $t['result'] );
			// don't touch other fragments even if they contain a full HTML doc (they shouldn't)
			$poOutput->getContentHolder()->setAsHtmlString( 'body fragment', $docHtml );
			$poInput->setTitle( $title );
			$poOutput->setTitle( $title );
			yield $label => [ $poInput, ParserOptions::newFromAnon(), [], $poOutput ];
		}
	}
}
