<?php

use MediaWiki\MainConfigNames;
use MediaWiki\Utils\UrlUtils;

/**
 * @group GlobalFunctions
 * @covers ::wfExpandUrl
 */
class WfExpandUrlTest extends MediaWikiIntegrationTestCase {
	/**
	 * Same tests as the UrlUtils method to ensure they don't fall out of sync
	 * @dataProvider UrlUtilsProviders::provideExpand
	 */
	public function testWfExpandUrl( string $input, array $options,
		$defaultProto, string $expected
	) {
		$conf = [
			MainConfigNames::Server => $options[UrlUtils::SERVER] ?? null,
			MainConfigNames::CanonicalServer => $options[UrlUtils::CANONICAL_SERVER] ?? null,
			MainConfigNames::InternalServer => $options[UrlUtils::INTERNAL_SERVER] ?? null,
			MainConfigNames::HttpsPort => $options[UrlUtils::HTTPS_PORT] ?? 443,
		];
		$currentProto = $options[UrlUtils::FALLBACK_PROTOCOL];

		$this->overrideConfigValues( $conf );
		$this->setRequest( new FauxRequest( [], false, null, $currentProto ) );
		$this->assertEquals( $expected, wfExpandUrl( $input, $defaultProto ) );
	}
}
