<?php

namespace MediaWiki\Tests\Parser;

use InvalidArgumentException;
use MediaWiki\Content\WikitextContent;
use MediaWiki\Json\JsonCodec;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\RevisionOutputCache;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Tests\Mocks\Json\JsonDeserializableSuperClass;
use MediaWiki\User\User;
use MediaWiki\Utils\MWTimestamp;
use MediaWikiIntegrationTestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Psr\Log\NullLogger;
use TestLogger;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\ObjectCache\HashBagOStuff;
use Wikimedia\ObjectCache\WANObjectCache;
use Wikimedia\Stats\StatsFactory;
use Wikimedia\TestingAccessWrapper;
use Wikimedia\UUID\GlobalIdGenerator;

/**
 * @covers \MediaWiki\Parser\RevisionOutputCache
 */
class RevisionOutputCacheTest extends MediaWikiIntegrationTestCase {

	/** @var int */
	private $time;

	/** @var string */
	private $cacheTime;

	/** @var RevisionRecord */
	private $revision;

	protected function setUp(): void {
		parent::setUp();

		$this->time = time();
		$this->cacheTime = MWTimestamp::convert( TS_MW, $this->time + 1 );
		MWTimestamp::setFakeTime( $this->time );

		$this->revision = new MutableRevisionRecord(
			PageIdentityValue::localIdentity( 42, NS_MAIN, 'Testing_Testing' ),
			RevisionRecord::LOCAL
		);
		$this->revision->setId( 24 );
		$this->revision->setTimestamp( MWTimestamp::convert( TS_MW, $this->time ) );
		$this->revision->setSlot(
			SlotRecord::newUnsaved(
				SlotRecord::MAIN,
				new WikitextContent( 'test test test' )
			)
		);
	}

	/**
	 * @param BagOStuff|null $storage
	 * @param LoggerInterface|null $logger
	 * @param int $expiry
	 * @param string $epoch
	 *
	 * @return RevisionOutputCache
	 */
	private function createRevisionOutputCache(
		?BagOStuff $storage = null,
		?LoggerInterface $logger = null,
		$expiry = 3600,
		$epoch = '19900220000000'
	): RevisionOutputCache {
		$globalIdGenerator = $this->createMock( GlobalIdGenerator::class );
		$globalIdGenerator->method( 'newUUIDv1' )->willReturn( 'uuid-uuid' );
		return new RevisionOutputCache(
			'test',
			new WANObjectCache( [ 'cache' => $storage ?: new HashBagOStuff() ] ),
			$expiry,
			$epoch,
			new JsonCodec(),
			StatsFactory::newNull(),
			$logger ?: new NullLogger(),
			$globalIdGenerator
		);
	}

	private function getDummyUsedOptions(): array {
		return array_slice(
			ParserOptions::allCacheVaryingOptions(),
			0,
			2
		);
	}

	private function createDummyParserOutput(): ParserOutput {
		$parserOutput = new ParserOutput();
		$parserOutput->setRawText( 'TEST' );
		foreach ( $this->getDummyUsedOptions() as $option ) {
			$parserOutput->recordOption( $option );
		}
		$parserOutput->updateCacheExpiry( 4242 );
		return $parserOutput;
	}

	/**
	 * @covers \MediaWiki\Parser\RevisionOutputCache::makeParserOutputKey
	 */
	public function testMakeParserOutputKey() {
		$cache = $this->createRevisionOutputCache();

		$options1 = ParserOptions::newFromAnon();
		$options1->setOption( $this->getDummyUsedOptions()[0], 'value1' );
		$key1 = $cache->makeParserOutputKey( $this->revision, $options1, $this->getDummyUsedOptions() );
		$this->assertNotNull( $key1 );

		$options2 = ParserOptions::newFromAnon();
		$options2->setOption( $this->getDummyUsedOptions()[0], 'value2' );
		$key2 = $cache->makeParserOutputKey( $this->revision, $options2, $this->getDummyUsedOptions() );
		$this->assertNotNull( $key2 );
		$this->assertNotSame( $key1, $key2 );
	}

	/**
	 * Test that fetching without storing first returns false.
	 * @covers \MediaWiki\Parser\RevisionOutputCache::get
	 */
	public function testGetEmpty() {
		$cache = $this->createRevisionOutputCache();
		$options = ParserOptions::newFromAnon();

		$this->assertFalse( $cache->get( $this->revision, $options ) );
	}

