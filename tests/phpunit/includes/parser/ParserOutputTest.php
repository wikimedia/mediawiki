<?php

namespace MediaWiki\Tests\Parser;

use LogicException;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\ParserOutputFlags;
use MediaWiki\Parser\ParserOutputLinkTypes;
use MediaWiki\Parser\ParserOutputStringSets;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleValue;
use MediaWiki\Utils\MWTimestamp;
use MediaWikiLangTestCase;
use Wikimedia\Bcp47Code\Bcp47CodeValue;
use Wikimedia\Parsoid\Core\SectionMetadata;
use Wikimedia\TestingAccessWrapper;
use Wikimedia\Tests\SerializationTestTrait;

/**
 * @covers \MediaWiki\Parser\ParserOutput
 * @covers \MediaWiki\Parser\CacheTime
 * @group Database
 *        ^--- trigger DB shadowing because we are using Title magic
 */
class ParserOutputTest extends MediaWikiLangTestCase {
	use SerializationTestTrait;

	protected function setUp(): void {
		parent::setUp();

		MWTimestamp::setFakeTime( ParserCacheSerializationTestCases::FAKE_TIME );
		$this->overrideConfigValue(
			MainConfigNames::ParserCacheExpireTime,
			ParserCacheSerializationTestCases::FAKE_CACHE_EXPIRY
		);
		$this->overrideConfigValue(
			MainConfigNames::ParserCacheAsyncExpireTime,
			ParserCacheSerializationTestCases::FAKE_ASYNC_CACHE_EXPIRY
		);
	}

	public static function getClassToTest(): string {
		return ParserOutput::class;
	}

	public static function getSerializedDataPath(): string {
		return __DIR__ . '/../../data/ParserCache';
	}

	public static function getTestInstancesAndAssertions(): array {
		return ParserCacheSerializationTestCases::getParserOutputTestCases();
	}

	public static function getSupportedSerializationFormats(): array {
		return ParserCacheSerializationTestCases::getSupportedSerializationFormats(
			self::getClassToTest() );
	}

	public static function provideIsLinkInternal() {
		return [
			// Different domains
			[ false, 'http://example.org', 'http://mediawiki.org' ],
			// Same domains
			[ true, 'http://example.org', 'http://example.org' ],
			[ true, 'https://example.org', 'https://example.org' ],
			[ true, '//example.org', '//example.org' ],
			// Same domain different cases
			[ true, 'http://example.org', 'http://EXAMPLE.ORG' ],
			// Paths, queries, and fragments are not relevant
			[ true, 'http://example.org', 'http://example.org/wiki/Main_Page' ],
			[ true, 'http://example.org', 'http://example.org?my=query' ],
			[ true, 'http://example.org', 'http://example.org#its-a-fragment' ],
			// Different protocols
			[ false, 'http://example.org', 'https://example.org' ],
			[ false, 'https://example.org', 'http://example.org' ],
			// Protocol relative servers always match http and https links
			[ true, '//example.org', 'http://example.org' ],
			[ true, '//example.org', 'https://example.org' ],
			// But they don't match strange things like this
			[ false, '//example.org', 'irc://example.org' ],
		];
	}

	/**
	 * Test to make sure ParserOutput::isLinkInternal behaves properly
	 * @dataProvider provideIsLinkInternal
	 * @covers \MediaWiki\Parser\ParserOutput::isLinkInternal
	 */
	public function testIsLinkInternal( $shouldMatch, $server, $url ) {
		$this->assertEquals( $shouldMatch, ParserOutput::isLinkInternal( $server, $url ) );
	}

	/**
	 * @covers \MediaWiki\Parser\ParserOutput::appendJsConfigVar
	 * @covers \MediaWiki\Parser\ParserOutput::setJsConfigVar
	 * @covers \MediaWiki\Parser\ParserOutput::getJsConfigVars
	 */
	public function testJsConfigVars() {
		$po = new ParserOutput();

		$po->setJsConfigVar( 'a', '1' );
		$po->appendJsConfigVar( 'b', 'a' );
		$po->appendJsConfigVar( 'b', '0' );

		$this->assertEqualsCanonicalizing( [
			'a' => 1,
			'b' => [ 'a' => true, '0' => true ],
		], $po->getJsConfigVars() );

		$po->setJsConfigVar( 'c', '2' );
		$po->appendJsConfigVar( 'b', 'b' );
		$po->appendJsConfigVar( 'b', '1' );

		$this->assertEqualsCanonicalizing( [
			'a' => 1,
			'b' => [ 'a' => true, 'b' => true, '0' => true, '1' => true ],
			'c' => 2,
		], $po->getJsConfigVars() );
	}

	/**
	 * @covers \MediaWiki\Parser\ParserOutput::appendExtensionData
	 * @covers \MediaWiki\Parser\ParserOutput::setExtensionData
	 * @covers \MediaWiki\Parser\ParserOutput::getExtensionData
	 */
	public function testExtensionData() {
		$po = new ParserOutput();

		$po->setExtensionData( "one", "Foo" );
		$po->appendExtensionData( "three", "abc" );

		$this->assertEquals( "Foo", $po->getExtensionData( "one" ) );
		$this->assertNull( $po->getExtensionData( "spam" ) );

		$po->setExtensionData( "two", "Bar" );
		$this->assertEquals( "Foo", $po->getExtensionData( "one" ) );
		$this->assertEquals( "Bar", $po->getExtensionData( "two" ) );

		// Note that overwriting extension data (as this test case
		// does) is deprecated and will eventually throw an
		// exception. However, at the moment it is still worth testing
		// this case to ensure backward compatibility. (T300981)
		$po->setExtensionData( "one", null );
		$this->assertNull( $po->getExtensionData( "one" ) );
		$this->assertEquals( "Bar", $po->getExtensionData( "two" ) );

		$this->assertEqualsCanonicalizing( [
			'abc' => true,
		], $po->getExtensionData( "three" ) );

		$po->appendExtensionData( "three", "xyz" );
		$this->assertEqualsCanonicalizing( [
			'abc' => true,
			'xyz' => true,
		], $po->getExtensionData( "three" ) );
	}

	/**
	 * @covers \MediaWiki\Parser\ParserOutput::setPageProperty
	 * @covers \MediaWiki\Parser\ParserOutput::setNumericPageProperty
	 * @covers \MediaWiki\Parser\ParserOutput::setUnsortedPageProperty
	 * @covers \MediaWiki\Parser\ParserOutput::getPageProperty
	 * @covers \MediaWiki\Parser\ParserOutput::unsetPageProperty
	 * @covers \MediaWiki\Parser\ParserOutput::getPageProperties
	 * @dataProvider providePageProperties
	 */
	public function testPageProperties( string $setPageProperty, $value1, $value2 ) {
		$po = new ParserOutput();
		$po->$setPageProperty( 'foo', $value1 );

		$properties = $po->getPageProperties();
		$this->assertSame( $value1, $po->getPageProperty( 'foo' ) );
		$this->assertSame( $value1, $properties['foo'] );

		$po->$setPageProperty( 'foo', $value2 );

		$properties = $po->getPageProperties();
		$this->assertSame( $value2, $po->getPageProperty( 'foo' ) );
		$this->assertSame( $value2, $properties['foo'] );

		$po->unsetPageProperty( 'foo' );

		$properties = $po->getPageProperties();
		$this->assertSame( null, $po->getPageProperty( 'foo' ) );
		$this->assertArrayNotHasKey( 'foo', $properties );
	}

