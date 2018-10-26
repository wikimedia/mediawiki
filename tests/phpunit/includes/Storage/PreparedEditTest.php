<?php

namespace MediaWiki\Edit;

use ParserOutput;
use MediaWikiTestCase;

/**
 * @covers \MediaWiki\Edit\PreparedEdit
 */
class PreparedEditTest extends MediaWikiTestCase {
	function testCallback() {
		$output = new ParserOutput();
		$edit = new PreparedEdit();
		$edit->parserOutputCallback = function () {
			return new ParserOutput();
		};

		$this->assertEquals( $output, $edit->getOutput() );
		$this->assertEquals( $output, $edit->output );
	}
}
