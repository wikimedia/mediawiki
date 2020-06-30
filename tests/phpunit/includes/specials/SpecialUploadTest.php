<?php

class SpecialUploadTest extends MediaWikiIntegrationTestCase {
	/**
	 * @covers SpecialUpload::getInitialPageText
	 * @dataProvider provideGetInitialPageText
	 */
	public function testGetInitialPageText( $expected, $inputParams ) {
		$result = SpecialUpload::getInitialPageText( ...$inputParams );
		$this->assertEquals( $expected, $result );
	}

	public function provideGetInitialPageText() {
		return [
			[
				'expect' => "== Summary ==\nthis is a test\n",
				'params' => [
					'this is a test'
				],
			],
			[
				'expect' => "== Summary ==\nthis is a test\n",
				'params' => [
					"== Summary ==\nthis is a test",
				],
			],
		];
	}
}
