<?php

use MediaWiki\MediaWikiServices;

/**
 * @coversDefaultClass LocalRepo
 * @group Database
 */
class LocalRepoTest extends MediaWikiIntegrationTestCase {
	/**
	 * @param array $extraInfo To pass to LocalRepo constructor
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
	 * @covers ::__construct
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
	 * @covers ::newFileFromRow
	 */
	public function testNewFileFromRow( $prefix, $expectedClass ) {
		$this->editPage( 'File:Test_file', 'Some description' );

		$row = (object)[
			"{$prefix}_name" => 'Test_file',
			// We cheat and include this for img_ too, it will be ignored
			"{$prefix}_archive_name" => 'Archive_name',
			"{$prefix}_user" => '1',
			"{$prefix}_timestamp" => '12345678910111',
			"{$prefix}_metadata" => '',
			"{$prefix}_sha1" => sha1( '' ),
			"{$prefix}_size" => '0',
			"{$prefix}_height" => '0',
			"{$prefix}_width" => '0',
			"{$prefix}_bits" => '0',
			"{$prefix}_description_text" => '',
			"{$prefix}_description_data" => null,
		];
		$file = $this->newRepo()->newFileFromRow( $row );
		$this->assertInstanceOf( $expectedClass, $file );
		$this->assertSame( 'Test_file', $file->getName() );
		$this->assertSame( 1, $file->getUser( 'id' ) );
	}

	public static function provideNewFileFromRow() {
		return [
			'img' => [ 'img', LocalFile::class ],
			'oi' => [ 'oi', OldLocalFile::class ],
		];
	}

