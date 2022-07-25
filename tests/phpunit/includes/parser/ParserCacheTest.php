<?php

namespace MediaWiki\Tests\Parser;

use BagOStuff;
use CacheTime;
use EmptyBagOStuff;
use HashBagOStuff;
use InvalidArgumentException;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Json\JsonCodec;
use MediaWiki\Page\PageRecord;
use MediaWiki\Page\PageStoreRecord;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Tests\Json\JsonUnserializableSuperClass;
use MediaWikiIntegrationTestCase;
use MWTimestamp;
use NullStatsdDataFactory;
use ParserCache;
use ParserOptions;
use ParserOutput;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Psr\Log\NullLogger;
use TestLogger;
use Title;
use User;
use Wikimedia\TestingAccessWrapper;
use WikiPage;

/**
 * @covers ParserCache
 * @package MediaWiki\Tests\Parser
 */
class ParserCacheTest extends MediaWikiIntegrationTestCase {

	/** @var int */
	private $time;

	/** @var string */
	private $cacheTime;

	/** @var PageRecord */
	private $page;

	protected function setUp(): void {
		parent::setUp();
		$this->time = time();
		$this->cacheTime = MWTimestamp::convert( TS_MW, $this->time + 1 );
		$this->page = $this->createPageRecord();

		MWTimestamp::setFakeTime( $this->time );
	}

	/**
	 * @param array $overrides
	 * @return PageRecord
	 */
	private function createPageRecord( array $overrides = [] ): PageRecord {
		return new PageStoreRecord( (object)array_merge( [
			'page_id' => 42,
			'page_namespace' => NS_MAIN,
			'page_title' => 'Testing_Testing',
			'page_latest' => 24,
			'page_is_new' => false,
			'page_is_redirect' => false,
			'page_touched' => $this->time,
			'page_lang' => 'qqx',
		], $overrides ), PageRecord::LOCAL );
	}

	/**
	 * @param HookContainer|null $hookContainer
	 * @param BagOStuff|null $storage
	 * @param LoggerInterface|null $logger
	 * @param WikiPageFactory|null $wikiPageFactory
	 * @return ParserCache
	 */
	private function createParserCache(
		HookContainer $hookContainer = null,
		BagOStuff $storage = null,
		LoggerInterface $logger = null,
		WikiPageFactory $wikiPageFactory = null
	): ParserCache {
		return new ParserCache(
			'test',
			$storage ?: new HashBagOStuff(),
			'19900220000000',
			$hookContainer ?: $this->createHookContainer( [] ),
			new JsonCodec(),
			new NullStatsdDataFactory(),
			$logger ?: new NullLogger(),
			$this->getServiceContainer()->getTitleFactory(),
			$wikiPageFactory ?: $this->getServiceContainer()->getWikiPageFactory()
		);
	}

	/**
	 * @return array
	 */
	private function getDummyUsedOptions(): array {
		return array_slice(
			ParserOptions::allCacheVaryingOptions(),
			0,
			2
		);
	}

	/**
	 * @return ParserOutput
	 */
	private function createDummyParserOutput(): ParserOutput {
		$parserOutput = new ParserOutput();
		$parserOutput->setText( 'TEST' );
		foreach ( $this->getDummyUsedOptions() as $option ) {
			$parserOutput->recordOption( $option );
		}
		$parserOutput->updateCacheExpiry( 4242 );
		return $parserOutput;
	}

	/**
	 * @covers ParserCache::getMetadata
	 */
	public function testGetMetadataMissing() {
		$cache = $this->createParserCache();
		$metadataFromCache = $cache->getMetadata( $this->page, ParserCache::USE_CURRENT_ONLY );
		$this->assertNull( $metadataFromCache );
	}

	/**
	 * @covers ParserCache::getMetadata
	 */
	public function testGetMetadataAllGood() {
		$cache = $this->createParserCache();
		$parserOutput = $this->createDummyParserOutput();

		$cache->save( $parserOutput, $this->page, ParserOptions::newFromAnon(), $this->cacheTime );

		$metadataFromCache = $cache->getMetadata( $this->page, ParserCache::USE_CURRENT_ONLY );
		$this->assertNotNull( $metadataFromCache );
		$this->assertSame( $this->getDummyUsedOptions(), $metadataFromCache->getUsedOptions() );
		$this->assertSame( 4242, $metadataFromCache->getCacheExpiry() );
		$this->assertSame( $this->page->getLatest(), $metadataFromCache->getCacheRevisionId() );
		$this->assertSame( $this->cacheTime, $metadataFromCache->getCacheTime() );
	}

