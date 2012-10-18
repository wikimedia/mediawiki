<?php

/**
 * @group ContentHandler
 *
 * @group Database
 *        ^--- needed, because we do need the database to test link updates
 */
class WikitextContentTest extends TextContentTest {

	public function newContent( $text ) {
		return new WikitextContent( $text );
	}

	public function dataGetParserOutput() {
		return array(
			array("WikitextContentTest_testGetParserOutput", CONTENT_MODEL_WIKITEXT, "hello ''world''\n", "<p>hello <i>world</i>\n</p>"),
			// @todo: more...?
		);
	}

	public function dataGetSecondaryDataUpdates() {
		return array(
			array( "WikitextContentTest_testGetSecondaryDataUpdates_1",
				CONTENT_MODEL_WIKITEXT, "hello ''world''\n",
				array( 'LinksUpdate' => array(  'mRecursive' => true,
				                                'mLinks' => array() ) )
			),
			array( "WikitextContentTest_testGetSecondaryDataUpdates_2",
				CONTENT_MODEL_WIKITEXT, "hello [[world test 21344]]\n",
				array( 'LinksUpdate' => array(  'mRecursive' => true,
				                                'mLinks' => array( array( 'World_test_21344' => 0 ) ) ) )
			),
			// @todo: more...?
		);
	}

	/**
	 * @dataProvider dataGetSecondaryDataUpdates
	 * @group Database
	 */
	public function testGetSecondaryDataUpdates( $title, $model, $text, $expectedStuff ) {
		$title = Title::newFromText( $title );
		$title->resetArticleID( 2342 ); //dummy id. fine as long as we don't try to execute the updates!

		$content = ContentHandler::makeContent( $text, $title, $model );

		$updates = $content->getSecondaryDataUpdates( $title );

		// make updates accessible by class name
		foreach ( $updates as $update ) {
			$class = get_class( $update );
			$updates[$class] = $update;
		}

		foreach ( $expectedStuff as $class => $fieldValues ) {
			$this->assertArrayHasKey( $class, $updates, "missing an update of type $class" );

			$update = $updates[$class];

			foreach ( $fieldValues as $field => $value ) {
				$v = $update->$field; #if the field doesn't exist, just crash and burn
				$this->assertEquals( $value, $v, "unexpected value for field $field in instance of $class" );
			}
		}
	}


	static $sections =

"Intro

== stuff ==
hello world

== test ==
just a test

== foo ==
more stuff
";

	public function dataGetSection() {
		return array(
			array( WikitextContentTest::$sections,
					"0",
					"Intro"
			),
			array( WikitextContentTest::$sections,
					"2",
"== test ==
just a test"
			),
			array( WikitextContentTest::$sections,
					"8",
					false
			),
		);
	}

	/**
	 * @dataProvider dataGetSection
	 */
	public function testGetSection( $text, $sectionId, $expectedText ) {
		$content = $this->newContent( $text );

		$sectionContent = $content->getSection( $sectionId );
		if ( is_object( $sectionContent ) ) {
			$sectionText = $sectionContent->getNativeData();
		} else {
			$sectionText = $sectionContent;
		}

		$this->assertEquals( $expectedText, $sectionText );
	}

	public function dataReplaceSection() {
		return array(
			array( WikitextContentTest::$sections,
			       "0",
			       "No more",
			       null,
			       trim( preg_replace( '/^Intro/sm', 'No more', WikitextContentTest::$sections ) )
			),
			array( WikitextContentTest::$sections,
			       "",
			       "No more",
			       null,
			       "No more"
			),
			array( WikitextContentTest::$sections,
			       "2",
			       "== TEST ==\nmore fun",
			       null,
			       trim( preg_replace( '/^== test ==.*== foo ==/sm', "== TEST ==\nmore fun\n\n== foo ==", WikitextContentTest::$sections ) )
			),
			array( WikitextContentTest::$sections,
			       "8",
			       "No more",
			       null,
			       WikitextContentTest::$sections
			),
			array( WikitextContentTest::$sections,
			       "new",
			       "No more",
			       "New",
			       trim( WikitextContentTest::$sections ) . "\n\n\n== New ==\n\nNo more"
			),
		);
	}

	/**
	 * @dataProvider dataReplaceSection
	 */
	public function testReplaceSection( $text, $section, $with, $sectionTitle, $expected ) {
		$content = $this->newContent( $text );
		$c = $content->replaceSection( $section, $this->newContent( $with ), $sectionTitle );

		$this->assertEquals( $expected, is_null( $c ) ? null : $c->getNativeData() );
	}

	public function testAddSectionHeader( ) {
		$content = $this->newContent( 'hello world' );
		$content = $content->addSectionHeader( 'test' );

		$this->assertEquals( "== test ==\n\nhello world", $content->getNativeData() );
	}

	public function dataPreSaveTransform() {
		return array(
			array( 'hello this is ~~~',
			       "hello this is [[Special:Contributions/127.0.0.1|127.0.0.1]]",
			),
			array( 'hello \'\'this\'\' is <nowiki>~~~</nowiki>',
			       'hello \'\'this\'\' is <nowiki>~~~</nowiki>',
			),
			array( // rtrim
				" Foo \n ",
				" Foo",
			),
		);
	}

