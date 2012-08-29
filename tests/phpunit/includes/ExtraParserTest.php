<?php

/**
 * Parser-related tests that don't suit for parserTests.txt
 */
class ExtraParserTest extends MediaWikiTestCase {

	function setUp() {
		global $wgMemc;
		global $wgContLang;
		global $wgShowDBErrorBacktrace;
		global $wgLanguageCode;
		global $wgAlwaysUseTidy;

		$wgShowDBErrorBacktrace = true;
		$wgLanguageCode = 'en';
		$wgContLang = new Language( 'en' );
		$wgMemc = new EmptyBagOStuff;
		$wgAlwaysUseTidy = false;
		
		$this->options = new ParserOptions;
		$this->options->setTemplateCallback( array( __CLASS__, 'statelessFetchTemplate' ) );
		$this->parser = new Parser;

		MagicWord::clearCache();
	}

	// Bug 8689 - Long numeric lines kill the parser
	function testBug8689() {
		global $wgLang;
		global $wgUser;
		$longLine = '1.' . str_repeat( '1234567890', 100000 ) . "\n";

		if ( $wgLang === null ) $wgLang = new Language;
		
		$t = Title::newFromText( 'Unit test' );
		$options = ParserOptions::newFromUser( $wgUser );
		$this->assertEquals( "<p>$longLine</p>",
			$this->parser->parse( $longLine, $t, $options )->getText() );
	}
	
	/* Test the parser entry points */
	function testParse() {
		$title = Title::newFromText( __FUNCTION__ );
		$parserOutput = $this->parser->parse( "Test\n{{Foo}}\n{{Bar}}" , $title, $this->options );
		$this->assertEquals( "<p>Test\nContent of <i>Template:Foo</i>\nContent of <i>Template:Bar</i>\n</p>", $parserOutput->getText() );
	}
	
	function testPreSaveTransform() {
		global $wgUser;
		$title = Title::newFromText( __FUNCTION__ );
		$outputText = $this->parser->preSaveTransform( "Test\r\n{{subst:Foo}}\n{{Bar}}", $title, $wgUser, $this->options );

		$this->assertEquals( "Test\nContent of ''Template:Foo''\n{{Bar}}", $outputText );
	}
	
	function testPreprocess() {
		$title = Title::newFromText( __FUNCTION__ );
		$outputText = $this->parser->preprocess( "Test\n{{Foo}}\n{{Bar}}" , $title, $this->options );
		
		$this->assertEquals( "Test\nContent of ''Template:Foo''\nContent of ''Template:Bar''", $outputText );
	}
	
	/**
	 * cleanSig() makes all templates substs and removes tildes
	 */
	function testCleanSig() {
		global $wgCleanSignatures;
		$oldCleanSignature = $wgCleanSignatures;
		$wgCleanSignatures = true;

		$title = Title::newFromText( __FUNCTION__ );
		$outputText = $this->parser->cleanSig( "{{Foo}} ~~~~" );

		$wgCleanSignatures = $oldCleanSignature;
		
		$this->assertEquals( "{{SUBST:Foo}} ", $outputText );
	}

	/**
	 * cleanSig() should do nothing if disabled
	 */
	function testCleanSigDisabled() {
		global $wgCleanSignatures;
		$oldCleanSignature = $wgCleanSignatures;
		$wgCleanSignatures = false;

		$title = Title::newFromText( __FUNCTION__ );
		$outputText = $this->parser->cleanSig( "{{Foo}} ~~~~" );

		$wgCleanSignatures = $oldCleanSignature;
		
		$this->assertEquals( "{{Foo}} ~~~~", $outputText );
	}
	
	/**
	 * cleanSigInSig() just removes tildes
	 * @dataProvider provideStringsForCleanSigInSig
	 */
	function testCleanSigInSig( $in, $out ) {
		$this->assertEquals( Parser::cleanSigInSig( $in), $out );
	}
	
	function provideStringsForCleanSigInSig() {
		return array(
			array( "{{Foo}} ~~~~", "{{Foo}} " ),
			array( "~~~", "" ),
			array( "~~~~~", "" ),
		);
	}
	
	function testGetSection() {
		$outputText2 = $this->parser->getSection( "Section 0\n== Heading 1 ==\nSection 1\n=== Heading 2 ===\nSection 2\n== Heading 3 ==\nSection 3\n", 2 );
		$outputText1 = $this->parser->getSection( "Section 0\n== Heading 1 ==\nSection 1\n=== Heading 2 ===\nSection 2\n== Heading 3 ==\nSection 3\n", 1 );
		
		$this->assertEquals( "=== Heading 2 ===\nSection 2", $outputText2 );
		$this->assertEquals( "== Heading 1 ==\nSection 1\n=== Heading 2 ===\nSection 2", $outputText1 );
	}
	
	function testReplaceSection() {
		$outputText = $this->parser->replaceSection( "Section 0\n== Heading 1 ==\nSection 1\n=== Heading 2 ===\nSection 2\n== Heading 3 ==\nSection 3\n", 1, "New section 1" );
		
		$this->assertEquals( "Section 0\nNew section 1\n\n== Heading 3 ==\nSection 3", $outputText );
	}
	
	/**
	 * Templates and comments are not affected, but noinclude/onlyinclude is.
	 */
	function testGetPreloadText() {
		$title = Title::newFromText( __FUNCTION__ );
		$outputText = $this->parser->getPreloadText( "{{Foo}}<noinclude> censored</noinclude> information <!-- is very secret -->", $title, $this->options );
		
		$this->assertEquals( "{{Foo}} information <!-- is very secret -->", $outputText );
	}
	
	static function statelessFetchTemplate( $title, $parser=false ) {
		$text = "Content of ''" . $title->getFullText() . "''";
		$deps = array();
		
		return array(
			'text' => $text,
			'finalTitle' => $title,
			'deps' => $deps );
	}

	/**
	 * @group Database
	 */
	function testTrackingCategory() {
		$title = Title::newFromText( __FUNCTION__ );
		$catName =  wfMessage( 'broken-file-category' )->inContentLanguage()->text();
		$cat = Title::makeTitleSafe( NS_CATEGORY, $catName );
		$expected = array( $cat->getDBkey() );
		$parserOutput = $this->parser->parse( "[[file:nonexistent]]" , $title, $this->options );
		$result = $parserOutput->getCategoryLinks();
		$this->assertEquals( $expected, $result );
	}

	/**
	 * @group Database
	 */
	function testTrackingCategorySpecial() {
		// Special pages shouldn't have tracking cats.
		$title = SpecialPage::getTitleFor( 'Contributions' );
		$parserOutput = $this->parser->parse( "[[file:nonexistent]]" , $title, $this->options );
		$result = $parserOutput->getCategoryLinks();
		$this->assertEmpty( $result );
	}
 }
