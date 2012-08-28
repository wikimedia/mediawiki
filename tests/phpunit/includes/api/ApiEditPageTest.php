<?php

/**
 * @group Editing
 * @group API
 * @group Database
 */
class ApiEditPageTest extends ApiTestCase {

	function setUp() {
		parent::setUp();
		$this->doLogin();
	}

	function getTokens() {
		return $this->getTokenList( self::$users['sysop'] );
	}

	function testEdit() {
		$name = 'ApiEditPageTest_testEdit';

		$tokenData = $this->getTokens();

		if( !isset( $tokenData[0]['query']['pages'] ) ) {
			$this->markTestIncomplete( "No edit token found" );
		}

		$keys = array_keys( $tokenData[0]['query']['pages'] );
		$key = array_pop( $keys );
		$pageinfo = $tokenData[0]['query']['pages'][$key];
		$session = $tokenData[2];

		// -----------------------------------------------------------------------

		$data = $this->doApiRequest( array(
				'action' => 'edit',
				'title' => $name,
				'text' => 'some text',
				'token' => $pageinfo['edittoken'] ),
			$session,
			false,
			self::$users['sysop']->user );

		$this->assertArrayHasKey( 'edit', $data[0] );
		$this->assertArrayHasKey( 'result', $data[0]['edit'] );
		$this->assertEquals( 'Success', $data[0]['edit']['result'] );

		$this->assertArrayHasKey( 'new', $data[0]['edit'] );
		$this->assertArrayNotHasKey( 'nochange', $data[0]['edit'] );

		$this->assertArrayHasKey( 'pageid', $data[0]['edit'] );
		$this->assertArrayHasKey( 'contentmodel', $data[0]['edit'] );
		$this->assertEquals( CONTENT_MODEL_WIKITEXT, $data[0]['edit']['contentmodel'] );

		// -----------------------------------------------------------------------
		$data = $this->doApiRequest( array(
				'action' => 'edit',
				'title' => $name,
				'text' => 'some text',
				'token' => $pageinfo['edittoken'] ),
			$session,
			false,
			self::$users['sysop']->user );

		$this->assertEquals( 'Success', $data[0]['edit']['result'] );

		$this->assertArrayNotHasKey( 'new', $data[0]['edit'] );
		$this->assertArrayHasKey( 'nochange', $data[0]['edit'] );

		// -----------------------------------------------------------------------
		$data = $this->doApiRequest( array(
				'action' => 'edit',
				'title' => $name,
				'text' => 'different text',
				'token' => $pageinfo['edittoken'] ),
			$session,
			false,
			self::$users['sysop']->user );

		$this->assertEquals( 'Success', $data[0]['edit']['result'] );

		$this->assertArrayNotHasKey( 'new', $data[0]['edit'] );
		$this->assertArrayNotHasKey( 'nochange', $data[0]['edit'] );

		$this->assertArrayHasKey( 'oldrevid', $data[0]['edit'] );
		$this->assertArrayHasKey( 'newrevid', $data[0]['edit'] );
		$this->assertTrue( $data[0]['edit']['newrevid'] !== $data[0]['edit']['oldrevid'], "revision id should change after edit" );
	}

	function testEditAppend() {
		$this->markTestIncomplete( "not yet implemented" );
	}

	function testEditSection() {
		$this->markTestIncomplete( "not yet implemented" );
	}

	function testUndo() {
		$this->markTestIncomplete( "not yet implemented" );
	}

	function testEditNonText() {
		$this->markTestIncomplete( "not yet implemented" );
	}
}
