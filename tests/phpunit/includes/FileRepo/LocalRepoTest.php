<?php

use MediaWiki\FileRepo\File\LocalFile;
use MediaWiki\FileRepo\File\OldLocalFile;
use MediaWiki\FileRepo\LocalRepo;
use MediaWiki\MainConfigNames;
use MediaWiki\Status\Status;
use MediaWiki\Title\Title;
use MediaWiki\WikiMap\WikiMap;
use Wikimedia\ObjectCache\EmptyBagOStuff;
use Wikimedia\ObjectCache\HashBagOStuff;
use Wikimedia\ObjectCache\WANObjectCache;

/**
 * @group Database
 * @covers \MediaWiki\FileRepo\FileRepo
 * @covers \MediaWiki\FileRepo\LocalRepo
 */
class LocalRepoTest extends MediaWikiIntegrationTestCase {
	/**
	 * @param array $extraInfo To pass to LocalRepo constructor
	 * @return LocalRepo
	 */
	private function newRepo( array $extraInfo = [] ) {
		return new LocalRepo( $extraInfo + [
			'name' => 'local',
			'backend' => 'local-backend',
		] );
	}

	/**
	 * @param array $extraInfo To pass to constructor
	 * @param bool $expected
	 * @dataProvider provideHasSha1Storage
	 */
	public function testHasSha1Storage( array $extraInfo, $expected ) {
		$this->assertSame( $expected, $this->newRepo( $extraInfo )->hasSha1Storage() );
	}

	public static function provideHasSha1Storage() {
		return [
			[ [], false ],
			[ [ 'storageLayout' => 'sha256' ], false ],
			[ [ 'storageLayout' => 'sha1' ], true ],
		];
	}

	/**
	 * @param string $prefix 'img' or 'oi'
	 * @param string $expectedClass 'LocalFile' or 'OldLocalFile'
	 * @dataProvider provideNewFileFromRow
	 */
	public function testNewFileFromRow( $prefix, $expectedClass ) {
		$this->editPage( 'File:Test_file', 'Some description' );

		$row = (object)[
			"{$prefix}_name" => 'Test_file',
			"{$prefix}_user" => '1',
			"{$prefix}_timestamp" => '12345678910111',
			"{$prefix}_metadata" => '',
			"{$prefix}_sha1" => sha1( '' ),
			"{$prefix}_size" => '0',
			"{$prefix}_height" => '0',
			"{$prefix}_width" => '0',
			"{$prefix}_bits" => '0',
			"{$prefix}_media_type" => 'UNKNOWN',
			"{$prefix}_description_text" => '',
			"{$prefix}_description_data" => null,
		];
		if ( $prefix === 'oi' ) {
			$row->oi_archive_name = 'Archive_name';
			$row->oi_deleted = '0';
		}
		$file = $this->newRepo()->newFileFromRow( $row );
		$this->assertInstanceOf( $expectedClass, $file );
		$this->assertSame( 'Test_file', $file->getName() );
		$this->assertSame( 1, $file->getUploader()->getId() );
	}

	public static function provideNewFileFromRow() {
		return [
			'img' => [ 'img', LocalFile::class ],
			'oi' => [ 'oi', OldLocalFile::class ],
		];
	}

	public function testNewFileFromRow_invalid() {
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( 'LocalRepo::newFileFromRow: invalid row' );

		$row = (object)[
			"img_user" => '1',
			"img_timestamp" => '12345678910111',
			"img_metadata" => '',
			"img_sha1" => sha1( '' ),
			"img_size" => '0',
			"img_height" => '0',
			"img_width" => '0',
			"img_bits" => '0',
		];
		$file = $this->newRepo()->newFileFromRow( $row );
	}

	public function testNewFromArchiveName() {
		$this->editPage( 'File:Test_file', 'Some description' );

		$file = $this->newRepo()->newFromArchiveName( 'Test_file', 'b' );
		$this->assertInstanceOf( OldLocalFile::class, $file );
		$this->assertSame( 'Test_file', $file->getName() );

		$page = $this->getExistingTestPage( 'File:Test_file' );
		$file = $this->newRepo()->newFromArchiveName( $page, 'b' );
		$this->assertInstanceOf( OldLocalFile::class, $file );
		$this->assertSame( 'Test_file', $file->getName() );
	}

	// TODO cleanupDeletedBatch, deletedFileHasKey, hiddenFileHasKey

