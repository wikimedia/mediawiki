<?php

/**
 * @group Database
 * @group Cache
 */
class MessageCacheTest extends MediaWikiLangTestCase {

	protected function setUp() {
		parent::setUp();
		$this->configureLanguages();
		MessageCache::singleton()->enable();
	}

	/**
	 * Helper function -- setup site language for testing
	 */
	protected function configureLanguages() {
		// for the test, we need the content language to be anything but English,
		// let's choose e.g. German (de)
		$langCode = 'de';
		$langObj = Language::factory( $langCode );

		$this->setMwGlobals( array(
			'wgLanguageCode' => $langCode,
			'wgLang' => $langObj,
			'wgContLang' => $langObj,
		) );
	}

	function addDBData() {
		$this->configureLanguages();

		// Set up messages and fallbacks ab -> ru -> de -> en
		$this->makePage( 'FallbackLanguageTest-Full', 'ab' );
		$this->makePage( 'FallbackLanguageTest-Full', 'ru' );
		$this->makePage( 'FallbackLanguageTest-Full', 'de' );
		$this->makePage( 'FallbackLanguageTest-Full', 'en' );

		// Fallbacks where ab does not exist
		$this->makePage( 'FallbackLanguageTest-Partial', 'ru' );
		$this->makePage( 'FallbackLanguageTest-Partial', 'de' );
		$this->makePage( 'FallbackLanguageTest-Partial', 'en' );

		// Fallback to the content language
		$this->makePage( 'FallbackLanguageTest-ContLang', 'de' );
		$this->makePage( 'FallbackLanguageTest-ContLang', 'en' );

		// Fallback to english
		$this->makePage( 'FallbackLanguageTest-English', 'en' );

		// Full key tests -- always want russian
		$this->makePage( 'MessageCacheTest-FullKeyTest', 'ab' );
		$this->makePage( 'MessageCacheTest-FullKeyTest', 'ru' );
	}

	/**
	 * Helper function for addDBData -- adds a simple page to the database
	 *
	 * @param string $title Title of page to be created
	 * @param string $lang  Language and content of the created page
	 */
	protected function makePage( $title, $lang ) {
		global $wgContLang;

		$title = Title::newFromText(
			( $lang == $wgContLang->getCode() ) ? $title : "$title/$lang",
			NS_MEDIAWIKI
		);
		$wikiPage = new WikiPage( $title );
		$content = ContentHandler::makeContent( $lang, $title );
		$wikiPage->doEditContent( $content, "$lang translation test case" );
	}

	/**
	 * Test message fallbacks, bug #1495
	 *
	 * @dataProvider provideMessagesForFallback
	 */
	function testMessageFallbacks( $message, $lang, $expectedContent ) {
		$result = MessageCache::singleton()->get( $message, true, $lang );
		$this->assertEquals( $expectedContent, $result, "Message fallback failed." );
	}

	public static function provideMessagesForFallback() {
		return array(
			array( 'FallbackLanguageTest-Full', 'ab', 'ab' ),
			array( 'FallbackLanguageTest-Partial', 'ab', 'ru' ),
			array( 'FallbackLanguageTest-ContLang', 'ab', 'de' ),
			array( 'FallbackLanguageTest-English', 'ab', 'en' ),
			array( 'FallbackLanguageTest-None', 'ab', false ),
		);
	}

	/**
	 * There's a fallback case where the message key is given as fully qualified -- this
	 * should ignore the passed $lang and use the language from the key
	 *
	 * @dataProvider provideMessagesForFullKeys
	 */
	function testFullKeyBehaviour( $message, $lang, $expectedContent ) {
		$result = MessageCache::singleton()->get( $message, true, $lang, true );
		$this->assertEquals( $expectedContent, $result, "Full key message fallback failed." );
	}

	public static function provideMessagesForFullKeys() {
		return array(
			array( 'MessageCacheTest-FullKeyTest/ru', 'ru', 'ru' ),
			array( 'MessageCacheTest-FullKeyTest/ru', 'ab', 'ru' ),
			array( 'MessageCacheTest-FullKeyTest/ru/foo', 'ru', false ),
		);
	}

}
