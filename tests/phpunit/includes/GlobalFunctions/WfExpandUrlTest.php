<?php
use MediaWiki\Utils\UrlUtils;

/**
 * @group GlobalFunctions
 * @covers ::wfExpandUrl
 */
class WfExpandUrlTest extends MediaWikiIntegrationTestCase {
	/**
	 * @dataProvider provideExpandableUrls
	 */
	public function testWfExpandUrl( string $input, array $conf,
		string $currentProto, $defaultProto, string $expected
	) {
		$this->setMwGlobals( $conf );
		$this->setRequest( new FauxRequest( [], false, null, $currentProto ) );
		$this->assertEquals( $expected, wfExpandUrl( $input, $defaultProto ) );
	}

	public static function provideExpandableUrls() {
		// Same tests as the UrlUtils method to ensure they don't fall out of sync
		foreach (
			UrlUtilsTest::provideExpand() as $key => [ $input, $options, $defaultProto, $expected ]
		) {
			$conf = [
				'wgServer' => $options[UrlUtils::SERVER] ?? null,
				'wgCanonicalServer' => $options[UrlUtils::CANONICAL_SERVER] ?? null,
				'wgInternalServer' => $options[UrlUtils::INTERNAL_SERVER] ?? null,
				'wgHttpsPort' => $options[UrlUtils::HTTPS_PORT] ?? 443,
			];
			yield $key =>
				[ $input, $conf, $options[UrlUtils::FALLBACK_PROTOCOL], $defaultProto, $expected ];
		}
	}
}
