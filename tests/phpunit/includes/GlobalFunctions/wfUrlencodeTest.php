<?php
/**
 * Tests for includes/GlobalFunctions.php -> wfUrlencode()
 *
 * The function only need a string parameter and might react to IIS7.0
 */

class wfUrlencodeTest extends MediaWikiTestCase {

	#### TESTS ##############################################################
	
	/** @dataProvider provideURLS */
	public function testEncodingUrlWith( $input, $expected ) {
		$this->verifyEncodingFor( 'Apache', $input, $expected );
	}

	/** @dataProvider provideURLS */
	public function testEncodingUrlWithMicrosoftIis7( $input, $expected ) {
		$this->verifyEncodingFor( 'Microsoft-IIS/7', $input, $expected );
	}

	#### HELPERS #############################################################
	
	/**
	 * Internal helper that actually run the test.
	 * Called by the public methods testEncodingUrlWith...()
	 *
	 */
	private function verifyEncodingFor( $server, $input, $expectations ) {
		$expected = $this->extractExpect( $server, $expectations );

		// save up global
		$old = isset($_SERVER['SERVER_SOFTWARE'])
			? $_SERVER['SERVER_SOFTWARE']
			: null
		;
		$_SERVER['SERVER_SOFTWARE'] = $server;
		wfUrlencode( null );

		// do the requested test
		$this->assertEquals(
			$expected,
			wfUrlencode( $input ),
			"Encoding '$input' for server '$server' should be '$expected'"
		);

		// restore global
		if( $old === null ) {
			unset( $_SERVER['SERVER_SOFTWARE'] );
		} else {
			$_SERVER['SERVER_SOFTWARE'] = $old;
		}
		wfUrlencode( null );
	}

	/**
	 * Interprets the provider array. Return expected value depending
	 * the HTTP server name.
	 */
	private function extractExpect( $server, $expectations ) {
		if( is_string( $expectations ) ) {
			return $expectations;
		} elseif( is_array( $expectations ) ) {
			if( !array_key_exists( $server, $expectations ) ) {
				throw new MWException( __METHOD__ . " expectation does not have any value for server name $server. Check the provider array.\n" );
			} else {
				return $expectations[$server];
			}
	 	} else {
			throw new MWException( __METHOD__ . " given invalid expectation for '$server'. Should be a string or an array( <http server name> => <string> ).\n" );
	 	}
	}  


	#### PROVIDERS ###########################################################

	/**
	 * Format is either:
	 *   array( 'input', 'expected' );
	 * Or:
	 *   array( 'input',
	 *       array( 'Apache', 'expected' ),
	 *       array( 'Microsoft-IIS/7', 'expected' ),
	 *    ),
	 * If you want to add other HTTP server name, you will have to add a new
	 * testing method much like the testEncodingUrlWith() method above. 
	 */
	public static function provideURLS() {
		return array(
		### RFC 1738 chars	
			// + is not safe
			array( '+', '%2B' ),
			// & and = not safe in queries
			array( '&', '%26' ),
			array( '=', '%3D' ),

			array( ':', array(
				'Apache'          => ':',
				'Microsoft-IIS/7' => '%3A',
			) ),

			// remaining chars do not need encoding
			array(
				';@$-_.!*',
				';@$-_.!*',
			),

		### Other tests
			// slash remain unchanged. %2F seems to break things
			array( '/', '/' ),
	
			// Other 'funnies' chars
			array( '[]', '%5B%5D' ),
			array( '<>', '%3C%3E' ),

			// Apostrophe is encoded
			array( '\'', '%27' ),
		);
	}
}
