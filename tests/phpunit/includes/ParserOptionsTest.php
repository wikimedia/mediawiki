<?php

class ParserOptionsTest extends MediaWikiTestCase {

	private $popts;
	private $pcache;

	function setUp() {
		ParserTest::setUp(); //reuse setup from parser tests
		global $wgContLang, $wgUser, $wgLanguageCode;
		$wgContLang = Language::factory( $wgLanguageCode );
		$this->popts = new ParserOptions( $wgUser );
		$this->pcache = ParserCache::singleton();
	}

	function tearDown() {
		parent::tearDown();
	}

	/**
	 * ParserOptions::optionsHash was not giving consistent results when $wgUseDynamicDates was set
	 * @group Database
	 */
	function testGetParserCacheKeyWithDynamicDates() {
		global $wgUseDynamicDates;
		$wgUseDynamicDates = true;

		$title = Title::newFromText( "Some test article" );
		$article = new Article( $title );

		$pcacheKeyBefore = $this->pcache->getKey( $article, $this->popts );
		$this->assertNotNull( $this->popts->getDateFormat() );
		$pcacheKeyAfter = $this->pcache->getKey( $article, $this->popts );
		$this->assertEquals( $pcacheKeyBefore, $pcacheKeyAfter );
	}
}