	/**
	 * Test that fetching with the same options return the saved value.
	 * @covers \MediaWiki\Parser\RevisionOutputCache::get
	 * @covers \MediaWiki\Parser\RevisionOutputCache::save
	 */
	public function testSaveGetSameOptions() {
		$cache = $this->createRevisionOutputCache();
		$parserOutput = new ParserOutput( 'TEST_TEXT' );

		$options1 = ParserOptions::newFromAnon();
		$options1->setOption( $this->getDummyUsedOptions()[0], 'value1' );
		$cache->save( $parserOutput, $this->revision, $options1, $this->cacheTime );

		$savedOutput = $cache->get( $this->revision, $options1 );
		$this->assertInstanceOf( ParserOutput::class, $savedOutput );
		// RevisionOutputCache adds a comment to the HTML, so check if the result starts with page content.
		$this->assertStringStartsWith( 'TEST_TEXT',
			$savedOutput->getRawText() );
		$this->assertSame( $this->cacheTime, $savedOutput->getCacheTime() );
		$this->assertSame( $this->revision->getId(), $savedOutput->getCacheRevisionId() );
	}

	/**
	 * Test that non-cacheable output is not stored
	 * @covers \MediaWiki\Parser\RevisionOutputCache::save
	 */
	public function testDoesNotStoreNonCacheable() {
		$cache = $this->createRevisionOutputCache();
		$parserOutput = new ParserOutput( 'TEST_TEXT' );
		$parserOutput->updateCacheExpiry( 0 );

		$options1 = ParserOptions::newFromAnon();
		$cache->save( $parserOutput, $this->revision, $options1, $this->cacheTime );

		$this->assertFalse( $cache->get( $this->revision, $options1 ) );
	}

	/**
	 * Test that setting the cache epoch will cause outdated entries to be ignored
	 * @covers \MediaWiki\Parser\RevisionOutputCache::get
	 */
	public function testExpiresByEpoch() {
		$store = new HashBagOStuff();
		$cache = $this->createRevisionOutputCache( $store );
		$parserOutput = new ParserOutput( 'TEST_TEXT' );

		$options = ParserOptions::newFromAnon();
		$cache->save( $parserOutput, $this->revision, $options, $this->cacheTime );

		// determine cache epoch younger than cache time
		$cacheTime = MWTimestamp::convert( TS_UNIX, $parserOutput->getCacheTime() );
		$epoch = MWTimestamp::convert( TS_MW, $cacheTime + 60 );

		// create a cache with the new epoch
		$cache = $this->createRevisionOutputCache( $store, null, 60 * 60, $epoch );
		$this->assertFalse( $cache->get( $this->revision, $options ) );
	}

	/**
	 * Test that setting the cache expiry period will cause outdated entries to be ignored
	 * @covers \MediaWiki\Parser\RevisionOutputCache::get
	 */
	public function testExpiresByDuration() {
		$store = new HashBagOStuff();

		// original cache is good for an hour
		$cache = $this->createRevisionOutputCache( $store );
		$parserOutput = new ParserOutput( 'TEST_TEXT' );

		$options = ParserOptions::newFromAnon();
		$cache->save( $parserOutput, $this->revision, $options, $this->cacheTime );

		// move the clock forward by 60 seconds
		$cacheTime = MWTimestamp::convert( TS_UNIX, $parserOutput->getCacheTime() );
		MWTimestamp::setFakeTime( $cacheTime + 60 );

		// create a cache that expires after 30 seconds
		$cache = $this->createRevisionOutputCache( $store, null, 30 );
		$this->assertFalse( $cache->get( $this->revision, $options ) );
	}

	/**
	 * Test that ParserOptions::isSafeToCache is respected on save
	 * @covers \MediaWiki\Parser\RevisionOutputCache::save
	 */
	public function testDoesNotStoreNotSafeToCache() {
		$cache = $this->createRevisionOutputCache();
		$parserOutput = new ParserOutput( 'TEST_TEXT' );

		$options = ParserOptions::newFromAnon();
		$options->setOption( 'wrapclass', 'wrapwrap' );

		$cache->save( $parserOutput, $this->revision, $options, $this->cacheTime );

		$this->assertFalse( $cache->get( $this->revision, $options ) );
	}

	/**
	 * Test that ParserOptions::isSafeToCache is respected on get
	 * @covers \MediaWiki\Parser\RevisionOutputCache::get
	 */
	public function testDoesNotGetNotSafeToCache() {
		$cache = $this->createRevisionOutputCache();
		$parserOutput = new ParserOutput( 'TEST_TEXT' );

		$cache->save( $parserOutput, $this->revision, ParserOptions::newFromAnon(), $this->cacheTime );

		$otherOptions = ParserOptions::newFromAnon();
		$otherOptions->setOption( 'wrapclass', 'wrapwrap' );

		$this->assertFalse( $cache->get( $this->revision, $otherOptions ) );
	}

