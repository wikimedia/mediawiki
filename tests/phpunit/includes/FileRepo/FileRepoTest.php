<?php

use MediaWiki\FileRepo\FileRepo;
use MediaWiki\MainConfigNames;
use Wikimedia\FileBackend\FSFileBackend;

/**
 * @covers \MediaWiki\FileRepo\FileRepo
 */
class FileRepoTest extends MediaWikiIntegrationTestCase {

	public function testFileRepoConstructionOptionCanNotBeNull() {
		$this->expectException( InvalidArgumentException::class );
		new FileRepo();
	}

	public function testFileRepoConstructionOptionCanNotBeAnEmptyArray() {
		$this->expectException( InvalidArgumentException::class );
		new FileRepo( [] );
	}

	public function testFileRepoConstructionOptionNeedNameKey() {
		$this->expectException( InvalidArgumentException::class );
		new FileRepo( [
			'backend' => 'foobar'
		] );
	}

	public function testFileRepoConstructionOptionNeedBackendKey() {
		$this->expectException( InvalidArgumentException::class );
		new FileRepo( [
			'name' => 'foobar'
		] );
	}

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

	public function testFileRepoConstructionWithInvalidCasing() {
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( 'File repos with initial capital false' );

		$this->overrideConfigValue( MainConfigNames::CapitalLinks, true );

		new FileRepo( [
			'name' => 'foobar',
			'backend' => 'local-backend',
			'initialCapital' => false,
		] );
	}
}