	/**
	 * @covers ParserCache::getMetadata
	 */
	public function testGetMetadataExpired() {
		$cache = $this->createParserCache();
		$parserOutput = $this->createDummyParserOutput();
		$cache->save( $parserOutput, $this->page, ParserOptions::newFromAnon(), $this->cacheTime );

		$this->page = $this->createPageRecord( [ 'page_touched' => $this->time + 10000 ] );
		$this->assertNull( $cache->getMetadata( $this->page, ParserCache::USE_CURRENT_ONLY ) );
		$metadataFromCache = $cache->getMetadata( $this->page, ParserCache::USE_EXPIRED );
		$this->assertNotNull( $metadataFromCache );
		$this->assertSame( $this->getDummyUsedOptions(), $metadataFromCache->getUsedOptions() );
		$this->assertSame( 4242, $metadataFromCache->getCacheExpiry() );
		$this->assertSame( $this->page->getLatest(), $metadataFromCache->getCacheRevisionId() );
		$this->assertSame( $this->cacheTime, $metadataFromCache->getCacheTime() );
	}

	/**
	 * @covers ParserCache::getMetadata
	 */
	public function testGetMetadataOutdated() {
		$cache = $this->createParserCache();
		$parserOutput = $this->createDummyParserOutput();
		$cache->save( $parserOutput, $this->page, ParserOptions::newFromAnon(), $this->cacheTime );

		$this->page = $this->createPageRecord( [ 'page_latest' => $this->page->getLatest() + 1 ] );
		$this->assertNull( $cache->getMetadata( $this->page, ParserCache::USE_CURRENT_ONLY ) );
		$this->assertNull( $cache->getMetadata( $this->page, ParserCache::USE_EXPIRED ) );
		$metadataFromCache = $cache->getMetadata( $this->page, ParserCache::USE_OUTDATED );
		$this->assertSame( $this->getDummyUsedOptions(), $metadataFromCache->getUsedOptions() );
		$this->assertSame( 4242, $metadataFromCache->getCacheExpiry() );
		$this->assertNotSame( $this->page->getLatest(), $metadataFromCache->getCacheRevisionId() );
		$this->assertSame( $this->cacheTime, $metadataFromCache->getCacheTime() );
	}

	/**
	 * @covers ParserCache::makeParserOutputKey
	 */
	public function testMakeParserOutputKey() {
		$cache = $this->createParserCache();

		$options1 = ParserOptions::newFromAnon();
		$options1->setOption( $this->getDummyUsedOptions()[0], 'value1' );
		$key1 = $cache->makeParserOutputKey( $this->page, $options1, $this->getDummyUsedOptions() );
		$this->assertNotNull( $key1 );

		$options2 = ParserOptions::newFromAnon();
		$options2->setOption( $this->getDummyUsedOptions()[0], 'value2' );
		$key2 = $cache->makeParserOutputKey( $this->page, $options2, $this->getDummyUsedOptions() );
		$this->assertNotNull( $key2 );
		$this->assertNotSame( $key1, $key2 );
	}

	/*
	 * Test that fetching without storing first returns false.
	 * @covers ParserCache::get
	 */
	public function testGetEmpty() {
		$cache = $this->createParserCache();
		$options = ParserOptions::newFromAnon();

		$this->assertFalse( $cache->get( $this->page, $options ) );
	}

	/**
	 * Test that fetching with the same options return the saved value.
	 * @covers ParserCache::get
	 * @covers ParserCache::save
	 */
	public function testSaveGetSameOptions() {
		$cache = $this->createParserCache();
		$parserOutput = new ParserOutput( 'TEST_TEXT' );

		$options1 = ParserOptions::newFromAnon();
		$options1->setOption( $this->getDummyUsedOptions()[0], 'value1' );
		$cache->save( $parserOutput, $this->page, $options1, $this->cacheTime );

		$savedOutput = $cache->get( $this->page, $options1 );
		$this->assertInstanceOf( ParserOutput::class, $savedOutput );
		// ParserCache adds a comment to the HTML, so check if the result starts with page content.
		$this->assertStringStartsWith( 'TEST_TEXT', $savedOutput->getText() );
		$this->assertSame( $this->cacheTime, $savedOutput->getCacheTime() );
		$this->assertSame( $this->page->getLatest(), $savedOutput->getCacheRevisionId() );
	}

