<?php

use MediaWiki\MediaWikiServices;
use Wikimedia\TestingAccessWrapper;

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
	 *
	 * @return Revision
	 */
	protected function makePage( $title, $lang, $content = null ) {
		if ( $content === null ) {
			$content = $lang;
		}
		if ( $lang !== MediaWikiServices::getInstance()->getContentLanguage()->getCode() ) {
			$title = "$title/$lang";
		}

		$title = Title::newFromText( $title, NS_MEDIAWIKI );
		$wikiPage = new WikiPage( $title );
		$contentHandler = ContentHandler::makeContent( $content, $title );
		$status = $wikiPage->doEditContent( $contentHandler, "$lang translation test case" );

		// sanity
		$this->assertTrue( $status->isOK(), 'Create page ' . $title->getPrefixedDBkey() );
		return $status->value['revision'];
	}

	/**
	 * Test message fallbacks, T3495
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

			// T48579
			[ 'FallbackLanguageTest-NoDervContLang', 'de', 'de/none' ],
			// UI language different from content language should only use de/none as last option
			[ 'FallbackLanguageTest-NoDervContLang', 'fit', 'de/none' ],
		];
	}

	public function testReplaceMsg() {
		$messageCache = MessageCache::singleton();
		$message = 'go';
		$uckey = MediaWikiServices::getInstance()->getContentLanguage()->ucfirst( $message );
		$oldText = $messageCache->get( $message ); // "Ausführen"

		$dbw = wfGetDB( DB_MASTER );
		$dbw->startAtomic( __METHOD__ ); // simulate request and block deferred updates
		$messageCache->replace( $uckey, 'Allez!' );
		$this->assertEquals( 'Allez!',
			$messageCache->getMsgFromNamespace( $uckey, 'de' ),
			'Updates are reflected in-process immediately' );
		$this->assertEquals( 'Allez!',
			$messageCache->get( $message ),
			'Updates are reflected in-process immediately' );
		$this->makePage( 'Go', 'de', 'Race!' );
		$dbw->endAtomic( __METHOD__ );

		$this->assertSame( 0,
			DeferredUpdates::pendingUpdatesCount(),
			'Post-commit deferred update triggers a run of all updates' );

		$this->assertEquals( 'Race!', $messageCache->get( $message ), 'Correct final contents' );

		$this->makePage( 'Go', 'de', $oldText );
		$messageCache->replace( $uckey, $oldText ); // deferred update runs immediately
		$this->assertEquals( $oldText, $messageCache->get( $message ), 'Content restored' );
	}

	public function testReplaceCache() {
		global $wgWANObjectCaches;

		// We need a WAN cache for this.
		$this->setMwGlobals( [
			'wgMainWANCache' => 'hash',
			'wgWANObjectCaches' => $wgWANObjectCaches + [
				'hash' => [
					'class'    => WANObjectCache::class,
					'cacheId'  => 'hash',
					'channels' => []
				]
			]
		] );

		$messageCache = MessageCache::singleton();
		$messageCache->enable();

		// Populate one key
		$this->makePage( 'Key1', 'de', 'Value1' );
		$this->assertSame( 0,
			DeferredUpdates::pendingUpdatesCount(),
			'Post-commit deferred update triggers a run of all updates' );
		$this->assertEquals( 'Value1', $messageCache->get( 'Key1' ), 'Key1 was successfully edited' );

		// Screw up the database so MessageCache::loadFromDB() will
		// produce the wrong result for reloading Key1
		$this->db->delete(
			'page', [ 'page_namespace' => NS_MEDIAWIKI, 'page_title' => 'Key1' ], __METHOD__
		);

		// Populate the second key
		$this->makePage( 'Key2', 'de', 'Value2' );
		$this->assertSame( 0,
			DeferredUpdates::pendingUpdatesCount(),
			'Post-commit deferred update triggers a run of all updates' );
		$this->assertEquals( 'Value2', $messageCache->get( 'Key2' ), 'Key2 was successfully edited' );

		// Now test that the second edit didn't reload Key1
		$this->assertEquals( 'Value1', $messageCache->get( 'Key1' ),
			'Key1 wasn\'t reloaded by edit of Key2' );
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

	public function testNoDBAccessContentLanguage() {
		global $wgContLanguageCode;

		$dbr = wfGetDB( DB_REPLICA );

		MessageCache::singleton()->getMsgFromNamespace( 'allpages', $wgContLanguageCode );

		$this->assertSame( 0, $dbr->trxLevel() );
		$dbr->setFlag( DBO_TRX, $dbr::REMEMBER_PRIOR ); // make queries trigger TRX

		MessageCache::singleton()->getMsgFromNamespace( 'go', $wgContLanguageCode );

		$dbr->restoreFlags();

		$this->assertSame( 0, $dbr->trxLevel(), "No DB read queries (content language)" );
	}

	public function testNoDBAccessNonContentLanguage() {
		$dbr = wfGetDB( DB_REPLICA );

		MessageCache::singleton()->getMsgFromNamespace( 'allpages/nl', 'nl' );

		$this->assertSame( 0, $dbr->trxLevel() );
		$dbr->setFlag( DBO_TRX, $dbr::REMEMBER_PRIOR ); // make queries trigger TRX

		MessageCache::singleton()->getMsgFromNamespace( 'go/nl', 'nl' );

		$dbr->restoreFlags();

		$this->assertSame( 0, $dbr->trxLevel(), "No DB read queries (non-content language)" );
	}

	/**
	 * Regression test for T218918
	 */
	public function testLoadFromDB_fetchLatestRevision() {
		// Create three revisions of the same message page.
		// Must be an existing message key.
		$key = 'Log';
		$this->makePage( $key, 'de', 'Test eins' );
		$this->makePage( $key, 'de', 'Test zwei' );
		$r3 = $this->makePage( $key, 'de', 'Test drei' );

		// Create an out-of-sequence revision by importing a
		// revision with an old timestamp. Hacky.
		$importRevision = new WikiRevision( new HashConfig() );
		$importRevision->setTitle( $r3->getTitle() );
		$importRevision->setComment( 'Imported edit' );
		$importRevision->setTimestamp( '19991122001122' );
		$importRevision->setText( 'IMPORTED OLD TEST' );
		$importRevision->setUsername( 'ext>Alan Smithee' );

		$importer = MediaWikiServices::getInstance()->getWikiRevisionOldRevisionImporterNoUpdates();
		$importer->import( $importRevision );

		// Now, load the message from the wiki page
		$messageCache = MessageCache::singleton();
		$messageCache->enable();
		$messageCache = TestingAccessWrapper::newFromObject( $messageCache );

		$cache = $messageCache->loadFromDB( 'de' );

		$this->assertArrayHasKey( $key, $cache );

		// Text in the cache has an extra space in front!
		$this->assertSame( ' ' . 'Test drei', $cache[$key] );
	}

}
