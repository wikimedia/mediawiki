<?php
/**
 * @covers GitInfo
 */
class GitInfoTest extends MediaWikiTestCase {

	protected function setUp() {
		parent::setUp();
		$this->setMwGlobals( 'wgGitInfoCacheDirectory', __DIR__ . '/../data/gitinfo' );
	}

	public function testValidJsonData() {
		$dir = $GLOBALS['IP'] . DIRECTORY_SEPARATOR . 'testValidJsonData';
		$fixture = new GitInfo( $dir );

		$this->assertTrue( $fixture->cacheIsComplete() );
		$this->assertEquals( 'refs/heads/master', $fixture->getHead() );
		$this->assertEquals( '0123456789abcdef0123456789abcdef01234567',
			$fixture->getHeadSHA1() );
		$this->assertEquals( '1070884800', $fixture->getHeadCommitDate() );
		$this->assertEquals( 'master', $fixture->getCurrentBranch() );
		$this->assertContains( '0123456789abcdef0123456789abcdef01234567',
			$fixture->getHeadViewUrl() );
	}

	public function testMissingJsonData() {
		$dir = $GLOBALS['IP'] . '/testMissingJsonData';
		$fixture = new GitInfo( $dir );

		$this->assertFalse( $fixture->cacheIsComplete() );

		$this->assertEquals( false, $fixture->getHead() );
		$this->assertEquals( false, $fixture->getHeadSHA1() );
		$this->assertEquals( false, $fixture->getHeadCommitDate() );
		$this->assertEquals( false, $fixture->getCurrentBranch() );
		$this->assertEquals( false, $fixture->getHeadViewUrl() );

		// After calling all the outputs, the cache should be complete
		$this->assertTrue( $fixture->cacheIsComplete() );
	}

}
