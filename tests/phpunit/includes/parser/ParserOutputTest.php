<?php

use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\Tests\Parser\ParserCacheSerializationTestCases;
use Wikimedia\TestingAccessWrapper;
use Wikimedia\Tests\SerializationTestTrait;

/**
 * @covers ParserOutput
 * @covers CacheTime
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
	}

	/**
	 * Overrides SerializationTestTrait::getClassToTest
	 * @return string
	 */
	protected function getClassToTest(): string {
		return ParserOutput::class;
	}

	/**
	 * Overrides SerializationTestTrait::getSerializedDataPath
	 * @return string
	 */
	protected function getSerializedDataPath(): string {
		return __DIR__ . '/../../data/ParserCache';
	}

	/**
	 * Overrides SerializationTestTrait::getTestInstancesAndAssertions
	 * @return array
	 */
	protected function getTestInstancesAndAssertions(): array {
		return ParserCacheSerializationTestCases::getParserOutputTestCases();
	}

	/**
	 * Overrides SerializationTestTrait::getSupportedSerializationFormats
	 * @return array
	 */
	protected function getSupportedSerializationFormats(): array {
		return ParserCacheSerializationTestCases::getSupportedSerializationFormats(
			$this->getClassToTest() );
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
	 * @covers ParserOutput::isLinkInternal
	 */
	public function testIsLinkInternal( $shouldMatch, $server, $url ) {
		$this->assertEquals( $shouldMatch, ParserOutput::isLinkInternal( $server, $url ) );
	}

	/**
	 * @covers ParserOutput::appendJsConfigVar
	 * @covers ParserOutput::setJsConfigVar
	 * @covers ParserOutput::getJsConfigVars
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
	 * @covers ParserOutput::appendExtensionData
	 * @covers ParserOutput::setExtensionData
	 * @covers ParserOutput::getExtensionData
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
	 * @covers ParserOutput::setPageProperty
	 * @covers ParserOutput::getPageProperty
	 * @covers ParserOutput::unsetPageProperty
	 * @covers ParserOutput::getPageProperties
	 */
	public function testProperties() {
		$po = new ParserOutput();

		$po->setPageProperty( 'foo', 'val' );

		$properties = $po->getPageProperties();
		$this->assertSame( 'val', $po->getPageProperty( 'foo' ) );
		$this->assertSame( 'val', $properties['foo'] );

		$po->setPageProperty( 'foo', 'second val' );

		$properties = $po->getPageProperties();
		$this->assertSame( 'second val', $po->getPageProperty( 'foo' ) );
		$this->assertSame( 'second val', $properties['foo'] );

		$po->unsetPageProperty( 'foo' );

		$properties = $po->getPageProperties();
		$this->assertSame( null, $po->getPageProperty( 'foo' ) );
		$this->assertArrayNotHasKey( 'foo', $properties );
	}

	/**
	 * @covers ParserOutput::getWrapperDivClass
	 * @covers ParserOutput::addWrapperDivClass
	 * @covers ParserOutput::clearWrapperDivClass
	 * @covers ParserOutput::getText
	 */
	public function testWrapperDivClass() {
		$po = new ParserOutput();

		$po->setText( 'Kittens' );
		$this->assertStringContainsString( 'Kittens', $po->getText() );
		$this->assertStringNotContainsString( '<div', $po->getText() );
		$this->assertSame( 'Kittens', $po->getRawText() );

		$po->addWrapperDivClass( 'foo' );
		$text = $po->getText();
		$this->assertStringContainsString( 'Kittens', $text );
		$this->assertStringContainsString( '<div', $text );
		$this->assertStringContainsString( 'class="foo"', $text );

		$po->addWrapperDivClass( 'bar' );
		$text = $po->getText();
		$this->assertStringContainsString( 'Kittens', $text );
		$this->assertStringContainsString( '<div', $text );
		$this->assertStringContainsString( 'class="foo bar"', $text );

		$po->addWrapperDivClass( 'bar' ); // second time does nothing, no "foo bar bar".
		$text = $po->getText( [ 'unwrap' => true ] );
		$this->assertStringContainsString( 'Kittens', $text );
		$this->assertStringNotContainsString( '<div', $text );
		$this->assertStringNotContainsString( 'class="foo bar"', $text );

		$text = $po->getText( [ 'wrapperDivClass' => '' ] );
		$this->assertStringContainsString( 'Kittens', $text );
		$this->assertStringNotContainsString( '<div', $text );
		$this->assertStringNotContainsString( 'class="foo bar"', $text );

		$text = $po->getText( [ 'wrapperDivClass' => 'xyzzy' ] );
		$this->assertStringContainsString( 'Kittens', $text );
		$this->assertStringContainsString( '<div', $text );
		$this->assertStringContainsString( 'class="xyzzy"', $text );
		$this->assertStringNotContainsString( 'class="foo bar"', $text );

		$text = $po->getRawText();
		$this->assertSame( 'Kittens', $text );

		$po->clearWrapperDivClass();
		$text = $po->getText();
		$this->assertStringContainsString( 'Kittens', $text );
		$this->assertStringNotContainsString( '<div', $text );
		$this->assertStringNotContainsString( 'class="foo bar"', $text );
	}

	/**
	 * @covers ParserOutput::getText
	 * @dataProvider provideGetText
	 * @dataProvider provideGetTextBackCompat
	 * @dataProvider provideGetTextForwardCompat
	 * @param array $options Options to getText()
	 * @param string $text Parser text
	 * @param string $expect Expected output
	 */
	public function testGetText( $options, $text, $expect ) {
		$this->overrideConfigValues( [
			MainConfigNames::ArticlePath => '/wiki/$1',
			MainConfigNames::ScriptPath => '/w',
			MainConfigNames::Script => '/w/index.php',
		] );

		$po = new ParserOutput( $text );
		$po->setTOCHTML( self::provideGetTextToC() );
		$actual = $po->getText( $options );
		$this->assertSame( $expect, $actual );
	}

	public static function provideGetTextToC() {
		$toc = <<<EOF
<div id="toc" class="toc"><div class="toctitle"><h2>Contents</h2></div>
<ul>
<li class="toclevel-1 tocsection-1"><a href="#Section_1"><span class="tocnumber">1</span> <span class="toctext">Section 1</span></a></li>
<li class="toclevel-1 tocsection-2"><a href="#Section_2"><span class="tocnumber">2</span> <span class="toctext">Section 2</span></a>
<ul>
<li class="toclevel-2 tocsection-3"><a href="#Section_2.1"><span class="tocnumber">2.1</span> <span class="toctext">Section 2.1</span></a></li>
</ul>
</li>
<li class="toclevel-1 tocsection-4"><a href="#Section_3"><span class="tocnumber">3</span> <span class="toctext">Section 3</span></a></li>
</ul>
</div>

EOF;
		return $toc;
	}

	// REMOVE THIS ONCE Parser::TOC_START IS REMOVED
	public static function provideGetTextBackCompat() {
		$toc = self::provideGetTextToc();
		$text = <<<EOF
<p>Test document.
</p>
<mw:toc>$toc</mw:toc>
<h2><span class="mw-headline" id="Section_1">Section 1</span><mw:editsection page="Test Page" section="1">Section 1</mw:editsection></h2>
<p>One
</p>
<h2><span class="mw-headline" id="Section_2">Section 2</span><mw:editsection page="Test Page" section="2">Section 2</mw:editsection></h2>
<p>Two
</p>
<h3><span class="mw-headline" id="Section_2.1">Section 2.1</span><mw:editsection page="Talk:User:Bug_T261347" section="3">Section 2.1</mw:editsection></h3>
<p>Two point one
</p>
<h2><span class="mw-headline" id="Section_3">Section 3</span><mw:editsection page="Test Page" section="4">Section 3</mw:editsection></h2>
<p>Three
</p>
EOF;

		return [
			'No options (mw:toc)' => [
				[], $text, <<<EOF
<p>Test document.
</p>
<div id="toc" class="toc"><div class="toctitle"><h2>Contents</h2></div>
<ul>
<li class="toclevel-1 tocsection-1"><a href="#Section_1"><span class="tocnumber">1</span> <span class="toctext">Section 1</span></a></li>
<li class="toclevel-1 tocsection-2"><a href="#Section_2"><span class="tocnumber">2</span> <span class="toctext">Section 2</span></a>
<ul>
<li class="toclevel-2 tocsection-3"><a href="#Section_2.1"><span class="tocnumber">2.1</span> <span class="toctext">Section 2.1</span></a></li>
</ul>
</li>
<li class="toclevel-1 tocsection-4"><a href="#Section_3"><span class="tocnumber">3</span> <span class="toctext">Section 3</span></a></li>
</ul>
</div>

<h2><span class="mw-headline" id="Section_1">Section 1</span><span class="mw-editsection"><span class="mw-editsection-bracket">[</span><a href="/w/index.php?title=Test_Page&amp;action=edit&amp;section=1" title="Edit section: Section 1">edit</a><span class="mw-editsection-bracket">]</span></span></h2>
<p>One
</p>
<h2><span class="mw-headline" id="Section_2">Section 2</span><span class="mw-editsection"><span class="mw-editsection-bracket">[</span><a href="/w/index.php?title=Test_Page&amp;action=edit&amp;section=2" title="Edit section: Section 2">edit</a><span class="mw-editsection-bracket">]</span></span></h2>
<p>Two
</p>
<h3><span class="mw-headline" id="Section_2.1">Section 2.1</span></h3>
<p>Two point one
</p>
<h2><span class="mw-headline" id="Section_3">Section 3</span><span class="mw-editsection"><span class="mw-editsection-bracket">[</span><a href="/w/index.php?title=Test_Page&amp;action=edit&amp;section=4" title="Edit section: Section 3">edit</a><span class="mw-editsection-bracket">]</span></span></h2>
<p>Three
</p>
EOF
			],
			'Disable section edit links (mw:toc)' => [
				[ 'enableSectionEditLinks' => false ], $text, <<<EOF
<p>Test document.
</p>
<div id="toc" class="toc"><div class="toctitle"><h2>Contents</h2></div>
<ul>
<li class="toclevel-1 tocsection-1"><a href="#Section_1"><span class="tocnumber">1</span> <span class="toctext">Section 1</span></a></li>
<li class="toclevel-1 tocsection-2"><a href="#Section_2"><span class="tocnumber">2</span> <span class="toctext">Section 2</span></a>
<ul>
<li class="toclevel-2 tocsection-3"><a href="#Section_2.1"><span class="tocnumber">2.1</span> <span class="toctext">Section 2.1</span></a></li>
</ul>
</li>
<li class="toclevel-1 tocsection-4"><a href="#Section_3"><span class="tocnumber">3</span> <span class="toctext">Section 3</span></a></li>
</ul>
</div>

<h2><span class="mw-headline" id="Section_1">Section 1</span></h2>
<p>One
</p>
<h2><span class="mw-headline" id="Section_2">Section 2</span></h2>
<p>Two
</p>
<h3><span class="mw-headline" id="Section_2.1">Section 2.1</span></h3>
<p>Two point one
</p>
<h2><span class="mw-headline" id="Section_3">Section 3</span></h2>
<p>Three
</p>
EOF
			],
			'Disable TOC, but wrap (mw:toc)' => [
				[ 'allowTOC' => false, 'wrapperDivClass' => 'mw-parser-output' ], $text, <<<EOF
<div class="mw-parser-output"><p>Test document.
</p>

<h2><span class="mw-headline" id="Section_1">Section 1</span><span class="mw-editsection"><span class="mw-editsection-bracket">[</span><a href="/w/index.php?title=Test_Page&amp;action=edit&amp;section=1" title="Edit section: Section 1">edit</a><span class="mw-editsection-bracket">]</span></span></h2>
<p>One
</p>
<h2><span class="mw-headline" id="Section_2">Section 2</span><span class="mw-editsection"><span class="mw-editsection-bracket">[</span><a href="/w/index.php?title=Test_Page&amp;action=edit&amp;section=2" title="Edit section: Section 2">edit</a><span class="mw-editsection-bracket">]</span></span></h2>
<p>Two
</p>
<h3><span class="mw-headline" id="Section_2.1">Section 2.1</span></h3>
<p>Two point one
</p>
<h2><span class="mw-headline" id="Section_3">Section 3</span><span class="mw-editsection"><span class="mw-editsection-bracket">[</span><a href="/w/index.php?title=Test_Page&amp;action=edit&amp;section=4" title="Edit section: Section 3">edit</a><span class="mw-editsection-bracket">]</span></span></h2>
<p>Three
</p></div>
EOF
			],
		];
		// phpcs:enable
	}

		// Remove this once we've transitioned the cache to the new
		// TOC_PLACEHOLDER
	public static function provideGetTextForwardCompat() {
		// phpcs:disable Generic.Files.LineLength
		$toc = self::provideGetTextToc();
		$text = <<<EOF
<p>Test document.
</p>
<meta property="mw:PageProp/toc" />
<h2><span class="mw-headline" id="Section_1">Section 1</span><mw:editsection page="Test Page" section="1">Section 1</mw:editsection></h2>
<p>One
</p>
<h2><span class="mw-headline" id="Section_2">Section 2</span><mw:editsection page="Test Page" section="2">Section 2</mw:editsection></h2>
<p>Two
</p>
<h3><span class="mw-headline" id="Section_2.1">Section 2.1</span><mw:editsection page="Talk:User:Bug_T261347" section="3">Section 2.1</mw:editsection></h3>
<p>Two point one
</p>
<h2><span class="mw-headline" id="Section_3">Section 3</span><mw:editsection page="Test Page" section="4">Section 3</mw:editsection></h2>
<p>Three
</p>
EOF;

		return [
			'No options (meta tag)' => [
				[], $text, <<<EOF
<p>Test document.
</p>
<div id="toc" class="toc"><div class="toctitle"><h2>Contents</h2></div>
<ul>
<li class="toclevel-1 tocsection-1"><a href="#Section_1"><span class="tocnumber">1</span> <span class="toctext">Section 1</span></a></li>
<li class="toclevel-1 tocsection-2"><a href="#Section_2"><span class="tocnumber">2</span> <span class="toctext">Section 2</span></a>
<ul>
<li class="toclevel-2 tocsection-3"><a href="#Section_2.1"><span class="tocnumber">2.1</span> <span class="toctext">Section 2.1</span></a></li>
</ul>
</li>
<li class="toclevel-1 tocsection-4"><a href="#Section_3"><span class="tocnumber">3</span> <span class="toctext">Section 3</span></a></li>
</ul>
</div>

<h2><span class="mw-headline" id="Section_1">Section 1</span><span class="mw-editsection"><span class="mw-editsection-bracket">[</span><a href="/w/index.php?title=Test_Page&amp;action=edit&amp;section=1" title="Edit section: Section 1">edit</a><span class="mw-editsection-bracket">]</span></span></h2>
<p>One
</p>
<h2><span class="mw-headline" id="Section_2">Section 2</span><span class="mw-editsection"><span class="mw-editsection-bracket">[</span><a href="/w/index.php?title=Test_Page&amp;action=edit&amp;section=2" title="Edit section: Section 2">edit</a><span class="mw-editsection-bracket">]</span></span></h2>
<p>Two
</p>
<h3><span class="mw-headline" id="Section_2.1">Section 2.1</span></h3>
<p>Two point one
</p>
<h2><span class="mw-headline" id="Section_3">Section 3</span><span class="mw-editsection"><span class="mw-editsection-bracket">[</span><a href="/w/index.php?title=Test_Page&amp;action=edit&amp;section=4" title="Edit section: Section 3">edit</a><span class="mw-editsection-bracket">]</span></span></h2>
<p>Three
</p>
EOF
			],
			'Disable section edit links (meta tag)' => [
				[ 'enableSectionEditLinks' => false ], $text, <<<EOF
<p>Test document.
</p>
<div id="toc" class="toc"><div class="toctitle"><h2>Contents</h2></div>
<ul>
<li class="toclevel-1 tocsection-1"><a href="#Section_1"><span class="tocnumber">1</span> <span class="toctext">Section 1</span></a></li>
<li class="toclevel-1 tocsection-2"><a href="#Section_2"><span class="tocnumber">2</span> <span class="toctext">Section 2</span></a>
<ul>
<li class="toclevel-2 tocsection-3"><a href="#Section_2.1"><span class="tocnumber">2.1</span> <span class="toctext">Section 2.1</span></a></li>
</ul>
</li>
<li class="toclevel-1 tocsection-4"><a href="#Section_3"><span class="tocnumber">3</span> <span class="toctext">Section 3</span></a></li>
</ul>
</div>

<h2><span class="mw-headline" id="Section_1">Section 1</span></h2>
<p>One
</p>
<h2><span class="mw-headline" id="Section_2">Section 2</span></h2>
<p>Two
</p>
<h3><span class="mw-headline" id="Section_2.1">Section 2.1</span></h3>
<p>Two point one
</p>
<h2><span class="mw-headline" id="Section_3">Section 3</span></h2>
<p>Three
</p>
EOF
			],
			'Disable TOC, but wrap (meta tag)' => [
				[ 'allowTOC' => false, 'wrapperDivClass' => 'mw-parser-output' ], $text, <<<EOF
<div class="mw-parser-output"><p>Test document.
</p>

<h2><span class="mw-headline" id="Section_1">Section 1</span><span class="mw-editsection"><span class="mw-editsection-bracket">[</span><a href="/w/index.php?title=Test_Page&amp;action=edit&amp;section=1" title="Edit section: Section 1">edit</a><span class="mw-editsection-bracket">]</span></span></h2>
<p>One
</p>
<h2><span class="mw-headline" id="Section_2">Section 2</span><span class="mw-editsection"><span class="mw-editsection-bracket">[</span><a href="/w/index.php?title=Test_Page&amp;action=edit&amp;section=2" title="Edit section: Section 2">edit</a><span class="mw-editsection-bracket">]</span></span></h2>
<p>Two
</p>
<h3><span class="mw-headline" id="Section_2.1">Section 2.1</span></h3>
<p>Two point one
</p>
<h2><span class="mw-headline" id="Section_3">Section 3</span><span class="mw-editsection"><span class="mw-editsection-bracket">[</span><a href="/w/index.php?title=Test_Page&amp;action=edit&amp;section=4" title="Edit section: Section 3">edit</a><span class="mw-editsection-bracket">]</span></span></h2>
<p>Three
</p></div>
EOF
			],
		];
		// phpcs:enable
	}

	public static function provideGetText() {
		$toc = self::provideGetTextToc();
		$text = <<<EOF
<p>Test document.
</p>
<mw:tocplace></mw:tocplace>
<h2><span class="mw-headline" id="Section_1">Section 1</span><mw:editsection page="Test Page" section="1">Section 1</mw:editsection></h2>
<p>One
</p>
<h2><span class="mw-headline" id="Section_2">Section 2</span><mw:editsection page="Test Page" section="2">Section 2</mw:editsection></h2>
<p>Two
</p>
<h3><span class="mw-headline" id="Section_2.1">Section 2.1</span><mw:editsection page="Talk:User:Bug_T261347" section="3">Section 2.1</mw:editsection></h3>
<p>Two point one
</p>
<h2><span class="mw-headline" id="Section_3">Section 3</span><mw:editsection page="Test Page" section="4">Section 3</mw:editsection></h2>
<p>Three
</p>
EOF;

		$dedupText = <<<EOF
<p>This is a test document.</p>
<style data-mw-deduplicate="duplicate1">.Duplicate1 {}</style>
<style data-mw-deduplicate="duplicate1">.Duplicate1 {}</style>
<style data-mw-deduplicate="duplicate2">.Duplicate2 {}</style>
<style data-mw-deduplicate="duplicate1">.Duplicate1 {}</style>
<style data-mw-deduplicate="duplicate2">.Duplicate2 {}</style>
<style data-mw-not-deduplicate="duplicate1">.Duplicate1 {}</style>
<style data-mw-deduplicate="duplicate1">.Same-attribute-different-content {}</style>
<style data-mw-deduplicate="duplicate3">.Duplicate1 {}</style>
<style>.Duplicate1 {}</style>
EOF;

		return [
			'No options' => [
				[], $text, <<<EOF
<p>Test document.
</p>
<div id="toc" class="toc"><div class="toctitle"><h2>Contents</h2></div>
<ul>
<li class="toclevel-1 tocsection-1"><a href="#Section_1"><span class="tocnumber">1</span> <span class="toctext">Section 1</span></a></li>
<li class="toclevel-1 tocsection-2"><a href="#Section_2"><span class="tocnumber">2</span> <span class="toctext">Section 2</span></a>
<ul>
<li class="toclevel-2 tocsection-3"><a href="#Section_2.1"><span class="tocnumber">2.1</span> <span class="toctext">Section 2.1</span></a></li>
</ul>
</li>
<li class="toclevel-1 tocsection-4"><a href="#Section_3"><span class="tocnumber">3</span> <span class="toctext">Section 3</span></a></li>
</ul>
</div>

<h2><span class="mw-headline" id="Section_1">Section 1</span><span class="mw-editsection"><span class="mw-editsection-bracket">[</span><a href="/w/index.php?title=Test_Page&amp;action=edit&amp;section=1" title="Edit section: Section 1">edit</a><span class="mw-editsection-bracket">]</span></span></h2>
<p>One
</p>
<h2><span class="mw-headline" id="Section_2">Section 2</span><span class="mw-editsection"><span class="mw-editsection-bracket">[</span><a href="/w/index.php?title=Test_Page&amp;action=edit&amp;section=2" title="Edit section: Section 2">edit</a><span class="mw-editsection-bracket">]</span></span></h2>
<p>Two
</p>
<h3><span class="mw-headline" id="Section_2.1">Section 2.1</span></h3>
<p>Two point one
</p>
<h2><span class="mw-headline" id="Section_3">Section 3</span><span class="mw-editsection"><span class="mw-editsection-bracket">[</span><a href="/w/index.php?title=Test_Page&amp;action=edit&amp;section=4" title="Edit section: Section 3">edit</a><span class="mw-editsection-bracket">]</span></span></h2>
<p>Three
</p>
EOF
			],
			'Disable section edit links' => [
				[ 'enableSectionEditLinks' => false ], $text, <<<EOF
<p>Test document.
</p>
<div id="toc" class="toc"><div class="toctitle"><h2>Contents</h2></div>
<ul>
<li class="toclevel-1 tocsection-1"><a href="#Section_1"><span class="tocnumber">1</span> <span class="toctext">Section 1</span></a></li>
<li class="toclevel-1 tocsection-2"><a href="#Section_2"><span class="tocnumber">2</span> <span class="toctext">Section 2</span></a>
<ul>
<li class="toclevel-2 tocsection-3"><a href="#Section_2.1"><span class="tocnumber">2.1</span> <span class="toctext">Section 2.1</span></a></li>
</ul>
</li>
<li class="toclevel-1 tocsection-4"><a href="#Section_3"><span class="tocnumber">3</span> <span class="toctext">Section 3</span></a></li>
</ul>
</div>

<h2><span class="mw-headline" id="Section_1">Section 1</span></h2>
<p>One
</p>
<h2><span class="mw-headline" id="Section_2">Section 2</span></h2>
<p>Two
</p>
<h3><span class="mw-headline" id="Section_2.1">Section 2.1</span></h3>
<p>Two point one
</p>
<h2><span class="mw-headline" id="Section_3">Section 3</span></h2>
<p>Three
</p>
EOF
			],
			'Disable TOC, but wrap' => [
				[ 'allowTOC' => false, 'wrapperDivClass' => 'mw-parser-output' ], $text, <<<EOF
<div class="mw-parser-output"><p>Test document.
</p>

<h2><span class="mw-headline" id="Section_1">Section 1</span><span class="mw-editsection"><span class="mw-editsection-bracket">[</span><a href="/w/index.php?title=Test_Page&amp;action=edit&amp;section=1" title="Edit section: Section 1">edit</a><span class="mw-editsection-bracket">]</span></span></h2>
<p>One
</p>
<h2><span class="mw-headline" id="Section_2">Section 2</span><span class="mw-editsection"><span class="mw-editsection-bracket">[</span><a href="/w/index.php?title=Test_Page&amp;action=edit&amp;section=2" title="Edit section: Section 2">edit</a><span class="mw-editsection-bracket">]</span></span></h2>
<p>Two
</p>
<h3><span class="mw-headline" id="Section_2.1">Section 2.1</span></h3>
<p>Two point one
</p>
<h2><span class="mw-headline" id="Section_3">Section 3</span><span class="mw-editsection"><span class="mw-editsection-bracket">[</span><a href="/w/index.php?title=Test_Page&amp;action=edit&amp;section=4" title="Edit section: Section 3">edit</a><span class="mw-editsection-bracket">]</span></span></h2>
<p>Three
</p></div>
EOF
			],
			'Style deduplication' => [
				[], $dedupText, <<<EOF
<p>This is a test document.</p>
<style data-mw-deduplicate="duplicate1">.Duplicate1 {}</style>
<link rel="mw-deduplicated-inline-style" href="mw-data:duplicate1"/>
<style data-mw-deduplicate="duplicate2">.Duplicate2 {}</style>
<link rel="mw-deduplicated-inline-style" href="mw-data:duplicate1"/>
<link rel="mw-deduplicated-inline-style" href="mw-data:duplicate2"/>
<style data-mw-not-deduplicate="duplicate1">.Duplicate1 {}</style>
<link rel="mw-deduplicated-inline-style" href="mw-data:duplicate1"/>
<style data-mw-deduplicate="duplicate3">.Duplicate1 {}</style>
<style>.Duplicate1 {}</style>
EOF
			],
			'Style deduplication disabled' => [
				[ 'deduplicateStyles' => false ], $dedupText, $dedupText
			],
		];
		// phpcs:enable
	}

	/**
	 * @covers ParserOutput::hasText
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
		$po->setText( '' );
		$this->assertTrue( $po->hasText() );

		$po = new ParserOutput( 'foo' );
		$po->setText( null );
		$this->assertFalse( $po->hasText() );
	}

	/**
	 * @covers ParserOutput::getText
	 */
	public function testGetText_failsIfNoText() {
		$po = new ParserOutput( null );

		$this->expectException( LogicException::class );
		$po->getText();
	}

	public function provideGetText_absoluteURLs() {
		yield 'empty' => [
			'text' => '',
			'expectedText' => '',
		];
		yield 'no-links' => [
			'text' => '<p>test</p>',
			'expectedText' => '<p>test</p>',
		];
		yield 'simple link' => [
			'text' => '<a href="/wiki/Test">test</a>',
			'expectedText' => '<a href="//TEST_SERVER/wiki/Test">test</a>',
		];
		yield 'already absolute, relative' => [
			'text' => '<a href="//TEST_SERVER/wiki/Test">test</a>',
			'expectedText' => '<a href="//TEST_SERVER/wiki/Test">test</a>',
		];
		yield 'already absolute, https' => [
			'text' => '<a href="https://TEST_SERVER/wiki/Test">test</a>',
			'expectedText' => '<a href="https://TEST_SERVER/wiki/Test">test</a>',
		];
		yield 'external' => [
			'text' => '<a href="https://en.wikipedia.org/wiki/Test">test</a>',
			'expectedText' => '<a href="https://en.wikipedia.org/wiki/Test">test</a>',
		];
	}

	/**
	 * @dataProvider provideGetText_absoluteURLs
	 */
	public function testGetText_absoluteURLs( string $text, string $expectedText ) {
		$this->overrideConfigValue( MainConfigNames::Server, '//TEST_SERVER' );
		$parserOutput = new ParserOutput( $text );
		$this->assertSame( $expectedText, $parserOutput->getText( [ 'absoluteURLs' => true ] ) );
	}

	/**
	 * @covers ParserOutput::getRawText
	 */
	public function testGetRawText_failsIfNoText() {
		$po = new ParserOutput( null );

		$this->expectException( LogicException::class );
		$po->getRawText();
	}

	public function provideMergeHtmlMetaDataFrom() {
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
		$a = new ParserOutput();
		$a->setTOCHTML( '<p>TOC A</p>' );
		$a->setSections( [ [ 'fromtitle' => 'A1' ], [ 'fromtitle' => 'A2' ] ] );

		$b = new ParserOutput();
		$b->setTOCHTML( '<p>TOC B</p>' );
		$b->setSections( [ [ 'fromtitle' => 'B1' ], [ 'fromtitle' => 'B2' ] ] );

		yield 'concat TOC' => [ $a, $b, [
			'getTOCHTML' => '<p>TOC A</p><p>TOC B</p>',
			'getSections' => [
				[ 'fromtitle' => 'A1' ],
				[ 'fromtitle' => 'A2' ],
				[ 'fromtitle' => 'B1' ],
				[ 'fromtitle' => 'B2' ]
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
	 * @covers ParserOutput::mergeHtmlMetaDataFrom
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
		unset( $expected['getTOCHTML'] );
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
	 * @covers ParserOutput::addLink
	 * @covers ParserOutput::getLinks
	 */
	public function testAddLink() {
		$a = new ParserOutput();
		$a->addLink( Title::makeTitle( NS_MAIN, 'Kittens' ), 6 );
		$a->addLink( new TitleValue( NS_TALK, 'Kittens' ), 16 );
		$a->addLink( new TitleValue( NS_MAIN, 'Goats_786827346' ) );

		$expected = [
			NS_MAIN => [ 'Kittens' => 6, 'Goats_786827346' => 0 ],
			NS_TALK => [ 'Kittens' => 16 ]
		];
		$this->assertSame( $expected, $a->getLinks() );
	}

	public function provideMergeTrackingMetaDataFrom() {
		// links ------------
		$a = new ParserOutput();
		$a->addLink( Title::makeTitle( NS_MAIN, 'Kittens' ), 6 );
		$a->addLink( new TitleValue( NS_TALK, 'Kittens' ), 16 );
		$a->addLink( new TitleValue( NS_MAIN, 'Goats' ), 7 );

		$a->addTemplate( Title::makeTitle( NS_TEMPLATE, 'Goats' ), 107, 1107 );

		$a->addLanguageLink( 'de' );
		$a->addLanguageLink( 'ru' );
		$a->addInterwikiLink( Title::makeTitle( NS_MAIN, 'Kittens DE', '', 'de' ) );
		$a->addInterwikiLink( new TitleValue( NS_MAIN, 'Kittens RU', '', 'ru' ) );
		$a->addExternalLink( 'https://kittens.wikimedia.test' );
		$a->addExternalLink( 'https://goats.wikimedia.test' );

		$a->addCategory( 'Foo', 'X' );
		$a->addImage( 'Billy.jpg', '20180101000013', 'DEAD' );

		$b = new ParserOutput();
		$b->addLink( Title::makeTitle( NS_MAIN, 'Goats' ), 7 );
		$b->addLink( Title::makeTitle( NS_TALK, 'Goats' ), 17 );
		$b->addLink( new TitleValue( NS_MAIN, 'Dragons' ), 8 );
		$b->addLink( new TitleValue( NS_FILE, 'Dragons.jpg' ), 28 );

		$b->addTemplate( Title::makeTitle( NS_TEMPLATE, 'Dragons' ), 108, 1108 );
		$a->addTemplate( new TitleValue( NS_MAIN, 'Dragons' ), 118, 1118 );

		$b->addLanguageLink( 'fr' );
		$b->addLanguageLink( 'ru' );
		$b->addInterwikiLink( Title::makeTitle( NS_MAIN, 'Kittens FR', '', 'fr' ) );
		$b->addInterwikiLink( new TitleValue( NS_MAIN, 'Dragons RU', '', 'ru' ) );
		$b->addExternalLink( 'https://dragons.wikimedia.test' );
		$b->addExternalLink( 'https://goats.wikimedia.test' );

		$b->addCategory( 'Bar', 'Y' );
		$b->addImage( 'Puff.jpg', '20180101000017', 'BEEF' );

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
			'getLanguageLinks' => [ 'de', 'ru', 'fr' ],
			'getInterwikiLinks' => [
				'de' => [ 'Kittens_DE' => 1 ],
				'ru' => [ 'Kittens_RU' => 1, 'Dragons_RU' => 1, ],
				'fr' => [ 'Kittens_FR' => 1 ],
			],
			'getCategories' => [ 'Foo' => 'X', 'Bar' => 'Y' ],
			'getImages' => [ 'Billy.jpg' => 1, 'Puff.jpg' => 1 ],
			'getFileSearchOptions' => [
				'Billy.jpg' => [ 'time' => '20180101000013', 'sha1' => 'DEAD' ],
				'Puff.jpg' => [ 'time' => '20180101000017', 'sha1' => 'BEEF' ],
			],
			'getExternalLinks' => [
				'https://dragons.wikimedia.test' => 1,
				'https://kittens.wikimedia.test' => 1,
				'https://goats.wikimedia.test' => 1,
			]
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
	 * @covers ParserOutput::mergeTrackingMetaDataFrom
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

	public function provideMergeInternalMetaDataFrom() {
		// hooks
		$a = new ParserOutput();

		$this->hideDeprecated( 'ParserOutput::addOutputHook' );
		$a->addOutputHook( 'foo', 'X' );
		$a->addOutputHook( 'bar' );

		$b = new ParserOutput();

		$b->addOutputHook( 'foo', 'Y' );
		$b->addOutputHook( 'bar' );
		$b->addOutputHook( 'zoo' );

		yield 'hooks' => [ $a, $b, [
			'getOutputHooks' => [
				[ 'foo', 'X' ],
				[ 'bar', false ],
				[ 'foo', 'Y' ],
				[ 'zoo', false ],
			],
		] ];

		// flags & co
		$a = new ParserOutput();

		$a->addWarningMsg( 'duplicate-args-warning', 'A', 'B', 'C' );
		$a->addWarningMsg( 'template-loop-warning', 'D' );

		$a->setFlag( 'foo' );
		$a->setFlag( 'bar' );

		$a->recordOption( 'Foo' );
		$a->recordOption( 'Bar' );

		$b = new ParserOutput();

		$b->addWarningMsg( 'template-equals-warning' );
		$b->addWarningMsg( 'template-loop-warning', 'D' );
		$this->hideDeprecated( 'ParserOutput::addWarning' );
		$b->addWarning( 'Old School' ); // test the deprecated ::addWarning()

		$b->setFlag( 'zoo' );
		$b->setFlag( 'bar' );

		$b->recordOption( 'Zoo' );
		$b->recordOption( 'Bar' );

		yield 'flags' => [ $a, $b, [
			'getWarnings' => [
				wfMessage( 'duplicate-args-warning', 'A', 'B', 'C' )->text(),
				wfMessage( 'template-loop-warning', 'D' )->text(),
				wfMessage( 'template-equals-warning' )->text(),
				'Old School',
			],
			'$mFlags' => [ 'foo' => true, 'bar' => true, 'zoo' => true ],
			'getUsedOptions' => [ 'Foo', 'Bar', 'Zoo' ],
		] ];

		// timestamp ------------
		$a = new ParserOutput();
		$a->setTimestamp( '20180101000011' );
		$b = new ParserOutput();
		yield 'only left timestamp' => [ $a, $b, [ 'getTimestamp' => '20180101000011' ] ];

		$a = new ParserOutput();
		$b = new ParserOutput();
		$b->setTimestamp( '20180101000011' );
		yield 'only right timestamp' => [ $a, $b, [ 'getTimestamp' => '20180101000011' ] ];

		$a = new ParserOutput();
		$a->setTimestamp( '20180101000011' );
		$b = new ParserOutput();
		$b->setTimestamp( '20180101000001' );
		yield 'left timestamp wins' => [ $a, $b, [ 'getTimestamp' => '20180101000011' ] ];

		$a = new ParserOutput();
		$a->setTimestamp( '20180101000001' );
		$b = new ParserOutput();
		$b->setTimestamp( '20180101000011' );
		yield 'right timestamp wins' => [ $a, $b, [ 'getTimestamp' => '20180101000011' ] ];

		// speculative rev id ------------
		$a = new ParserOutput();
		$a->setSpeculativeRevIdUsed( 9 );
		$b = new ParserOutput();
		yield 'only left speculative rev id' => [ $a, $b, [ 'getSpeculativeRevIdUsed' => 9 ] ];

		$a = new ParserOutput();
		$b = new ParserOutput();
		$b->setSpeculativeRevIdUsed( 9 );
		yield 'only right speculative rev id' => [ $a, $b, [ 'getSpeculativeRevIdUsed' => 9 ] ];

		$a = new ParserOutput();
		$a->setSpeculativeRevIdUsed( 9 );
		$b = new ParserOutput();
		$b->setSpeculativeRevIdUsed( 9 );
		yield 'same speculative rev id' => [ $a, $b, [ 'getSpeculativeRevIdUsed' => 9 ] ];

		// limit report (recursive max) ------------
		$a = new ParserOutput();

		$a->setLimitReportData( 'naive1', 7 );
		$a->setLimitReportData( 'naive2', 27 );

		$a->setLimitReportData( 'limitreport-simple1', 7 );
		$a->setLimitReportData( 'limitreport-simple2', 27 );

		$a->setLimitReportData( 'limitreport-pair1', [ 7, 9 ] );
		$a->setLimitReportData( 'limitreport-pair2', [ 27, 29 ] );

		$a->setLimitReportData( 'limitreport-more1', [ 7, 9, 1 ] );
		$a->setLimitReportData( 'limitreport-more2', [ 27, 29, 21 ] );

		$a->setLimitReportData( 'limitreport-only-a', 13 );

		$b = new ParserOutput();

		$b->setLimitReportData( 'naive1', 17 );
		$b->setLimitReportData( 'naive2', 17 );

		$b->setLimitReportData( 'limitreport-simple1', 17 );
		$b->setLimitReportData( 'limitreport-simple2', 17 );

		$b->setLimitReportData( 'limitreport-pair1', [ 17, 19 ] );
		$b->setLimitReportData( 'limitreport-pair2', [ 17, 19 ] );

		$b->setLimitReportData( 'limitreport-more1', [ 17, 19, 11 ] );
		$b->setLimitReportData( 'limitreport-more2', [ 17, 19, 11 ] );

		$b->setLimitReportData( 'limitreport-only-b', 23 );

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
	 * @covers ParserOutput::mergeInternalMetaDataFrom
	 *
	 * @param ParserOutput $a
	 * @param ParserOutput $b
	 * @param array $expected
	 */
	public function testMergeInternalMetaDataFrom( ParserOutput $a, ParserOutput $b, $expected ) {
		$a->mergeInternalMetaDataFrom( $b );

		$this->assertFieldValues( $a, $expected );

		// test twice, to make sure the operation is idempotent
		$a->mergeInternalMetaDataFrom( $b );

		$this->assertFieldValues( $a, $expected );
	}

	/**
	 * @covers ParserOutput::mergeInternalMetaDataFrom
	 * @covers ParserOutput::getTimes
	 * @covers ParserOutput::resetParseStartTime
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
	 * @covers ParserOutput::getCacheTime
	 * @covers ParserOutput::setCacheTime
	 */
	public function testGetCacheTime() {
		$clock = MWTimestamp::convert( TS_UNIX, '20100101000000' );
		MWTimestamp::setFakeTime( static function () use ( &$clock ) {
			return $clock++;
		} );

		$po = new ParserOutput();
		$time = $po->getCacheTime();

		// Use current (fake) time per default. Ignore the last digit.
		// Subsequent calls must yield the exact same timestamp as the first.
		$this->assertStringStartsWith( '2010010100000', $time );
		$this->assertSame( $time, $po->getCacheTime() );

		// After setting, the getter must return the time that was set.
		$time = '20110606112233';
		$po->setCacheTime( $time );
		$this->assertSame( $time, $po->getCacheTime() );
	}

	/**
	 * @covers ParserOutput::addExtraCSPScriptSrc
	 * @covers ParserOutput::addExtraCSPDefaultSrc
	 * @covers ParserOutput::addExtraCSPStyleSrc
	 * @covers ParserOutput::getExtraCSPScriptSrcs
	 * @covers ParserOutput::getExtraCSPDefaultSrcs
	 * @covers ParserOutput::getExtraCSPStyleSrcs
	 */
	public function testCSPSources() {
		$po = new ParserOutput;

		$this->assertEquals( $po->getExtraCSPScriptSrcs(), [], 'empty Script' );
		$this->assertEquals( $po->getExtraCSPStyleSrcs(), [], 'empty Style' );
		$this->assertEquals( $po->getExtraCSPDefaultSrcs(), [], 'empty Default' );

		$po->addExtraCSPScriptSrc( 'foo.com' );
		$po->addExtraCSPScriptSrc( 'bar.com' );
		$po->addExtraCSPDefaultSrc( 'baz.com' );
		$po->addExtraCSPStyleSrc( 'fred.com' );
		$po->addExtraCSPStyleSrc( 'xyzzy.com' );

		$this->assertEquals( $po->getExtraCSPScriptSrcs(), [ 'foo.com', 'bar.com' ], 'Script' );
		$this->assertEquals( $po->getExtraCSPDefaultSrcs(),  [ 'baz.com' ], 'Default' );
		$this->assertEquals( $po->getExtraCSPStyleSrcs(), [ 'fred.com', 'xyzzy.com' ], 'Style' );
	}

	/**
	 * @covers ParserOutput::addTrackingCategory
	 */
	public function testAddTrackingCategory() {
		$this->hideDeprecated( 'ParserOutput::addTrackingCategory' );

		$po = new ParserOutput;
		$po->setPageProperty( 'defaultsort', 'foobar' );

		$page = PageReferenceValue::localReference( NS_USER, 'Testing' );

		$po->addTrackingCategory( 'index-category', $page ); // from CORE_TRACKING_CATEGORIES
		$po->addTrackingCategory( 'sitenotice', $page ); // should be "-", which is ignored
		$po->addTrackingCategory( 'brackets-start', $page ); // invalid text
		// TODO: assert proper handling of non-existing messages

		$expected = wfMessage( 'index-category' )
			->page( $page )
			->inContentLanguage()
			->text();

		$expected = strtr( $expected, ' ', '_' );
		$this->assertSame( [ $expected => 'foobar' ], $po->getCategories() );
	}
}
