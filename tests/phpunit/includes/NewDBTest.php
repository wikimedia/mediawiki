<?php

class NewDBTest extends MediaWikiTestCase {

	function setUp() {
	}

	function tearDown() {
	}
	
	function addDBData() {
		
		//Make a page
		$article = new Article( Title::newFromText( 'Foobar' ) );
		$article->doEdit( 'FoobarContent',
							'',
							EDIT_NEW,
							false,
							User::newFromName( 'UTSysop' ) );
	}
	
	function needsDB() { return true; }

	function testBootstrapCreation() {
		
		$article = new Article( Title::newFromText("UTPage") );
		
		$this->assertEquals("UTContent", $article->fetchContent(), "Automatic main page creation");
		
		$article = new Article( Title::newFromText("Foobar") );
		
		$this->assertEquals("FoobarContent", $article->fetchContent(), "addDBData() adds to the database");
		
	}

}

