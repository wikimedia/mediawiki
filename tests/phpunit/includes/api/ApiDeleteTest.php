<?php

/**
 * Tests for MediaWiki api.php?action=delete.
 *
 * @author Yifei He
 *
 * @group API
 * @group Database
 * @group medium
 *
 * @covers ApiDelete
 */
class ApiDeleteTest extends ApiTestCase {

	protected function setUp() {
		global $wgExtraNamespaces, $wgNamespaceContentModels, $wgContentHandlers, $wgContLang;

		parent::setUp();

		$this->setMwGlobals( [
			'wgExtraNamespaces' => $wgExtraNamespaces,
			'wgNamespaceContentModels' => $wgNamespaceContentModels,
			'wgContentHandlers' => $wgContentHandlers,
			'wgContLang' => $wgContLang,
		] );

		MWNamespace::clearCaches();
		$wgContLang->resetNamespaces(); # reset namespace cache

		$this->doLogin();
	}

	protected function tearDown() {
		global $wgContLang;

		MWNamespace::clearCaches();
		$wgContLang->resetNamespaces(); # reset namespace cache

		parent::tearDown();
	}

	public function testDelete() {
		$name = 'Help:ApiDeleteTest_testDelete'; // assume Help namespace to default to wikitext

		// -- test non-existing page --------------------------------------------
		try {
			$this->doApiRequestWithToken( [
				'action' => 'delete',
				'title' => $name,
			] );
			$this->fail( "Should have raised an ApiUsageException" );
		} catch ( ApiUsageException $e ) {
			$this->assertTrue( self::apiExceptionHasCode( $e, 'missingtitle' ) );
		}

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'text' => 'some text',
		] );

		// -- test page --------------------------------------------
		$apiResult = $this->doApiRequestWithToken( [
			'action' => 'delete',
			'title' => $name,
		] );
		$apiResult = $apiResult[0];

		// Validate API result data
		$this->assertArrayHasKey( 'delete', $apiResult );
		$this->assertArrayHasKey( 'title', $apiResult['delete'] );
		$this->assertEquals( Title::newFromText( $name ), $apiResult['delete']['title'] );
		$this->assertArrayHasKey( 'logid', $apiResult['delete'] );

		// -- validate -----------------------------------------------------
		$page = new WikiPage( Title::newFromText( $name ) );
		$content = $page->getContent();
		$this->assertNull( $content, 'Page should have been deleted' );
	}
}
