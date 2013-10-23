<?php

/**
 * Tests for MediaWiki api.php?action=edit.
 *
 * @author Daniel Kinzler
 *
 * @group API
 * @group Database
 * @group medium
 */
class ApiEditPageTest extends ApiTestCase {

	public function setUp() {
		global $wgExtraNamespaces, $wgNamespaceContentModels, $wgContentHandlers, $wgContLang;

		parent::setUp();

		$wgExtraNamespaces[12312] = 'Dummy';
		$wgExtraNamespaces[12313] = 'Dummy_talk';

		$wgNamespaceContentModels[12312] = "testing";
		$wgContentHandlers["testing"] = 'DummyContentHandlerForTesting';

		MWNamespace::getCanonicalNamespaces( true ); # reset namespace cache
		$wgContLang->resetNamespaces(); # reset namespace cache

		$this->doLogin();
	}

	public function tearDown() {
		global $wgExtraNamespaces, $wgNamespaceContentModels, $wgContentHandlers, $wgContLang;

		unset( $wgExtraNamespaces[12312] );
		unset( $wgExtraNamespaces[12313] );

		unset( $wgNamespaceContentModels[12312] );
		unset( $wgContentHandlers["testing"] );

		MWNamespace::getCanonicalNamespaces( true ); # reset namespace cache
		$wgContLang->resetNamespaces(); # reset namespace cache

		parent::tearDown();
	}