	/**
	 * Test that fetching with different unused option returns a value.
	 * @covers ParserCache::get
	 * @covers ParserCache::save
	 */
	public function testSaveGetDifferentUnusedOption() {
		$cache = $this->createParserCache();
		$optionName = $this->getDummyUsedOptions()[0];
		$parserOutput = new ParserOutput( 'TEST_TEXT' );

		$options1 = ParserOptions::newFromAnon();
		$options1->setOption( $optionName, 'value1' );
		$cache->save( $parserOutput, $this->page, $options1, $this->cacheTime );

		$options2 = ParserOptions::newFromAnon();
		$options2->setOption( $optionName, 'value2' );
		$savedOutput = $cache->get( $this->page, $options2 );
		$this->assertInstanceOf( ParserOutput::class, $savedOutput );
		// ParserCache adds a comment to the HTML, so check if the result starts with page content.
		$this->assertStringStartsWith( 'TEST_TEXT', $savedOutput->getText() );
		$this->assertSame( $this->cacheTime, $savedOutput->getCacheTime() );
		$this->assertSame( $this->page->getLatest(), $savedOutput->getCacheRevisionId() );
	}

	/**
	 * Test that non-cacheable output is not stored
	 * @covers ParserCache::save
	 * @covers ParserCache::get
	 */
	public function testDoesNotStoreNonCacheable() {
		$cache = $this->createParserCache();
		$parserOutput = new ParserOutput( 'TEST_TEXT' );
		$parserOutput->updateCacheExpiry( 0 );

		$options1 = ParserOptions::newFromAnon();
		$cache->save( $parserOutput, $this->page, $options1, $this->cacheTime );

		$this->assertFalse( $cache->get( $this->page, $options1 ) );
		$this->assertFalse( $cache->get( $this->page, $options1, true ) );
		$this->assertFalse( $cache->getDirty( $this->page, $options1 ) );
	}

	/**
	 * Test that ParserOptions::isSafeToCache is respected on save
	 * @covers ParserCache::save
	 */
	public function testDoesNotStoreNotSafeToCacheAndUsed() {
		$cache = $this->createParserCache();
		$parserOutput = new ParserOutput( 'TEST_TEXT' );
		$parserOutput->recordOption( 'wrapclass' );

		$options = ParserOptions::newFromAnon();
		$options->setOption( 'wrapclass', 'wrapwrap' );

		$cache->save( $parserOutput, $this->page, $options, $this->cacheTime );

		$this->assertFalse( $cache->get( $this->page, $options ) );
		$this->assertFalse( $cache->get( $this->page, $options, true ) );
		$this->assertFalse( $cache->getDirty( $this->page, $options ) );
	}

	/**
	 * Test that ParserOptions::isSafeToCache is respected on get
	 * @covers ParserCache::get
	 */
	public function testDoesNotGetNotSafeToCache() {
		$cache = $this->createParserCache();
		$parserOutput = new ParserOutput( 'TEST_TEXT' );
		$parserOutput->recordOption( 'wrapclass' );

		$cache->save( $parserOutput, $this->page, ParserOptions::newFromAnon(), $this->cacheTime );

		$otherOptions = ParserOptions::newFromAnon();
		$otherOptions->setOption( 'wrapclass', 'wrapwrap' );

		$this->assertFalse( $cache->get( $this->page, $otherOptions ) );
		$this->assertFalse( $cache->get( $this->page, $otherOptions, true ) );
		$this->assertFalse( $cache->getDirty( $this->page, $otherOptions ) );
	}

	/**
	 * Test that ParserOptions::isSafeToCache is respected on save
	 * @covers ParserCache::save
	 * @covers ParserCache::get
	 */
	public function testStoresNotSafeToCacheAndUnused() {
		$cache = $this->createParserCache();
		$parserOutput = new ParserOutput( 'TEST_TEXT' );

		$options = ParserOptions::newFromAnon();
		$options->setOption( 'wrapclass', 'wrapwrap' );

		$cache->save( $parserOutput, $this->page, $options, $this->cacheTime );
		$this->assertStringContainsString( 'TEST_TEXT', $cache->get( $this->page, $options )->getText() );
	}

