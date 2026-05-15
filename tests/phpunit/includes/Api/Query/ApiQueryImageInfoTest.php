<?php

namespace MediaWiki\Tests\Api\Query;

use MediaWiki\Api\ApiQueryImageInfo;
use MediaWiki\Context\RequestContext;
use MediaWiki\FileRepo\File\File;
use MediaWiki\MainConfigNames;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Tests\Api\ApiTestCase;
use MediaWiki\Tests\FileRepo\TestRepoTrait;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use MediaWiki\Utils\MWTimestamp;
use Wikimedia\Timestamp\TimestampFormat as TS;

/**
 * @covers \MediaWiki\Api\ApiQueryImageInfo
 * @group API
 * @group medium
 * @group Database
 */
class ApiQueryImageInfoTest extends ApiTestCase {
	use MockAuthorityTrait;
	use TempUserTestTrait;
	use TestRepoTrait;

	private const IMAGES_DIR = __DIR__ . '/../../../data/media';

	private const IMAGE_NAME = 'Random-11m.png';

	private const OLD_IMAGE_TIMESTAMP = '20201105235241';

	private const NEW_IMAGE_TIMESTAMP = '20201105235242';

	private const OLD_IMAGE_SIZE = 12345;

	private const NEW_IMAGE_SIZE = 54321;

	private const NO_COMMENT_TIMESTAMP = '20201105235239';

	// T426802: A "lost" old file revision where the storage blob is missing
	private const LOST_FILE_TIMESTAMP = '20201105235240';
	private const LOST_FILE_SIZE = 99999;

	private const IMAGE_2_NAME = 'Random-2.png';
	private const IMAGE_2_TIMESTAMP = '20230101000000';
	private const IMAGE_2_SIZE = 12345;

	/** @var UserIdentity */
	private $testUser = null;
	/** @var User */
	private $tempUser = null;

	protected function setUp(): void {
		ApiQueryImageInfo::resetTransformCountForUnitTest();
		parent::setUp();
	}

	public function tearDown(): void {
		self::destroyTestRepo();
		parent::tearDown();
	}