	public static function providePageProperties() {
		yield 'Unsorted' => [ 'setUnsortedPageProperty', 'val', 'second val' ];
		yield 'Numeric' => [ 'setNumericPageProperty', 42, 3.14 ];
		yield 'Unsorted (old style)' => [ 'setPageProperty', 'val', 'second val' ];
	}

	/**
	 * @covers \MediaWiki\Parser\ParserOutput::setNumericPageProperty
	 */
	public function testNumericPageProperties() {
		$po = new ParserOutput();

		$po->setNumericPageProperty( 'foo', '123' );

		$properties = $po->getPageProperties();
		$this->assertSame( 123, $po->getPageProperty( 'foo' ) );
		$this->assertSame( 123, $properties['foo'] );
	}

	/**
	 * @covers \MediaWiki\Parser\ParserOutput::setUnsortedPageProperty
	 */
	public function testUnsortedPageProperties() {
		$po = new ParserOutput();

		$po->setUnsortedPageProperty( 'foo', 123 );

		$properties = $po->getPageProperties();
		$this->assertSame( '123', $po->getPageProperty( 'foo' ) );
		$this->assertSame( '123', $properties['foo'] );
	}

	/**
	 * @covers \MediaWiki\Parser\ParserOutput::setLanguage
	 * @covers \MediaWiki\Parser\ParserOutput::getLanguage
	 */
	public function testLanguage() {
		$po = new ParserOutput();

		$langFr = new Bcp47CodeValue( 'fr' );
		$langCrhCyrl = new Bcp47CodeValue( 'crh-cyrl' );

		// Fallback to null
		$this->assertSame( null, $po->getLanguage() );

		// Simple case
		$po->setLanguage( $langFr );
		$this->assertSame( $langFr->toBcp47Code(), $po->getLanguage()->toBcp47Code() );

		// Language with a variant
		$po->setLanguage( $langCrhCyrl );
		$this->assertSame( $langCrhCyrl->toBcp47Code(), $po->getLanguage()->toBcp47Code() );
	}

	/**
	 * @covers \MediaWiki\Parser\ParserOutput::getWrapperDivClass
	 * @covers \MediaWiki\Parser\ParserOutput::addWrapperDivClass
	 * @covers \MediaWiki\Parser\ParserOutput::clearWrapperDivClass
	 */
	public function testWrapperDivClass() {
		$po = new ParserOutput();
		$opts = ParserOptions::newFromAnon();
		$pipeline = MediaWikiServices::getInstance()->getDefaultOutputPipeline();

		$po->setRawText( 'Kittens' );
		$text = $pipeline->run( $po, $opts, [] )->getContentHolderText();
		$this->assertStringContainsString( 'Kittens', $text );
		$this->assertStringNotContainsString( '<div', $text );
		$this->assertSame( 'Kittens', $po->getRawText() );

		$po->addWrapperDivClass( 'foo' );
		$text = $pipeline->run( $po, $opts, [] )->getContentHolderText();
		$this->assertStringContainsString( 'Kittens', $text );
		$this->assertStringContainsString( '<div', $text );
		$this->assertStringContainsString( 'class="mw-content-ltr foo"', $text );

		$po->addWrapperDivClass( 'bar' );
		$text = $pipeline->run( $po, $opts, [] )->getContentHolderText();
		$this->assertStringContainsString( 'Kittens', $text );
		$this->assertStringContainsString( '<div', $text );
		$this->assertStringContainsString( 'class="mw-content-ltr foo bar"', $text );

		$po->addWrapperDivClass( 'bar' ); // second time does nothing, no "foo bar bar".
		$text = $pipeline->run( $po, $opts, [ 'unwrap' => true ] )->getContentHolderText();
		$this->assertStringContainsString( 'Kittens', $text );
		$this->assertStringNotContainsString( '<div', $text );
		$this->assertStringNotContainsString( 'class="', $text );

		$text = $pipeline->run( $po, $opts, [ 'wrapperDivClass' => '' ] )->getContentHolderText();
		$this->assertStringContainsString( 'Kittens', $text );
		$this->assertStringNotContainsString( '<div', $text );
		$this->assertStringNotContainsString( 'class="', $text );

		$text = $pipeline->run( $po, $opts, [ 'wrapperDivClass' => 'xyzzy' ] )->getContentHolderText();
		$this->assertStringContainsString( 'Kittens', $text );
		$this->assertStringContainsString( '<div', $text );
		$this->assertStringContainsString( 'class="mw-content-ltr xyzzy"', $text );
		$this->assertStringNotContainsString( 'foo bar', $text );

		$text = $po->getRawText();
		$this->assertSame( 'Kittens', $text );

		$po->clearWrapperDivClass();
		$text = $pipeline->run( $po, $opts, [] )->getContentHolderText();
		$this->assertStringContainsString( 'Kittens', $text );
		$this->assertStringNotContainsString( '<div', $text );
		$this->assertStringNotContainsString( 'class="', $text );
	}

	/**
	 * @covers \MediaWiki\Parser\ParserOutput::hasText
	 */
	public function testHasText() {
		$po = new ParserOutput( '' );
		$this->assertTrue( $po->hasText() );

		$po = new ParserOutput( null );
		$this->assertFalse( $po->hasText() );

		$po = new ParserOutput();
		$this->assertFalse( $po->hasText() );

		$po = new ParserOutput( '' );
		$this->assertTrue( $po->hasText() );

		$po = new ParserOutput( null );
		$po->setRawText( '' );
		$this->assertTrue( $po->hasText() );

		$po = new ParserOutput( 'foo' );
		$po->setRawText( null );
		$this->assertFalse( $po->hasText() );
	}

	/**
	 * @covers \MediaWiki\Parser\ParserOutput::getRawText
	 */
	public function testGetRawText_failsIfNoText() {
		$po = new ParserOutput( null );

		$this->expectException( LogicException::class );
		$po->getRawText();
	}

