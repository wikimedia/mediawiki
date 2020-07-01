<?php

class FileRepoTest extends MediaWikiIntegrationTestCase {

	/**
	 * @covers FileRepo::__construct
	 */
	public function testFileRepoConstructionOptionCanNotBeNull() {
		$this->expectException( MWException::class );
		new FileRepo();
	}

	/**
	 * @covers FileRepo::__construct
	 */
	public function testFileRepoConstructionOptionCanNotBeAnEmptyArray() {
		$this->expectException( MWException::class );
		new FileRepo( [] );
	}

	/**
	 * @covers FileRepo::__construct
	 */
	public function testFileRepoConstructionOptionNeedNameKey() {
		$this->expectException( MWException::class );
		new FileRepo( [
			'backend' => 'foobar'
		] );
	}

	/**
	 * @covers FileRepo::__construct
	 */
	public function testFileRepoConstructionOptionNeedBackendKey() {
		$this->expectException( MWException::class );
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

	/**
	 * @covers FileRepo::__construct
	 */
	public function testFileRepoConstructionWithInvalidCasing() {
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( 'File repos with initial capital false' );

		$this->setMwGlobals( 'wgCapitalLinks', true );

		new FileRepo( [
			'name' => 'foobar',
			'backend' => 'local-backend',
			'initialCapital' => false,
		] );
	}
}
