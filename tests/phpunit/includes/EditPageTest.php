<?php

/**
 * @group Editing
 *
 * @group Database
 *        ^--- tell jenkins this test needs the database
 *
 * @group medium
 *        ^--- tell phpunit that these test cases may take longer than 2 seconds.
 */
class EditPageTest extends MediaWikiLangTestCase {

	/**
	 * @dataProvider provideExtractSectionTitle
	 * @covers EditPage::extractSectionTitle
	 */
	public function testExtractSectionTitle( $section, $title ) {
		$extracted = EditPage::extractSectionTitle( $section );
		$this->assertEquals( $title, $extracted );
	}

	public static function provideExtractSectionTitle() {
		return array(
			array(
				"== Test ==\n\nJust a test section.",
				"Test"
			),
			array(
				"An initial section, no header.",
				false
			),
			array(
				"An initial section with a fake heder (bug 32617)\n\n== Test == ??\nwtf",
				false
			),
			array(
				"== Section ==\nfollowed by a fake == Non-section == ??\nnoooo",
				"Section"
			),
			array(
				"== Section== \t\r\n followed by whitespace (bug 35051)",
				'Section',
			),
		);
	}

	/**
	 * User input text is passed to rtrim() by edit page. This is a simple
	 * wrapper around assertEquals() which calls rrtrim() to normalize the
	 * expected and actual texts.
	 */
	protected function assertEditedTextEquals( $expected, $actual, $msg = '' ) {
		return $this->assertEquals( rtrim( $expected ), rtrim( $actual ), $msg );
	}

	/**
	 * Performs an edit and checks the result.
	 *
	 * @param string|Title $title The title of the page to edit
	 * @param string|null $baseText Some text to create the page with before attempting the edit.
	 * @param User|string|null $user The user to perform the edit as.
	 * @param array $edit An array of request parameters used to define the edit to perform.
	 *              Some well known fields are:
	 *              * wpTextbox1: the text to submit
	 *              * wpSummary: the edit summary
	 *              * wpEditToken: the edit token (will be inserted if not provided)
	 *              * wpSectionTitle: the section to edit
	 *              * wpMinorEdit: mark as minor edit
	 *              * wpWatchthis: whether to watch the page
	 *              * oldid: base revision ID for this edit
	 * @param int|null $expectedCode The expected result code (EditPage::AS_XXX constants).
	 *                  Set to null to skip the check. Defaults to EditPage::AS_OK.
	 * @param string|null $expectedText The text expected to be on the page after the edit.
	 *                  Set to null to skip the check.
	 * @param string|null $message An optional message to show along with any error message.
	 *
	 * @return WikiPage The page that was just edited, useful for getting the edit's rev_id, etc.
	 */
	protected function assertEdit( $title, $baseText, $user = null, array $edit,
		$expectedCode = EditPage::AS_OK, $expectedText = null, $message = null
	) {
		if ( is_string( $title ) ) {
			$ns = $this->getDefaultWikitextNS();
			$title = Title::newFromText( $title, $ns );
		}

		if ( is_string( $user ) ) {
			$user = User::newFromName( $user );

			if ( $user->getId() === 0 ) {
				$user->addToDatabase();
			}
		}

		$page = WikiPage::factory( $title );

		if ( $baseText !== null ) {
			$content = ContentHandler::makeContent( $baseText, $title );
			$page->doEditContent( $content, "base text for test" );

			//sanity check
			$page->clear();
			$currentText = ContentHandler::getContentText( $page->getContent() );

			# EditPage rtrim() the user input, so we alter our expected text
			# to reflect that.
			$this->assertEditedTextEquals( $baseText, $currentText );
		}

		if ( !isset( $edit['oldid'] ) ) {
			$edit['oldid'] = $page->getLatest();
		}

		if ( $user == null ) {
			$user = $GLOBALS['wgUser'];
		} else {
			$this->setMwGlobals( 'wgUser', $user );
		}

		if ( !isset( $edit['wpEditToken'] ) ) {
			$edit['wpEditToken'] = $user->getEditToken();
		}

		$req = new FauxRequest( $edit, true ); // session ??

		$ep = new EditPage( new Article( $title ) );
		$ep->setBaseRevisionId( $edit['oldid'] );
		$ep->setContextTitle( $title );
		$ep->importFormData( $req );

		$bot = isset( $edit['bot'] ) ? (bool)$edit['bot'] : false;

		// this is where the edit happens!
		// Note: don't want to use EditPage::AttemptSave, because it messes with $wgOut
		// and throws exceptions like PermissionsError
		$status = $ep->internalAttemptSave( $result, $bot );

		if ( $expectedCode !== null ) {
			// check edit code
			$this->assertEquals( $expectedCode, $status->value,
				"Expected result code mismatch. $message" );
		}

		$page = WikiPage::factory( $title );

		if ( $expectedText !== null ) {
			// check resulting page text
			$content = $page->getContent();
			$text = ContentHandler::getContentText( $content );

			# EditPage rtrim() the user input, so we alter our expected text
			# to reflect that.
			$this->assertEditedTextEquals( $expectedText, $text,
				"Expected article text mismatch. $message" );
		}

		return $page;
	}