	public static function provideMergeHtmlMetaDataFrom() {
		// title text ------------
		$a = new ParserOutput();
		$a->setTitleText( 'X' );
		$b = new ParserOutput();
		yield 'only left title text' => [ $a, $b, [ 'getTitleText' => 'X' ] ];

		$a = new ParserOutput();
		$b = new ParserOutput();
		$b->setTitleText( 'Y' );
		yield 'only right title text' => [ $a, $b, [ 'getTitleText' => 'Y' ] ];

		$a = new ParserOutput();
		$a->setTitleText( 'X' );
		$b = new ParserOutput();
		$b->setTitleText( 'Y' );
		yield 'left title text wins' => [ $a, $b, [ 'getTitleText' => 'X' ] ];

		// index policy ------------
		$a = new ParserOutput();
		$a->setIndexPolicy( 'index' );
		$b = new ParserOutput();
		yield 'only left index policy' => [ $a, $b, [ 'getIndexPolicy' => 'index' ] ];

		$a = new ParserOutput();
		$b = new ParserOutput();
		$b->setIndexPolicy( 'index' );
		yield 'only right index policy' => [ $a, $b, [ 'getIndexPolicy' => 'index' ] ];

		$a = new ParserOutput();
		$a->setIndexPolicy( 'noindex' );
		$b = new ParserOutput();
		$b->setIndexPolicy( 'index' );
		yield 'left noindex wins' => [ $a, $b, [ 'getIndexPolicy' => 'noindex' ] ];

		$a = new ParserOutput();
		$a->setIndexPolicy( 'index' );
		$b = new ParserOutput();
		$b->setIndexPolicy( 'noindex' );
		yield 'right noindex wins' => [ $a, $b, [ 'getIndexPolicy' => 'noindex' ] ];

		$crhCyrl = new Bcp47CodeValue( 'crh-cyrl' );

		$a = new ParserOutput();
		$a->setLanguage( $crhCyrl );
		$b = new ParserOutput();
		yield 'only left language' => [ $a, $b, [ 'getLanguage' => $crhCyrl ] ];

		$a = new ParserOutput();
		$b = new ParserOutput();
		$b->setLanguage( $crhCyrl );
		yield 'only right language' => [ $a, $b, [ 'getLanguage' => $crhCyrl ] ];

		// head items and friends ------------
		$a = new ParserOutput();
		$a->addHeadItem( '<foo1>' );
		$a->addHeadItem( '<bar1>', 'bar' );
		$a->addModules( [ 'test-module-a' ] );
		$a->addModuleStyles( [ 'test-module-styles-a' ] );
		$a->setJsConfigVar( 'test-config-var-a', 'a' );
		$a->appendJsConfigVar( 'test-config-var-c', 'abc' );
		$a->appendJsConfigVar( 'test-config-var-c', 'def' );
		$a->addExtraCSPStyleSrc( 'css.com' );
		$a->addExtraCSPStyleSrc( 'css2.com' );
		$a->addExtraCSPScriptSrc( 'js.com' );
		$a->addExtraCSPDefaultSrc( 'img.com' );

		$b = new ParserOutput();
		$b->setIndexPolicy( 'noindex' );
		$b->addHeadItem( '<foo2>' );
		$b->addHeadItem( '<bar2>', 'bar' );
		$b->addModules( [ 'test-module-b' ] );
		$b->addModuleStyles( [ 'test-module-styles-b' ] );
		$b->setJsConfigVar( 'test-config-var-b', 'b' );
		$b->setJsConfigVar( 'test-config-var-a', 'X' );
		$a->appendJsConfigVar( 'test-config-var-c', 'xyz' );
		$a->appendJsConfigVar( 'test-config-var-c', 'def' );
		$b->addExtraCSPStyleSrc( 'https://css.ca' );
		$b->addExtraCSPScriptSrc( 'jscript.com' );
		$b->addExtraCSPScriptSrc( 'vbscript.com' );
		$b->addExtraCSPDefaultSrc( 'img.com/foo.jpg' );

		// Note that overwriting test-config-var-a during the merge
		// (as this test case does) is deprecated and will eventually
		// throw an exception. However, at the moment it is still worth
		// testing this case to ensure backward compatibility. (T300307)
		yield 'head items and friends' => [ $a, $b, [
			'getHeadItems' => [
				'<foo1>',
				'<foo2>',
				'bar' => '<bar2>', // overwritten
			],
			'getModules' => [
				'test-module-a',
				'test-module-b',
			],
			'getModuleStyles' => [
				'test-module-styles-a',
				'test-module-styles-b',
			],
			'getJsConfigVars' => [
				'test-config-var-a' => 'X', // overwritten
				'test-config-var-b' => 'b',
				'test-config-var-c' => [ // merged safely
					'abc' => true, 'def' => true, 'xyz' => true,
				],
			],
			'getExtraCSPStyleSrcs' => [
				'css.com',
				'css2.com',
				'https://css.ca'
			],
			'getExtraCSPScriptSrcs' => [
				'js.com',
				'jscript.com',
				'vbscript.com'
			],
			'getExtraCSPDefaultSrcs' => [
				'img.com',
				'img.com/foo.jpg'
			]
		] ];

		// TOC ------------
		$a = new ParserOutput( '' );
		$a->setSections( [ [ 'fromtitle' => 'A1' ], [ 'fromtitle' => 'A2' ] ] );

		$b = new ParserOutput( '' );
		$b->setSections( [ [ 'fromtitle' => 'B1' ], [ 'fromtitle' => 'B2' ] ] );

		yield 'concat TOC' => [ $a, $b, [
			'getSections' => [
				SectionMetadata::fromLegacy( [ 'fromtitle' => 'A1' ] )->toLegacy(),
				SectionMetadata::fromLegacy( [ 'fromtitle' => 'A2' ] )->toLegacy(),
				SectionMetadata::fromLegacy( [ 'fromtitle' => 'B1' ] )->toLegacy(),
				SectionMetadata::fromLegacy( [ 'fromtitle' => 'B2' ] )->toLegacy()
			],
		] ];

		// Skin Control  ------------
		$a = new ParserOutput();
		$a->setNewSection( true );
		$a->setHideNewSection( true );
		$a->setNoGallery( true );
		$a->addWrapperDivClass( 'foo' );

		$a->setIndicator( 'foo', 'Foo!' );
		$a->setIndicator( 'bar', 'Bar!' );

		$a->setExtensionData( 'foo', 'Foo!' );
		$a->setExtensionData( 'bar', 'Bar!' );
		$a->appendExtensionData( 'bat', 'abc' );

		$b = new ParserOutput();
		$b->setNoGallery( true );
		$b->setEnableOOUI( true );
		$b->setPreventClickjacking( true );
		$a->addWrapperDivClass( 'bar' );

		$b->setIndicator( 'zoo', 'Zoo!' );
		$b->setIndicator( 'bar', 'Barrr!' );

		$b->setExtensionData( 'zoo', 'Zoo!' );
		$b->setExtensionData( 'bar', 'Barrr!' );
		$b->appendExtensionData( 'bat', 'xyz' );

		// Note that overwriting extension data during the merge
		// (as this test case does for 'bar') is deprecated and will eventually
		// throw an exception. However, at the moment it is still worth
		// testing this case to ensure backward compatibility. (T300981)
		yield 'skin control flags' => [ $a, $b, [
			'getNewSection' => true,
			'getHideNewSection' => true,
			'getNoGallery' => true,
			'getEnableOOUI' => true,
			'getPreventClickjacking' => true,
			'getIndicators' => [
				'foo' => 'Foo!',
				'bar' => 'Barrr!', // overwritten
				'zoo' => 'Zoo!',
			],
			'getWrapperDivClass' => 'foo bar',
			'$mExtensionData' => [
				'foo' => 'Foo!',
				'bar' => 'Barrr!', // overwritten
				'zoo' => 'Zoo!',
				// internal strategy key is exposed here because we're looking
				// at the raw property value, not using getExtensionData()
				'bat' => [ 'abc' => true, 'xyz' => true, '_mw-strategy' => 'union' ],
			],
		] ];
	}

	/**
	 * @dataProvider provideMergeHtmlMetaDataFrom
	 * @covers \MediaWiki\Parser\ParserOutput::mergeHtmlMetaDataFrom
	 *
	 * @param ParserOutput $a
	 * @param ParserOutput $b
	 * @param array $expected
	 */
	public function testMergeHtmlMetaDataFrom( ParserOutput $a, ParserOutput $b, $expected ) {
		$a->mergeHtmlMetaDataFrom( $b );

		$this->assertFieldValues( $a, $expected );

		// test twice, to make sure the operation is idempotent (except for the TOC, see below)
		$a->mergeHtmlMetaDataFrom( $b );

		// XXX: TOC joining should get smarter. Can we make it idempotent as well?
		unset( $expected['getSections'] );

		$this->assertFieldValues( $a, $expected );
	}

