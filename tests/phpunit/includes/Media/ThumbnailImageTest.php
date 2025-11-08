<?php

use MediaWiki\FileRepo\File\UnregisteredLocalFile;
use MediaWiki\FileRepo\FileRepo;
use Wikimedia\FileBackend\FileBackend;

/**
 * @group Media
 * @group medium
 *
 * @covers \ThumbnailImage
 * @covers \MediaTransformOutput
 */
class ThumbnailImageTest extends MediaWikiMediaTestCase {

	private function newFile( $name = 'Test.jpg' ) {
		return $this->dataFile( $name );
	}

	private function newThumbnail( $file = null, $url = null, $path = false, $parameters = [] ) {
		$file ??= $this->newFile();
		$path ??= 'thumb/a/ab/Test.jpg/123px-Test.jpg';
		$url ??= "https://example.com/w/images/$path";

		$parameters += [
			'width' => 200,
			'height' => 100,
		];

		return new ThumbnailImage( $file, $url, $path, $parameters );
	}

	public function testConstructor() {
		$file = $this->newFile();
		$path = 'thumb/a/ab/Test.jpg/123px-Test.jpg';
		$url = "https://example.com/w/images/$path";

		$parameters = [
			'width' => 300,
			'height' => 200,
		];

		$thumbnail = $this->newThumbnail(
			$file,
			$url,
			$path,
			$parameters
		);

		$this->assertSame( $file, $thumbnail->getFile() );
		$this->assertSame( $url, $thumbnail->getUrl() );
		$this->assertSame( $parameters['width'], $thumbnail->getWidth() );
		$this->assertSame( $parameters['height'], $thumbnail->getHeight() );
		$this->assertFalse( $thumbnail->isError() );
	}

	/**
	 * Check that we can stream data from a file system path
	 */
	public function testStreamFileWithStatus_fsPath() {
		$fsPath = $this->getFilePath() . 'test.jpg';
		$data = file_get_contents( $fsPath );
		$file = $this->newFile();

		// NOTE: We need the FileRepo in $file for the streamer option,
		// to prevent a real reset of the output buffer.
		$thumbnail = $this->newThumbnail( $file, null, $fsPath );

		ob_start();
		$thumbnail->streamFileWithStatus();
		$output = ob_get_clean();

		$this->assertSame( $data, $output );
	}

	/**
	 * Check that we can stream using the FileBackend
	 */
	public function testStreamFileWithStatus_thumbStoragePath() {
		$this->backend = $this->createNoOpMock( FileBackend::class, [ 'streamFile' ] );

		$this->backend->expects( $this->once() )
			->method( 'streamFile' )
			->wilLreturn( StatusValue::newGood() );

		$this->repo = new FileRepo( $this->getRepoOptions() );

		$file = $this->newFile( 'test.jpg' );
		$thumbnail = $this->newThumbnail(
			$file,
			$file->getThumbUrl(),
			$file->getThumbPath()
		);

		$thumbnail->streamFileWithStatus();

		// no assertion needed, we just expect streamFile() to be called.
	}

	/**
	 * Check that we don't explode if no file repo is known
	 */
	public function testStreamFileWithStatus_UnregisteredLocalFile() {
		// Use a non-existing file, so streaming will fail.
		// If streaming was successful, we'd generate real output, since
		// without a file backend, we have no way to disable a full reset
		// of output buffers.
		$fsPath = $this->getFilePath() . 'this does not exist';

		// No file repo or backend!
		$file = new UnregisteredLocalFile( false, false, $fsPath );
		$thumbnail = $this->newThumbnail( $file );

		// Check that streaming fails gracefully
		$status = $thumbnail->streamFileWithStatus();
		$this->assertStatusError( 'backend-fail-stream', $status );
	}

}
