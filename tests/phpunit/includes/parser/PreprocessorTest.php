<?php

use MediaWiki\MediaWikiServices;

/**
 * @covers Preprocessor
 *
 * @covers Preprocessor_Hash
 * @covers PPDStack_Hash
 * @covers PPDStackElement_Hash
 * @covers PPDPart_Hash
 * @covers PPFrame_Hash
 * @covers PPTemplateFrame_Hash
 * @covers PPCustomFrame_Hash
 * @covers PPNode_Hash_Tree
 * @covers PPNode_Hash_Text
 * @covers PPNode_Hash_Array
 * @covers PPNode_Hash_Attr
 */
class PreprocessorTest extends MediaWikiIntegrationTestCase {
	protected $mTitle = 'Page title';
	protected $mPPNodeCount = 0;
	/**
	 * @var ParserOptions
	 */
	protected $mOptions;
	/**
	 * @var array
	 */
	protected $mPreprocessors;

	protected static $classNames = [
		Preprocessor_Hash::class
	];

	protected function setUp() : void {
		parent::setUp();
		$this->mOptions = ParserOptions::newFromUserAndLang( new User,
			MediaWikiServices::getInstance()->getContentLanguage() );

		$this->mPreprocessors = [];
		foreach ( self::$classNames as $className ) {
			$this->mPreprocessors[$className] = new $className( $this );
		}
	}

	public function getStripList() {
		return [ 'gallery', 'display map' /* Used by Maps, see r80025 CR */, '/foo' ];
	}

	protected static function addClassArg( $testCases ) {
		$newTestCases = [];
		foreach ( self::$classNames as $className ) {
			foreach ( $testCases as $testCase ) {
				array_unshift( $testCase, $className );
				$newTestCases[] = $testCase;
			}
		}
		return $newTestCases;
	}

