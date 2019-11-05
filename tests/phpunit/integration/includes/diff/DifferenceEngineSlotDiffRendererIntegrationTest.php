<?php

/**
 * @group small
 */
class DifferenceEngineSlotDiffRendererIntegrationTest extends \MediaWikiIntegrationTestCase {

	/**
	 * @covers DifferenceEngineSlotDiffRenderer::getExtraCacheKeys
	 */
	public function testGetExtraCacheKeys_noExternalDiffEngineConfigured() {
		$this->setMwGlobals( [
			'wgDiffEngine' => null,
			'wgExternalDiffEngine' => null,
		] );

		$differenceEngine = new CustomDifferenceEngine();
		$slotDiffRenderer = new DifferenceEngineSlotDiffRenderer( $differenceEngine );
		$extraCacheKeys = $slotDiffRenderer->getExtraCacheKeys();
		$this->assertSame( [ 'foo' ], $extraCacheKeys );
	}
}