	private function assertFieldValues( ParserOutput $po, $expected ) {
		$po = TestingAccessWrapper::newFromObject( $po );

		foreach ( $expected as $method => $value ) {
			$canonicalize = false;
			if ( $method[0] === '$' ) {
				$field = substr( $method, 1 );
				$actual = $po->__get( $field );
			} elseif ( str_contains( $method, '!' ) ) {
				[ $trimmedMethod, $ignore ] = explode( '!', $method, 2 );
				$args = $value['_args_'] ?? [];
				unset( $value['_args_'] );
				$actual = $po->__call( $trimmedMethod, $args );
			} else {
				$actual = $po->__call( $method, [] );
			}
			if ( $method === 'getJsConfigVars' ) {
				$canonicalize = true;
			}

			if ( $canonicalize ) {
				// order of entries isn't significant
				$this->assertEqualsCanonicalizing( $value, $actual, $method );
			} else {
				$this->assertEquals( $value, $actual, $method );
			}
		}
	}

	/**
	 * @covers \MediaWiki\Parser\ParserOutput::addLink
	 * @covers \MediaWiki\Parser\ParserOutput::getLinks
	 * @covers \MediaWiki\Parser\ParserOutput::getLinkList
	 */
	public function testAddLink() {
		$a = new ParserOutput();
		$a->addLink( Title::makeTitle( NS_MAIN, 'Kittens' ), 6 );
		$a->addLink( new TitleValue( NS_TALK, 'Kittens' ), 16 );
		$a->addLink( new TitleValue( NS_MAIN, 'Goats_786827346' ) );
		# fragments are stripped for local links
		$a->addLink( new TitleValue( NS_TALK, 'Puppies', 'Topic' ), 17 );

		$expected = [
			NS_MAIN => [ 'Kittens' => 6, 'Goats_786827346' => 0 ],
			NS_TALK => [ 'Kittens' => 16, 'Puppies' => 17 ]
		];
		$this->assertSame( $expected, $a->getLinks() );
		$expected = [
			[
				'link' => new TitleValue( NS_MAIN, 'Kittens' ),
				'pageid' => 6,
			],
			[
				'link' => new TitleValue( NS_MAIN, 'Goats_786827346' ),
				'pageid' => 0,
			],
			[
				'link' => new TitleValue( NS_TALK, 'Kittens' ),
				'pageid' => 16,
			],
			[
				'link' => new TitleValue( NS_TALK, 'Puppies' ),
				'pageid' => 17,
			],
		];
		$this->assertEquals( $expected, $a->getLinkList( ParserOutputLinkTypes::LOCAL ) );
	}

