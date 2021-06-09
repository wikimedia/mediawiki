<?php

/**
 * These tests should work regardless of $wgCapitalLinks
 * @todo Split tests into providers and test methods
 */

use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\Authority;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\User\UserIdentity;

/**
 * @group Database
 */
class LocalFileTest extends MediaWikiIntegrationTestCase {
	use MockAuthorityTrait;

	protected function setUp(): void {
		parent::setUp();
		$this->tablesUsed[] = 'image';
		$this->tablesUsed[] = 'oldimage';
	}

	private static function getDefaultInfo() {
		return [
			'name' => 'test',
			'directory' => '/testdir',
			'url' => '/testurl',
			'hashLevels' => 2,
			'transformVia404' => false,
			'backend' => new FSFileBackend( [
				'name' => 'local-backend',
				'wikiId' => wfWikiID(),
				'containerPaths' => [
					'cont1' => "/testdir/local-backend/tempimages/cont1",
					'cont2' => "/testdir/local-backend/tempimages/cont2"
				]
			] )
		];
	}

	/**
	 * @covers File::getHashPath
	 * @dataProvider provideGetHashPath
	 * @param string $expected
	 * @param bool $capitalLinks
	 * @param array $info
	 */
	public function testGetHashPath( $expected, $capitalLinks, array $info ) {
		$this->setMwGlobals( 'wgCapitalLinks', $capitalLinks );
		$this->assertSame( $expected, ( new LocalRepo( $info + self::getDefaultInfo() ) )
			->newFile( 'test!' )->getHashPath() );
	}

	public static function provideGetHashPath() {
		return [
			[ '', true, [ 'hashLevels' => 0 ] ],
			[ 'a/a2/', true, [ 'hashLevels' => 2 ] ],
			[ 'c/c4/', false, [ 'initialCapital' => false ] ],
		];
	}

	/**
	 * @covers File::getRel
	 * @dataProvider provideGetRel
	 * @param string $expected
	 * @param bool $capitalLinks
	 * @param array $info
	 */
	public function testGetRel( $expected, $capitalLinks, array $info ) {
		$this->setMwGlobals( 'wgCapitalLinks', $capitalLinks );

		$this->assertSame( $expected, ( new LocalRepo( $info + self::getDefaultInfo() ) )
			->newFile( 'test!' )->getRel() );
	}

	public static function provideGetRel() {
		return [
			[ 'Test!', true, [ 'hashLevels' => 0 ] ],
			[ 'a/a2/Test!', true, [ 'hashLevels' => 2 ] ],
			[ 'c/c4/test!', false, [ 'initialCapital' => false ] ],
		];
	}

	/**
	 * @covers File::getUrlRel
	 * @dataProvider provideGetUrlRel
	 * @param string $expected
	 * @param bool $capitalLinks
	 * @param array $info
	 */
	public function testGetUrlRel( $expected, $capitalLinks, array $info ) {
		$this->setMwGlobals( 'wgCapitalLinks', $capitalLinks );

		$this->assertSame( $expected, ( new LocalRepo( $info + self::getDefaultInfo() ) )
			->newFile( 'test!' )->getUrlRel() );
	}

	public static function provideGetUrlRel() {
		return [
			[ 'Test%21', true, [ 'hashLevels' => 0 ] ],
			[ 'a/a2/Test%21', true, [ 'hashLevels' => 2 ] ],
			[ 'c/c4/test%21', false, [ 'initialCapital' => false ] ],
		];
	}

	/**
	 * @covers File::getArchivePath
	 * @dataProvider provideGetArchivePath
	 * @param string $expected
	 * @param bool $capitalLinks
	 * @param array $info
	 * @param array $args
	 */
	public function testGetArchivePath( $expected, $capitalLinks, array $info, array $args ) {
		$this->setMwGlobals( 'wgCapitalLinks', $capitalLinks );

		$this->assertSame( $expected, ( new LocalRepo( $info + self::getDefaultInfo() ) )
			->newFile( 'test!' )->getArchivePath( ...$args ) );
	}

