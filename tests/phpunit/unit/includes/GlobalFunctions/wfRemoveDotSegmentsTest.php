<?php

/**
 * @group GlobalFunctions
 * @covers ::wfRemoveDotSegments
 */
class WfRemoveDotSegmentsTest extends MediaWikiUnitTestCase {
	/**
	 * @dataProvider providePaths
	 */
	public function testWfRemoveDotSegments( $inputPath, $outputPath ) {
		$this->assertEquals(
			$outputPath,
			wfRemoveDotSegments( $inputPath ),
			"Testing $inputPath expands to $outputPath"
		);
	}

	/**
	 * Provider of URL paths for testing wfRemoveDotSegments()
	 */
	public static function providePaths() {
		// Same tests as the UrlUtils method to ensure they don't fall out of sync
		return UrlUtilsTest::provideRemoveDotSegments();
	}
}
