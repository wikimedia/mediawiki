<?php

use MediaWiki\Context\RequestContext;
use MediaWiki\Diff\TextDiffer\TextDiffer;
use MediaWiki\Diff\TextDiffer\Wikidiff2TextDiffer;
use MediaWiki\Tests\Diff\TextDiffer\TextDifferData;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Diff\TextDiffer\Wikidiff2TextDiffer
 */
class Wikidiff2TextDifferTest extends MediaWikiIntegrationTestCase {
	private function createDiffer() {
		$differ = new Wikidiff2TextDiffer( [] );
		$localizer = RequestContext::getMain();
		$localizer->setLanguage( 'qqx' );
		$differ->setLocalizer( $localizer );
		TestingAccessWrapper::newFromObject( $differ )->haveMoveSupport = true;
		return $differ;
	}

	public static function provideRenderBatch() {
		return [
			[ false ],
			[ true ]
		];
	}

	/**
	 * @requires extension wikidiff2
	 * @dataProvider provideRenderBatch
	 * @param bool $useMultiFormat
	 */
	public function testRenderBatch( $useMultiFormat ) {
		if ( !function_exists( 'wikidiff2_multi_format_diff' ) && $useMultiFormat ) {
			$this->markTestSkipped( 'Need wikidiff2 1.14.0+' );
		}
		$oldText = 'foo';
		$newText = 'bar';
		$differ = new Wikidiff2TextDiffer( [ 'useMultiFormat' => $useMultiFormat ] );
		// Should not need a MessageLocalizer
		$result = $differ->renderBatch( $oldText, $newText, [ 'table', 'inline' ] );
		$this->assertSame(
			[
				'table' => TextDifferData::WIKIDIFF2_TABLE,
				'inline' => TextDifferData::WIKIDIFF2_INLINE
			],
			$result
		);
	}

	public function testGetName() {
		$differ = new Wikidiff2TextDiffer( [] );
		$this->assertSame( 'wikidiff2', $differ->getName() );
	}

	public function testGetFormatContext() {
		$differ = new Wikidiff2TextDiffer( [] );
		$this->assertSame( TextDiffer::CONTEXT_ROW, $differ->getFormatContext( 'table' ) );
	}

	public static function provideGetTablePrefixes() {
		return [
			[
				'table',
				'class="mw-diff-inline-legend oo-ui-element-hidden".*\(diff-inline-tooltip-ins\)'
			],
			[
				'inline',
				'class="mw-diff-inline-legend".*\(diff-inline-tooltip-ins\)'
			],
		];
	}

	/**
	 * @dataProvider provideGetTablePrefixes
	 * @param string $format
	 * @param string $pattern
	 */
	public function testGetTablePrefixes( $format, $pattern ) {
		$differ = $this->createDiffer();
		$result = $differ->getTablePrefixes( $format );
		$this->assertMatchesRegularExpression(
			'{' . $pattern . '}s',
			$result[TextSlotDiffRenderer::INLINE_LEGEND_KEY]
		);
	}

	public static function provideLocalize() {
		return [
			'normal table' => [
				'table',
				TextDifferData::WIKIDIFF2_TABLE,
				[],
				'<td colspan="2" class="diff-lineno">\(lineno: 1\)</td>'
			],
			'table with move tooltip' => [
				'table',
				// From wikidiff2 001.phpt
				'<td class="diff-marker"><a class="mw-diff-movedpara-left" href="#movedpara_7_0_rhs">&#x26AB;</a></td>',
				[],
				'title="\(diff-paragraph-moved-tonew\)"'
			],
			'table with reduced line numbers' => [
				'table',
				TextDifferData::WIKIDIFF2_TABLE,
				[ 'reducedLineNumbers' => true ],
				'<td colspan="2" class="diff-lineno"></td>'
			],
			'inline tooltip' => [
				'inline',
				TextDifferData::WIKIDIFF2_INLINE,
				[],
				'<ins title="\(diff-inline-tooltip-ins\)">'
			],
		];
	}

	/**
	 * @dataProvider provideLocalize
	 * @param string $format
	 * @param string $input
	 * @param array $options
	 * @param string $pattern
	 */
	public function testLocalize( $format, $input, $options, $pattern ) {
		$differ = $this->createDiffer();
		$result = $differ->localize( $format, $input, $options );
		$this->assertMatchesRegularExpression(
			'{' . $pattern . '}s',
			$result
		);
	}

	public static function provideAddLocalizedTitleTooltips() {
		return [
			'moved paragraph left should get new location title' => [
				'<a class="mw-diff-movedpara-left">⚫</a>',
				'<a class="mw-diff-movedpara-left" title="(diff-paragraph-moved-tonew)">⚫</a>',
			],
			'moved paragraph right should get old location title' => [
				'<a class="mw-diff-movedpara-right">⚫</a>',
				'<a class="mw-diff-movedpara-right" title="(diff-paragraph-moved-toold)">⚫</a>',
			],
			'nothing changed when key not hit' => [
				'<a class="mw-diff-movedpara-rightis">⚫</a>',
				'<a class="mw-diff-movedpara-rightis">⚫</a>',
			],
		];
	}

	/**
	 * @dataProvider provideAddLocalizedTitleTooltips
	 */
	public function testAddLocalizedTitleTooltips( $input, $expected ) {
		$differ = TestingAccessWrapper::newFromObject( $this->createDiffer() );

		$this->assertEquals( $expected, $differ->addLocalizedTitleTooltips( 'table', $input ) );
	}

}
