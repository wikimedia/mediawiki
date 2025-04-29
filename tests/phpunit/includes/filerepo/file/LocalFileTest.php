<?php

/**
 * These tests should work regardless of $wgCapitalLinks
 * @todo Split tests into providers and test methods
 */

use MediaWiki\FileRepo\File\ArchivedFile;
use MediaWiki\FileRepo\File\File;
use MediaWiki\FileRepo\File\LocalFile;
use MediaWiki\FileRepo\File\OldLocalFile;
use MediaWiki\FileRepo\LocalRepo;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\Event\PageRevisionUpdatedEvent;
use MediaWiki\Permissions\Authority;
use MediaWiki\Tests\ExpectCallbackTrait;
use MediaWiki\Tests\recentchanges\ChangeTrackingUpdateSpyTrait;
use MediaWiki\Tests\Search\SearchUpdateSpyTrait;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\Title\Title;
use MediaWiki\User\UserIdentity;
use MediaWiki\WikiMap\WikiMap;
use PHPUnit\Framework\Assert;
use Wikimedia\ArrayUtils\ArrayUtils;
use Wikimedia\FileBackend\FSFileBackend;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Database
 */
class LocalFileTest extends MediaWikiIntegrationTestCase {
	use MockAuthorityTrait;
	use ChangeTrackingUpdateSpyTrait;
	use SearchUpdateSpyTrait;
	use ExpectCallbackTrait;

	private static function getDefaultInfo() {
		return [
			'name' => 'test',
			'directory' => '/testdir',
			'url' => '/testurl',
			'hashLevels' => 2,
			'transformVia404' => false,
			'backend' => new FSFileBackend( [
				'name' => 'local-backend',
				'wikiId' => WikiMap::getCurrentWikiId(),
				'containerPaths' => [
					'cont1' => "/testdir/local-backend/tempimages/cont1",
					'cont2' => "/testdir/local-backend/tempimages/cont2"
				]
			] )
		];
	}

