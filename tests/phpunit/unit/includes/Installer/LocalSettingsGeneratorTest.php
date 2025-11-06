<?php
namespace MediaWiki\Tests\Unit\Installer;

use MediaWiki\Installer\DatabaseInstaller;
use MediaWiki\Installer\Installer;
use MediaWiki\Installer\LocalSettingsGenerator;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\Installer\LocalSettingsGenerator
 */
class LocalSettingsGeneratorTest extends MediaWikiUnitTestCase {
	private function getLocalSettingsGenerator( array $vars ): LocalSettingsGenerator {
		$vars += [
			'_Extensions' => [],
			'_Skins' => []
		];

		$db = $this->createMock( DatabaseInstaller::class );
		$db->method( 'getGlobalNames' )
			->willReturn( [
				'wgDBserver',
				'wgDBname',
				'wgDBuser',
				'wgDBpassword',
				'wgDBssl',
				'wgDBprefix',
				'wgDBTableOptions',
			] );

		$installer = $this->createMock( Installer::class );
		$installer->method( 'getVar' )
			->willReturnCallback( static fn ( string $name ) => $vars[$name] ?? '' );

		$installer->method( 'getDBInstaller' )
			->willReturn( $db );

		return new LocalSettingsGenerator( $installer );
	}

	// T372569, T355013
	public function testShouldEscapeUserInput(): void {
		$generator = $this->getLocalSettingsGenerator( [
			'wgSitename' => "Sitename with 'apostrophe",
			'wgDBpassword' => 'dollar$'
		] );

		$settings = $generator->getText();

		$this->assertStringContainsString(
			'$wgSitename = "Sitename with \'apostrophe";',
			$settings
		);
		$this->assertStringContainsString(
			'$wgDBpassword = "dollar\$"',
			$settings
		);
	}
}
