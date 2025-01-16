<?php

namespace MediaWiki\Tests\Maintenance;

use MigrateFileTables;

/**
 * @covers \MigrateFileTables
 * @group Database
 */
class MigrateFileTablesTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return MigrateFileTables::class;
	}

	public function testMigration() {
		$dbw = $this->getDb();
		$dbw->truncateTable( 'image' );
		$dbw->truncateTable( 'file' );
		$dbw->truncateTable( 'oldimage' );
		$dbw->truncateTable( 'filerevision' );
		$norm = $this->getServiceContainer()->getActorNormalization();
		$user = $this->getTestSysop()->getUserIdentity();
		$actorId = $norm->acquireActorId( $user, $dbw );
		$comment = $this->getServiceContainer()->getCommentStore()->createComment( $dbw, 'comment' );
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

		$dbw->newInsertQueryBuilder()
			->insertInto( 'image' )
			->row( [
				'img_name' => 'Random-11m.png',
				'img_size' => 10816824,
				'img_width' => 1000,
				'img_height' => 1800,
				'img_metadata' => $dbw->encodeBlob( json_encode( $expectedMetaArray ) ),
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
		$dbw->newInsertQueryBuilder()
			->insertInto( 'oldimage' )
			->row( [
				'oi_name' => 'Random-11m.png',
				'oi_size' => 11816824,
				'oi_width' => 1100,
				'oi_height' => 2000,
				'oi_metadata' => $dbw->encodeBlob( json_encode( $expectedMetaArray ) ),
				'oi_bits' => 16,
				'oi_media_type' => 'BITMAP',
				'oi_major_mime' => 'image',
				'oi_minor_mime' => 'png',
				'oi_description_id' => $comment->id,
				'oi_actor' => $actorId,
				'oi_timestamp' => $dbw->timestamp( '20191105235242' ),
				'oi_sha1' => 'sy0212340bgdh0jt4vdltuzoh7j80ru',
				'oi_deleted' => 1,
				'oi_archive_name' => '20191105235242!Random-11m.png'
			] )
			->caller( __METHOD__ )
			->execute();

		$this->maintenance->execute();

		$fileActual = iterator_to_array( $dbw->newSelectQueryBuilder()
			->select( '*' )
			->from( 'file' )
			->join( 'filetypes', null, 'file_type = ft_id ' )
			->join( 'filerevision', null, 'file_latest = fr_id' )
			->caller( __METHOD__ )->fetchResultSet() );
		$this->assertCount( 1, $fileActual );

		// file asserts
		$this->assertSame( 'Random-11m.png', $fileActual[0]->file_name );
		$this->assertSame( 0, intval( $fileActual[0]->file_deleted ) );

		// filetypes asserts
		$this->assertSame( 'BITMAP', $fileActual[0]->ft_media_type );
		$this->assertSame( 'image', $fileActual[0]->ft_major_mime );
		$this->assertSame( 'png', $fileActual[0]->ft_minor_mime );

		// filerevision asserts
		// latest rev
		$this->assertSame( $fileActual[0]->file_id, $fileActual[0]->fr_file );
		$this->assertSame( 10816824, intval( $fileActual[0]->fr_size ) );
		$this->assertSame( 1000, intval( $fileActual[0]->fr_width ) );
		$this->assertSame( 1800, intval( $fileActual[0]->fr_height ) );
		$this->assertSame( json_encode( $expectedMetaArray ), $dbw->decodeBlob( $fileActual[0]->fr_metadata ) );
		$this->assertSame( 16, intval( $fileActual[0]->fr_bits ) );
		$this->assertSame( $comment->id, intval( $fileActual[0]->fr_description_id ) );
		$this->assertSame( $actorId, intval( $fileActual[0]->fr_actor ) );
		$this->assertSame( $dbw->timestamp( '20201105235242' ), $fileActual[0]->fr_timestamp );
		$this->assertSame( 'sy02psim0bgdh0jt4vdltuzoh7j80ru', $fileActual[0]->fr_sha1 );
		$this->assertSame( '', $fileActual[0]->fr_archive_name );
		$this->assertSame( 0, intval( $fileActual[0]->fr_deleted ) );

		// old rev
		$oldFileRevActual = iterator_to_array( $dbw->newSelectQueryBuilder()
			->select( '*' )
			->from( 'filerevision' )
			->orderBy( 'fr_timestamp' )
			->limit( 1 )
			->caller( __METHOD__ )->fetchResultSet() );
		$this->assertSame( $fileActual[0]->file_id, $oldFileRevActual[0]->fr_file );
		$this->assertSame( 11816824, intval( $oldFileRevActual[0]->fr_size ) );
		$this->assertSame( 1100, intval( $oldFileRevActual[0]->fr_width ) );
		$this->assertSame( 2000, intval( $oldFileRevActual[0]->fr_height ) );
		$this->assertSame( json_encode( $expectedMetaArray ), $dbw->decodeBlob( $oldFileRevActual[0]->fr_metadata ) );
		$this->assertSame( 16, intval( $oldFileRevActual[0]->fr_bits ) );
		$this->assertSame( $comment->id, intval( $oldFileRevActual[0]->fr_description_id ) );
		$this->assertSame( $actorId, intval( $oldFileRevActual[0]->fr_actor ) );
		$this->assertSame( $dbw->timestamp( '20191105235242' ), $oldFileRevActual[0]->fr_timestamp );
		$this->assertSame( 'sy0212340bgdh0jt4vdltuzoh7j80ru', $oldFileRevActual[0]->fr_sha1 );
		$this->assertSame( '20191105235242!Random-11m.png', $oldFileRevActual[0]->fr_archive_name );
		$this->assertSame( 1, intval( $oldFileRevActual[0]->fr_deleted ) );
	}
}