	public function addDBData() {
		parent::addDBData();

		$this->initTestRepoGroup();

		$this->testUser = new UserIdentityValue( 12364321, 'Dummy User' );

		$actorId = $this->getServiceContainer()
			->getActorStore()
			->acquireActorId( $this->testUser, $this->getDb() );
		$this->getDb()->newInsertQueryBuilder()
			->insertInto( 'image' )
			->row( [
				'img_name' => 'Random-11m.png',
				'img_size' => self::NEW_IMAGE_SIZE,
				'img_width' => 1000,
				'img_height' => 1800,
				'img_metadata' => '',
				'img_bits' => 16,
				'img_media_type' => 'BITMAP',
				'img_major_mime' => 'image',
				'img_minor_mime' => 'png',
				'img_description_id' => $this->getServiceContainer()
					->getCommentStore()
					->createComment( $this->getDb(), "'''comment'''" )->id,
				'img_actor' => $actorId,
				'img_timestamp' => $this->getDb()->timestamp( self::NEW_IMAGE_TIMESTAMP ),
				'img_sha1' => 'sy02psim0bgdh0jt4vdltuzoh7j80ru',
			] )
			->caller( __METHOD__ )
			->execute();
		$this->getDb()->newInsertQueryBuilder()
			->insertInto( 'oldimage' )
			->row( [
				'oi_name' => 'Random-11m.png',
				'oi_archive_name' => self::OLD_IMAGE_TIMESTAMP . 'Random-11m.png',
				'oi_size' => self::OLD_IMAGE_SIZE,
				'oi_width' => 1000,
				'oi_height' => 1800,
				'oi_metadata' => '',
				'oi_bits' => 16,
				'oi_media_type' => 'BITMAP',
				'oi_major_mime' => 'image',
				'oi_minor_mime' => 'png',
				'oi_description_id' => $this->getServiceContainer()
					->getCommentStore()
					->createComment( $this->getDb(), 'deleted comment' )->id,
				'oi_actor' => $actorId,
				'oi_timestamp' => $this->getDb()->timestamp( self::OLD_IMAGE_TIMESTAMP ),
				'oi_sha1' => 'sy02psim0bgdh0jt4vdltuzoh7j80ru',
				'oi_deleted' => File::DELETED_FILE | File::DELETED_COMMENT | File::DELETED_USER,
			] )
			->row( [
				'oi_name' => 'Random-11m.png',
				'oi_archive_name' => self::NO_COMMENT_TIMESTAMP . 'Random-11m.png',
				'oi_size' => self::OLD_IMAGE_SIZE,
				'oi_width' => 1000,
				'oi_height' => 1800,
				'oi_metadata' => '',
				'oi_bits' => 16,
				'oi_media_type' => 'BITMAP',
				'oi_major_mime' => 'image',
				'oi_minor_mime' => 'png',
				'oi_description_id' => $this->getServiceContainer()
					->getCommentStore()
					->createComment( $this->getDb(), '' )->id,
				'oi_actor' => $actorId,
				'oi_timestamp' => $this->getDb()->timestamp( self::NO_COMMENT_TIMESTAMP ),
				'oi_sha1' => 'sy02psim0bgdh0jt4vdltuzoh7j80ru',
				'oi_deleted' => 0,
			] )
			->caller( __METHOD__ )
			->execute();

		// T426802: Insert a "lost" old file revision with empty archive name.
		// This simulates a file where the storage blob is missing but the
		// DB record still exists.
		$this->getDb()->newInsertQueryBuilder()
			->insertInto( 'oldimage' )
			->row( [
				'oi_name' => 'Random-11m.png',
				'oi_archive_name' => '',
				'oi_size' => self::LOST_FILE_SIZE,
				'oi_width' => 800,
				'oi_height' => 600,
				'oi_metadata' => '',
				'oi_bits' => 16,
				'oi_media_type' => 'BITMAP',
				'oi_major_mime' => 'image',
				'oi_minor_mime' => 'png',
				'oi_description_id' => $this->getServiceContainer()
					->getCommentStore()
					->createComment( $this->getDb(), 'lost file comment' )->id,
				'oi_actor' => $actorId,
				'oi_timestamp' => $this->getDb()->timestamp( self::LOST_FILE_TIMESTAMP ),
				'oi_sha1' => 'sy02psim0bgdh0jt4vdltuzoh7j80ru',
				'oi_deleted' => 0,
			] )
			->caller( __METHOD__ )
			->execute();

		// Set up temp user config
		$this->enableAutoCreateTempUser();
		$this->tempUser = $this->getServiceContainer()
			->getTempUserCreator()
			->create( null, new FauxRequest() )->getUser();
		$tempActorId = $this->getServiceContainer()
			->getActorStore()
			->acquireActorId( $this->tempUser, $this->getDb() );
		$this->getDb()->newInsertQueryBuilder()
			->insertInto( 'image' )
			->row( [
				'img_name' => self::IMAGE_2_NAME,
				'img_size' => self::IMAGE_2_SIZE,
				'img_width' => 1000,
				'img_height' => 1800,
				'img_metadata' => '',
				'img_bits' => 16,
				'img_media_type' => 'BITMAP',
				'img_major_mime' => 'image',
				'img_minor_mime' => 'png',
				'img_description_id' => $this->getServiceContainer()
					->getCommentStore()
					->createComment( $this->getDb(), "'''comment'''" )->id,
				'img_actor' => $tempActorId,
				'img_timestamp' => $this->getDb()->timestamp( self::IMAGE_2_TIMESTAMP ),
				'img_sha1' => 'aaaaasim0bgdh0jt4vdltuzoh7',
			] )
			->caller( __METHOD__ )
			->execute();
	}

	private function getImageInfoFromResult( array $result ) {
		$this->assertArrayHasKey( 'query', $result );
		$this->assertArrayHasKey( 'pages', $result['query'] );
		$this->assertArrayHasKey( '-1', $result['query']['pages'] );
		$info = $result['query']['pages']['-1'];
		$this->assertSame( NS_FILE, $info['ns'] );
		$this->assertSame( 'File:' . self::IMAGE_NAME, $info['title'] );
		$this->assertTrue( $info['missing'] );
		$this->assertTrue( $info['known'] );
		$this->assertSame( 'test', $info['imagerepository'] );
		$this->assertFalse( $info['badfile'] );
		$this->assertIsArray( $info['imageinfo'] );
		return $info['imageinfo'][0];
	}

