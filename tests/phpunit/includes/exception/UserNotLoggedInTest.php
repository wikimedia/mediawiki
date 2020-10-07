<?php

/**
 * @covers UserNotLoggedIn
 * @author Addshore
 */
class UserNotLoggedInTest extends MediaWikiIntegrationTestCase {

	public function testConstruction() {
		$e = new UserNotLoggedIn();
		$this->assertEquals( 'exception-nologin', $e->title );
		$this->assertEquals( 'exception-nologin-text', $e->msg );
		$this->assertEquals( [], $e->params );
	}

}
