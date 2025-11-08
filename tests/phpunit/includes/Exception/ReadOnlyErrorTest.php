<?php

use MediaWiki\Exception\ReadOnlyError;
use MediaWiki\Tests\Unit\DummyServicesTrait;

/**
 * @covers \MediaWiki\Exception\ReadOnlyError
 * @author Addshore
 */
class ReadOnlyErrorTest extends MediaWikiIntegrationTestCase {
	use DummyServicesTrait;

	public function testConstruction() {
		$reason = 'This site is read-only for $reasons';
		$this->setService( 'ReadOnlyMode', $this->getDummyReadOnlyMode( $reason ) );
		$e = new ReadOnlyError();
		$this->assertEquals( 'readonly', $e->title );
		$this->assertEquals( 'readonlytext', $e->msg );
		$this->assertEquals( [ $reason ], $e->params );
	}

}
