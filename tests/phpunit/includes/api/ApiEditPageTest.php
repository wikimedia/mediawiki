<?php

use MediaWiki\Block\DatabaseBlock;

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
		parent::setUp();

		$this->setMwGlobals( [
			'wgExtraNamespaces' => [
				12312 => 'Dummy',
				12313 => 'Dummy_talk',
				12314 => 'DummyNonText',
				12315 => 'DummyNonText_talk',
			],
			'wgNamespaceContentModels' => [
				12312 => 'testing',
				12314 => 'testing-nontext',
			],
		] );
		$this->mergeMwGlobalArrayValue( 'wgContentHandlers', [
			'testing' => 'DummyContentHandlerForTesting',
			'testing-nontext' => 'DummyNonTextContentHandler',
			'testing-serialize-error' => 'DummySerializeErrorContentHandler',
		] );
		$this->tablesUsed = array_merge(
			$this->tablesUsed,
			[ 'change_tag', 'change_tag_def', 'logging' ]
		);
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
		$this->assertSame( 'Success', $apiResult['edit']['result'] );

		$this->assertArrayHasKey( 'new', $apiResult['edit'] );
		$this->assertArrayNotHasKey( 'nochange', $apiResult['edit'] );

		$this->assertArrayHasKey( 'pageid', $apiResult['edit'] );

		// -- test existing page, no change ----------------------------
		$data = $this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'text' => 'some text',
		] );

		$this->assertSame( 'Success', $data[0]['edit']['result'] );

		$this->assertArrayNotHasKey( 'new', $data[0]['edit'] );
		$this->assertArrayHasKey( 'nochange', $data[0]['edit'] );

		// -- test existing page, with change --------------------------
		$data = $this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'text' => 'different text'
		] );

		$this->assertSame( 'Success', $data[0]['edit']['result'] );

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

			$this->assertSame( 'Success', $re['edit']['result'] ); // sanity
		}

		// -- try append/prepend --------------------------------------------
		list( $re ) = $this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			$op . 'text' => $append, ] );

		$this->assertSame( 'Success', $re['edit']['result'] );

		// -- validate -----------------------------------------------------
		$page = new WikiPage( Title::newFromText( $name ) );
		$content = $page->getContent();
		$this->assertNotNull( $content, 'Page should have been created' );

		$text = $content->getText();

		$this->assertSame( $expected, $text );
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
		$this->assertSame( 'Success', $re['edit']['result'] );
		$newtext = WikiPage::factory( Title::newFromText( $name ) )
			->getContent( Revision::RAW )
			->getText();
		$this->assertSame( "==section 1==\nnew content 1\n\n==section 2==\ncontent2", $newtext );

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

		$this->assertSame( 'Success', $re['edit']['result'] );
		// Check the page text is correct
		$text = WikiPage::factory( Title::newFromText( $name ) )
			->getContent( Revision::RAW )
			->getText();
		$this->assertSame( "== header ==\n\ntest", $text );

		// Now on one that does
		$this->assertTrue( Title::newFromText( $name )->exists() );
		list( $re2 ) = $this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'section' => 'new',
			'text' => 'test',
			'summary' => 'header',
		] );

		$this->assertSame( 'Success', $re2['edit']['result'] );
		$text = WikiPage::factory( Title::newFromText( $name ) )
			->getContent( Revision::RAW )
			->getText();
		$this->assertSame( "== header ==\n\ntest\n\n== header ==\n\ntest", $text );
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
		] );

		$this->assertSame( 'Success', $re['edit']['result'],
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
			] );

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
			] );

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
		] );

		$this->assertSame( 'Success', $re['edit']['result'],
			"no edit conflict expected here" );
	}

	public function testEditConflict_T43990() {
		static $count = 0;
		$count++;

		/*
		 * T43990: if the target page has a newer revision than the redirect, then editing the
		 * redirect while specifying 'redirect' and *not* specifying 'basetimestamp' erroneously
		 * caused an edit conflict to be detected.
		 */

		// assume NS_HELP defaults to wikitext
		$name = "Help:ApiEditPageTest_testEditConflict_redirect_T43990_$count";
		$title = Title::newFromText( $name );
		$page = WikiPage::factory( $title );

		$rname = "Help:ApiEditPageTest_testEditConflict_redirect_T43990_r$count";
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
		] );

		$this->assertSame( 'Success', $re['edit']['result'],
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
			ApiUsageException::class,
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
		$this->assertSame( 'Success', $apiResult['edit']['result'] );

		$this->assertArrayHasKey( 'new', $apiResult['edit'] );
		$this->assertArrayNotHasKey( 'nochange', $apiResult['edit'] );

		$this->assertArrayHasKey( 'pageid', $apiResult['edit'] );

		// validate resulting revision
		$page = WikiPage::factory( Title::newFromText( $name ) );
		$this->assertSame( "testing-nontext", $page->getContentModel() );
		$this->assertSame( $data, $page->getContent()->serialize() );
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
		$this->assertSame( 'Success', $apiResult['edit']['result'] );
		$this->assertArrayHasKey( 'contentmodel', $apiResult['edit'] );
		// Content model is wikitext
		$this->assertSame( 'wikitext', $apiResult['edit']['contentmodel'] );

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
		$this->assertSame( 'Success', $apiResult['edit']['result'] );
		$this->assertArrayHasKey( 'contentmodel', $apiResult['edit'] );
		$this->assertSame( 'json', $apiResult['edit']['contentmodel'] );

		$apiResult = $this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'undo' => $apiResult['edit']['newrevid']
		], null, $sysop )[0];

		// Check success
		$this->assertArrayHasKey( 'edit', $apiResult );
		$this->assertArrayHasKey( 'result', $apiResult['edit'] );
		$this->assertSame( 'Success', $apiResult['edit']['result'] );
		$this->assertArrayHasKey( 'contentmodel', $apiResult['edit'] );
		// Check that the contentmodel is back to wikitext now.
		$this->assertSame( 'wikitext', $apiResult['edit']['contentmodel'] );
	}

	// The tests below are mostly not commented because they do exactly what
	// you'd expect from the name.

	public function testCorrectContentFormat() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'text' => 'some text',
			'contentmodel' => 'wikitext',
			'contentformat' => 'text/x-wiki',
		] );

		$this->assertTrue( Title::newFromText( $name )->exists() );
	}

	public function testUnsupportedContentFormat() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );

		$this->setExpectedException( ApiUsageException::class,
			'Unrecognized value for parameter "contentformat": nonexistent format.' );

		try {
			$this->doApiRequestWithToken( [
				'action' => 'edit',
				'title' => $name,
				'text' => 'some text',
				'contentformat' => 'nonexistent format',
			] );
		} finally {
			$this->assertFalse( Title::newFromText( $name )->exists() );
		}
	}

	public function testMismatchedContentFormat() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );

		$this->setExpectedException( ApiUsageException::class,
			'The requested format text/plain is not supported for content ' .
			"model wikitext used by $name." );

		try {
			$this->doApiRequestWithToken( [
				'action' => 'edit',
				'title' => $name,
				'text' => 'some text',
				'contentmodel' => 'wikitext',
				'contentformat' => 'text/plain',
			] );
		} finally {
			$this->assertFalse( Title::newFromText( $name )->exists() );
		}
	}

	public function testUndoToInvalidRev() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );

		$revId = $this->editPage( $name, 'Some text' )->value['revision']
			->getId();
		$revId++;

		$this->setExpectedException( ApiUsageException::class,
			"There is no revision with ID $revId." );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'undo' => $revId,
		] );
	}

	/**
	 * Tests what happens if the undo parameter is a valid revision, but
	 * the undoafter parameter doesn't refer to a revision that exists in the
	 * database.
	 */
	public function testUndoAfterToInvalidRev() {
		// We can't just pick a large number for undoafter (as in
		// testUndoToInvalidRev above), because then MediaWiki will helpfully
		// assume we switched around undo and undoafter and we'll test the code
		// path for undo being invalid, not undoafter.  So instead we delete
		// the revision from the database.  In real life this case could come
		// up if a revision number was skipped, e.g., if two transactions try
		// to insert new revision rows at once and the first one to succeed
		// gets rolled back.
		$name = 'Help:' . ucfirst( __FUNCTION__ );
		$titleObj = Title::newFromText( $name );

		$revId1 = $this->editPage( $name, '1' )->value['revision']->getId();
		$revId2 = $this->editPage( $name, '2' )->value['revision']->getId();
		$revId3 = $this->editPage( $name, '3' )->value['revision']->getId();

		// Make the middle revision disappear
		$dbw = wfGetDB( DB_MASTER );
		$dbw->delete( 'revision', [ 'rev_id' => $revId2 ], __METHOD__ );
		$dbw->update( 'revision', [ 'rev_parent_id' => $revId1 ],
			[ 'rev_id' => $revId3 ], __METHOD__ );

		$this->setExpectedException( ApiUsageException::class,
			"There is no revision with ID $revId2." );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'undo' => $revId3,
			'undoafter' => $revId2,
		] );
	}

	/**
	 * Tests what happens if the undo parameter is a valid revision, but
	 * undoafter is hidden (rev_deleted).
	 */
	public function testUndoAfterToHiddenRev() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );
		$titleObj = Title::newFromText( $name );

		$this->editPage( $name, '0' );

		$revId1 = $this->editPage( $name, '1' )->value['revision']->getId();

		$revId2 = $this->editPage( $name, '2' )->value['revision']->getId();

		// Hide the middle revision
		$list = RevisionDeleter::createList( 'revision',
			RequestContext::getMain(), $titleObj, [ $revId1 ] );
		$list->setVisibility( [
			'value' => [ Revision::DELETED_TEXT => 1 ],
			'comment' => 'Bye-bye',
		] );

		$this->setExpectedException( ApiUsageException::class,
			"There is no revision with ID $revId1." );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'undo' => $revId2,
			'undoafter' => $revId1,
		] );
	}

	/**
	 * Test undo when a revision with a higher id has an earlier timestamp.
	 * This can happen if importing an old revision.
	 */
	public function testUndoWithSwappedRevisions() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );
		$titleObj = Title::newFromText( $name );

		$this->editPage( $name, '0' );

		$revId2 = $this->editPage( $name, '2' )->value['revision']->getId();

		$revId1 = $this->editPage( $name, '1' )->value['revision']->getId();

		// Now monkey with the timestamp
		$dbw = wfGetDB( DB_MASTER );
		$dbw->update(
			'revision',
			[ 'rev_timestamp' => $dbw->timestamp( time() - 86400 ) ],
			[ 'rev_id' => $revId1 ],
			__METHOD__
		);

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'undo' => $revId2,
			'undoafter' => $revId1,
		] );

		$text = ( new WikiPage( $titleObj ) )->getContent()->getText();

		// This is wrong!  It should be 1.  But let's test for our incorrect
		// behavior for now, so if someone fixes it they'll fix the test as
		// well to expect 1.  If we disabled the test, it might stay disabled
		// even once the bug is fixed, which would be a shame.
		$this->assertSame( '2', $text );
	}

	public function testUndoWithConflicts() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );

		$this->setExpectedException( ApiUsageException::class,
			'The edit could not be undone due to conflicting intermediate edits.' );

		$this->editPage( $name, '1' );

		$revId = $this->editPage( $name, '2' )->value['revision']->getId();

		$this->editPage( $name, '3' );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'undo' => $revId,
		] );

		$text = ( new WikiPage( Title::newFromText( $name ) ) )->getContent()
			->getText();
		$this->assertSame( '3', $text );
	}

	/**
	 * undoafter is supposed to be less than undo.  If not, we reverse their
	 * meaning, so that the two are effectively interchangeable.
	 */
	public function testReversedUndoAfter() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );

		$this->editPage( $name, '0' );
		$revId1 = $this->editPage( $name, '1' )->value['revision']->getId();
		$revId2 = $this->editPage( $name, '2' )->value['revision']->getId();

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'undo' => $revId1,
			'undoafter' => $revId2,
		] );

		$text = ( new WikiPage( Title::newFromText( $name ) ) )->getContent()
			->getText();
		$this->assertSame( '1', $text );
	}

	public function testUndoToRevFromDifferentPage() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );

		$this->editPage( "$name-1", 'Some text' );
		$revId = $this->editPage( "$name-1", 'Some more text' )
			->value['revision']->getId();

		$this->editPage( "$name-2", 'Some text' );

		$this->setExpectedException( ApiUsageException::class,
			"r$revId is not a revision of $name-2." );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => "$name-2",
			'undo' => $revId,
		] );
	}

	public function testUndoAfterToRevFromDifferentPage() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );

		$revId1 = $this->editPage( "$name-1", 'Some text' )
			->value['revision']->getId();

		$revId2 = $this->editPage( "$name-2", 'Some text' )
			->value['revision']->getId();

		$this->setExpectedException( ApiUsageException::class,
			"r$revId1 is not a revision of $name-2." );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => "$name-2",
			'undo' => $revId2,
			'undoafter' => $revId1,
		] );
	}

	public function testMd5Text() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );

		$this->assertFalse( Title::newFromText( $name )->exists() );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'text' => 'Some text',
			'md5' => md5( 'Some text' ),
		] );

		$this->assertTrue( Title::newFromText( $name )->exists() );
	}

	public function testMd5PrependText() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );

		$this->editPage( $name, 'Some text' );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'prependtext' => 'Alert: ',
			'md5' => md5( 'Alert: ' ),
		] );

		$text = ( new WikiPage( Title::newFromText( $name ) ) )
			->getContent()->getText();
		$this->assertSame( 'Alert: Some text', $text );
	}

	public function testMd5AppendText() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );

		$this->editPage( $name, 'Some text' );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'appendtext' => ' is nice',
			'md5' => md5( ' is nice' ),
		] );

		$text = ( new WikiPage( Title::newFromText( $name ) ) )
			->getContent()->getText();
		$this->assertSame( 'Some text is nice', $text );
	}

	public function testMd5PrependAndAppendText() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );

		$this->editPage( $name, 'Some text' );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'prependtext' => 'Alert: ',
			'appendtext' => ' is nice',
			'md5' => md5( 'Alert:  is nice' ),
		] );

		$text = ( new WikiPage( Title::newFromText( $name ) ) )
			->getContent()->getText();
		$this->assertSame( 'Alert: Some text is nice', $text );
	}

	public function testIncorrectMd5Text() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );

		$this->setExpectedException( ApiUsageException::class,
			'The supplied MD5 hash was incorrect.' );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'text' => 'Some text',
			'md5' => md5( '' ),
		] );
	}

	public function testIncorrectMd5PrependText() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );

		$this->setExpectedException( ApiUsageException::class,
			'The supplied MD5 hash was incorrect.' );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'prependtext' => 'Some ',
			'appendtext' => 'text',
			'md5' => md5( 'Some ' ),
		] );
	}

	public function testIncorrectMd5AppendText() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );

		$this->setExpectedException( ApiUsageException::class,
			'The supplied MD5 hash was incorrect.' );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'prependtext' => 'Some ',
			'appendtext' => 'text',
			'md5' => md5( 'text' ),
		] );
	}

	public function testCreateOnly() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );

		$this->setExpectedException( ApiUsageException::class,
			'The article you tried to create has been created already.' );

		$this->editPage( $name, 'Some text' );
		$this->assertTrue( Title::newFromText( $name )->exists() );

		try {
			$this->doApiRequestWithToken( [
				'action' => 'edit',
				'title' => $name,
				'text' => 'Some more text',
				'createonly' => '',
			] );
		} finally {
			// Validate that content was not changed
			$text = ( new WikiPage( Title::newFromText( $name ) ) )
				->getContent()->getText();

			$this->assertSame( 'Some text', $text );
		}
	}

	public function testNoCreate() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );

		$this->setExpectedException( ApiUsageException::class,
			"The page you specified doesn't exist." );

		$this->assertFalse( Title::newFromText( $name )->exists() );

		try {
			$this->doApiRequestWithToken( [
				'action' => 'edit',
				'title' => $name,
				'text' => 'Some text',
				'nocreate' => '',
			] );
		} finally {
			$this->assertFalse( Title::newFromText( $name )->exists() );
		}
	}

	/**
	 * Appending/prepending is currently only supported for TextContent.  We
	 * test this right now, and when support is added this test should be
	 * replaced by tests that the support is correct.
	 */
	public function testAppendWithNonTextContentHandler() {
		$name = 'MediaWiki:' . ucfirst( __FUNCTION__ );

		$this->setExpectedException( ApiUsageException::class,
			"Can't append to pages using content model testing-nontext." );

		$this->setTemporaryHook( 'ContentHandlerDefaultModelFor',
			function ( Title $title, &$model ) use ( $name ) {
				if ( $title->getPrefixedText() === $name ) {
					$model = 'testing-nontext';
				}
				return true;
			}
		);

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'appendtext' => 'Some text',
		] );
	}

	public function testAppendInMediaWikiNamespace() {
		$name = 'MediaWiki:' . ucfirst( __FUNCTION__ );

		$this->assertFalse( Title::newFromText( $name )->exists() );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'appendtext' => 'Some text',
		] );

		$this->assertTrue( Title::newFromText( $name )->exists() );
	}

	public function testAppendInMediaWikiNamespaceWithSerializationError() {
		$name = 'MediaWiki:' . ucfirst( __FUNCTION__ );

		$this->setExpectedException( ApiUsageException::class,
			'Content serialization failed: Could not unserialize content' );

		$this->setTemporaryHook( 'ContentHandlerDefaultModelFor',
			function ( Title $title, &$model ) use ( $name ) {
				if ( $title->getPrefixedText() === $name ) {
					$model = 'testing-serialize-error';
				}
				return true;
			}
		);

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'appendtext' => 'Some text',
		] );
	}

	public function testAppendNewSection() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );

		$this->editPage( $name, 'Initial content' );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'appendtext' => '== New section ==',
			'section' => 'new',
		] );

		$text = ( new WikiPage( Title::newFromText( $name ) ) )
			->getContent()->getText();

		$this->assertSame( "Initial content\n\n== New section ==", $text );
	}

	public function testAppendNewSectionWithInvalidContentModel() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );

		$this->setExpectedException( ApiUsageException::class,
			'Sections are not supported for content model text.' );

		$this->editPage( $name, 'Initial content' );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'appendtext' => '== New section ==',
			'section' => 'new',
			'contentmodel' => 'text',
		] );
	}

	public function testAppendNewSectionWithTitle() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );

		$this->editPage( $name, 'Initial content' );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'sectiontitle' => 'My section',
			'appendtext' => 'More content',
			'section' => 'new',
		] );

		$page = new WikiPage( Title::newFromText( $name ) );

		$this->assertSame( "Initial content\n\n== My section ==\n\nMore content",
			$page->getContent()->getText() );
		$this->assertSame( '/* My section */ new section',
			$page->getRevision()->getComment() );
	}

	public function testAppendNewSectionWithSummary() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );

		$this->editPage( $name, 'Initial content' );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'appendtext' => 'More content',
			'section' => 'new',
			'summary' => 'Add new section',
		] );

		$page = new WikiPage( Title::newFromText( $name ) );

		$this->assertSame( "Initial content\n\n== Add new section ==\n\nMore content",
			$page->getContent()->getText() );
		// EditPage actually assumes the summary is the section name here
		$this->assertSame( '/* Add new section */ new section',
			$page->getRevision()->getComment() );
	}

	public function testAppendNewSectionWithTitleAndSummary() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );

		$this->editPage( $name, 'Initial content' );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'sectiontitle' => 'My section',
			'appendtext' => 'More content',
			'section' => 'new',
			'summary' => 'Add new section',
		] );

		$page = new WikiPage( Title::newFromText( $name ) );

		$this->assertSame( "Initial content\n\n== My section ==\n\nMore content",
			$page->getContent()->getText() );
		$this->assertSame( 'Add new section',
			$page->getRevision()->getComment() );
	}

	public function testAppendToSection() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );

		$this->editPage( $name, "== Section 1 ==\n\nContent\n\n" .
			"== Section 2 ==\n\nFascinating!" );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'appendtext' => ' and more content',
			'section' => '1',
		] );

		$text = ( new WikiPage( Title::newFromText( $name ) ) )
			->getContent()->getText();

		$this->assertSame( "== Section 1 ==\n\nContent and more content\n\n" .
			"== Section 2 ==\n\nFascinating!", $text );
	}

	public function testAppendToFirstSection() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );

		$this->editPage( $name, "Content\n\n== Section 1 ==\n\nFascinating!" );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'appendtext' => ' and more content',
			'section' => '0',
		] );

		$text = ( new WikiPage( Title::newFromText( $name ) ) )
			->getContent()->getText();

		$this->assertSame( "Content and more content\n\n== Section 1 ==\n\n" .
			"Fascinating!", $text );
	}

	public function testAppendToNonexistentSection() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );

		$this->setExpectedException( ApiUsageException::class, 'There is no section 1.' );

		$this->editPage( $name, 'Content' );

		try {
			$this->doApiRequestWithToken( [
				'action' => 'edit',
				'title' => $name,
				'appendtext' => ' and more content',
				'section' => '1',
			] );
		} finally {
			$text = ( new WikiPage( Title::newFromText( $name ) ) )
				->getContent()->getText();

			$this->assertSame( 'Content', $text );
		}
	}

	public function testEditMalformedSection() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );

		$this->setExpectedException( ApiUsageException::class,
			'The "section" parameter must be a valid section ID or "new".' );
		$this->editPage( $name, 'Content' );

		try {
			$this->doApiRequestWithToken( [
				'action' => 'edit',
				'title' => $name,
				'text' => 'Different content',
				'section' => 'It is unlikely that this is valid',
			] );
		} finally {
			$text = ( new WikiPage( Title::newFromText( $name ) ) )
				->getContent()->getText();

			$this->assertSame( 'Content', $text );
		}
	}

	public function testEditWithStartTimestamp() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );
		$this->setExpectedException( ApiUsageException::class,
			'The page has been deleted since you fetched its timestamp.' );

		$startTime = MWTimestamp::convert( TS_MW, time() - 1 );

		$this->editPage( $name, 'Some text' );

		$pageObj = new WikiPage( Title::newFromText( $name ) );
		$pageObj->doDeleteArticle( 'Bye-bye' );

		$this->assertFalse( $pageObj->exists() );

		try {
			$this->doApiRequestWithToken( [
				'action' => 'edit',
				'title' => $name,
				'text' => 'Different text',
				'starttimestamp' => $startTime,
			] );
		} finally {
			$this->assertFalse( $pageObj->exists() );
		}
	}

	public function testEditMinor() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );

		$this->editPage( $name, 'Some text' );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'text' => 'Different text',
			'minor' => '',
		] );

		$revisionStore = \MediaWiki\MediaWikiServices::getInstance()->getRevisionStore();
		$revision = $revisionStore->getRevisionByTitle( Title::newFromText( $name ) );
		$this->assertTrue( $revision->isMinor() );
	}

	public function testEditRecreate() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );

		$startTime = MWTimestamp::convert( TS_MW, time() - 1 );

		$this->editPage( $name, 'Some text' );

		$pageObj = new WikiPage( Title::newFromText( $name ) );
		$pageObj->doDeleteArticle( 'Bye-bye' );

		$this->assertFalse( $pageObj->exists() );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'text' => 'Different text',
			'starttimestamp' => $startTime,
			'recreate' => '',
		] );

		$this->assertTrue( Title::newFromText( $name )->exists() );
	}

	public function testEditWatch() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );
		$user = self::$users['sysop']->getUser();

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'text' => 'Some text',
			'watch' => '',
		] );

		$this->assertTrue( Title::newFromText( $name )->exists() );
		$this->assertTrue( $user->isWatched( Title::newFromText( $name ) ) );
	}

	public function testEditUnwatch() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );
		$user = self::$users['sysop']->getUser();
		$titleObj = Title::newFromText( $name );

		$user->addWatch( $titleObj );

		$this->assertFalse( $titleObj->exists() );
		$this->assertTrue( $user->isWatched( $titleObj ) );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'text' => 'Some text',
			'unwatch' => '',
		] );

		$this->assertTrue( $titleObj->exists() );
		$this->assertFalse( $user->isWatched( $titleObj ) );
	}

	public function testEditWithTag() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );

		ChangeTags::defineTag( 'custom tag' );

		$revId = $this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'text' => 'Some text',
			'tags' => 'custom tag',
		] )[0]['edit']['newrevid'];

		$dbw = wfGetDB( DB_MASTER );
		$this->assertSame( 'custom tag', $dbw->selectField(
			[ 'change_tag', 'change_tag_def' ],
			'ctd_name',
			[ 'ct_rev_id' => $revId ],
			__METHOD__,
			[ 'change_tag_def' => [ 'JOIN', 'ctd_id = ct_tag_id' ] ]
			)
		);
	}

	public function testEditWithoutTagPermission() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );

		$this->setExpectedException( ApiUsageException::class,
			'You do not have permission to apply change tags along with your changes.' );

		$this->assertFalse( Title::newFromText( $name )->exists() );

		ChangeTags::defineTag( 'custom tag' );
		$this->setMwGlobals( 'wgRevokePermissions',
			[ 'user' => [ 'applychangetags' => true ] ] );

		try {
			$this->doApiRequestWithToken( [
				'action' => 'edit',
				'title' => $name,
				'text' => 'Some text',
				'tags' => 'custom tag',
			] );
		} finally {
			$this->assertFalse( Title::newFromText( $name )->exists() );
		}
	}

	public function testEditAbortedByEditPageHookWithResult() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );

		$this->setTemporaryHook( 'EditFilterMergedContent',
			function ( $unused1, $unused2, Status $status ) {
				$status->apiHookResult = [ 'msg' => 'A message for you!' ];
				return false;
			} );

		$res = $this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'text' => 'Some text',
		] );

		$this->assertFalse( Title::newFromText( $name )->exists() );
		$this->assertSame( [ 'edit' => [ 'msg' => 'A message for you!',
			'result' => 'Failure' ] ], $res[0] );
	}

	public function testEditAbortedByEditPageHookWithNoResult() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );

		$this->setExpectedException( ApiUsageException::class,
			'The modification you tried to make was aborted by an extension.' );

		$this->setTemporaryHook( 'EditFilterMergedContent',
			function () {
				return false;
			}
		);

		try {
			$this->doApiRequestWithToken( [
				'action' => 'edit',
				'title' => $name,
				'text' => 'Some text',
			] );
		} finally {
			$this->assertFalse( Title::newFromText( $name )->exists() );
		}
	}

	public function testEditWhileBlocked() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );

		$this->assertNull( DatabaseBlock::newFromTarget( '127.0.0.1' ), 'Sanity check' );

		$block = new DatabaseBlock( [
			'address' => self::$users['sysop']->getUser()->getName(),
			'by' => self::$users['sysop']->getUser()->getId(),
			'reason' => 'Capriciousness',
			'timestamp' => '19370101000000',
			'expiry' => 'infinity',
			'enableAutoblock' => true,
		] );
		$block->insert();

		try {
			$this->doApiRequestWithToken( [
				'action' => 'edit',
				'title' => $name,
				'text' => 'Some text',
			] );
			$this->fail( 'Expected exception not thrown' );
		} catch ( ApiUsageException $ex ) {
			$this->assertSame( 'You have been blocked from editing.', $ex->getMessage() );
			$this->assertNotNull( DatabaseBlock::newFromTarget( '127.0.0.1' ), 'Autoblock spread' );
		} finally {
			$block->delete();
			self::$users['sysop']->getUser()->clearInstanceCache();
		}
	}

	public function testEditWhileReadOnly() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );

		$this->setExpectedException( ApiUsageException::class,
			'The wiki is currently in read-only mode.' );

		$svc = \MediaWiki\MediaWikiServices::getInstance()->getReadOnlyMode();
		$svc->setReason( "Read-only for testing" );

		try {
			$this->doApiRequestWithToken( [
				'action' => 'edit',
				'title' => $name,
				'text' => 'Some text',
			] );
		} finally {
			$svc->setReason( false );
		}
	}

	public function testCreateImageRedirectAnon() {
		$name = 'File:' . ucfirst( __FUNCTION__ );

		$this->setExpectedException( ApiUsageException::class,
			"Anonymous users can't create image redirects." );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'text' => '#REDIRECT [[File:Other file.png]]',
		], null, new User() );
	}

	public function testCreateImageRedirectLoggedIn() {
		$name = 'File:' . ucfirst( __FUNCTION__ );

		$this->setExpectedException( ApiUsageException::class,
			"You don't have permission to create image redirects." );

		$this->setMwGlobals( 'wgRevokePermissions',
			[ 'user' => [ 'upload' => true ] ] );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'text' => '#REDIRECT [[File:Other file.png]]',
		] );
	}

	public function testTooBigEdit() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );

		$this->setExpectedException( ApiUsageException::class,
			'The content you supplied exceeds the article size limit of 1 kilobyte.' );

		$this->setMwGlobals( 'wgMaxArticleSize', 1 );

		$text = str_repeat( '!', 1025 );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'text' => $text,
		] );
	}

	public function testProhibitedAnonymousEdit() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );

		$this->setExpectedException( ApiUsageException::class,
			'The action you have requested is limited to users in the group: ' );

		$this->setMwGlobals( 'wgRevokePermissions', [ '*' => [ 'edit' => true ] ] );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'text' => 'Some text',
		], null, new User() );
	}

	public function testProhibitedChangeContentModel() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );

		$this->setExpectedException( ApiUsageException::class,
			"You don't have permission to change the content model of a page." );

		$this->setMwGlobals( 'wgRevokePermissions',
			[ 'user' => [ 'editcontentmodel' => true ] ] );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'text' => 'Some text',
			'contentmodel' => 'json',
		] );
	}
}
