<?php

namespace MediaWiki\Tests\OutputTransform\Stages;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\OutputTransform\OutputTransformStage;
use MediaWiki\OutputTransform\Stages\DeduplicateStyles;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Tests\OutputTransform\OutputTransformStageTestBase;
use MediaWiki\Tests\OutputTransform\TestUtils;
use Psr\Log\NullLogger;

/**
 * @covers \MediaWiki\OutputTransform\Stages\DeduplicateStyles
 */
class DeduplicateStylesTest extends OutputTransformStageTestBase {

	public function createStage(): OutputTransformStage {
		return new DeduplicateStyles(
			new ServiceOptions( [] ),
			new NullLogger()
		);
	}

	public function provideShouldRun(): array {
		return( [
			[ new ParserOutput(), null, [ 'deduplicateStyles' => true ] ],
			[ new ParserOutput(), null, [] ],
		] );
	}

	public function provideShouldNotRun(): array {
		return( [
			[ new ParserOutput(), null, [ 'deduplicateStyles' => false ] ],
		] );
	}

	public function provideTransform(): iterable {
		$testCases = [
			'legacy parser output' => [
				TestUtils::TEST_TO_DEDUP,
				[],
				<<<EOF
<p>This is a test document.</p>
<style data-mw-deduplicate="duplicate1">.Duplicate1 {}</style>
<link rel="mw-deduplicated-inline-style" href="mw-data:duplicate1">
<style data-mw-deduplicate="duplicate2">.Duplicate2 {}</style>
<link rel="mw-deduplicated-inline-style" href="mw-data:duplicate1">
<link rel="mw-deduplicated-inline-style" href="mw-data:duplicate2">
<style data-mw-not-deduplicate="duplicate1">.Duplicate1 {}</style>
<link rel="mw-deduplicated-inline-style" href="mw-data:duplicate1">
<style data-mw-deduplicate="duplicate3">.Duplicate1 {}</style>
<style>.Duplicate1 {}</style>
EOF
			],
			'parsoid content with encoded style tags in data-mw attribute' => [
				<<<EOF
<style data-mw-deduplicate="duplicate1">.Duplicate1 {}</style>
<span data-mw="{&quot;name&quot;:&quot;ref&quot;,&quot;attrs&quot;:{&quot;name&quot;:&quot;blank&quot;},
&quot;body&quot;:{&quot;html&quot;:&quot;<style data-mw-deduplicate=\&quot;duplicate1\&quot;>.Duplicate1 {}</style>&quot;}"></span>
<style data-mw-deduplicate="duplicate1">.Duplicate1 {}</style>
EOF
				,
				[ 'isParsoidContent' => true ],
				<<<EOF
<style data-mw-deduplicate="duplicate1">.Duplicate1 {}</style>
<span data-mw="{&quot;name&quot;:&quot;ref&quot;,&quot;attrs&quot;:{&quot;name&quot;:&quot;blank&quot;},
&quot;body&quot;:{&quot;html&quot;:&quot;<style data-mw-deduplicate=\&quot;duplicate1\&quot;>.Duplicate1 {}</style>&quot;}"></span>
<link rel="mw-deduplicated-inline-style" href="mw-data:duplicate1">
EOF
			]
		];

		foreach ( $testCases as $name => [ $input, $options, $expected ] ) {
			yield $name => [
				new ParserOutput( $input ),
				null,
				$options,
				new ParserOutput( $expected )
			];
		}
	}
}