	/**
	 * Test that fetching with different used option don't return a value.
	 * @covers ParserCache::get
	 * @covers ParserCache::save
	 */
	public function testSaveGetDifferentUsedOption() {
		$cache = $this->createParserCache();
		$parserOutput = new ParserOutput( 'TEST_TEXT' );
		$optionName = $this->getDummyUsedOptions()[0];
		$parserOutput->recordOption( $optionName );

		$options1 = ParserOptions::newFromAnon();
		$options1->setOption( $optionName, 'value1' );
		$cache->save( $parserOutput, $this->page, $options1, $this->cacheTime );

		$options2 = ParserOptions::newFromAnon();
		$options2->setOption( $optionName, 'value2' );
		$this->assertFalse( $cache->get( $this->page, $options2 ) );
	}

	/**
	 * Test that output with expired metadata can be retrieved with getDirty
	 * @covers ParserCache::getDirty
	 * @covers ParserCache::get
	 */
	public function testGetExpiredMetadata() {
		$cache = $this->createParserCache();
		$parserOutput = new ParserOutput( 'TEST_TEXT' );
		$parserOutput->updateCacheExpiry( 10 );

		$options1 = ParserOptions::newFromAnon();
		$cache->save( $parserOutput, $this->page, $options1, $this->cacheTime );

		MWTimestamp::setFakeTime( $this->time + 15 * 1000 );
		$this->assertFalse( $cache->get( $this->page, $options1 ) );
		$this->assertInstanceOf( ParserOutput::class,
			$cache->get( $this->page, $options1, true ) );
		$this->assertInstanceOf( ParserOutput::class,
			$cache->getDirty( $this->page, $options1 ) );
	}

	/**
	 * Test that expired output with not expired metadata can be retrieved with getDirty
	 * @covers ParserCache::getDirty
	 * @covers ParserCache::get
	 */
	public function testGetExpiredContent() {
		$cache = $this->createParserCache();
		$optionName = $this->getDummyUsedOptions()[0];

		$parserOutput1 = new ParserOutput( 'TEST_TEXT1' );
		$parserOutput1->recordOption( $optionName );
		$parserOutput1->updateCacheExpiry( 10 );
		$options1 = ParserOptions::newFromAnon();
		$options1->setOption( $optionName, 'value1' );
		$cache->save( $parserOutput1, $this->page, $options1, $this->cacheTime );

		$parserOutput2 = new ParserOutput( 'TEST_TEXT2' );
		$parserOutput2->recordOption( $optionName );
		$parserOutput2->updateCacheExpiry( 100500600 );
		$options2 = ParserOptions::newFromAnon();
		$options2->setOption( $optionName, 'value2' );
		$cache->save( $parserOutput2, $this->page, $options2, $this->cacheTime );

		MWTimestamp::setFakeTime( $this->time + 15 * 1000 );
		$this->assertFalse( $cache->get( $this->page, $options1 ) );
		$this->assertInstanceOf( ParserOutput::class,
			$cache->get( $this->page, $options1, true ) );
		$this->assertInstanceOf( ParserOutput::class,
			$cache->getDirty( $this->page, $options1 ) );
	}

	/**
	 * Test that output with outdated metadata can be retrieved with getDirty
	 * @covers ParserCache::getDirty
	 * @covers ParserCache::get
	 */
	public function testGetOutdatedMetadata() {
		$cache = $this->createParserCache();
		$parserOutput = new ParserOutput( 'TEST_TEXT' );

		$options1 = ParserOptions::newFromAnon();
		$cache->save( $parserOutput, $this->page, $options1, $this->cacheTime );
		$this->assertInstanceOf( ParserOutput::class,
			$cache->get( $this->page, $options1 ) );

		$this->page = $this->createPageRecord( [ 'page_latest' => $this->page->getLatest() + 1 ] );
		$this->assertFalse( $cache->get( $this->page, $options1 ) );
		$this->assertInstanceOf( ParserOutput::class,
			$cache->get( $this->page, $options1, true ) );
		$this->assertInstanceOf( ParserOutput::class,
			$cache->getDirty( $this->page, $options1 ) );
	}

