<?php

namespace MediaWiki\Tests\Api;

use MediaWiki\Api\ApiUsageException;
use MediaWiki\CommentStore\CommentStoreComment;
use MediaWiki\Content\JavaScriptContent;
use MediaWiki\Content\WikitextContent;
use MediaWiki\Context\RequestContext;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\WikiPage;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Status\Status;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleValue;
use MediaWiki\User\Options\StaticUserOptionsLookup;
use MediaWiki\User\User;
use MediaWiki\Utils\MWTimestamp;
use RevisionDeleter;
use Wikimedia\Rdbms\IDBAccessObject;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * Tests for MediaWiki api.php?action=edit.
 *
 * @author Daniel Kinzler
 *
 * @group API
 * @group Database
 * @group medium
 *
 * @covers \MediaWiki\Api\ApiEditPage
 */
class ApiEditPageTest extends ApiTestCase {

	use TempUserTestTrait;

	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValues( [
			MainConfigNames::ExtraNamespaces => [
				12312 => 'Dummy',
				12313 => 'Dummy_talk',
				12314 => 'DummyNonText',
				12315 => 'DummyNonText_talk',
			],
			MainConfigNames::NamespaceContentModels => [
				12312 => 'testing',
				12314 => 'testing-nontext',
			],
			MainConfigNames::WatchlistExpiry => true,
			MainConfigNames::WatchlistExpiryMaxDuration => '6 months',
		] );
		$this->mergeMwGlobalArrayValue( 'wgContentHandlers', [
			'testing' => 'DummyContentHandlerForTesting',
			'testing-nontext' => 'DummyNonTextContentHandler',
			'testing-serialize-error' => 'DummySerializeErrorContentHandler',
		] );
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
		$title = Title::makeTitle( NS_HELP, "ApiEditPageTest_testEditAppend_$count" );

		// -- create page (or not) -----------------------------------------
		if ( $text !== null ) {
			[ $re ] = $this->doApiRequestWithToken( [
				'action' => 'edit',
				'title' => $title->getPrefixedText(),
				'text' => $text, ] );

			$this->assertSame( 'Success', $re['edit']['result'] );
		}

