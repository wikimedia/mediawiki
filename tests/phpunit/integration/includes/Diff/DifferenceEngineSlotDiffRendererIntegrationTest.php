<?php

use MediaWiki\MainConfigNames;

/**
 * @group small
 */
class DifferenceEngineSlotDiffRendererIntegrationTest extends \MediaWikiIntegrationTestCase {

	/**
	 * @covers \DifferenceEngineSlotDiffRenderer::getExtraCacheKeys
	 */
	public function testGetExtraCacheKeys_noExternalDiffEngineConfigured() {
		$this->overrideConfigValues( [
			MainConfigNames::DiffEngine => null,
			MainConfigNames::ExternalDiffEngine => null,
		] );

		$differenceEngine = new CustomDifferenceEngine();
		$slotDiffRenderer = new DifferenceEngineSlotDiffRenderer( $differenceEngine );
		$extraCacheKeys = $slotDiffRenderer->getExtraCacheKeys();
		$this->assertSame( [ 'foo' ], $extraCacheKeys );
	}
}
