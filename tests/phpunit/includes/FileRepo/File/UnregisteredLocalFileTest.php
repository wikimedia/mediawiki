<?php

use MediaWiki\FileRepo\File\UnregisteredLocalFile;
use MediaWiki\Title\Title;

/**
 * @covers \MediaWiki\FileRepo\File\UnregisteredLocalFile
 */
class UnregisteredLocalFileTest extends MediaWikiMediaTestCase {

	private function assertFileProps( UnregisteredLocalFile $file, array $expectedData ): void {
		$fileData = [
			'width' => $file->getWidth(),
			'height' => $file->getHeight(),
			'mimetype' => $file->getMimeType(),
			'bitdepth' => $file->getBitDepth(),
			'metadata' => $file->getMetadataArray(),
			'url' => $file->getURL(),
			'size' => $file->getSize(),
		];
		$this->assertSame( $expectedData, $fileData );
	}

	public function testConstructorInvalid() {
		$this->expectException( BadMethodCallException::class );
		new UnregisteredLocalFile( false, false, false );
	}

	public function testConstructorTitle() {
		$title = Title::makeTitle( NS_FILE, 'bmp-100x100.bmp' );
		$file = new UnregisteredLocalFile( $title, $this->repo, 'mwstore://localtesting/data/bmp-100x100.bmp' );
		$expectedData = [
			'width' => 100,
			'height' => 100,
			'mimetype' => 'image/x-bmp',
			'bitdepth' => 0,
			'metadata' => [],
			'url' => 'http://localhost/thumbtest/5/58/bmp-100x100.bmp',
			'size' => 30138,
		];
		$this->assertFileProps( $file, $expectedData );
	}

	public function testExistingFile() {
		$file = $this->dataFile( 'bmp-100x100.bmp' );
		$expectedData = [
			'width' => 100,
			'height' => 100,
			'mimetype' => 'image/x-bmp',
			'bitdepth' => 0,
			'metadata' => [],
			'url' => 'http://localhost/thumbtest/5/58/bmp-100x100.bmp',
			'size' => 30138,
		];
		$this->assertFileProps( $file, $expectedData );
	}

	/** @dataProvider provideMissingFile */
	public function testMissingFile( $path, $mimeType, $expectedData ) {
		$file = new UnregisteredLocalFile( false, $this->repo, $path, $mimeType );
		$this->assertFileProps( $file, $expectedData );
	}

	public static function provideMissingFile() {
		return [
			'non-existing file with known mime type' => [
				'non-existing.png',
				'image/png',
				[
					'width' => 0,
					'height' => 0,
					'mimetype' => 'image/png',
					'bitdepth' => 0,
					'metadata' => [],
					'url' => 'http://localhost/thumbtest/6/6e/non-existing.png',
					'size' => false,
				],
			],
			'non-existing file without mime type' => [
				'non-existing.png',
				false,
				[
					'width' => 0,
					'height' => 0,
					'mimetype' => 'unknown/unknown',
					'bitdepth' => 0,
					'metadata' => [],
					'url' => 'http://localhost/thumbtest/6/6e/non-existing.png',
					'size' => false,
				],
			],
		];
	}
}