	/**
	 * @covers \MediaWiki\FileRepo\File\File::getHashPath
	 * @dataProvider provideGetHashPath
	 * @param string $expected
	 * @param bool $capitalLinks
	 * @param array $info
	 */
	public function testGetHashPath( $expected, $capitalLinks, array $info ) {
		$this->overrideConfigValue( MainConfigNames::CapitalLinks, $capitalLinks );
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
	 * @covers \MediaWiki\FileRepo\File\File::getRel
	 * @dataProvider provideGetRel
	 * @param string $expected
	 * @param bool $capitalLinks
	 * @param array $info
	 */
	public function testGetRel( $expected, $capitalLinks, array $info ) {
		$this->overrideConfigValue( MainConfigNames::CapitalLinks, $capitalLinks );

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
	 * @covers \MediaWiki\FileRepo\File\File::getUrlRel
	 * @dataProvider provideGetUrlRel
	 * @param string $expected
	 * @param bool $capitalLinks
	 * @param array $info
	 */
	public function testGetUrlRel( $expected, $capitalLinks, array $info ) {
		$this->overrideConfigValue( MainConfigNames::CapitalLinks, $capitalLinks );

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
	 * @covers \MediaWiki\FileRepo\File\File::getArchivePath
	 * @dataProvider provideGetArchivePath
	 * @param string $expected
	 * @param bool $capitalLinks
	 * @param array $info
	 * @param array $args
	 */
	public function testGetArchivePath( $expected, $capitalLinks, array $info, array $args ) {
		$this->overrideConfigValue( MainConfigNames::CapitalLinks, $capitalLinks );

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
	 * @covers \MediaWiki\FileRepo\File\File::getThumbPath
	 * @dataProvider provideGetThumbPath
	 * @param string $expected
	 * @param bool $capitalLinks
	 * @param array $info
	 * @param array $args
	 */
	public function testGetThumbPath( $expected, $capitalLinks, array $info, array $args ) {
		$this->overrideConfigValue( MainConfigNames::CapitalLinks, $capitalLinks );

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
	 * @covers \MediaWiki\FileRepo\File\File::getArchiveUrl
	 * @dataProvider provideGetArchiveUrl
	 * @param string $expected
	 * @param bool $capitalLinks
	 * @param array $info
	 * @param array $args
	 */
	public function testGetArchiveUrl( $expected, $capitalLinks, array $info, array $args ) {
		$this->overrideConfigValue( MainConfigNames::CapitalLinks, $capitalLinks );

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
	 * @covers \MediaWiki\FileRepo\File\File::getThumbUrl
	 * @dataProvider provideGetThumbUrl
	 * @param string $expected
	 * @param bool $capitalLinks
	 * @param array $info
	 * @param array $args
	 */
	public function testGetThumbUrl( $expected, $capitalLinks, array $info, array $args ) {
		$this->overrideConfigValue( MainConfigNames::CapitalLinks, $capitalLinks );

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
	 * @covers \MediaWiki\FileRepo\File\File::getArchiveVirtualUrl
	 * @dataProvider provideGetArchiveVirtualUrl
	 * @param string $expected
	 * @param bool $capitalLinks
	 * @param array $info
	 * @param array $args
	 */
	public function testGetArchiveVirtualUrl(
		$expected, $capitalLinks, array $info, array $args
	) {
		$this->overrideConfigValue( MainConfigNames::CapitalLinks, $capitalLinks );

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
	 * @covers \MediaWiki\FileRepo\File\File::getThumbVirtualUrl
	 * @dataProvider provideGetThumbVirtualUrl
	 * @param string $expected
	 * @param bool $capitalLinks
	 * @param array $info
	 * @param array $args
	 */
	public function testGetThumbVirtualUrl( $expected, $capitalLinks, array $info, array $args ) {
		$this->overrideConfigValue( MainConfigNames::CapitalLinks, $capitalLinks );

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
	 * @covers \MediaWiki\FileRepo\File\File::getUrl
	 * @dataProvider provideGetUrl
	 * @param string $expected
	 * @param bool $capitalLinks
	 * @param array $info
	 */
	public function testGetUrl( $expected, $capitalLinks, array $info ) {
		$this->overrideConfigValue( MainConfigNames::CapitalLinks, $capitalLinks );

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
	 * @covers \MediaWiki\FileRepo\File\LocalFile::getUploader
	 */
	public function testGetUploaderForNonExistingFile() {
		$file = ( new LocalRepo( self::getDefaultInfo() ) )->newFile( 'test!' );
		$this->assertNull( $file->getUploader() );
	}

	public function providePermissionChecks() {
		$capablePerformer = $this->mockRegisteredAuthorityWithPermissions( [ 'deletedhistory', 'deletedtext' ] );
		$incapablePerformer = $this->mockRegisteredAuthorityWithoutPermissions( [ 'deletedhistory', 'deletedtext' ] );
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
		$this->getDb()->newInsertQueryBuilder()
			->insertInto( 'oldimage' )
			->row( [
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
					->createComment( $this->getDb(), 'comment' )->id,
				'oi_actor' => $this->getServiceContainer()
					->getActorStore()
					->acquireActorId( $uploader, $this->getDb() ),
				'oi_timestamp' => $this->getDb()->timestamp( '20201105235242' ),
				'oi_sha1' => 'sy02psim0bgdh0jt4vdltuzoh7j80ru',
				'oi_deleted' => $deletedFlags,
			] )
			->caller( __METHOD__ )
			->execute();
		$file = OldLocalFile::newFromTitle(
			Title::makeTitle( NS_FILE, 'Random-11m.png' ),
			$this->getServiceContainer()->getRepoGroup()->getLocalRepo(),
			'20201105235242'
		);
		$this->assertInstanceOf( File::class, $file, 'Created a test file' );
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
					->createComment( $this->getDb(), 'comment' )->id,
				'fa_actor' => $this->getServiceContainer()
					->getActorStore()
					->acquireActorId( $uploader, $this->getDb() ),
				'fa_user' => $uploader->getId(),
				'fa_user_text' => $uploader->getName(),
				'fa_timestamp' => $this->getDb()->timestamp( '20201105235242' ),
				'fa_sha1' => 'sy02psim0bgdh0jt4vdltuzoh7j80ru',
				'fa_deleted' => $deletedFlags,
			]
		);
	}

	/**
	 * @dataProvider providePermissionChecks
	 * @covers \MediaWiki\FileRepo\File\LocalFile::getUploader
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
	 * @covers \MediaWiki\FileRepo\File\ArchivedFile::getDescription
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
	 * @covers \MediaWiki\FileRepo\File\ArchivedFile::getUploader
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
	 * @covers \MediaWiki\FileRepo\File\LocalFile::getDescription
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
	 * @covers \MediaWiki\FileRepo\File\File::getDescriptionShortUrl
	 */
	public function testDescriptionShortUrlForNonExistingFile() {
		$file = ( new LocalRepo( self::getDefaultInfo() ) )->newFile( 'test!' );
		$this->assertNull( $file->getDescriptionShortUrl() );
	}

	/**
	 * @covers \MediaWiki\FileRepo\File\LocalFile::getDescriptionText
	 */
	public function testDescriptionText_NonExisting() {
		$file = ( new LocalRepo( self::getDefaultInfo() ) )->newFile( 'test!' );
		$this->assertFalse( $file->getDescriptionText() );
	}

	/**
	 * @covers \MediaWiki\FileRepo\File\LocalFile::getDescriptionText
	 */
	public function testDescriptionText_Existing() {
		$this->assertTrue( $this->editPage(
			__METHOD__,
			'TEST CONTENT',
			'',
			NS_FILE
		)->isOK() );
		$file = ( new LocalRepo( self::getDefaultInfo() ) )->newFile( __METHOD__ );
		$this->assertStringContainsString( 'TEST CONTENT', $file->getDescriptionText() );
	}

	public static function provideLoadFromDBAndCache() {
		return [
			'legacy' => [
				'a:6:{s:10:"frameCount";i:0;s:9:"loopCount";i:1;s:8:"duration";d:0;s:8:"bitDepth";i:16;s:9:"colorType";s:10:"truecolour";s:8:"metadata";a:2:{s:8:"DateTime";s:19:"2019:07:30 13:52:32";s:15:"_MW_PNG_VERSION";i:1;}}',
				[],
				false,
			],
			'json' => [
				'{"data":{"frameCount":0,"loopCount":1,"duration":0,"bitDepth":16,"colorType":"truecolour","metadata":{"DateTime":"2019:07:30 13:52:32","_MW_PNG_VERSION":1}}}',
				[],
				false,
			],
			'json with blobs' => [
				'{"blobs":{"colorType":"__BLOB0__"},"data":{"frameCount":0,"loopCount":1,"duration":0,"bitDepth":16,"metadata":{"DateTime":"2019:07:30 13:52:32","_MW_PNG_VERSION":1}}}',
				[ '"truecolour"' ],
				false,
			],
			'large (>100KB triggers uncached case)' => [
				'{"data":{"large":"' . str_repeat( 'x', 102401 ) . '","frameCount":0,"loopCount":1,"duration":0,"bitDepth":16,"colorType":"truecolour","metadata":{"DateTime":"2019:07:30 13:52:32","_MW_PNG_VERSION":1}}}',
				[],
				102401,
			],
			'large json blob' => [
				'{"blobs":{"large":"__BLOB0__"},"data":{"frameCount":0,"loopCount":1,"duration":0,"bitDepth":16,"colorType":"truecolour","metadata":{"DateTime":"2019:07:30 13:52:32","_MW_PNG_VERSION":1}}}',
				[ '"' . str_repeat( 'x', 102401 ) . '"' ],
				102401,
			],
		];
	}

	/**
	 * Test loadFromDB() and loadFromCache() and helpers
	 *
	 * @dataProvider provideLoadFromDBAndCache
	 * @covers \MediaWiki\FileRepo\File\File
	 * @covers \MediaWiki\FileRepo\File\LocalFile
	 * @param string $meta
	 * @param array $blobs Metadata blob values
	 * @param int|false $largeItemSize The size of the "large" metadata item,
	 *   or false if there will be no such item.
	 */
	public function testLoadFromDBAndCache( $meta, $blobs, $largeItemSize ) {
		$services = $this->getServiceContainer();
		$dbw = $this->getDb();
		$norm = $services->getActorNormalization();
		$user = $this->getTestSysop()->getUserIdentity();
		$actorId = $norm->acquireActorId( $user, $dbw );
		$comment = $services->getCommentStore()->createComment( $dbw, 'comment' );
		$title = Title::makeTitle( NS_FILE, 'Random-11m.png' );

		if ( $blobs ) {
			$blobStore = $services->getBlobStore();
			foreach ( $blobs as $i => $value ) {
				$address = $blobStore->storeBlob( $value );
				$meta = str_replace( "__BLOB{$i}__", $address, $meta );
			}
		}

		// The provided metadata strings should all unserialize to this
		$expectedMetaArray = [
			'frameCount' => 0,
			'loopCount' => 1,
			'duration' => 0.0,
			'bitDepth' => 16,
			'colorType' => 'truecolour',
			'metadata' => [
				'DateTime' => '2019:07:30 13:52:32',
				'_MW_PNG_VERSION' => 1,
			],
		];
		if ( $largeItemSize ) {
			$expectedMetaArray['large'] = str_repeat( 'x', $largeItemSize );
		}
		$expectedProps = [
			'name' => 'Random-11m.png',
			'size' => 10816824,
			'width' => 1000,
			'height' => 1800,
			'metadata' => $expectedMetaArray,
			'bits' => 16,
			'media_type' => 'BITMAP',
			'mime' => 'image/png',
			'timestamp' => '20201105235242',
			'sha1' => 'sy02psim0bgdh0jt4vdltuzoh7j80ru'
		];

		$dbw->newInsertQueryBuilder()
			->insertInto( 'image' )
			->row( [
				'img_name' => 'Random-11m.png',
				'img_size' => 10816824,
				'img_width' => 1000,
				'img_height' => 1800,
				'img_metadata' => $dbw->encodeBlob( $meta ),
				'img_bits' => 16,
				'img_media_type' => 'BITMAP',
				'img_major_mime' => 'image',
				'img_minor_mime' => 'png',
				'img_description_id' => $comment->id,
				'img_actor' => $actorId,
				'img_timestamp' => $dbw->timestamp( '20201105235242' ),
				'img_sha1' => 'sy02psim0bgdh0jt4vdltuzoh7j80ru',
			] )
			->caller( __METHOD__ )
			->execute();
		$repo = $services->getRepoGroup()->getLocalRepo();
		$file = $repo->findFile( $title );

		$this->assertFileProperties( $expectedProps, $file );
		$this->assertSame( 'truecolour', $file->getMetadataItem( 'colorType' ) );
		$this->assertSame(
			[ 'loopCount' => 1, 'bitDepth' => 16 ],
			$file->getMetadataItems( [ 'loopCount', 'bitDepth', 'nonexistent' ] )
		);
		$this->assertSame( 'comment', $file->getDescription() );
		$this->assertTrue( $user->equals( $file->getUploader() ) );

		// Test cache by corrupting DB
		// Don't wipe img_metadata though since that will be loaded by loadExtraFromDB()
		$dbw->newUpdateQueryBuilder()
			->update( 'image' )
			->set( [ 'img_size' => 0 ] )
			->where( [ 'img_name' => 'Random-11m.png' ] )
			->caller( __METHOD__ )->execute();
		$file = LocalFile::newFromTitle( $title, $repo );

		$this->assertFileProperties( $expectedProps, $file );
		$this->assertSame( 'truecolour', $file->getMetadataItem( 'colorType' ) );
		$this->assertSame(
			[ 'loopCount' => 1, 'bitDepth' => 16 ],
			$file->getMetadataItems( [ 'loopCount', 'bitDepth', 'nonexistent' ] )
		);
		$this->assertSame( 'comment', $file->getDescription() );
		$this->assertTrue( $user->equals( $file->getUploader() ) );

		// Make sure we were actually hitting the WAN cache
		$dbw->newDeleteQueryBuilder()
			->deleteFrom( 'image' )
			->where( [ 'img_name' => 'Random-11m.png' ] )
			->caller( __METHOD__ )->execute();
		$file->invalidateCache();
		$file = LocalFile::newFromTitle( $title, $repo );
		$this->assertSame( false, $file->exists() );
	}

	private function assertFileProperties( $expectedProps, $file ) {
		// Compare metadata without ordering
		if ( isset( $expectedProps['metadata'] ) ) {
			$this->assertArrayEquals( $expectedProps['metadata'], $file->getMetadataArray() );
		}

		// Filter out unsupported expected properties
		$expectedProps = array_intersect_key(
			$expectedProps,
			array_fill_keys( [
				'name', 'size', 'width', 'height',
				'bits', 'media_type', 'mime', 'timestamp', 'sha1'
			], true )
		);

		// Compare the other properties
		$actualProps = [
			'name' => $file->getName(),
			'size' => $file->getSize(),
			'width' => $file->getWidth(),
			'height' => $file->getHeight(),
			'bits' => $file->getBitDepth(),
			'media_type' => $file->getMediaType(),
			'mime' => $file->getMimeType(),
			'timestamp' => $file->getTimestamp(),
			'sha1' => $file->getSha1()
		];
		$actualProps = array_intersect_key( $actualProps, $expectedProps );
		$this->assertArrayEquals( $expectedProps, $actualProps, false, true );
	}

	public static function provideLegacyMetadataRoundTrip() {
		return [
			[ '0' ],
			[ '-1' ],
			[ '' ]
		];
	}

	/**
	 * Test the legacy function LocalFile::getMetadata()
	 * @dataProvider provideLegacyMetadataRoundTrip
	 * @covers \MediaWiki\FileRepo\File\LocalFile
	 */
	public function testLegacyMetadataRoundTrip( $meta ) {
		$file = new class( $meta ) extends LocalFile {
			public function __construct( $meta ) {
				$repo = MediaWikiServices::getInstance()->getRepoGroup()->getLocalRepo();
				parent::__construct(
					Title::makeTitle( NS_FILE, 'TestLegacyMetadataRoundTrip' ),
					$repo );
				$this->loadMetadataFromString( $meta );
				$this->dataLoaded = true;
			}
		};
		$this->assertSame( $meta, $file->getMetadata() );
	}

	public static function provideRecordUpload3() {
		$files = [
			'test.jpg' => [
				'width' => 20,
				'height' => 20,
				'bits' => 8,
				'metadata' => [
					'ImageDescription' => 'Test file',
					'XResolution' => '72/1',
					'YResolution' => '72/1',
					'ResolutionUnit' => 2,
					'YCbCrPositioning' => 1,
					'JPEGFileComment' => [
						'Created with GIMP',
					],
					'MEDIAWIKI_EXIF_VERSION' => 2,
				],
				'fileExists' => true,
				'size' => 437,
				'file-mime' => 'image/jpeg',
				'major_mime' => 'image',
				'minor_mime' => 'jpeg',
				'mime' => 'image/jpeg',
				'sha1' => '620ezvucfyia1mltnavzpqg9gmai2gf',
				'media_type' => 'BITMAP',
			],
			'large-text.pdf' => [
				'width' => 1275,
				'height' => 1650,
				'fileExists' => true,
				'size' => 10598657,
				'file-mime' => 'application/pdf',
				'major_mime' => 'application',
				'minor_mime' => 'pdf',
				'mime' => 'application/pdf',
				'sha1' => '1o3l1yqjue2diq07grnnyq9kyapfpor',
				'bits' => 0,
				'media_type' => 'OFFICE',
				'metadata' => [
					'Pages' => '6',
					'text' => [
						'Page 1 text .................................',
						'Page 2 text .................................',
						'Page 3 text .................................',
						'Page 4 text .................................',
						'Page 5 text .................................',
						'Page 6 text .................................',
					]
				]
			],
			'no-text.pdf' => [
				'width' => 1275,
				'height' => 1650,
				'fileExists' => true,
				'size' => 10598657,
				'file-mime' => 'application/pdf',
				'major_mime' => 'application',
				'minor_mime' => 'pdf',
				'mime' => 'application/pdf',
				'sha1' => '1o3l1yqjue2diq07grnnyq9kyapfpor',
				'bits' => 0,
				'media_type' => 'OFFICE',
				'metadata' => [
					'Pages' => '6',
				]
			]
		];
		$configurations = [
			[],
			[ 'useJsonMetadata' => true ],
			[
				'useJsonMetadata' => true,
				'useSplitMetadata' => true,
				'splitMetadataThreshold' => 50
			]
		];
		return ArrayUtils::cartesianProduct( $files, $configurations );
	}

	private function getMockPdfHandler() {
		return new class extends ImageHandler {
			public function doTransform( $image, $dstPath, $dstUrl, $params, $flags = 0 ) {
			}

			public function useSplitMetadata() {
				return true;
			}
		};
	}

	/**
	 * Test recordUpload3() and confirm that file properties are reflected back
	 * after loading the new file from the DB.
	 *
	 * @covers \MediaWiki\FileRepo\File\LocalFile
	 * @dataProvider provideRecordUpload3
	 * @param array $props File properties
	 * @param array $conf LocalRepo configuration overrides
	 */
	public function testRecordUpload3( $props, $conf ) {
		$repo = new LocalRepo(
			[
				'class' => LocalRepo::class,
				'name' => 'test',
				'backend' => new FSFileBackend( [
					'name' => 'test-backend',
					'wikiId' => WikiMap::getCurrentWikiId(),
					'basePath' => '/nonexistent'
				] )
			] + $conf
		);
		$title = Title::makeTitle( NS_FILE, 'Test.jpg' );
		$file = new LocalFile( $title, $repo );

		if ( $props['mime'] === 'application/pdf' ) {
			TestingAccessWrapper::newFromObject( $file )->handler = $this->getMockPdfHandler();
		}

		$status = $file->recordUpload3(
			'oldver',
			'comment',
			'page text',
			$this->getTestSysop()->getUser(),
			$props
		);
		$this->assertStatusGood( $status );
		// Check properties of the same object immediately after upload
		$this->assertFileProperties( $props, $file );
		// Check round-trip through the DB
		$file = new LocalFile( $title, $repo );
		$this->assertFileProperties( $props, $file );
	}

	/**
	 * @covers \MediaWiki\FileRepo\File\LocalFile
	 */
	public function testUpload() {
		$repo = $this->getLocalRepoForUpload();
		$title = Title::makeTitle( NS_FILE, 'Test.jpg' );
		$file = new LocalFile( $title, $repo );
		$path = __DIR__ . '/../../../data/media/test.jpg';
		$status = $file->upload(
			$path,
			'comment',
			'page text',
			0,
			false,
			false,
			$this->getTestUser()->getUser()
		);
		$this->assertStatusGood( $status );

		// Test reupload
		$file = new LocalFile( $title, $repo );
		$path = __DIR__ . '/../../../data/media/jpeg-xmp-nullchar.jpg';
		$status = $file->upload(
			$path,
			'comment',
			'page text',
			0,
			false,
			false,
			$this->getTestUser()->getUser()
		);
		$this->assertStatusGood( $status );
	}

	/**
	 * @covers \MediaWiki\FileRepo\File\LocalFile
	 */
	public function testUpload_updatePropagation() {
		// Clear some extension hook handlers that may interfere with mock object expectations.
		$this->clearHooks( [
			'PageSaveComplete',
			'RevisionRecordInserted',
		] );

		// Expect two non-edit recent changes entries but only one edit count.
		$this->expectChangeTrackingUpdates( 0, 2, 1, 0, 1 );

		// Expect only one search update, the re-upload doesn't change the page.
		$this->expectSearchUpdates( 1 );

		// now upload
		$repo = $this->getLocalRepoForUpload();
		$title = Title::makeTitle( NS_FILE, 'Test.jpg' );
		$file = new LocalFile( $title, $repo );
		$path = __DIR__ . '/../../../data/media/test.jpg';
		$status = $file->upload(
			$path,
			'comment',
			'page text',
			0,
			false,
			false,
			$this->getTestUser()->getUser()
		);
		$this->assertStatusGood( $status );

		// now upload again
		$file = new LocalFile( $title, $repo );
		$path = __DIR__ . '/../../../data/media/jpeg-xmp-nullchar.jpg';
		$status = $file->upload(
			$path,
			'comment',
			'page text',
			0,
			false,
			false,
			$this->getTestUser()->getUser()
		);
		$this->assertStatusGood( $status );
	}

	/**
	 * @covers \MediaWiki\FileRepo\File\LocalFile
	 */
	public function testUpload_eventEmission() {
		$repo = $this->getLocalRepoForUpload();
		$title = Title::makeTitle( NS_FILE, 'Test.jpg' );
		$file = new LocalFile( $title, $repo );

		// Event emitted by PageUpdater::saveRevision
		$this->expectDomainEvent(
			PageRevisionUpdatedEvent::TYPE, 1,
			static function ( PageRevisionUpdatedEvent $event ) use ( &$calls, $file ) {
				Assert::assertSame( $file->getName(), $event->getPage()->getDBkey() );

				Assert::assertTrue( $event->isCreation(), 'isCreation' );
				Assert::assertTrue( $event->changedLatestRevisionId(), 'changedLatestRevisionId' );
				Assert::assertTrue( $event->isEffectiveContentChange(), 'isEffectiveContentChange' );
				Assert::assertTrue( $event->isNominalContentChange(), 'isNominalContentChange' );

				Assert::assertTrue(
					$event->hasCause( PageRevisionUpdatedEvent::CAUSE_UPLOAD ),
					PageRevisionUpdatedEvent::CAUSE_UPLOAD
				);

				Assert::assertTrue( $event->isSilent(), 'isSilent' );
				Assert::assertFalse( $event->isImplicit(), 'isImplicit' );
			}
		);

		// Hooks fired by PageUpdater::saveRevision
		$this->expectHook( 'RevisionFromEditComplete' );
		$this->expectHook( 'PageSaveComplete' );

		// now upload
		$path = __DIR__ . '/../../../data/media/test.jpg';
		$status = $file->upload(
			$path,
			'comment',
			'page text',
			0,
			false,
			false,
			$this->getTestUser()->getUser()
		);
		$this->assertStatusGood( $status );
	}

	/**
	 * @covers \MediaWiki\FileRepo\File\LocalFile
	 */
	public function testReUpload_eventEmission() {
		// initial upload
		$repo = $this->getLocalRepoForUpload();
		$title = Title::makeTitle( NS_FILE, 'Test.jpg' );
		$file = new LocalFile( $title, $repo );
		$path = __DIR__ . '/../../../data/media/test.jpg';
		$status = $file->upload(
			$path,
			'comment',
			'page text',
			0,
			false,
			false,
			$this->getTestUser()->getUser()
		);
		$this->assertStatusGood( $status );

		// flush the queue
		$this->runDeferredUpdates();

		$this->expectDomainEvent(
			PageRevisionUpdatedEvent::TYPE, 1,
			static function ( PageRevisionUpdatedEvent $event ) use ( &$calls, $file ) {
				Assert::assertSame( $file->getName(), $event->getPage()->getDBkey() );

				Assert::assertFalse( $event->isCreation(), 'isCreation' );
				Assert::assertTrue( $event->changedLatestRevisionId(), 'changedLatestRevisionId' );
				Assert::assertFalse( $event->isEffectiveContentChange(), 'isEffectiveContentChange' );
				Assert::assertFalse( $event->isNominalContentChange(), 'isNominalContentChange' );

				Assert::assertTrue(
					$event->hasCause( PageRevisionUpdatedEvent::CAUSE_UPLOAD ),
					PageRevisionUpdatedEvent::CAUSE_UPLOAD
				);

				Assert::assertTrue( $event->isSilent(), 'isSilent' );
				Assert::assertTrue( $event->isImplicit(), 'isImplicit' );

				Assert::assertFalse(
					$event->getLatestRevisionAfter()->isMinor(),
					'isMinor'
				);
			}
		);

		// Hooks fired by PageUpdater
		$this->expectHook( 'RevisionFromEditComplete' );
		$this->expectHook( 'PageSaveComplete' );

		// now upload again
		$file = new LocalFile( $title, $repo );
		$path = __DIR__ . '/../../../data/media/jpeg-xmp-nullchar.jpg';
		$status = $file->upload(
			$path,
			'comment',
			'page text',
			0,
			false,
			false,
			$this->getTestUser()->getUser()
		);
		$this->assertStatusGood( $status );
	}

	public static function provideReserializeMetadata() {
		return [
			[
				'',
				''
			],
			[
				'a:1:{s:4:"test";i:1;}',
				'{"data":{"test":1}}'
			],
			[
				serialize( [ 'test' => str_repeat( 'x', 100 ) ] ),
				'{"data":[],"blobs":{"test":"tt:%d"}}'
			]
		];
	}

	/**
	 * Test reserializeMetadata() via maybeUpgradeRow()
	 *
	 * @covers \MediaWiki\FileRepo\File\LocalFile::maybeUpgradeRow
	 * @covers \MediaWiki\FileRepo\File\LocalFile::reserializeMetadata
	 * @dataProvider provideReserializeMetadata
	 */
	public function testReserializeMetadata( $input, $expected ) {
		$dbw = $this->getDb();
		$services = $this->getServiceContainer();
		$norm = $services->getActorNormalization();
		$user = $this->getTestSysop()->getUserIdentity();
		$actorId = $norm->acquireActorId( $user, $dbw );
		$comment = $services->getCommentStore()->createComment( $dbw, 'comment' );

		$dbw->newInsertQueryBuilder()
			->insertInto( 'image' )
			->row( [
				'img_name' => 'Test.pdf',
				'img_size' => 1,
				'img_width' => 1,
				'img_height' => 1,
				'img_metadata' => $dbw->encodeBlob( $input ),
				'img_bits' => 0,
				'img_media_type' => 'OFFICE',
				'img_major_mime' => 'application',
				'img_minor_mime' => 'pdf',
				'img_description_id' => $comment->id,
				'img_actor' => $actorId,
				'img_timestamp' => $dbw->timestamp( '20201105235242' ),
				'img_sha1' => 'hhhh',
			] )
			->caller( __METHOD__ )
			->execute();

		$repo = new LocalRepo( [
			'class' => LocalRepo::class,
			'name' => 'test',
			'useJsonMetadata' => true,
			'useSplitMetadata' => true,
			'splitMetadataThreshold' => 50,
			'updateCompatibleMetadata' => true,
			'reserializeMetadata' => true,
			'backend' => new FSFileBackend( [
				'name' => 'test-backend',
				'wikiId' => WikiMap::getCurrentWikiId(),
				'basePath' => '/nonexistent'
			] )
		] );
		$title = Title::makeTitle( NS_FILE, 'Test.pdf' );
		$file = new LocalFile( $title, $repo );
		TestingAccessWrapper::newFromObject( $file )->handler = $this->getMockPdfHandler();
		$file->load();
		$file->maybeUpgradeRow();

		$metadata = $dbw->decodeBlob( $dbw->newSelectQueryBuilder()
			->select( 'img_metadata' )
			->from( 'image' )
			->where( [ 'img_name' => 'Test.pdf' ] )
			->caller( __METHOD__ )->fetchField()
		);
		$this->assertStringMatchesFormat( $expected, $metadata );
	}

	/**
	 * Test upgradeRow() via maybeUpgradeRow()
	 *
	 * @covers \MediaWiki\FileRepo\File\LocalFile::maybeUpgradeRow
	 * @covers \MediaWiki\FileRepo\File\LocalFile::upgradeRow
	 */
	public function testUpgradeRow() {
		$repo = new LocalRepo( [
			'class' => LocalRepo::class,
			'name' => 'test',
			'updateCompatibleMetadata' => true,
			'useJsonMetadata' => true,
			'hashLevels' => 0,
			'backend' => new FSFileBackend( [
				'name' => 'test-backend',
				'wikiId' => WikiMap::getCurrentWikiId(),
				'containerPaths' => [ 'test-public' => __DIR__ . '/../../../data/media' ]
			] )
		] );
		$dbw = $this->getDb();
		$services = $this->getServiceContainer();
		$norm = $services->getActorNormalization();
		$user = $this->getTestSysop()->getUserIdentity();
		$actorId = $norm->acquireActorId( $user, $dbw );
		$comment = $services->getCommentStore()->createComment( $dbw, 'comment' );

		$dbw->newInsertQueryBuilder()
			->insertInto( 'image' )
			->row( [
				'img_name' => 'Png-native-test.png',
				'img_size' => 1,
				'img_width' => 1,
				'img_height' => 1,
				'img_metadata' => $dbw->encodeBlob( 'a:1:{s:8:"metadata";a:1:{s:15:"_MW_PNG_VERSION";i:0;}}' ),
				'img_bits' => 0,
				'img_media_type' => 'OFFICE',
				'img_major_mime' => 'image',
				'img_minor_mime' => 'png',
				'img_description_id' => $comment->id,
				'img_actor' => $actorId,
				'img_timestamp' => $dbw->timestamp( '20201105235242' ),
				'img_sha1' => 'hhhh',
			] )
			->caller( __METHOD__ )
			->execute();

		$title = Title::makeTitle( NS_FILE, 'Png-native-test.png' );
		$file = new LocalFile( $title, $repo );
		$file->load();
		$file->maybeUpgradeRow();
		$metadata = $dbw->decodeBlob( $dbw->newSelectQueryBuilder()
			->select( 'img_metadata' )
			->from( 'image' )
			->where( [ 'img_name' => 'Png-native-test.png' ] )
			->fetchField()
		);
		// Just confirm that it looks like JSON with real metadata
		$this->assertStringStartsWith( '{"data":{"frameCount":0,', $metadata );

		$file = new LocalFile( $title, $repo );
		$this->assertFileProperties(
			[
				'size' => 4665,
				'width' => 420,
				'height' => 300,
				'sha1' => '3n69qtiaif1swp3kyfueqjtmw2u4c2b',
				'bits' => 8,
				'media_type' => 'BITMAP',
			],
			$file );
	}

	/**
	 * @return LocalRepo
	 */
	private function getLocalRepoForUpload(): LocalRepo {
		$repo = new LocalRepo(
			[
				'class' => LocalRepo::class,
				'name' => 'test',
				'backend' => new FSFileBackend( [
					'name' => 'test-backend',
					'wikiId' => WikiMap::getCurrentWikiId(),
					'basePath' => $this->getNewTempDirectory()
				] )
			]
		);

		return $repo;
	}
}