	public static function provideMergeTrackingMetaDataFrom() {
		// links ------------
		$a = new ParserOutput();
		$a->addLink( Title::makeTitle( NS_MAIN, 'Kittens' ), 6 );
		$a->addLink( new TitleValue( NS_TALK, 'Kittens' ), 16 );
		# fragments are stripped in local links
		$a->addLink( new TitleValue( NS_MAIN, 'Goats', 'Kids' ), 7 );

		$a->addTemplate( Title::makeTitle( NS_TEMPLATE, 'Goats' ), 107, 1107 );

		$a->addLanguageLink( new TitleValue( NS_MAIN, 'de', '', 'de' ) );
		# fragments are preserved in language links
		$a->addLanguageLink( new TitleValue( NS_MAIN, 'ru', 'ru', 'ru' ) );
		$a->addInterwikiLink( Title::makeTitle( NS_MAIN, 'Kittens DE', '', 'de' ) );
		# fragments are stripped in interwiki links
		$a->addInterwikiLink( new TitleValue( NS_MAIN, 'Kittens RU', 'ru', 'ru' ) );
		$a->addExternalLink( 'https://kittens.wikimedia.test' );
		# fragments are preserved in external links
		$a->addExternalLink( 'https://goats.wikimedia.test#kids' );

		# fragments are stripped for categories (syntax is overloaded for sort)
		$a->addCategory( new TitleValue( NS_CATEGORY, 'Foo', 'bar' ), 'X' );
		# fragments are stripped for images
		$a->addImage( new TitleValue( NS_FILE, 'Billy.jpg', 'fragment' ), '20180101000013', 'DEAD' );
		# fragments are stripped for links to special pages
		$a->addLink( new TitleValue( NS_SPECIAL, 'Version', 'section' ) );

		$b = new ParserOutput();
		$b->addLink( Title::makeTitle( NS_MAIN, 'Goats' ), 7 );
		$b->addLink( Title::makeTitle( NS_TALK, 'Goats' ), 17 );
		$b->addLink( new TitleValue( NS_MAIN, 'Dragons' ), 8 );
		$b->addLink( new TitleValue( NS_FILE, 'Dragons.jpg' ), 28 );

		# fragments are stripped from template links
		$b->addTemplate( Title::makeTitle( NS_TEMPLATE, 'Dragons', 'red' ), 108, 1108 );
		$a->addTemplate( new TitleValue( NS_MAIN, 'Dragons', 'platinum' ), 118, 1118 );

		$b->addLanguageLink( new TitleValue( NS_MAIN, 'fr', '', 'fr' ) );
		$b->addLanguageLink( new TitleValue( NS_MAIN, 'ru', 'ru', 'ru' ) );
		$b->addInterwikiLink( Title::makeTitle( NS_MAIN, 'Kittens FR', '', 'fr' ) );
		$b->addInterwikiLink( new TitleValue( NS_MAIN, 'Dragons RU', '', 'ru' ) );
		$b->addExternalLink( 'https://dragons.wikimedia.test' );
		$b->addExternalLink( 'https://goats.wikimedia.test#kids' );

		$b->addCategory( 'Bar', 'Y' );
		$b->addImage( new TitleValue( NS_FILE, 'Puff.jpg' ), '20180101000017', 'BEEF' );

		yield 'all kinds of links' => [ $a, $b, [
			'getLinks' => [
				NS_MAIN => [
					'Kittens' => 6,
					'Goats' => 7,
					'Dragons' => 8,
				],
				NS_TALK => [
					'Kittens' => 16,
					'Goats' => 17,
				],
				NS_FILE => [
					'Dragons.jpg' => 28,
				],
			],
			'getLinkList!LOCAL' => [
				'_args_' => [ ParserOutputLinkTypes::LOCAL ],
				[
					'link' => new TitleValue( NS_MAIN, 'Kittens' ),
					'pageid' => 6,
				],
				[
					'link' => new TitleValue( NS_MAIN, 'Goats' ),
					'pageid' => 7,
				],
				[
					'link' => new TitleValue( NS_MAIN, 'Dragons' ),
					'pageid' => 8,
				],
				[
					'link' => new TitleValue( NS_TALK, 'Kittens' ),
					'pageid' => 16,
				],
				[
					'link' => new TitleValue( NS_TALK, 'Goats' ),
					'pageid' => 17,
				],
				[
					'link' => new TitleValue( NS_FILE, 'Dragons.jpg' ),
					'pageid' => 28,
				],
			],
			'getTemplates' => [
				NS_MAIN => [
					'Dragons' => 118,
				],
				NS_TEMPLATE => [
					'Dragons' => 108,
					'Goats' => 107,
				],
			],
			'getTemplateIds' => [
				NS_MAIN => [
					'Dragons' => 1118,
				],
				NS_TEMPLATE => [
					'Dragons' => 1108,
					'Goats' => 1107,
				],
			],
			'getLinkList!TEMPLATE' => [
				'_args_' => [ ParserOutputLinkTypes::TEMPLATE ],
				[
					'link' => new TitleValue( NS_TEMPLATE, 'Goats' ),
					'pageid' => 107,
					'revid' => 1107,
				],
				[
					'link' => new TitleValue( NS_TEMPLATE, 'Dragons' ),
					'pageid' => 108,
					'revid' => 1108,
				],
				[
					'link' => new TitleValue( NS_MAIN, 'Dragons' ),
					'pageid' => 118,
					'revid' => 1118,
				],
			],
			'getLanguageLinks' => [ 'de:de', 'ru:ru#ru', 'fr:fr' ],
			'getLinkList!LANGUAGE' => [
				'_args_' => [ ParserOutputLinkTypes::LANGUAGE ],
				[
					'link' => new TitleValue( NS_MAIN, 'de', '', 'de' ),
				],
				[
					'link' => new TitleValue( NS_MAIN, 'ru', 'ru', 'ru' ),
				],
				[
					'link' => new TitleValue( NS_MAIN, 'fr', '', 'fr' ),
				],
			],
			'getInterwikiLinks' => [
				'de' => [ 'Kittens_DE' => 1 ],
				'ru' => [ 'Kittens_RU' => 1, 'Dragons_RU' => 1, ],
				'fr' => [ 'Kittens_FR' => 1 ],
			],
			'getLinkList!INTERWIKI' => [
				'_args_' => [ ParserOutputLinkTypes::INTERWIKI ],
				[
					'link' => new TitleValue( NS_MAIN, 'Kittens_DE', '', 'de' ),
				],
				[
					'link' => new TitleValue( NS_MAIN, 'Kittens_RU', '', 'ru' ),
				],
				[
					'link' => new TitleValue( NS_MAIN, 'Dragons_RU', '', 'ru' ),
				],
				[
					'link' => new TitleValue( NS_MAIN, 'Kittens_FR', '', 'fr' ),
				],
			],
			'getCategoryMap' => [ 'Foo' => 'X', 'Bar' => 'Y' ],
			'getLinkList!CATEGORY' => [
				'_args_' => [ ParserOutputLinkTypes::CATEGORY ],
				[
					'link' => new TitleValue( NS_CATEGORY, 'Foo' ),
					'sort' => 'X',
				],
				[
					'link' => new TitleValue( NS_CATEGORY, 'Bar' ),
					'sort' => 'Y',
				],
			],
			'getImages' => [ 'Billy.jpg' => 1, 'Puff.jpg' => 1 ],
			'getFileSearchOptions' => [
				'Billy.jpg' => [ 'time' => '20180101000013', 'sha1' => 'DEAD' ],
				'Puff.jpg' => [ 'time' => '20180101000017', 'sha1' => 'BEEF' ],
			],
			'getLinkList!MEDIA' => [
				'_args_' => [ ParserOutputLinkTypes::MEDIA ],
				[
					'link' => new TitleValue( NS_FILE, 'Billy.jpg' ),
					'time' => '20180101000013',
					'sha1' => 'DEAD',
				],
				[
					'link' => new TitleValue( NS_FILE, 'Puff.jpg' ),
					'time' => '20180101000017',
					'sha1' => 'BEEF',
				],
			],
			'getExternalLinks' => [
				'https://dragons.wikimedia.test' => 1,
				'https://kittens.wikimedia.test' => 1,
				'https://goats.wikimedia.test#kids' => 1,
			],
			'getLinkList!SPECIAL' => [
				'_args_' => [ ParserOutputLinkTypes::SPECIAL ],
				[
					'link' => new TitleValue( NS_SPECIAL, 'Version' ),
				],
			],
		] ];

		// properties ------------
		$a = new ParserOutput();

		$a->setPageProperty( 'foo', 'Foo!' );
		$a->setPageProperty( 'bar', 'Bar!' );

		$a->setExtensionData( 'foo', 'Foo!' );
		$a->setExtensionData( 'bar', 'Bar!' );
		$a->appendExtensionData( 'bat', 'abc' );

		$b = new ParserOutput();

		$b->setPageProperty( 'zoo', 'Zoo!' );
		$b->setPageProperty( 'bar', 'Barrr!' );

		$b->setExtensionData( 'zoo', 'Zoo!' );
		$b->setExtensionData( 'bar', 'Barrr!' );
		$b->appendExtensionData( 'bat', 'xyz' );

		// Note that overwriting extension data during the merge
		// (as this test case does for 'bar') is deprecated and will eventually
		// throw an exception. However, at the moment it is still worth
		// testing this case to ensure backward compatibility. (T300981)
		yield 'properties' => [ $a, $b, [
			'getPageProperties' => [
				'foo' => 'Foo!',
				'bar' => 'Barrr!', // overwritten
				'zoo' => 'Zoo!',
			],
			'$mExtensionData' => [
				'foo' => 'Foo!',
				'bar' => 'Barrr!', // overwritten
				'zoo' => 'Zoo!',
				// internal strategy key is exposed here because we're looking
				// at the raw property value, not using getExtensionData()
				'bat' => [ 'abc' => true, 'xyz' => true, '_mw-strategy' => 'union' ],
			],
		] ];
	}

	/**
	 * @dataProvider provideMergeTrackingMetaDataFrom
	 * @covers \MediaWiki\Parser\ParserOutput::mergeTrackingMetaDataFrom
	 *
	 * @param ParserOutput $a
	 * @param ParserOutput $b
	 * @param array $expected
	 */
	public function testMergeTrackingMetaDataFrom( ParserOutput $a, ParserOutput $b, $expected ) {
		$a->mergeTrackingMetaDataFrom( $b );

		$this->assertFieldValues( $a, $expected );

		// test twice, to make sure the operation is idempotent
		$a->mergeTrackingMetaDataFrom( $b );

		$this->assertFieldValues( $a, $expected );
	}

	/**
	 * @dataProvider provideMergeTrackingMetaDataFrom
	 * @covers \MediaWiki\Parser\ParserOutput::collectMetadata
	 *
	 * @param ParserOutput $a
	 * @param ParserOutput $b
	 * @param array $expected
	 */
	public function testCollectMetaData( ParserOutput $a, ParserOutput $b, $expected ) {
		$b->collectMetadata( $a );

		$this->assertFieldValues( $a, $expected );
	}

