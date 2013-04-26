<?php

class FileRepoTest extends MediaWikiTestCase {
	/**
	 * @expectedException MWException
	 */
	function testFileRepoConstructionOptionCanNotBeNull() {
		new FileRepo();
	}

	/**
	 * @expectedException MWException
	 */
	function testFileRepoConstructionOptionCanNotBeAnEmptyArray() {
		new FileRepo( array() );
	}

	/**
	 * @expectedException MWException
	 */
	function testFileRepoConstructionOptionNeedNameKey() {
		new FileRepo( array(
			'backend' => 'foobar'
		) );
	}

	/**
	 * @expectedException MWException
	 */
	function testFileRepoConstructionOptionNeedBackendKey() {
		new FileRepo( array(
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
