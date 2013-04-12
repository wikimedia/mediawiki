<?php

class FileRepoTest extends MediaWikiTestCase {

	/**
	 * @expectedException MWException
	 */
	function testFileRepoConstructionOptionCanNotBeNull() {
		return new FileRepo();
	}

	/**
	 * @expectedException MWException
	 */
	function testFileRepoConstructionOptionCanNotBeAnEmptyArray() {
		return new FileRepo( array() );
	}

	/**
	 * @expectedException MWException
	 */
	function testFileRepoConstructionOptionNeedNameKey() {
		return new FileRepo( array(
			'backend' => 'foobar'
		) );
	}

	/**
	 * @expectedException MWException
	 */
	function testFileRepoConstructionOptionNeedBackendKey() {
		return new FileRepo( array(
			'name' => 'foobar'
		) );
	}

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