	public static function provideMergeInternalMetaDataFrom() {
		// flags & co
		$a = [
			'warning' => [
				[ 'duplicate-args-warning', 'A', 'B', 'C' ],
				[ 'template-loop-warning', 'D' ],
			],
			'outputFlag' => [ 'foo', 'bar' ],
			'recordOption' => [ 'Foo', 'Bar' ],
		];

		$b = [
			'warning' => [
				[ 'template-equals-warning' ],
				[ 'template-loop-warning', 'D' ],
			],
			'outputFlag' => [ 'zoo', 'bar' ],
			'recordOption' => [ 'Zoo', 'Bar' ],
		];

		yield 'flags' => [ $a, $b, [
			'getWarnings' => [
				wfMessage( 'duplicate-args-warning', 'A', 'B', 'C' )->text(),
				wfMessage( 'template-loop-warning', 'D' )->text(),
				wfMessage( 'template-equals-warning' )->text(),
			],
			'$mFlags' => [ 'foo' => true, 'bar' => true, 'zoo' => true ],
			'getUsedOptions' => [ 'Foo', 'Bar', 'Zoo' ],
		] ];

		// cache time
		$someTime = "20240207202040";
		$someLaterTime = "20240207202112";
		$a = [
			'cacheTime' => $someTime,
		];
		$b = [];
		yield 'only left cache time' => [ $a, $b, [ 'getCacheTime' => $someTime ] ];

		$a = [];
		$b = [
			'cacheTime' => $someTime,
		];
		yield 'only right cache time' => [ $a, $b, [ 'getCacheTime' => $someTime ] ];

		$a = [
			'cacheTime' => $someLaterTime,
		];
		$b = [
			'cacheTime' => $someTime,
		];
		yield 'left has later cache time' => [ $a, $b, [ 'getCacheTime' => $someLaterTime ] ];

		$a = [
			'cacheTime' => $someTime,
		];
		$b = [
			'cacheTime' => $someLaterTime,
		];
		yield 'right has later cache time' => [ $a, $b, [ 'getCacheTime' => $someLaterTime ] ];

		$a = [
			'cacheTime' => -1,
		];
		$b = [
			'cacheTime' => $someTime,
		];
		yield 'left is uncacheable' => [ $a, $b, [ 'getCacheTime' => "-1" ] ];

		$a = [
			'cacheTime' => $someTime,
		];
		$b = [
			'cacheTime' => -1,
		];
		yield 'right is uncacheable' => [ $a, $b, [ 'getCacheTime' => "-1" ] ];

		// timestamp ------------
		$a = [
			'revisionTimestamp' => '20180101000011',
		];
		$b = [];
		yield 'only left timestamp' => [ $a, $b, [ 'getTimestamp' => '20180101000011' ] ];

		$a = [];
		$b = [
			'revisionTimestamp' => '20180101000011',
		];
		yield 'only right timestamp' => [ $a, $b, [ 'getTimestamp' => '20180101000011' ] ];

		$a = [
			'revisionTimestamp' => '20180101000011',
		];
		$b = [
			'revisionTimestamp' => '20180101000001',
		];
		yield 'left timestamp wins' => [ $a, $b, [ 'getTimestamp' => '20180101000011' ] ];

		$a = [
			'revisionTimestamp' => '20180101000001',
		];
		$b = [
			'revisionTimestamp' => '20180101000011',
		];
		yield 'right timestamp wins' => [ $a, $b, [ 'getTimestamp' => '20180101000011' ] ];

		// speculative rev id ------------
		$a = [
			'speculativeRevIdUsed' => 9,
		];
		$b = [];
		yield 'only left speculative rev id' => [ $a, $b, [ 'getSpeculativeRevIdUsed' => 9 ] ];

		$a = [];
		$b = [
			'speculativeRevIdUsed' => 9,
		];
		yield 'only right speculative rev id' => [ $a, $b, [ 'getSpeculativeRevIdUsed' => 9 ] ];

		$a = [
			'speculativeRevIdUsed' => 9,
		];
		$b = [
			'speculativeRevIdUsed' => 9,
		];
		yield 'same speculative rev id' => [ $a, $b, [ 'getSpeculativeRevIdUsed' => 9 ] ];

		// limit report (recursive max) ------------
		$a = [
			'limitReportData' => [
				[ 'naive1', 7 ],
				[ 'naive2', 27 ],

				[ 'limitreport-simple1', 7 ],
				[ 'limitreport-simple2', 27 ],

				[ 'limitreport-pair1', [ 7, 9 ] ],
				[ 'limitreport-pair2', [ 27, 29 ] ],

				[ 'limitreport-more1', [ 7, 9, 1 ] ],
				[ 'limitreport-more2', [ 27, 29, 21 ] ],

				[ 'limitreport-only-a', 13 ],
			],
		];

		$b = [
			'limitReportData' => [
				[ 'naive1', 17 ],
				[ 'naive2', 17 ],

				[ 'limitreport-simple1', 17 ],
				[ 'limitreport-simple2', 17 ],

				[ 'limitreport-pair1', [ 17, 19 ] ],
				[ 'limitreport-pair2', [ 17, 19 ] ],

				[ 'limitreport-more1', [ 17, 19, 11 ] ],
				[ 'limitreport-more2', [ 17, 19, 11 ] ],

				[ 'limitreport-only-b', 23 ],
			],
		];

		// first write wins
		yield 'limit report' => [ $a, $b, [
			'getLimitReportData' => [
				'naive1' => 7,
				'naive2' => 27,
				'limitreport-simple1' => 7,
				'limitreport-simple2' => 27,
				'limitreport-pair1' => [ 7, 9 ],
				'limitreport-pair2' => [ 27, 29 ],
				'limitreport-more1' => [ 7, 9, 1 ],
				'limitreport-more2' => [ 27, 29, 21 ],
				'limitreport-only-a' => 13,
			],
			'getLimitReportJSData' => [
				'naive1' => 7,
				'naive2' => 27,
				'limitreport' => [
					'simple1' => 7,
					'simple2' => 27,
					'pair1' => [ 'value' => 7, 'limit' => 9 ],
					'pair2' => [ 'value' => 27, 'limit' => 29 ],
					'more1' => [ 7, 9, 1 ],
					'more2' => [ 27, 29, 21 ],
					'only-a' => 13,
				],
			],
		] ];
	}

	/**
	 * @dataProvider provideMergeInternalMetaDataFrom
	 * @covers \MediaWiki\Parser\ParserOutput::mergeInternalMetaDataFrom
	 */
	public function testMergeInternalMetaDataFrom( array $aSpec, array $bSpec, $expected ) {
		$this->filterDeprecated( '/^.*CacheTime::setCacheTime called with -1 as an argument/' );
		$a = $this->createParserOutput( $aSpec );
		$b = $this->createParserOutput( $bSpec );
		$a->mergeInternalMetaDataFrom( $b );

		$this->assertFieldValues( $a, $expected );

		// test twice, to make sure the operation is idempotent
		$a->mergeInternalMetaDataFrom( $b );

		$this->assertFieldValues( $a, $expected );
	}

	private function createParserOutput( array $spec ): ParserOutput {
		$po = new ParserOutput();
		foreach ( $spec['warning'] ?? [] as $warning ) {
			$po->addWarningMsg( ...$warning );
		}
		foreach ( $spec['outputFlag'] ?? [] as $outputFlag ) {
			$po->setOutputFlag( $outputFlag );
		}
		foreach ( $spec['recordOption'] ?? [] as $recordOption ) {
			$po->recordOption( $recordOption );
		}
		foreach ( $spec['limitReportData'] ?? [] as $limitReportData ) {
			$po->setLimitReportData( ...$limitReportData );
		}
		if ( isset( $spec['cacheTime'] ) ) {
			$po->setCacheTime( $spec['cacheTime'] );
		}
		if ( isset( $spec['revisionTimestamp'] ) ) {
			$po->setRevisionTimestamp( $spec['revisionTimestamp'] );
		}
		if ( isset( $spec['speculativeRevIdUsed'] ) ) {
			$po->setSpeculativeRevIdUsed( $spec['speculativeRevIdUsed'] );
		}
		return $po;
	}

