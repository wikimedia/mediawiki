<?php

namespace MediaWiki\Tests\Maintenance;

use CheckImages;
use File;
use LocalRepo;
use RepoGroup;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \CheckImages
 * @group Database
 * @author Dreamy Jazz
 */
class CheckImagesTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return CheckImages::class;
	}

	public function testWhenImageIsNotLocallyAccessible() {
		// Mock that all File objects obtained from the local repo does not have a path.
		$mockFile = $this->createMock( File::class );
		$mockFile->method( 'getPath' )
			->willReturn( false );
		$mockFileRepo = $this->createMock( LocalRepo::class );
		$mockFileRepo->method( 'newFileFromRow' )
			->willReturn( $mockFile );
		$mockRepoGroup = $this->createMock( RepoGroup::class );
		$mockRepoGroup->method( 'getLocalRepo' )
			->willReturn( $mockFileRepo );
		$this->setService( 'RepoGroup', $mockRepoGroup );

		$this->maintenance->execute();
		$output = $this->getActualOutputForAssertion();
		$this->assertStringContainsString( 'Random-112m.png: not locally accessible', $output );
		$this->assertStringContainsString( 'Random-13m.png: not locally accessible', $output );
		$this->assertStringContainsString( 'Good images: 0/2', $output );
	}

	public function testWhenAllImagesAreMissing() {
		$this->maintenance->execute();
		$output = $this->getActualOutputForAssertion();
		$this->assertStringContainsString( 'Random-112m.png: missing', $output );
		$this->assertStringContainsString( 'Random-13m.png: missing', $output );
		$this->assertStringContainsString( 'Good images: 0/2', $output );
	}

	/**
	 * Creates a mock {@link LocalRepo} instance to be returned by the {@link RepoGroup} service
	 * that returns the specified integer from {@link LocalRepo::getFileSize}.
	 *
	 * @param int $mockFileSizeForAllFiles
	 * @return void
	 */
	private function mockLocalRepoWithDefinedFileSize( int $mockFileSizeForAllFiles ): void {
		$realLocalRepo = $this->getServiceContainer()->getRepoGroup()->getLocalRepo();
		$mockFileRepo = $this->createMock( LocalRepo::class );
		$mockFileRepo->method( 'getFileSize' )
			->willReturn( $mockFileSizeForAllFiles );
		$mockFileRepo->method( 'newFileFromRow' )
			->willReturnCallback( static function ( $row ) use ( $realLocalRepo ) {
				return $realLocalRepo->newFileFromRow( $row );
			} );
		$mockRepoGroup = $this->createMock( RepoGroup::class );
		$mockRepoGroup->method( 'getLocalRepo' )
			->willReturn( $mockFileRepo );
		$this->setService( 'RepoGroup', $mockRepoGroup );
	}

	public function testWhenFileSizeIsZero() {
		$this->mockLocalRepoWithDefinedFileSize( 0 );

		$this->maintenance->execute();
		$output = $this->getActualOutputForAssertion();
		$this->assertStringContainsString( 'Random-112m.png: truncated, was 12345', $output );
		$this->assertStringContainsString( 'Random-13m.png: truncated, was 54321', $output );
		$this->assertStringContainsString( 'Good images: 0/2', $output );
	}

	public function testWhenFileSizeDoesNotMatchSizeInDB() {
		// Mock that all files have a size which does not match any size in the DB
		$this->mockLocalRepoWithDefinedFileSize( 123 );

		$this->maintenance->execute();
		$output = $this->getActualOutputForAssertion();
		$this->assertStringContainsString( 'Random-112m.png: size mismatch DB=12345, actual=123', $output );
		$this->assertStringContainsString( 'Random-13m.png: size mismatch DB=54321, actual=123', $output );
		$this->assertStringContainsString( 'Good images: 0/2', $output );
	}

	public function testWhenOneFileSizeMatches() {
		// Mock that all files have the size that matches the size in the DB for Random-112m.png
		$this->mockLocalRepoWithDefinedFileSize( 12345 );

		// To prevent IDE errors, call the ::setBatchSize method when it has been explicitly documented as having the
		// TestingAccessWrapper type.
		/** @var TestingAccessWrapper $maintenance */
		$maintenance = $this->maintenance;
		$maintenance->setBatchSize( 1 );
		$maintenance->execute();

		$output = $this->getActualOutputForAssertion();
		$this->assertStringNotContainsString( 'Random-112m.png', $output );
		$this->assertStringContainsString( 'Random-13m.png: size mismatch DB=54321, actual=123', $output );
		$this->assertStringContainsString( 'Good images: 1/2', $output );
	}

	public function addDBDataOnce() {
		// Add some testing rows to the image table to simulate two images existing.
		$testUser = $this->getTestUser()->getUser();
		$commentId = $this->getServiceContainer()->getCommentStore()
			->createComment( $this->getDb(), 'test' )->id;
		$this->getDb()->newInsertQueryBuilder()
			->insertInto( 'image' )
			->rows( [
				[
					'img_name' => 'Random-13m.png',
					'img_size' => 54321,
					'img_width' => 1000,
					'img_height' => 1800,
					'img_metadata' => '',
					'img_bits' => 16,
					'img_media_type' => MEDIATYPE_BITMAP,
					'img_major_mime' => 'image',
					'img_minor_mime' => 'png',
					'img_description_id' => $commentId,
					'img_actor' => $testUser->getActorId(),
					'img_timestamp' => $this->getDb()->timestamp( '20201105234242' ),
					'img_sha1' => 'sy02psim0bgdh0jt4vdltuzoh7j80yu',
				],
				[
					'img_name' => 'Random-112m.png',
					'img_size' => 12345,
					'img_width' => 1000,
					'img_height' => 1800,
					'img_metadata' => '',
					'img_bits' => 16,
					'img_media_type' => MEDIATYPE_BITMAP,
					'img_major_mime' => 'image',
					'img_minor_mime' => 'png',
					'img_description_id' => $commentId,
					'img_actor' => $testUser->getActorId(),
					'img_timestamp' => $this->getDb()->timestamp( '20201105235242' ),
					'img_sha1' => 'sy02psim0bgdh0jt4vdltuzoh7j80ru',
				],
			] )
			->caller( __METHOD__ )
			->execute();
	}
}
