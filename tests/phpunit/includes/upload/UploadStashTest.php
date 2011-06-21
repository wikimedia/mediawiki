<?php

class UploadStashTest extends MediaWikiTestCase {
	public function setUp() {
		parent::setUp();
		
		// Setup a fake session if necessary
		if ( !isset( $_SESSION ) ) {
			$GLOBALS['_SESSION'] = array();
		}
		
		// Setup a file for bug 29408
		$this->bug29408File = dirname( __FILE__ ) . '/bug29408';
		file_put_contents( $this->bug29408File, "\x00" );		
	}
	
	public function testBug29408() {
		$repo = RepoGroup::singleton()->getLocalRepo();
		$stash = new UploadStash( $repo );
		
		// Throws exception caught by PHPUnit on failure
		$file = $stash->stashFile( $this->bug29408File );
		// We'll never reach this point if we hit bug 29408
		$this->assertTrue( true, 'Unrecognized file without extension' );
		
		$file->remove();
	}
	
	public function tearDown() {
		parent::tearDown();
		
		unlink( $this->bug29408File . "." );
		
	}
}