	public function testEdit() {
		$name = 'Help:ApiEditPageTest_testEdit'; // assume Help namespace to default to wikitext

		// -- test new page --------------------------------------------
		$apiResult = $this->doApiRequestWithToken( array(
			'action' => 'edit',
			'title' => $name,
			'text' => 'some text',
		) );
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
			'text' => 'some text',
		) );

		$this->assertEquals( 'Success', $data[0]['edit']['result'] );

		$this->assertArrayNotHasKey( 'new', $data[0]['edit'] );
		$this->assertArrayHasKey( 'nochange', $data[0]['edit'] );

		// -- test existing page, with change --------------------------
		$data = $this->doApiRequestWithToken( array(
			'action' => 'edit',
			'title' => $name,
			'text' => 'different text'
		) );

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

	public function testNonTextEdit() {
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

	public static function provideEditAppend() {
		return array(
			array( #0: append
				'foo', 'append', 'bar', "foobar"
			),
			array( #1: prepend
				'foo', 'prepend', 'bar', "barfoo"
			),
			array( #2: append to empty page
				'', 'append', 'foo', "foo"
			),
			array( #3: prepend to empty page
				'', 'prepend', 'foo', "foo"
			),
			array( #4: append to non-existing page
				null, 'append', 'foo', "foo"
			),
			array( #5: prepend to non-existing page
				null, 'prepend', 'foo', "foo"
			),
		);
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
			if ( $text === '' ) {
				// can't create an empty page, so create it with some content
				$this->doApiRequestWithToken( array(
					'action' => 'edit',
					'title' => $name,
					'text' => '(dummy)', ) );
			}

			list( $re ) = $this->doApiRequestWithToken( array(
				'action' => 'edit',
				'title' => $name,
				'text' => $text, ) );

			$this->assertEquals( 'Success', $re['edit']['result'] ); // sanity
		}

		// -- try append/prepend --------------------------------------------
		list( $re ) = $this->doApiRequestWithToken( array(
			'action' => 'edit',
			'title' => $name,
			$op . 'text' => $append, ) );

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

		list( $re ) = $this->doApiRequestWithToken( array(
			'action' => 'edit',
			'title' => $name,
			'section' => '1',
			'text' => "==section 1==\nnew content 1",
		) );
		$this->assertEquals( 'Success', $re['edit']['result'] );
		$newtext = WikiPage::factory( Title::newFromText( $name) )->getContent( Revision::RAW )->getNativeData();
		$this->assertEquals( $newtext, "==section 1==\nnew content 1\n\n==section 2==\ncontent2" );

		// Test that we raise a 'nosuchsection' error
		try {
			$this->doApiRequestWithToken( array(
				'action' => 'edit',
				'title' => $name,
				'section' => '9999',
				'text' => 'text',
			) );
			$this->fail( "Should have raised a UsageException" );
		} catch ( UsageException $e ) {
			$this->assertEquals( $e->getCodeString(), 'nosuchsection' );
		}
	}

	/**
	 * Test action=edit&section=new
	 * Run it twice so we test adding a new section on a
	 * page that doesn't exist (bug 52830) and one that
	 * does exist
	 */
	public function testEditNewSection() {
		$name = 'Help:ApiEditPageTest_testEditNewSection';

		// Test on a page that does not already exist
		$this->assertFalse( Title::newFromText( $name )->exists() );
		list( $re ) = $this->doApiRequestWithToken( array(
			'action' => 'edit',
			'title' => $name,
			'section' => 'new',
			'text' => 'test',
			'summary' => 'header',
		));

		$this->assertEquals( 'Success', $re['edit']['result'] );
		// Check the page text is correct
		$text = WikiPage::factory( Title::newFromText( $name ) )->getContent( Revision::RAW )->getNativeData();
		$this->assertEquals( $text, "== header ==\n\ntest" );

		// Now on one that does
		$this->assertTrue( Title::newFromText( $name )->exists() );
		list( $re2 ) = $this->doApiRequestWithToken( array(
			'action' => 'edit',
			'title' => $name,
			'section' => 'new',
			'text' => 'test',
			'summary' => 'header',
		));

		$this->assertEquals( 'Success', $re2['edit']['result'] );
		$text = WikiPage::factory( Title::newFromText( $name ) )->getContent( Revision::RAW )->getNativeData();
		$this->assertEquals( $text, "== header ==\n\ntest\n\n== header ==\n\ntest" );
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
			"testing 1", EDIT_NEW, false, self::$users['sysop']->user );
		$this->forceRevisionDate( $page, '20120101000000' );
		$baseTime = $page->getRevision()->getTimestamp();

		// conflicting edit
		$page->doEditContent( new WikitextContent( "Foo bar" ),
			"testing 2", EDIT_UPDATE, $page->getLatest(), self::$users['uploader']->user );
		$this->forceRevisionDate( $page, '20120101020202' );

		// try to save edit, expect conflict
		try {
			$this->doApiRequestWithToken( array(
				'action' => 'edit',
				'title' => $name,
				'text' => 'nix bar!',
				'basetimestamp' => $baseTime,
			), null, self::$users['sysop']->user );

			$this->fail( 'edit conflict expected' );
		} catch ( UsageException $ex ) {
			$this->assertEquals( 'editconflict', $ex->getCodeString() );
		}
	}

	public function testEditConflict_redirect() {
		static $count = 0;
		$count++;

		// assume NS_HELP defaults to wikitext
		$name = "Help:ApiEditPageTest_testEditConflict_redirect_$count";
		$title = Title::newFromText( $name );
		$page = WikiPage::factory( $title );

		$rname = "Help:ApiEditPageTest_testEditConflict_redirect_r$count";
		$rtitle = Title::newFromText( $rname );
		$rpage = WikiPage::factory( $rtitle );

		// base edit for content
		$page->doEditContent( new WikitextContent( "Foo" ),
			"testing 1", EDIT_NEW, false, self::$users['sysop']->user );
		$this->forceRevisionDate( $page, '20120101000000' );
		$baseTime = $page->getRevision()->getTimestamp();

		// base edit for redirect
		$rpage->doEditContent( new WikitextContent( "#REDIRECT [[$name]]" ),
			"testing 1", EDIT_NEW, false, self::$users['sysop']->user );
		$this->forceRevisionDate( $rpage, '20120101000000' );

		// conflicting edit to redirect
		$rpage->doEditContent( new WikitextContent( "#REDIRECT [[$name]]\n\n[[Category:Test]]" ),
			"testing 2", EDIT_UPDATE, $page->getLatest(), self::$users['uploader']->user );
		$this->forceRevisionDate( $rpage, '20120101020202' );

		// try to save edit; should work, because we follow the redirect
		list( $re, , ) = $this->doApiRequestWithToken( array(
			'action' => 'edit',
			'title' => $rname,
			'text' => 'nix bar!',
			'basetimestamp' => $baseTime,
			'redirect' => true,
		), null, self::$users['sysop']->user );

		$this->assertEquals( 'Success', $re['edit']['result'],
			"no edit conflict expected when following redirect" );

		// try again, without following the redirect. Should fail.
		try {
			$this->doApiRequestWithToken( array(
				'action' => 'edit',
				'title' => $rname,
				'text' => 'nix bar!',
				'basetimestamp' => $baseTime,
			), null, self::$users['sysop']->user );

			$this->fail( 'edit conflict expected' );
		} catch ( UsageException $ex ) {
			$this->assertEquals( 'editconflict', $ex->getCodeString() );
		}
	}

	public function testEditConflict_bug41990() {
		static $count = 0;
		$count++;

		/*
		* bug 41990: if the target page has a newer revision than the redirect, then editing the
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
			"testing 1", EDIT_NEW, false, self::$users['sysop']->user );
		$this->forceRevisionDate( $page, '20120101000000' );

		// base edit for redirect
		$rpage->doEditContent( new WikitextContent( "#REDIRECT [[$name]]" ),
			"testing 1", EDIT_NEW, false, self::$users['sysop']->user );
		$this->forceRevisionDate( $rpage, '20120101000000' );
		$baseTime = $rpage->getRevision()->getTimestamp();

		// new edit to content
		$page->doEditContent( new WikitextContent( "Foo bar" ),
			"testing 2", EDIT_UPDATE, $page->getLatest(), self::$users['uploader']->user );
		$this->forceRevisionDate( $rpage, '20120101020202' );

		// try to save edit; should work, following the redirect.
		list( $re, , ) = $this->doApiRequestWithToken( array(
			'action' => 'edit',
			'title' => $rname,
			'text' => 'nix bar!',
			'redirect' => true,
		), null, self::$users['sysop']->user );

		$this->assertEquals( 'Success', $re['edit']['result'],
			"no edit conflict expected here" );
	}

	protected function forceRevisionDate( WikiPage $page, $timestamp ) {
		$dbw = wfGetDB( DB_MASTER );

		$dbw->update( 'revision',
			array( 'rev_timestamp' => $dbw->timestamp( $timestamp ) ),
			array( 'rev_id' => $page->getLatest() ) );

		$page->clear();
	}
}