	public static function provideGetArchivePath() {
		return [
			[ 'mwstore://local-backend/test-public/archive', true, [ 'hashLevels' => 0 ], [] ],
			[ 'mwstore://local-backend/test-public/archive/a/a2', true, [ 'hashLevels' => 2 ], [] ],
			[
				'mwstore://local-backend/test-public/archive/!',
				true, [ 'hashLevels' => 0 ], [ '!' ]
			], [
				'mwstore://local-backend/test-public/archive/a/a2/!',
				true, [ 'hashLevels' => 2 ], [ '!' ]
			],
		];
	}

	/**
	 * @covers File::getThumbPath
	 * @dataProvider provideGetThumbPath
	 * @param string $expected
	 * @param bool $capitalLinks
	 * @param array $info
	 * @param array $args
	 */
	public function testGetThumbPath( $expected, $capitalLinks, array $info, array $args ) {
		$this->setMwGlobals( 'wgCapitalLinks', $capitalLinks );

		$this->assertSame( $expected, ( new LocalRepo( $info + self::getDefaultInfo() ) )
			->newFile( 'test!' )->getThumbPath( ...$args ) );
	}

	public static function provideGetThumbPath() {
		return [
			[ 'mwstore://local-backend/test-thumb/Test!', true, [ 'hashLevels' => 0 ], [] ],
			[ 'mwstore://local-backend/test-thumb/a/a2/Test!', true, [ 'hashLevels' => 2 ], [] ],
			[
				'mwstore://local-backend/test-thumb/Test!/x',
				true, [ 'hashLevels' => 0 ], [ 'x' ]
			], [
				'mwstore://local-backend/test-thumb/a/a2/Test!/x',
				true, [ 'hashLevels' => 2 ], [ 'x' ]
			],
		];
	}

	/**
	 * @covers File::getArchiveUrl
	 * @dataProvider provideGetArchiveUrl
	 * @param string $expected
	 * @param bool $capitalLinks
	 * @param array $info
	 * @param array $args
	 */
	public function testGetArchiveUrl( $expected, $capitalLinks, array $info, array $args ) {
		$this->setMwGlobals( 'wgCapitalLinks', $capitalLinks );

		$this->assertSame( $expected, ( new LocalRepo( $info + self::getDefaultInfo() ) )
			->newFile( 'test!' )->getArchiveUrl( ...$args ) );
	}

	public static function provideGetArchiveUrl() {
		return [
			[ '/testurl/archive', true, [ 'hashLevels' => 0 ], [] ],
			[ '/testurl/archive/a/a2', true, [ 'hashLevels' => 2 ], [] ],
			[ '/testurl/archive/%21', true, [ 'hashLevels' => 0 ], [ '!' ] ],
			[ '/testurl/archive/a/a2/%21', true, [ 'hashLevels' => 2 ], [ '!' ] ],
		];
	}

	/**
	 * @covers File::getThumbUrl
	 * @dataProvider provideGetThumbUrl
	 * @param string $expected
	 * @param bool $capitalLinks
	 * @param array $info
	 * @param array $args
	 */
	public function testGetThumbUrl( $expected, $capitalLinks, array $info, array $args ) {
		$this->setMwGlobals( 'wgCapitalLinks', $capitalLinks );

		$this->assertSame( $expected, ( new LocalRepo( $info + self::getDefaultInfo() ) )
			->newFile( 'test!' )->getThumbUrl( ...$args ) );
	}

	public static function provideGetThumbUrl() {
		return [
			[ '/testurl/thumb/Test%21', true, [ 'hashLevels' => 0 ], [] ],
			[ '/testurl/thumb/a/a2/Test%21', true, [ 'hashLevels' => 2 ], [] ],
			[ '/testurl/thumb/Test%21/x', true, [ 'hashLevels' => 0 ], [ 'x' ] ],
			[ '/testurl/thumb/a/a2/Test%21/x', true, [ 'hashLevels' => 2 ], [ 'x' ] ],
		];
	}

