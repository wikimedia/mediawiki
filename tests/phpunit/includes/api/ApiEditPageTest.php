<?php

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

		$wgExtraNamespaces[12312] = 'Dummy';
		$wgExtraNamespaces[12313] = 'Dummy_talk';
		$wgExtraNamespaces[12314] = 'DummyNonText';
		$wgExtraNamespaces[12315] = 'DummyNonText_talk';

		$wgNamespaceContentModels[12312] = "testing";
		$wgNamespaceContentModels[12314] = "testing-nontext";

		$wgContentHandlers["testing"] = 'DummyContentHandlerForTesting';
		$wgContentHandlers["testing-nontext"] = 'DummyNonTextContentHandler';

		MWNamespace::getCanonicalNamespaces( true ); # reset namespace cache
		$wgContLang->resetNamespaces(); # reset namespace cache

		$this->doLogin();
	}

	protected function tearDown() {
		MWNamespace::getCanonicalNamespaces( true ); # reset namespace cache
		parent::tearDown();
	}

	public function testEdit() {
		$name = 'Help:ApiEditPageTest_testEdit'; // assume Help namespace to default to wikitext

		// -- test new page --------------------------------------------
		$apiResult = $this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'text' => 'some text',
		] );
		$apiResult = $apiResult[0];

		// Validate API result data
		$this->assertArrayHasKey( 'edit', $apiResult );
		$this->assertArrayHasKey( 'result', $apiResult['edit'] );
		$this->assertEquals( 'Success', $apiResult['edit']['result'] );

		$this->assertArrayHasKey( 'new', $apiResult['edit'] );
		$this->assertArrayNotHasKey( 'nochange', $apiResult['edit'] );

		$this->assertArrayHasKey( 'pageid', $apiResult['edit'] );

		// -- test existing page, no change ----------------------------
		$data = $this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'text' => 'some text',
		] );

		$this->assertEquals( 'Success', $data[0]['edit']['result'] );

		$this->assertArrayNotHasKey( 'new', $data[0]['edit'] );
		$this->assertArrayHasKey( 'nochange', $data[0]['edit'] );

		// -- test existing page, with change --------------------------
		$data = $this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'text' => 'different text'
		] );

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

	/**
	 * @return array
	 */
	public static function provideEditAppend() {
		return [
			[ # 0: append
				'foo', 'append', 'bar', "foobar"
			],
			[ # 1: prepend
				'foo', 'prepend', 'bar', "barfoo"
			],
			[ # 2: append to empty page
				'', 'append', 'foo', "foo"
			],
			[ # 3: prepend to empty page
				'', 'prepend', 'foo', "foo"
			],
			[ # 4: append to non-existing page
				null, 'append', 'foo', "foo"
			],
			[ # 5: prepend to non-existing page
				null, 'prepend', 'foo', "foo"
			],
		];
	}

	/**
	 * @dataProvider provideEditAppend
	 */
	public function testEditAppend( $text, $op, $append, $expected ) {
		static $count = 0;
		$count++;

		// assume NS_HELP defaults to wikitext
		$name = "Help:ApiEditPageTest_testEditAppend_$count";

		// -- create page (or not) -----------------------------------------
		if ( $text !== null ) {
			list( $re ) = $this->doApiRequestWithToken( [
				'action' => 'edit',
				'title' => $name,
				'text' => $text, ] );

			$this->assertEquals( 'Success', $re['edit']['result'] ); // sanity
		}

		// -- try append/prepend --------------------------------------------
		list( $re ) = $this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			$op . 'text' => $append, ] );

		$this->assertEquals( 'Success', $re['edit']['result'] );

		// -- validate -----------------------------------------------------
		$page = new WikiPage( Title::newFromText( $name ) );
		$content = $page->getContent();
		$this->assertNotNull( $content, 'Page should have been created' );

		$text = $content->getNativeData();

		$this->assertEquals( $expected, $text );
	}

	/**
	 * Test editing of sections
	 */
	public function testEditSection() {
		$name = 'Help:ApiEditPageTest_testEditSection';
		$page = WikiPage::factory( Title::newFromText( $name ) );
		$text = "==section 1==\ncontent 1\n==section 2==\ncontent2";
		// Preload the page with some text
		$page->doEditContent( ContentHandler::makeContent( $text, $page->getTitle() ), 'summary' );

		list( $re ) = $this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'section' => '1',
			'text' => "==section 1==\nnew content 1",
		] );
		$this->assertEquals( 'Success', $re['edit']['result'] );
		$newtext = WikiPage::factory( Title::newFromText( $name ) )
			->getContent( Revision::RAW )
			->getNativeData();
		$this->assertEquals( "==section 1==\nnew content 1\n\n==section 2==\ncontent2", $newtext );

		// Test that we raise a 'nosuchsection' error
		try {
			$this->doApiRequestWithToken( [
				'action' => 'edit',
				'title' => $name,
				'section' => '9999',
				'text' => 'text',
			] );
			$this->fail( "Should have raised an ApiUsageException" );
		} catch ( ApiUsageException $e ) {
			$this->assertTrue( self::apiExceptionHasCode( $e, 'nosuchsection' ) );
		}
	}

	/**
	 * Test action=edit&section=new
	 * Run it twice so we test adding a new section on a
	 * page that doesn't exist (T54830) and one that
	 * does exist
	 */
	public function testEditNewSection() {
		$name = 'Help:ApiEditPageTest_testEditNewSection';

		// Test on a page that does not already exist
		$this->assertFalse( Title::newFromText( $name )->exists() );
		list( $re ) = $this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'section' => 'new',
			'text' => 'test',
			'summary' => 'header',
		] );

		$this->assertEquals( 'Success', $re['edit']['result'] );
		// Check the page text is correct
		$text = WikiPage::factory( Title::newFromText( $name ) )
			->getContent( Revision::RAW )
			->getNativeData();
		$this->assertEquals( "== header ==\n\ntest", $text );

		// Now on one that does
		$this->assertTrue( Title::newFromText( $name )->exists() );
		list( $re2 ) = $this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'section' => 'new',
			'text' => 'test',
			'summary' => 'header',
		] );

		$this->assertEquals( 'Success', $re2['edit']['result'] );
		$text = WikiPage::factory( Title::newFromText( $name ) )
			->getContent( Revision::RAW )
			->getNativeData();
		$this->assertEquals( "== header ==\n\ntest\n\n== header ==\n\ntest", $text );
	}

	/**
	 * Ensure we can edit through a redirect, if adding a section
	 */
	public function testEdit_redirect() {
		static $count = 0;
		$count++;

		// assume NS_HELP defaults to wikitext
		$name = "Help:ApiEditPageTest_testEdit_redirect_$count";
		$title = Title::newFromText( $name );
		$page = WikiPage::factory( $title );

		$rname = "Help:ApiEditPageTest_testEdit_redirect_r$count";
		$rtitle = Title::newFromText( $rname );
		$rpage = WikiPage::factory( $rtitle );

		// base edit for content
		$page->doEditContent( new WikitextContent( "Foo" ),
			"testing 1", EDIT_NEW, false, self::$users['sysop']->getUser() );
		$this->forceRevisionDate( $page, '20120101000000' );
		$baseTime = $page->getRevision()->getTimestamp();

		// base edit for redirect
		$rpage->doEditContent( new WikitextContent( "#REDIRECT [[$name]]" ),
			"testing 1", EDIT_NEW, false, self::$users['sysop']->getUser() );
		$this->forceRevisionDate( $rpage, '20120101000000' );

		// conflicting edit to redirect
		$rpage->doEditContent( new WikitextContent( "#REDIRECT [[$name]]\n\n[[Category:Test]]" ),
			"testing 2", EDIT_UPDATE, $page->getLatest(), self::$users['uploader']->getUser() );
		$this->forceRevisionDate( $rpage, '20120101020202' );

		// try to save edit, following the redirect
		list( $re, , ) = $this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $rname,
			'text' => 'nix bar!',
			'basetimestamp' => $baseTime,
			'section' => 'new',
			'redirect' => true,
		], null, self::$users['sysop']->getUser() );

		$this->assertEquals( 'Success', $re['edit']['result'],
			"no problems expected when following redirect" );
	}

	/**
	 * Ensure we cannot edit through a redirect, if attempting to overwrite content
	 */
	public function testEdit_redirectText() {
		static $count = 0;
		$count++;

		// assume NS_HELP defaults to wikitext
		$name = "Help:ApiEditPageTest_testEdit_redirectText_$count";
		$title = Title::newFromText( $name );
		$page = WikiPage::factory( $title );

		$rname = "Help:ApiEditPageTest_testEdit_redirectText_r$count";
		$rtitle = Title::newFromText( $rname );
		$rpage = WikiPage::factory( $rtitle );

		// base edit for content
		$page->doEditContent( new WikitextContent( "Foo" ),
			"testing 1", EDIT_NEW, false, self::$users['sysop']->getUser() );
		$this->forceRevisionDate( $page, '20120101000000' );
		$baseTime = $page->getRevision()->getTimestamp();

		// base edit for redirect
		$rpage->doEditContent( new WikitextContent( "#REDIRECT [[$name]]" ),
			"testing 1", EDIT_NEW, false, self::$users['sysop']->getUser() );
		$this->forceRevisionDate( $rpage, '20120101000000' );

		// conflicting edit to redirect
		$rpage->doEditContent( new WikitextContent( "#REDIRECT [[$name]]\n\n[[Category:Test]]" ),
			"testing 2", EDIT_UPDATE, $page->getLatest(), self::$users['uploader']->getUser() );
		$this->forceRevisionDate( $rpage, '20120101020202' );

		// try to save edit, following the redirect but without creating a section
		try {
			$this->doApiRequestWithToken( [
				'action' => 'edit',
				'title' => $rname,
				'text' => 'nix bar!',
				'basetimestamp' => $baseTime,
				'redirect' => true,
			], null, self::$users['sysop']->getUser() );

			$this->fail( 'redirect-appendonly error expected' );
		} catch ( ApiUsageException $ex ) {
			$this->assertTrue( self::apiExceptionHasCode( $ex, 'redirect-appendonly' ) );
		}
	}

	public function testEditConflict() {
		static $count = 0;
		$count++;

		// assume NS_HELP defaults to wikitext
		$name = "Help:ApiEditPageTest_testEditConflict_$count";
		$title = Title::newFromText( $name );

		$page = WikiPage::factory( $title );

		// base edit
		$page->doEditContent( new WikitextContent( "Foo" ),
			"testing 1", EDIT_NEW, false, self::$users['sysop']->getUser() );
		$this->forceRevisionDate( $page, '20120101000000' );
		$baseTime = $page->getRevision()->getTimestamp();

		// conflicting edit
		$page->doEditContent( new WikitextContent( "Foo bar" ),
			"testing 2", EDIT_UPDATE, $page->getLatest(), self::$users['uploader']->getUser() );
		$this->forceRevisionDate( $page, '20120101020202' );

		// try to save edit, expect conflict
		try {
			$this->doApiRequestWithToken( [
				'action' => 'edit',
				'title' => $name,
				'text' => 'nix bar!',
				'basetimestamp' => $baseTime,
			], null, self::$users['sysop']->getUser() );

			$this->fail( 'edit conflict expected' );
		} catch ( ApiUsageException $ex ) {
			$this->assertTrue( self::apiExceptionHasCode( $ex, 'editconflict' ) );
		}
	}

	/**
	 * Ensure that editing using section=new will prevent simple conflicts
	 */
	public function testEditConflict_newSection() {
		static $count = 0;
		$count++;

		// assume NS_HELP defaults to wikitext
		$name = "Help:ApiEditPageTest_testEditConflict_newSection_$count";
		$title = Title::newFromText( $name );

		$page = WikiPage::factory( $title );

		// base edit
		$page->doEditContent( new WikitextContent( "Foo" ),
			"testing 1", EDIT_NEW, false, self::$users['sysop']->getUser() );
		$this->forceRevisionDate( $page, '20120101000000' );
		$baseTime = $page->getRevision()->getTimestamp();

		// conflicting edit
		$page->doEditContent( new WikitextContent( "Foo bar" ),
			"testing 2", EDIT_UPDATE, $page->getLatest(), self::$users['uploader']->getUser() );
		$this->forceRevisionDate( $page, '20120101020202' );

		// try to save edit, expect no conflict
		list( $re, , ) = $this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'text' => 'nix bar!',
			'basetimestamp' => $baseTime,
			'section' => 'new',
		], null, self::$users['sysop']->getUser() );

		$this->assertEquals( 'Success', $re['edit']['result'],
			"no edit conflict expected here" );
	}

	public function testEditConflict_bug41990() {
		static $count = 0;
		$count++;

		/*
		* T43990: if the target page has a newer revision than the redirect, then editing the
		* redirect while specifying 'redirect' and *not* specifying 'basetimestamp' erroneously
		* caused an edit conflict to be detected.
		*/

		// assume NS_HELP defaults to wikitext
		$name = "Help:ApiEditPageTest_testEditConflict_redirect_bug41990_$count";
		$title = Title::newFromText( $name );
		$page = WikiPage::factory( $title );

		$rname = "Help:ApiEditPageTest_testEditConflict_redirect_bug41990_r$count";
		$rtitle = Title::newFromText( $rname );
		$rpage = WikiPage::factory( $rtitle );

		// base edit for content
		$page->doEditContent( new WikitextContent( "Foo" ),
			"testing 1", EDIT_NEW, false, self::$users['sysop']->getUser() );
		$this->forceRevisionDate( $page, '20120101000000' );

		// base edit for redirect
		$rpage->doEditContent( new WikitextContent( "#REDIRECT [[$name]]" ),
			"testing 1", EDIT_NEW, false, self::$users['sysop']->getUser() );
		$this->forceRevisionDate( $rpage, '20120101000000' );

		// new edit to content
		$page->doEditContent( new WikitextContent( "Foo bar" ),
			"testing 2", EDIT_UPDATE, $page->getLatest(), self::$users['uploader']->getUser() );
		$this->forceRevisionDate( $rpage, '20120101020202' );

		// try to save edit; should work, following the redirect.
		list( $re, , ) = $this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $rname,
			'text' => 'nix bar!',
			'section' => 'new',
			'redirect' => true,
		], null, self::$users['sysop']->getUser() );

		$this->assertEquals( 'Success', $re['edit']['result'],
			"no edit conflict expected here" );
	}

	/**
	 * @param WikiPage $page
	 * @param string|int $timestamp
	 */
	protected function forceRevisionDate( WikiPage $page, $timestamp ) {
		$dbw = wfGetDB( DB_MASTER );

		$dbw->update( 'revision',
			[ 'rev_timestamp' => $dbw->timestamp( $timestamp ) ],
			[ 'rev_id' => $page->getLatest() ] );

		$page->clear();
	}

	public function testCheckDirectApiEditingDisallowed_forNonTextContent() {
		$this->setExpectedException(
			'ApiUsageException',
			'Direct editing via API is not supported for content model ' .
				'testing used by Dummy:ApiEditPageTest_nonTextPageEdit'
		);

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => 'Dummy:ApiEditPageTest_nonTextPageEdit',
			'text' => '{"animals":["kittens!"]}'
		] );
	}

	public function testSupportsDirectApiEditing_withContentHandlerOverride() {
		$name = 'DummyNonText:ApiEditPageTest_testNonTextEdit';
		$data = serialize( 'some bla bla text' );

		$result = $this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'text' => $data,
		] );

		$apiResult = $result[0];

		// Validate API result data
		$this->assertArrayHasKey( 'edit', $apiResult );
		$this->assertArrayHasKey( 'result', $apiResult['edit'] );
		$this->assertEquals( 'Success', $apiResult['edit']['result'] );

		$this->assertArrayHasKey( 'new', $apiResult['edit'] );
		$this->assertArrayNotHasKey( 'nochange', $apiResult['edit'] );

		$this->assertArrayHasKey( 'pageid', $apiResult['edit'] );

		// validate resulting revision
		$page = WikiPage::factory( Title::newFromText( $name ) );
		$this->assertEquals( "testing-nontext", $page->getContentModel() );
		$this->assertEquals( $data, $page->getContent()->serialize() );
	}

	/**
	 * This test verifies that after changing the content model
	 * of a page, undoing that edit via the API will also
	 * undo the content model change.
	 */
	public function testUndoAfterContentModelChange() {
		$name = 'Help:' . __FUNCTION__;
		$uploader = self::$users['uploader']->getUser();
		$sysop = self::$users['sysop']->getUser();
		$apiResult = $this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'text' => 'some text',
		], null, $sysop )[0];

		// Check success
		$this->assertArrayHasKey( 'edit', $apiResult );
		$this->assertArrayHasKey( 'result', $apiResult['edit'] );
		$this->assertEquals( 'Success', $apiResult['edit']['result'] );
		$this->assertArrayHasKey( 'contentmodel', $apiResult['edit'] );
		// Content model is wikitext
		$this->assertEquals( 'wikitext', $apiResult['edit']['contentmodel'] );

		// Convert the page to JSON
		$apiResult = $this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'text' => '{}',
			'contentmodel' => 'json',
		], null, $uploader )[0];

		// Check success
		$this->assertArrayHasKey( 'edit', $apiResult );
		$this->assertArrayHasKey( 'result', $apiResult['edit'] );
		$this->assertEquals( 'Success', $apiResult['edit']['result'] );
		$this->assertArrayHasKey( 'contentmodel', $apiResult['edit'] );
		$this->assertEquals( 'json', $apiResult['edit']['contentmodel'] );

		$apiResult = $this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'undo' => $apiResult['edit']['newrevid']
		], null, $sysop )[0];

		// Check success
		$this->assertArrayHasKey( 'edit', $apiResult );
		$this->assertArrayHasKey( 'result', $apiResult['edit'] );
		$this->assertEquals( 'Success', $apiResult['edit']['result'] );
		$this->assertArrayHasKey( 'contentmodel', $apiResult['edit'] );
		// Check that the contentmodel is back to wikitext now.
		$this->assertEquals( 'wikitext', $apiResult['edit']['contentmodel'] );
	}
}
