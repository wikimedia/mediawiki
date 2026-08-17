<?php

use MediaWiki\FileRepo\LocalRepo;
use MediaWiki\Upload\Exception\UploadStashBadPathException;
use MediaWiki\Upload\Exception\UploadStashFileNotFoundException;
use MediaWiki\Upload\Exception\UploadStashNoSuchKeyException;
use MediaWiki\Upload\Exception\UploadStashNotLoggedInException;
use MediaWiki\Upload\Exception\UploadStashWrongOwnerException;
use MediaWiki\Upload\Exception\UploadStashZeroLengthFileException;
use MediaWiki\Upload\UploadStashFile;
use Wikimedia\FileBackend\FSFile\FSFile;

/**
 * @covers \MediaWiki\Upload\UploadStash
 * @covers \MediaWiki\Upload\UploadStashFile
 * @group Database
 */
class UploadStashTest extends MediaWikiIntegrationTestCase {
	public function testGetFileBadFormat() {
		$stash = $this->getStash();
		$this->expectException( UploadStashBadPathException::class );
		$stash->getFile( '' );
	}

	public function testGetFileNotRegistered() {
		$stash = $this->getStash( new User );
		$this->expectException( UploadStashNotLoggedInException::class );
		$stash->getFile( '1234.jpg' );
	}

	public function testGetFileWrongOwner() {
		$user1 = $this->getMutableTestUser()->getUser();
		$user2 = $this->getMutableTestUser()->getUser();
		$stash1 = $this->getStash( $user1 );
		$stash2 = $this->getStash( $user2 );
		$stashFile = $stash1->stashFile( $this->getDataPath() );
		$this->expectException( UploadStashWrongOwnerException::class );
		$stash2->getFile( $stashFile->getFileKey() );
	}

	public function testStashFileNotLoggedIn() {
		// Ensure that the file isn't actually created (regression)
		$repo = $this->createNoOpMock( LocalRepo::class );
		$stash = new UploadStash( $repo, new User );
		$path = $this->getDataPath();
		$this->expectException( UploadStashNotLoggedInException::class );
		$stash->stashFile( $path, 'upload', [] );
	}

	public function testStashFileBadPath() {
		$stash = $this->getStash();
		$path = $this->getDataPath( 'nonexistent.jpg' );
		$this->expectException( UploadStashBadPathException::class );
		$stash->stashFile( $path, 'upload', [] );
	}

	public function testStashFileSuccess() {
		$stash = $this->getStash();
		$path = $this->getDataPath();

		// Insert a file
		$stashFile = $stash->stashFile( $path, 'upload', [] );
		$this->assertInstanceOf( UploadStashFile::class, $stashFile );

		// Check injected properties
		$this->assertStashProps( $stashFile );

		// Check properties from the database
		$stash = $this->getStash();
		$stashFile2 = $stash->getFile( $stashFile->getFileKey() );
		$this->assertStashProps( $stashFile2 );
	}

	public static function provideStashFileBadExtension() {
		return [
			[ '', 'jpg' ],
			[ 'php', '' ]
		];
	}

	/**
	 * @dataProvider provideStashFileBadExtension
	 */
	public function testStashFileBadExtension( $originalExtension, $expectedExtension ) {
		$temp = $this->getServiceContainer()->getTempFSFileFactory()
			->newTempFSFile( 'us', $originalExtension );
		copy( $this->getDataPath(), $temp->getPath() );
		$stash = $this->getStash();
		$stashFile = $stash->stashFile( $temp->getPath(), 'upload', [] );
		$this->assertSame( $expectedExtension, $stashFile->getExtension() );
	}

	public function testStashFileZeroLength() {
		$temp = $this->getServiceContainer()->getTempFSFileFactory()->newTempFSFile( 'us' );
		file_put_contents( $temp->getPath(), '' );
		$stash = $this->getStash();
		$this->expectException( UploadStashZeroLengthFileException::class );
		$stash->stashFile( $temp->getPath(), 'upload' );
	}

	public function testClear() {
		$stash = $this->getStash();
		$path = $this->getDataPath();
		$stashFile = $stash->stashFile( $path, 'upload', [] );
		$key = $stashFile->getFileKey();
		$this->assertNotNull( $stash->getFile( $key ) );
		$stash->clear();
		$this->assertNotFound( $stash, $key );
	}