	/**
	 * @covers File::getArchiveVirtualUrl
	 * @dataProvider provideGetArchiveVirtualUrl
	 * @param string $expected
	 * @param bool $capitalLinks
	 * @param array $info
	 * @param array $args
	 */
	public function testGetArchiveVirtualUrl(
		$expected, $capitalLinks, array $info, array $args
	) {
		$this->setMwGlobals( 'wgCapitalLinks', $capitalLinks );

		$this->assertSame( $expected, ( new LocalRepo( $info + self::getDefaultInfo() ) )
			->newFile( 'test!' )->getArchiveVirtualUrl( ...$args ) );
	}

	public static function provideGetArchiveVirtualUrl() {
		return [
			[ 'mwrepo://test/public/archive', true, [ 'hashLevels' => 0 ], [] ],
			[ 'mwrepo://test/public/archive/a/a2', true, [ 'hashLevels' => 2 ], [] ],
			[ 'mwrepo://test/public/archive/%21', true, [ 'hashLevels' => 0 ], [ '!' ] ],
			[ 'mwrepo://test/public/archive/a/a2/%21', true, [ 'hashLevels' => 2 ], [ '!' ] ],
		];
	}

	/**
	 * @covers File::getThumbVirtualUrl
	 * @dataProvider provideGetThumbVirtualUrl
	 * @param string $expected
	 * @param bool $capitalLinks
	 * @param array $info
	 * @param array $args
	 */
	public function testGetThumbVirtualUrl( $expected, $capitalLinks, array $info, array $args ) {
		$this->setMwGlobals( 'wgCapitalLinks', $capitalLinks );

		$this->assertSame( $expected, ( new LocalRepo( $info + self::getDefaultInfo() ) )
			->newFile( 'test!' )->getThumbVirtualUrl( ...$args ) );
	}

	public static function provideGetThumbVirtualUrl() {
		return [
			[ 'mwrepo://test/thumb/Test%21', true, [ 'hashLevels' => 0 ], [] ],
			[ 'mwrepo://test/thumb/a/a2/Test%21', true, [ 'hashLevels' => 2 ], [] ],
			[ 'mwrepo://test/thumb/Test%21/%21', true, [ 'hashLevels' => 0 ], [ '!' ] ],
			[ 'mwrepo://test/thumb/a/a2/Test%21/%21', true, [ 'hashLevels' => 2 ], [ '!' ] ],
		];
	}

	/**
	 * @covers File::getUrl
	 * @dataProvider provideGetUrl
	 * @param string $expected
	 * @param bool $capitalLinks
	 * @param array $info
	 */
	public function testGetUrl( $expected, $capitalLinks, array $info ) {
		$this->setMwGlobals( 'wgCapitalLinks', $capitalLinks );

		$this->assertSame( $expected, ( new LocalRepo( $info + self::getDefaultInfo() ) )
			->newFile( 'test!' )->getUrl() );
	}

	public static function provideGetUrl() {
		return [
			[ '/testurl/Test%21', true, [ 'hashLevels' => 0 ] ],
			[ '/testurl/a/a2/Test%21', true, [ 'hashLevels' => 2 ] ],
		];
	}

	/**
	 * @covers ::wfLocalFile
	 */
	public function testWfLocalFile() {
		$this->hideDeprecated( 'wfLocalFile' );
		$file = wfLocalFile( "File:Some_file_that_probably_doesn't exist.png" );
		$this->assertInstanceOf(
			LocalFile::class,
			$file,
			'wfLocalFile() returns LocalFile for valid Titles'
		);
	}

	/**
	 * @covers File::getUser
	 */
	public function testGetUserForNonExistingFile() {
		$this->hideDeprecated( 'File::getUser' );
		$file = ( new LocalRepo( self::getDefaultInfo() ) )->newFile( 'test!' );
		$this->assertSame( 'Unknown user', $file->getUser() );
	}

	/**
	 * @covers LocalFile::getUploader
	 */
	public function testGetUploaderForNonExistingFile() {
		$file = ( new LocalRepo( self::getDefaultInfo() ) )->newFile( 'test!' );
		$this->assertNull( $file->getUploader() );
	}

