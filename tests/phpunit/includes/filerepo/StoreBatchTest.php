<?php
/**
 * @group Filerepo
 */
class StoreBatchTest extends MediaWikiTestCase {

	public function setUp() {
		parent::setUp();
		
		$this->repo = RepoGroup::singleton()->getLocalRepo();
		$this->date = gmdate( "YmdHis" );
		$this->users = array(
			'sysop' => new ApiTestUser(
				'Uploadstashtestsysop',
				'Upload Stash Test Sysop',
				'upload_stash_test_sysop@sample.com',
				array( 'sysop' )
			),
			'uploader' => new ApiTestUser(
				'Uploadstashtestuser',
				'Upload Stash Test User',
				'upload_stash_test_user@sample.com',
				array()
			)
		);
	}

	/**
	 * Store a file or virtual URL source into a media file name.
	 *
	 * @param $originalName string The title of the image
	 * @param $srcPath string The filepath or virtual URL
	 * @param $flags integer Flags to pass into repo::store().
	 */
	private function storeit($originalName, $srcPath, $flags) {
		$hashPath = $this->repo->getHashPath( $originalName );
		$dstRel = "$hashPath{$this->date}!$originalName";
		$dstUrlRel = $hashPath . $this->date . '!' . rawurlencode( $originalName );

		$result = $this->repo->store( $srcPath, 'temp', $dstRel, $flags );
		$result->value = $this->repo->getVirtualUrl( 'temp' ) . '/' . $dstUrlRel;
		return $result;
	}
					
	
	/**
	 * Test storing a file using different flags.
	 *
	 * @param $fn string The title of the image
	 * @param $infn string The name of the file (in the filesystem)
	 * @param $otherfn string The name of the different file (in the filesystem)
	 * @param $fromrepo logical 'true' if we want to copy from a virtual URL out of the Repo.
	 */
	private function storecohort($fn, $infn, $otherfn, $fromrepo) {
		$f = $this->storeit( $fn, $infn, 0 );
		$this->assertTrue( $f->isOK(), 'failed to store a new file' );
		$this->assertEquals( $f->failCount, 0, "counts wrong {$f->successCount} {$f->failCount}" );
		$this->assertEquals( $f->successCount, 1 , "counts wrong {$f->successCount} {$f->failCount}" );
		if ( $fromrepo ) {
			$f = $this->storeit( "Other-$fn", $infn, FileRepo::OVERWRITE);
			$infn = $f->value;
		}
		// This should work because we're allowed to overwrite
		$f = $this->storeit( $fn, $infn, FileRepo::OVERWRITE );
		$this->assertTrue( $f->isOK(), 'We should be allowed to overwrite' );
		$this->assertEquals( $f->failCount, 0, "counts wrong {$f->successCount} {$f->failCount}" );
		$this->assertEquals( $f->successCount, 1 , "counts wrong {$f->successCount} {$f->failCount}" );
		// This should fail because we're overwriting.
		$f = $this->storeit( $fn, $infn, 0 );
		$this->assertFalse( $f->isOK(), 'We should not be allowed to overwrite' );
		$this->assertEquals( $f->failCount, 1, "counts wrong {$f->successCount} {$f->failCount}" );
		$this->assertEquals( $f->successCount, 0 , "counts wrong {$f->successCount} {$f->failCount}" );
		// This should succeed because we're overwriting the same content.
		$f = $this->storeit( $fn, $infn, FileRepo::OVERWRITE_SAME );
		$this->assertTrue( $f->isOK(), 'We should be able to overwrite the same content' );
		$this->assertEquals( $f->failCount, 0, "counts wrong {$f->successCount} {$f->failCount}" );
		$this->assertEquals( $f->successCount, 1 , "counts wrong {$f->successCount} {$f->failCount}" );
		// This should fail because we're overwriting different content.
		if ( $fromrepo ) {
			$f = $this->storeit( "Other-$fn", $otherfn, FileRepo::OVERWRITE);
			$otherfn = $f->value;
		}
		$f = $this->storeit( $fn, $otherfn, FileRepo::OVERWRITE_SAME );
		$this->assertFalse( $f->isOK(), 'We should not be allowed to overwrite different content' );
		$this->assertEquals( $f->failCount, 1, "counts wrong {$f->successCount} {$f->failCount}" );
		$this->assertEquals( $f->successCount, 0 , "counts wrong {$f->successCount} {$f->failCount}" );
	}

	public function teststore() {
		global $IP;
		$this->storecohort( "Test1.png", "$IP/skins/monobook/wiki.png", "$IP/skins/monobook/video.png", false );
		$this->storecohort( "Test2.png", "$IP/skins/monobook/wiki.png", "$IP/skins/monobook/video.png", true );
	}

	public function tearDown() {
		parent::tearDown();
		
	}
}
