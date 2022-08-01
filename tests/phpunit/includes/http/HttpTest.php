<?php

use MediaWiki\MainConfigNames;

/**
 * @covers Http
 * @group Http
 * @group small
 */
class HttpTest extends MediaWikiIntegrationTestCase {

	/**
	 * @covers Http::getProxy
	 */
	public function testGetProxy() {
		$this->hideDeprecated( 'Http::getProxy' );

		$this->overrideConfigValue( MainConfigNames::HTTPProxy, false );
		$this->assertSame(
			'',
			Http::getProxy(),
			'default setting'
		);

		$this->overrideConfigValue( MainConfigNames::HTTPProxy, 'proxy.domain.tld' );
		$this->assertEquals(
			'proxy.domain.tld',
			Http::getProxy()
		);
	}

}
