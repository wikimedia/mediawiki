<?php

/**
 * @covers Http
 * @group Http
 * @group small
 */
class HttpTest extends MediaWikiTestCase {

	/**
	 * @covers Http::getProxy
	 */
	public function testGetProxy() {
		$this->hideDeprecated( 'Http::getProxy' );

		$this->setMwGlobals( 'wgHTTPProxy', false );
		$this->assertSame(
			'',
			Http::getProxy(),
			'default setting'
		);

		$this->setMwGlobals( 'wgHTTPProxy', 'proxy.domain.tld' );
		$this->assertEquals(
			'proxy.domain.tld',
			Http::getProxy()
		);
	}

}
