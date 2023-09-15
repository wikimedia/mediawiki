<?php

use MediaWiki\Request\DerivativeRequest;
use MediaWiki\Request\WebRequest;

/**
 * @covers MediaWiki\Request\DerivativeRequest
 */
class DerivativeRequestTest extends MediaWikiUnitTestCase {

	public function testSetIp() {
		$original = new WebRequest();
		$original->setIP( '1.2.3.4' );
		$derivative = new DerivativeRequest( $original, [] );

		$this->assertEquals( '1.2.3.4', $derivative->getIP() );

		$derivative->setIP( '5.6.7.8' );

		$this->assertEquals( '5.6.7.8', $derivative->getIP() );
		$this->assertEquals( '1.2.3.4', $original->getIP() );
	}

}
