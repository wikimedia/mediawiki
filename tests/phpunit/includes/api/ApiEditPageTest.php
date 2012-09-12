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

	public function setup() {
		global $wgExtraNamespaces, $wgNamespaceContentModels, $wgContentHandlers, $wgContLang;

		parent::setup();

		$wgExtraNamespaces[ 12312 ] = 'Dummy';
		$wgExtraNamespaces[ 12313 ] = 'Dummy_talk';

		$wgNamespaceContentModels[ 12312 ] = "testing";
		$wgContentHandlers[ "testing" ] = 'DummyContentHandlerForTesting';

		MWNamespace::getCanonicalNamespaces( true ); # reset namespace cache
		$wgContLang->resetNamespaces(); # reset namespace cache

		$this->doLogin();
	}

	public function teardown() {
		global $wgExtraNamespaces, $wgNamespaceContentModels, $wgContentHandlers, $wgContLang;

		unset( $wgExtraNamespaces[ 12312 ] );
		unset( $wgExtraNamespaces[ 12313 ] );

		unset( $wgNamespaceContentModels[ 12312 ] );
		unset( $wgContentHandlers[ "testing" ] );

		MWNamespace::getCanonicalNamespaces( true ); # reset namespace cache
		$wgContLang->resetNamespaces(); # reset namespace cache

		parent::teardown();
	}

	function testEdit( ) {
		$name = 'ApiEditPageTest_testEdit';

		// -- test new page --------------------------------------------
		$apiResult = $this->doApiRequestWithToken( array(
				'action' => 'edit',
				'title' => $name,
				'text' => 'some text', ) );
		$apiResult = $apiResult[0];

		// Validate API result data
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

	function testNonTextEdit( ) {
		$name = 'Dummy:ApiEditPageTest_testNonTextEdit';
		$data = serialize( 'some bla bla text' );

		// -- test new page --------------------------------------------
		$apiResult = $this->doApiRequestWithToken( array(
			'action' => 'edit',
			'title' => $name,
			'text' => $data, ) );
		$apiResult = $apiResult[0];

		// Validate API result data
		$this->assertArrayHasKey( 'edit', $apiResult );
		$this->assertArrayHasKey( 'result', $apiResult['edit'] );
		$this->assertEquals( 'Success', $apiResult['edit']['result'] );

		$this->assertArrayHasKey( 'new', $apiResult['edit'] );
		$this->assertArrayNotHasKey( 'nochange', $apiResult['edit'] );

		$this->assertArrayHasKey( 'pageid', $apiResult['edit'] );

		// validate resulting revision
		$page = WikiPage::factory( Title::newFromText( $name ) );
		$this->assertEquals( "testing", $page->getContentModel() );
		$this->assertEquals( $data, $page->getContent()->serialize() );
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
