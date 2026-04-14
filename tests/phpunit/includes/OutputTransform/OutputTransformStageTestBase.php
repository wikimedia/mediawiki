<?php
declare( strict_types = 1 );

namespace MediaWiki\Tests\OutputTransform;

use MediaWiki\Context\RequestContext;
use MediaWiki\MainConfigNames;
use Mediawiki\MediaWikiServices;
use MediaWiki\OutputTransform\OutputTransformStage;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWikiIntegrationTestCase;

abstract class OutputTransformStageTestBase extends MediaWikiIntegrationTestCase {
	abstract public function createStage(): OutputTransformStage;

	abstract public static function provideShouldRun(): iterable;

	abstract public static function provideShouldNotRun(): iterable;

	abstract public static function provideTransform(): iterable;

	/**
	 * @dataProvider provideShouldRun
	 */
	public function testShouldRun( ParserOutput $parserOutput, ParserOptions $parserOptions, array $options ) {
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
	public function testShouldNotRun( ParserOutput $parserOutput, ParserOptions $parserOptions, array $options ) {
		$stage = $this->createStage();
		$this->assertFalse( $stage->shouldRun( $parserOutput, $parserOptions, $options ) );
	}

	/**
	 * @dataProvider provideTransform
	 */
	public function testTransform( ParserOutput $parserOutput, ParserOptions $parserOptions, array $options,
								   ParserOutput $expected, string $message = '' ): void {
		$stage = $this->createStage();
		$result = $stage->transform( $parserOutput, $parserOptions, $options );

		// Clear the parse start time to avoid a spurious diff.
		$result->clearParseStartTime();
		$expected->clearParseStartTime();
		$jsonCodec = MediaWikiServices::getInstance()->getJsonCodec();
		$this->assertEquals(
			$jsonCodec->toJsonArray( $expected ),
			$jsonCodec->toJsonArray( $result ),
			$message
		);
	}
}
