<?php

class UploadStashTest extends MediaWikiTestCase {
	/**
	 * @var Array of UploadStashTestUser
	 */
	public static $users;

	public function setUp() {
		parent::setUp();
		
		// Setup a file for bug 29408
		$this->bug29408File = dirname( __FILE__ ) . '/bug29408';
		file_put_contents( $this->bug29408File, "\x00" );		
		
		self::$users = array(
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
	
	public function testBug29408() {
		global $wgUser;
		$wgUser = self::$users['uploader']->user;
		
		$repo = RepoGroup::singleton()->getLocalRepo();
		$stash = new UploadStash( $repo );
		
		// Throws exception caught by PHPUnit on failure
		$file = $stash->stashFile( $this->bug29408File );
		// We'll never reach this point if we hit bug 29408
		$this->assertTrue( true, 'Unrecognized file without extension' );
		
		$stash->removeFile( $file->getFileKey() );
	}
	
	public function tearDown() {
		parent::tearDown();
		
		unlink( $this->bug29408File . "." );
		
	}
}