	/**
	 * Test that outdated output with good metadata can be retrieved with getDirty
	 * @covers ParserCache::getDirty
	 * @covers ParserCache::get
	 */
	public function testGetOutdatedContent() {
		$cache = $this->createParserCache();
		$optionName = $this->getDummyUsedOptions()[0];

		$parserOutput1 = new ParserOutput( 'TEST_TEXT' );
		$parserOutput1->recordOption( $optionName );
		$options1 = ParserOptions::newFromAnon();
		$options1->setOption( $optionName, 'value1' );
		$cache->save( $parserOutput1, $this->page, $options1, $this->cacheTime );

		$this->page = $this->createPageRecord( [ 'page_latest' => $this->page->getLatest() + 1 ] );
		$parserOutput2 = new ParserOutput( 'TEST_TEXT' );
		$parserOutput2->recordOption( $optionName );
		$options2 = ParserOptions::newFromAnon();
		$options2->setOption( $optionName, 'value2' );
		$cache->save( $parserOutput2, $this->page, $options2, $this->cacheTime );

		$this->assertFalse( $cache->get( $this->page, $options1 ) );
		$this->assertInstanceOf( ParserOutput::class,
			$cache->get( $this->page, $options1, true ) );
		$this->assertInstanceOf( ParserOutput::class,
			$cache->getDirty( $this->page, $options1 ) );
	}

	/**
	 * Test that fetching after deleting a key returns false.
	 * @covers ParserCache::deleteOptionsKey
	 */
	public function testDeleteOptionsKey() {
		$cache = $this->createParserCache();
		$parserOutput = new ParserOutput( 'TEST_TEXT' );

		$options1 = ParserOptions::newFromAnon();
		$cache->save( $parserOutput, $this->page, $options1, $this->cacheTime );
		$this->assertInstanceOf( ParserOutput::class,
			$cache->get( $this->page, $options1 ) );
		$cache->deleteOptionsKey( $this->page );

		$this->assertFalse( $cache->get( $this->page, $options1 ) );
	}

	/**
	 * Test that RejectParserCacheValue hook can reject ParserOutput
	 * @covers ParserCache::get
	 */
	public function testRejectedByHook() {
		$parserOutput = new ParserOutput( 'TEST_TEXT' );
		$options = ParserOptions::newFromAnon();
		$options->setOption( $this->getDummyUsedOptions()[0], 'value1' );

		$wikiPageMock = $this->createMock( WikiPage::class );
		$wikiPageMock->method( 'getContentModel' )->willReturn( 'wikitext' );
		$wikiPageFactoryMock = $this->createMock( WikiPageFactory::class );
		$wikiPageFactoryMock->method( 'newFromTitle' )
			->with( $this->page )
			->willReturn( $wikiPageMock );
		$hookContainer = $this->createHookContainer( [
			'RejectParserCacheValue' =>
				function ( ParserOutput $value, WikiPage $hookPage, ParserOptions $popts )
				use ( $wikiPageMock, $parserOutput, $options ) {
					$this->assertEquals( $parserOutput, $value );
					$this->assertSame( $wikiPageMock, $hookPage );
					$this->assertSame( $options, $popts );
					return false;
				}
		] );
		$cache = $this->createParserCache( $hookContainer, null, null, $wikiPageFactoryMock );
		$cache->save( $parserOutput, $this->page, $options, $this->cacheTime );
		$this->assertFalse( $cache->get( $this->page, $options ) );
	}

	/**
	 * Test that ParserCacheSaveComplete hook is run
	 * @covers ParserCache::save
	 */
	public function testParserCacheSaveCompleteHook() {
		$parserOutput = new ParserOutput( 'TEST_TEXT' );
		$options = ParserOptions::newFromAnon();
		$options->setOption( $this->getDummyUsedOptions()[0], 'value1' );

		$hookContainer = $this->createHookContainer( [
			'ParserCacheSaveComplete' =>
				function (
					ParserCache $hookCache, ParserOutput $value, Title $hookTitle, ParserOptions $popts, int $revId
				) use ( $parserOutput, $options ) {
					$this->assertSame( $parserOutput, $value );
					$this->assertSame( $options, $popts );
					$this->assertSame( 42, $revId );
				}
		] );
		$cache = $this->createParserCache( $hookContainer );
		$cache->save( $parserOutput, $this->page, $options, $this->cacheTime, 42 );
	}

