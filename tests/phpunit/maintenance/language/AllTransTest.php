<?php

namespace MediaWiki\Tests\Maintenance;

use AllTrans;
use MediaWiki\Json\FormatJson;
use MediaWiki\Tests\Mocks\Language\MockLocalisationCacheTrait;

/**
 * @covers \AllTrans
 * @author Dreamy Jazz
 */
class AllTransTest extends MaintenanceBaseTestCase {
	use MockLocalisationCacheTrait;

	protected function getMaintenanceClass() {
		return AllTrans::class;
	}

	/** @dataProvider provideExecute */
	public function testExecute( $enMessageJsonFile ) {
		$this->setService( 'LocalisationCache', $this->getMockLocalisationCache() );
		$expectedMessages = FormatJson::decode( file_get_contents( $enMessageJsonFile ), true );
		$this->expectOutputString( implode( "\n", array_keys( $expectedMessages ) ) . "\n" );
		$this->maintenance->execute();
	}

	public static function provideExecute() {
		return [
			'Using mock en.json' => [ MW_INSTALL_PATH . "/tests/phpunit/data/localisationcache/en.json" ],
		];
	}
}