	/**
	 * @covers \MediaWiki\Parser\ParserOutput::mergeInternalMetaDataFrom
	 * @covers \MediaWiki\Parser\ParserOutput::getTimes
	 * @covers \MediaWiki\Parser\ParserOutput::resetParseStartTime
	 */
	public function testMergeInternalMetaDataFrom_parseStartTime() {
		/** @var object $a */
		$a = new ParserOutput();
		$a = TestingAccessWrapper::newFromObject( $a );

		$a->resetParseStartTime();
		$aClocks = $a->mParseStartTime;

		$b = new ParserOutput();

		$a->mergeInternalMetaDataFrom( $b );
		$mergedClocks = $a->mParseStartTime;

		foreach ( $mergedClocks as $clock => $timestamp ) {
			$this->assertSame( $aClocks[$clock], $timestamp, $clock );
		}

		// try again, with times in $b also set, and later than $a's
		usleep( 1234 );

		/** @var object $b */
		$b = new ParserOutput();
		$b = TestingAccessWrapper::newFromObject( $b );

		$b->resetParseStartTime();

		$bClocks = $b->mParseStartTime;

		$a->mergeInternalMetaDataFrom( $b->object );
		$mergedClocks = $a->mParseStartTime;

		foreach ( $mergedClocks as $clock => $timestamp ) {
			$this->assertSame( $aClocks[$clock], $timestamp, $clock );
			$this->assertLessThanOrEqual( $bClocks[$clock], $timestamp, $clock );
		}

		// try again, with $a's times being later
		usleep( 1234 );
		$a->resetParseStartTime();
		$aClocks = $a->mParseStartTime;

		$a->mergeInternalMetaDataFrom( $b->object );
		$mergedClocks = $a->mParseStartTime;

		foreach ( $mergedClocks as $clock => $timestamp ) {
			$this->assertSame( $bClocks[$clock], $timestamp, $clock );
			$this->assertLessThanOrEqual( $aClocks[$clock], $timestamp, $clock );
		}

		// try again, with no times in $a set
		$a = new ParserOutput();
		$a = TestingAccessWrapper::newFromObject( $a );

		$a->mergeInternalMetaDataFrom( $b->object );
		$mergedClocks = $a->mParseStartTime;

		foreach ( $mergedClocks as $clock => $timestamp ) {
			$this->assertSame( $bClocks[$clock], $timestamp, $clock );
		}
	}

	/**
	 * @covers \MediaWiki\Parser\ParserOutput::mergeInternalMetaDataFrom
	 * @covers \MediaWiki\Parser\ParserOutput::getTimes
	 * @covers \MediaWiki\Parser\ParserOutput::resetParseStartTime
	 * @covers \MediaWiki\Parser\ParserOutput::recordTimeProfile
	 * @covers \MediaWiki\Parser\ParserOutput::getTimeProfile
	 */
	public function testMergeInternalMetaDataFrom_timeProfile() {
		/** @var object $a */
		$a = new ParserOutput();
		$a = TestingAccessWrapper::newFromObject( $a );

		$a->resetParseStartTime();
		usleep( 1234 );
		$a->recordTimeProfile();

		$aClocks = $a->mTimeProfile;

		// make sure a second call to recordTimeProfile has no effect
		usleep( 1234 );
		$a->recordTimeProfile();

		foreach ( $aClocks as $clock => $duration ) {
			$this->assertNotNull( $duration );
			$this->assertGreaterThan( 0, $duration );
			$this->assertSame( $aClocks[$clock], $a->getTimeProfile( $clock ) );
		}

		$b = new ParserOutput();

		$a->mergeInternalMetaDataFrom( $b );
		$mergedClocks = $a->mTimeProfile;

		foreach ( $mergedClocks as $clock => $duration ) {
			$this->assertSame( $aClocks[$clock], $duration, $clock );
		}

		// try again, with times in $b also set, and later than $a's
		$b->resetParseStartTime();
		usleep( 1234 );
		$b->recordTimeProfile();

		$b = TestingAccessWrapper::newFromObject( $b );
		$bClocks = $b->mTimeProfile;

		$a->mergeInternalMetaDataFrom( $b->object );
		$mergedClocks = $a->mTimeProfile;

		foreach ( $mergedClocks as $clock => $duration ) {
			$this->assertGreaterThanOrEqual( $aClocks[$clock], $duration, $clock );
			$this->assertGreaterThanOrEqual( $bClocks[$clock], $duration, $clock );
		}
	}

	/**
	 * @covers \MediaWiki\Parser\ParserOutput::getCacheTime
	 * @covers \MediaWiki\Parser\ParserOutput::setCacheTime
	 */
	public function testGetCacheTime() {
		$clock = MWTimestamp::convert( TS_UNIX, '20100101000000' );
		MWTimestamp::setFakeTime( static function () use ( &$clock ) {
			return $clock++;
		} );

		$po = new ParserOutput();
		$time = $po->getCacheTime();

		// Use current (fake) time by default. Ignore the last digit.
		// Subsequent calls must yield the exact same timestamp as the first.
		$this->assertStringStartsWith( '2010010100000', $time );
		$this->assertSame( $time, $po->getCacheTime() );

		// After setting, the getter must return the time that was set.
		$time = '20110606112233';
		$po->setCacheTime( $time );
		$this->assertSame( $time, $po->getCacheTime() );
	}

	/**
	 * @covers \MediaWiki\Parser\ParserOutput::addExtraCSPScriptSrc
	 * @covers \MediaWiki\Parser\ParserOutput::addExtraCSPDefaultSrc
	 * @covers \MediaWiki\Parser\ParserOutput::addExtraCSPStyleSrc
	 * @covers \MediaWiki\Parser\ParserOutput::getExtraCSPScriptSrcs
	 * @covers \MediaWiki\Parser\ParserOutput::getExtraCSPDefaultSrcs
	 * @covers \MediaWiki\Parser\ParserOutput::getExtraCSPStyleSrcs
	 */
	public function testCSPSources() {
		$po = new ParserOutput;

		$this->assertEquals( [], $po->getExtraCSPScriptSrcs(), 'empty Script' );
		$this->assertEquals( [], $po->getExtraCSPStyleSrcs(), 'empty Style' );
		$this->assertEquals( [], $po->getExtraCSPDefaultSrcs(), 'empty Default' );

		$po->addExtraCSPScriptSrc( 'foo.com' );
		$po->addExtraCSPScriptSrc( 'bar.com' );
		$po->addExtraCSPDefaultSrc( 'baz.com' );
		$po->addExtraCSPStyleSrc( 'fred.com' );
		$po->addExtraCSPStyleSrc( 'xyzzy.com' );

		$this->assertEquals( [ 'foo.com', 'bar.com' ], $po->getExtraCSPScriptSrcs(), 'Script' );
		$this->assertEquals( [ 'baz.com' ], $po->getExtraCSPDefaultSrcs(), 'Default' );
		$this->assertEquals( [ 'fred.com', 'xyzzy.com' ], $po->getExtraCSPStyleSrcs(), 'Style' );
	}

