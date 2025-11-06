<?php
namespace MediaWiki\Tests\Installer;

use MediaWiki\Installer\WebInstaller;
use MediaWiki\Request\FauxRequest;
use MediaWikiIntegrationTestCase;

class WebInstallerTest extends MediaWikiIntegrationTestCase {
	/**
	 * @covers \MediaWiki\Installer\WebInstaller::getAcceptLanguage
	 * @dataProvider provideGetAcceptLanguage
	 */
	public function testGetAcceptLanguage( $expected, $acceptLanguage ) {
		$request = new FauxRequest();
		$request->setHeader( 'Accept-Language', $acceptLanguage );
		$webInstaller = new WebInstaller( $request );
		$this->assertSame(
			$expected,
			$webInstaller->getAcceptLanguage()
		);
	}

	public static function provideGetAcceptLanguage() {
		return [
			[ 'de-ch', 'de-LI,de-CH;q=0.8,de;q=0.5,en;q=0.3' ],
			// T189193: This should be 'de-de' or 'de'.
			[ 'de-at', 'de-DE,de-AT;q=0.8,de;q=0.5,en;q=0.3' ],
			// T187866: 'no' gets accepted.
			[ 'no', 'no,nl;q=0.5' ],
		];
	}
}
