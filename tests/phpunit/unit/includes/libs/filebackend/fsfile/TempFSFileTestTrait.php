<?php

/**
 * Code shared between the unit and integration tests
 */
trait TempFSFileTestTrait {
	abstract protected function newFile();

	/**
	 * @covers TempFSFile::__construct
	 * @covers TempFSFile::purge
	 */
	public function testPurge() {
		$file = $this->newFile();
		$this->assertTrue( file_exists( $file->getPath() ) );
		$file->purge();
		$this->assertFalse( file_exists( $file->getPath() ) );
	}

	/**
	 * @covers TempFSFile::__construct
	 * @covers TempFSFile::bind
	 * @covers TempFSFile::autocollect
	 * @covers TempFSFile::__destruct
	 */
	public function testBind() {
		$file = $this->newFile();
		$path = $file->getPath();
		$this->assertTrue( file_exists( $path ) );
		$obj = new stdclass;
		$file->bind( $obj );
		unset( $file );
		$this->assertTrue( file_exists( $path ) );
		unset( $obj );
		$this->assertFalse( file_exists( $path ) );
	}

	/**
	 * @covers TempFSFile::__construct
	 * @covers TempFSFile::preserve
	 * @covers TempFSFile::__destruct
	 */
	public function testPreserve() {
		$file = $this->newFile();
		$path = $file->getPath();
		$this->assertTrue( file_exists( $path ) );
		$file->preserve();
		unset( $file );
		$this->assertTrue( file_exists( $path ) );
		Wikimedia\suppressWarnings();
		unlink( $path );
		Wikimedia\restoreWarnings();
	}
}