	/**
	 * Test that fetching with different used option don't return a value.
	 * @covers \MediaWiki\Parser\RevisionOutputCache::get
	 * @covers \MediaWiki\Parser\RevisionOutputCache::save
	 */
	public function testSaveGetDifferentUsedOption() {
		$cache = $this->createRevisionOutputCache();
		$parserOutput = new ParserOutput( 'TEST_TEXT' );
		$optionName = $this->getDummyUsedOptions()[0];
		$parserOutput->recordOption( $optionName );

		$options1 = ParserOptions::newFromAnon();
		$options1->setOption( $optionName, 'value1' );
		$cache->save( $parserOutput, $this->revision, $options1, $this->cacheTime );

		$options2 = ParserOptions::newFromAnon();
		$options2->setOption( $optionName, 'value2' );
		$this->assertFalse( $cache->get( $this->revision, $options2 ) );
	}

	/**
	 * @covers \MediaWiki\Parser\RevisionOutputCache::save
	 */
	public function testSaveNoText() {
		$this->expectException( InvalidArgumentException::class );
		$this->createRevisionOutputCache()->save(
			new ParserOutput( null ),
			$this->revision,
			ParserOptions::newFromAnon()
		);
	}

	public static function provideCorruptData() {
		yield 'JSON serialization, bad data' => [ 'bla bla' ];
		yield 'JSON serialization, no _class_' => [ '{"test":"test"}' ];
		yield 'JSON serialization, non-existing _class_' => [ '{"_class_":"NonExistentBogusClass"}' ];

		$wrongInstance = new JsonDeserializableSuperClass( 'test' );
		yield 'JSON serialization, wrong class' => [ json_encode( $wrongInstance->jsonSerialize() ) ];
	}

	/**
	 * Test that we handle corrupt data gracefully.
	 * This is important for forward-compatibility with JSON serialization.
	 * We want to be sure that we don't crash horribly if we have to roll
	 * back to a version of the code that doesn't know about JSON.
	 *
	 * @dataProvider provideCorruptData
	 * @covers \MediaWiki\Parser\RevisionOutputCache::get
	 * @covers \MediaWiki\Parser\RevisionOutputCache::restoreFromJson
	 * @param string $data
	 */
	public function testCorruptData( string $data ) {
		$cache = $this->createRevisionOutputCache();
		$parserOutput = new ParserOutput( 'TEST_TEXT' );

		$options1 = ParserOptions::newFromAnon();
		$cache->save( $parserOutput, $this->revision, $options1, $this->cacheTime );

		$outputKey = $cache->makeParserOutputKey(
			$this->revision,
			$options1,
			$parserOutput->getUsedOptions()
		);

		$backend = TestingAccessWrapper::newFromObject( $cache )->cache;
		$backend->set( $outputKey, $data );

		// just make sure we don't crash and burn
		$this->assertFalse( $cache->get( $this->revision, $options1 ) );
	}

	/**
	 * @covers \MediaWiki\Parser\RevisionOutputCache::encodeAsJson
	 */
	public function testNonSerializableJsonIsReported() {
		$testLogger = new TestLogger( true );
		$cache = $this->createRevisionOutputCache( null, $testLogger );

		$parserOutput = $this->createDummyParserOutput();
		$parserOutput->setExtensionData( 'test', new User() );
		$cache->save( $parserOutput, $this->revision, ParserOptions::newFromAnon() );
		$this->assertArraySubmapSame(
			[ [ LogLevel::ERROR, 'Unable to serialize JSON' ] ],
			$testLogger->getBuffer()
		);
	}

	/**
	 * @covers \MediaWiki\Parser\RevisionOutputCache::encodeAsJson
	 */
	public function testCyclicStructuresDoNotBlowUpInJson() {
		$this->markTestSkipped( 'Temporarily disabled: T314338' );
		$testLogger = new TestLogger( true );
		$cache = $this->createRevisionOutputCache( null, $testLogger );

		$parserOutput = $this->createDummyParserOutput();
		$cyclicArray = [ 'a' => 'b' ];
		$cyclicArray['c'] = &$cyclicArray;
		$parserOutput->setExtensionData( 'test', $cyclicArray );
		$cache->save( $parserOutput, $this->revision, ParserOptions::newFromAnon() );
		$this->assertArraySubmapSame(
			[ [ LogLevel::ERROR, 'Unable to serialize JSON' ] ],
			$testLogger->getBuffer()
		);
	}

	/**
	 * Tests that unicode characters are not \u escaped
	 * @covers \MediaWiki\Parser\RevisionOutputCache::encodeAsJson
	 */
	public function testJsonEncodeUnicode() {
		$unicodeCharacter = "Э";
		$cache = $this->createRevisionOutputCache( new HashBagOStuff() );
		$parserOutput = $this->createDummyParserOutput();
		$parserOutput->setRawText( $unicodeCharacter );
		$cache->save( $parserOutput, $this->revision, ParserOptions::newFromAnon() );

		$backend = TestingAccessWrapper::newFromObject( $cache )->cache;
		$json = $backend->get(
			$cache->makeParserOutputKey( $this->revision, ParserOptions::newFromAnon() )
		);
		$this->assertStringNotContainsString( "\u003E", $json );
		$this->assertStringContainsString( $unicodeCharacter, $json );
	}
}
