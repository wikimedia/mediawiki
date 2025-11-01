<?php

use MediaWiki\Context\RequestContext;
use MediaWiki\Diff\TextDiffer\PhpTextDiffer;
use MediaWiki\Tests\Diff\TextDiffer\TextDifferData;

/**
 * @covers \MediaWiki\Diff\TextDiffer\PhpTextDiffer
 * @covers \MediaWiki\Diff\TextDiffer\BaseTextDiffer
 */
class PhpTextDifferTest extends MediaWikiIntegrationTestCase {
	private function createDiffer() {
		$lang = $this->getServiceContainer()
			->getLanguageFactory()
			->getLanguage( 'en' );
		$differ = new PhpTextDiffer( $lang );

		$localizer = RequestContext::getMain();
		$localizer->setLanguage( $lang );

		$differ->setLocalizer( $localizer );
		return $differ;
	}

	public function testRender() {
		$differ = $this->createDiffer();
		$result = $differ->render( 'foo', 'bar', 'table' );
		$this->assertSame( TextDifferData::PHP_TABLE, $result );
	}

	public static function provideRenderBatch() {
		return [
			'empty' => [
				[],
				[]
			],
			'one format' => [
				[ 'table' ],
				[
					'table' => TextDifferData::PHP_TABLE,
				]
			],
			'multiple formats' => [
				[ 'table', 'unified' ],
				[
					'table' => TextDifferData::PHP_TABLE,
					'unified' => TextDifferData::PHP_UNIFIED,
				]
			],
		];
	}

	/**
	 * @dataProvider provideRenderBatch
	 * @param array $formats
	 * @param array $expected
	 */
	public function testRenderBatch( $formats, $expected ) {
		$oldText = 'foo';
		$newText = 'bar';
		$differ = $this->createDiffer();
		$result = $differ->renderBatch( $oldText, $newText, $formats );
		$this->assertSame( $expected, $result );
	}

	public function testHasFormat() {
		$differ = $this->createDiffer();
		$this->assertTrue( $differ->hasFormat( 'table' ) );
		$this->assertFalse( $differ->hasFormat( 'external' ) );
	}

	public function testAddModules() {
		$out = RequestContext::getMain()->getOutput();
		$differ = $this->createDiffer();
		$differ->addModules( $out, 'table' );
		$this->assertSame( [], $out->getModules() );
	}

	public function testGetCacheKeys() {
		$differ = $this->createDiffer();
		$result = $differ->getCacheKeys( [ 'table' ] );
		$this->assertSame( [], $result );
	}

	public static function provideLocalize() {
		return [
			[ 1, [], 'Line 1:' ],
			[ 2, [], 'Line 2:' ],
			[ 1, [ 'reducedLineNumbers' => true ], '' ],
			[ [ 3, 5 ], [ 'diff-type' => 'inline' ], 'Line 3 ⟶ 5:' ],
			[ [ 1, 5 ], [ 'diff-type' => 'inline', 'reducedLineNumbers' => true ], 'Line 1 ⟶ 5:' ],
			[ [ 1, 1 ], [ 'diff-type' => 'inline', 'reducedLineNumbers' => true ], '' ]
		];
	}

	/**
	 * @dataProvider provideLocalize
	 * @param int|int[] $line
	 * @param array $options
	 * @param string $expected
	 */
	public function testLocalize( $line, $options, $expected ) {
		$content = is_array( $line )
			? "<!-- LINES $line[0],$line[1] -->"
			: "<!--LINE $line-->";
		$differ = $this->createDiffer();
		$result = $differ->localize(
			'table',
			$content,
			$options
		);
		$this->assertSame( $expected, $result );
	}

	public function testGetTablePrefixes() {
		$this->assertSame( [], $this->createDiffer()->getTablePrefixes( 'table' ) );
	}

	public function testGetPreferredFormatBatch() {
		$this->assertSame(
			[ 'table' ],
			$this->createDiffer()->getPreferredFormatBatch( 'table' )
		);
	}
}
