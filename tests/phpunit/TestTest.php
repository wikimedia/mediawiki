<?php
use Wikibase\Content\DeferredCopyEntityHolder;

/**
 * Tests for MediaWiki api.php?action=edit.
 *
 * @author Daniel Kinzler
 *
 * @group API
 * @group Database
 * @group medium
 *
 * @covers ApiEditPage
 */
class ApiEditPageTest extends ApiTestCase {

	protected function setUp() {
		global $wgExtraNamespaces, $wgNamespaceContentModels, $wgContentHandlers, $wgContLang;

		parent::setUp();

		$this->setMwGlobals( [
			'wgExtraNamespaces' => $wgExtraNamespaces,
			'wgNamespaceContentModels' => $wgNamespaceContentModels,
			'wgContentHandlers' => $wgContentHandlers,
			'wgContLang' => $wgContLang,
		] );

		$wgExtraNamespaces[12314] = 'DummyNonText';
		$wgExtraNamespaces[12315] = 'DummyNonText_talk';

		$wgNamespaceContentModels[12314] = "testing-nontext";

		$wgContentHandlers["testing-nontext"] = 'DummyNonTextContentHandler';

		MWNamespace::getCanonicalNamespaces( true ); # reset namespace cache
		$wgContLang->resetNamespaces(); # reset namespace cache

		$this->doLogin();
	}

	protected function tearDown() {
		MWNamespace::getCanonicalNamespaces( true ); # reset namespace cache
		parent::tearDown();
	}

	public function testSupportsDirectApiEditing_withContentHandlerOverride() {
		$expected = 'testing-nontext';

		$name = 'DummyNonText:ApiEditPageTest_testNonTextEdit';
		$data = serialize( 'some bla bla text' );

		$result = $this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'text' => $data,
			'summary' => __METHOD__,
		] );

		$apiResult = $result[0];

		// Validate API result data
		$this->assertArrayHasKey( 'edit', $apiResult );
		$this->assertArrayHasKey( 'result', $apiResult['edit'] );
		$this->assertEquals( 'Success', $apiResult['edit']['result'] );

		$this->assertArrayHasKey( 'new', $apiResult['edit'] );
		$this->assertArrayNotHasKey( 'nochange', $apiResult['edit'] );

		$this->assertArrayHasKey( 'pageid', $apiResult['edit'] );

		$page = WikiPage::factory( Title::newFromText( $name ) );

		$this->assertEquals( "testing-nontext", $page->getContentModel() );
		$this->assertEquals( $data, $page->getContent()->serialize() );
	}

}
