<?php

namespace MediaWiki\Tests\Rest;

use MediaWiki\Rest\ParsoidRenderId;
use MediaWikiUnitTestCase;

class ParsoidRenderIdTest extends MediaWikiUnitTestCase {

	/**
	 * @covers \MediaWiki\Rest\ParsoidRenderId
	 */
	public function testConstruction() {
		$renderID = new ParsoidRenderId( 1, '123-abc' );
		$this->assertSame( 1, $renderID->getRevisionID() );
		$this->assertEquals( '123-abc', $renderID->getTId() );
		$this->assertEquals( '1/123-abc', $renderID->__toString() );
	}

	/**
	 * @covers \MediaWiki\Rest\ParsoidRenderId
	 */
	public function testRoundTrip() {
		$renderID = new ParsoidRenderId( 1, '123-abc' );
		$stringRenderID = $renderID->__toString();
		$backToRenderID = $renderID::newFromString( $stringRenderID );
		$this->assertSame( $stringRenderID, $backToRenderID->__toString() );
	}
}
