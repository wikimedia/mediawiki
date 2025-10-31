<?php

namespace MediaWiki\Tests\Content;

use MediaWiki\Content\WikitextContent;
use MediaWiki\Content\WikiTextStructure;
use MediaWiki\Title\Title;
use MediaWikiLangTestCase;

/**
 * @group Database
 * @covers \MediaWiki\Content\WikiTextStructure
 */
class WikitextStructureTest extends MediaWikiLangTestCase {

	/**
	 * Get WikitextStructure for given text
	 * @param string $text
	 * @return WikiTextStructure
	 */
	private function getStructure( $text ) {
		$content = new WikitextContent( $text );
		$contentRenderer = $this->getServiceContainer()->getContentRenderer();
		$parserOutput = $contentRenderer->getParserOutput( $content, Title::makeTitle( NS_MAIN, 'TestTitle' ) );
		return new WikiTextStructure( $parserOutput );
	}

	public function testHeadings() {
		$text = <<<END
Some text here
== Heading one ==
Some text
==== heading two ====
More text
=== Applicability of the strict mass-energy equivalence formula, ''E'' = ''mc''<sup>2</sup> ===
and more text
== Wikitext '''in''' [[Heading]] and also <b>html</b> ==
more text
==== See also ====
* Also things to see!
END;
		$struct = $this->getStructure( $text );
		$headings = $struct->headings();
		$this->assertCount( 4, $headings );
		$this->assertContains( "Heading one", $headings );
		$this->assertContains( "heading two", $headings );
		$this->assertContains( "Applicability of the strict mass-energy equivalence formula, E = mc2",
			$headings );
		$this->assertContains( "Wikitext in Heading and also html", $headings );
	}

	public function testDefaultSort() {
		$text = <<<END
Louise Michel
== Heading one ==
Some text
==== See also ====
* Also things to see!
{{DEFAULTSORT:Michel, Louise}}
END;
		$struct = $this->getStructure( $text );
		$this->assertEquals( "Michel, Louise", $struct->getDefaultSort() );
	}

	public function testHeadingsFirst() {
		$text = <<<END
== Heading one ==
Some text
==== heading two ====
END;
		$struct = $this->getStructure( $text );
		$headings = $struct->headings();
		$this->assertCount( 2, $headings );
		$this->assertContains( "Heading one", $headings );
		$this->assertContains( "heading two", $headings );
	}

	public function testHeadingsNone() {
		$text = "This text is completely devoid of headings.";
		$struct = $this->getStructure( $text );
		$headings = $struct->headings();
		$this->assertArrayEquals( [], $headings );
	}

	public function testTexts() {
		$text = <<<END
Opening text is opening.
<h2 class="hello">Then comes header</h2>
Then we got more<br>text
=== And more headers ===
{| class="wikitable"
|-
! Header table
|-
| row in table
|-
| another row in table
|}
END;
		$struct = $this->getStructure( $text );
		$this->assertEquals( "Opening text is opening.", $struct->getOpeningText() );
		$this->assertEquals( "Opening text is opening. Then we got more text",
			$struct->getMainText() );
		$this->assertEquals( [ "Header table row in table another row in table" ],
			$struct->getAuxiliaryText() );
	}

	public function testPreservesWordSpacing() {
		$text = "<dd><dl>foo</dl><dl>bar</dl></dd><p>baz</p>";
		$struct = $this->getStructure( $text );
		$this->assertEquals( "foo bar baz", $struct->getMainText() );
	}
}
