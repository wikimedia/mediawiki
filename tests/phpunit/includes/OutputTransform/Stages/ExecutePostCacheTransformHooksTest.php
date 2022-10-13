<?php

namespace MediaWiki\Tests\OutputTransform\Stages;

use MediaWiki\Context\RequestContext;
use MediaWiki\MainConfigNames;
use MediaWiki\OutputTransform\Stages\ExecutePostCacheTransformHooks;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Tests\OutputTransform\TestUtils;

/**
 * This test does not extend OutputTransformStageTestBase because we're explicitly testing that
 * the options are modified during the pipeline run.
 * @covers \MediaWiki\OutputTransform\Stages\ExecutePostCacheTransformHooks
 */
class ExecutePostCacheTransformHooksTest extends \MediaWikiIntegrationTestCase {

	/**
	 * @covers \MediaWiki\OutputTransform\Stages\ExecutePostCacheTransformHooks::transform
	 */
	public function testTransform(): void {
		// Avoid other skins affecting the section edit links
		$this->overrideConfigValue( MainConfigNames::DefaultSkin, 'fallback' );
		RequestContext::resetMain();

		$this->overrideConfigValues( [
			MainConfigNames::ScriptPath => '/w',
			MainConfigNames::Script => '/w/index.php',
			MainConfigNames::ParserEnableLegacyHeadingDOM => false,
		] );

		// This tests that the options are modified by the PostCacheTransformHookRunner (if it is not run, or if
		// the options are not modified, the test fails)
		$po = new ParserOutput( TestUtils::TEST_DOC );
		$expected = new ParserOutput( TestUtils::TEST_DOC_WITH_LINKS_NEW_MARKUP );
		$this->getServiceContainer()->getHookContainer()->register( 'ParserOutputPostCacheTransform',
			static function ( ParserOutput $out, &$text, array &$options ) {
				$options['enableSectionEditLinks'] = true;
			}
		);
		// T358103: VisualEditor will change the section edit links causing a test failure.
		$this->clearHook( 'SkinEditSectionLinks' );
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
			]
		);
		$this->assertEquals( $expected, $res );
	}

	/**
	 * @covers \MediaWiki\OutputTransform\Stages\ExecutePostCacheTransformHooks::shouldRun
	 */
	public function testShouldRun() {
		$transform = new ExecutePostCacheTransformHooks( $this->getServiceContainer()->getHookContainer() );
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
	 * @covers \MediaWiki\OutputTransform\Stages\ExecutePostCacheTransformHooks::shouldRun
	 */
	public function testShouldNotRun() {
		$transform = new ExecutePostCacheTransformHooks( $this->getServiceContainer()->getHookContainer() );
		$this->getServiceContainer()->getHookContainer()->clear( 'ParserOutputPostCacheTransform' );
		$options = [];
		self::assertFalse( $transform->shouldRun( new ParserOutput(), null, $options ) );
	}
}