	public function providePermissionChecks() {
		$capablePerformer = $this->mockAnonAuthorityWithPermissions( [ 'deletedhistory', 'deletedtext' ] );
		$incapablePerformer = $this->mockAnonAuthorityWithoutPermissions( [ 'deletedhistory', 'deletedtext' ] );
		yield 'Deleted, RAW' => [
			'performer' => $incapablePerformer,
			'audience' => File::RAW,
			'deleted' => File::DELETED_USER | File::DELETED_COMMENT,
			'expected' => true,
		];
		yield 'No permission, not deleted' => [
			'performer' => $incapablePerformer,
			'audience' => File::FOR_THIS_USER,
			'deleted' => 0,
			'expected' => true,
		];
		yield 'No permission, deleted' => [
			'performer' => $incapablePerformer,
			'audience' => File::FOR_THIS_USER,
			'deleted' => File::DELETED_USER | File::DELETED_COMMENT,
			'expected' => false,
		];
		yield 'Not deleted, public' => [
			'performer' => $capablePerformer,
			'audience' => File::FOR_PUBLIC,
			'deleted' => 0,
			'expected' => true,
		];
		yield 'Deleted, public' => [
			'performer' => $capablePerformer,
			'audience' => File::FOR_PUBLIC,
			'deleted' => File::DELETED_USER | File::DELETED_COMMENT,
			'expected' => false,
		];
		yield 'With permission, deleted' => [
			'performer' => $capablePerformer,
			'audience' => File::FOR_THIS_USER,
			'deleted' => File::DELETED_USER | File::DELETED_COMMENT,
			'expected' => true,
		];
	}

	private function getOldLocalFileWithDeletion(
		UserIdentity $uploader,
		int $deletedFlags
	): OldLocalFile {
		$this->db->insert(
			'oldimage',
			[
				'oi_name' => 'Random-11m.png',
				'oi_archive_name' => 'Random-11m.png',
				'oi_size' => 10816824,
				'oi_width' => 1000,
				'oi_height' => 1800,
				'oi_metadata' => '',
				'oi_bits' => 16,
				'oi_media_type' => 'BITMAP',
				'oi_major_mime' => 'image',
				'oi_minor_mime' => 'png',
				'oi_description_id' => $this->getServiceContainer()
					->getCommentStore()
					->createComment( $this->db, 'comment' )->id,
				'oi_actor' => $this->getServiceContainer()
					->getActorStore()
					->acquireActorId( $uploader, $this->db ),
				'oi_timestamp' => $this->db->timestamp( '20201105235242' ),
				'oi_sha1' => 'sy02psim0bgdh0jt4vdltuzoh7j80ru',
				'oi_deleted' => $deletedFlags,
			]
		);
		$file = OldLocalFile::newFromTitle(
			Title::newFromText( 'File:Random-11m.png' ),
			$this->getServiceContainer()->getRepoGroup()->getLocalRepo(),
			'20201105235242'
		);
		$this->assertInstanceOf( File::class, $file, 'Sanity: created a test file' );
		return $file;
	}

	private function getArchivedFileWithDeletion(
		UserIdentity $uploader,
		int $deletedFlags
	): ArchivedFile {
		return ArchivedFile::newFromRow( (object)[
				'fa_id' => 1,
				'fa_storage_group' => 'test',
				'fa_storage_key' => 'bla',
				'fa_name' => 'Random-11m.png',
				'fa_archive_name' => 'Random-11m.png',
				'fa_size' => 10816824,
				'fa_width' => 1000,
				'fa_height' => 1800,
				'fa_metadata' => '',
				'fa_bits' => 16,
				'fa_media_type' => 'BITMAP',
				'fa_major_mime' => 'image',
				'fa_minor_mime' => 'png',
				'fa_description_id' => $this->getServiceContainer()
					->getCommentStore()
					->createComment( $this->db, 'comment' )->id,
				'fa_actor' => $this->getServiceContainer()
					->getActorStore()
					->acquireActorId( $uploader, $this->db ),
				'fa_user' => $uploader->getId(),
				'fa_user_text' => $uploader->getName(),
				'fa_timestamp' => $this->db->timestamp( '20201105235242' ),
				'fa_sha1' => 'sy02psim0bgdh0jt4vdltuzoh7j80ru',
				'fa_deleted' => $deletedFlags,
			]
		);
	}

