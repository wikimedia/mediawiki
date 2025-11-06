<?php

namespace MediaWiki\Tests\Maintenance;

use DumpMessages;
use MediaWiki\Json\FormatJson;
use MediaWiki\Tests\Mocks\Language\MockLocalisationCacheTrait;

/**
 * @covers \DumpMessages
 * @author Dreamy Jazz
 */
class DumpMessagesTest extends MaintenanceBaseTestCase {
	use MockLocalisationCacheTrait;

	protected function getMaintenanceClass() {
		return DumpMessages::class;
	}

	/** @dataProvider provideExecute */
	public function testExecute( $enMessageJsonFile ) {
		$this->setService( 'LocalisationCache', $this->getMockLocalisationCache() );
		// Clear any local overrides, possibly from other extensions.
		$this->clearHook( 'MessageCacheFetchOverrides' );
		$this->expectOutputString(
			"MediaWiki " . MW_VERSION . " language file\n" .
			serialize( FormatJson::decode( file_get_contents( $enMessageJsonFile ), true ) )
		);
		$this->maintenance->execute();
	}

	public static function provideExecute() {
		return [
			'Using mock en.json' => [ MW_INSTALL_PATH . "/tests/phpunit/data/localisationcache/en.json" ],
		];
	}
}
