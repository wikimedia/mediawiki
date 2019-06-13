<?php

class FileRepoTest extends MediaWikiTestCase {

	/**
	 * @expectedException MWException
	 * @covers FileRepo::__construct
	 */
	public function testFileRepoConstructionOptionCanNotBeNull() {
		new FileRepo();
	}

	/**
	 * @expectedException MWException
	 * @covers FileRepo::__construct
	 */
	public function testFileRepoConstructionOptionCanNotBeAnEmptyArray() {
		new FileRepo( [] );
	}

	/**
	 * @expectedException MWException
	 * @covers FileRepo::__construct
	 */
	public function testFileRepoConstructionOptionNeedNameKey() {
		new FileRepo( [
			'backend' => 'foobar'
		] );
	}

	/**
	 * @expectedException MWException
	 * @covers FileRepo::__construct
	 */
	public function testFileRepoConstructionOptionNeedBackendKey() {
		new FileRepo( [
			'name' => 'foobar'
		] );
	}

	/**
	 * @covers FileRepo::__construct
	 */
	public function testFileRepoConstructionWithRequiredOptions() {
		$f = new FileRepo( [
			'name' => 'FileRepoTestRepository',
			'backend' => new FSFileBackend( [
				'name' => 'local-testing',
				'wikiId' => 'test_wiki',
				'containerPaths' => []
			] )
		] );
		$this->assertInstanceOf( FileRepo::class, $f );
	}
}
