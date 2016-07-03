<?php

/**
 * @group GlobalFunctions
 * @covers ::wfShorthandToInteger
 */
class WfShorthandToIntegerTest extends MediaWikiTestCase {
	/**
	 * @dataProvider provideABunchOfShorthands
	 */
	public function testWfShorthandToInteger( $input, $output, $description ) {
		$this->assertEquals(
			wfShorthandToInteger( $input ),
			$output,
			$description
		);
	}

	public static function provideABunchOfShorthands() {
		return [
			[ '', -1, 'Empty string' ],
			[ '     ', -1, 'String of spaces' ],
			[ '1G', 1024 * 1024 * 1024, 'One gig uppercased' ],
			[ '1g', 1024 * 1024 * 1024, 'One gig lowercased' ],
			[ '1M', 1024 * 1024, 'One meg uppercased' ],
			[ '1m', 1024 * 1024, 'One meg lowercased' ],
			[ '1K', 1024, 'One kb uppercased' ],
			[ '1k', 1024, 'One kb lowercased' ],
		];
	}
}
