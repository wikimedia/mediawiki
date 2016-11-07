<?php
/**
 * @covers GitInfo
 */
class GitInfoTest extends MediaWikiTestCase {

	protected function setUp() {
		parent::setUp();
		$this->setMwGlobals( 'wgGitInfoCacheDirectory', __DIR__ . '/../data/gitinfo' );
	}

	protected function assertValidGitInfo( GitInfo $gitInfo ) {
		$this->assertTrue( $gitInfo->cacheIsComplete() );
		$this->assertEquals( 'refs/heads/master', $gitInfo->getHead() );
		$this->assertEquals( '0123456789abcdef0123456789abcdef01234567',
			$gitInfo->getHeadSHA1() );
		$this->assertEquals( '1070884800', $gitInfo->getHeadCommitDate() );
		$this->assertEquals( 'master', $gitInfo->getCurrentBranch() );
		$this->assertContains( '0123456789abcdef0123456789abcdef01234567',
			$gitInfo->getHeadViewUrl() );
	}

	public function testValidJsonData() {
		global $IP;

		$this->assertValidGitInfo( new GitInfo( "$IP/testValidJsonData") );
		$this->assertValidGitInfo( new GitInfo( __DIR__ . "/../data/gitinfo/extension" ) );
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
