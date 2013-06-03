<?php

class RDFaSanitizeTest extends MediaWikiTestCase {

	protected function setUp() {
		parent::setUp();
		if ( !class_exists( 'DOMDocument' ) ) {
			$this->markTestSkipped( 'EasyRdf and the RDFa Sanitize test require DOMDocument' );
		}
		if ( !class_exists( 'EasyRdf_Graph' ) ) {
			$this->markTestSkipped( 'EasyRdf must be installed for the RDFa Sanitize test. Please run composer install with the --dev option.' );
		}
	}

	function dataRDFa() {
		$testblob = <<<HTML
<article prefix="foaf: http://example.org/wrong/foaf/uri/ dc: http://purl.org/dc/terms/">
	<h1>Testing prefix, about, tyepof, property, and nesting</h1>
	<div about="#this" typeof="dc:Agent" prefix="foaf: http://xmlns.com/foaf/0.1/">
		<p property="foaf:name">A particular agent</p>
	</div>
</article>

<article vocab="http://xmlns.com/foaf/0.1/">
	<h1>Testing vocab and terms</h1>
	<div about="#me">
		<p property="name">Ivan Herman</p>
	</div>
</article>

<article about="http://www.example.org/software" vocab="http://www.example.org/vocab#">
	<h1>Testing rel and resource.</h1>
	<p rel="license" resource="http://www.w3.org/Consortium/Legal/2002/copyright-software-20021231">Ivan Herman</p>
</article>

<article prefix="cal: http://www.w3.org/2002/12/cal/icaltzd# xsd: http://www.w3.org/2001/XMLSchema#">
	<h1>Testing datatypes and content</h1>
	<p about="#event1" typeof="cal:Vevent">
		<b property="cal:summary">Weekend off in Iona</b>: 
		<span property="cal:dtstart" content="2006-10-21" datatype="xsd:date">Oct 21st</span>
		to <span property="cal:dtend" content="2006-10-23"  datatype="xsd:date">Oct 23rd</span>.
		See <a rel="cal:url" href="http://freetime.example.org/">FreeTime.Example.org</a> for
		info on <span property="cal:location">Iona, UK</span>.
	</p>
</article>

<article prefix="rdf: http://www.w3.org/1999/02/22-rdf-syntax-ns#">
	<h1>Testing inlist</h1>
	<div about="">
		<p property="rdf:value" inlist>Foo</p>
		<a rel="rdf:value" href="http://example.org/" inlist>Foo</a>
	</div>
</article>

<article vocab="http://xmlns.com/foaf/0.1/">
	<h1>Testing rev and meta</h1>
	<div about="#me" typeof="Person">
		<meta property="name" content="The Doctor">
		<ul rev="member">
			<li typeof="Group" property="name">Time Lords</li>
			<li typeof="Group" property="name">Time Travelers</li>
		</ul>
	</div>
</article>

<article prefix="ex: http://www.example.org/">
	<h1>Testing the ':' character usage in a CURIE</h1>
	<p about="http://www.example.org" property="ex:column:test">Test</p>
</article>

<article prefix="foaf: http://xmlns.com/foaf/0.1/ schema: http://schema.org/ rdfa: http://www.w3.org/ns/rdfa#">
	<h1>Testing &lt;link&gt;, bnode CURIEs, and multiple entries to property</h1>
	<div typeof="schema:Person">
		<link property="rdfa:copy" resource="_:a">
	</div>
	<div typeof="foaf:Person">
		<link property="rdfa:copy" resource="_:a">
	</div>
	<p resource="_:a" typeof="rdfa:Pattern">Name: <span property="schema:name foaf:name">Amanda</span></p>
</article>

<article vocab="http://example.org/vocab/" prefix="prefix: http://example.org/prefix/">
	<h1>Testing various formats of CURIE, safe CURIE, etc...</h1>
	<div about=":b"></div>
	<div about="prefix:c"></div>
	<div about="[prefix:d]"></div>
	<div about="[:e]"></div>
</article>
HTML;

		// Instead of defining a pile of separate ugly strings for tests lets take advantage of the fact
		// that RDFa is based on HTML and use PHP's DOM to split tests by top-level nodes and extract the
		// message from a h1.
		$doc = new DOMDocument;
		@$doc->loadHTML( $testblob ); # Use @ since PHP foolishly complains about <article>

		$tests = array();
		foreach( $doc->getElementsByTagName( 'article' ) as $article ) {
			$name = $article->getElementsByTagName( 'h1' )->item( 0 )->textContent;
			// Sanitizer::removeHTMLtags will kill attributes on <article> so we convert to a <div> for the body.
			$div = $doc->createElement( 'div' );
			foreach( $article->attributes as $attr ) {
				$div->setAttributeNode( $attr );
			}
			while ( $article->firstChild ) {
				$div->appendChild( $article->firstChild );
			}
			$body = $doc->saveHTML( $div );
			$tests[] = array( $name, $body );
		}
		
		return $tests;
	}

	/**
	 * @dataProvider dataRDFa
	 */
	function testRDFa( $name, $html ) {
		// Parse the chunk of HTML alone to form the expected RDFa graph
		$expected = new EasyRdf_Graph( "http://example.org/");
		$expected->parse( $html, 'rdfa' );
		
		// Sanitize the chunk of HTML allowing <a> for some of the tests
		$sanitizedHtml = Sanitizer::removeHTMLtags( $html, null, array(), array( 'a' ) );
		$actual = new EasyRdf_Graph( "http://example.org/" );
		$actual->parse( $sanitizedHtml, 'rdfa' );

		// Assert that the graph parsed from the sanitized RDFa is the same as the original graph
		$this->assertEquals( $expected->dump( false ), $actual->dump( false ), $name );
	}

}
