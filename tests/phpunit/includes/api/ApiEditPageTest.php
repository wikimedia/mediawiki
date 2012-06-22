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

	function testEdit( ) {
		$name = 'ApiEditPageTest_testEdit';

		// -- test new page --------------------------------------------
		$apiResult = $this->doApiRequestWithToken( array(
				'action' => 'edit',
				'title' => $name,
				'text' => 'some text', ) );
		$apiResult = $apiResult[0];

		# Validate API result data
		$this->assertArrayHasKey( 'edit', $apiResult );
		$this->assertArrayHasKey( 'result', $apiResult['edit'] );
		$this->assertEquals( 'Success', $apiResult['edit']['result'] );

		$this->assertArrayHasKey( 'new', $apiResult['edit'] );
		$this->assertArrayNotHasKey( 'nochange', $apiResult['edit'] );

		$this->assertArrayHasKey( 'pageid', $apiResult['edit'] );

		// -- test existing page, no change ----------------------------
		$data = $this->doApiRequestWithToken( array(
				'action' => 'edit',
				'title' => $name,
				'text' => 'some text', ) );

		$this->assertEquals( 'Success', $data[0]['edit']['result'] );

		$this->assertArrayNotHasKey( 'new', $data[0]['edit'] );
		$this->assertArrayHasKey( 'nochange', $data[0]['edit'] );

		// -- test existing page, with change --------------------------
		$data = $this->doApiRequestWithToken( array(
				'action' => 'edit',
				'title' => $name,
				'text' => 'different text' ) );

		$this->assertEquals( 'Success', $data[0]['edit']['result'] );

		$this->assertArrayNotHasKey( 'new', $data[0]['edit'] );
		$this->assertArrayNotHasKey( 'nochange', $data[0]['edit'] );

		$this->assertArrayHasKey( 'oldrevid', $data[0]['edit'] );
		$this->assertArrayHasKey( 'newrevid', $data[0]['edit'] );
		$this->assertNotEquals(
			$data[0]['edit']['newrevid'],
			$data[0]['edit']['oldrevid'],
			"revision id should change after edit"
		);
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
