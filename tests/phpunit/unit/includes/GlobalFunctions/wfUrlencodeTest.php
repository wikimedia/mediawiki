<?php

/**
 * The function only need a string parameter and might react to IIS7.0
 *
 * @group GlobalFunctions
 * @covers ::wfUrlencode
 */
class WfUrlencodeTest extends MediaWikiUnitTestCase {
	# ### TESTS ##############################################################

	/**
	 * @dataProvider provideURLS
	 */
	public function testEncodingUrlWith( $input, $expected ) {
		$this->verifyEncodingFor( 'Apache', $input, $expected );
	}

	/**
	 * @dataProvider provideURLS
	 */
	public function testEncodingUrlWithMicrosoftIis7( $input, $expected ) {
		$this->verifyEncodingFor( 'Microsoft-IIS/7', $input, $expected );
	}

	# ### HELPERS #############################################################

	/**
	 * Internal helper that actually run the test.
	 * Called by the public methods testEncodingUrlWith...()
	 * @param string $server
	 * @param string $input
	 * @param array|string $expectations
	 */
	private function verifyEncodingFor( $server, $input, $expectations ) {
		$expected = $this->extractExpect( $server, $expectations );

		// save up global
		$old = $_SERVER['SERVER_SOFTWARE'] ?? null;
		$_SERVER['SERVER_SOFTWARE'] = $server;
		wfUrlencode( null );

		// do the requested test
		$this->assertEquals(
			$expected,
			wfUrlencode( $input ),
			"Encoding '$input' for server '$server' should be '$expected'"
		);

		// restore global
		if ( $old === null ) {
			unset( $_SERVER['SERVER_SOFTWARE'] );
		} else {
			$_SERVER['SERVER_SOFTWARE'] = $old;
		}
		wfUrlencode( null );
	}

	/**
	 * Interprets the provider array. Return expected value depending
	 * the HTTP server name.
	 * @param string $server
	 * @param string|array $expectations
	 * @return string
	 */
	private function extractExpect( $server, $expectations ) {
		if ( is_string( $expectations ) ) {
			return $expectations;
		} elseif ( is_array( $expectations ) ) {
			if ( !array_key_exists( $server, $expectations ) ) {
				throw new MWException( __METHOD__ . " expectation does not have any "
					. "value for server name $server. Check the provider array.\n" );
			} else {
				return $expectations[$server];
			}
		} else {
			throw new MWException( __METHOD__ . " given invalid expectation for "
				. "'$server'. Should be a string or an array [ <http server name> => <string> ].\n" );
		}
	}

	# ### PROVIDERS ###########################################################

	/**
	 * Format is either:
	 *   [ 'input', 'expected' ];
	 * Or:
	 *   [ 'input',
	 *       [ 'Apache', 'expected' ],
	 *       [ 'Microsoft-IIS/7', 'expected' ],
	 *   ],
	 * If you want to add other HTTP server name, you will have to add a new
	 * testing method much like the testEncodingUrlWith() method above.
	 */
	public static function provideURLS() {
		// NOTE: Keep in sync with qunit/mediawiki.util/util.test.js
		return [
			// Plus is not safe, ambigious with space.
			[ '+', '%2B' ],
			// & and = not safe in query parameters.
			[ '&', '%26' ],
			[ '=', '%3D' ],
			[ ':', [
				'Apache' => ':',
				'Microsoft-IIS/7' => '%3A',
			] ],
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