	public function testOutputStrings() {
		$po = new ParserOutput;

		$this->assertEquals( [], $po->getOutputStrings( ParserOutputStringSets::MODULE ) );
		$this->assertEquals( [], $po->getOutputStrings( ParserOutputStringSets::MODULE_STYLE ) );
		$this->assertEquals( [], $po->getOutputStrings( ParserOutputStringSets::EXTRA_CSP_SCRIPT_SRC ) );
		$this->assertEquals( [], $po->getOutputStrings( ParserOutputStringSets::EXTRA_CSP_STYLE_SRC ) );
		$this->assertEquals( [], $po->getOutputStrings( ParserOutputStringSets::EXTRA_CSP_DEFAULT_SRC ) );

		$this->assertEquals( [], $po->getModules() );
		$this->assertEquals( [], $po->getModuleStyles() );
		$this->assertEquals( [], $po->getExtraCSPScriptSrcs() );
		$this->assertEquals( [], $po->getExtraCSPStyleSrcs() );
		$this->assertEquals( [], $po->getExtraCSPDefaultSrcs() );

		$po->appendOutputStrings( ParserOutputStringSets::MODULE, [ 'a' ] );
		$po->appendOutputStrings( ParserOutputStringSets::MODULE_STYLE, [ 'b' ] );
		$po->appendOutputStrings( ParserOutputStringSets::EXTRA_CSP_SCRIPT_SRC, [ 'foo.com', 'bar.com' ] );
		$po->appendOutputStrings( ParserOutputStringSets::EXTRA_CSP_DEFAULT_SRC, [ 'baz.com' ] );
		$po->appendOutputStrings( ParserOutputStringSets::EXTRA_CSP_STYLE_SRC, [ 'fred.com' ] );
		$po->appendOutputStrings( ParserOutputStringSets::EXTRA_CSP_STYLE_SRC, [ 'xyzzy.com' ] );

		$this->assertEquals( [ 'a' ], $po->getOutputStrings( ParserOutputStringSets::MODULE ) );
		$this->assertEquals( [ 'b' ], $po->getOutputStrings( ParserOutputStringSets::MODULE_STYLE ) );
		$this->assertEquals( [ 'foo.com', 'bar.com' ],
							 $po->getOutputStrings( ParserOutputStringSets::EXTRA_CSP_SCRIPT_SRC ) );
		$this->assertEquals( [ 'baz.com' ],
							 $po->getOutputStrings( ParserOutputStringSets::EXTRA_CSP_DEFAULT_SRC ) );
		$this->assertEquals( [ 'fred.com', 'xyzzy.com' ],
							 $po->getOutputStrings( ParserOutputStringSets::EXTRA_CSP_STYLE_SRC ) );

		$this->assertEquals( [ 'a' ], $po->getModules() );
		$this->assertEquals( [ 'b' ], $po->getModuleStyles() );
		$this->assertEquals( [ 'foo.com', 'bar.com' ], $po->getExtraCSPScriptSrcs() );
		$this->assertEquals( [ 'baz.com' ], $po->getExtraCSPDefaultSrcs() );
		$this->assertEquals( [ 'fred.com', 'xyzzy.com' ], $po->getExtraCSPStyleSrcs() );
	}

	/**
	 * @covers \MediaWiki\Parser\ParserOutput::getCacheTime()
	 * @covers \MediaWiki\Parser\ParserOutput::setCacheTime()
	 */
	public function testCacheTime() {
		$po = new ParserOutput();

		// Should not have a cache time yet
		$this->assertFalse( $po->hasCacheTime() );
		// But calling ::get assigns a cache time
		$po->getCacheTime();
		$this->assertTrue( $po->hasCacheTime() );
		$this->assertTrue( $po->isCacheable() );
		// Reset cache time
		$po->setCacheTime( "20240207202040" );
		$this->assertSame( "20240207202040", $po->getCacheTime() );
	}

	/**
	 * @covers \MediaWiki\Parser\ParserOutput::isCacheable()
	 * @covers \MediaWiki\Parser\ParserOutput::getCacheExpiry()
	 * @covers \MediaWiki\Parser\ParserOutput::hasReducedExpiry()
	 */
	public function testAsyncNotReady() {
		$defaultExpiry = ParserCacheSerializationTestCases::FAKE_CACHE_EXPIRY;
		$asyncExpiry = ParserCacheSerializationTestCases::FAKE_ASYNC_CACHE_EXPIRY;
		// $asyncExpiry has to be smaller than the default for these tests to
		// work properly.
		$this->assertTrue( $asyncExpiry < $defaultExpiry );

		$po = new ParserOutput();
		$po->getCacheTime(); // assign a cache time
		$this->assertTrue( $po->isCacheable() );
		$this->assertFalse( $po->hasReducedExpiry() );

		// hasReducedExpiry is set if there is/was any async content
		$po->setOutputFlag( ParserOutputFlags::HAS_ASYNC_CONTENT );
		$this->assertTrue( $po->isCacheable() );
		$this->assertTrue( $po->hasReducedExpiry() );
		$this->assertTrue( $po->getCacheExpiry() === $defaultExpiry );

		// Setting ASYNC_NOT_READY also shortens the cache expiry
		$po->setOutputFlag( ParserOutputFlags::ASYNC_NOT_READY );
		$this->assertTrue( $po->isCacheable() );
		$this->assertTrue( $po->hasReducedExpiry() );
		$this->assertTrue( $po->getCacheExpiry() === $asyncExpiry );

		$po->updateCacheExpiry( $defaultExpiry - 1 );
		$this->assertTrue( $po->isCacheable() );
		$this->assertTrue( $po->hasReducedExpiry() );
		$this->assertTrue( $po->getCacheExpiry() === $asyncExpiry );
	}

	/**
	 * @covers \MediaWiki\Parser\ParserOutput::getRenderId()
	 * @covers \MediaWiki\Parser\ParserOutput::setRenderId()
	 */
	public function testRenderId() {
		$po = new ParserOutput();

		// Should be null when unset
		$this->assertNull( $po->getRenderId() );

		// Sanity check for setter and getter
		$po->setRenderId( "TestRenderId" );
		$this->assertEquals( "TestRenderId", $po->getRenderId() );
	}

	/**
	 * @covers \MediaWiki\Parser\ParserOutput::getRenderId()
	 */
	public function testRenderIdBackCompat() {
		$po = new ParserOutput();

		// Parser cache used to contain extension data under a different name
		$po->setExtensionData( 'parsoid-render-id', "1234/LegacyRenderId" );
		$this->assertEquals( "LegacyRenderId", $po->getRenderId() );
	}

	public function testSetFromParserOptions() {
		// parser output set from canonical parser options
		$pOptions = ParserOptions::newFromAnon();
		$pOutput = new ParserOutput;
		$pOutput->setFromParserOptions( $pOptions );
		$this->assertSame( 'mw-parser-output', $pOutput->getWrapperDivClass() );
		$this->assertFalse( $pOutput->getOutputFlag( ParserOutputFlags::IS_PREVIEW ) );
		$this->assertTrue( $pOutput->isCacheable() );
		$this->assertFalse( $pOutput->getOutputFlag( ParserOutputFlags::NO_SECTION_EDIT_LINKS ) );
		$this->assertFalse( $pOutput->getOutputFlag( ParserOutputFlags::COLLAPSIBLE_SECTIONS ) );

		// set the various parser options and verify in parser output
		$pOptions->setWrapOutputClass( 'test-wrapper' );
		$pOptions->setIsPreview( true );
		$pOptions->setSuppressSectionEditLinks();
		$pOptions->setCollapsibleSections();
		$pOutput = new ParserOutput;
		$pOutput->setFromParserOptions( $pOptions );
		$this->assertEquals( 'test-wrapper', $pOutput->getWrapperDivClass() );
		$this->assertTrue( $pOutput->getOutputFlag( ParserOutputFlags::IS_PREVIEW ) );
		$this->assertFalse( $pOutput->isCacheable() );
		$this->assertTrue( $pOutput->getOutputFlag( ParserOutputFlags::NO_SECTION_EDIT_LINKS ) );
		$this->assertTrue( $pOutput->getOutputFlag( ParserOutputFlags::COLLAPSIBLE_SECTIONS ) );
	}
}
