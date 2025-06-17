<?php

namespace MediaWiki\Tests\Parser;

use CacheTime;
use JsonSerializable;
use MediaWiki\Json\JsonCodec;
use MediaWikiIntegrationTestCase;
use MWTimestamp;
use ParserOutput;
use Title;
use Wikimedia\TestingAccessWrapper;
use Wikimedia\Tests\SerializationTestUtils;

/**
 * A collection of serialization test cases for parser cache.
 *
 * Contains a set of acceptance tests for CacheTime and ParserOutput objects.
 * The acceptance tests will be run on instances created by the current code,
 * as well as instances deserialized from stored serializations for various MW
 * versions.
 *
 * Since backwards compatibility for objects stored in ParserCache is necessary,
 * failure of a deserialization test most likely indicates an error in the code.
 * However, since the serialization format may change, thus if proper compatibility
 * logic was added but a serialization test is still failing, you might want to
 * update stored serialized data using validateParserCacheSerializationTestData.php
 * script. The same script should be run when more acceptance tests are added
 * to generate and save serialized object, which would be used for acceptance
 * deserialization tests.
 *
 * @see SerializationTestTrait
 * @see SerializationTestUtils
 * @see ValidateParserCacheSerializationTestData
 * @package MediaWiki\Tests\Parser
 */
abstract class ParserCacheSerializationTestCases {

	public const FAKE_TIME = 123456789;

	public const FAKE_CACHE_EXPIRY = 42;

	private const MOCK_EXT_DATA = [
		'boolean' => true,
		'null' => null,
		'number' => 42,
		'string' => 'string',
		'array' => [ 1, 2, 3 ],
		'map' => [ 'key' => 'value' ]
	];

	private const MOCK_FALSY_PROPERTIES = [
		'boolean' => false,
		'null' => null,
		'number' => 0,
		'string' => '',
		'numstring' => '0',
		'array' => [],
	];

	private const MOCK_BINARY_PROPERTIES = [
		'empty' => '',
		'\x00' => "\x00",
		'gzip' => "\x1f\x8b\x08\x00\x00\x00\x00\x00\x00\x03\xcb\x48\xcd\xc9\xc9\x57\x28\xcf\x2f'
			. '\xca\x49\x01\x00\x85\x11\x4a\x0d\x0b\x00\x00\x00",
	];

	private const SECTIONS = [
		[
			'toclevel' => 0,
			'level' => '1',
			'line' => 'heading_1',
			'number' => '1.0',
			'index' => 'T-1',
			'fromtitle' => false,
			'byteoffset' => null,
			'anchor' => 'heading_1',
			'linkAnchor' => '#heading_1',
		],
		[
			'toclevel' => 1,
			'level' => '2',
			'line' => 'heading_2',
			'number' => '2.0',
			'index' => 'T-2',
			'fromtitle' => false,
			'byteoffset' => null,
			'anchor' => 'heading_2',
			'linkAnchor' => '#heading_2'
		],
	];

	private const CACHE_TIME = '20010419042521';