	public static function provideCases() {
		// phpcs:disable Generic.Files.LineLength
		return self::addClassArg( [
			[ "Foo", "<root>Foo</root>" ],
			[ "<!-- Foo -->", "<root><comment>&lt;!-- Foo --&gt;</comment></root>" ],
			[ "<!-- Foo --><!-- Bar -->", "<root><comment>&lt;!-- Foo --&gt;</comment><comment>&lt;!-- Bar --&gt;</comment></root>" ],
			[ "<!-- Foo -->  <!-- Bar -->", "<root><comment>&lt;!-- Foo --&gt;</comment>  <comment>&lt;!-- Bar --&gt;</comment></root>" ],
			[ "<!-- Foo --> \n <!-- Bar -->", "<root><comment>&lt;!-- Foo --&gt;</comment> \n <comment>&lt;!-- Bar --&gt;</comment></root>" ],
			[ "<!-- Foo --> \n <!-- Bar -->\n", "<root><comment>&lt;!-- Foo --&gt;</comment> \n<comment> &lt;!-- Bar --&gt;\n</comment></root>" ],
			[ "<!-- Foo -->  <!-- Bar -->\n", "<root><comment>&lt;!-- Foo --&gt;</comment>  <comment>&lt;!-- Bar --&gt;</comment>\n</root>" ],
			[ "<!-->Bar", "<root><comment>&lt;!--&gt;Bar</comment></root>" ],
			[ "<!-- Comment -- comment", "<root><comment>&lt;!-- Comment -- comment</comment></root>" ],
			[ "== Foo ==\n  <!-- Bar -->\n== Baz ==\n", "<root><h level=\"2\" i=\"1\">== Foo ==</h>\n<comment>  &lt;!-- Bar --&gt;\n</comment><h level=\"2\" i=\"2\">== Baz ==</h>\n</root>" ],
			[ "<gallery/>", "<root><ext><name>gallery</name><attr></attr></ext></root>" ],
			[ "Foo <gallery/> Bar", "<root>Foo <ext><name>gallery</name><attr></attr></ext> Bar</root>" ],
			[ "<gallery></gallery>", "<root><ext><name>gallery</name><attr></attr><inner></inner><close>&lt;/gallery&gt;</close></ext></root>" ],
			[ "<foo> <gallery></gallery>", "<root>&lt;foo&gt; <ext><name>gallery</name><attr></attr><inner></inner><close>&lt;/gallery&gt;</close></ext></root>" ],
			[ "<foo> <gallery><gallery></gallery>", "<root>&lt;foo&gt; <ext><name>gallery</name><attr></attr><inner>&lt;gallery&gt;</inner><close>&lt;/gallery&gt;</close></ext></root>" ],
			[ "<noinclude> Foo bar </noinclude>", "<root><ignore>&lt;noinclude&gt;</ignore> Foo bar <ignore>&lt;/noinclude&gt;</ignore></root>" ],
			[ "<noinclude>\n{{Foo}}\n</noinclude>", "<root><ignore>&lt;noinclude&gt;</ignore>\n<template lineStart=\"1\"><title>Foo</title></template>\n<ignore>&lt;/noinclude&gt;</ignore></root>" ],
			[ "<noinclude>\n{{Foo}}\n</noinclude>\n", "<root><ignore>&lt;noinclude&gt;</ignore>\n<template lineStart=\"1\"><title>Foo</title></template>\n<ignore>&lt;/noinclude&gt;</ignore>\n</root>" ],
			[ "<gallery>foo bar", "<root>&lt;gallery&gt;foo bar</root>" ],
			[ "<{{foo}}>", "<root>&lt;<template><title>foo</title></template>&gt;</root>" ],
			[ "<{{{foo}}}>", "<root>&lt;<tplarg><title>foo</title></tplarg>&gt;</root>" ],
			[ "<gallery></gallery</gallery>", "<root><ext><name>gallery</name><attr></attr><inner>&lt;/gallery</inner><close>&lt;/gallery&gt;</close></ext></root>" ],
			[ "=== Foo === ", "<root><h level=\"3\" i=\"1\">=== Foo === </h></root>" ],
			[ "==<!-- -->= Foo === ", "<root><h level=\"2\" i=\"1\">==<comment>&lt;!-- --&gt;</comment>= Foo === </h></root>" ],
			[ "=== Foo ==<!-- -->= ", "<root><h level=\"1\" i=\"1\">=== Foo ==<comment>&lt;!-- --&gt;</comment>= </h></root>" ],
			[ "=== Foo ===<!-- -->\n", "<root><h level=\"3\" i=\"1\">=== Foo ===<comment>&lt;!-- --&gt;</comment></h>\n</root>" ],
			[ "=== Foo ===<!-- --> <!-- -->\n", "<root><h level=\"3\" i=\"1\">=== Foo ===<comment>&lt;!-- --&gt;</comment> <comment>&lt;!-- --&gt;</comment></h>\n</root>" ],
			[ "== Foo ==\n== Bar == \n", "<root><h level=\"2\" i=\"1\">== Foo ==</h>\n<h level=\"2\" i=\"2\">== Bar == </h>\n</root>" ],
			[ "===========", "<root><h level=\"5\" i=\"1\">===========</h></root>" ],
			[ "Foo\n=\n==\n=\n", "<root>Foo\n=\n==\n=\n</root>" ],
			[ "{{Foo}}", "<root><template><title>Foo</title></template></root>" ],
			[ "\n{{Foo}}", "<root>\n<template lineStart=\"1\"><title>Foo</title></template></root>" ],
			[ "{{Foo|bar}}", "<root><template><title>Foo</title><part><name index=\"1\" /><value>bar</value></part></template></root>" ],
			[ "{{Foo|bar}}a", "<root><template><title>Foo</title><part><name index=\"1\" /><value>bar</value></part></template>a</root>" ],
			[ "{{Foo|bar|baz}}", "<root><template><title>Foo</title><part><name index=\"1\" /><value>bar</value></part><part><name index=\"2\" /><value>baz</value></part></template></root>" ],
			[ "{{Foo|1=bar}}", "<root><template><title>Foo</title><part><name>1</name>=<value>bar</value></part></template></root>" ],
			[ "{{Foo|=bar}}", "<root><template><title>Foo</title><part><name></name>=<value>bar</value></part></template></root>" ],
			[ "{{Foo|bar=baz}}", "<root><template><title>Foo</title><part><name>bar</name>=<value>baz</value></part></template></root>" ],
			[ "{{Foo|{{bar}}=baz}}", "<root><template><title>Foo</title><part><name><template><title>bar</title></template></name>=<value>baz</value></part></template></root>" ],
			[ "{{Foo|1=bar|baz}}", "<root><template><title>Foo</title><part><name>1</name>=<value>bar</value></part><part><name index=\"1\" /><value>baz</value></part></template></root>" ],
			[ "{{Foo|1=bar|2=baz}}", "<root><template><title>Foo</title><part><name>1</name>=<value>bar</value></part><part><name>2</name>=<value>baz</value></part></template></root>" ],
			[ "{{Foo|bar|foo=baz}}", "<root><template><title>Foo</title><part><name index=\"1\" /><value>bar</value></part><part><name>foo</name>=<value>baz</value></part></template></root>" ],
			[ "{{{1}}}", "<root><tplarg><title>1</title></tplarg></root>" ],
			[ "{{{1|}}}", "<root><tplarg><title>1</title><part><name index=\"1\" /><value></value></part></tplarg></root>" ],
			[ "{{{Foo}}}", "<root><tplarg><title>Foo</title></tplarg></root>" ],
			[ "{{{Foo|}}}", "<root><tplarg><title>Foo</title><part><name index=\"1\" /><value></value></part></tplarg></root>" ],
			[ "{{{Foo|bar|baz}}}", "<root><tplarg><title>Foo</title><part><name index=\"1\" /><value>bar</value></part><part><name index=\"2\" /><value>baz</value></part></tplarg></root>" ],
			[ "{<!-- -->{Foo}}", "<root>{<comment>&lt;!-- --&gt;</comment>{Foo}}</root>" ],
			[ "{{{{Foobar}}}}", "<root>{<tplarg><title>Foobar</title></tplarg>}</root>" ],
			[ "{{{ {{Foo}} }}}", "<root><tplarg><title> <template><title>Foo</title></template> </title></tplarg></root>" ],
			[ "{{ {{{Foo}}} }}", "<root><template><title> <tplarg><title>Foo</title></tplarg> </title></template></root>" ],
			[ "{{{{{Foo}}}}}", "<root><template><title><tplarg><title>Foo</title></tplarg></title></template></root>" ],
			[ "{{{{{Foo}} }}}", "<root><tplarg><title><template><title>Foo</title></template> </title></tplarg></root>" ],
			[ "{{{{{{Foo}}}}}}", "<root><tplarg><title><tplarg><title>Foo</title></tplarg></title></tplarg></root>" ],
			[ "{{{{{{Foo}}}}}", "<root>{<template><title><tplarg><title>Foo</title></tplarg></title></template></root>" ],
			[ "[[[Foo]]", "<root>[[[Foo]]</root>" ],
			[ "{{Foo|[[[[bar]]|baz]]}}", "<root><template><title>Foo</title><part><name index=\"1\" /><value>[[[[bar]]|baz]]</value></part></template></root>" ], // This test is important, since it means the difference between having the [[ rule stacked or not
			[ "{{Foo|[[[[bar]|baz]]}}", "<root>{{Foo|[[[[bar]|baz]]}}</root>" ],
			[ "{{Foo|Foo [[[[bar]|baz]]}}", "<root>{{Foo|Foo [[[[bar]|baz]]}}</root>" ],
			[ "Foo <display map>Bar</display map             >Baz", "<root>Foo <ext><name>display map</name><attr></attr><inner>Bar</inner><close>&lt;/display map             &gt;</close></ext>Baz</root>" ],
			[ "Foo <display map foo>Bar</display map             >Baz", "<root>Foo <ext><name>display map</name><attr> foo</attr><inner>Bar</inner><close>&lt;/display map             &gt;</close></ext>Baz</root>" ],
			[ "Foo <gallery bar=\"baz\" />", "<root>Foo <ext><name>gallery</name><attr> bar=&quot;baz&quot; </attr></ext></root>" ],
			[ "Foo <gallery bar=\"1\" baz=2 />", "<root>Foo <ext><name>gallery</name><attr> bar=&quot;1&quot; baz=2 </attr></ext></root>" ],
			[ "</foo>Foo<//foo>", "<root><ext><name>/foo</name><attr></attr><inner>Foo</inner><close>&lt;//foo&gt;</close></ext></root>" ], # Worth blacklisting IMHO
			[ "{{#ifexpr: ({{{1|1}}} = 2) | Foo | Bar }}", "<root><template><title>#ifexpr: (<tplarg><title>1</title><part><name index=\"1\" /><value>1</value></part></tplarg> = 2) </title><part><name index=\"1\" /><value> Foo </value></part><part><name index=\"2\" /><value> Bar </value></part></template></root>" ],
			[ "{{#if: {{{1|}}} | Foo | {{Bar}} }}", "<root><template><title>#if: <tplarg><title>1</title><part><name index=\"1\" /><value></value></part></tplarg> </title><part><name index=\"1\" /><value> Foo </value></part><part><name index=\"2\" /><value> <template><title>Bar</title></template> </value></part></template></root>" ],
			[ "{{#if: {{{1|}}} | Foo | [[Bar]] }}", "<root><template><title>#if: <tplarg><title>1</title><part><name index=\"1\" /><value></value></part></tplarg> </title><part><name index=\"1\" /><value> Foo </value></part><part><name index=\"2\" /><value> [[Bar]] </value></part></template></root>" ],
			[ "{{#if: {{{1|}}} | [[Foo]] | Bar }}", "<root><template><title>#if: <tplarg><title>1</title><part><name index=\"1\" /><value></value></part></tplarg> </title><part><name index=\"1\" /><value> [[Foo]] </value></part><part><name index=\"2\" /><value> Bar </value></part></template></root>" ],
			[ "{{#if: {{{1|}}} | 1 | {{#if: {{{1|}}} | 2 | 3 }} }}", "<root><template><title>#if: <tplarg><title>1</title><part><name index=\"1\" /><value></value></part></tplarg> </title><part><name index=\"1\" /><value> 1 </value></part><part><name index=\"2\" /><value> <template><title>#if: <tplarg><title>1</title><part><name index=\"1\" /><value></value></part></tplarg> </title><part><name index=\"1\" /><value> 2 </value></part><part><name index=\"2\" /><value> 3 </value></part></template> </value></part></template></root>" ],
			[ "{{ {{Foo}}", "<root>{{ <template><title>Foo</title></template></root>" ],
			[ "{{Foobar {{Foo}} {{Bar}} {{Baz}} ", "<root>{{Foobar <template><title>Foo</title></template> <template><title>Bar</title></template> <template><title>Baz</title></template> </root>" ],
			[ "[[Foo]] |", "<root>[[Foo]] |</root>" ],
			[ "{{Foo|Bar|", "<root>{{Foo|Bar|</root>" ],
			[ "[[Foo]", "<root>[[Foo]</root>" ],
			[ "[[Foo|Bar]", "<root>[[Foo|Bar]</root>" ],
			[ "{{Foo| [[Bar] }}", "<root>{{Foo| [[Bar] }}</root>" ],
			[ "{{Foo| [[Bar|Baz] }}", "<root>{{Foo| [[Bar|Baz] }}</root>" ],
			[ "{{Foo|bar=[[baz]}}", "<root>{{Foo|bar=[[baz]}}</root>" ],
			[ "{{foo|", "<root>{{foo|</root>" ],
			[ "{{foo|}", "<root>{{foo|}</root>" ],
			[ "{{foo|} }}", "<root><template><title>foo</title><part><name index=\"1\" /><value>} </value></part></template></root>" ],
			[ "{{foo|bar=|}", "<root>{{foo|bar=|}</root>" ],
			[ "{{Foo|} Bar=", "<root>{{Foo|} Bar=</root>" ],
			[ "{{Foo|} Bar=}}", "<root><template><title>Foo</title><part><name>} Bar</name>=<value></value></part></template></root>" ],
			/* [ file_get_contents( __DIR__ . '/QuoteQuran.txt' ], file_get_contents( __DIR__ . '/QuoteQuranExpanded.txt' ) ], */
		] );
		// phpcs:enable
	}

