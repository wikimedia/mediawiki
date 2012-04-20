<?php
/**
 * @medium
 */
class LanguageDocumentationTest extends MediaWikiTestCase {

	protected static $keys;

	static function setUpBeforeClass() {
		self::$keys = array(
			'qqq' => Language::getMessageKeysFor( 'qqq' ),
			'en'  => Language::getMessageKeysFor( 'en' ),
		);

		global $IP, $wgMessageStructure;
		if( !isset( $wgMessageStructure ) ) {
			require_once( $IP . '/maintenance/language/messages.inc' );
		}

		foreach( $wgMessageStructure as $block ) {
			foreach( $block as $key ) {
				self::$keys['messages.inc'][] = $key;
			}
		}
	}

	/**
	 * Compares en against qqq, make sure all messages are documented
	 */
	function testAllEnglishMessagesAreDocumentedInQqq() {
		$delta = array_diff( self::$keys['en'], self::$keys['qqq'] );
		$this->assertEmpty( $delta,
			'All English messages should be documented in qqq'
		);
	}
	/**
	 * Compares qqq against en, make sure qqq does not document removed messages
	 */
	function testQqqDocumentsExistingMessages() {
		$delta = array_diff( self::$keys['qqq'], self::$keys['en'] );
		$this->assertEmpty( $delta,
			'Qqq should only document existing English messages.'
		);
	}

	/**
	 * Compares en against messages.inc
	 */
	function testMessageInMessagesincFile() {
		$this->assertEquals(
			array()
			, array_diff( self::$keys['en'], self::$keys['messages.inc'] )
			, 'All messages should be described in messages.inc'
		);
	}

	/**
	 * Tests that languages have all the keys defined using En as reference
	 *
	 * This test will emit lot of failure until nice translators
	 * from translatewiki.net finish up the translations. So this test
	 * should probably only be ran before a new MediaWiki release.
	 *
	 * @depends testAllEnglishMessagesAreDocumentedInQqq
	 * @depends testQqqDocumentsExistingMessages
	 *
	 * @group Utility
	 *
	 * @dataProvider provideLanguageKeys
	 */
	function testLanguagesAreCompletelyTranslated( $lang ) {
		$langKeys = Language::getMessageKeysFor( $lang );

		$this->assertEquals(
			array()
			, array_diff( $langKeys, self::$keys['en'] )
			, "{$lang} should have all the English message keys"
		);
	}


	/**
	 * Provide all languages BUT english (en)
	 */
	function provideLanguageKeys() {
		$cases = array();

		$langs = Language::getLanguageNames();
		unset( $langs['en'] );

		foreach( $langs as $code => $name ) {
			$cases[] = array( $code );
		}
		return $cases;
	}
}
