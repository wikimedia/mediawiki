<?php
namespace MediaWiki\Tests\Languages;

use MediaWiki\MainConfigNames;
use MediaWiki\Tests\Language\LanguageClassesTestCase;
use TextSlotDiffRenderer;

/**
 * @covers \LanguageZh
 */
class LanguageZhTest extends LanguageClassesTestCase {
	public function testSegmentForDiff() {
		$this->overrideConfigValue( MainConfigNames::DiffEngine, 'php' );
		$lhs = '维基';
		$rhs = '维基百科';
		$diff = TextSlotDiffRenderer::diff( $lhs, $rhs, [ 'contentLanguage' => 'zh' ] );
		// Check that only the second part is highlighted, and word segmentation markers are not present
		$this->assertStringContainsString(
			'<div>维基<ins class="diffchange diffchange-inline">百科</ins></div>',
			$diff
		);
	}
}