	/**
	 * @todo split into a dataprovider and test method
	 * @covers EditPage
	 */
	public function testCreatePage() {
		$this->assertEdit(
			'EditPageTest_testCreatePage',
			null,
			null,
			array(
				'wpTextbox1' => "Hello World!",
			),
			EditPage::AS_SUCCESS_NEW_ARTICLE,
			"Hello World!",
			"expected article being created"
		)->doDeleteArticleReal( 'EditPageTest_testCreatePage' );

		$this->assertEdit(
			'EditPageTest_testCreatePage',
			null,
			null,
			array(
				'wpTextbox1' => "",
			),
			EditPage::AS_BLANK_ARTICLE,
			null,
			"expected article not being created if empty"
		);

		$this->assertEdit(
			'MediaWiki:January',
			null,
			'UTSysop',
			array(
				'wpTextbox1' => "Not January",
			),
			EditPage::AS_SUCCESS_NEW_ARTICLE,
			"Not January",
			"expected MediaWiki: page being created"
		)->doDeleteArticleReal( 'EditPageTest_testCreatePage' );

		$this->assertEdit(
			'MediaWiki:EditPageTest_testCreatePage',
			null,
			'UTSysop',
			array(
				'wpTextbox1' => "",
			),
			EditPage::AS_BLANK_ARTICLE,
			null,
			"expected not-registered MediaWiki: page not being created if empty"
		);

		$this->assertEdit(
			'MediaWiki:January',
			null,
			'UTSysop',
			array(
				'wpTextbox1' => "",
			),
			EditPage::AS_SUCCESS_NEW_ARTICLE,
			"",
			"expected registered MediaWiki: page being created even if empty"
		)->doDeleteArticleReal( 'EditPageTest_testCreatePage' );

		$this->assertEdit(
			'MediaWiki:Ipb-default-expiry',
			null,
			'UTSysop',
			array(
				'wpTextbox1' => "",
			),
			EditPage::AS_BLANK_ARTICLE,
			"",
			"expected registered MediaWiki: page whose default content is empty not being created if empty"
		);

		$this->assertEdit(
			'MediaWiki:January',
			null,
			'UTSysop',
			array(
				'wpTextbox1' => "January",
			),
			EditPage::AS_BLANK_ARTICLE,
			null,
			"expected MediaWiki: page not being created if text equals default message"
		);
	}

	public function testUpdatePage() {
		$text = "one";
		$edit = array(
			'wpTextbox1' => $text,
			'wpSummary' => 'first update',
		);

		$page = $this->assertEdit( 'EditPageTest_testUpdatePage', "zero", null, $edit,
			EditPage::AS_SUCCESS_UPDATE, $text,
			"expected successfull update with given text" );

		$text = "two";
		$edit = array(
			'wpTextbox1' => $text,
			'wpSummary' => 'second update',
		);

		$this->assertEdit( 'EditPageTest_testUpdatePage', null, null, $edit,
			EditPage::AS_SUCCESS_UPDATE, $text,
			"expected successfull update with given text" );
	}

	public static function provideSectionEdit() {
		$text = 'Intro

== one ==
first section.

== two ==
second section.
';

		$sectionOne = '== one ==
hello
';

		$newSection = '== new section ==

hello
';

		$textWithNewSectionOne = preg_replace(
			'/== one ==.*== two ==/ms',
			"$sectionOne\n== two ==", $text
		);

		$textWithNewSectionAdded = "$text\n$newSection";

		return array(
			array( #0
				$text,
				'',
				'hello',
				'replace all',
				'hello'
			),

			array( #1
				$text,
				'1',
				$sectionOne,
				'replace first section',
				$textWithNewSectionOne,
			),

			array( #2
				$text,
				'new',
				'hello',
				'new section',
				$textWithNewSectionAdded,
			),
		);
	}

