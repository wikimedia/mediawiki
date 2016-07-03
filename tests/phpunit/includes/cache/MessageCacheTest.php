<?php

/**
 * @group Database
 * @group Cache
 * @covers MessageCache
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
		$this->setUserLang( 'de' );
		$this->setContentLang( 'de' );
	}

	function addDBDataOnce() {
		$this->configureLanguages();

		// Set up messages and fallbacks ab -> ru -> de
		$this->makePage( 'FallbackLanguageTest-Full', 'ab' );
		$this->makePage( 'FallbackLanguageTest-Full', 'ru' );
		$this->makePage( 'FallbackLanguageTest-Full', 'de' );

		// Fallbacks where ab does not exist
		$this->makePage( 'FallbackLanguageTest-Partial', 'ru' );
		$this->makePage( 'FallbackLanguageTest-Partial', 'de' );

		// Fallback to the content language
		$this->makePage( 'FallbackLanguageTest-ContLang', 'de' );

		// Add customizations for an existing message.
		$this->makePage( 'sunday', 'ru' );

		// Full key tests -- always want russian
		$this->makePage( 'MessageCacheTest-FullKeyTest', 'ab' );
		$this->makePage( 'MessageCacheTest-FullKeyTest', 'ru' );

		// In content language -- get base if no derivative
		$this->makePage( 'FallbackLanguageTest-NoDervContLang', 'de', 'de/none' );
	}

	/**
	 * Helper function for addDBData -- adds a simple page to the database
	 *
	 * @param string $title Title of page to be created
	 * @param string $lang Language and content of the created page
	 * @param string|null $content Content of the created page, or null for a generic string
	 */
	protected function makePage( $title, $lang, $content = null ) {
		global $wgContLang;

		if ( $content === null ) {
			$content = $lang;
		}
		if ( $lang !== $wgContLang->getCode() ) {
			$title = "$title/$lang";
		}

		$title = Title::newFromText( $title, NS_MEDIAWIKI );
		$wikiPage = new WikiPage( $title );
		$contentHandler = ContentHandler::makeContent( $content, $title );
		$wikiPage->doEditContent( $contentHandler, "$lang translation test case" );
	}

	/**
	 * Test message fallbacks, bug #1495
	 *
	 * @dataProvider provideMessagesForFallback
	 */
	public function testMessageFallbacks( $message, $lang, $expectedContent ) {
		$result = MessageCache::singleton()->get( $message, true, $lang );
		$this->assertEquals( $expectedContent, $result, "Message fallback failed." );
	}

	function provideMessagesForFallback() {
		return [
			[ 'FallbackLanguageTest-Full', 'ab', 'ab' ],
			[ 'FallbackLanguageTest-Partial', 'ab', 'ru' ],
			[ 'FallbackLanguageTest-ContLang', 'ab', 'de' ],
			[ 'FallbackLanguageTest-None', 'ab', false ],

			// Existing message with customizations on the fallbacks
			[ 'sunday', 'ab', 'амҽыш' ],

			// bug 46579
			[ 'FallbackLanguageTest-NoDervContLang', 'de', 'de/none' ],
			// UI language different from content language should only use de/none as last option
			[ 'FallbackLanguageTest-NoDervContLang', 'fit', 'de/none' ],
		];
	}

	/**
	 * There's a fallback case where the message key is given as fully qualified -- this
	 * should ignore the passed $lang and use the language from the key
	 *
	 * @dataProvider provideMessagesForFullKeys
	 */
	public function testFullKeyBehaviour( $message, $lang, $expectedContent ) {
		$result = MessageCache::singleton()->get( $message, true, $lang, true );
		$this->assertEquals( $expectedContent, $result, "Full key message fallback failed." );
	}

	function provideMessagesForFullKeys() {
		return [
			[ 'MessageCacheTest-FullKeyTest/ru', 'ru', 'ru' ],
			[ 'MessageCacheTest-FullKeyTest/ru', 'ab', 'ru' ],
			[ 'MessageCacheTest-FullKeyTest/ru/foo', 'ru', false ],
		];
	}

	/**
	 * @dataProvider provideNormalizeKey
	 */
	public function testNormalizeKey( $key, $expected ) {
		$actual = MessageCache::normalizeKey( $key );
		$this->assertEquals( $expected, $actual );
	}

	public function provideNormalizeKey() {
		return [
			[ 'Foo', 'foo' ],
			[ 'foo', 'foo' ],
			[ 'fOo', 'fOo' ],
			[ 'FOO', 'fOO' ],
			[ 'Foo bar', 'foo_bar' ],
			[ 'Ćab', 'ćab' ],
			[ 'Ćab_e 3', 'ćab_e_3' ],
			[ 'ĆAB', 'ćAB' ],
			[ 'ćab', 'ćab' ],
			[ 'ćaB', 'ćaB' ],
		];
	}
}
