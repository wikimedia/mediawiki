<?php

class wfShorthandToIntegerTest extends MediaWikiTestCase {
	/**
	 * @dataProvider provideABunchOfShorthands
	 */
	function testWfShorthandToInteger( $input, $output, $description ) {
		$this->assertEquals(
			wfShorthandToInteger( $input ),
			$output,
			$description
		);
	}

	function provideABunchOfShorthands() {
		return array(
			array( '', -1, 'Empty string' ),
			array( '     ', -1, 'String of spaces' ),
			array( '1G', 1024 * 1024 * 1024, 'One gig uppercased' ),
			array( '1g', 1024 * 1024 * 1024, 'One gig lowercased' ),
			array( '1M', 1024 * 1024, 'One meg uppercased' ),
			array( '1m', 1024 * 1024, 'One meg lowercased' ),
			array( '1K', 1024, 'One kb uppercased' ),
			array( '1k', 1024, 'One kb lowercased' ),
		);
	}
	
}
