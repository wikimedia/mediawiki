<?php

/**
 * @covers UserNotLoggedIn
 * @author Adam Shorland
 */
class UserNotLoggedInTest extends MediaWikiTestCase {

	public function testConstruction() {
		$e = new UserNotLoggedIn();
		$this->assertEquals( 'exception-nologin', $e->title );
		$this->assertEquals( 'exception-nologin-text', $e->msg );
		$this->assertEquals( array(), $e->params );
	}

}
