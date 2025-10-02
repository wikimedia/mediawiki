<?php
/**
 * Copyright © 2013 Alexandre Emsenhuber
 *
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\MainConfigNames;

/**
 * @group GlobalFunctions
 * @covers ::wfParseUrl
 */
class WfParseUrlTest extends MediaWikiIntegrationTestCase {
	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValue( MainConfigNames::UrlProtocols, [
			'//',
			'http://',
			'https://',
			'file://',
			'mailto:',
			'news:',
		] );
	}

	/**
	 * Same tests as the UrlUtils method
	 * @dataProvider UrlUtilsProviders::provideParse
	 */
	public function testWfParseUrl( $url, $parts ) {
		$this->hideDeprecated( 'wfParseUrl' );
		$this->assertEquals(
			$parts,
			wfParseUrl( $url )
		);
	}
}
