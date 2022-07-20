<?php

use MediaWiki\MainConfigNames;
use MediaWiki\MainConfigSchema;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Database
 * @group Cache
 * @covers MessageCache
 */
class MessageCacheTest extends MediaWikiLangTestCase {

	protected function setUp(): void {
		parent::setUp();
		$this->configureLanguages();
		$this->getServiceContainer()->getMessageCache()->enable();
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

	public function addDBDataOnce() {
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
	 * @return RevisionRecord
	 */
	private function makePage( $title, $lang, $content = null ) {
		if ( $content === null ) {
			$content = $lang;
		}
		if ( $lang !== $this->getServiceContainer()->getContentLanguage()->getCode() ) {
			$title = "$title/$lang";
		}

		$title = Title::newFromText( $title, NS_MEDIAWIKI );
		$wikiPage = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );
		$content = ContentHandler::makeContent( $content, $title );
		$summary = CommentStoreComment::newUnsavedComment( "$lang translation test case" );

		$newRevision = $wikiPage->newPageUpdater( $this->getTestSysop()->getUser() )
			->setContent( SlotRecord::MAIN, $content )
			->saveRevision( $summary );

		$this->assertNotNull( $newRevision, 'Create page ' . $title->getPrefixedDBkey() );
		return $newRevision;
	}

	/**
	 * Test message fallbacks, T3495
	 *
	 * @dataProvider provideMessagesForFallback
	 */
	public function testMessageFallbacks( $message, $lang, $expectedContent ) {
		$result = $this->getServiceContainer()->getMessageCache()->get( $message, true, $lang );
		$this->assertEquals( $expectedContent, $result, "Message fallback failed." );
	}

	public function provideMessagesForFallback() {
		return [
			[ 'FallbackLanguageTest-Full', 'ab', 'ab' ],
			[ 'FallbackLanguageTest-Partial', 'ab', 'ru' ],
			[ 'FallbackLanguageTest-ContLang', 'ab', 'de' ],
			[ 'FallbackLanguageTest-None', 'ab', false ],

			// T48579
			[ 'FallbackLanguageTest-NoDervContLang', 'de', 'de/none' ],
			// UI language different from content language should only use de/none as last option
			[ 'FallbackLanguageTest-NoDervContLang', 'fit', 'de/none' ],
		];
	}

	public function testReplaceMsg() {
		$messageCache = $this->getServiceContainer()->getMessageCache();
		$message = 'go';
		$uckey = $this->getServiceContainer()->getContentLanguage()->ucfirst( $message );
		$oldText = $messageCache->get( $message ); // "Ausführen"

		$dbw = wfGetDB( DB_PRIMARY );
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
		// We need a WAN cache for this.
		$this->overrideConfigValues( [
			MainConfigNames::MainWANCache => CACHE_HASH,
			MainConfigNames::WANObjectCaches =>
				MainConfigSchema::getDefaultValue( MainConfigNames::WANObjectCaches ) + [
				'hash' => [
					'class'    => WANObjectCache::class,
					'cacheId'  => CACHE_HASH,
					'channels' => []
				]
			]
		] );

		$messageCache = $this->getServiceContainer()->getMessageCache();
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
		global $wgLanguageCode;

		$dbr = wfGetDB( DB_REPLICA );

		$messageCache = $this->getServiceContainer()->getMessageCache();
		$messageCache->getMsgFromNamespace( 'allpages', $wgLanguageCode );

		$this->assertSame( 0, $dbr->trxLevel() );
		$dbr->setFlag( DBO_TRX, $dbr::REMEMBER_PRIOR ); // make queries trigger TRX

		$messageCache->getMsgFromNamespace( 'go', $wgLanguageCode );

		$dbr->restoreFlags();

		$this->assertSame( 0, $dbr->trxLevel(), "No DB read queries (content language)" );
	}

	public function testNoDBAccessNonContentLanguage() {
		$dbr = wfGetDB( DB_REPLICA );

		$messageCache = $this->getServiceContainer()->getMessageCache();
		$messageCache->getMsgFromNamespace( 'allpages/nl', 'nl' );

		$this->assertSame( 0, $dbr->trxLevel() );
		$dbr->setFlag( DBO_TRX, $dbr::REMEMBER_PRIOR ); // make queries trigger TRX

		$messageCache->getMsgFromNamespace( 'go/nl', 'nl' );

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
		$title = Title::newFromLinkTarget( $r3->getPageAsLinkTarget() );
		$importRevision->setTitle( $title );
		$importRevision->setComment( 'Imported edit' );
		$importRevision->setTimestamp( '19991122001122' );
		$content = ContentHandler::makeContent( 'IMPORTED OLD TEST', $title );
		$importRevision->setContent( SlotRecord::MAIN, $content );
		$importRevision->setUsername( 'ext>Alan Smithee' );

		$importer = $this->getServiceContainer()->getWikiRevisionOldRevisionImporterNoUpdates();
		$importer->import( $importRevision );

		// Now, load the message from the wiki page
		$messageCache = $this->getServiceContainer()->getMessageCache();
		$messageCache->enable();
		$messageCache = TestingAccessWrapper::newFromObject( $messageCache );

		$cache = $messageCache->loadFromDB( 'de' );

		$this->assertArrayHasKey( $key, $cache );

		// Text in the cache has an extra space in front!
		$this->assertSame( ' ' . 'Test drei', $cache[$key] );
	}

	/**
	 * @dataProvider provideIsMainCacheable
	 * @param string|null $code The language code
	 * @param string $message The message key
	 * @param bool $expected
	 */
	public function testIsMainCacheable( $code, $message, $expected ) {
		$messageCache = TestingAccessWrapper::newFromObject(
			$this->getServiceContainer()->getMessageCache() );
		$this->assertSame( $expected, $messageCache->isMainCacheable( $message, $code ) );
	}

	public function provideIsMainCacheable() {
		$cases = [
			// $message                $expected
			[ 'allpages',              true ],
			[ 'Allpages',              true ],
			[ 'Allpages/bat',          true ],
			[ 'Conversiontable/zh-tw', true ],
			[ 'My_special_message',    false ],
		];
		foreach ( [ null, 'en', 'fr' ] as $code ) {
			foreach ( $cases as $case ) {
				yield array_merge( [ $code ], $case );
			}
		}
	}
}
