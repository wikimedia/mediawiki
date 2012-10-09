<?php

class ParserOptionsTest extends MediaWikiTestCase {

	private $popts;
	private $pcache;

	protected function setUp() {
		global $wgLanguageCode, $wgUser;
		parent::setUp();

		$langObj = Language::factory( $wgLanguageCode );

		$this->setMwGlobals( array(
			'wgContLang' => $langObj,
			'wgUseDynamicDates' => true,
		) );

		$this->popts = ParserOptions::newFromUserAndLang( $wgUser, $langObj );
		$this->pcache = ParserCache::singleton();
	}

	/**
	 * ParserOptions::optionsHash was not giving consistent results when $wgUseDynamicDates was set
	 * @group Database
	 */
	function testGetParserCacheKeyWithDynamicDates() {
		$title = Title::newFromText( "Some test article" );
		$page = WikiPage::factory( $title );

		$pcacheKeyBefore = $this->pcache->getKey( $page, $this->popts );
		$this->assertNotNull( $this->popts->getDateFormat() );

		$pcacheKeyAfter = $this->pcache->getKey( $page, $this->popts );
		$this->assertEquals( $pcacheKeyBefore, $pcacheKeyAfter );
	}
}
