<?php
/**
 * Test regex wordboundary (\b) with devanagari words
 */
class bug46773Test extends MediaWikiTestCase {

	/**
	 * @dataProvider provideWordPatternsInMarathi
	 */
	function testRegexBoundariesDevanagari( $expected, $pattern, $subject ) {
		if($expected) {
			$this->assertRegexp( $pattern, $subject );
		} else {
			$this->assertNotRegexp( $pattern, $subject );
		}
	}

	/**
	 * @dataProvider provideWordPatternsInMarathi
	 */
	function testRegexBoundariesDevanagariInUnicodeMode( $expected, $pattern, $subject ) {
		$pattern .= 'u';  # enable unicode mode
		$this->testRegexBoundariesDevanagari( $expected, $pattern, $subject );
	}

	function provideWordPatternsInMarathi () {
		# See https://bugzilla.wikimedia.org/46773

		# FIXME make sure the tests are what they are meant to be
		$MATCH = true;
		$REJECT = false;

		return array(
			# match?, pattern, subject
			array( $REJECT, '/तू\b/', 'तूप' ),
			array( $REJECT, '/\bतू/', 'धातू' ),
			array( $REJECT, '/\bतू\b/', 'दुकानातून' ),
		);
	}

}
