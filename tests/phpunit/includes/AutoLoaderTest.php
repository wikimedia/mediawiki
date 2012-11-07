<?php
class AutoLoaderTest extends MediaWikiTestCase {

	public function testConfig() {
		$result = CheckAutoLoader::assertAutoloadConf( /* includeExt = */ true );

		$this->assertEquals(
			$result['missing'],
			array(),
			'No classes missing from configuration.'
		);

		$this->assertEquals(
			$result['wrong'],
			array(),
			'No incorrect entries in the configuration.'
		);
	}
}
