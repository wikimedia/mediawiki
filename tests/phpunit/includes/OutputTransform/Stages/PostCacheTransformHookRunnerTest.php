<?php

namespace MediaWiki\OutputTransform\Stages;

use MediaWiki\MainConfigNames;
use MediaWiki\OutputTransform\TestUtils;
use MediaWiki\Parser\ParserOutput;
use RequestContext;

/**
 * This test does not extend OutputTransformStageTest because we're explicitly testing that
 * the options are modified during the pipeline run.
 * @covers \MediaWiki\OutputTransform\Stages\PostCacheTransformHookRunner
 */
class PostCacheTransformHookRunnerTest extends \MediaWikiIntegrationTestCase {

	/**
	 * @covers \MediaWiki\OutputTransform\Stages\PostCacheTransformHookRunner::transform
	 */
	public function testTransform(): void {
		// Avoid other skins affecting the section edit links
		$this->overrideConfigValue( MainConfigNames::DefaultSkin, 'fallback' );
		RequestContext::resetMain();

		$this->overrideConfigValues( [
			MainConfigNames::ArticlePath => '/wiki/$1',
			MainConfigNames::ScriptPath => '/w',
			MainConfigNames::Script => '/w/index.php',
		] );

		// This tests that the options are modified by the PostCacheTransformHookRunner (if it is not run, or if
		// the options are not modified, the test fails)
		$po = new ParserOutput( TestUtils::TEST_DOC );
		$expected = new ParserOutput( TestUtils::TEST_DOC_WITH_LINKS );
		$this->getServiceContainer()->getHookContainer()->register( 'ParserOutputPostCacheTransform',
			static function ( ParserOutput $out, &$text, array &$options ) {
				$options['enableSectionEditLinks'] = true;
			}
		);
		$pipeline = $this->getServiceContainer()->getDefaultOutputPipeline();
		$res = $pipeline->run( $po, null,
			[
				'allowTOC' => true,
				'injectTOC' => false,
				'enableSectionEditLinks' => false,
				'userLang' => null,
				'skin' => null,
				'unwrap' => false,
				'wrapperDivClass' => '',
				'deduplicateStyles' => false,
				'absoluteURLs' => false,
				'includeDebugInfo' => false,
				'bodyContentOnly' => false,
			]
		);
		$this->assertEquals( $expected, $res );
	}

	/**
	 * @covers \MediaWiki\OutputTransform\Stages\PostCacheTransformHookRunner::shouldRun
	 */
	public function testShouldRun() {
		$transform = new PostCacheTransformHookRunner( $this->getServiceContainer()->getHookContainer() );
		$this->getServiceContainer()
			->getHookContainer()
			->register( 'ParserOutputPostCacheTransform',
				static function ( ParserOutput $out, &$text, array &$options ) {
					$options['enableSectionEditLinks'] = true;
				} );
		$options = [];
		self::assertTrue( $transform->shouldRun( new ParserOutput(), null, $options ) );
	}

	/**
	 * @covers \MediaWiki\OutputTransform\Stages\PostCacheTransformHookRunner::shouldRun
	 */
	public function testShouldNotRun() {
		$transform = new PostCacheTransformHookRunner( $this->getServiceContainer()->getHookContainer() );
		$this->getServiceContainer()->getHookContainer()->clear( 'ParserOutputPostCacheTransform' );
		$options = [];
		self::assertFalse( $transform->shouldRun( new ParserOutput(), null, $options ) );
	}
}
