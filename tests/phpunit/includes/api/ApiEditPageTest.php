<?php

/**
 * Tests for MediaWiki api.php?action=edit.
 *
 * @author Daniel Kinzler
 *
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

	/**
	 * Used as a dependency by other tests, simply a callback
	 * to the parent test which cannot be used as a dependency
	 * in the child class.
	 */
	function testLoginSysop() {
		return ApiTestCase::testLoginSysop();
	}

	/**
	 * @depends testLoginSysop
	 */
	function testEdit( $session ) {
		$name = 'ApiEditPageTest_testEdit';

		// -----------------------------------------------------------------------

		$apiResult = $this->doApiRequestWithToken( array(
				'action' => 'edit',
				'title' => $name,
				'text' => 'some text', ),
			$session,
			self::$users['sysop']->user );
		$apiResult = $apiResult[0];

		# Validate API result data
		$this->assertArrayHasKey( 'edit', $apiResult );
		$this->assertArrayHasKey( 'result', $apiResult['edit'] );
		$this->assertEquals( 'Success', $apiResult['edit']['result'] );

		$this->assertArrayHasKey( 'new', $apiResult['edit'] );
		$this->assertArrayNotHasKey( 'nochange', $apiResult['edit'] );

		$this->assertArrayHasKey( 'pageid', $apiResult['edit'] );

		// -----------------------------------------------------------------------
		$data = $this->doApiRequestWithToken( array(
				'action' => 'edit',
				'title' => $name,
				'text' => 'some text', ),
			$session,
			self::$users['sysop']->user );

		$this->assertEquals( 'Success', $data[0]['edit']['result'] );

		$this->assertArrayNotHasKey( 'new', $data[0]['edit'] );
		$this->assertArrayHasKey( 'nochange', $data[0]['edit'] );

		// -----------------------------------------------------------------------
		$data = $this->doApiRequestWithToken( array(
				'action' => 'edit',
				'title' => $name,
				'text' => 'different text', ),
			$session,
			self::$users['sysop']->user );

		$this->assertEquals( 'Success', $data[0]['edit']['result'] );

		$this->assertArrayNotHasKey( 'new', $data[0]['edit'] );
		$this->assertArrayNotHasKey( 'nochange', $data[0]['edit'] );

		$this->assertArrayHasKey( 'oldrevid', $data[0]['edit'] );
		$this->assertArrayHasKey( 'newrevid', $data[0]['edit'] );
		$this->assertTrue( $data[0]['edit']['newrevid'] !== $data[0]['edit']['oldrevid'], "revision id should change after edit" );
	}

	/**
	 * @depends testLoginSysop
	 */
	function testEditAppend() {
		$this->markTestIncomplete( "not yet implemented" );
	}

	/**
	 * @depends testLoginSysop
	 */
	function testEditSection() {
		$this->markTestIncomplete( "not yet implemented" );
	}

	/**
	 * @depends testLoginSysop
	 */
	function testUndo() {
		$this->markTestIncomplete( "not yet implemented" );
	}

	/**
	 * @depends testLoginSysop
	 */
	function testEditNonText() {
		$this->markTestIncomplete( "not yet implemented" );
	}
}
