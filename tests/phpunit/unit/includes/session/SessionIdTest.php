<?php

namespace MediaWiki\Tests\Session;

use MediaWiki\Session\SessionId;
use MediaWikiUnitTestCase;

/**
 * @group Session
 * @covers \MediaWiki\Session\SessionId
 */
class SessionIdTest extends MediaWikiUnitTestCase {

	public function testEverything() {
		$id = new SessionId( 'foo' );
		$this->assertSame( 'foo', $id->getId() );
		$this->assertSame( 'foo', (string)$id );
		$id->setId( 'bar' );
		$this->assertSame( 'bar', $id->getId() );
		$this->assertSame( 'bar', (string)$id );
	}

}