	public function testGetImageInfoLatestImage() {
		[ $result, ] = $this->doApiRequest( [
			'action' => 'query',
			'prop' => 'imageinfo',
			'titles' => 'File:' . self::IMAGE_NAME,
			'iiprop' => implode( '|', ApiQueryImageInfo::getPropertyNames() ),
			'iistart' => self::NEW_IMAGE_TIMESTAMP,
			'iiend' => self::NEW_IMAGE_TIMESTAMP,
		] );
		$image = $this->getImageInfoFromResult( $result );
		$this->assertSame( MWTimestamp::convert( TS::ISO_8601, self::NEW_IMAGE_TIMESTAMP ), $image['timestamp'] );
		$this->assertSame( "'''comment'''", $image['comment'] );
		$this->assertSame( $this->testUser->getName(), $image['user'] );
		$this->assertSame( $this->testUser->getId(), $image['userid'] );
		$this->assertSame( self::NEW_IMAGE_SIZE, $image['size'] );
	}

	public function testGetImageInfoThumburlsFromStepsPortrait() {
		$this->overrideConfigValues( [
			MainConfigNames::Server => 'http://example.com',
			MainConfigNames::ThumbnailSteps => [ 20, 40, 120, 250 ],
		] );
		RequestContext::getMain()->setUser( $this->getTestUser()->getUser() );
		// Original portrait is 120x160
		$this->importFileToTestRepo( self::IMAGES_DIR . '/portrait-rotated.jpg', 'Portrait-rotated.jpg' );

		[ $result, ] = $this->doApiRequest( [
			'action' => 'query',
			'prop' => 'imageinfo',
			'titles' => 'File:Portrait-rotated.jpg',
			'iiprop' => 'thumburls',
		] );

		$info = $result['query']['pages']['1'];
		$image = $info['imageinfo'][0];
		$this->assertEquals( [
			20 => [ 'width' => 20, 'height' => 27, 'url' => 'http://example.com/w/thumb.php?f=Portrait-rotated.jpg&width=20' ],
			40 => [ 'width' => 40, 'height' => 53, 'url' => 'http://example.com/w/thumb.php?f=Portrait-rotated.jpg&width=40' ]
		], $image['thumburls'] );
	}

	public function testGetImageInfoThumburlsFromStepsLandscape() {
		$this->overrideConfigValues( [
			MainConfigNames::Server => 'http://example.com',
			MainConfigNames::ThumbnailSteps => [ 20, 40, 120, 250 ],
		] );
		RequestContext::getMain()->setUser( $this->getTestUser()->getUser() );
		// Original landscape is 160x120
		$this->importFileToTestRepo( self::IMAGES_DIR . '/landscape-plain.jpg', 'Landscape-plain.jpg' );

		[ $result, ] = $this->doApiRequest( [
			'action' => 'query',
			'prop' => 'imageinfo',
			'titles' => 'File:Landscape-plain.jpg',
			'iiprop' => 'thumburls',
		] );

		$info = $result['query']['pages']['1'];
		$image = $info['imageinfo'][0];
		$this->assertEquals( [
			20 => [ 'width' => 20, 'height' => 15, 'url' => 'http://example.com/w/thumb.php?f=Landscape-plain.jpg&width=20' ],
			40 => [ 'width' => 40, 'height' => 30, 'url' => 'http://example.com/w/thumb.php?f=Landscape-plain.jpg&width=40' ],
			120 => [ 'width' => 120, 'height' => 90, 'url' => 'http://example.com/w/thumb.php?f=Landscape-plain.jpg&width=120' ]
		], $image['thumburls'] );
	}

	public function testGetImageInfoThumburlsFromUnionPortrait() {
		$this->overrideConfigValues( [
			MainConfigNames::Server => 'http://example.com',
			MainConfigNames::ThumbnailSteps => null,
			MainConfigNames::ImageLimits => [
				[ 32, 24 ],
				[ 128, 96 ],
				[ 256, 192 ],
			],
			MainConfigNames::ThumbLimits => [
				30,
				40,
				110,
			],
			MainConfigNames::ResponsiveImages => true,
		] );
		$this->mergeMwGlobalArrayValue( 'wgDefaultUserOptions', [ 'thumbsize' => 1 ] );
		RequestContext::getMain()->setUser( $this->getTestUser()->getUser() );
		// Original is 160x120
		$this->importFileToTestRepo( self::IMAGES_DIR . '/portrait-rotated.jpg', 'Portrait-rotated.jpg' );

		[ $result, ] = $this->doApiRequest( [
			'action' => 'query',
			'prop' => 'imageinfo',
			'titles' => 'File:Portrait-rotated.jpg',
			'iiprop' => 'thumburls',
		] );

		$info = $result['query']['pages']['1'];
		$image = $info['imageinfo'][0];
		$this->assertEquals( [
			// $wgThumbLimits, default + responsive
			40 => [ 'width' => 40, 'height' => 53, 'url' => 'http://example.com/w/thumb.php?f=Portrait-rotated.jpg&width=40' ],
			80 => [ 'width' => 80, 'height' => 107, 'url' => 'http://example.com/w/thumb.php?f=Portrait-rotated.jpg&width=80' ],
			// $wgImageLimits, fit portrait in 32x24
			18 => [ 'width' => 18, 'height' => 24, 'url' => 'http://example.com/w/thumb.php?f=Portrait-rotated.jpg&width=18' ],
		], $image['thumburls'] );
	}