	/**
	 * @dataProvider providePermissionChecks
	 * @covers LocalFile::getUploader
	 */
	public function testGetUploader(
		Authority $performer,
		int $audience,
		int $deleted,
		bool $expected
	) {
		$file = $this->getOldLocalFileWithDeletion( $performer->getUser(), $deleted );
		if ( $expected ) {
			$this->assertTrue( $performer->getUser()->equals( $file->getUploader( $audience, $performer ) ) );
		} else {
			$this->assertNull( $file->getUploader( $audience, $performer ) );
		}
	}

	/**
	 * @dataProvider providePermissionChecks
	 * @covers ArchivedFile::getDescription
	 */
	public function testGetDescription(
		Authority $performer,
		int $audience,
		int $deleted,
		bool $expected
	) {
		$file = $this->getArchivedFileWithDeletion( $performer->getUser(), $deleted );
		if ( $expected ) {
			$this->assertSame( 'comment', $file->getDescription( $audience, $performer ) );
		} else {
			$this->assertSame( '', $file->getDescription( $audience, $performer ) );
		}
	}

	/**
	 * @dataProvider providePermissionChecks
	 * @covers ArchivedFile::getUploader
	 */
	public function testArchivedGetUploader(
		Authority $performer,
		int $audience,
		int $deleted,
		bool $expected
	) {
		$file = $this->getArchivedFileWithDeletion( $performer->getUser(), $deleted );
		if ( $expected ) {
			$this->assertTrue( $performer->getUser()->equals( $file->getUploader( $audience, $performer ) ) );
		} else {
			$this->assertNull( $file->getUploader( $audience, $performer ) );
		}
	}

	/**
	 * @dataProvider providePermissionChecks
	 * @covers LocalFile::getDescription
	 */
	public function testArchivedGetDescription(
		Authority $performer,
		int $audience,
		int $deleted,
		bool $expected
	) {
		$file = $this->getOldLocalFileWithDeletion( $performer->getUser(), $deleted );
		if ( $expected ) {
			$this->assertSame( 'comment', $file->getDescription( $audience, $performer ) );
		} else {
			$this->assertSame( '', $file->getDescription( $audience, $performer ) );
		}
	}

	/**
	 * @covers File::getDescriptionShortUrl
	 */
	public function testDescriptionShortUrlForNonExistingFile() {
		$file = ( new LocalRepo( self::getDefaultInfo() ) )->newFile( 'test!' );
		$this->assertNull( $file->getDescriptionShortUrl() );
	}

	/**
	 * @covers File::getDescriptionText
	 */
	public function testDescriptionTextForNonExistingFile() {
		$file = ( new LocalRepo( self::getDefaultInfo() ) )->newFile( 'test!' );
		$this->assertFalse( $file->getDescriptionText() );
	}