	public function testRemoveFile() {
		$stash = $this->getStash();
		$path = $this->getDataPath();
		$stashFile = $stash->stashFile( $path, 'upload', [] );
		$repo = $this->getServiceContainer()->getRepoGroup()->getLocalRepo();
		$stashPath = $stashFile->getPath();
		$key = $stashFile->getFileKey();

		$this->assertTrue( $repo->fileExists( $stashPath ) );

		$this->assertTrue( $stash->removeFile( $key ) );

		$this->assertFalse( $repo->fileExists( $stashPath ) );
		$this->assertNotFound( $stash, $key );
	}

	/**
	 * Remove a stash file that's already missing from the repo for some reason
	 */
	public function testRemoveFileAlreadyGone() {
		$stash = $this->getStash();
		$path = $this->getDataPath();
		$stashFile = $stash->stashFile( $path, 'upload', [] );
		$repo = $this->getServiceContainer()->getRepoGroup()->getLocalRepo();
		$stashPath = $stashFile->getPath();
		$key = $stashFile->getFileKey();

		$this->assertStatusGood( $repo->quickPurge( $stashPath ) );

		$this->assertTrue( $stash->removeFile( $stashFile->getFileKey() ) );

		$this->assertFalse( $repo->fileExists( $stashPath ) );
		$this->assertNotFound( $stash, $key );
	}

	public function testRemoveFileNotRegistered() {
		$stash = $this->getStash( new User );
		$this->expectException( UploadStashNotLoggedInException::class );
		$stash->removeFile( '1234.jpg' );
	}

	public function testRemoveFileNoSuchKey() {
		$stash = $this->getStash();
		$this->expectException( UploadStashNoSuchKeyException::class );
		$stash->removeFile( 'nonexistent' );
	}

	public function testRemoveFileWrongOwner() {
		$user1 = $this->getMutableTestUser()->getUser();
		$user2 = $this->getMutableTestUser()->getUser();
		$stash1 = $this->getStash( $user1 );
		$stash2 = $this->getStash( $user2 );
		$stashFile = $stash1->stashFile( $this->getDataPath() );
		$this->expectException( UploadStashWrongOwnerException::class );
		$stash2->removeFile( $stashFile->getFileKey() );
	}

	public function testListFiles() {
		$stash = $this->getStash();
		$path = $this->getDataPath();
		$file1 = $stash->stashFile( $path, 'upload', [] );
		$file2 = $stash->stashFile( $path, 'upload', [] );
		$result = $stash->listFiles();
		$this->assertSame(
			[ $file1->getFileKey(), $file2->getFileKey() ],
			$result
		);
	}

	private function getStash( $user = null ): UploadStash {
		$user ??= $this->getTestUser()->getUser();
		// Make a new UploadStash so that we can test cache misses
		return new UploadStash(
			$this->getServiceContainer()->getRepoGroup()->getLocalRepo(),
			$user
		);
	}

	private function getDataPath( $name = 'test.jpg' ): string {
		return MW_INSTALL_PATH . '/tests/phpunit/data/media/' . $name;
	}

	private function assertStashProps( UploadStashFile $stashFile ) {
		$sha1 = ( new FSFile( $this->getDataPath() ) )->getSha1Base36();
		$this->assertSame( $sha1, $stashFile->getSha1(), 'getSha1' );
		$this->assertFalse( $stashFile->getDescriptionUrl(), 'getDescriptionUrl' );

		$fileKey = $stashFile->getFileKey();
		$this->assertMatchesRegularExpression( UploadStash::KEY_FORMAT_REGEX, $fileKey, 'getFileKey' );

		$thumbName = $stashFile->thumbName( [ 'width' => 120 ] );
		$this->assertSame( "120px-$fileKey", $thumbName, 'thumbName' );
		$this->assertSame(
			"/wiki/Special:UploadStash/thumb/$fileKey/$thumbName",
			$stashFile->getThumbUrl( $thumbName ),
			'getThumbUrl'
		);

		$this->assertSame( $fileKey, $stashFile->getUrlName(), 'getUrlName' );
		$this->assertSame(
			"/wiki/Special:UploadStash/file/$fileKey",
			$stashFile->getUrl(),
			'getUrl'
		);
		$this->assertSame( true, $stashFile->exists() );
	}

	/**
	 * Assert that a key is not present in the stash
	 */
	private function assertNotFound( UploadStash $stash, string $key ) {
		try {
			$stash->getFile( $key );
			$this->fail( 'expected file not found exception' );
		} catch ( UploadStashFileNotFoundException ) {
		}
	}
}
