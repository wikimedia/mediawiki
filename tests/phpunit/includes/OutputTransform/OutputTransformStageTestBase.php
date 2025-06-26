<?php
declare( strict_types = 1 );

namespace MediaWiki\Tests\OutputTransform;

use MediaWiki\Context\RequestContext;
use MediaWiki\MainConfigNames;
use MediaWiki\OutputTransform\OutputTransformStage;
use MediaWiki\Parser\Parsoid\PageBundleParserOutputConverter;
use MediaWikiIntegrationTestCase;

abstract class OutputTransformStageTestBase extends MediaWikiIntegrationTestCase {
	abstract public function createStage(): OutputTransformStage;

	abstract public static function provideShouldRun(): iterable;

	abstract public static function provideShouldNotRun(): iterable;

	abstract public static function provideTransform(): iterable;

	/**
	 * @dataProvider provideShouldRun
	 */
	public function testShouldRun( $parserOutput, $parserOptions, $options ) {
		$stage = $this->createStage();
		$this->assertTrue( $stage->shouldRun( $parserOutput, $parserOptions, $options ) );
	}

	public function setUp(): void {
		RequestContext::resetMain();
		$this->overrideConfigValues( [
			MainConfigNames::ScriptPath => '/w',
			MainConfigNames::Script => '/w/index.php',
			MainConfigNames::Server => '//TEST_SERVER',
			MainConfigNames::DefaultSkin => 'fallback'
		] );

		// Prevent extensions from interfering with the output
		$this->clearHook( 'SkinEditSectionLinks' );
	}

	/**
	 * @dataProvider provideShouldNotRun
	 */
	public function testShouldNotRun( $parserOutput, $parserOptions, $options ) {
		$stage = $this->createStage();
		$this->assertFalse( $stage->shouldRun( $parserOutput, $parserOptions, $options ) );
	}

	/**
	 * @dataProvider provideTransform
	 */
	public function testTransform( $parserOutput, $parserOptions, $options, $expected, $message = '' ) {
		$stage = $this->createStage();
		$result = $stage->transform( $parserOutput, $parserOptions, $options );
		// If this has Parsoid internal metadata, clear it in both the expected
		// value and the result; these are internal implementation details
		// that shouldn't be hardwired into tests.
		if ( PageBundleParserOutputConverter::hasPageBundle( $result ) ) {
			$key = PageBundleParserOutputConverter::PARSOID_PAGE_BUNDLE_KEY;
			$expected->setExtensionData( $key, $result->getExtensionData( $key ) );
		}
		// Similarly, clear the parse start time to avoid a spurious diff.
		$result->clearParseStartTime();
		$expected->clearParseStartTime();
		$this->assertEquals( $expected, $result, $message );
	}
}
