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
		$this->assertTrue( is_file( $file->getPath() ) );
		$file->purge();
		$this->assertFalse( is_file( $file->getPath() ) );
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
		$this->assertTrue( is_file( $path ) );
		$obj = new class {
		};
		$file->bind( $obj );
		unset( $file );
		$this->assertTrue( is_file( $path ) );

		// Make sure the file still exists after garbage collection
		gc_collect_cycles();
		$this->assertTrue( is_file( $path ) );

		unset( $obj );
		$this->assertFalse( is_file( $path ) );
	}

	/**
	 * @covers TempFSFile::__construct
	 * @covers TempFSFile::preserve
	 * @covers TempFSFile::__destruct
	 */
	public function testPreserve() {
		$file = $this->newFile();
		$path = $file->getPath();
		$this->assertTrue( is_file( $path ) );
		$file->preserve();
		unset( $file );
		$this->assertTrue( is_file( $path ) );
		@unlink( $path );
	}
}
