<?php

/**
 * @group Database
 */
class RequestContextTest extends MediaWikiTestCase {

	/**
	 * Test the relationship between title and wikipage in RequestContext
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
		$this->assertTrue( $curTitle->equals( $context->getWikiPage()->getTitle() ),
			"When a title is updated the WikiPage should be purged and recreated on-demand with the new title." );

	}

	public function testImportScopedSession() {
		$context = RequestContext::getMain();

		$info = $context->exportSession();
		$this->assertEquals( '127.0.0.1', $info['ip'], "Correct initial IP address." );
		$this->assertEquals( array(), $info['headers'], "Correct initial headers." );
		$this->assertEquals( '', $info['sessionId'], "Correct initial session ID." );
		$this->assertEquals( 0, $info['userId'], "Correct initial user ID." );

		$user = User::newFromName( 'UnitTestUser' );
		$user->addToDatabase();

		$sinfo = array(
			'sessionId' => 'd612ee607c87e749ef14da4983a702cd',
			'userId' => $user->getId(),
			'ip' => '172.80.0.2',
			'headers' => array( 'USER-AGENT' => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:18.0) Gecko/20100101 Firefox/18.0' )
		);
		$sc = RequestContext::importScopedSession( $sinfo ); // load new context

		$info = $context->exportSession();
		$this->assertEquals( $sinfo['ip'], $info['ip'], "Correct IP address." );
		$this->assertEquals( $sinfo['headers'], $info['headers'], "Correct headers." );
		$this->assertEquals( $sinfo['sessionId'], $info['sessionId'], "Correct session ID." );
		$this->assertEquals( $sinfo['userId'], $info['userId'], "Correct user ID." );
		$this->assertEquals( $sinfo['ip'], $context->getRequest()->getIP(), "Correct context IP address." );
		$this->assertEquals( $sinfo['headers'], $context->getRequest()->getAllHeaders(), "Correct context headers." );
		$this->assertEquals( $sinfo['sessionId'], session_id(), "Correct context session ID." );
		$this->assertEquals( $sinfo['userId'], $context->getUser()->getId(), "Correct context user ID." );
		$this->assertEquals( 'UnitTestUser', $context->getUser()->getName(), "Correct context user name." );

		unset ( $sc ); // restore previous context

		$info = $context->exportSession();
		$this->assertEquals( '127.0.0.1', $info['ip'], "Correct initial IP address." );
		$this->assertEquals( array(), $info['headers'], "Correct initial headers." );
		$this->assertEquals( '', $info['sessionId'], "Correct initial session ID." );
		$this->assertEquals( 0, $info['userId'], "Correct initial user ID." );
	}
}
