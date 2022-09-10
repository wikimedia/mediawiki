<?php

use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;

/**
 * @covers ApiQueryImageInfo
 * @group API
 * @group medium
 * @group Database
 */
class ApiQueryImageInfoTest extends ApiTestCase {
	use MockAuthorityTrait;

	private const IMAGE_NAME = 'Random-11m.png';

	private const OLD_IMAGE_TIMESTAMP = '20201105235241';

	private const NEW_IMAGE_TIMESTAMP = '20201105235242';

	private const OLD_IMAGE_SIZE = 12345;

	private const NEW_IMAGE_SIZE = 54321;

	private const NO_COMMENT_TIMESTAMP = '20201105235239';

	protected function setUp(): void {
		parent::setUp();
		$this->tablesUsed[] = 'image';
		$this->tablesUsed[] = 'oldimage';
	}

	public function addDBData() {
		parent::addDBData();
		$actorId = $this->getServiceContainer()
			->getActorStore()
			->acquireActorId( $this->getTestUser()->getUserIdentity(), $this->db );
		$this->db->insert(
			'image',
			[
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
					->createComment( $this->db, "'''comment'''" )->id,
				'img_actor' => $actorId,
				'img_timestamp' => $this->db->timestamp( self::NEW_IMAGE_TIMESTAMP ),
				'img_sha1' => 'sy02psim0bgdh0jt4vdltuzoh7j80ru',
			]
		);
		$this->db->insert(
			'oldimage',
			[
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
					->createComment( $this->db, 'deleted comment' )->id,
				'oi_actor' => $actorId,
				'oi_timestamp' => $this->db->timestamp( self::OLD_IMAGE_TIMESTAMP ),
				'oi_sha1' => 'sy02psim0bgdh0jt4vdltuzoh7j80ru',
				'oi_deleted' => File::DELETED_FILE | File::DELETED_COMMENT | File::DELETED_USER,
			]
		);
		$this->db->insert(
			'oldimage',
			[
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
					->createComment( $this->db, '' )->id,
				'oi_actor' => $actorId,
				'oi_timestamp' => $this->db->timestamp( self::NO_COMMENT_TIMESTAMP ),
				'oi_sha1' => 'sy02psim0bgdh0jt4vdltuzoh7j80ru',
				'oi_deleted' => 0,
			]
		);
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
		$this->assertSame( 'local', $info['imagerepository'] );
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
		$this->assertSame( MWTimestamp::convert( TS_ISO_8601, self::NEW_IMAGE_TIMESTAMP ), $image['timestamp'] );
		$this->assertSame( "'''comment'''", $image['comment'] );
		$this->assertSame( $this->getTestUser()->getUserIdentity()->getName(), $image['user'] );
		$this->assertSame( $this->getTestUser()->getUserIdentity()->getId(), $image['userid'] );
		$this->assertSame( self::NEW_IMAGE_SIZE, $image['size'] );
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
		$this->assertSame( MWTimestamp::convert( TS_ISO_8601, self::NO_COMMENT_TIMESTAMP ), $image['timestamp'] );
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
		$this->assertSame( MWTimestamp::convert( TS_ISO_8601, self::OLD_IMAGE_TIMESTAMP ), $image['timestamp'] );
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
		$this->assertSame( MWTimestamp::convert( TS_ISO_8601, self::OLD_IMAGE_TIMESTAMP ), $image['timestamp'] );
		$this->assertTrue( $image['commenthidden'] );
		$this->assertSame( 'deleted comment', $image['comment'] );
		$this->assertTrue( $image['userhidden'] );
		$this->assertSame( $this->getTestUser()->getUserIdentity()->getName(), $image['user'] );
		$this->assertSame( $this->getTestUser()->getUserIdentity()->getId(), $image['userid'] );
		$this->assertTrue( $image['filehidden'] );
		$this->assertSame( self::OLD_IMAGE_SIZE, $image['size'] );
	}
}
