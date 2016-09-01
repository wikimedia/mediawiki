<?php

class WikitextStructureTest extends MediaWikiLangTestCase {

	private function getMockTitle() {
		return Title::newFromText( "TestTitle" );
	}

	/**
	 * Get parser output for Wiki text
	 * @param $text
	 * @return ParserOutput
	 */
	private function getParserOutput( $text ) {
		$content = new WikitextContent( $text );
		return $content->getParserOutput( $this->getMockTitle() );
	}

	/**
	 * Get WikitextStructure for given text
	 * @param $text
	 * @return WikiTextStructure
	 */
	private function getStructure( $text ) {
		return new WikiTextStructure( $this->getParserOutput( $text ) );
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
== Then comes header ==
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
		$this->assertEquals( "Opening text is opening.   Then we got more text",
			$struct->getMainText() );
		$this->assertEquals( [ "Header table  row in table  another row in table" ],
			$struct->getAuxiliaryText() );
	}
}
