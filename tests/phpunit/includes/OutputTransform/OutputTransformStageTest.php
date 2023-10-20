<?php

namespace Mediawiki\OutputTransform;

use MediaWiki\MainConfigNames;
use MediaWikiIntegrationTestCase;
use RequestContext;

abstract class OutputTransformStageTest extends MediaWikiIntegrationTestCase {
	abstract public function createStage(): OutputTransformStage;

	abstract public function provideShouldRun(): array;

	abstract public function provideShouldNotRun(): array;

	abstract public function provideTransform(): array;

	public function testShouldRun() {
		$stage = $this->createStage();
		foreach ( $this->provideShouldRun() as [ $parserOutput, $parserOptions, $options ] ) {
			$this->assertTrue( $stage->shouldRun( $parserOutput, $parserOptions, $options ) );
		}
	}

	public function setUp(): void {
		RequestContext::resetMain();
		$this->overrideConfigValues( [
			MainConfigNames::ArticlePath => '/wiki/$1',
			MainConfigNames::ScriptPath => '/w',
			MainConfigNames::Script => '/w/index.php',
			MainConfigNames::Server => '//TEST_SERVER',
			MainConfigNames::DefaultSkin => 'fallback'
		] );
	}

	public function testShouldNotRun() {
		$stage = $this->createStage();
		foreach ( $this->provideShouldNotRun() as [ $parserOutput, $parserOptions, $options ] ) {
			$this->assertFalse( $stage->shouldRun( $parserOutput, $parserOptions, $options ) );
		}
		if ( count( $this->provideShouldNotRun() ) === 0 ) {
			$this->markTestSkipped( 'No case where the pass is not run' );
		}
	}

	public function testTransform() {
		$stage = $this->createStage();
		foreach ( $this->provideTransform() as [ $parserOutput, $parserOptions, $options, $expected ] ) {
			$this->assertEquals( $expected, $stage->transform( $parserOutput, $parserOptions, $options ) );
		}
	}
}