	public function testCleanupDeletedBatch_sha1Storage() {
		$this->assertEquals( Status::newGood(),
			$this->newRepo( [ 'storageLayout' => 'sha1' ] )->cleanupDeletedBatch( [] ) );
	}

	/**
	 * @param string $input
	 * @param string $expected
	 * @dataProvider provideGetHashFromKey
	 */
	public function testGetHashFromKey( $input, $expected ) {
		$this->assertSame( $expected, LocalRepo::getHashFromKey( $input ) );
	}

	public static function provideGetHashFromKey() {
		return [
			[ '', false ],
			[ '.', false ],
			[ 'a.', 'a' ],
			[ '.b', 'b' ],
			[ '..c', 'c' ],
			[ 'd.x', 'd' ],
			[ '.e.x', 'e' ],
			[ '..f.x', 'f' ],
			[ 'g..x', 'g' ],
			[ '01234567890123456789012345678901.x', '1234567890123456789012345678901' ],
		];
	}

	public function testCheckRedirect_nonRedirect() {
		$this->editPage( 'File:Not a redirect', 'Not a redirect' );
		$this->assertFalse(
			$this->newRepo()->checkRedirect( Title::makeTitle( NS_FILE, 'Not a redirect' ) ) );
	}

	public function testCheckRedirect_redirect() {
		$this->editPage( 'File:Redirect', '#REDIRECT [[File:Target]]' );

		$target = $this->newRepo()->checkRedirect( Title::makeTitle( NS_FILE, 'Redirect' ) );
		$this->assertEquals( 'File:Target', $target->getPrefixedText() );

		$page = $this->getExistingTestPage( 'File:Redirect' );
		$target = $this->newRepo()->checkRedirect( $page );
		$this->assertEquals( 'File:Target', $target->getPrefixedText() );
	}

	public function testCheckRedirectSharedEmptyCache() {
		$dbDomain = WikiMap::getCurrentWikiDbDomain()->getId();
		$mockBag = $this->getMockBuilder( EmptyBagOStuff::class )
			->onlyMethods( [ 'makeKey', 'makeGlobalKey' ] )
			->getMock();
		$mockBag->expects( $this->never() )
			->method( 'makeKey' )
			->with(
				'filerepo-file-redirect', 'local', md5( 'Redirect' )
			);
		$mockBag->expects( $this->once() )
			->method( 'makeGlobalKey' )
			->with(
				'filerepo-file-redirect', $dbDomain, md5( 'Redirect' )
			)->willReturn(
				implode( ':', [ 'filerepo-file-redirect', $dbDomain, md5( 'Redirect' ) ] )
			);

		$wanCache = new WANObjectCache( [ 'cache' => $mockBag ] );
		$repo = $this->newRepo( [ 'wanCache' => $wanCache ] );

		$this->editPage( 'File:Redirect', '#REDIRECT [[File:Target]]' );
		$this->assertEquals( 'File:Target',
			$repo->checkRedirect( Title::makeTitle( NS_FILE, 'Redirect' ) )->getPrefixedText() );
	}

	public function testCheckRedirect_invalidFile() {
		$this->expectException( RuntimeException::class );
		$this->expectExceptionMessage( '`Notafile` is not a valid file title.' );
		$this->newRepo()->checkRedirect( Title::makeTitle( NS_MAIN, 'Notafile' ) );
	}

	public function testFindBySha1() {
		$this->markTestIncomplete( "Haven't figured out how to upload files yet" );

		$repo = $this->newRepo();

		$tmpFileFactory = $this->getServiceContainer()->getTempFSFileFactory();
		foreach ( [ 'File1', 'File2', 'File3' ] as $name ) {
			$fsFile = $tmpFileFactory->newTempFSFile( '' );
			file_put_contents( $fsFile->getPath(), "$name contents" );
			$localFile = $repo->newFile( $name );
			$localFile->upload( $fsFile, 'Uploaded', "$name desc" );
		}
	}

