<?php
declare( strict_types = 1 );

namespace MediaWiki\Tests\OutputTransform;

use MediaWiki\Context\RequestContext;
use MediaWiki\MainConfigNames;
use Mediawiki\MediaWikiServices;
use MediaWiki\OutputTransform\OutputTransformStage;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\Parsoid\PageBundleParserOutputConverter;
use MediaWikiIntegrationTestCase;
use Wikimedia\TestingAccessWrapper;

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
	public function testTransform( ParserOutput $parserOutput, ?ParserOptions $parserOptions, array $options,
								   ParserOutput $expected, string $message = '' ): void {
		$stage = $this->createStage();
		$result = $stage->transform( $parserOutput, $parserOptions, $options );
		// If this has Parsoid internal metadata, clear it in both the expected
		// value and the result; these are internal implementation details
		// that shouldn't be hardwired into tests.
		if ( PageBundleParserOutputConverter::hasPageBundle( $result ) ) {
			$ch = TestingAccessWrapper::newFromObject(
				$expected->getContentHolder()
			);
			$ch->pageBundle = clone $result->getContentHolder()->getBasePageBundle();
		}
		// Similarly, clear the parse start time to avoid a spurious diff.
		$result->clearParseStartTime();
		$expected->clearParseStartTime();
		$jsonCodec = MediaWikiServices::getInstance()->getJsonCodec();
		$this->assertEquals(
			$jsonCodec->toJsonArray( $expected, ParserOutput::class ),
			$jsonCodec->toJsonArray( $result, ParserOutput::class ),
			$message
		);
	}
}