	public function testGetImageInfoThumburlsFromUnionLandscape() {
		$this->overrideConfigValues( [
			MainConfigNames::Server => 'http://example.com',
			MainConfigNames::ThumbnailSteps => null,
			MainConfigNames::ImageLimits => [
				[ 32, 24 ],
				[ 128, 96 ],
				[ 256, 192 ],
			],
			MainConfigNames::ThumbLimits => [
				30,
				40,
				110,
			],
			MainConfigNames::ResponsiveImages => true,
		] );
		$this->mergeMwGlobalArrayValue( 'wgDefaultUserOptions', [ 'thumbsize' => 1 ] );
		RequestContext::getMain()->setUser( $this->getTestUser()->getUser() );
		$this->importFileToTestRepo( self::IMAGES_DIR . '/landscape-plain.jpg', 'Landscape-plain.jpg' );

		[ $result, ] = $this->doApiRequest( [
			'action' => 'query',
			'prop' => 'imageinfo',
			'titles' => 'File:Landscape-plain.jpg',
			'iiprop' => 'thumburls',
		] );

		$info = $result['query']['pages']['1'];
		$image = $info['imageinfo'][0];
		$this->assertEquals( [
			// $wgThumbLimits, default + responsive
			40 => [ 'width' => 40, 'height' => 30, 'url' => 'http://example.com/w/thumb.php?f=Landscape-plain.jpg&width=40' ],
			80 => [ 'width' => 80, 'height' => 60, 'url' => 'http://example.com/w/thumb.php?f=Landscape-plain.jpg&width=80' ],
			// $wgImageLimits
			32 => [ 'width' => 32, 'height' => 24, 'url' => 'http://example.com/w/thumb.php?f=Landscape-plain.jpg&width=32' ],
			128 => [ 'width' => 128, 'height' => 96, 'url' => 'http://example.com/w/thumb.php?f=Landscape-plain.jpg&width=128' ],
		], $image['thumburls'] );
	}

	public function testGetImageCreatedByTempUser() {
		[ $result, ] = $this->doApiRequest( [
			'action' => 'query',
			'prop' => 'imageinfo',
			'titles' => 'File:' . self::IMAGE_2_NAME
		] );
		$image = $result['query']['pages']['-1']['imageinfo'][0];
		$this->assertArrayHasKey( 'temp', $image );
		$this->assertTrue( $image['temp'] );
	}

	public function testGetImageEmptyComment() {
		[ $result, ] = $this->doApiRequest( [
			'action' => 'query',
			'prop' => 'imageinfo',
			'titles' => 'File:' . self::IMAGE_NAME,
			'iiprop' => implode( '|', ApiQueryImageInfo::getPropertyNames() ),
			'iistart' => self::NO_COMMENT_TIMESTAMP,
			'iiend' => self::NO_COMMENT_TIMESTAMP,
		] );
		$image = $this->getImageInfoFromResult( $result );
		$this->assertSame( MWTimestamp::convert( TS::ISO_8601, self::NO_COMMENT_TIMESTAMP ), $image['timestamp'] );
		$this->assertSame( '', $image['comment'] );
		$this->assertArrayNotHasKey( 'commenthidden', $image );
	}

	public function testGetImageInfoOldRestrictedImage() {
		[ $result, ] = $this->doApiRequest( [
				'action' => 'query',
				'prop' => 'imageinfo',
				'titles' => 'File:' . self::IMAGE_NAME,
				'iiprop' => implode( '|', ApiQueryImageInfo::getPropertyNames() ),
				'iistart' => self::OLD_IMAGE_TIMESTAMP,
				'iiend' => self::OLD_IMAGE_TIMESTAMP,
			],
			null,
			false,
			$this->getTestUser()->getAuthority()
		);
		$image = $this->getImageInfoFromResult( $result );
		$this->assertSame( MWTimestamp::convert( TS::ISO_8601, self::OLD_IMAGE_TIMESTAMP ), $image['timestamp'] );
		$this->assertTrue( $image['commenthidden'] );
		$this->assertArrayNotHasKey( "comment", $image );
		$this->assertTrue( $image['userhidden'] );
		$this->assertArrayNotHasKey( 'user', $image );
		$this->assertArrayNotHasKey( 'userid', $image );
		$this->assertTrue( $image['filehidden'] );
		$this->assertSame( self::OLD_IMAGE_SIZE, $image['size'] );
	}