	/**
	 * Get XML preprocessor tree from the preprocessor (which may not be the
	 * native XML-based one).
	 *
	 * @param string $className
	 * @param string $wikiText
	 * @return string
	 */
	protected function preprocessToXml( $className, $wikiText ) {
		$preprocessor = $this->mPreprocessors[$className];
		if ( method_exists( $preprocessor, 'preprocessToXml' ) ) {
			return $this->normalizeXml( $preprocessor->preprocessToXml( $wikiText ) );
		}

		$dom = $preprocessor->preprocessToObj( $wikiText );
		if ( is_callable( [ $dom, 'saveXML' ] ) ) {
			return $dom->saveXML();
		} else {
			return $this->normalizeXml( $dom->__toString() );
		}
	}

	/**
	 * Normalize XML string to the form that a DOMDocument saves out.
	 *
	 * @param string $xml
	 * @return string
	 */
	protected function normalizeXml( $xml ) {
		// Normalize self-closing tags
		$xml = preg_replace( '!<([a-z]+)/>!', '<$1></$1>', str_replace( ' />', '/>', $xml ) );
		// Remove <equals> tags, which only occur in Preprocessor_Hash and
		// have no semantic value
		$xml = preg_replace( '!</?equals>!', '', $xml );
		return $xml;
	}

