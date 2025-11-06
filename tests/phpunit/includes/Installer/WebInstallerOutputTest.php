<?php
namespace MediaWiki\Tests\Installer;

use MediaWiki\Installer\WebInstaller;
use MediaWiki\Installer\WebInstallerOutput;
use MediaWikiIntegrationTestCase;

class WebInstallerOutputTest extends MediaWikiIntegrationTestCase {
	/**
	 * @covers \MediaWiki\Installer\WebInstallerOutput::getCSS
	 */
	public function testGetCSS() {
		$_SERVER['DOCUMENT_ROOT'] = __DIR__ . '../../../';
		$installer = $this->createMock( WebInstaller::class );
		$out = new WebInstallerOutput( $installer );
		$css = $out->getCSS();
		$this->assertStringContainsString(
			'#mw-panel {',
			$css,
			'CSS for installer can be generated'
		);
	}
}