	/**
	 * @covers ::__construct
	 * @covers ::newFileFromRow
	 */
	public function testNewFileFromRow_invalid() {
		$this->expectException( MWException::class );
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

	/**
	 * @covers ::__construct
	 * @covers ::newFromArchiveName
	 */
	public function testNewFromArchiveName() {
		$this->editPage( 'File:Test_file', 'Some description' );

		$file = $this->newRepo()->newFromArchiveName( 'Test_file', 'b' );
		$this->assertInstanceOf( OldLocalFile::class, $file );
		$this->assertSame( 'Test_file', $file->getName() );
	}

	// TODO cleanupDeletedBatch, deletedFileHasKey, hiddenFileHasKey

	/**
	 * @covers ::__construct
	 * @covers ::cleanupDeletedBatch
	 */
	public function testCleanupDeletedBatch_sha1Storage() {
		$this->assertEquals( Status::newGood(),
			$this->newRepo( [ 'storageLayout' => 'sha1' ] )->cleanupDeletedBatch( [] ) );
	}

	/**
	 * @param string $input
	 * @param string $expected
	 * @dataProvider provideGetHashFromKey
	 * @covers ::getHashFromKey
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

	/**
	 * @covers ::__construct
	 * @covers ::checkRedirect
	 */
	public function testCheckRedirect_nonRedirect() {
		$this->editPage( 'File:Not a redirect', 'Not a redirect' );
		$this->assertFalse(
			$this->newRepo()->checkRedirect( Title::makeTitle( NS_FILE, 'Not a redirect' ) ) );
	}

	/**
	 * @covers ::__construct
	 * @covers ::checkRedirect
	 * @covers ::getSharedCacheKey
	 */
	public function testCheckRedirect_redirect() {
		$this->editPage( 'File:Redirect', '#REDIRECT [[File:Target]]' );
		$this->assertEquals( 'File:Target',
			$this->newRepo()->checkRedirect( Title::makeTitle( NS_FILE, 'Redirect' ) )
				->getPrefixedText() );
	}

	/**
	 * @covers ::__construct
	 * @covers ::checkRedirect
	 * @covers ::getSharedCacheKey
	 * @covers ::getLocalCacheKey
	 */
	public function testCheckRedirect_redirect_noWANCache() {
		$this->markTestIncomplete( 'WANObjectCache::makeKey is final' );

		$mockWan = $this->getMockBuilder( WANObjectCache::class )
			->setConstructorArgs( [ [ 'cache' => new EmptyBagOStuff ] ] )
			->setMethods( [ 'makeKey' ] )
			->getMock();
		$mockWan->expects( $this->exactly( 2 ) )->method( 'makeKey' )->withConsecutive(
			[ 'file_redirect', md5( 'Redirect' ) ],
			[ 'filerepo', 'local', 'file_redirect', md5( 'Redirect' ) ]
		)->will( $this->onConsecutiveCalls( false, 'somekey' ) );

		$repo = $this->newRepo( [ 'wanCache' => $mockWan ] );

		$this->editPage( 'File:Redirect', '#REDIRECT [[File:Target]]' );
		$this->assertEquals( 'File:Target',
			$repo->checkRedirect( Title::makeTitle( NS_FILE, 'Redirect' ) )->getPrefixedText() );
	}

	/**
	 * @covers ::__construct
	 * @covers ::checkRedirect
	 */
	public function testCheckRedirect_invalidFile() {
		$this->expectException( MWException::class );
		$this->expectExceptionMessage( '`Notafile` is not a valid file title.' );
		$this->newRepo()->checkRedirect( Title::makeTitle( NS_MAIN, 'Notafile' ) );
	}

	/**
	 * @covers ::__construct
	 * @covers ::findBySha1
	 */
	public function testFindBySha1() {
		$this->markTestIncomplete( "Haven't figured out how to upload files yet" );

		$repo = $this->newRepo();

		$tmpFileFactory = MediaWikiServices::getInstance()->getTempFSFileFactory();
		foreach ( [ 'File1', 'File2', 'File3' ] as $name ) {
			$fsFile = $tmpFileFactory->newTempFSFile( '' );
			file_put_contents( $fsFile->getPath(), "$name contents" );
			$localFile = $repo->newFile( $name );
			$localFile->upload( $fsFile, 'Uploaded', "$name desc" );
		}
	}

	/**
	 * @covers ::__construct
	 * @covers ::getSharedCacheKey
	 * @covers ::checkRedirect
	 * @covers ::invalidateImageRedirect
	 */
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

	/**
	 * @covers ::getInfo
	 */
	public function testGetInfo() {
		$this->setMwGlobals( [
			'wgFavicon' => '//example.com/favicon.ico',
			'wgSitename' => 'Test my site',
		] );

		$repo = $this->newRepo( [ 'favicon' => 'Hey, this option is ignored in LocalRepo!' ] );

		$this->assertSame( [
			'name' => 'local',
			'displayname' => 'Test my site',
			'rootUrl' => false,
			'local' => true,
			'url' => false,
			'thumbUrl' => false,
			'initialCapital' => true,
			// XXX This assumes protocol-relative will get expanded to http instead of https
			'favicon' => 'http://example.com/favicon.ico',
		], $repo->getInfo() );
	}

	// XXX The following getInfo tests are really testing FileRepo, not LocalRepo, but we want to
	// make sure they're true for LocalRepo too. How should we do this? A trait?

	/**
	 * @covers ::getInfo
	 */
	public function testGetInfo_name() {
		$this->assertSame( 'some-name',
			$this->newRepo( [ 'name' => 'some-name' ] )->getInfo()['name'] );
	}

	/**
	 * @covers ::getInfo
	 */
	public function testGetInfo_displayName() {
		$this->assertSame( wfMessage( 'shared-repo' )->text(),
			$this->newRepo( [ 'name' => 'not-local' ] )->getInfo()['displayname'] );
	}

	/**
	 * @covers ::getInfo
	 */
	public function testGetInfo_displayNameCustomMsg() {
		$this->editPage( 'MediaWiki:Shared-repo-name-not-local', 'Name to display please' );
		// Allow the message to take effect
		MediaWikiServices::getInstance()->getMessageCache()->enable();

		$this->assertSame( 'Name to display please',
			$this->newRepo( [ 'name' => 'not-local' ] )->getInfo()['displayname'] );
	}

	/**
	 * @covers ::getInfo
	 */
	public function testGetInfo_rootUrl() {
		$this->assertSame( 'https://my.url',
			$this->newRepo( [ 'url' => 'https://my.url' ] )->getInfo()['rootUrl'] );
	}

	/**
	 * @covers ::getInfo
	 */
	public function testGetInfo_rootUrlCustomized() {
		$this->assertSame(
			'https://my.url/some/sub/dir',
			$this->newRepo( [
				'url' => 'https://my.url',
				'zones' => [ 'public' => [ 'url' => 'https://my.url/some/sub/dir' ] ],
			] )->getInfo()['rootUrl']
		);
	}

	/**
	 * @covers ::getInfo
	 */
	public function testGetInfo_local() {
		$this->assertFalse( $this->newRepo( [ 'name' => 'not-local' ] )->getInfo()['local'] );
	}

	/**
	 * @param string $setting
	 * @dataProvider provideGetInfo_optionalSettings
	 * @covers ::getInfo
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
	 * @param string $method
	 * @param mixed ...$args
	 * @dataProvider provideSkipWriteOperationIfSha1
	 * @covers ::store
	 * @covers ::storeBatch
	 * @covers ::cleanupBatch
	 * @covers ::publish
	 * @covers ::publishBatch
	 * @covers ::delete
	 * @covers ::deleteBatch
	 * @covers ::skipWriteOperationIfSha1
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
