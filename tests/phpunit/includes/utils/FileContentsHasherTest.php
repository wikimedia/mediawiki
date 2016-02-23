<?php

/**
 * @covers FileContentsHasherTest
 */
class FileContentsHasherTest extends MediaWikiTestCase {

	public function provideSingleFile() {
		return array_map( function ( $file ) {
			return [ $file, file_get_contents( $file ) ];
		}, glob( __DIR__ . '/../../data/filecontentshasher/*.*' ) );
	}

	public function provideMultipleFiles() {
		return [
			[ $this->provideSingleFile() ]
		];
	}

	/**
	 * @covers FileContentsHasher::getFileContentsHash
	 * @covers FileContentsHasher::getFileContentsHashInternal
	 * @dataProvider provideSingleFile
	 */
	public function testSingleFileHash( $fileName, $contents ) {
		foreach ( [ 'md4', 'md5' ] as $algo ) {
			$expectedHash = hash( $algo, $contents );
			$actualHash = FileContentsHasher::getFileContentsHash( $fileName, $algo );
			$this->assertEquals( $expectedHash, $actualHash );
			$actualHashRepeat = FileContentsHasher::getFileContentsHash( $fileName, $algo );
			$this->assertEquals( $expectedHash, $actualHashRepeat );
		}
	}

	/**
	 * @covers FileContentsHasher::getFileContentsHash
	 * @covers FileContentsHasher::getFileContentsHashInternal
	 * @dataProvider provideMultipleFiles
	 */
	public function testMultipleFileHash( $files ) {
		$fileNames = [];
		$hashes = [];
		foreach ( $files as $fileInfo ) {
			list( $fileName, $contents ) = $fileInfo;
			$fileNames[] = $fileName;
			$hashes[] = md5( $contents );
		}

		$expectedHash = md5( implode( '', $hashes ) );
		$actualHash = FileContentsHasher::getFileContentsHash( $fileNames, 'md5' );
		$this->assertEquals( $expectedHash, $actualHash );
		$actualHashRepeat = FileContentsHasher::getFileContentsHash( $fileNames, 'md5' );
		$this->assertEquals( $expectedHash, $actualHashRepeat );
	}
}
