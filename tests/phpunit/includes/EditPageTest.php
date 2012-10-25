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
class EditPageTest extends MediaWikiTestCase {

	/**
	 * @dataProvider provideExtractSectionTitle
	 */
	function testExtractSectionTitle( $section, $title ) {
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
	 * Performs an edit and checks the result.
	 *
	 * @param String|Title $title The title of the page to edit
	 * @param String|null $baseText Some text to create the page with before attempting the edit.
	 * @param User|String|null $user The user to perform the edit as.
	 * @param array $edit An array of request parameters used to define the edit to perform.
	 *              Some well known fields are:
	 *              * wpTextbox1: the text to submit
	 *              * wpSummary: the edit summary
	 *              * wpEditToken: the edit token (will be inserted if not provided)
	 *              * wpEdittime: timestamp of the edit's base revision (will be inserted if not provided)
	 *              * wpStarttime: timestamp when the edit started (will be inserted if not provided)
	 *              * wpSectionTitle: the section to edit
	 *              * wpMinorEdit: mark as minor edit
	 *              * wpWatchthis: whether to watch the page
	 * @param int|null $expectedCode The expected result code (EditPage::AS_XXX constants).
	 *                  Set to null to skip the check. Defaults to EditPage::AS_OK.
	 * @param String|null $expectedText The text expected to be on the page after the edit.
	 *                  Set to null to skip the check.
	 * @param String|null $message An optional message to show along with any error message.
	 *
	 * @return Status
	 */
	protected function assertEdit( $title, $baseText, $user = null, array $edit,
		$expectedCode = EditPage::AS_OK, $expectedText = null, $message = null
	) {
		if ( is_string( $title ) ) {
			$ns = $this->getDefaultWikitextNS();
			$title = Title::newFromText( $title, $ns );
		}

		if ( $user !== null && is_string( $user ) ) {
			$user = User::newFromName( $user );

			if ( $user->getId() === 0 ) {
				$user = User::createNew( $user->getName() );
			}
		}

		$page = WikiPage::factory( $title );

		if ( $baseText !== null ) {
			$content = ContentHandler::makeContent( $baseText, $title );
			$page->doEditContent( $content, "base text for test" );
			sleep( 1 ); //sleep after creation, so timestamps are unique

			//sanity check
			$page->clear();
			$currentText = ContentHandler::getContentText( $page->getContent() );
			$this->assertEquals( trim( $baseText ), trim( $currentText ) );
		}

		if ( $user == null ) {
			$user = $GLOBALS['wgUser'];
		} else {
			$this->setMwGlobals( 'wgUser', $user );
		}

		if ( !isset( $edit['wpEditToken'] ) ) {
			$edit['wpEditToken'] = $user->getEditToken();
		}

		if ( !isset( $edit['wpEdittime'] ) ) {
			$edit['wpEdittime'] = $page->exists() ? $page->getTimestamp() : '';
		}

		if ( !isset( $edit['wpStarttime'] ) ) {
			$edit['wpStarttime'] = wfTimestampNow();
		}

		$req = new FauxRequest( $edit, true ); // session ??

		$ep = new EditPage( new Article( $title ) );
		$ep->setContextTitle( $title );
		$ep->importFormData( $req );

		$bot = isset( $edit['bot'] ) ? (bool)$edit['bot'] : false;
		$status = $ep->internalAttemptSave( $result, $bot ); // <--- this is where the edit happens

		if ( $expectedCode !== null ) {
			// check edit code
			$this->assertEquals( $expectedCode, $status->value,
				"Expected result code mismatch. $message" );
		}

		if ( $expectedText !== null ) {
			// check resulting page text
			$page = WikiPage::factory( $title );
			$content = $page->getContent();
			$text = ContentHandler::getContentText( $content );

			$this->assertEquals( trim( $expectedText ) , trim( $text ),
				"Expected article text mismatch. $message" );
		}

		$page = WikiPage::factory( $title );
		return $page;
	}

	public function testCreatePage() {
		$text = "Hello World!";
		$edit = array(
			'wpTextbox1' => $text,
			'wpSummary' => 'just testing',
		);

		$this->assertEdit( 'EditPageTest_testCreatePafe', null, null, $edit,
			EditPage::AS_SUCCESS_NEW_ARTICLE, $text, __METHOD__ );
	}

	public function testUpdatePage() {
		$text = "one";
		$edit = array(
			'wpTextbox1' => $text,
			'wpSummary' => 'first update',
		);

		$this->assertEdit( 'EditPageTest_testUpdatePage', "zero", null, $edit,
			EditPage::AS_SUCCESS_UPDATE, $text, __METHOD__ );

		sleep( 1 ); //sleep after edit, so timestamps are unique

		$text = "two";
		$edit = array(
			'wpTextbox1' => $text,
			'wpSummary' => 'second update',
		);

		$this->assertEdit( 'EditPageTest_testUpdatePage', null, null, $edit,
			EditPage::AS_SUCCESS_UPDATE, $text, __METHOD__ );
	}

	public static function provideSectionEdit() {
		$text =
'Intro

== one ==
first section.

== two ==
second section.
';

		$sectionOne =
'== one ==
hello
';

		$newSection =
'== new section ==

hello
';

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
				preg_replace( '/== one ==.*== two ==/ms', "$sectionOne\n== two ==", $text ),
			),

			array( #2
				$text,
				'new',
				'hello',
				'new section',
				"$text\n$newSection"
			),
		);
	}

	/**
	 * @dataProvider provideSectionEdit
	 */
	public function testSectionEdit( $base, $section, $text, $summary, $expected ) {
		$edit = array(
			'wpTextbox1' => $text,
			'wpSummary' => $summary,
			'wpSection' => $section,
		);

		$this->assertEdit( 'EditPageTest_testSectionEdit', $base, null, $edit,
			EditPage::AS_SUCCESS_UPDATE, $expected, __METHOD__ );
	}

	public function testEditConflict() {
		//create page
		$ns = $this->getDefaultWikitextNS();
		$title = Title::newFromText( 'EditPageTest_testEditConflict', $ns );
		$page = WikiPage::factory( $title );

		$text = "one\n\ntwo\n\nthree\n";

		$content = ContentHandler::makeContent( $text, $title );
		$page->doEditContent( $content, "base text for test" );

		$edittime = $page->getTimestamp();
		sleep(1); // make sure we have distinct timestamps for each revision

		// start timestamps for conflict detection
		$starttime = wfTimestampNow();
		$adamsTime = wfTimestamp( TS_MW, (int)wfTimestamp( TS_UNIX, $starttime ) +1 );
		$bertasTime = wfTimestamp( TS_MW, (int)wfTimestamp( TS_UNIX, $starttime ) +2 );

		// first edit
		$newText = str_replace( 'two', 'TWO', $text );
		$edit = array(
			'wpTextbox1' => $newText,
			'wpSummary' => 'Adam\'s edit',
			'wpEdittime' => $edittime,
			'wpStarttime' => $adamsTime,
		);

		$this->assertEdit( 'EditPageTest_testEditConflict', null, 'Adam', $edit,
			EditPage::AS_SUCCESS_UPDATE, null, __METHOD__ );

		// second edit
		$newText = str_replace( 'two', '(two)', $text );
		$edit = array(
			'wpTextbox1' => $newText,
			'wpSummary' => 'Berta\'s edit',
			'wpEdittime' => $edittime,
			'wpStarttime' => $bertasTime,
		);

		$this->assertEdit( 'EditPageTest_testEditConflict', null, 'Berta', $edit,
			EditPage::AS_CONFLICT_DETECTED, null, __METHOD__ );
	}

	public static function provideAutoMerge() {
		$tests = array();
		$i = 0;

		$tests[ $i ] = array( #0: plain conflict
			"one\n\ntwo\n\nthree\n",
			array( #adam's edit
				'wpStarttime' => 1,
				'wpTextbox1' => "ONE\n\ntwo\n\nthree\n",
			),
			array( #berta's edit
				'wpStarttime' => 2,
				'wpTextbox1' => "(one)\n\ntwo\n\nthree\n",
			),
			EditPage::AS_CONFLICT_DETECTED,
			null
		);

		// #1: again, with swapped wpStarttime params.
		$i++;
		$tests[ $i ] = $tests[ $i-1 ];
		$tests[ $i ][1]['wpStarttime'] = 2;
		$tests[ $i ][2]['wpStarttime'] = 1;

		$i++;
		$tests[ $i ] = array( #2: successful merge
			"one\n\ntwo\n\nthree\n",
			array( #adam's edit
				'wpStarttime' => 1,
				'wpTextbox1' => "ONE\n\ntwo\n\nthree\n",
			),
			array( #berta's edit
				'wpStarttime' => 2,
				'wpTextbox1' => "one\n\ntwo\n\nTHREE\n",
			),
			EditPage::AS_SUCCESS_UPDATE,
			"ONE\n\ntwo\n\nTHREE\n"
		);

		// #3: again, with swapped wpStarttime params.
		$i++;
		$tests[ $i ] = $tests[ $i-1 ];
		$tests[ $i ][1]['wpStarttime'] = 2;
		$tests[ $i ][2]['wpStarttime'] = 1;

		$text = "Intro\n\n";
		$text .= "== first section ==\n\n";
		$text .= "one\n\ntwo\n\nthree\n\n";
		$text .= "== second section ==\n\n";
		$text .= "four\n\nfive\n\nsix\n\n";

		$section = preg_replace( '/.*(== first section ==.*)== second section ==.*/sm', '$1', $text );

		$i++;
		$tests[ $i ] = array( #4: merge in section
			$text,
			array( #adam's edit
				'wpStarttime' => 1,
				'wpTextbox1' => str_replace( 'one', 'ONE', $section ),
				'wpSection' => '1'
			),
			array( #berta's edit
				'wpStarttime' => 2,
				'wpTextbox1' => str_replace( 'three', 'THREE', $section ),
				'wpSection' => '1'
			),
			EditPage::AS_SUCCESS_UPDATE,
			str_replace( 'one', 'ONE', str_replace( 'three', 'THREE', $text ) )
		);

		// #5: again, with swapped wpStarttime params.
		$i++;
		$tests[ $i ] = $tests[ $i-1 ];
		$tests[ $i ][1]['wpStarttime'] = 2;
		$tests[ $i ][2]['wpStarttime'] = 1;

		return $tests;
	}

	/**
	 * @dataProvider provideAutoMerge
	 */
	public function testAutoMerge( $text, $adamsEdit, $bertasEdit, $expectedCode, $expectedText ) {
		global $wgDiff3;

		if ( !$wgDiff3 ) {
			$this->markTestSkipped( "Can't test conflict resolution because \$wgDiff3 is not configured" );
		}

		if ( !file_exists( $wgDiff3 ) ) {
			#XXX: this sucks, since it uses arcane internal knowledge about TextContentHandler::merge3 and wfMerge.
			$this->markTestSkipped( "Can't test conflict resolution because \$wgDiff3 is misconfigured: can't find $wgDiff3" );
		}

		//create page
		$ns = $this->getDefaultWikitextNS();
		$title = Title::newFromText( 'EditPageTest_testAutoMerge', $ns );
		$page = WikiPage::factory( $title );

		if ( $page->exists() ) {
			$page->doDeleteArticle( "clean slate for testing" );
		}

		$content = ContentHandler::makeContent( $text, $title );
		$page->doEditContent( $content, "base text for test" );

		$edittime = $page->getTimestamp();
		sleep(1); // make sure we have distinct timestamps for each revision

		// start timestamps for conflict detection
		if ( !isset( $adamsEdit['wpStarttime'] ) ) {
			$adamsEdit['wpStarttime'] = 1;
		}

		if ( !isset( $bertasEdit['wpStarttime'] ) ) {
			$bertasEdit['wpStarttime'] = 2;
		}

		$starttime = wfTimestampNow();
		$adamsTime = wfTimestamp( TS_MW, (int)wfTimestamp( TS_UNIX, $starttime ) + (int)$adamsEdit['wpStarttime'] );
		$bertasTime = wfTimestamp( TS_MW, (int)wfTimestamp( TS_UNIX, $starttime ) + (int)$bertasEdit['wpStarttime'] );

		$adamsEdit['wpStarttime'] = $adamsTime;
		$bertasEdit['wpStarttime'] = $bertasTime;

		$adamsEdit['wpSummary'] = 'Adam\'s edit';
		$bertasEdit['wpSummary'] = 'Bertas\'s edit';

		$adamsEdit['wpEdittime'] = $edittime;
		$bertasEdit['wpEdittime'] = $edittime;

		// first edit
		$this->assertEdit( 'EditPageTest_testAutoMerge', null, 'Adam', $adamsEdit,
			EditPage::AS_SUCCESS_UPDATE, null, __METHOD__ );

		// second edit
		$this->assertEdit( 'EditPageTest_testAutoMerge', null, 'Berta', $bertasEdit,
			$expectedCode, $expectedText, __METHOD__ );
	}
}