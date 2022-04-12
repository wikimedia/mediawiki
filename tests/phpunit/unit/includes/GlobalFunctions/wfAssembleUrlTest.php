<?php
/**
 * @group GlobalFunctions
 * @covers ::wfAssembleUrl
 */
class WfAssembleUrlTest extends MediaWikiUnitTestCase {
	/**
	 * @dataProvider provideURLParts
	 */
	public function testWfAssembleUrl( $parts, $output ) {
		$partsDump = print_r( $parts, true );
		$this->assertEquals(
			$output,
			wfAssembleUrl( $parts ),
			"Testing $partsDump assembles to $output"
		);
	}

	/**
	 * Provider of URL parts for testing wfAssembleUrl()
	 */
	public static function provideURLParts() {
		// Same tests as the UrlUtils method to ensure they don't fall out of sync
		return UrlUtilsTest::provideAssemble();
	}
}