	/**
	 * @covers File
	 * @covers LocalFile
	 */
	public function testLoadFromDBAndCache() {
		$services = MediaWikiServices::getInstance();

		$cache = new HashBagOStuff;
		$this->setService(
			'MainWANObjectCache',
			new WANObjectCache( [
				'cache' => $cache
			] )
		);

		$dbw = wfGetDB( DB_PRIMARY );
		$norm = $services->getActorNormalization();
		$user = $this->getTestSysop()->getUserIdentity();
		$actorId = $norm->acquireActorId( $user, $dbw );
		$comment = $services->getCommentStore()->createComment( $dbw, 'comment' );
		$title = Title::newFromText( 'File:Random-11m.png' );

		// phpcs:ignore Generic.Files.LineLength
		$meta = 'a:6:{s:10:"frameCount";i:0;s:9:"loopCount";i:1;s:8:"duration";d:0;s:8:"bitDepth";i:16;s:9:"colorType";s:10:"truecolour";s:8:"metadata";a:2:{s:8:"DateTime";s:19:"2019:07:30 13:52:32";s:15:"_MW_PNG_VERSION";i:1;}}';

		$dbw->insert(
			'image',
			[
				'img_name' => 'Random-11m.png',
				'img_size' => 10816824,
				'img_width' => 1000,
				'img_height' => 1800,
				'img_metadata' => $meta,
				'img_bits' => 16,
				'img_media_type' => 'BITMAP',
				'img_major_mime' => 'image',
				'img_minor_mime' => 'png',
				'img_description_id' => $comment->id,
				'img_actor' => $actorId,
				'img_timestamp' => $dbw->timestamp( '20201105235242' ),
				'img_sha1' => 'sy02psim0bgdh0jt4vdltuzoh7j80ru',
			]
		);
		$repo = $services->getRepoGroup()->getLocalRepo();
		$file = $repo->findFile( $title );

		$this->assertSame( 'Random-11m.png', $file->getName() );
		$this->assertSame( 10816824, $file->getSize() );
		$this->assertSame( 1000, $file->getWidth() );
		$this->assertSame( 1800, $file->getHeight() );
		$this->assertSame( unserialize( $meta ), $file->getMetadataArray() );
		$this->assertSame( 'truecolour', $file->getMetadataItem( 'colorType' ) );
		$this->assertSame(
			[ 'loopCount' => 1, 'bitDepth' => 16 ],
			$file->getMetadataItems( [ 'loopCount', 'bitDepth', 'nonexistent' ] )
		);
		$this->assertSame( 16, $file->getBitDepth() );
		$this->assertSame( 'BITMAP', $file->getMediaType() );
		$this->assertSame( 'image/png', $file->getMimeType() );
		$this->assertSame( 'comment', $file->getDescription() );
		$this->assertTrue( $user->equals( $file->getUploader() ) );
		$this->assertSame( '20201105235242', $file->getTimestamp() );
		$this->assertSame( 'sy02psim0bgdh0jt4vdltuzoh7j80ru', $file->getSha1() );

		// Test cache
		$dbw->delete( 'image', [ 'img_name' => 'Random-11m.png' ], __METHOD__ );
		$file = LocalFile::newFromTitle( $title, $repo );

		$this->assertSame( 'Random-11m.png', $file->getName() );
		$this->assertSame( 10816824, $file->getSize() );
		$this->assertSame( 1000, $file->getWidth() );
		$this->assertSame( 1800, $file->getHeight() );
		$this->assertSame( unserialize( $meta ), $file->getMetadataArray() );
		$this->assertSame( 'truecolour', $file->getMetadataItem( 'colorType' ) );
		$this->assertSame(
			[ 'loopCount' => 1, 'bitDepth' => 16 ],
			$file->getMetadataItems( [ 'loopCount', 'bitDepth', 'nonexistent' ] )
		);
		$this->assertSame( 16, $file->getBitDepth() );
		$this->assertSame( 'BITMAP', $file->getMediaType() );
		$this->assertSame( 'image/png', $file->getMimeType() );
		$this->assertSame( 'comment', $file->getDescription() );
		$this->assertTrue( $user->equals( $file->getUploader() ) );
		$this->assertSame( '20201105235242', $file->getTimestamp() );
		$this->assertSame( 'sy02psim0bgdh0jt4vdltuzoh7j80ru', $file->getSha1() );

		// Make sure we were actually hitting the WAN cache
		$cache->clear();
		$file = $repo->findFile( $title );
		$this->assertSame( false, $file );
	}

	public function provideLegacyMetadataRoundTrip() {
		return [
			[ '0' ],
			[ '-1' ],
			[ '' ]
		];
	}

	/**
	 * Test the legacy function LocalFile::getMetadata()
	 * @dataProvider provideLegacyMetadataRoundTrip
	 * @covers LocalFile
	 */
	public function testLegacyMetadataRoundTrip( $meta ) {
		$file = new class( $meta ) extends LocalFile {
			public function __construct( $meta ) {
				$repo = MediaWikiServices::getInstance()->getRepoGroup()->getLocalRepo();
				parent::__construct(
					Title::newFromText( 'File:TestLegacyMetadataRoundTrip' ),
					$repo );
				$this->loadMetadataFromString( $meta );
				$this->dataLoaded = true;
			}
		};
		$this->assertSame( $meta, $file->getMetadata() );
	}
}