	public function testGetImageInfoOldRestrictedImage_sysop() {
		[ $result, ] = $this->doApiRequest( [
			'action' => 'query',
			'prop' => 'imageinfo',
			'titles' => 'File:' . self::IMAGE_NAME,
			'iiprop' => implode( '|', ApiQueryImageInfo::getPropertyNames() ),
			'iistart' => self::OLD_IMAGE_TIMESTAMP,
			'iiend' => self::OLD_IMAGE_TIMESTAMP,
		],
			null,
			false,
			$this->mockRegisteredUltimateAuthority()
		);
		$image = $this->getImageInfoFromResult( $result );
		$this->assertSame( MWTimestamp::convert( TS::ISO_8601, self::OLD_IMAGE_TIMESTAMP ), $image['timestamp'] );
		$this->assertTrue( $image['commenthidden'] );
		$this->assertSame( 'deleted comment', $image['comment'] );
		$this->assertTrue( $image['userhidden'] );
		$this->assertSame( $this->testUser->getName(), $image['user'] );
		$this->assertSame( $this->testUser->getId(), $image['userid'] );
		$this->assertTrue( $image['filehidden'] );
		$this->assertSame( self::OLD_IMAGE_SIZE, $image['size'] );
	}

	/**
	 * T426802: When an old file revision has a DB record but the storage
	 * blob is missing (empty archive name), timestamp and user should
	 * still be included in the API response alongside filemissing=true.
	 */
	public function testGetImageInfoLostFile() {
		[ $result, ] = $this->doApiRequest( [
			'action' => 'query',
			'prop' => 'imageinfo',
			'titles' => 'File:' . self::IMAGE_NAME,
			'iiprop' => 'timestamp|user|userid|size|comment',
			'iilimit' => 'max',
		] );
		$this->assertArrayHasKey( 'query', $result );
		$this->assertArrayHasKey( 'pages', $result['query'] );
		$info = $result['query']['pages']['-1'];
		$this->assertIsArray( $info['imageinfo'] );

		// Find the entry for the lost file revision by looking for filemissing
		$lostEntry = null;
		foreach ( $info['imageinfo'] as $entry ) {
			if ( isset( $entry['filemissing'] ) ) {
				$lostEntry = $entry;
				break;
			}
		}

		$this->assertNotNull( $lostEntry, 'Should have an entry with filemissing' );
		$this->assertTrue( $lostEntry['filemissing'] );

		// These fields should now be present even though the file blob is missing
		$this->assertArrayHasKey( 'timestamp', $lostEntry,
			'Timestamp should be present for lost files (T426802)' );
		$this->assertSame(
			MWTimestamp::convert( TS::ISO_8601, self::LOST_FILE_TIMESTAMP ),
			$lostEntry['timestamp']
		);
		$this->assertArrayHasKey( 'user', $lostEntry,
			'User should be present for lost files (T426802)' );
		$this->assertSame( $this->testUser->getName(), $lostEntry['user'] );
		$this->assertSame( $this->testUser->getId(), $lostEntry['userid'] );
		$this->assertSame( self::LOST_FILE_SIZE, $lostEntry['size'] );
		$this->assertSame( 'lost file comment', $lostEntry['comment'] );
	}

	/**
	 * T221812: Querying imageinfo for a completely non-existent file
	 * (no DB record, no storage blob) should not crash and should not
	 * return any imageinfo data. Note: this exercises the execute()
	 * early-return path, not getInfo().
	 */
	public function testGetImageInfoNonExistentFile() {
		[ $result, ] = $this->doApiRequest( [
			'action' => 'query',
			'prop' => 'imageinfo',
			'titles' => 'File:This_file_does_not_exist_at_all.png',
			'iiprop' => 'timestamp|user|size|comment',
		] );
		$this->assertArrayHasKey( 'query', $result );
		$this->assertArrayHasKey( 'pages', $result['query'] );
		$page = reset( $result['query']['pages'] );
		// Non-existent file should not have imageinfo at all
		$this->assertArrayNotHasKey( 'imageinfo', $page );
		$this->assertSame( '', $page['imagerepository'] );
	}
}