	public function dataPreloadTransform() {
		return array(
			array( 'hello this is ~~~',
			       "hello this is ~~~",
			),
			array( 'hello \'\'this\'\' is <noinclude>foo</noinclude><includeonly>bar</includeonly>',
			       'hello \'\'this\'\' is bar',
			),
		);
	}

	public function dataGetRedirectTarget() {
		return array(
			array( '#REDIRECT [[Test]]',
			       'Test',
			),
			array( '#REDIRECT Test',
			       null,
			),
			array( '* #REDIRECT [[Test]]',
			       null,
			),
		);
	}

	public function dataGetTextForSummary() {
		return array(
			array( "hello\nworld.",
				16,
				'hello world.',
			),
			array( 'hello world.',
				8,
				'hello...',
			),
			array( '[[hello world]].',
				8,
				'hel...',
			),
		);
	}

	/**
	 * @todo: test needs database! Should be done by a test class in the Database group.
	 */
	/*
	public function getRedirectChain() {
		$text = $this->getNativeData();
		return Title::newFromRedirectArray( $text );
	}
	*/

	/**
	 * @todo: test needs database! Should be done by a test class in the Database group.
	 */
	/*
	public function getUltimateRedirectTarget() {
		$text = $this->getNativeData();
		return Title::newFromRedirectRecurse( $text );
	}
	*/


	public function dataIsCountable() {
		return array(
			array( '',
			       null,
			       'any',
			       true
			),
			array( 'Foo',
			       null,
			       'any',
			       true
			),
			array( 'Foo',
			       null,
			       'comma',
			       false
			),
			array( 'Foo, bar',
			       null,
			       'comma',
			       true
			),
			array( 'Foo',
			       null,
			       'link',
			       false
			),
			array( 'Foo [[bar]]',
			       null,
			       'link',
			       true
			),
			array( 'Foo',
			       true,
			       'link',
			       true
			),
			array( 'Foo [[bar]]',
			       false,
			       'link',
			       false
			),
			array( '#REDIRECT [[bar]]',
			       true,
			       'any',
			       false
			),
			array( '#REDIRECT [[bar]]',
			       true,
			       'comma',
			       false
			),
			array( '#REDIRECT [[bar]]',
			       true,
			       'link',
			       false
			),
		);
	}

	public function testMatchMagicWord( ) {
		$mw = MagicWord::get( "staticredirect" );

		$content = $this->newContent( "#REDIRECT [[FOO]]\n__STATICREDIRECT__" );
		$this->assertTrue( $content->matchMagicWord( $mw ), "should have matched magic word" );

		$content = $this->newContent( "#REDIRECT [[FOO]]" );
		$this->assertFalse( $content->matchMagicWord( $mw ), "should not have matched magic word" );
	}

	public function testUpdateRedirect( ) {
		$target = Title::newFromText( "testUpdateRedirect_target" );

		// test with non-redirect page
		$content = $this->newContent( "hello world." );
		$newContent = $content->updateRedirect( $target );

		$this->assertTrue( $content->equals( $newContent ), "content should be unchanged" );

		// test with actual redirect
		$content = $this->newContent( "#REDIRECT [[Someplace]]" );
		$newContent = $content->updateRedirect( $target );

		$this->assertFalse( $content->equals( $newContent ), "content should have changed" );
		$this->assertTrue( $newContent->isRedirect(), "new content should be a redirect" );

		$this->assertEquals( $target->getFullText(), $newContent->getRedirectTarget()->getFullText() );
	}

	# =================================================================================================================

	public function testGetModel() {
		$content = $this->newContent( "hello world." );

		$this->assertEquals( CONTENT_MODEL_WIKITEXT, $content->getModel() );
	}

	public function testGetContentHandler() {
		$content = $this->newContent( "hello world." );

		$this->assertEquals( CONTENT_MODEL_WIKITEXT, $content->getContentHandler()->getModelID() );
	}

	public function dataEquals( ) {
		return array(
			array( new WikitextContent( "hallo" ), null, false ),
			array( new WikitextContent( "hallo" ), new WikitextContent( "hallo" ), true ),
			array( new WikitextContent( "hallo" ), new JavascriptContent( "hallo" ), false ),
			array( new WikitextContent( "hallo" ), new TextContent( "hallo" ), false ),
			array( new WikitextContent( "hallo" ), new WikitextContent( "HALLO" ), false ),
		);
	}

	public function dataGetDeletionUpdates() {
		return array(
			array("WikitextContentTest_testGetSecondaryDataUpdates_1",
				CONTENT_MODEL_WIKITEXT, "hello ''world''\n",
				array( 'LinksDeletionUpdate' => array( ) )
			),
			array("WikitextContentTest_testGetSecondaryDataUpdates_2",
				CONTENT_MODEL_WIKITEXT, "hello [[world test 21344]]\n",
				array( 'LinksDeletionUpdate' => array( ) )
			),
			// @todo: more...?
		);
	}

}
