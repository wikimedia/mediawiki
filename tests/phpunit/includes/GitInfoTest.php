<?php
/**
 * @covers GitInfo
 */
class GitInfoTest extends MediaWikiTestCase {

	private static $tempDir;

	public static function setUpBeforeClass() {
		self::$tempDir = wfTempDir() . '/mw-phpunit-' . wfRandomString( 8 );
		if ( !mkdir( self::$tempDir ) ) {
			self::$tempDir = null;
			throw new Exception( 'Unable to create temporary directory' );
		}
		mkdir( self::$tempDir . '/gitrepo' );
		mkdir( self::$tempDir . '/gitrepo/1' );
		mkdir( self::$tempDir . '/gitrepo/2' );
		mkdir( self::$tempDir . '/gitrepo/3' );
		mkdir( self::$tempDir . '/gitrepo/1/.git' );
		mkdir( self::$tempDir . '/gitrepo/1/.git/refs' );
		mkdir( self::$tempDir . '/gitrepo/1/.git/refs/heads' );
		file_put_contents( self::$tempDir . '/gitrepo/1/.git/HEAD',
			"ref: refs/heads/master\n" );
		file_put_contents( self::$tempDir . '/gitrepo/1/.git/refs/heads/master',
			"0123456789012345678901234567890123abcdef\n" );
		file_put_contents( self::$tempDir . '/gitrepo/1/.git/packed-refs',
			"abcdef6789012345678901234567890123456789 refs/heads/master\n" );
		file_put_contents( self::$tempDir . '/gitrepo/2/.git',
			"gitdir: ../1/.git\n" );
		file_put_contents( self::$tempDir . '/gitrepo/3/.git',
			'gitdir: ' . self::$tempDir . "/gitrepo/1/.git\n" );
	}

	public static function tearDownAfterClass() {
		if ( self::$tempDir ) {
			wfRecursiveRemoveDir( self::$tempDir );
		}
	}

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

		$this->assertValidGitInfo( new GitInfo( "$IP/testValidJsonData" ) );
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

	public function testReadingHead() {
		$dir = self::$tempDir . '/gitrepo/1';
		$fixture = new GitInfo( $dir );

		$this->assertEquals( 'refs/heads/master', $fixture->getHead() );
		$this->assertEquals( '0123456789012345678901234567890123abcdef', $fixture->getHeadSHA1() );
	}

	public function testIndirection() {
		$dir = self::$tempDir . '/gitrepo/2';
		$fixture = new GitInfo( $dir );

		$this->assertEquals( 'refs/heads/master', $fixture->getHead() );
		$this->assertEquals( '0123456789012345678901234567890123abcdef', $fixture->getHeadSHA1() );
	}

	public function testIndirection2() {
		$dir = self::$tempDir . '/gitrepo/3';
		$fixture = new GitInfo( $dir );

		$this->assertEquals( 'refs/heads/master', $fixture->getHead() );
		$this->assertEquals( '0123456789012345678901234567890123abcdef', $fixture->getHeadSHA1() );
	}

	public function testReadingPackedRefs() {
		$dir = self::$tempDir . '/gitrepo/1';
		unlink( self::$tempDir . '/gitrepo/1/.git/refs/heads/master' );
		$fixture = new GitInfo( $dir );

		$this->assertEquals( 'refs/heads/master', $fixture->getHead() );
		$this->assertEquals( 'abcdef6789012345678901234567890123456789', $fixture->getHeadSHA1() );
	}
}
