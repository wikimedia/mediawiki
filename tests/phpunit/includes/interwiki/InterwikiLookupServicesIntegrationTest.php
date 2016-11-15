<?php
/**
 * @group MediaWiki
 */

use MediaWiki\MediaWikiServices;
use MediaWiki\Interwiki\CdbInterwikiLookup;
use MediaWiki\Interwiki\HashInterwikiLookup;
use MediaWiki\Interwiki\DatabaseInterwikiLookup;

class InterwikiLookupServicesIntegrationTest extends MediaWikiTestCase {
	public function setUp() {
		parent::setUp();
		MediaWikiServices::getInstance()
			->resetServiceForTesting( 'InterwikiLookup' );
	}

	public static function provideGlobals() {
		return [
			'database' => [
				[
					'wgContLang' => Language::factory( 'en' ),
					'wgInterwikiExpiry' => 60*60,
					'wgInterwikiCache' => false,
				],
				DatabaseInterwikiLookup::class,
			],
			'hash' => [
				[
					'wgContLang' => Language::factory( 'en' ),
					'wgInterwikiExpiry' => 60*60,
					'wgInterwikiCache' => [],
					'wgInterwikiScopes' => 3,
					'wgInterwikiFallbackSite' => 'en'
				],
				HashInterwikiLookup::class,
			],
			'cdb' => [
				[
					'wgContLang' => Language::factory( 'en' ),
					'wgInterwikiExpiry' => 60*60,
					'wgInterwikiCache' => '/path/to/file.cdb',
					'wgInterwikiScopes' => 3,
					'wgInterwikiFallbackSite' => 'en'
				],
				CdbInterwikiLookup::class,
			]
		];
	}

	/**
	 * @dataProvider provideGlobals
	 */
	public function testServiceIntegration( array $overrideGlobals, $expectedImpl ) {
		$this->setMwGlobals( $overrideGlobals );
		$this->assertEquals(
			$expectedImpl,
			get_class( MediaWikiServices::getInstance()->getInterwikiLookup() )
		);
	}
}