	/**
	 * @dataProvider provideCases
	 */
	public function testPreprocessorOutput( $className, $wikiText, $expectedXml ) {
		$this->assertEquals( $this->normalizeXml( $expectedXml ),
			$this->preprocessToXml( $className, $wikiText ) );
	}

	/**
	 * These are more complex test cases taken out of wiki articles.
	 */
	public static function provideFiles() {
		// phpcs:disable Generic.Files.LineLength
		return self::addClassArg( [
			[ "QuoteQuran" ], # https://en.wikipedia.org/w/index.php?title=Template:QuoteQuran/sandbox&oldid=237348988 GFDL + CC BY-SA by Striver
			[ "Factorial" ], # https://en.wikipedia.org/w/index.php?title=Template:Factorial&oldid=98548758 GFDL + CC BY-SA by Polonium
			[ "All_system_messages" ], # https://tl.wiktionary.org/w/index.php?title=Suleras:All_system_messages&oldid=2765 GPL text generated by MediaWiki
			[ "Fundraising" ], # https://tl.wiktionary.org/w/index.php?title=MediaWiki:Sitenotice&oldid=5716 GFDL + CC BY-SA, copied there by Sky Harbor.
			[ "NestedTemplates" ], # T29936
		] );
		// phpcs:enable
	}

