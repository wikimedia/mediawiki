<?php

class WebInstallerOutputTest extends MediaWikiIntegrationTestCase {
	/**
	 * @covers WebInstallerOutput::getCSS
	 */
	public function testGetCSS() {
		$_SERVER['DOCUMENT_ROOT'] = __DIR__ . '../../../';
		$installer = $this->getMockBuilder( WebInstaller::class )
			->disableOriginalConstructor()
			->getMock();
		$out = new WebInstallerOutput( $installer );
		$css = $out->getCSS();
		$this->assertStringContainsString(
			'#content {',
			$css,
			'CSS for installer can be generated'
		);
	}
}
