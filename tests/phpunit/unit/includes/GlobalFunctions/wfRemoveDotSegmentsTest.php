<?php

/**
 * @group GlobalFunctions
 * @covers ::wfRemoveDotSegments
 */
class WfRemoveDotSegmentsTest extends MediaWikiUnitTestCase {
	/**
	 * Same tests as the UrlUtils method to ensure they don't fall out of sync
	 * @dataProvider UrlUtilsProviders::provideRemoveDotSegments
	 */
	public function testWfRemoveDotSegments( $inputPath, $outputPath ) {
		$this->assertEquals(
			$outputPath,
			wfRemoveDotSegments( $inputPath ),
			"Testing $inputPath expands to $outputPath"
		);
	}
}
