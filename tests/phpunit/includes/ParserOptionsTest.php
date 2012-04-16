<?php

class ParserOptionsTest extends MediaWikiTestCase {

	private $popts;
	private $pcache;

	function setUp() {
		global $wgContLang, $wgUser, $wgLanguageCode;
		$wgContLang = Language::factory( $wgLanguageCode );
		$this->popts = ParserOptions::newFromUserAndLang( $wgUser, $wgContLang );
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
		$page = WikiPage::factory( $title );

		$pcacheKeyBefore = $this->pcache->getKey( $page, $this->popts );
		$this->assertNotNull( $this->popts->getDateFormat() );
		$pcacheKeyAfter = $this->pcache->getKey( $page, $this->popts );
		$this->assertEquals( $pcacheKeyBefore, $pcacheKeyAfter );
	}
}