	/**
	 * Get acceptance test cases for CacheTime class.
	 * @see SerializationTestTrait::getTestInstancesAndAssertions()
	 * @return array[]
	 */
	public static function getCacheTimeTestCases(): array {
		$cacheTimeWithUsedOptions = new CacheTime();
		$cacheTimeWithUsedOptions->recordOptions( [ 'optA', 'optX' ] );

		$cacheTimeWithTime = new CacheTime();
		$cacheTimeWithTime->setCacheTime( self::CACHE_TIME );

		$cacheExpiry = 10;
		$cacheTimeWithExpiry = new CacheTime();
		$cacheTimeWithExpiry->updateCacheExpiry( $cacheExpiry );

		$cacheRevisionId = 1234;
		$cacheTimeWithRevId = new CacheTime();
		$cacheTimeWithRevId->setCacheRevisionId( $cacheRevisionId );

		return [
			'empty' => [
				'instance' => new CacheTime(),
				'assertions' => static function ( MediaWikiIntegrationTestCase $testCase, CacheTime $object ) {
					$testCase->assertSame( self::FAKE_CACHE_EXPIRY, $object->getCacheExpiry() );
					$testCase->assertNull( $object->getCacheRevisionId() );
					$testCase->assertSame(
						MWTimestamp::convert( TS_MW, self::FAKE_TIME ),
						$object->getCacheTime()
					);
					// When the cacheRevisionId is not set, this method always returns true.
					$testCase->assertFalse( $object->isDifferentRevision( 29 ) );
					$testCase->assertFalse( $object->isDifferentRevision( 31 ) );
					$testCase->assertTrue( $object->isCacheable() );
					$testCase->assertSame(
						$object->getCacheTime(),
						MWTimestamp::convert( TS_MW, self::FAKE_TIME )
					);
				}
			],
			'usedOptions' => [
				'instance' => $cacheTimeWithUsedOptions,
				'assertions' => static function ( MediaWikiIntegrationTestCase $testCase, CacheTime $object ) {
					$testCase->assertArrayEquals( [ 'optA', 'optX' ], $object->getUsedOptions() );
				}
			],
			'cacheTime' => [
				'instance' => $cacheTimeWithTime,
				'assertions' => static function (
					MediaWikiIntegrationTestCase $testCase, CacheTime $object
				) {
					$testCase->assertSame( self::CACHE_TIME, $object->getCacheTime() );
				}
			],
			'cacheExpiry' => [
				'instance' => $cacheTimeWithExpiry,
				'assertions' => static function (
					MediaWikiIntegrationTestCase $testCase, CacheTime $object
				) use ( $cacheExpiry ) {
					$testCase->assertSame( $cacheExpiry, $object->getCacheExpiry() );
				}
			],
			'cacheRevisionId' => [
				'instance' => $cacheTimeWithRevId,
				'assertions' => static function (
					MediaWikiIntegrationTestCase $testCase, CacheTime $object
				) use ( $cacheRevisionId ) {
					$testCase->assertSame( $cacheRevisionId, $object->getCacheRevisionId() );
				}
			]
		];
	}

	/**
	 * This matches PagePropsTable::encodeValue() and represents how
	 * page properties are actually stored in the database.
	 * @param mixed $v
	 * @return int|float|string
	 */
	public static function pagePropEncode( $v ) {
		if ( is_int( $v ) || is_float( $v ) || is_string( $v ) ) {
			return $v;
		}
		if ( is_bool( $v ) ) {
			return (int)$v;
		}
		if ( $v === null ) {
			return '';
		}
		if ( is_array( $v ) ) {
			// strval on an array yield 'Array' plus a warning.
			return 'Array';
		}
		return strval( $v );
	}

	/**
	 * Assert equality in terms of semantically equivalent page property
	 * values.
	 * @param MediaWikiIntegrationTestCase $testCase
	 * @param mixed $expected
	 * @param mixed $actual
	 */
	public static function assertPagePropSame( MediaWikiIntegrationTestCase $testCase, $expected, $actual ): void {
		$testCase->assertSame(
			self::pagePropEncode( $expected ),
			$actual
		);
	}

