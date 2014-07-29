<?php
/**
 * @group GlobalFunctions
 * @covers ::wfWikiID
 */
class WfWikiId extends MediaWikiTestCase {

	public function testReturnsProperDbName() {
		$this->setMwGlobals( 'wgDBname', 'known_db_name' );
		$this->assertEquals('known_db_name',  wfWikiID() );
	}

	public function testHonorsDatabasePrefix() {
		$this->setMwGlobals( array(
			'wgDBname'   => 'known_db_name',
			'wgDBprefix' => 'prefix',
		));
		# Note: prefix is actually a suffix in wfWikiID()
		$this->assertEquals('known_db_name-prefix',  wfWikiID() );
	}

}
