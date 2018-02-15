<?php

/**
 * @group Database
 *        ^--- trigger DB shadowing because we are using Title magic
 */
class ParserOutputTest extends MediaWikiTestCase {

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
	 * @covers ParserOutput::setExtensionData
	 * @covers ParserOutput::getExtensionData
	 */
	public function testExtensionData() {
		$po = new ParserOutput();

		$po->setExtensionData( "one", "Foo" );

		$this->assertEquals( "Foo", $po->getExtensionData( "one" ) );
		$this->assertNull( $po->getExtensionData( "spam" ) );

		$po->setExtensionData( "two", "Bar" );
		$this->assertEquals( "Foo", $po->getExtensionData( "one" ) );
		$this->assertEquals( "Bar", $po->getExtensionData( "two" ) );

		$po->setExtensionData( "one", null );
		$this->assertNull( $po->getExtensionData( "one" ) );
		$this->assertEquals( "Bar", $po->getExtensionData( "two" ) );
	}

	/**
	 * @covers ParserOutput::setProperty
	 * @covers ParserOutput::getProperty
	 * @covers ParserOutput::unsetProperty
	 * @covers ParserOutput::getProperties
	 */
	public function testProperties() {
		$po = new ParserOutput();

		$po->setProperty( 'foo', 'val' );

		$properties = $po->getProperties();
		$this->assertEquals( $po->getProperty( 'foo' ), 'val' );
		$this->assertEquals( $properties['foo'], 'val' );

		$po->setProperty( 'foo', 'second val' );

		$properties = $po->getProperties();
		$this->assertEquals( $po->getProperty( 'foo' ), 'second val' );
		$this->assertEquals( $properties['foo'], 'second val' );

		$po->unsetProperty( 'foo' );

		$properties = $po->getProperties();
		$this->assertEquals( $po->getProperty( 'foo' ), false );
		$this->assertArrayNotHasKey( 'foo', $properties );
	}

	/**
	 * @covers ParserOutput::getText
	 * @dataProvider provideGetText
	 * @param array $options Options to getText()
	 * @param string $text Parser text
	 * @param string $expect Expected output
	 */
	public function testGetText( $options, $text, $expect ) {
		$this->setMwGlobals( [
			'wgArticlePath' => '/wiki/$1',
			'wgScriptPath' => '/w',
			'wgScript' => '/w/index.php',
		] );

		$po = new ParserOutput( $text );
		$actual = $po->getText( $options );
		$this->assertSame( $expect, $actual );
	}

	public static function provideGetText() {
		// phpcs:disable Generic.Files.LineLength
		$text = <<<EOF
<div class="mw-parser-output"><p>Test document.
</p>
<mw:toc><div id="toc" class="toc"><div class="toctitle"><h2>Contents</h2></div>
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
</mw:toc>
<h2><span class="mw-headline" id="Section_1">Section 1</span><mw:editsection page="Test Page" section="1">Section 1</mw:editsection></h2>
<p>One
</p>
<h2><span class="mw-headline" id="Section_2">Section 2</span><mw:editsection page="Test Page" section="2">Section 2</mw:editsection></h2>
<p>Two
</p>
<h3><span class="mw-headline" id="Section_2.1">Section 2.1</span><mw:editsection page="Test Page" section="3">Section 2.1</mw:editsection></h3>
<p>Two point one
</p>
<h2><span class="mw-headline" id="Section_3">Section 3</span><mw:editsection page="Test Page" section="4">Section 3</mw:editsection></h2>
<p>Three
</p></div>
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
<div class="mw-parser-output"><p>Test document.
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
<h3><span class="mw-headline" id="Section_2.1">Section 2.1</span><span class="mw-editsection"><span class="mw-editsection-bracket">[</span><a href="/w/index.php?title=Test_Page&amp;action=edit&amp;section=3" title="Edit section: Section 2.1">edit</a><span class="mw-editsection-bracket">]</span></span></h3>
<p>Two point one
</p>
<h2><span class="mw-headline" id="Section_3">Section 3</span><span class="mw-editsection"><span class="mw-editsection-bracket">[</span><a href="/w/index.php?title=Test_Page&amp;action=edit&amp;section=4" title="Edit section: Section 3">edit</a><span class="mw-editsection-bracket">]</span></span></h2>
<p>Three
</p></div>
EOF
			],
			'Disable section edit links' => [
				[ 'enableSectionEditLinks' => false ], $text, <<<EOF
<div class="mw-parser-output"><p>Test document.
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
</p></div>
EOF
			],
			'Disable TOC' => [
				[ 'allowTOC' => false ], $text, <<<EOF
<div class="mw-parser-output"><p>Test document.
</p>

<h2><span class="mw-headline" id="Section_1">Section 1</span><span class="mw-editsection"><span class="mw-editsection-bracket">[</span><a href="/w/index.php?title=Test_Page&amp;action=edit&amp;section=1" title="Edit section: Section 1">edit</a><span class="mw-editsection-bracket">]</span></span></h2>
<p>One
</p>
<h2><span class="mw-headline" id="Section_2">Section 2</span><span class="mw-editsection"><span class="mw-editsection-bracket">[</span><a href="/w/index.php?title=Test_Page&amp;action=edit&amp;section=2" title="Edit section: Section 2">edit</a><span class="mw-editsection-bracket">]</span></span></h2>
<p>Two
</p>
<h3><span class="mw-headline" id="Section_2.1">Section 2.1</span><span class="mw-editsection"><span class="mw-editsection-bracket">[</span><a href="/w/index.php?title=Test_Page&amp;action=edit&amp;section=3" title="Edit section: Section 2.1">edit</a><span class="mw-editsection-bracket">]</span></span></h3>
<p>Two point one
</p>
<h2><span class="mw-headline" id="Section_3">Section 3</span><span class="mw-editsection"><span class="mw-editsection-bracket">[</span><a href="/w/index.php?title=Test_Page&amp;action=edit&amp;section=4" title="Edit section: Section 3">edit</a><span class="mw-editsection-bracket">]</span></span></h2>
<p>Three
</p></div>
EOF
			],
			'Unwrap text' => [
				[ 'unwrap' => true ], $text, <<<EOF
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
<h3><span class="mw-headline" id="Section_2.1">Section 2.1</span><span class="mw-editsection"><span class="mw-editsection-bracket">[</span><a href="/w/index.php?title=Test_Page&amp;action=edit&amp;section=3" title="Edit section: Section 2.1">edit</a><span class="mw-editsection-bracket">]</span></span></h3>
<p>Two point one
</p>
<h2><span class="mw-headline" id="Section_3">Section 3</span><span class="mw-editsection"><span class="mw-editsection-bracket">[</span><a href="/w/index.php?title=Test_Page&amp;action=edit&amp;section=4" title="Edit section: Section 3">edit</a><span class="mw-editsection-bracket">]</span></span></h2>
<p>Three
</p>
EOF
			],
			'Unwrap without a mw-parser-output wrapper' => [
				[ 'unwrap' => true ], '<div class="foobar">Content</div>', '<div class="foobar">Content</div>'
			],
			'Unwrap with extra comment at end' => [
				[ 'unwrap' => true ], '<div class="mw-parser-output"><p>Test document.</p></div>
<!-- Saved in parser cache... -->', '<p>Test document.</p>
<!-- Saved in parser cache... -->'
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

}
