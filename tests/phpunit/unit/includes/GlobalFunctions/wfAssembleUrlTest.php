<?php
/**
 * @group GlobalFunctions
 * @covers ::wfAssembleUrl
 */
class WfAssembleUrlTest extends MediaWikiUnitTestCase {
	/**
	 * Same tests as the UrlUtils method to ensure they don't fall out of sync
	 * @dataProvider UrlUtilsProviders::provideAssemble
	 */
	public function testWfAssembleUrl( $parts, $output ) {
		$partsDump = print_r( $parts, true );
		$this->assertEquals(
			$output,
			wfAssembleUrl( $parts ),
			"Testing $partsDump assembles to $output"
		);
	}
}