	/**
	 * Tests that parser cache respects skipped if page does not exist
	 * @covers ParserCache::get
	 */
	public function testSkipIfNotExist() {
		$mockPage = $this->createNoOpMock( PageRecord::class, [ 'exists', 'assertWiki' ] );
		$mockPage->method( 'exists' )->willReturn( false );
		$wikiPageMock = $this->createMock( WikiPage::class );
		$wikiPageMock->method( 'getContentModel' )->willReturn( 'wikitext' );
		$wikiPageFactoryMock = $this->createMock( WikiPageFactory::class );
		$wikiPageFactoryMock->method( 'newFromTitle' )
			->with( $mockPage )
			->willReturn( $wikiPageMock );
		$cache = $this->createParserCache( null, null, null, $wikiPageFactoryMock );
		$this->assertFalse( $cache->get( $mockPage, ParserOptions::newFromAnon() ) );
	}

	/**
	 * Tests that parser cache respects skipped if page is redirect
	 * @covers ParserCache::get
	 */
	public function testSkipIfRedirect() {
		$cache = $this->createParserCache();
		$page = $this->createPageRecord( [
			'page_is_redirect' => true
		] );
		$this->assertFalse( $cache->get( $page, ParserOptions::newFromAnon() ) );
	}

	/**
	 * Tests that getCacheStorage returns underlying BagOStuff
	 * @covers ParserCache::getCacheStorage
	 */
	public function testGetCacheStorage() {
		$storage = new EmptyBagOStuff();
		$cache = $this->createParserCache( null, $storage );
		$this->assertSame( $storage, $cache->getCacheStorage() );
	}

	/**
	 * @covers ParserCache::save
	 */
	public function testSaveNoText() {
		$this->expectException( InvalidArgumentException::class );
		$this->createParserCache()->save(
			new ParserOutput( null ),
			$this->page,
			ParserOptions::newFromAnon()
		);
	}

	public function provideCorruptData() {
		yield 'JSON serialization, bad data' => [ 'bla bla' ];
		yield 'JSON serialization, no _class_' => [ '{"test":"test"}' ];
		yield 'JSON serialization, non-existing _class_' => [ '{"_class_":"NonExistentBogusClass"}' ];
		$wrongInstance = new JsonUnserializableSuperClass( 'test' );
		yield 'JSON serialization, wrong class' => [ json_encode( $wrongInstance->jsonSerialize() ) ];
	}

	/**
	 * Test that we handle corrupt data gracefully.
	 * This is important for forward-compatibility with JSON serialization.
	 * We want to be sure that we don't crash horribly if we have to roll
	 * back to a version of the code that doesn't know about JSON.
	 *
	 * @dataProvider provideCorruptData
	 * @covers ParserCache::get
	 * @covers ParserCache::restoreFromJson
	 * @param string $data
	 */
	public function testCorruptData( string $data ) {
		$cache = $this->createParserCache( null, new HashBagOStuff() );
		$parserOutput = new ParserOutput( 'TEST_TEXT' );

		$options1 = ParserOptions::newFromAnon();
		$cache->save( $parserOutput, $this->page, $options1, $this->cacheTime );

		$outputKey = $cache->makeParserOutputKey(
			$this->page,
			$options1,
			$parserOutput->getUsedOptions()
		);

		$cache->getCacheStorage()->set( $outputKey, $data );

		// just make sure we don't crash and burn
		$this->assertFalse( $cache->get( $this->page, $options1 ) );
	}

	/**
	 * Test that we handle corrupt data gracefully.
	 * This is important for forward-compatibility with JSON serialization.
	 * We want to be sure that we don't crash horribly if we have to roll
	 * back to a version of the code that doesn't know about JSON.
	 *
	 * @covers ParserCache::getMetadata
	 */
	public function testCorruptMetadata() {
		$cacheStorage = new HashBagOStuff();
		$cache = $this->createParserCache( null, $cacheStorage );
		$parserOutput = new ParserOutput( 'TEST_TEXT' );

		$options1 = ParserOptions::newFromAnon();
		$cache->save( $parserOutput, $this->page, $options1, $this->cacheTime );

		// Mess up the metadata
		$optionsKey = TestingAccessWrapper::newFromObject( $cache )->makeMetadataKey(
			$this->page
		);
		$cacheStorage->set( $optionsKey, 'bad data' );

		// Recreate the cache to drop in-memory cached metadata.
		$cache = $this->createParserCache( null, $cacheStorage );

		// just make sure we don't crash and burn
		$this->assertNull( $cache->getMetadata( $this->page ) );
	}