	public function testInvalidateImageRedirect() {
		global $wgTestMe;
		$wgTestMe = true;
		$repo = $this->newRepo(
			[ 'wanCache' => new WANObjectCache( [ 'cache' => new HashBagOStuff ] ) ] );

		$title = Title::makeTitle( NS_FILE, 'Redirect' );

		$this->editPage( 'File:Redirect', '#REDIRECT [[File:Target]]' );

		$this->assertSame( 'File:Target',
			$repo->checkRedirect( $title )->getPrefixedText() );

		$this->editPage( 'File:Redirect', 'No longer a redirect' );

		$this->assertSame( 'File:Target',
			$repo->checkRedirect( $title )->getPrefixedText() );

		$repo->invalidateImageRedirect( $title );

		$this->markTestIncomplete(
			"Can't figure out how to get image redirect validation to take effect" );

		$this->assertSame( false, $repo->checkRedirect( $title ) );
	}

	public function testGetInfo() {
		$this->overrideConfigValues( [
			MainConfigNames::Server => '//example.org',
			MainConfigNames::Favicon => 'https://global.example/favicon.ico',
			MainConfigNames::Sitename => 'Test my site',
		] );

		$repo = $this->newRepo( [ 'favicon' => '/img/favicon.ico' ] );

		$this->assertSame( [
			'name' => 'local',
			'displayname' => 'Test my site',
			'rootUrl' => false,
			'local' => true,
			'url' => false,
			'thumbUrl' => false,
			'initialCapital' => true,
			// This expands to HTTP instead of HTTPS because the test context imitates HTTP
			'favicon' => 'http://example.org/img/favicon.ico',
		], $repo->getInfo() );
	}

	// XXX The following getInfo tests are really testing FileRepo, not LocalRepo, but we want to
	// make sure they're true for LocalRepo too. How should we do this? A trait?

	public function testGetInfo_name() {
		$this->assertSame( 'some-name',
			$this->newRepo( [ 'name' => 'some-name' ] )->getInfo()['name'] );
	}

	public function testGetInfo_displayName() {
		$this->assertSame( wfMessage( 'shared-repo' )->text(),
			$this->newRepo( [ 'name' => 'not-local' ] )->getInfo()['displayname'] );
	}

	public function testGetInfo_displayNameCustomMsg() {
		$this->editPage( 'MediaWiki:Shared-repo-name-not-local', 'Name to display please' );
		// Allow the message to take effect
		$this->getServiceContainer()->getMessageCache()->enable();

		$this->assertSame( 'Name to display please',
			$this->newRepo( [ 'name' => 'not-local' ] )->getInfo()['displayname'] );
	}

	public function testGetInfo_rootUrl() {
		$this->assertSame( 'https://my.url',
			$this->newRepo( [ 'url' => 'https://my.url' ] )->getInfo()['rootUrl'] );
	}

	public function testGetInfo_rootUrlCustomized() {
		$this->assertSame(
			'https://my.url/some/sub/dir',
			$this->newRepo( [
				'url' => 'https://my.url',
				'zones' => [ 'public' => [ 'url' => 'https://my.url/some/sub/dir' ] ],
			] )->getInfo()['rootUrl']
		);
	}

	public function testGetInfo_local() {
		$this->assertFalse( $this->newRepo( [ 'name' => 'not-local' ] )->getInfo()['local'] );
	}

	/**
	 * @param string $setting
	 * @dataProvider provideGetInfo_optionalSettings
	 */
	public function testGetInfo_optionalSettings( $setting ) {
		$this->assertSame( 'dummy test value',
			$this->newRepo( [ $setting => 'dummy test value' ] )->getInfo()[$setting] );
	}

	public static function provideGetInfo_optionalSettings() {
		return [
			[ 'url' ],
			[ 'thumbUrl' ],
			[ 'initialCapital' ],
			[ 'descBaseUrl' ],
			[ 'scriptDirUrl' ],
			[ 'articleUrl' ],
			[ 'fetchDescription' ],
			[ 'descriptionCacheExpiry' ],
		];
	}

	/**
	 * @dataProvider provideSkipWriteOperationIfSha1
	 */
	public function testSkipWriteOperationIfSha1( $method, ...$args ) {
		$repo = $this->newRepo( [ 'storageLayout' => 'sha1' ] );
		$this->assertEquals( Status::newGood(), $repo->$method( ...$args ) );
	}

	public static function provideSkipWriteOperationIfSha1() {
		return [
			[ 'store', '', '', '' ],
			[ 'storeBatch', [ '' ] ],
			[ 'cleanupBatch', [ '' ] ],
			[ 'publish', '', '', '' ],
			[ 'publishBatch', [ '' ] ],
			[ 'delete', '', '' ],
			[ 'deleteBatch', [ '' ] ],
		];
	}
}