	/**
	 * @dataProvider provideSectionEdit
	 * @covers EditPage
	 */
	public function testSectionEdit( $base, $section, $text, $summary, $expected ) {
		$edit = array(
			'wpTextbox1' => $text,
			'wpSummary' => $summary,
			'wpSection' => $section,
		);

		$this->assertEdit( 'EditPageTest_testSectionEdit', $base, null, $edit,
			EditPage::AS_SUCCESS_UPDATE, $expected,
			"expected successfull update of section" );
	}

	public static function provideAutoMerge() {
		$tests = array();

		$tests[] = array( #0: plain conflict
			"Elmo", # base edit user
			"one\n\ntwo\n\nthree\n",
			array( #adam's edit
				'wpTextbox1' => "ONE\n\ntwo\n\nthree\n",
			),
			array( #berta's edit
				'wpTextbox1' => "(one)\n\ntwo\n\nthree\n",
			),
			EditPage::AS_CONFLICT_DETECTED, # expected code
			"ONE\n\ntwo\n\nthree\n", # expected text
			'expected edit conflict', # message
		);

		$tests[] = array( #1: successful merge
			"Elmo", # base edit user
			"one\n\ntwo\n\nthree\n",
			array( #adam's edit
				'wpTextbox1' => "ONE\n\ntwo\n\nthree\n",
			),
			array( #berta's edit
				'wpTextbox1' => "one\n\ntwo\n\nTHREE\n",
			),
			EditPage::AS_SUCCESS_UPDATE, # expected code
			"ONE\n\ntwo\n\nTHREE\n", # expected text
			'expected automatic merge', # message
		);

		$text = "Intro\n\n";
		$text .= "== first section ==\n\n";
		$text .= "one\n\ntwo\n\nthree\n\n";
		$text .= "== second section ==\n\n";
		$text .= "four\n\nfive\n\nsix\n\n";

		// extract the first section.
		$section = preg_replace( '/.*(== first section ==.*)== second section ==.*/sm', '$1', $text );

		// generate expected text after merge
		$expected = str_replace( 'one', 'ONE', str_replace( 'three', 'THREE', $text ) );

		$tests[] = array( #2: merge in section
			"Elmo", # base edit user
			$text,
			array( #adam's edit
				'wpTextbox1' => str_replace( 'one', 'ONE', $section ),
				'wpSection' => '1'
			),
			array( #berta's edit
				'wpTextbox1' => str_replace( 'three', 'THREE', $section ),
				'wpSection' => '1'
			),
			EditPage::AS_SUCCESS_UPDATE, # expected code
			$expected, # expected text
			'expected automatic section merge', # message
		);

		// see whether it makes a difference who did the base edit
		$testsWithAdam = array_map( function ( $test ) {
			$test[0] = 'Adam'; // change base edit user
			return $test;
		}, $tests );

		$testsWithBerta = array_map( function ( $test ) {
			$test[0] = 'Berta'; // change base edit user
			return $test;
		}, $tests );

		return array_merge( $tests, $testsWithAdam, $testsWithBerta );
	}

	/**
	 * @dataProvider provideAutoMerge
	 * @covers EditPage
	 */
	public function testAutoMerge( $baseUser, $text, $adamsEdit, $bertasEdit,
		$expectedCode, $expectedText, $message = null
	) {
		$this->checkHasDiff3();

		//create page
		$ns = $this->getDefaultWikitextNS();
		$title = Title::newFromText( 'EditPageTest_testAutoMerge', $ns );
		$page = WikiPage::factory( $title );

		if ( $page->exists() ) {
			$page->doDeleteArticle( "clean slate for testing" );
		}

		$baseEdit = array(
			'wpTextbox1' => $text,
		);

		$page = $this->assertEdit( 'EditPageTest_testAutoMerge', null,
			$baseUser, $baseEdit, null, null, __METHOD__ );
		$baseRevId = $page->getLatest();

		$adamsEdit['oldid'] = $baseRevId;
		$bertasEdit['oldid'] = $baseRevId;

		$adamsEdit['wpSummary'] = 'Adam\'s edit';
		$bertasEdit['wpSummary'] = 'Bertas\'s edit';

		// first edit
		$this->assertEdit( 'EditPageTest_testAutoMerge', null, 'Adam', $adamsEdit,
			EditPage::AS_SUCCESS_UPDATE, null, "expected successfull update" );

		// second edit
		$this->assertEdit( 'EditPageTest_testAutoMerge', null, 'Berta', $bertasEdit,
			$expectedCode, $expectedText, $message );
	}
}
