<?php

/**
 * Changing the visibility of methods to be able to test them.
 */
class TestableNestedMenuParser extends NestedMenuParser {
	public function parseLines( array $lines, array $maxChildrenAtLevel = array() ) {
		parent::parseLines( $lines, $maxChildrenAtLevel );
	}

	public function parseOneLine( $line ) {
		parent::parseOneLine( $line );
	}
}


/**
 * @covers NestedMenuParser
 * @group Database
 */
class NestedMenuParserTest extends MediaWikiTestCase {
	public function testParseOneLine() {
		$service = new TestableNestedMenuParser();

		$this->assertEquals(
			$service->parseOneLine( "** Yet another child|This text differs from the page name" ),
			array(
				'original' => '???',
				'text' => '???',
				'href' => '???'
			)
		);
	}

	public function testParseLines() {
		$service = new TestableNestedMenuParser();


	}

	public function testParseMessage() {
		$service = new TestableNestedMenuParser();


	}
}
