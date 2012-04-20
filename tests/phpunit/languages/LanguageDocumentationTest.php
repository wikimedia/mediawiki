<?php
/**
 * @medium
 */
class LanguageDocumentationTest extends MediaWikiTestCase {

	protected static $keys;

	// Hold the old l10ncache configuration which we are overriding
	// in class setup
	protected static $savedL10nCacheConf;

	static function setUpBeforeClass() {
		$l10nCache = Language::getLocalisationCache();
		// Save value to restore them in tearDownAfterClass
		self::$savedL10nCacheConf = array(
			'cacheExtensions' => $l10nCache->cacheExtensions,
			'forceRecache'    => $l10nCache->forceRecache,
		);
		// Disable loading of extensions messages
		$l10nCache->cacheExtensions = false;
		// Make sure we will reload the l10n
		$l10nCache->forceRecache    = true;

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

	static function tearDownAfterClass() {

		// Restore l10n cache configuration

		$l10nCache = Language::getLocalisationCache();
		$l10nCache->cacheExtensions = self::$savedL10nCacheConf['cacheExtensions'];
		// Make sure file will be recached, though we have probably unloaded
		// any cached language already.
		$l10nCache->forceRecache    = true;
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
	 * @dataProvider provideLanguageKeys
	 */
	function testLanguagesAreCompletelyTranslated( $lang ) {
		// Load all keys for a language, that is populating the l10n cache
		// we definitely want to clean up after test is completed
		$langKeys = Language::getMessagesFor( $lang );

		$this->assertEquals(
			array()
			, array_intersect( $langKeys, self::$keys['en'] )
			, "{$lang} should have all the English message keys"
		);

		// clear l10n cache to save up memory
		Language::getLocalisationCache()->unload( $lang );
	}

	/**
	 * Provide all languages BUT english (en)
	 */
	function provideLanguageKeys() {
		$cases = array();

		$langs = Language::getLanguageNames();
		unset( $langs['en'] );

		// TEMP HACK, limit test to some languages
		$tempList = array(
			'fr' => $langs['fr'],
			'nl' => $langs['nl'],
			'de' => $langs['de'],
		);
		$langs = $tempList;

		foreach( $langs as $code => $name ) {
			$cases[] = array( $code );
		}
		return $cases;
	}
}
