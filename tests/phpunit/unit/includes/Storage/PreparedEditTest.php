<?php

namespace MediaWiki\Tests\Edit;

use MediaWiki\Edit\PreparedEdit;
use MediaWiki\Parser\ParserOutput;

/**
 * @covers \MediaWiki\Edit\PreparedEdit
 */
class PreparedEditTest extends \MediaWikiUnitTestCase {
	public function testCallback() {
		$output = new ParserOutput();
		$output->clearParseStartTime();

		$edit = new PreparedEdit();
		$edit->parserOutputCallback = static function () {
			$output = new ParserOutput();
			$output->clearParseStartTime();
			return $output;
		};

		$this->assertEquals( $output, $edit->getOutput() );
		$this->assertEquals( $output, $edit->output );
	}
}
