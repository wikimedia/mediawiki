<?php

namespace MediaWiki\Session;

use MediaWikiTestCase;

/**
 * @group Session
 * @covers MediaWiki\Session\SessionId
 */
class SessionIdTest extends MediaWikiTestCase {

	public function testEverything() {
		$id = new SessionId( 'foo' );
		$this->assertSame( 'foo', $id->getId() );
		$this->assertSame( 'foo', (string)$id );
		$id->setId( 'bar' );
		$this->assertSame( 'bar', $id->getId() );
		$this->assertSame( 'bar', (string)$id );
	}

}