	/**
	 * Get acceptance test cases for ParserOutput class.
	 * @see SerializationTestTrait::getTestInstancesAndAssertions()
	 * @return array[]
	 */
	public static function getParserOutputTestCases() {
		$parserOutputWithCacheTimeProps = new ParserOutput( 'CacheTime' );
		$parserOutputWithCacheTimeProps->setCacheTime( self::CACHE_TIME );
		$parserOutputWithCacheTimeProps->updateCacheExpiry( 10 );
		$parserOutputWithCacheTimeProps->setCacheRevisionId( 42 );

		$parserOutputWithUsedOptions = new ParserOutput( 'Dummy' );
		$parserOutputWithUsedOptions->recordOption( 'optA' );
		$parserOutputWithUsedOptions->recordOption( 'optX' );

		$parserOutputWithExtensionData = new ParserOutput( '' );
		foreach ( self::MOCK_EXT_DATA as $key => $value ) {
			$parserOutputWithExtensionData->setExtensionData( $key, $value );
		}

		$parserOutputWithProperties = new ParserOutput( '' );
		foreach ( self::MOCK_EXT_DATA as $key => $value ) {
			$parserOutputWithProperties->setPageProperty( $key, $value );
		}

		$parserOutputWithProperties1_45 = new ParserOutput( '' );
		foreach ( self::MOCK_EXT_DATA as $key => $value ) {
			$value = self::pagePropEncode( $value );
			$parserOutputWithProperties1_45->setPageProperty( $key, $value );
		}

		$parserOutputWithFalsyProperties = new ParserOutput( '' );
		foreach ( self::MOCK_FALSY_PROPERTIES as $key => $value ) {
			$parserOutputWithFalsyProperties->setPageProperty( $key, $value );
		}

		$parserOutputWithFalsyProperties1_45 = new ParserOutput( '' );
		foreach ( self::MOCK_FALSY_PROPERTIES as $key => $value ) {
			$value = self::pagePropEncode( $value );
			$parserOutputWithFalsyProperties1_45->setPageProperty( $key, $value );
		}

		$parserOutputWithBinaryProperties = new ParserOutput( '' );
		foreach ( self::MOCK_BINARY_PROPERTIES as $key => $value ) {
			$parserOutputWithBinaryProperties->setPageProperty( $key, $value );
		}

		$parserOutputWithMetadata = new ParserOutput( '' );
		$parserOutputWithMetadata->setSpeculativeRevIdUsed( 42 );
		$parserOutputWithMetadata->addLanguageLink( 'link1' );
		$parserOutputWithMetadata->addLanguageLink( 'link2' );
		$parserOutputWithMetadata->addInterwikiLink( Title::makeTitle( NS_MAIN, 'interwiki1', '', 'enwiki' ) );
		$parserOutputWithMetadata->addInterwikiLink( Title::makeTitle( NS_MAIN, 'interwiki2', '', 'enwiki' ) );
		$parserOutputWithMetadata->addCategory( 'category2', '1' );
		$parserOutputWithMetadata->addCategory( 'category1', '2' );
		$parserOutputWithMetadata->setIndicator( 'indicator1', 'indicator1_value' );
		$parserOutputWithMetadata->setTitleText( 'title_text1' );
		$parserOutputWithMetadata->setSections( [ 'section1', 'section2' ] );
		$parserOutputWithMetadata->addLink( Title::makeTitle( NS_MAIN, 'Link1' ), 42 );
		$parserOutputWithMetadata->addLink( Title::makeTitle( NS_USER, 'Link2' ), 43 );
		$parserOutputWithMetadata->addTemplate(
			Title::makeTitle( NS_TEMPLATE, 'Template1' ),
			42,
			4242
		);
		$parserOutputWithMetadata->addImage(
			'Image1',
			MWTimestamp::convert( TS_MW, 123456789 ),
			'test_sha1'
		);
		$parserOutputWithMetadata->addExternalLink( 'https://test.org' );
		$parserOutputWithMetadata->addHeadItem( 'head_item1', 'tag1' );
		$parserOutputWithMetadata->addModules( [ 'module1' ] );
		$parserOutputWithMetadata->addModuleStyles( [ 'module_style1' ] );
		$parserOutputWithMetadata->setJsConfigVar( 'key1', 'value1' );
		$parserOutputWithMetadata->addOutputHook( 'hook1', self::MOCK_EXT_DATA );
		$parserOutputWithMetadata->addWarning( 'warning1' );
		$parserOutputWithMetadata->setIndexPolicy( 'noindex' );
		$parserOutputWithMetadata->setTOCHTML( 'tochtml1' );
		$parserOutputWithMetadata->setTimestamp( MWTimestamp::convert( TS_MW, 987654321 ) );
		$parserOutputWithMetadata->setLimitReportData( 'limit_report_key1', 'value1' );
		$parserOutputWithMetadata->setEnableOOUI( true );
		$parserOutputWithMetadata->setHideNewSection( true );
		$parserOutputWithMetadata->setNewSection( true );
		$parserOutputWithMetadata->setOutputFlag( 'test' );

		// For compatibility with older serialized objects, clear out the
		// $mWarningMsgs array, which is not currently stored.
		// See T343050 for the steps required to remove this workaround in
		// the future.
		TestingAccessWrapper::newFromObject(
			$parserOutputWithMetadata
		)->mWarningMsgs = [];

		$parserOutputWithMetadataPost1_44 = clone $parserOutputWithMetadata;
		$parserOutputWithMetadataPost1_44->setLanguageLinks( [ 'm:link1', 'mw:link2' ] );
		$parserOutputWithMetadataPost1_44->setSections( self::SECTIONS );
		$parserOutputWithMetadataPost1_44->setTOCHTML( '' );
		TestingAccessWrapper::newFromObject(
			$parserOutputWithMetadataPost1_44
		)->mOutputHooks = [];

		$parserOutputWithSections = new ParserOutput( '' );
		$parserOutputWithSections->setSections( self::SECTIONS );

		$parserOutputWithMetadataPost1_31 = new ParserOutput( '' );
		$parserOutputWithMetadataPost1_31->addWrapperDivClass( 'test_wrapper' );
		$parserOutputWithMetadataPost1_31->setSpeculativePageIdUsed( 4242 );
		$parserOutputWithMetadataPost1_31->setRevisionTimestampUsed(
			MWTimestamp::convert( TS_MW, 123456789 )
		);
		$parserOutputWithMetadataPost1_31->setRevisionUsedSha1Base36( 'test_hash' );
		$parserOutputWithMetadataPost1_31->setNoGallery( true );

		$parserOutputWithMetadataPost1_34 = new ParserOutput( '' );
		$parserOutputWithMetadataPost1_34->addExtraCSPStyleSrc( 'style1' );
		$parserOutputWithMetadataPost1_34->addExtraCSPDefaultSrc( 'default1' );
		$parserOutputWithMetadataPost1_34->addExtraCSPScriptSrc( 'script1' );
		$parserOutputWithMetadataPost1_34->addLink( Title::makeTitle( NS_SPECIAL, 'Link3' ) );

		$parserOutputWithEmptyToC = new ParserOutput( '' );
		$parserOutputWithEmptyToC->setSections( [] );

		return [
			'empty' => [
				'instance' => new ParserOutput( '' ),
				'assertions' => function ( MediaWikiIntegrationTestCase $testCase, ParserOutput $object ) {
					// Empty CacheTime assertions
					self::getCacheTimeTestCases()['empty']['assertions']( $testCase, $object );
					// Empty string text is counted as having text.
					$testCase->assertTrue( $object->hasText() );

					$testCase->assertSame( '', $object->getText() );
					$testCase->assertSame( '', $object->getWrapperDivClass() );
					$testCase->assertNull( $object->getSpeculativeRevIdUsed() );
					$testCase->assertNull( $object->getSpeculativePageIdUsed() );
					$testCase->assertNull( $object->getRevisionTimestampUsed() );
					$testCase->assertNull( $object->getRevisionUsedSha1Base36() );
					$testCase->assertArrayEquals( [], $object->getLanguageLinks() );
					$testCase->assertArrayEquals( [], $object->getInterwikiLinks() );
					$testCase->assertArrayEquals( [], $object->getCategoryNames() );
					$testCase->assertArrayEquals( [], $object->getCategories() );
					$testCase->assertArrayEquals( [], $object->getIndicators() );
					$testCase->assertSame( '', $object->getTitleText() );
					$testCase->assertArrayEquals( [], $object->getSections() );
					$testCase->assertArrayEquals( [], $object->getLinks() );
					$testCase->assertArrayEquals( [], $object->getLinksSpecial() );
					$testCase->assertArrayEquals( [], $object->getTemplates() );
					$testCase->assertArrayEquals( [], $object->getTemplateIds() );
					$testCase->assertArrayEquals( [], $object->getImages() );
					$testCase->assertArrayEquals( [], $object->getFileSearchOptions() );
					$testCase->assertArrayEquals( [], $object->getExternalLinks() );
					$testCase->assertFalse( $object->getNoGallery() );
					$testCase->assertArrayEquals( [], $object->getHeadItems() );
					$testCase->assertArrayEquals( [], $object->getModules() );
					$testCase->assertArrayEquals( [], $object->getModuleStyles() );
					$testCase->assertArrayEquals( [], $object->getJsConfigVars() );
					$testCase->assertArrayEquals( [], $object->getOutputHooks() );
					$testCase->assertArrayEquals( [], $object->getWarnings() );
					$testCase->assertSame( '', $object->getIndexPolicy() );
					$testCase->assertSame( '', $object->getTOCHTML() );
					$testCase->assertNull( $object->getTimestamp() );
					$testCase->assertArrayEquals( [], $object->getLimitReportData() );
					$testCase->assertArrayEquals( [], $object->getLimitReportJSData() );
					$testCase->assertFalse( $object->getEnableOOUI() );
					$testCase->assertArrayEquals( [], $object->getExtraCSPDefaultSrcs() );
					$testCase->assertArrayEquals( [], $object->getExtraCSPScriptSrcs() );
					$testCase->assertArrayEquals( [], $object->getExtraCSPStyleSrcs() );
					$testCase->assertFalse( $object->getHideNewSection() );
					$testCase->assertFalse( $object->getNewSection() );
					$testCase->assertFalse( $object->getDisplayTitle() );
					$testCase->assertFalse( $object->getFlag( 'test' ) );
					$testCase->assertArrayEquals( [], $object->getAllFlags() );
					$testCase->assertNull( $object->getPageProperty( 'test_prop' ) );
					$testCase->assertArrayEquals( [], $object->getPageProperties() );
					$testCase->assertArrayEquals( [], $object->getUsedOptions() );
					$testCase->assertNull( $object->getExtensionData( 'test_ext_data' ) );
					$testCase->assertNull( $object->getTimeSinceStart( 'wall' ) );
				}
			],
			'cacheTime' => [
				'instance' => $parserOutputWithCacheTimeProps,
				'assertions' => static function ( MediaWikiIntegrationTestCase $testCase, ParserOutput $object ) {
					$testCase->assertSame( self::CACHE_TIME, $object->getCacheTime() );
					$testCase->assertSame( 10, $object->getCacheExpiry() );
					$testCase->assertSame( 42, $object->getCacheRevisionId() );
				}
			],
			'text' => [
				'instance' => new ParserOutput( 'Lorem Ipsum' ),
				'assertions' => static function ( MediaWikiIntegrationTestCase $testCase, ParserOutput $object ) {
					$testCase->assertTrue( $object->hasText() );
					$testCase->assertSame( 'Lorem Ipsum', $object->getRawText() );
					$testCase->assertSame( 'Lorem Ipsum', $object->getText() );
				}
			],
			'usedOptions' => [
				'instance' => $parserOutputWithUsedOptions,
				'assertions' => static function ( MediaWikiIntegrationTestCase $testCase, ParserOutput $object ) {
					$testCase->assertArrayEquals( [ 'optA', 'optX' ], $object->getUsedOptions() );
				}
			],
			'extensionData' => [
				'instance' => $parserOutputWithExtensionData,
				'assertions' => static function ( MediaWikiIntegrationTestCase $testCase, ParserOutput $object ) {
					$testCase->assertSame( self::MOCK_EXT_DATA['boolean'], $object->getExtensionData( 'boolean' ) );
					$testCase->assertSame( self::MOCK_EXT_DATA['null'], $object->getExtensionData( 'null' ) );
					$testCase->assertSame( self::MOCK_EXT_DATA['number'], $object->getExtensionData( 'number' ) );
					$testCase->assertSame( self::MOCK_EXT_DATA['string'], $object->getExtensionData( 'string' ) );
					$testCase->assertArrayEquals( self::MOCK_EXT_DATA['array'], $object->getExtensionData( 'array' ) );
					$testCase->assertSame( self::MOCK_EXT_DATA['map'], $object->getExtensionData( 'map' ) );
				}
			],
			'pageProperties' => [
				'instance' => $parserOutputWithProperties,
				'assertions' => static function ( MediaWikiIntegrationTestCase $testCase, ParserOutput $object ) {
					$testCase->assertSame( self::MOCK_EXT_DATA['boolean'], $object->getPageProperty( 'boolean' ) );
					$testCase->assertSame( self::MOCK_EXT_DATA['null'], $object->getPageProperty( 'null' ) );
					$testCase->assertSame( self::MOCK_EXT_DATA['number'], $object->getPageProperty( 'number' ) );
					$testCase->assertSame( self::MOCK_EXT_DATA['string'], $object->getPageProperty( 'string' ) );
					$testCase->assertArrayEquals( self::MOCK_EXT_DATA['array'], $object->getPageProperty( 'array' ) );
					$testCase->assertSame( self::MOCK_EXT_DATA['map'], $object->getPageProperty( 'map' ) );
					$testCase->assertArrayEquals( self::MOCK_EXT_DATA, $object->getPageProperties() );
				}
			],
			'pageProperties1_45' => [
				'instance' => $parserOutputWithProperties1_45,
				'assertions' => static function ( MediaWikiIntegrationTestCase $testCase, ParserOutput $object ) {
					self::assertPagePropSame( $testCase, self::MOCK_EXT_DATA['boolean'], $object->getPageProperty( 'boolean' ) );
					self::assertPagePropSame( $testCase, self::MOCK_EXT_DATA['null'], $object->getPageProperty( 'null' ) );
					self::assertPagePropSame( $testCase, self::MOCK_EXT_DATA['number'], $object->getPageProperty( 'number' ) );
					self::assertPagePropSame( $testCase, self::MOCK_EXT_DATA['string'], $object->getPageProperty( 'string' ) );
					self::assertPagePropSame( $testCase, self::MOCK_EXT_DATA['array'], $object->getPageProperty( 'array' ) );
					self::assertPagePropSame( $testCase, self::MOCK_EXT_DATA['map'], $object->getPageProperty( 'map' ) );
					$testCase->assertArrayEquals(
						array_map( fn ( $v )=>self::pagePropEncode( $v ), self::MOCK_EXT_DATA ),
						array_map( fn ( $v )=>self::pagePropEncode( $v ), $object->getPageProperties() )
					);
				}
			],
			'binaryPageProperties' => [
				'instance' => $parserOutputWithBinaryProperties,
				'assertions' => static function ( MediaWikiIntegrationTestCase $testCase, ParserOutput $object ) {
					$testCase->assertSame( self::MOCK_BINARY_PROPERTIES['empty'], $object->getPageProperty( 'empty' ) );
					$testCase->assertSame( self::MOCK_BINARY_PROPERTIES['\x00'], $object->getPageProperty( '\x00' ) );
					$testCase->assertSame( self::MOCK_BINARY_PROPERTIES['gzip'], $object->getPageProperty( 'gzip' ) );
					$testCase->assertArrayEquals( self::MOCK_BINARY_PROPERTIES, $object->getPageProperties() );
				}
			],
			'withMetadata' => [
				'instance' => $parserOutputWithMetadata,
				'assertions' => static function ( MediaWikiIntegrationTestCase $testCase, ParserOutput $object ) {
					$testCase->assertSame( 42, $object->getSpeculativeRevIdUsed() );
					$testCase->assertArrayEquals( [ 'link1', 'link2' ], $object->getLanguageLinks() );
					$testCase->assertArrayEquals( [ 'enwiki' => [
						'interwiki1' => 1,
						'interwiki2' => 1
					] ], $object->getInterwikiLinks() );
					$testCase->assertArrayEquals( [ 'category1', 'category2' ], $object->getCategoryNames() );
					$testCase->assertArrayEquals( [
						'category1' => '2',
						'category2' => '1'
					], $object->getCategories() );
					$testCase->assertArrayEquals( [ 'indicator1' => 'indicator1_value' ], $object->getIndicators() );
					$testCase->assertSame( 'title_text1', $object->getTitleText() );
					$testCase->assertArrayEquals( [ 'section1', 'section2' ], $object->getSections() );
					$testCase->assertArrayEquals( [
						NS_MAIN => [ 'Link1' => 42 ],
						NS_USER => [ 'Link2' => 43 ]
					], $object->getLinks() );
					$testCase->assertArrayEquals( [
						NS_SPECIAL => [ 'Template1' => 42 ]
					], $object->getTemplates() );
					$testCase->assertArrayEquals( [
						NS_SPECIAL => [ 'Template1' => 4242 ]
					], $object->getTemplateIds() );
					$testCase->assertArrayEquals( [ 'Image1' => 1 ], $object->getImages() );
					$testCase->assertArrayEquals( [ 'Image1' => [
						'time' => MWTimestamp::convert( TS_MW, 123456789 ), 'sha1' => 'test_sha1'
					] ], $object->getFileSearchOptions() );
					$testCase->assertArrayEquals( [ 'https://test.com' => 1 ], $object->getExternalLinks() );
					$testCase->assertArrayEquals( [ 'tag1' => 'head_item1' ], $object->getHeadItems() );
					$testCase->assertArrayEquals( [ 'module1' ], $object->getModules() );
					$testCase->assertArrayEquals( [ 'module_style1' ], $object->getModuleStyles() );
					$testCase->assertArrayEquals( [ 'key1' => 'value1' ], $object->getJsConfigVars() );
					$testCase->assertArrayEquals( [ [ 'hook1', self::MOCK_EXT_DATA ] ], $object->getOutputHooks() );
					$testCase->assertArrayEquals( [ 'warning1' ], $object->getWarnings() );
					$testCase->assertSame( 'noindex', $object->getIndexPolicy() );
					$testCase->assertSame( 'tochtml1', $object->getTOCHTML() );
					$testCase->assertSame( MWTimestamp::convert( TS_MW, 987654321 ), $object->getTimestamp() );
					$testCase->assertArrayEquals(
						[ 'limit_report_key1' => 'value1' ],
						$object->getLimitReportData()
					);
					$testCase->assertArrayEquals(
						[ 'limit_report_key1' => 'value1' ],
						$object->getLimitReportJSData()
					);
					$testCase->assertTrue( $object->getEnableOOUI() );
					$testCase->assertTrue( $object->getHideNewSection() );
					$testCase->assertTrue( $object->getNewSection() );
					$testCase->assertTrue( $object->getFlag( 'test' ) );
					$testCase->assertArrayEquals( [ 'test' ], $object->getAllFlags() );
				}
			],
			'withMetadataPost1_44' => [
				'instance' => $parserOutputWithMetadataPost1_44,
				'assertions' => static function ( MediaWikiIntegrationTestCase $testCase, ParserOutput $object ) {
					$testCase->assertSame( 42, $object->getSpeculativeRevIdUsed() );
					$testCase->assertArrayEquals( [ 'm:link1', 'mw:link2' ], $object->getLanguageLinks() );
					$testCase->assertArrayEquals( [ 'enwiki' => [
						'interwiki1' => 1,
						'interwiki2' => 1
					] ], $object->getInterwikiLinks() );
					$testCase->assertArrayEquals( [ 'category1', 'category2' ], $object->getCategoryNames() );
					$testCase->assertArrayEquals( [
						'category1' => '2',
						'category2' => '1'
					], $object->getCategories() );
					$testCase->assertArrayEquals( [ 'indicator1' => 'indicator1_value' ], $object->getIndicators() );
					$testCase->assertSame( 'title_text1', $object->getTitleText() );
					$testCase->assertArrayEquals( self::SECTIONS, $object->getSections() );
					$testCase->assertArrayEquals( [
						NS_MAIN => [ 'Link1' => 42 ],
						NS_USER => [ 'Link2' => 43 ]
					], $object->getLinks() );
					$testCase->assertArrayEquals( [
						NS_SPECIAL => [ 'Template1' => 42 ]
					], $object->getTemplates() );
					$testCase->assertArrayEquals( [
						NS_SPECIAL => [ 'Template1' => 4242 ]
					], $object->getTemplateIds() );
					$testCase->assertArrayEquals( [ 'Image1' => 1 ], $object->getImages() );
					$testCase->assertArrayEquals( [ 'Image1' => [
						'time' => MWTimestamp::convert( TS_MW, 123456789 ), 'sha1' => 'test_sha1'
					] ], $object->getFileSearchOptions() );
					$testCase->assertArrayEquals( [ 'https://test.com' => 1 ], $object->getExternalLinks() );
					$testCase->assertArrayEquals( [ 'tag1' => 'head_item1' ], $object->getHeadItems() );
					$testCase->assertArrayEquals( [ 'module1' ], $object->getModules() );
					$testCase->assertArrayEquals( [ 'module_style1' ], $object->getModuleStyles() );
					$testCase->assertArrayEquals( [ 'key1' => 'value1' ], $object->getJsConfigVars() );
					$testCase->assertArrayEquals( [ 'warning1' ], $object->getWarnings() );
					$testCase->assertSame( 'noindex', $object->getIndexPolicy() );
					$testCase->assertSame( MWTimestamp::convert( TS_MW, 987654321 ), $object->getTimestamp() );
					$testCase->assertArrayEquals(
						[ 'limit_report_key1' => 'value1' ],
						$object->getLimitReportData()
					);
					$testCase->assertArrayEquals(
						[ 'limit_report_key1' => 'value1' ],
						$object->getLimitReportJSData()
					);
					$testCase->assertTrue( $object->getEnableOOUI() );
					$testCase->assertTrue( $object->getHideNewSection() );
					$testCase->assertTrue( $object->getNewSection() );
					$testCase->assertTrue( $object->getOutputFlag( 'test' ) );
					$testCase->assertArrayEquals( [ 'test' ], $object->getAllFlags() );
				}
			],
			'withSections' => [
				'instance' => $parserOutputWithSections,
				'assertions' => static function ( MediaWikiIntegrationTestCase $testCase, ParserOutput $object ) {
					$testCase->assertArrayEquals( self::SECTIONS, $object->getSections() );
				}
			],
			'withMetadataPost1_31' => [
				'instance' => $parserOutputWithMetadataPost1_31,
				'assertions' => static function ( MediaWikiIntegrationTestCase $testCase, ParserOutput $object ) {
					$testCase->assertSame( 'test_wrapper', $object->getWrapperDivClass() );
					$testCase->assertSame( 4242, $object->getSpeculativePageIdUsed() );
					$testCase->assertSame(
						MWTimestamp::convert( TS_MW, 123456789 ),
						$object->getRevisionTimestampUsed()
					);
					$testCase->assertSame( 'test_hash', $object->getRevisionUsedSha1Base36() );
					$testCase->assertTrue( $object->getNoGallery() );
				}
			],
			'withMetadataPost1_34' => [
				'instance' => $parserOutputWithMetadataPost1_34,
				'assertions' => static function ( MediaWikiIntegrationTestCase $testCase, ParserOutput $object ) {
					$testCase->assertArrayEquals( [ 'default1' ], $object->getExtraCSPDefaultSrcs() );
					$testCase->assertArrayEquals( [ 'script1' ], $object->getExtraCSPScriptSrcs() );
					$testCase->assertArrayEquals( [ 'style1' ], $object->getExtraCSPStyleSrcs() );
					$testCase->assertArrayEquals( [ 'Link3' => 1 ], $object->getLinksSpecial() );
				}
			],
			'withFalsyProperties' => [
				'instance' => $parserOutputWithFalsyProperties,
				'assertions' => static function ( MediaWikiIntegrationTestCase $testCase, ParserOutput $object ) {
					$testCase->assertSame(
						self::MOCK_FALSY_PROPERTIES['boolean'],
						$object->getPageProperty( 'boolean' )
					);
					$testCase->assertSame(
						self::MOCK_FALSY_PROPERTIES['null'],
						$object->getPageProperty( 'null' )
					);
					$testCase->assertSame(
						self::MOCK_FALSY_PROPERTIES['number'],
						$object->getPageProperty( 'number' )
					);
					$testCase->assertSame(
						self::MOCK_FALSY_PROPERTIES['string'],
						$object->getPageProperty( 'string' )
					);
					$testCase->assertSame(
						self::MOCK_FALSY_PROPERTIES['numstring'],
						$object->getPageProperty( 'numstring' )
					);
					$testCase->assertArrayEquals(
						self::MOCK_FALSY_PROPERTIES['array'],
						$object->getPageProperty( 'array' )
					);
				}
			],
			'withFalsyProperties1_45' => [
				'instance' => $parserOutputWithFalsyProperties1_45,
				'assertions' => static function ( MediaWikiIntegrationTestCase $testCase, ParserOutput $object ) {
					self::assertPagePropSame( $testCase,
						self::MOCK_FALSY_PROPERTIES['boolean'],
						$object->getPageProperty( 'boolean' )
					);
					self::assertPagePropSame( $testCase,
						self::MOCK_FALSY_PROPERTIES['null'],
						$object->getPageProperty( 'null' )
					);
					self::assertPagePropSame( $testCase,
						self::MOCK_FALSY_PROPERTIES['number'],
						$object->getPageProperty( 'number' )
					);
					self::assertPagePropSame( $testCase,
						self::MOCK_FALSY_PROPERTIES['string'],
						$object->getPageProperty( 'string' )
					);
					self::assertPagePropSame( $testCase,
						self::MOCK_FALSY_PROPERTIES['numstring'],
						$object->getPageProperty( 'numstring' )
					);
					self::assertPagePropSame( $testCase,
						self::MOCK_FALSY_PROPERTIES['array'],
						$object->getPageProperty( 'array' )
					);
				}
			],
			'withEmptyToC' => [
				'instance' => $parserOutputWithEmptyToC,
				'assertions' => static function ( MediaWikiIntegrationTestCase $testCase, ParserOutput $object ) {
					$testCase->assertArrayEquals( [], $object->getSections() );
				},
			],
		];
	}

	/**
	 * @param string $class the class name
	 * @return string[][] a list of supported serialization formats info
	 * in the following format:
	 *  'ext' => string file extension for stored serializations
	 *  'serializer' => callable to serialize objects
	 *  'deserializer' => callable to deserialize objects
	 */
	public static function getSupportedSerializationFormats( string $class ): array {
		$serializationFormats = [ [
			'ext' => 'serialized',
			'serializer' => 'serialize',
			'deserializer' => 'unserialize'
		] ];
		if ( is_subclass_of( $class, JsonSerializable::class ) ) {
			$jsonCodec = new JsonCodec();
			$serializationFormats[] = [
				'ext' => 'json',
				'serializer' => static function ( JsonSerializable $obj ) use ( $jsonCodec ) {
					return $jsonCodec->serialize( $obj );
				},
				'deserializer' => static function ( $data ) use ( $jsonCodec ) {
					return $jsonCodec->unserialize( $data );
				}
			];
		}
		return $serializationFormats;
	}
}
