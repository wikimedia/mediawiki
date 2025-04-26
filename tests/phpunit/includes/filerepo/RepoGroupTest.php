<?php

use MediaWiki\FileRepo\ForeignAPIRepo;
use MediaWiki\MainConfigNames;

/**
 * @covers \MediaWiki\FileRepo\RepoGroup
 */
class RepoGroupTest extends MediaWikiIntegrationTestCase {

	public function testHasForeignRepoNegative() {
		$this->overrideConfigValue( MainConfigNames::ForeignFileRepos, [] );
		$this->assertFalse( $this->getServiceContainer()->getRepoGroup()->hasForeignRepos() );
	}

	public function testHasForeignRepoPositive() {
		$this->setUpForeignRepo();
		$this->assertTrue( $this->getServiceContainer()->getRepoGroup()->hasForeignRepos() );
	}

	public function testForEachForeignRepo() {
		$this->setUpForeignRepo();
		$fakeCallback = $this->createMock( RepoGroupTestHelper::class );
		$fakeCallback->expects( $this->once() )->method( 'callback' );
		$this->getServiceContainer()->getRepoGroup()->forEachForeignRepo(
			[ $fakeCallback, 'callback' ], [ [] ] );
	}

	public function testForEachForeignRepoNone() {
		$this->overrideConfigValue( MainConfigNames::ForeignFileRepos, [] );
		$fakeCallback = $this->createMock( RepoGroupTestHelper::class );
		$fakeCallback->expects( $this->never() )->method( 'callback' );
		$this->getServiceContainer()->getRepoGroup()->forEachForeignRepo(
			[ $fakeCallback, 'callback' ], [ [] ] );
	}

	private function setUpForeignRepo() {
		global $wgUploadDirectory;
		$this->overrideConfigValue( MainConfigNames::ForeignFileRepos, [ [
			'class' => ForeignAPIRepo::class,
			'name' => 'wikimediacommons',
			'backend' => 'wikimediacommons-backend',
			'apibase' => 'https://commons.wikimedia.org/w/api.php',
			'hashLevels' => 2,
			'fetchDescription' => true,
			'descriptionCacheExpiry' => 43200,
			'apiThumbCacheExpiry' => 86400,
			'directory' => $wgUploadDirectory
		] ] );
	}
}
use MediaWiki\FileRepo\FileRepo;

/**
 * Quick helper class to use as a mock callback for RepoGroup::forEachForeignRepo.
 */
class RepoGroupTestHelper {
	public function callback( FileRepo $repo, array $foo ) {
		return true;
	}
}
