<?php
declare( strict_types = 1 );

namespace MediaWiki\Tests\OutputTransform\Stages;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\OutputTransform\OutputTransformStage;
use MediaWiki\OutputTransform\Stages\DeduplicateStylesDOM;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\Parsoid\PageBundleParserOutputConverter;
use MediaWiki\Tests\OutputTransform\OutputTransformStageTestBase;
use MediaWiki\Tests\OutputTransform\TestUtils;
use Psr\Log\NullLogger;
use Wikimedia\Parsoid\Core\HtmlPageBundle;

/**
 * @covers \MediaWiki\OutputTransform\Stages\DeduplicateStyles
 */
class DeduplicateStylesDOMTest extends OutputTransformStageTestBase {

	public function createStage(): OutputTransformStage {
		return new DeduplicateStylesDOM( new ServiceOptions( [] ), new NullLogger() );
	}

	public static function provideShouldRun(): array {
		return( [
			[ new ParserOutput(), ParserOptions::newFromAnon(), [ 'deduplicateStyles' => true ] ],
			[ new ParserOutput(), ParserOptions::newFromAnon(), [] ],
		] );
	}

	public static function provideShouldNotRun(): array {
		return( [
			[ new ParserOutput(), ParserOptions::newFromAnon(), [ 'deduplicateStyles' => false ] ],
		] );
	}

	public static function provideTransform(): iterable {
		$testCases = [
			'legacy parser output' => [
				TestUtils::TEST_TO_DEDUP,
				false,
				<<<EOF
<p>This is a test document.</p>
<style data-mw-deduplicate="duplicate1">.Duplicate1 {}</style>
<link rel="mw-deduplicated-inline-style" href="mw-data:duplicate1"/>
<style data-mw-deduplicate="duplicate2">.Duplicate2 {}</style>
<link rel="mw-deduplicated-inline-style" href="mw-data:duplicate1"/>
<link rel="mw-deduplicated-inline-style" href="mw-data:duplicate2"/>
<style data-mw-not-deduplicate="duplicate1">.Duplicate1 {}</style>
<link rel="mw-deduplicated-inline-style" href="mw-data:duplicate1"/>
<style data-mw-deduplicate="duplicate3">.Duplicate1 {}</style>
<style>.Duplicate1 {}</style>
EOF
			],
			'parsoid content with encoded style tags in data-mw attribute' => [
				<<<EOF
<style data-mw-deduplicate="duplicate1">.Duplicate1 {}</style>
<span data-mw="{&quot;name&quot;:&quot;ref&quot;,&quot;attrs&quot;:{&quot;name&quot;:&quot;blank&quot;},
&quot;body&quot;:{&quot;html&quot;:&quot;<style data-mw-deduplicate=\&quot;duplicate1\&quot;>.Duplicate1 {}</style>&quot;}}"></span>
<style data-mw-deduplicate="duplicate1">.Duplicate1 {}</style>
EOF
				,
				true,
				<<<EOF
<style data-mw-deduplicate="duplicate1">.Duplicate1 {}</style>
<span data-mw='{"name":"ref","attrs":{"name":"blank"},"body":{"html":"&lt;style data-mw-deduplicate=\"duplicate1\">.Duplicate1 {}&lt;/style>"}}'></span>
<link rel="mw-deduplicated-inline-style" href="mw-data:duplicate1"/>
EOF
			]
		];

		foreach ( $testCases as $name => [ $input, $isParsoid, $expected ] ) {
			if ( $isParsoid ) {
				$in = PageBundleParserOutputConverter::parserOutputFromPageBundle(
					new HtmlPageBundle( $input )
				);
				$out = PageBundleParserOutputConverter::parserOutputFromPageBundle(
					new HtmlPageBundle( $expected )
				);
			} else {
				$in = new ParserOutput( $input );
				$out = new ParserOutput( $expected );
			}
			yield $name => [ $in, ParserOptions::newFromAnon(), [], $out ];
		}
	}
}