	/**
	 * Test what happens when upgrading from 1.35 or earlier,
	 * when old cache entries do not yet use JSON.
	 *
	 * @covers ParserCache::get
	 */
	public function testMigrationToJson() {
		$bagOStuff = new HashBagOStuff();

		$cache = $this->getMockBuilder( ParserCache::class )
			->setConstructorArgs( [
				'test',
				$bagOStuff,
				'19900220000000',
				$this->createHookContainer( [] ),
				new JsonCodec(),
				new NullStatsdDataFactory(),
				new NullLogger(),
				$this->getServiceContainer()->getTitleFactory(),
				$this->getServiceContainer()->getWikiPageFactory()
			] )
			->onlyMethods( [ 'convertForCache' ] )
			->getMock();

		// Emulate pre-1.36 behavior: rely on native PHP serialization.
		// Note that backwards compatibility of the actual serialization is covered
		// by ParserOutputTest which uses various versions of serialized data
		// under tests/phpunit/data/ParserCache.
		$cache->method( 'convertForCache' )->willReturnCallback(
			static function ( CacheTime $obj, string $key ) {
				return $obj;
			}
		);

		$parserOutput1 = new ParserOutput( 'Lorem Ipsum' );

		$options = ParserOptions::newFromAnon();
		$cache->save( $parserOutput1, $this->page, $options, $this->cacheTime );

		// emulate migration to JSON
		$cache = $this->createParserCache( null, $bagOStuff );

		// make sure we can load non-json cache data
		$cachedOutput = $cache->get( $this->page, $options );
		$this->assertEquals( $parserOutput1, $cachedOutput );

		// now test that the cache works when using JSON
		$parserOutput2 = new ParserOutput( 'dolor sit amet' );
		$cache->save( $parserOutput2, $this->page, $options, $this->cacheTime );

		// make sure we can load json cache data
		$cachedOutput = $cache->get( $this->page, $options );
		$this->assertEquals( $parserOutput2, $cachedOutput );
	}

	/**
	 * @covers ParserCache::convertForCache
	 */
	public function testNonSerializableJsonIsReported() {
		$testLogger = new TestLogger( true );
		$cache = $this->createParserCache( null, null, $testLogger );

		$parserOutput = $this->createDummyParserOutput();
		$parserOutput->setExtensionData( 'test', new User() );
		$cache->save( $parserOutput, $this->page, ParserOptions::newFromAnon() );
		$this->assertArraySubmapSame(
			[ [ LogLevel::ERROR, 'Unable to serialize JSON' ] ],
			$testLogger->getBuffer()
		);
	}

	/**
	 * @covers ParserCache::convertForCache
	 */
	public function testCyclicStructuresDoNotBlowUpInJson() {
		$testLogger = new TestLogger( true );
		$cache = $this->createParserCache( null, null, $testLogger );

		$parserOutput = $this->createDummyParserOutput();
		$cyclicArray = [ 'a' => 'b' ];
		$cyclicArray['c'] = &$cyclicArray;
		$parserOutput->setExtensionData( 'test', $cyclicArray );
		$cache->save( $parserOutput, $this->page, ParserOptions::newFromAnon() );
		$this->assertArraySubmapSame(
			[ [ LogLevel::ERROR, 'Unable to serialize JSON' ] ],
			$testLogger->getBuffer()
		);
	}

	/**
	 * Tests that unicode characters are not \u escaped
	 *
	 * @covers ParserCache::convertForCache
	 */
	public function testJsonEncodeUnicode() {
		$unicodeCharacter = "Э";
		$cache = $this->createParserCache( null, new HashBagOStuff() );

		$parserOutput = $this->createDummyParserOutput();
		$parserOutput->setText( $unicodeCharacter );
		$cache->save( $parserOutput, $this->page, ParserOptions::newFromAnon() );
		$json = $cache->getCacheStorage()->get(
			$cache->makeParserOutputKey( $this->page, ParserOptions::newFromAnon() )
		);
		$this->assertStringNotContainsString( "\u003E", $json );
		$this->assertStringContainsString( $unicodeCharacter, $json );
	}
}