	/**
	 * @dataProvider provideFiles
	 */
	public function testPreprocessorOutputFiles( $className, $filename ) {
		$folder = __DIR__ . "/../../../parser/preprocess";
		$wikiText = file_get_contents( "$folder/$filename.txt" );
		$output = $this->preprocessToXml( $className, $wikiText );

		$expectedFilename = "$folder/$filename.expected";
		if ( file_exists( $expectedFilename ) ) {
			$expectedXml = $this->normalizeXml( file_get_contents( $expectedFilename ) );
			$this->assertEquals( $expectedXml, $output );
		} else {
			$tempFilename = tempnam( $folder, "$filename." );
			file_put_contents( $tempFilename, $output );
			$this->markTestIncomplete( "File $expectedFilename missing. Output stored as $tempFilename" );
		}
	}

	/**
	 * Tests from T30642 · https://phabricator.wikimedia.org/T30642
	 */
	public static function provideHeadings() {
		// phpcs:disable Generic.Files.LineLength
		return self::addClassArg( [
			/* These should become headings: */
			[ "== h ==<!--c1-->", "<root><h level=\"2\" i=\"1\">== h ==<comment>&lt;!--c1--&gt;</comment></h></root>" ],
			[ "== h == 	<!--c1-->", "<root><h level=\"2\" i=\"1\">== h == 	<comment>&lt;!--c1--&gt;</comment></h></root>" ],
			[ "== h ==<!--c1--> 	", "<root><h level=\"2\" i=\"1\">== h ==<comment>&lt;!--c1--&gt;</comment> 	</h></root>" ],
			[ "== h == 	<!--c1--> 	", "<root><h level=\"2\" i=\"1\">== h == 	<comment>&lt;!--c1--&gt;</comment> 	</h></root>" ],
			[ "== h ==<!--c1--><!--c2-->", "<root><h level=\"2\" i=\"1\">== h ==<comment>&lt;!--c1--&gt;</comment><comment>&lt;!--c2--&gt;</comment></h></root>" ],
			[ "== h == 	<!--c1--><!--c2-->", "<root><h level=\"2\" i=\"1\">== h == 	<comment>&lt;!--c1--&gt;</comment><comment>&lt;!--c2--&gt;</comment></h></root>" ],
			[ "== h ==<!--c1--><!--c2--> 	", "<root><h level=\"2\" i=\"1\">== h ==<comment>&lt;!--c1--&gt;</comment><comment>&lt;!--c2--&gt;</comment> 	</h></root>" ],
			[ "== h == 	<!--c1--><!--c2--> 	", "<root><h level=\"2\" i=\"1\">== h == 	<comment>&lt;!--c1--&gt;</comment><comment>&lt;!--c2--&gt;</comment> 	</h></root>" ],
			[ "== h == 	<!--c1-->  <!--c2-->", "<root><h level=\"2\" i=\"1\">== h == 	<comment>&lt;!--c1--&gt;</comment>  <comment>&lt;!--c2--&gt;</comment></h></root>" ],
			[ "== h ==<!--c1-->  <!--c2--> 	", "<root><h level=\"2\" i=\"1\">== h ==<comment>&lt;!--c1--&gt;</comment>  <comment>&lt;!--c2--&gt;</comment> 	</h></root>" ],
			[ "== h == 	<!--c1-->  <!--c2--> 	", "<root><h level=\"2\" i=\"1\">== h == 	<comment>&lt;!--c1--&gt;</comment>  <comment>&lt;!--c2--&gt;</comment> 	</h></root>" ],
			[ "== h ==<!--c1--><!--c2--><!--c3-->", "<root><h level=\"2\" i=\"1\">== h ==<comment>&lt;!--c1--&gt;</comment><comment>&lt;!--c2--&gt;</comment><comment>&lt;!--c3--&gt;</comment></h></root>" ],
			[ "== h ==<!--c1-->  <!--c2--><!--c3-->", "<root><h level=\"2\" i=\"1\">== h ==<comment>&lt;!--c1--&gt;</comment>  <comment>&lt;!--c2--&gt;</comment><comment>&lt;!--c3--&gt;</comment></h></root>" ],
			[ "== h ==<!--c1--><!--c2-->  <!--c3-->", "<root><h level=\"2\" i=\"1\">== h ==<comment>&lt;!--c1--&gt;</comment><comment>&lt;!--c2--&gt;</comment>  <comment>&lt;!--c3--&gt;</comment></h></root>" ],
			[ "== h ==<!--c1-->  <!--c2-->  <!--c3-->", "<root><h level=\"2\" i=\"1\">== h ==<comment>&lt;!--c1--&gt;</comment>  <comment>&lt;!--c2--&gt;</comment>  <comment>&lt;!--c3--&gt;</comment></h></root>" ],
			[ "== h ==  <!--c1--><!--c2--><!--c3-->", "<root><h level=\"2\" i=\"1\">== h ==  <comment>&lt;!--c1--&gt;</comment><comment>&lt;!--c2--&gt;</comment><comment>&lt;!--c3--&gt;</comment></h></root>" ],
			[ "== h ==  <!--c1-->  <!--c2--><!--c3-->", "<root><h level=\"2\" i=\"1\">== h ==  <comment>&lt;!--c1--&gt;</comment>  <comment>&lt;!--c2--&gt;</comment><comment>&lt;!--c3--&gt;</comment></h></root>" ],
			[ "== h ==  <!--c1--><!--c2-->  <!--c3-->", "<root><h level=\"2\" i=\"1\">== h ==  <comment>&lt;!--c1--&gt;</comment><comment>&lt;!--c2--&gt;</comment>  <comment>&lt;!--c3--&gt;</comment></h></root>" ],
			[ "== h ==  <!--c1-->  <!--c2-->  <!--c3-->", "<root><h level=\"2\" i=\"1\">== h ==  <comment>&lt;!--c1--&gt;</comment>  <comment>&lt;!--c2--&gt;</comment>  <comment>&lt;!--c3--&gt;</comment></h></root>" ],
			[ "== h ==<!--c1--><!--c2--><!--c3-->  ", "<root><h level=\"2\" i=\"1\">== h ==<comment>&lt;!--c1--&gt;</comment><comment>&lt;!--c2--&gt;</comment><comment>&lt;!--c3--&gt;</comment>  </h></root>" ],
			[ "== h ==<!--c1-->  <!--c2--><!--c3-->  ", "<root><h level=\"2\" i=\"1\">== h ==<comment>&lt;!--c1--&gt;</comment>  <comment>&lt;!--c2--&gt;</comment><comment>&lt;!--c3--&gt;</comment>  </h></root>" ],
			[ "== h ==<!--c1--><!--c2-->  <!--c3-->  ", "<root><h level=\"2\" i=\"1\">== h ==<comment>&lt;!--c1--&gt;</comment><comment>&lt;!--c2--&gt;</comment>  <comment>&lt;!--c3--&gt;</comment>  </h></root>" ],
			[ "== h ==<!--c1-->  <!--c2-->  <!--c3-->  ", "<root><h level=\"2\" i=\"1\">== h ==<comment>&lt;!--c1--&gt;</comment>  <comment>&lt;!--c2--&gt;</comment>  <comment>&lt;!--c3--&gt;</comment>  </h></root>" ],
			[ "== h ==  <!--c1--><!--c2--><!--c3-->  ", "<root><h level=\"2\" i=\"1\">== h ==  <comment>&lt;!--c1--&gt;</comment><comment>&lt;!--c2--&gt;</comment><comment>&lt;!--c3--&gt;</comment>  </h></root>" ],
			[ "== h ==  <!--c1-->  <!--c2--><!--c3-->  ", "<root><h level=\"2\" i=\"1\">== h ==  <comment>&lt;!--c1--&gt;</comment>  <comment>&lt;!--c2--&gt;</comment><comment>&lt;!--c3--&gt;</comment>  </h></root>" ],
			[ "== h ==  <!--c1--><!--c2-->  <!--c3-->  ", "<root><h level=\"2\" i=\"1\">== h ==  <comment>&lt;!--c1--&gt;</comment><comment>&lt;!--c2--&gt;</comment>  <comment>&lt;!--c3--&gt;</comment>  </h></root>" ],
			[ "== h ==  <!--c1-->  <!--c2-->  <!--c3-->  ", "<root><h level=\"2\" i=\"1\">== h ==  <comment>&lt;!--c1--&gt;</comment>  <comment>&lt;!--c2--&gt;</comment>  <comment>&lt;!--c3--&gt;</comment>  </h></root>" ],
			[ "== h ==<!--c1--> 	<!--c2-->", "<root><h level=\"2\" i=\"1\">== h ==<comment>&lt;!--c1--&gt;</comment> 	<comment>&lt;!--c2--&gt;</comment></h></root>" ],
			[ "== h == 	<!--c1--> 	<!--c2-->", "<root><h level=\"2\" i=\"1\">== h == 	<comment>&lt;!--c1--&gt;</comment> 	<comment>&lt;!--c2--&gt;</comment></h></root>" ],
			[ "== h ==<!--c1--> 	<!--c2--> 	", "<root><h level=\"2\" i=\"1\">== h ==<comment>&lt;!--c1--&gt;</comment> 	<comment>&lt;!--c2--&gt;</comment> 	</h></root>" ],

			/* These are not working: */
			[ "== h == x <!--c1--><!--c2--><!--c3-->  ", "<root>== h == x <comment>&lt;!--c1--&gt;</comment><comment>&lt;!--c2--&gt;</comment><comment>&lt;!--c3--&gt;</comment>  </root>" ],
			[ "== h ==<!--c1--> x <!--c2--><!--c3-->  ", "<root>== h ==<comment>&lt;!--c1--&gt;</comment> x <comment>&lt;!--c2--&gt;</comment><comment>&lt;!--c3--&gt;</comment>  </root>" ],
			[ "== h ==<!--c1--><!--c2--><!--c3--> x ", "<root>== h ==<comment>&lt;!--c1--&gt;</comment><comment>&lt;!--c2--&gt;</comment><comment>&lt;!--c3--&gt;</comment> x </root>" ],
		] );
		// phpcs:enable
	}

	/**
	 * @dataProvider provideHeadings
	 */
	public function testHeadings( $className, $wikiText, $expectedXml ) {
		$this->assertEquals( $this->normalizeXml( $expectedXml ),
			$this->preprocessToXml( $className, $wikiText ) );
	}
}
