<?php

/**
 * The function only need a string parameter and might react to IIS7.0
 *
 * @group GlobalFunctions
 * @covers ::wfUrlencode
 */
class WfUrlencodeTest extends MediaWikiUnitTestCase {
	/**
	 * @dataProvider provideURLS
	 */
	public function testEncodingUrl( $input, $expected ) {
		$this->assertEquals(
			$expected,
			wfUrlencode( $input )
		);
	}

	public static function provideURLS() {
		// NOTE: Keep in sync with qunit/mediawiki.util/util.test.js
		return [
			// Plus is not safe, ambiguous with space.
			[ '+', '%2B' ],
			// & and = not safe in query parameters.
			[ '&', '%26' ],
			[ '=', '%3D' ],
			[ ':', ':' ],
			// Encoding a slash to %2F would actively break things
			[ '/', '/' ],
			// Avoid redirect loop in Chromium T105265
			[ '~', '~' ],
			// Apostrophe
			[ '\'', '%27' ],
			[ '[]', '%5B%5D' ],
			[ '<>', '%3C%3E' ],
			// Remaining special chars do not need encoding
			[
				';@$-_.!*()',
				';@$-_.!*()',
			],
		];
	}
}
