<?php

class FileRepoTest extends MediaWikiTestCase {

	/**
	 * @expectedException MWException
	 * @covers FileRepo::__construct
	 */
	function testFileRepoConstructionOptionCanNotBeNull() {
		new FileRepo();
	}

	/**
	 * @expectedException MWException
	 * @covers FileRepo::__construct
	 */
	function testFileRepoConstructionOptionCanNotBeAnEmptyArray() {
		new FileRepo( array() );
	}

	/**
	 * @expectedException MWException
	 * @covers FileRepo::__construct
	 */
	function testFileRepoConstructionOptionNeedNameKey() {
		new FileRepo( array(
			'backend' => 'foobar'
		) );
	}

	/**
	 * @expectedException MWException
	 * @covers FileRepo::__construct
	 */
	function testFileRepoConstructionOptionNeedBackendKey() {
		new FileRepo( array(
			'name' => 'foobar'
		) );
	}

	/**
	 * @covers FileRepo::__construct
	 */
	function testFileRepoConstructionWithRequiredOptions() {
		$f = new FileRepo( array(
			'name' => 'FileRepoTestRepository',
			'backend' => new FSFileBackend( array(
				'name' => 'local-testing',
				'lockManager' => 'nullLockManager',
				'containerPaths' => array()
			) )
		) );
		$this->assertInstanceOf( 'FileRepo', $f );
	}
}
