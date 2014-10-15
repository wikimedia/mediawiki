<?php

/**
 * @group Database
 * @group RequestContext
 */
class RequestContextTest extends MediaWikiTestCase {

	/**
	 * Test the relationship between title and wikipage in RequestContext
	 * @covers RequestContext::getWikiPage
	 * @covers RequestContext::getTitle
	 */
	public function testWikiPageTitle() {
		$context = new RequestContext();

		$curTitle = Title::newFromText( "A" );
		$context->setTitle( $curTitle );
		$this->assertTrue( $curTitle->equals( $context->getWikiPage()->getTitle() ),
			"When a title is first set WikiPage should be created on-demand for that title." );

		$curTitle = Title::newFromText( "B" );
		$context->setWikiPage( WikiPage::factory( $curTitle ) );
		$this->assertTrue( $curTitle->equals( $context->getTitle() ),
			"Title must be updated when a new WikiPage is provided." );

		$curTitle = Title::newFromText( "C" );
		$context->setTitle( $curTitle );
		$this->assertTrue(
			$curTitle->equals( $context->getWikiPage()->getTitle() ),
			"When a title is updated the WikiPage should be purged "
				. "and recreated on-demand with the new title."
		);
	}

	/**
	 * @covers RequestContext::importScopedSession
	 */
	public function testImportScopedSession() {
		$context = RequestContext::getMain();

		$oInfo = $context->exportSession();
		$this->assertEquals( '127.0.0.1', $oInfo['ip'], "Correct initial IP address." );
		$this->assertEquals( 0, $oInfo['userId'], "Correct initial user ID." );

		$user = User::newFromName( 'UnitTestContextUser' );
		$user->addToDatabase();

		$sinfo = array(
			'sessionId' => 'd612ee607c87e749ef14da4983a702cd',
			'userId' => $user->getId(),
			'ip' => '192.0.2.0',
			'headers' => array(
				'USER-AGENT' => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:18.0) Gecko/20100101 Firefox/18.0'
			)
		);
		// importScopedSession() sets these variables
		$this->setMwGlobals( array(
			'wgUser' => new User,
			'wgRequest' => new FauxRequest,
		) );
		$sc = RequestContext::importScopedSession( $sinfo ); // load new context

		$info = $context->exportSession();
		$this->assertEquals( $sinfo['ip'], $info['ip'], "Correct IP address." );
		$this->assertEquals( $sinfo['headers'], $info['headers'], "Correct headers." );
		$this->assertEquals( $sinfo['sessionId'], $info['sessionId'], "Correct session ID." );
		$this->assertEquals( $sinfo['userId'], $info['userId'], "Correct user ID." );
		$this->assertEquals(
			$sinfo['ip'],
			$context->getRequest()->getIP(),
			"Correct context IP address."
		);
		$this->assertEquals(
			$sinfo['headers'],
			$context->getRequest()->getAllHeaders(),
			"Correct context headers."
		);
		$this->assertEquals( $sinfo['sessionId'], session_id(), "Correct context session ID." );
		$this->assertEquals( true, $context->getUser()->isLoggedIn(), "Correct context user." );
		$this->assertEquals( $sinfo['userId'], $context->getUser()->getId(), "Correct context user ID." );
		$this->assertEquals(
			'UnitTestContextUser',
			$context->getUser()->getName(),
			"Correct context user name."
		);

		unset( $sc ); // restore previous context

		$info = $context->exportSession();
		$this->assertEquals( $oInfo['ip'], $info['ip'], "Correct restored IP address." );
		$this->assertEquals( $oInfo['headers'], $info['headers'], "Correct restored headers." );
		$this->assertEquals( $oInfo['sessionId'], $info['sessionId'], "Correct restored session ID." );
		$this->assertEquals( $oInfo['userId'], $info['userId'], "Correct restored user ID." );
	}
}