		// -- try append/prepend --------------------------------------------
		[ $re ] = $this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $title->getPrefixedText(),
			$op . 'text' => $append, ] );

		$this->assertSame( 'Success', $re['edit']['result'] );

		// -- validate -----------------------------------------------------
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );
		$content = $page->getContent();
		$this->assertNotNull( $content, 'Page should have been created' );

		$text = $content->getText();

		$this->assertSame( $expected, $text );
	}

	/**
	 * Test editing of sections
	 */
	public function testEditSection() {
		$title = Title::makeTitle( NS_HELP, 'ApiEditPageTest_testEditSection' );
		$wikiPageFactory = $this->getServiceContainer()->getWikiPageFactory();
		$page = $wikiPageFactory->newFromTitle( $title );
		$text = "==section 1==\ncontent 1\n==section 2==\ncontent2";
		// Preload the page with some text
		$page->doUserEditContent(
			$page->getContentHandler()->unserializeContent( $text ),
			$this->getTestSysop()->getAuthority(),
			'summary'
		);

		[ $re ] = $this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $title->getPrefixedText(),
			'section' => '1',
			'text' => "==section 1==\nnew content 1",
		] );
		$this->assertSame( 'Success', $re['edit']['result'] );
		$newtext = $wikiPageFactory->newFromTitle( $title )
			->getContent( RevisionRecord::RAW )
			->getText();
		$this->assertSame( "==section 1==\nnew content 1\n\n==section 2==\ncontent2", $newtext );

		// Test that we raise a 'nosuchsection' error
		try {
			$this->doApiRequestWithToken( [
				'action' => 'edit',
				'title' => $title->getPrefixedText(),
				'section' => '9999',
				'text' => 'text',
			] );
			$this->fail( "Should have raised an ApiUsageException" );
		} catch ( ApiUsageException $e ) {
			$this->assertApiErrorCode( 'nosuchsection', $e );
		}
	}

	/**
	 * Test action=edit&section=new
	 * Run it twice so we test adding a new section on a
	 * page that doesn't exist (T54830) and one that
	 * does exist
	 */
	public function testEditNewSection() {
		$title = Title::makeTitle( NS_HELP, 'ApiEditPageTest_testEditNewSection' );
		$wikiPageFactory = $this->getServiceContainer()->getWikiPageFactory();

		// Test on a page that does not already exist
		$this->assertFalse( $title->exists() );
		[ $re ] = $this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $title->getPrefixedText(),
			'section' => 'new',
			'text' => 'test',
			'summary' => 'header',
		] );

		$this->assertSame( 'Success', $re['edit']['result'] );
		// Check the page text is correct
		$text = $wikiPageFactory->newFromTitle( $title )
			->getContent( RevisionRecord::RAW )
			->getText();
		$this->assertSame( "== header ==\n\ntest", $text );

		// Now on one that does
		$this->assertTrue( $title->exists( IDBAccessObject::READ_LATEST ) );
		[ $re2 ] = $this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $title->getPrefixedText(),
			'section' => 'new',
			'text' => 'test',
			'summary' => 'header',
		] );

		$this->assertSame( 'Success', $re2['edit']['result'] );
		$text = $wikiPageFactory->newFromTitle( $title )
			->getContent( RevisionRecord::RAW )
			->getText();
		$this->assertSame( "== header ==\n\ntest\n\n== header ==\n\ntest", $text );
	}

	/**
	 * Test action=edit&section=new with different combinations of summary and sectiontitle.
	 *
	 * @dataProvider provideEditNewSectionSummarySectiontitle
	 */
	public function testEditNewSectionSummarySectiontitle(
		$sectiontitle,
		$summary,
		$expectedText,
		$expectedSummary
	) {
		static $count = 0;
		$count++;
		$title = Title::makeTitle( NS_HELP, 'ApiEditPageTest_testEditNewSectionSummarySectiontitle' . $count );

		// Test edit 1 (new page)
		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $title->getPrefixedText(),
			'section' => 'new',
			'text' => 'text',
			'sectiontitle' => $sectiontitle,
			'summary' => $summary,
		] );

		$wikiPageFactory = $this->getServiceContainer()->getWikiPageFactory();
		$wikiPage = $wikiPageFactory->newFromTitle( $title );

		// Check the page text is correct
		$savedText = $wikiPage->getContent( RevisionRecord::RAW )->getText();
		$this->assertSame( $expectedText, $savedText, 'Correct text saved (new page)' );

		// Check that the edit summary is correct
		// (when not provided or empty, there is an autogenerated summary for page creation)
		$savedSummary = $wikiPage->getRevisionRecord()->getComment( RevisionRecord::RAW )->text;
		$expectedSummaryNew = $expectedSummary ?: wfMessage( 'autosumm-new' )->rawParams( $expectedText )
			->inContentLanguage()->text();
		$this->assertSame( $expectedSummaryNew, $savedSummary, 'Correct summary saved (new page)' );

		// Clear the page
		$this->editPage( $wikiPage, '' );

		// Test edit 2 (existing page)
		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $title->getPrefixedText(),
			'section' => 'new',
			'text' => 'text',
			'sectiontitle' => $sectiontitle,
			'summary' => $summary,
		] );

		$wikiPage = $wikiPageFactory->newFromTitle( $title );

		// Check the page text is correct
		$savedText = $wikiPage->getContent( RevisionRecord::RAW )->getText();
		$this->assertSame( $expectedText, $savedText, 'Correct text saved (existing page)' );

		// Check that the edit summary is correct
		$savedSummary = $wikiPage->getRevisionRecord()->getComment( RevisionRecord::RAW )->text;
		$this->assertSame( $expectedSummary, $savedSummary, 'Correct summary saved (existing page)' );
	}

	public static function provideEditNewSectionSummarySectiontitle() {
		$sectiontitleCases = [
			'unset' => null,
			'empty' => '',
			'set' => 'sectiontitle',
		];
		$summaryCases = [
			'unset' => null,
			'empty' => '',
			'set' => 'summary',
		];

		$expectedTexts = [
			"text",
			"text",
			"== summary ==\n\ntext",
			"text",
			"text",
			"text",
			"== sectiontitle ==\n\ntext",
			"== sectiontitle ==\n\ntext",
			"== sectiontitle ==\n\ntext",
		];

		$expectedSummaries = [
			'',
			'',
			'/* summary */ new section',
			'',
			'',
			'summary',
			'/* sectiontitle */ new section',
			'/* sectiontitle */ new section',
			'summary',
		];

		$i = 0;
		foreach ( $sectiontitleCases as $sectiontitleDesc => $sectiontitle ) {
			foreach ( $summaryCases as $summaryDesc => $summary ) {
				$message = "sectiontitle $sectiontitleDesc, summary $summaryDesc";
				yield $message => [
					$sectiontitle,
					$summary,
					$expectedTexts[$i],
					$expectedSummaries[$i],
				];
				$i++;
			}
		}
	}

	/**
	 * Ensure we can edit through a redirect, if adding a section
	 */
	public function testEdit_redirect() {
		static $count = 0;
		$count++;

		// assume NS_HELP defaults to wikitext
		$title = Title::makeTitle( NS_HELP, "ApiEditPageTest_testEdit_redirect_$count" );
		$wikiPageFactory = $this->getServiceContainer()->getWikiPageFactory();
		$page = $this->getExistingTestPage( $title );
		$this->forceRevisionDate( $page, '20120101000000' );

		$rtitle = Title::makeTitle( NS_HELP, "ApiEditPageTest_testEdit_redirect_r$count" );
		$rpage = $wikiPageFactory->newFromTitle( $rtitle );

		$baseTime = $page->getRevisionRecord()->getTimestamp();

		// base edit for redirect
		$rpage->doUserEditContent(
			new WikitextContent( "#REDIRECT [[{$title->getPrefixedText()}]]" ),
			$this->getTestSysop()->getUser(),
			"testing 1",
			EDIT_NEW
		);
		$this->forceRevisionDate( $rpage, '20120101000000' );

		// conflicting edit to redirect
		$rpage->doUserEditContent(
			new WikitextContent( "#REDIRECT [[{$title->getPrefixedText()}]]\n\n[[Category:Test]]" ),
			$this->getTestUser()->getUser(),
			"testing 2",
			EDIT_UPDATE
		);
		$this->forceRevisionDate( $rpage, '20120101020202' );

		// try to save edit, following the redirect
		[ $re, , ] = $this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $rtitle->getPrefixedText(),
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
		$title = Title::makeTitle( NS_HELP, "ApiEditPageTest_testEdit_redirectText_$count" );
		$wikiPageFactory = $this->getServiceContainer()->getWikiPageFactory();
		$page = $this->getExistingTestPage( $title );
		$this->forceRevisionDate( $page, '20120101000000' );
		$baseTime = $page->getRevisionRecord()->getTimestamp();

		$rtitle = Title::makeTitle( NS_HELP, "ApiEditPageTest_testEdit_redirectText_r$count" );
		$rpage = $wikiPageFactory->newFromTitle( $rtitle );

		// base edit for redirect
		$rpage->doUserEditContent(
			new WikitextContent( "#REDIRECT [[{$title->getPrefixedText()}]]" ),
			$this->getTestSysop()->getUser(),
			"testing 1",
			EDIT_NEW
		);
		$this->forceRevisionDate( $rpage, '20120101000000' );

		// conflicting edit to redirect
		$rpage->doUserEditContent(
			new WikitextContent( "#REDIRECT [[{$title->getPrefixedText()}]]\n\n[[Category:Test]]" ),
			$this->getTestUser()->getUser(),
			"testing 2",
			EDIT_UPDATE
		);
		$this->forceRevisionDate( $rpage, '20120101020202' );

		// try to save edit, following the redirect but without creating a section
		try {
			$this->doApiRequestWithToken( [
				'action' => 'edit',
				'title' => $rtitle->getPrefixedText(),
				'text' => 'nix bar!',
				'basetimestamp' => $baseTime,
				'redirect' => true,
			] );

			$this->fail( 'redirect-appendonly error expected' );
		} catch ( ApiUsageException $ex ) {
			$this->assertApiErrorCode( 'redirect-appendonly', $ex );
		}
	}

	public function testEditConflict_revid() {
		static $count = 0;
		$count++;

		// assume NS_HELP defaults to wikitext
		$title = Title::makeTitle( NS_HELP, "ApiEditPageTest_testEditConflict_$count" );

		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );

		// base edit
		$page->doUserEditContent(
			new WikitextContent( "Foo" ),
			$this->getTestSysop()->getUser(),
			"testing 1",
			EDIT_NEW
		);
		$this->forceRevisionDate( $page, '20120101000000' );
		$baseId = $page->getRevisionRecord()->getId();

		// conflicting edit
		$page->doUserEditContent(
			new WikitextContent( "Foo bar" ),
			$this->getTestUser()->getUser(),
			"testing 2",
			EDIT_UPDATE
		);
		$this->forceRevisionDate( $page, '20120101020202' );

		// try to save edit, expect conflict
		try {
			$this->doApiRequestWithToken( [
				'action' => 'edit',
				'title' => $title->getPrefixedText(),
				'text' => 'nix bar!',
				'baserevid' => $baseId,
			], null, $this->getTestSysop()->getUser() );

			$this->fail( 'edit conflict expected' );
		} catch ( ApiUsageException $ex ) {
			$this->assertApiErrorCode( 'editconflict', $ex );
		}
	}

	public function testEditConflict_timestamp() {
		static $count = 0;
		$count++;

		// assume NS_HELP defaults to wikitext
		$title = Title::makeTitle( NS_HELP, "ApiEditPageTest_testEditConflict_$count" );

		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );

		// base edit
		$page->doUserEditContent(
			new WikitextContent( "Foo" ),
			$this->getTestSysop()->getUser(),
			"testing 1",
			EDIT_NEW
		);
		$this->forceRevisionDate( $page, '20120101000000' );
		$baseTime = $page->getRevisionRecord()->getTimestamp();

		// conflicting edit
		$page->doUserEditContent(
			new WikitextContent( "Foo bar" ),
			$this->getTestUser()->getUser(),
			"testing 2",
			EDIT_UPDATE
		);
		$this->forceRevisionDate( $page, '20120101020202' );

		// try to save edit, expect conflict
		try {
			$this->doApiRequestWithToken( [
				'action' => 'edit',
				'title' => $title->getPrefixedText(),
				'text' => 'nix bar!',
				'basetimestamp' => $baseTime,
			] );

			$this->fail( 'edit conflict expected' );
		} catch ( ApiUsageException $ex ) {
			$this->assertApiErrorCode( 'editconflict', $ex );
		}
	}

	/**
	 * Ensure that editing using section=new will prevent simple conflicts
	 */
	public function testEditConflict_newSection() {
		static $count = 0;
		$count++;

		// assume NS_HELP defaults to wikitext
		$title = Title::makeTitle( NS_HELP, "ApiEditPageTest_testEditConflict_newSection_$count" );

		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );

		// base edit
		$page->doUserEditContent(
			new WikitextContent( "Foo" ),
			$this->getTestSysop()->getUser(),
			"testing 1",
			EDIT_NEW
		);
		$this->forceRevisionDate( $page, '20120101000000' );
		$baseTime = $page->getRevisionRecord()->getTimestamp();

		// conflicting edit
		$page->doUserEditContent(
			new WikitextContent( "Foo bar" ),
			$this->getTestUser()->getUser(),
			"testing 2",
			EDIT_UPDATE
		);
		$this->forceRevisionDate( $page, '20120101020202' );

		// try to save edit, expect no conflict
		[ $re, , ] = $this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $title->getPrefixedText(),
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
		$title = Title::makeTitle( NS_HELP, "ApiEditPageTest_testEditConflict_redirect_T43990_$count" );
		$wikiPageFactory = $this->getServiceContainer()->getWikiPageFactory();
		$page = $this->getExistingTestPage( $title );
		$this->forceRevisionDate( $page, '20120101000000' );

		$rtitle = Title::makeTitle( NS_HELP, "ApiEditPageTest_testEditConflict_redirect_T43990_r$count" );
		$rpage = $wikiPageFactory->newFromTitle( $rtitle );

		// base edit for redirect
		$rpage->doUserEditContent(
			new WikitextContent( "#REDIRECT [[{$title->getPrefixedText()}]]" ),
			$this->getTestSysop()->getUser(),
			"testing 1",
			EDIT_NEW
		);
		$this->forceRevisionDate( $rpage, '20120101000000' );

		// new edit to content
		$page->doUserEditContent(
			new WikitextContent( "Foo bar" ),
			$this->getTestUser()->getUser(),
			"testing 2",
			EDIT_UPDATE
		);
		$this->forceRevisionDate( $rpage, '20120101020202' );

		// try to save edit; should work, following the redirect.
		[ $re, , ] = $this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $rtitle->getPrefixedText(),
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
		$dbw = $this->getDb();

		$dbw->newUpdateQueryBuilder()
			->update( 'revision' )
			->set( [ 'rev_timestamp' => $dbw->timestamp( $timestamp ) ] )
			->where( [ 'rev_id' => $page->getLatest() ] )
			->caller( __METHOD__ )->execute();

		$page->clear();
	}

	public function testCheckDirectApiEditingDisallowed_forNonTextContent() {
		$this->expectApiErrorCode( 'no-direct-editing' );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => 'Dummy:ApiEditPageTest_nonTextPageEdit',
			'text' => '{"animals":["kittens!"]}'
		] );
	}

	public function testSupportsDirectApiEditing_withContentHandlerOverride() {
		$name = 'DummyNonText:ApiEditPageTest_testNonTextEdit';
		$data = 'some bla bla text';

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
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( Title::newFromText( $name ) );
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
		$sysop = $this->getTestSysop()->getUser();
		$otherUser = $this->getTestUser()->getUser();

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
		], null, $otherUser )[0];

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
		$title = Title::makeTitle( NS_HELP, 'TestCorrectContentFormat' );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $title->getPrefixedText(),
			'text' => 'some text',
			'contentmodel' => 'wikitext',
			'contentformat' => 'text/x-wiki',
		] );

		$this->assertTrue( $title->exists( IDBAccessObject::READ_LATEST ) );
	}

	public function testUnsupportedContentFormat() {
		$title = Title::makeTitle( NS_HELP, 'TestUnsupportedContentFormat' );

		$this->expectApiErrorCode( 'badvalue' );

		try {
			$this->doApiRequestWithToken( [
				'action' => 'edit',
				'title' => $title->getPrefixedText(),
				'text' => 'some text',
				'contentformat' => 'nonexistent format',
			] );
		} finally {
			$this->assertFalse( $title->exists( IDBAccessObject::READ_LATEST ) );
		}
	}

	public function testMismatchedContentFormat() {
		$title = Title::makeTitle( NS_HELP, 'TestMismatchedContentFormat' );

		$this->expectApiErrorCode( 'badformat' );

		try {
			$this->doApiRequestWithToken( [
				'action' => 'edit',
				'title' => $title->getPrefixedText(),
				'text' => 'some text',
				'contentmodel' => 'wikitext',
				'contentformat' => 'text/plain',
			] );
		} finally {
			$this->assertFalse( $title->exists( IDBAccessObject::READ_LATEST ) );
		}
	}

	public function testUndoToInvalidRev() {
		$title = Title::makeTitle( NS_HELP, 'TestUndoToInvalidRev' );

		$revId = $this->editPage( $title, 'Some text' )->getNewRevision()
			->getId();
		$revId++;

		$this->expectApiErrorCode( 'nosuchrevid' );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $title->getPrefixedText(),
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
		$page = $this->getServiceContainer()->getWikiPageFactory()
			->newFromLinkTarget( new TitleValue( NS_HELP, 'TestUndoAfterToInvalidRev' ) );

		$revId1 = $this->editPage( $page, '1' )->getNewRevision()->getId();
		$revId2 = $this->editPage( $page, '2' )->getNewRevision()->getId();
		$revId3 = $this->editPage( $page, '3' )->getNewRevision()->getId();

		// Make the middle revision disappear
		$dbw = $this->getDb();
		$dbw->newDeleteQueryBuilder()
			->deleteFrom( 'revision' )
			->where( [ 'rev_id' => $revId2 ] )
			->caller( __METHOD__ )->execute();
		$dbw->newUpdateQueryBuilder()
			->update( 'revision' )
			->set( [ 'rev_parent_id' => $revId1 ] )
			->where( [ 'rev_id' => $revId3 ] )
			->caller( __METHOD__ )->execute();

		$this->expectApiErrorCode( 'nosuchrevid' );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $page->getTitle()->getPrefixedText(),
			'undo' => $revId3,
			'undoafter' => $revId2,
		] );
	}

	/**
	 * Tests what happens if the undo parameter is a valid revision, but
	 * undoafter is hidden (rev_deleted).
	 */
	public function testUndoAfterToHiddenRev() {
		$page = $this->getServiceContainer()->getWikiPageFactory()
			->newFromLinkTarget( new TitleValue( NS_HELP, 'TestUndoAfterToHiddenRev' ) );
		$titleObj = $page->getTitle();

		$this->editPage( $page, '0' );

		$revId1 = $this->editPage( $page, '1' )->getNewRevision()->getId();

		$revId2 = $this->editPage( $page, '2' )->getNewRevision()->getId();

		// Hide the middle revision
		$list = RevisionDeleter::createList( 'revision',
			RequestContext::getMain(), $titleObj, [ $revId1 ] );
		// Set a user for modifying the visibility, this is needed because
		// setVisibility generates a log, which cannot be an anonymous user actor
		// when temporary accounts are enabled.
		RequestContext::getMain()->setUser( $this->getTestUser()->getUser() );
		$list->setVisibility( [
			'value' => [ RevisionRecord::DELETED_TEXT => 1 ],
			'comment' => 'Bye-bye',
		] );

		$this->expectApiErrorCode( 'nosuchrevid' );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $titleObj->getPrefixedText(),
			'undo' => $revId2,
			'undoafter' => $revId1,
		] );
	}

	/**
	 * Test undo when a revision with a higher id has an earlier timestamp.
	 * This can happen if importing an old revision.
	 */
	public function testUndoWithSwappedRevisions() {
		$this->markTestSkippedIfNoDiff3();

		$page = $this->getServiceContainer()->getWikiPageFactory()
			->newFromLinkTarget( new TitleValue( NS_HELP, 'TestUndoWithSwappedRevisions' ) );
		$this->editPage( $page, '0' );

		$revId2 = $this->editPage( $page, '2' )->getNewRevision()->getId();

		$revId1 = $this->editPage( $page, '1' )->getNewRevision()->getId();

		// Now monkey with the timestamp
		$dbw = $this->getDb();
		$dbw->newUpdateQueryBuilder()
			->update( 'revision' )
			->set( [ 'rev_timestamp' => $dbw->timestamp( time() - 86400 ) ] )
			->where( [ 'rev_id' => $revId1 ] )
			->caller( __METHOD__ )->execute();

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $page->getTitle()->getPrefixedText(),
			'undo' => $revId2,
			'undoafter' => $revId1,
		] );

		$page->loadPageData( IDBAccessObject::READ_LATEST );
		$this->assertSame( '1', $page->getContent()->getText() );
	}

	public function testUndoWithConflicts() {
		$this->expectApiErrorCode( 'undofailure' );

		$page = $this->getServiceContainer()->getWikiPageFactory()
			->newFromLinkTarget( new TitleValue( NS_HELP, 'TestUndoWithConflicts' ) );
		$this->editPage( $page, '1' );

		$revId = $this->editPage( $page, '2' )->getNewRevision()->getId();

		$this->editPage( $page, '3' );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $page->getTitle()->getPrefixedText(),
			'undo' => $revId,
		] );

		$page->loadPageData( IDBAccessObject::READ_LATEST );
		$this->assertSame( '3', $page->getContent()->getText() );
	}

	public function testReversedUndoAfter() {
		$this->markTestSkippedIfNoDiff3();

		$page = $this->getServiceContainer()->getWikiPageFactory()
			->newFromLinkTarget( new TitleValue( NS_HELP, 'TestReversedUndoAfter' ) );
		$this->editPage( $page, '0' );
		$revId1 = $this->editPage( $page, '1' )->getNewRevision()->getId();
		$revId2 = $this->editPage( $page, '2' )->getNewRevision()->getId();

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $page->getTitle()->getPrefixedText(),
			'undo' => $revId1,
			'undoafter' => $revId2,
		] );

		$page->loadPageData( IDBAccessObject::READ_LATEST );
		$this->assertSame( '2', $page->getContent()->getText() );
	}

	public function testUndoToRevFromDifferentPage() {
		$title1 = Title::makeTitle( NS_HELP, 'TestUndoToRevFromDifferentPage-1' );
		$this->editPage( $title1, 'Some text' );
		$revId = $this->editPage( $title1, 'Some more text' )
			->getNewRevision()->getId();

		$title2 = Title::makeTitle( NS_HELP, 'TestUndoToRevFromDifferentPage-2' );
		$this->editPage( $title2, 'Some text' );

		$this->expectApiErrorCode( 'revwrongpage' );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $title2->getPrefixedText(),
			'undo' => $revId,
		] );
	}

	public function testUndoAfterToRevFromDifferentPage() {
		$title1 = Title::makeTitle( NS_HELP, 'TestUndoAfterToRevFromDifferentPage-1' );
		$revId1 = $this->editPage( $title1, 'Some text' )
			->getNewRevision()->getId();

		$title2 = Title::makeTitle( NS_HELP, 'TestUndoAfterToRevFromDifferentPage-2' );
		$revId2 = $this->editPage( $title2, 'Some text' )
			->getNewRevision()->getId();

		$this->expectApiErrorCode( 'revwrongpage' );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $title2->getPrefixedText(),
			'undo' => $revId2,
			'undoafter' => $revId1,
		] );
	}

	public function testMd5Text() {
		$title = Title::makeTitle( NS_HELP, 'TestMd5Text' );

		$this->assertFalse( $title->exists( IDBAccessObject::READ_LATEST ) );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $title->getPrefixedText(),
			'text' => 'Some text',
			'md5' => md5( 'Some text' ),
		] );

		$this->assertTrue( $title->exists( IDBAccessObject::READ_LATEST ) );
	}

	public function testMd5PrependText() {
		$title = Title::makeTitle( NS_HELP, 'TestMd5PrependText' );

		$this->editPage( $title, 'Some text' );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $title->getPrefixedText(),
			'prependtext' => 'Alert: ',
			'md5' => md5( 'Alert: ' ),
		] );

		$text = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title )
			->getContent()->getText();
		$this->assertSame( 'Alert: Some text', $text );
	}

	public function testMd5AppendText() {
		$title = Title::makeTitle( NS_HELP, 'TestMd5AppendText' );

		$this->editPage( $title, 'Some text' );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $title->getPrefixedText(),
			'appendtext' => ' is nice',
			'md5' => md5( ' is nice' ),
		] );

		$text = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title )
			->getContent()->getText();
		$this->assertSame( 'Some text is nice', $text );
	}

	public function testMd5PrependAndAppendText() {
		$title = Title::makeTitle( NS_HELP, 'TestMd5PrependAndAppendText' );

		$this->editPage( $title, 'Some text' );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $title->getPrefixedText(),
			'prependtext' => 'Alert: ',
			'appendtext' => ' is nice',
			'md5' => md5( 'Alert:  is nice' ),
		] );

		$text = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title )
			->getContent()->getText();
		$this->assertSame( 'Alert: Some text is nice', $text );
	}

	public function testIncorrectMd5Text() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );

		$this->expectApiErrorCode( 'badmd5' );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'text' => 'Some text',
			'md5' => md5( '' ),
		] );
	}

	public function testIncorrectMd5PrependText() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );

		$this->expectApiErrorCode( 'badmd5' );

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

		$this->expectApiErrorCode( 'badmd5' );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'prependtext' => 'Some ',
			'appendtext' => 'text',
			'md5' => md5( 'text' ),
		] );
	}

	public function testCreateOnly() {
		$title = Title::makeTitle( NS_HELP, 'TestCreateOnly' );

		$this->expectApiErrorCode( 'articleexists' );

		$this->editPage( $title, 'Some text' );
		$this->assertTrue( $title->exists( IDBAccessObject::READ_LATEST ) );

		try {
			$this->doApiRequestWithToken( [
				'action' => 'edit',
				'title' => $title->getPrefixedText(),
				'text' => 'Some more text',
				'createonly' => '',
			] );
		} finally {
			// Validate that content was not changed
			$text = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title )
				->getContent()->getText();

			$this->assertSame( 'Some text', $text );
		}
	}

	public function testNoCreate() {
		$title = Title::makeTitle( NS_HELP, 'TestNoCreate' );

		$this->expectApiErrorCode( 'missingtitle' );

		$this->assertFalse( $title->exists( IDBAccessObject::READ_LATEST ) );

		try {
			$this->doApiRequestWithToken( [
				'action' => 'edit',
				'title' => $title->getPrefixedText(),
				'text' => 'Some text',
				'nocreate' => '',
			] );
		} finally {
			$this->assertFalse( $title->exists( IDBAccessObject::READ_LATEST ) );
		}
	}

	/**
	 * Appending/prepending is currently only supported for TextContent.  We
	 * test this right now, and when support is added this test should be
	 * replaced by tests that the support is correct.
	 */
	public function testAppendWithNonTextContentHandler() {
		$name = 'MediaWiki:' . ucfirst( __FUNCTION__ );

		$this->expectApiErrorCode( 'appendnotsupported' );

		$this->setTemporaryHook( 'ContentHandlerDefaultModelFor',
			static function ( Title $title, &$model ) use ( $name ) {
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
		$title = Title::makeTitle( NS_MEDIAWIKI, 'TestAppendInMediaWikiNamespace' );

		$this->assertFalse( $title->exists( IDBAccessObject::READ_LATEST ) );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $title->getPrefixedText(),
			'appendtext' => 'Some text',
		] );

		$this->assertTrue( $title->exists( IDBAccessObject::READ_LATEST ) );
	}

	public function testAppendInMediaWikiNamespaceWithSerializationError() {
		$name = 'MediaWiki:' . ucfirst( __FUNCTION__ );

		$this->expectApiErrorCode( 'parseerror' );

		$this->setTemporaryHook( 'ContentHandlerDefaultModelFor',
			static function ( Title $title, &$model ) use ( $name ) {
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
		$title = Title::makeTitle( NS_HELP, 'TestAppendNewSection' );

		$this->editPage( $title, 'Initial content' );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $title->getPrefixedText(),
			'appendtext' => '== New section ==',
			'section' => 'new',
		] );

		$text = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title )
			->getContent()->getText();

		$this->assertSame( "Initial content\n\n== New section ==", $text );
	}

	public function testAppendNewSectionWithInvalidContentModel() {
		$title = Title::makeTitle( NS_HELP, 'TestAppendNewSectionWithInvalidContentModel' );

		$this->expectApiErrorCode( 'sectionsnotsupported' );

		$this->editPage( $title, 'Initial content' );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $title->getPrefixedText(),
			'appendtext' => '== New section ==',
			'section' => 'new',
			'contentmodel' => 'text',
		] );
	}

	public function testAppendNewSectionWithTitle() {
		$title = Title::makeTitle( NS_HELP, 'TestAppendNewSectionWithTitle' );

		$this->editPage( $title, 'Initial content' );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $title->getPrefixedText(),
			'sectiontitle' => 'My section',
			'appendtext' => 'More content',
			'section' => 'new',
		] );

		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );

		$this->assertSame( "Initial content\n\n== My section ==\n\nMore content",
			$page->getContent()->getText() );
		$comment = $page->getRevisionRecord()->getComment();
		$this->assertInstanceOf( CommentStoreComment::class, $comment );
		$this->assertSame( '/* My section */ new section', $comment->text );
	}

	public function testAppendNewSectionWithSummary() {
		$title = Title::makeTitle( NS_HELP, 'TestAppendNewSectionWithSummary' );

		$this->editPage( $title, 'Initial content' );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $title->getPrefixedText(),
			'appendtext' => 'More content',
			'section' => 'new',
			'summary' => 'Add new section',
		] );

		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );

		$this->assertSame( "Initial content\n\n== Add new section ==\n\nMore content",
			$page->getContent()->getText() );
		// EditPage actually assumes the summary is the section name here
		$comment = $page->getRevisionRecord()->getComment();
		$this->assertInstanceOf( CommentStoreComment::class, $comment );
		$this->assertSame( '/* Add new section */ new section', $comment->text );
	}

	public function testAppendNewSectionWithTitleAndSummary() {
		$title = Title::makeTitle( NS_HELP, 'TestAppendNewSectionWithTitleAndSummary' );

		$this->editPage( $title, 'Initial content' );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $title->getPrefixedText(),
			'sectiontitle' => 'My section',
			'appendtext' => 'More content',
			'section' => 'new',
			'summary' => 'Add new section',
		] );

		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );

		$this->assertSame( "Initial content\n\n== My section ==\n\nMore content",
			$page->getContent()->getText() );
		$comment = $page->getRevisionRecord()->getComment();
		$this->assertInstanceOf( CommentStoreComment::class, $comment );
		$this->assertSame( 'Add new section', $comment->text );
	}

	public function testAppendToSection() {
		$title = Title::makeTitle( NS_HELP, 'TestAppendToSection' );

		$this->editPage( $title, "== Section 1 ==\n\nContent\n\n" .
			"== Section 2 ==\n\nFascinating!" );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $title->getPrefixedText(),
			'appendtext' => ' and more content',
			'section' => '1',
		] );

		$text = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title )
			->getContent()->getText();

		$this->assertSame( "== Section 1 ==\n\nContent and more content\n\n" .
			"== Section 2 ==\n\nFascinating!", $text );
	}

	public function testAppendToFirstSection() {
		$title = Title::makeTitle( NS_HELP, 'TestAppendToFirstSection' );

		$this->editPage( $title, "Content\n\n== Section 1 ==\n\nFascinating!" );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $title->getPrefixedText(),
			'appendtext' => ' and more content',
			'section' => '0',
		] );

		$text = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title )
			->getContent()->getText();

		$this->assertSame( "Content and more content\n\n== Section 1 ==\n\n" .
			"Fascinating!", $text );
	}

	public function testAppendToNonexistentSection() {
		$title = Title::makeTitle( NS_HELP, 'TestAppendToNonexistentSection' );

		$this->expectApiErrorCode( 'nosuchsection' );

		$this->editPage( $title, 'Content' );

		try {
			$this->doApiRequestWithToken( [
				'action' => 'edit',
				'title' => $title->getPrefixedText(),
				'appendtext' => ' and more content',
				'section' => '1',
			] );
		} finally {
			$text = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title )
				->getContent()->getText();

			$this->assertSame( 'Content', $text );
		}
	}

	public function testEditMalformedSection() {
		$title = Title::makeTitle( NS_HELP, 'TestEditMalformedSection' );

		$this->expectApiErrorCode( 'invalidsection' );
		$this->editPage( $title, 'Content' );

		try {
			$this->doApiRequestWithToken( [
				'action' => 'edit',
				'title' => $title->getPrefixedText(),
				'text' => 'Different content',
				'section' => 'It is unlikely that this is valid',
			] );
		} finally {
			$text = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title )
				->getContent()->getText();

			$this->assertSame( 'Content', $text );
		}
	}

	public function testEditWithStartTimestamp() {
		$title = Title::makeTitle( NS_HELP, 'TestEditWithStartTimestamp' );
		$this->expectApiErrorCode( 'pagedeleted' );

		$startTime = MWTimestamp::convert( TS_MW, time() - 1 );

		$this->editPage( $title, 'Some text' );

		$pageObj = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );
		$this->deletePage( $pageObj );

		$this->assertFalse( $pageObj->exists() );

		try {
			$this->doApiRequestWithToken( [
				'action' => 'edit',
				'title' => $title->getPrefixedText(),
				'text' => 'Different text',
				'starttimestamp' => $startTime,
			] );
		} finally {
			$this->assertFalse( $pageObj->exists() );
		}
	}

	public function testEditMinor() {
		$title = Title::makeTitle( NS_HELP, 'TestEditMinor' );

		$this->editPage( $title, 'Some text' );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $title->getPrefixedText(),
			'text' => 'Different text',
			'minor' => '',
		] );

		$revisionStore = $this->getServiceContainer()->getRevisionStore();
		$revision = $revisionStore->getRevisionByTitle( $title );
		$this->assertTrue( $revision->isMinor() );
	}

	public function testEditRecreate() {
		$title = Title::makeTitle( NS_HELP, 'TestEditRecreate' );

		$startTime = MWTimestamp::convert( TS_MW, time() - 1 );

		$this->editPage( $title, 'Some text' );

		$pageObj = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );
		$this->deletePage( $pageObj );

		$this->assertFalse( $pageObj->exists() );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $title->getPrefixedText(),
			'text' => 'Different text',
			'starttimestamp' => $startTime,
			'recreate' => '',
		] );

		$this->assertTrue( $title->exists( IDBAccessObject::READ_LATEST ) );
	}

	public function testEditWatch() {
		$title = Title::makeTitle( NS_HELP, 'TestEditWatch' );
		$user = $this->getTestSysop()->getUser();
		$watchlistManager = $this->getServiceContainer()->getWatchlistManager();

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $title->getPrefixedText(),
			'text' => 'Some text',
			'watch' => '',
			'watchlistexpiry' => '1 week',
		] );

		$this->assertTrue( $title->exists( IDBAccessObject::READ_LATEST ) );
		$this->assertTrue( $watchlistManager->isWatched( $user, $title ) );
		$this->assertTrue( $watchlistManager->isTempWatched( $user, $title ) );
	}

	public function testEditUnwatch() {
		$title = Title::makeTitle( NS_HELP, 'TestEditUnwatch' );
		$user = $this->getTestSysop()->getUser();

		$watchlistManager = $this->getServiceContainer()->getWatchlistManager();
		$watchlistManager->addWatch( $user, $title );

		$this->assertFalse( $title->exists() );
		$this->assertTrue( $watchlistManager->isWatched( $user, $title ) );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $title->getPrefixedText(),
			'text' => 'Some text',
			'unwatch' => '',
		] );

		$this->assertTrue( $title->exists( IDBAccessObject::READ_LATEST ) );
		$this->assertFalse( $watchlistManager->isWatched( $user, $title ) );
	}

	public function testEditWithTag() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );

		$this->getServiceContainer()->getChangeTagsStore()->defineTag( 'custom tag' );

		$revId = $this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'text' => 'Some text',
			'tags' => 'custom tag',
		] )[0]['edit']['newrevid'];

		$this->assertSame( 'custom tag', $this->getDb()->newSelectQueryBuilder()
			->select( 'ctd_name' )
			->from( 'change_tag' )
			->join( 'change_tag_def', null, 'ctd_id = ct_tag_id' )
			->where( [ 'ct_rev_id' => $revId ] )
			->caller( __METHOD__ )->fetchField() );
	}

	public function testEditWithoutTagPermission() {
		$title = Title::makeTitle( NS_HELP, 'TestEditWithoutTagPermission' );

		$this->expectApiErrorCode( 'tags-apply-no-permission' );

		$this->assertFalse( $title->exists( IDBAccessObject::READ_LATEST ) );

		$this->getServiceContainer()->getChangeTagsStore()->defineTag( 'custom tag' );
		$this->overrideConfigValue(
			MainConfigNames::RevokePermissions,
			[ 'user' => [ 'applychangetags' => true ] ]
		);

		try {
			$this->doApiRequestWithToken( [
				'action' => 'edit',
				'title' => $title->getPrefixedText(),
				'text' => 'Some text',
				'tags' => 'custom tag',
			] );
		} finally {
			$this->assertFalse( $title->exists( IDBAccessObject::READ_LATEST ) );
		}
	}

	public function testEditAbortedByEditPageHookWithResult() {
		$title = Title::makeTitle( NS_HELP, 'TestEditAbortedByEditPageHookWithResult' );

		$this->setTemporaryHook( 'EditFilterMergedContent',
			static function ( $unused1, $unused2, Status $status ) {
				$status->statusData = [ 'msg' => 'A message for you!' ];
				return false;
			} );

		$res = $this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $title->getPrefixedText(),
			'text' => 'Some text',
		] );

		$this->assertFalse( $title->exists( IDBAccessObject::READ_LATEST ) );
		$this->assertSame( [ 'edit' => [ 'msg' => 'A message for you!',
			'result' => 'Failure' ] ], $res[0] );
	}

	public function testEditAbortedByEditPageHookWithNoResult() {
		$title = Title::makeTitle( NS_HELP, 'TestEditAbortedByEditPageHookWithNoResult' );

		$this->expectApiErrorCode( 'hookaborted' );

		$this->setTemporaryHook( 'EditFilterMergedContent',
			static function () {
				return false;
			}
		);

		try {
			$this->doApiRequestWithToken( [
				'action' => 'edit',
				'title' => $title->getPrefixedText(),
				'text' => 'Some text',
			] );
		} finally {
			$this->assertFalse( $title->exists( IDBAccessObject::READ_LATEST ) );
		}
	}

	public function testEditWhileBlocked() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );

		$blockStore = $this->getServiceContainer()->getDatabaseBlockStore();
		$this->assertNull( $blockStore->newFromTarget( '127.0.0.1' ) );

		$user = $this->getTestSysop()->getUser();
		$blockStore->insertBlockWithParams( [
			'address' => $user->getName(),
			'by' => $user,
			'reason' => 'Capriciousness',
			'timestamp' => '19370101000000',
			'expiry' => 'infinity',
			'enableAutoblock' => true,
		] );

		try {
			$this->doApiRequestWithToken( [
				'action' => 'edit',
				'title' => $name,
				'text' => 'Some text',
			] );
			$this->fail( 'Expected exception not thrown' );
		} catch ( ApiUsageException $ex ) {
			$this->assertApiErrorCode( 'blocked', $ex );
			$this->assertNotNull( $blockStore->newFromTarget( '127.0.0.1' ), 'Autoblock spread' );
		}
	}

	public function testEditWhileReadOnly() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );

		// Create the test user before making the DB readonly
		$user = $this->getTestSysop()->getUser();
		$this->expectApiErrorCode( 'readonly' );

		$svc = $this->getServiceContainer()->getReadOnlyMode();
		$svc->setReason( "Read-only for testing" );

		try {
			$this->doApiRequestWithToken( [
				'action' => 'edit',
				'title' => $name,
				'text' => 'Some text',
			], null, $user );
		} finally {
			$svc->setReason( false );
		}
	}

	public function testCreateImageRedirectAnon() {
		$this->disableAutoCreateTempUser();
		$name = 'File:' . ucfirst( __FUNCTION__ );

		$this->expectApiErrorCode( 'noimageredirect-anon' );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'text' => '#REDIRECT [[File:Other file.png]]',
		], null, new User() );
	}

	public function testCreateImageRedirectLoggedIn() {
		$name = 'File:' . ucfirst( __FUNCTION__ );

		$this->expectApiErrorCode( 'noimageredirect' );

		$this->overrideConfigValue(
			MainConfigNames::RevokePermissions,
			[ 'user' => [ 'upload' => true ] ]
		);

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'text' => '#REDIRECT [[File:Other file.png]]',
		] );
	}

	public function testTooBigEdit() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );

		$this->expectApiErrorCode( 'contenttoobig' );

		$this->overrideConfigValue( MainConfigNames::MaxArticleSize, 1 );

		$text = str_repeat( '!', 1025 );

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'text' => $text,
		] );
	}

	public function testProhibitedAnonymousEdit() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );

		$this->expectApiErrorCode( 'permissiondenied' );

		$this->overrideConfigValue(
			MainConfigNames::RevokePermissions,
			[ '*' => [ 'edit' => true ] ]
		);

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'text' => 'Some text',
		], null, new User() );
	}

	public function testProhibitedChangeContentModel() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );

		$this->expectApiErrorCode( 'cantchangecontentmodel' );

		$this->overrideConfigValue(
			MainConfigNames::RevokePermissions,
			[ 'user' => [ 'editcontentmodel' => true ] ]
		);

		$this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $name,
			'text' => 'Some text',
			'contentmodel' => 'json',
		] );
	}

	public function testMidEditContentModelMismatch() {
		$title = Title::makeTitle( NS_HELP, 'TestMidEditContentModelMismatch' );

		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );

		// base edit, currently in Wikitext
		$page->doUserEditContent(
			new WikitextContent( "Foo" ),
			$this->getTestSysop()->getUser(),
			"testing 1",
			EDIT_NEW
		);
		$this->forceRevisionDate( $page, '20120101000000' );
		$baseId = $page->getRevisionRecord()->getId();

		// Attempt edit in Javascript. This may happen, for instance, if we
		// started editing the base content while it was in Javascript and
		// before we save it was changed to Wikitext (base edit model).
		$page->doUserEditContent(
			new JavaScriptContent( "Bar" ),
			$this->getTestUser()->getUser(),
			"testing 2",
			EDIT_UPDATE
		);
		$this->forceRevisionDate( $page, '20120101020202' );

		// ContentHandler may throw exception if we attempt saving the above, so we will
		// handle that with contentmodel-mismatch error. Test this is the case.
		try {
			$this->doApiRequestWithToken( [
				'action' => 'edit',
				'title' => $title->getPrefixedText(),
				'text' => 'different content models!',
				'baserevid' => $baseId,
			] );
			$this->fail( "Should have raised an ApiUsageException" );
		} catch ( ApiUsageException $e ) {
			$this->assertApiErrorCode( 'contentmodel-mismatch', $e );
		}
	}

	public function testEditWithWatchlistExpiry(): void {
		ConvertibleTimestamp::setFakeTime( '20240201000000' );
		$user = $this->getTestUser()->getUser();
		$watchlistManager = $this->getServiceContainer()->getWatchlistManager();
		$mockUserOptionsLookup = new StaticUserOptionsLookup( [
			$user->getName() => [
				'watchdefault' => '1',
				'watchdefault-expiry' => '1 week',
				'watchcreations' => '1',
				'watchcreations-expiry' => '1 month'
			],
		] );
		$this->setService( 'UserOptionsLookup', $mockUserOptionsLookup );

		$title = Title::makeTitle( NS_HELP, 'TestEditWithWatchlistExpiry' );
		$apiResult = $this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $title,
			'text' => 'some text'
		], null, $user );

		$this->assertSame(
			'2024-03-01T00:00:00Z',
			$apiResult[0]['edit']['watchlistexpiry'],
			'Watchlist expiry is 1 month for new pages'
		);
		$this->assertTrue( $title->exists( IDBAccessObject::READ_LATEST ) );
		$this->assertTrue( $watchlistManager->isWatched( $user, $title ) );
		$this->assertTrue( $watchlistManager->isTempWatched( $user, $title ) );

		// Unwatch, then edit again. Watchlist expiry should be just 1 week.
		$watchlistManager->removeWatch( $user, $title );
		$this->assertFalse( $watchlistManager->isWatched( $user, $title ) );

		$apiResult = $this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => $title,
			'text' => 'some more text'
		], null, $user );

		$this->assertSame(
			'2024-02-08T00:00:00Z',
			$apiResult[0]['edit']['watchlistexpiry'],
			'Watchlist expiry is 1 week for existing pages'
		);
		// The above proves the API is acting as it should, however at this point
		// $watchlistManager->isWatched( $user, $title ) will incorrectly return false.
		// This is most likely due to the process cache, which has had some known issues (T259379).
		// There's no user-facing way to watch/unwatch all in one process,
		// so this shouldn't be an actual problem.
	}
}
