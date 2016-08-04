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

	public function testCategories() {
		$text = <<<END
We also have a {{Template}} and an {{Another template}} in addition. 
This text also has [[Category:Some Category| ]] and then [[Category:Yet another category]].
And [[Category:Some Category| this category]] is repeated.
END;
		$struct = $this->getStructure( $text );
		$cats = $struct->categories();
		$this->assertCount( 2, $cats );
		$this->assertContains( "Some Category", $cats );
		$this->assertContains( "Yet another category", $cats );
	}

	public function testOutgoingLinks() {
		$text = <<<END
Here I add link to [[Some Page]]. And [[Some Page|This same page]] gets linked twice. 
We also have [[File:Image.jpg|image]].
We also have a {{Template}} and an {{Another template}} in addition. 
Some templates are {{lowercase}}.
And [[Some_Page]] is linked again. 
It also has [[Category:Some Category| ]] and then [[Category:Yet another category]].
Also link to a [[Talk:TestTitle|talk page]] is here. 
END;
		$struct = $this->getStructure( $text );
		$links = $struct->outgoingLinks();
		$this->assertContains( "Some_Page", $links );
		$this->assertContains( "Template:Template", $links );
		$this->assertContains( "Template:Another_template", $links );
		$this->assertContains( "Template:Lowercase", $links );
		$this->assertContains( "Talk:TestTitle", $links );
		$this->assertCount( 5, $links );
	}

	public function testTemplates() {
		$text = <<<END
We have a {{Template}} and an {{Another template}} in addition. 
Some templates are {{lowercase}}. And this {{Template}} is repeated. 
Here is {{another_template|with=argument}}.
This is a template that {{Xdoes not exist}}.
END;
		$this->setTemporaryHook( 'TitleExists', function ( Title $title, &$exists ) {
			$txt = $title->getBaseText();
			if ( $txt[0] != 'X' ) {
				$exists = true;
			}
			return true;
		} );
		$struct = $this->getStructure( $text );
		$templates = $struct->templates();
		$this->assertCount( 3, $templates );
		$this->assertContains( "Template:Template", $templates );
		$this->assertContains( "Template:Another template", $templates );
		$this->assertContains( "Template:Lowercase", $templates );
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
