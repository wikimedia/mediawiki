<?php

use MediaWiki\Diff\TextDiffer\ManifoldTextDiffer;
use MediaWiki\Tests\Diff\TextDiffer\TextDifferData;

/**
 * @covers \MediaWiki\Diff\TextDiffer\ManifoldTextDiffer
 * @covers \MediaWiki\Diff\TextDiffer\BaseTextDiffer
 */
class ManifoldTextDifferTest extends MediaWikiIntegrationTestCase {
	private function createDiffer( $configVars = [] ) {
		$services = $this->getServiceContainer();
		return new ManifoldTextDiffer(
			RequestContext::getMain(),
			$services->getLanguageFactory()->getLanguage( 'en' ),
			$configVars['DiffEngine'] ?? null,
			$configVars['ExternalDiffEngine'] ?? null,
			$configVars['Wikidiff2Options'] ?? []
		);
	}

	public function testGetName() {
		$this->assertSame( 'manifold', $this->createDiffer()->getName() );
	}

	public function testGetFormats() {
		if ( extension_loaded( 'wikidiff2' ) ) {
			$formats = [ 'table', 'inline', 'unified' ];
		} else {
			$formats = [ 'table', 'unified' ];
		}
		$this->assertSame(
			$formats,
			$this->createDiffer()->getFormats()
		);
	}

	public function testHasFormat() {
		$differ = $this->createDiffer();
		$this->assertTrue( $differ->hasFormat( 'table' ) );
		if ( extension_loaded( 'wikidiff2' ) ) {
			$this->assertTrue( $differ->hasFormat( 'inline' ) );
		}
		$this->assertFalse( $differ->hasFormat( 'external' ) );
		$this->assertFalse( $differ->hasFormat( 'nonexistent' ) );
	}

	public function testHasFormatExternal() {
		if ( wfIsWindows() ) {
			$this->markTestSkipped( 'This test only works on non-Windows platforms' );
		}
		$differ = $this->createDiffer( [
			'ExternalDiffEngine' => __DIR__ . '/externalDiffTest.sh'
		] );
		$this->assertTrue( $differ->hasFormat( 'external' ) );
	}

	public function testRenderForcePhp() {
		$differ = $this->createDiffer( [
			'DiffEngine' => 'php'
		] );
		$result = $differ->render( 'foo', 'bar', 'table' );
		$this->assertSame(
			TextDifferData::PHP_TABLE,
			$result
		);
	}

	/**
	 * @requires extension wikidiff2
	 */
	public function testRenderUnforcedWikidiff2() {
		$differ = $this->createDiffer();
		$result = $differ->render( 'foo', 'bar', 'table' );
		$this->assertSame(
			TextDifferData::WIKIDIFF2_TABLE,
			$result
		);
	}

	/**
	 * @requires extension wikidiff2
	 */
	public function testRenderBatchWikidiff2External() {
		if ( !is_executable( '/bin/sh' ) ) {
			$this->markTestSkipped( 'ExternalTextDiffer can\'t pass extra ' .
				'arguments like $wgPhpCli, so it\'s hard to be platform-independent' );
		}
		$differ = $this->createDiffer( [
			'ExternalDiffEngine' => __DIR__ . '/externalDiffTest.sh'
		] );
		$result = $differ->renderBatch( 'foo', 'bar',
			[ 'table', 'inline', 'external', 'unified' ] );
		$this->assertSame(
			[
				'table' => TextDifferData::WIKIDIFF2_TABLE,
				'inline' => TextDifferData::WIKIDIFF2_INLINE,
				'external' => TextDifferData::EXTERNAL,
				'unified' => TextDifferData::PHP_UNIFIED
			],
			$result
		);
	}

	public static function provideAddRowWrapper() {
		return [
			[ 'table', false ],
			[ 'external', false ],
			[ 'unified', true ]
		];
	}

	/**
	 * @dataProvider provideAddRowWrapper
	 * @param string $format
	 * @param bool $isWrap
	 */
	public function testAddRowWrapper( $format, $isWrap ) {
		if ( wfIsWindows() ) {
			$this->markTestSkipped( 'This test only works on non-Windows platforms' );
		}
		$differ = $this->createDiffer( [
			'ExternalDiffEngine' => __DIR__ . '/externalDiffTest.sh'
		] );
		$result = $differ->addRowWrapper( $format, 'foo' );
		if ( $isWrap ) {
			$this->assertSame( '<tr><td colspan="4"><pre>foo</pre></td></tr>', $result );
		} else {
			$this->assertSame( 'foo', $result );
		}
	}
}
