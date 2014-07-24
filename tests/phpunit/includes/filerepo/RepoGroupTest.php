<?php
class RepoGroupTest extends MediaWikiTestCase {

	function testHasForeignRepoNegative() {
		$this->setMwGlobals( 'wgForeignFileRepos', array() );
		RepoGroup::destroySingleton();
		FileBackendGroup::destroySingleton();
		$this->assertFalse( RepoGroup::singleton()->hasForeignRepos() );
	}

	function testHasForeignRepoPositive() {
		$this->setUpForeignRepo();
		$this->assertTrue( RepoGroup::singleton()->hasForeignRepos() );
	}

	function testForEachForeignRepo() {
		$this->setUpForeignRepo();
		$fakeCallback = $this->getMock( 'RepoGroupTestHelper' );
		$fakeCallback->expects( $this->once() )->method( 'callback' );
		RepoGroup::singleton()->forEachForeignRepo(
			array( $fakeCallback, 'callback' ), array( array() ) );
	}

	function testForEachForeignRepoNone() {
		$this->setMwGlobals( 'wgForeignFileRepos', array() );
		RepoGroup::destroySingleton();
		FileBackendGroup::destroySingleton();
		$fakeCallback = $this->getMock( 'RepoGroupTestHelper' );
		$fakeCallback->expects( $this->never() )->method( 'callback' );
		RepoGroup::singleton()->forEachForeignRepo(
			array( $fakeCallback, 'callback' ), array( array() ) );
	}

	private function setUpForeignRepo() {
		global $wgUploadDirectory;
		$this->setMwGlobals( 'wgForeignFileRepos', array( array(
			'class' => 'ForeignAPIRepo',
			'name' => 'wikimediacommons',
			'backend' => 'wikimediacommons-backend',
			'apibase' => 'https://commons.wikimedia.org/w/api.php',
			'hashLevels' => 2,
			'fetchDescription' => true,
			'descriptionCacheExpiry' => 43200,
			'apiThumbCacheExpiry' => 86400,
			'directory' => $wgUploadDirectory
		) ) );
		RepoGroup::destroySingleton();
		FileBackendGroup::destroySingleton();
	}
}

/**
 * Quick helper class to use as a mock callback for RepoGroup::singleton()->forEachForeignRepo.
 */
class RepoGroupTestHelper {
	function callback( FileRepo $repo, array $foo ) {
		return true;
	}
}
